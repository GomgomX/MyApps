<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Classes\Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller {
    public function index() {
        $lastPosts = Forum::select('players.name', 'z_forum.post_date', 'z_forum.section')->join('players', 'players.id', '=', 'z_forum.author_guid')->whereIn('z_forum.section', array_keys(config('custom.forum_sections')))->orderBy('z_forum.post_date', 'DESC')->get();

        $counters = [];
        $info = Forum::select('section', DB::raw('COUNT(id) as threads'), DB::raw('SUM(replies) as replies'))->whereColumn('first_post', 'id');
        
        $loggedInAccount = auth()->guard('account')->user();
        if(($loggedInAccount && $loggedInAccount->page_access < config('custom.access_admin_panel')) || !$loggedInAccount) {
            $info->where(function ($query) {
                $query->where('z_forum.section', '<>', 1)->orWhereNotIn('z_forum.post_topic', ['News Ticker', 'Featured Article']);
            });
        }
        
        $info = $info->groupBy('section')->get();
        foreach($info as $data) {
            $counters[$data->section] = ['threads' => $data->threads, 'posts' => $data->replies + $data->threads];
        }
        
        return view('forum.index', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'lastPosts' => $lastPosts, 'counters' => $counters]);
    }

    public function showBoard(Request $request, $id) {
        $id = (int) $id;
        if(!array_key_exists($id, config('custom.forum_sections'))) {
            return redirect('/forum/communityboards')->withErrors('Invalid section.');
        }

        $last_threads = Forum::select('players.name', 'z_forum.*')->join('players', 'players.id', '=', 'z_forum.author_guid')->where('z_forum.section', $id)->whereColumn('z_forum.first_post', 'z_forum.id');
        
        $loggedInAccount = auth()->guard('account')->user();
        if(($loggedInAccount && $loggedInAccount->page_access < config('custom.access_admin_panel')) || !$loggedInAccount) {
            $last_threads->where(function ($query) {
                $query->where('z_forum.section', '<>', 1)->orWhereNotIn('z_forum.post_topic', ['News Ticker', 'Featured Article']);
            });
        }

        $last_threads = $last_threads->orderBy('z_forum.last_post', 'DESC')->paginate(config('custom.forum_threads_per_page'));
       
        $last_posts = Forum::select('players.name', 'z_forum.first_post', 'z_forum.post_date')->join('players', 'players.id', '=', 'z_forum.author_guid')->whereIn('z_forum.first_post', array_values($last_threads->pluck('id')->toArray()))->orderBy('z_forum.post_date', 'DESC')->get();
        return view('forum.board', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'last_threads' => $last_threads, 'last_posts' => $last_posts, 'section_id' => $id]);
    }

    public function showThread(Request $request, $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid thread.',
            'id.numeric' => 'Invalid thread.'
        ]);

        if($validator->fails()) {
            return redirect('/forum/communityboards')->withErrors($validator);
        }
        
        $current_thread = Forum::select('players.name', 'z_forum.post_topic', 'z_forum.section')->join('players', 'players.id', '=', 'z_forum.author_guid')->where('z_forum.first_post', $id)->whereColumn('z_forum.first_post', 'z_forum.id')->first(); 
        $loggedInAccount = auth()->guard('account')->user();
        if(!$current_thread || ((($loggedInAccount && $loggedInAccount->page_access < config('custom.access_admin_panel')) || !$loggedInAccount) && $current_thread->section == 1 && in_array($current_thread->post_topic, array('News Ticker', 'Featured Article')))) {
            return redirect('/forum/communityboards')->withErrors("Thread with this ID does not exits.");
        }

        $threads = Forum::select('players.id', 'players.name', 'players.account_id', 'players.vocation', 'players.promotion', 'players.level', 'players.rank_id', 'z_forum.*')->join('players', 'players.id', '=', 'z_forum.author_guid')->where('z_forum.first_post', $id)->orderBy('z_forum.post_date')->with('getPlayer', 'getPlayer.getRank', 'getPlayer.getRank.getGuild')->paginate(config('custom.forum_posts_per_page')); 
        
        if($request->has('page') && $request->page > $threads->lastPage()) {
            return redirect()->route('forum.thread', ['id' => $id, 'page' => $threads->lastPage()]);
        }

        if(isset($threads[0]['name'])) {
            Forum::where('id', $id)->increment('views', 1);
        }

        $postCounter = Forum::select('author_aid', DB::raw('COUNT(id) as posts'))->whereIn('author_aid', array_values($threads->pluck('account_id')->unique()->toArray()))->groupBy('author_aid')->get();
        return view('forum.thread', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'thread_id' => $id, 'current_thread' => $current_thread, 'threads' => $threads, 'postCounter' => $postCounter]);
    }

    public function newTopic(Request $request, $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid thread.',
            'id.numeric' => 'Invalid thread.'
        ]);

        if($validator->fails()) {
            return redirect('/forum/communityboards')->withErrors($validator);
        }

        if(!array_key_exists($id, config('custom.forum_sections'))) {
            return redirect('/forum/communityboards')->withErrors("Board with ID ".$id." doesn't exist.");
        }

        $loggedInAccount = auth()->guard('account')->user();
        if(!(Functions::canPost($loggedInAccount) || $loggedInAccount->page_access >= config('custom.access_admin_panel'))) {
            return redirect('/forum/communityboards/board/'.$id)->withErrors('Your account is banned or you don\'t have any character with level '.config('custom.forum_level_limit').' on your account. You can\'t post.');
        }

        $request->session()->put('saveTopic', true);

        if($id == 1 && $loggedInAccount->page_access < config('custom.access_admin_panel')) {
            $validator->errors()->add('', 'Only moderators and admins can post on news board.');
            return view('forum.newtopic', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'section_id' => $id, 'errors' => $validator->errors()]);
        }

        return view('forum.newtopic', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'section_id' => $id]);
    }

    public function storeNewTopic(Request $request) {
        $id = (int) $request->section_id;
        if(!array_key_exists($id, config('custom.forum_sections'))) {
            return redirect('/forum/communityboards')->withErrors("Board with ID ".$id." doesn't exist.");
        }
        
        if(!($request->session()->has('saveTopic') && $request->session()->get('saveTopic'))) {
            return redirect('/forum/communityboards/board/'.$id);
        }

        $loggedInAccount = auth()->guard('account')->user();
        if(!(Functions::canPost($loggedInAccount) || $loggedInAccount->page_access >= config('custom.access_admin_panel'))) {
            return redirect('/forum/communityboards/board/'.$id)->withErrors('Your account is banned or you don\'t have any character with level '.config('custom.forum_level_limit').' on your account. You can\'t post.');
        }

        if($id == 1 && $loggedInAccount->page_access < config('custom.access_admin_panel')) {
            return back()->withErrors('Only moderators and admins can post on news board.');
        }

        $text = trim(Functions::codeLower($request->text));
        $topic = trim($request->topic);

        $validator = Validator::make(['char_id' => $request->char_id, 'text' => $text, 'topic' => $topic], [
            'char_id' => ['required', 'numeric', 'min:1'],
            'text' => 'required|string',
            'topic' => ['required', 'string']
        ], [
            'char_id.required' => 'Please select a character.',
            'char_id.numeric' => 'Please select a character.',
            'char_id.min' => 'Please select a character.',
            'text.required' => 'Please enter a message.',
            'text.string' => 'Invalid message.',
            'topic.required' => 'Please type a topic.',
            'topic.string' => 'Invalid topic.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tLength = 0;
        $topicLength = strlen($topic);
        for($i = 0; $i < $topicLength; $i++)
        {
            if(ord($topic[$i]) >= 33 && ord($topic[$i]) <= 126)
                $tLength++;
        }

        if($tLength < 5 || $topicLength > 50)
            return back()->withErrors("Too short or too long topic (short: ".$tLength." long: ".$topicLength." letters). Minimum 5 letters, maximum 50 letters.")->withInput();

        $length = 0;
        $textLength = strlen($text);
        for($i = 0; $i < $textLength; $i++)
        {
            if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                $length++;
        }

        if($length < 5 || $textLength > 10000)
            return back()->withErrors("Too short or too long post (short: ".$length." long: ".$textLength." letters). Minimum 5 letters, maximum 15000 letters.")->withInput();

        $player_on_account = false;
        foreach($loggedInAccount->getPlayers as $player) {
            if($request->char_id == $player->id) {
                $player_on_account = true;
                break;
            }
        }

        if(!$player_on_account) {
            return back()->withErrors("Player with selected ID ".$request->char_id." doesn't exist or isn't on your account.")->withInput();
        }

        $last_post = $loggedInAccount->last_post;
        if($last_post+config('custom.forum_post_interval')-time() > 0 && $loggedInAccount->page_access < config('custom.access_admin_panel')) {
            return back()->withErrors("You can post one time per ".config('custom.forum_post_interval')." seconds. Next post after ".($last_post+config('custom.forum_post_interval')-time())." second(s).")->withInput();
        }

        $loggedInAccount->last_post = time();
        $loggedInAccount->save();

        $thread = new Forum;
        $thread->first_post = 0;
        $thread->last_post = time();
        $thread->section = $id;
        $thread->replies = 0;
        $thread->views = 0;
        $thread->author_aid = $loggedInAccount->id;
        $thread->author_guid = (int) $request->char_id;
        $thread->post_text = $text;
        $thread->post_topic = $topic;
        $thread->post_smile = (int) $request->smile;
        $thread->post_date = time();
        $thread->last_edit_aid = 0;
        $thread->edit_date = 0;
        $thread->post_ip = $request->ip();
        $thread->save();

        $thread->first_post = $thread->id;
        $thread->save();

        $request->session()->forget('saveTopic');

        return redirect('/forum/communityboards/thread/'.$thread->id)->with('message', 'Thread add!');
    }

    public function newPost(Request $request, $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid thread.',
            'id.numeric' => 'Invalid thread.'
        ]);

        if($validator->fails()) {
            return redirect('/forum/communityboards')->withErrors($validator);
        }

        $thread = Forum::select('post_topic', 'id', 'section')->where('id', $id)->where('first_post', $id)->first();
        if(!$thread) {
            return redirect('/forum/communityboards')->withErrors("Thread with ID ".$id." doesn't exist.");
        }
        
        $loggedInAccount = auth()->guard('account')->user();
        if(!(Functions::canPost($loggedInAccount) || $loggedInAccount->page_access >= config('custom.access_admin_panel'))) {
            return redirect('/forum/communityboards/thread/'.$thread->id)->withErrors('Your account is banned or you don\'t have any character with level '.config('custom.forum_level_limit').' on your account. You can\'t post.');
        }

        $threads = Forum::select('players.name',  'z_forum.post_text', 'z_forum.post_topic', 'z_forum.post_smile')->join('players', 'players.id', '=', 'z_forum.author_guid')->where('first_post', $thread->id)->orderBy('post_date', 'DESC')->take(config('custom.forum_last_posts'))->get();

        $text = '';
        if($request->has('quote'))
        {
            $quoted_post = Forum::select('players.name',  'z_forum.post_text', 'z_forum.post_date')->join('players', 'players.id', '=', 'z_forum.author_guid')->where('z_forum.id', (int) $request->quote)->get();
            if(isset($quoted_post[0]['name']))
                $text = '[i]Originally posted by [player]'.$quoted_post[0]['name'].'[/player] on [s]'.date('d.m.y H:i:s', $quoted_post[0]['post_date']).'[/s]:[/i]
[quote]'.$quoted_post[0]['post_text'].'[/quote]
';
        }

        $request->session()->put('savePost', true);

        return view('forum.newpost', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'thread' => $thread, 'threads' => $threads, 'text' => $text]);
    }

    public function storeNewPost(Request $request) {
        $id = (int) $request->thread_id;
        $thread = Forum::select('post_topic', 'id', 'section')->where('id', $id)->where('first_post', $id)->first();
        if(!$thread) {
            return redirect('/forum/communityboards')->withErrors("Thread with ID ".$id." doesn't exist.");
        }
        
        if(!($request->session()->has('savePost') && $request->session()->get('savePost'))) {
            return redirect('/forum/communityboards/thread/'.$thread->id);
        }

        $loggedInAccount = auth()->guard('account')->user();
        if(!(Functions::canPost($loggedInAccount) || $loggedInAccount->page_access >= config('custom.access_admin_panel'))) {
            return redirect('/forum/communityboards/thread/'.$thread->id)->withErrors('Your account is banned or you don\'t have any character with level '.config('custom.forum_level_limit').' on your account. You can\'t post.');
        }

        $text = trim(Functions::codeLower($request->text));
        $validation = [
            'char_id' => ['required', 'numeric', 'min:1'],
            'text' => 'required|string'
        ];

        if($request->has('topic') && !empty($request->topic)) {
            $validation['topic'] = 'string';
            $request['topic'] = trim($request->topic);
        } else {
            $request['topic'] = '';
        }

        $validator = Validator::make(['char_id' => $request->char_id, 'text' => $text, 'topic' => $request->topic], $validation, [
            'char_id.required' => 'Please select a character.',
            'char_id.numeric' => 'Please select a character.',
            'char_id.min' => 'Please select a character.',
            'text.required' => 'Please enter a message.',
            'text.string' => 'Invalid message.',
            'topic.string' => 'Invalid topic.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if(!empty($request->topic)) {
            $tLength = 0;
            $topicLength = strlen($request->topic);
            for($i = 0; $i < $topicLength; $i++)
            {
                if(ord($request->topic[$i]) >= 33 && ord($request->topic[$i]) <= 126)
                    $tLength++;
            }

            if($tLength < 5 || $topicLength > 50)
                return back()->withErrors("Too short or too long topic (short: ".$tLength." long: ".$topicLength." letters). Minimum 5 letters, maximum 50 letters.")->withInput();
        }

        $length = 0;
        $textLength = strlen($text);
        for($i = 0; $i < $textLength; $i++)
        {
            if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                $length++;
        }

        if($length < 5 || $textLength > 10000)
            return back()->withErrors("Too short or too long post (short: ".$length." long: ".$textLength." letters). Minimum 5 letters, maximum 15000 letters.")->withInput();

        $player_on_account = false;
        foreach($loggedInAccount->getPlayers as $player) {
            if($request->char_id == $player->id) {
                $player_on_account = true;
                break;
            }
        }

        if(!$player_on_account) {
            return back()->withErrors("Player with selected ID ".$request->char_id." doesn't exist or isn't on your account.")->withInput();
        }

        $last_post = $loggedInAccount->last_post;
        if($last_post+config('custom.forum_post_interval')-time() > 0 && $loggedInAccount->page_access < config('custom.access_admin_panel')) {
            return back()->withErrors("You can post one time per ".config('custom.forum_post_interval')." seconds. Next post after ".($last_post+config('custom.forum_post_interval')-time())." second(s).")->withInput();
        }

        $loggedInAccount->last_post = time();
        $loggedInAccount->save();
        
        $values = [
            'first_post' => $thread->id, 
            'last_post' => 0,
            'section' => $thread->section,
            'replies' => 0,
            'views' => 0,
            'author_aid' => $loggedInAccount->id,
            'author_guid' => (int) $request->char_id,
            'post_text' => $text,
            'post_topic' => $request->topic,
            'post_smile' => (int) $request->smile,
            'post_date' => time(),
            'last_edit_aid' => 0,
            'edit_date' => 0,
            'post_ip' => $request->ip()
        ];

        Forum::insert($values);
        Forum::where('id', $thread->id)->increment('replies', 1, ['last_post' => time()]);

        $request->session()->forget('savePost');

        $params = ['id' => $thread->id];
        $pages = Forum::where('z_forum.first_post', $thread->id)->paginate(config('custom.forum_posts_per_page'))->lastPage();
        if($pages > 1) {
            $params['page'] = $pages;
        }
        return redirect()->route('forum.thread', $params)->with('message', 'Post added!');
    }

    public function edit(Request $request, $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid thread.',
            'id.numeric' => 'Invalid thread.'
        ]);

        if($validator->fails()) {
            return redirect('/forum/communityboards')->withErrors($validator);
        }

        $post = Forum::where('id', $id)->first();
        if(!$post) {
            return redirect('/forum/communityboards')->withErrors("Thread or post with ID ".$id." doesn't exist.");
        }
        
        $loggedInAccount = auth()->guard('account')->user();
        if(!(Functions::canPost($loggedInAccount) || $loggedInAccount->page_access >= config('custom.access_admin_panel'))) {
            return redirect('/forum/communityboards/thread/'.$post->first_post)->withErrors('Your account is banned or you don\'t have any character with level '.config('custom.forum_level_limit').' on your account. You can\'t post.');
        }

        if(!($loggedInAccount->id == $post['author_aid'] || $loggedInAccount->page_access >= config('custom.access_admin_panel')))
        {
            return redirect('/forum/communityboards/thread/'.$post->first_post)->withErrors('You are not the author of this '.($post->id == $post->first_post ? 'thread' : 'post').'.');
        }

        $request->session()->put('edit', true);
        if($request->has('page')) {
            $request->session()->put('page', $request->page);
        }

        return view('forum.edit', ['pageTitle' => 'Community Boards', 'subtopic' => 'communityboards', 'post' => $post, 'first_post' => Forum::select('post_topic')->where('id', $post['first_post'])->first()]);
    }

    public function save(Request $request) {
        $id = (int) $request->id;
        $post = Forum::where('id', $id)->first();
        if(!$post) {
            return redirect('/forum/communityboards')->withErrors("Thread or post with ID ".$id." doesn't exist.");
        }

        if(!($request->session()->has('edit') && $request->session()->get('edit'))) {
            return redirect('/forum/communityboards/thread/'.$post->first_post);
        }

        $loggedInAccount = auth()->guard('account')->user();
        if(!(Functions::canPost($loggedInAccount) || $loggedInAccount->page_access >= config('custom.access_admin_panel'))) {
            return redirect('/forum/communityboards/thread/'.$post->first_post)->withErrors('Your account is banned or you don\'t have any character with level '.config('custom.forum_level_limit').' on your account. You can\'t post.');
        }
        
        if(!($loggedInAccount->id == $post['author_aid'] || $loggedInAccount->page_access >= config('custom.access_admin_panel')))
        {
            return redirect('/forum/communityboards/thread/'.$post->first_post)->withErrors('You are not the author of this '.($post->id == $post->first_post ? 'thread' : 'post').'.');
        }

        $text = trim(Functions::codeLower($request->text));
        $validation = [
            'char_id' => ['required', 'numeric', 'min:1'],
            'text' => 'required|string'
        ];

        if($post['id'] == $post['first_post']) {
            $validation['topic'] = 'required|string';
            $request['topic'] = trim($request->topic);
        } elseif($request->has('topic') && !empty($request->topic)) {
            $validation['topic'] = 'string';
            $request['topic'] = trim($request->topic);
        } else {
            $request['topic'] = '';
        }

        $validator = Validator::make(['char_id' => $request->char_id, 'text' => $text, 'topic' => $request->topic], $validation, [
            'char_id.required' => 'A character has to be selected.',
            'char_id.numeric' => 'A character has to be selected.',
            'char_id.min' => 'A character has to be selected.',
            'text.required' => 'Message can\'t be empty.',
            'text.string' => 'Invalid message.',
            'topic.required' => 'Topic can\'t be empty.',
            'topic.string' => 'Invalid topic.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($post['id'] == $post['first_post'] || !empty($request->topic)) {
            $tLength = 0;
            $topicLength = strlen($request->topic);
            for($i = 0; $i < $topicLength; $i++)
            {
                if(ord($request->topic[$i]) >= 33 && ord($request->topic[$i]) <= 126)
                    $tLength++;
            }

            if($tLength < 5 || $topicLength > 50)
                return back()->withErrors("Too short or too long topic (short: ".$tLength." long: ".$topicLength." letters). Minimum 5 letters, maximum 50 letters.")->withInput();
        }

        $length = 0;
        $textLength = strlen($text);
        for($i = 0; $i < $textLength; $i++)
        {
            if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                $length++;
        }

        if($length < 5 || $textLength > 10000)
            return back()->withErrors("Too short or too long post (short: ".$length." long: ".$textLength." letters). Minimum 5 letters, maximum 15000 letters.")->withInput();

        $player_on_account = false;
        foreach($loggedInAccount->getPlayers as $player) {
            if($request->char_id == $player->id) {
                $player_on_account = true;
                break;
            }
        }

        if(!$player_on_account) {
            return back()->withErrors("Player with selected ID ".$request->char_id." doesn't exist or isn't on your account.")->withInput();
        }

        if($loggedInAccount->id != $post['author_aid']) {
            $request['char_id'] = $post['author_guid'];
        }

        $post->author_guid = (int) $request->char_id;
        $post->post_text = $text;
        $post->post_topic = $request->topic;
        $post->post_smile = (int) $request->smile;
        $post->last_edit_aid = $loggedInAccount->id;
        $post->edit_date = time();
        $post->save();

        $request->session()->forget('edit');

        if($post['id'] == $post['first_post']) {
            return redirect()->route('forum.thread', ['id' => $post->first_post])->with('message', 'Thread edited!');
        } else {
            $params = ['id' => $post->first_post];
            $page = $request->session()->get('page');
            if($page) {
                $params['page'] = $page;
                $request->session()->forget('page');
            }
            return redirect()->route('forum.thread', $params)->with('message', 'Post edited!');
        }
    }

    public function deletePost($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid thread.',
            'id.numeric' => 'Invalid thread.'
        ]);

        if($validator->fails()) {
            return redirect('/forum/communityboards')->withErrors($validator);
        }

        if(auth()->guard('account')->user()->page_access < config('custom.access_admin_panel'))
        {
            return redirect('/forum/communityboards')->withErrors("You are not a moderator.");
        }

        $post = Forum::select('id', 'first_post', 'section')->where('id', $id)->first();
        if(!$post) {
            return redirect('/forum/communityboards')->withErrors("Thread or post with ID ".$id." does not exist.");
        }

        $post->delete();
        if($post['id'] == $post['first_post'])
        {
            return redirect('/forum/communityboards/board/'.$post['section'])->with('message', 'Thread removed!');
        }
        else
        {
            Forum::where('id', $post['first_post'])->increment('replies', -1);
            $params = ['id' => $post['first_post']];
            $pages = Forum::where('z_forum.first_post', $post['first_post'])->paginate(config('custom.forum_posts_per_page'))->lastPage();
            if($pages > 1) {
                $params['page'] = $pages;
            }
            return redirect()->route('forum.thread', $params)->with('message', 'Post removed!');
        }
    }
}