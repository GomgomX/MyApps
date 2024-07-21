<x-layout :$pageTitle :$subtopic>
    @if($errors->any())
        <font color="red" size="2"><b>Errors occured:</b>
        @foreach($errors->all() as $error)
            <br />* {!!$error!!}
        @endforeach
        </font><br /><br />
    @else
        @if(session('message'))
            <font color="green" size="2"><b>Information:</b><br />* {!!session('message')!!}</font><br /><br />
        @endif
    @endif
 
    <a href="/forum/communityboards">Boards</a> >> <a href="/forum/communityboards/board/{{$threads[0]['section']}}">{{config('custom.forum_sections')[$threads[0]['section']]}}</a> >> <b>{{htmlspecialchars($current_thread['post_topic'])}}</b>
    <br /><br /><a href="/forum/communityboards/newpost/{{$thread_id}}">
    <img src="{{asset('images')}}/forum/post.gif" border="0" /></a><br />
    {{$threads->links()}}<br />{!!($threads->lastPage() > 1 ? '<br/>' : '')!!}
    <table width="100%"><tr bgcolor="{{config('custom.lightborder')}}" width="100%"><td colspan="2"><font size="4"><b>{{htmlspecialchars($current_thread['post_topic'])}}</b></font><font size="1"><br />by <a href="/community/characters/{{rawurlencode($current_thread['name'])}}">{{htmlspecialchars($current_thread['name'])}}</a></font></td></tr>
    <tr bgcolor="{{config('custom.vdarkborder')}}"><td width="200"><font color="white" size="1"><b>Author</b></font></td><td>&nbsp;</td></tr>
    @php
        $loggedInAccount = auth()->guard('account')->user();
        $number_of_rows = 0;
    @endphp
    @foreach($threads as $thread)
        @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
        <tr bgcolor="{{$bgcolor}}"><td valign="top" width="22%"><a href="/community/characters/{{rawurlencode($thread['name'])}}">{{htmlspecialchars($thread['name'])}}</a><br /><br /><font size="1">Profession: {{htmlspecialchars(Website::getVocationName($thread->promotion ? $thread->vocation+4 : $thread['vocation']))}}<br />Level: {{$thread['level']}}<br />
        @if(!empty($thread->rank_id))
            @php $rank = $thread->getPlayer->getRank; @endphp
            @if($rank)
                @php $guild = $rank->getGuild; @endphp
                @if($guild)
                    {{htmlspecialchars($rank->name)}} of <a href="/community/guilds/{{$guild->id}}">{{htmlspecialchars($guild->name)}}</a><br />
                @endif
            @endif
        @endif
        <br />Posts: {{$postCounter->where('author_aid', $thread['account_id'])->first()->posts}}<br /></font></td><td valign="top" style="word-wrap: break-word;word-break: break-word;overflow-wrap: break-word;">{!!Functions::showPost(htmlspecialchars($thread['post_topic']), htmlspecialchars($thread['post_text']), $thread['post_smile'])!!}</td></tr>
        <tr bgcolor="{{$bgcolor}}"><td><font size="1">{{date('d.m.y H:i:s', $thread['post_date'])}}
        @if($thread['edit_date'] > 0)
            @if($thread['last_edit_aid'] != $thread['author_aid'])
                <br />Edited by a moderator
            @else
                <br />Edited by {{htmlspecialchars($thread['name'])}}
            @endif
            <br />on {{date('d.m.y H:i:s', $thread['edit_date'])}}
        @endif
        </font></td><td>
        @php $removeRow = false; @endphp
        @php $editRow = false; @endphp
        @if($loggedInAccount && $loggedInAccount->page_access >= config('custom.access_admin_panel'))
            @php $removeRow = true; @endphp
            @if($thread['first_post'] != $thread['id'])
                <a href="/forum/communityboards/remove/{{$thread['id']}}" onclick="return confirm('Are you sure you want remove post of {{htmlspecialchars($thread['name'])}}?')"><font color="red">REMOVE POST</font></a>
            @else
                <a href="/forum/communityboards/remove/{{$thread['id']}}" onclick="return confirm('Are you sure you want remove thread > {{htmlspecialchars($thread['post_topic'])}} <?')"><font color="red">REMOVE THREAD</font></a>
            @endif
        @endif
        @if($loggedInAccount && ($thread['account_id'] == $loggedInAccount->id || $loggedInAccount->page_access >= config('custom.access_admin_panel')))
            @php $editRow = true; @endphp
            {!!$removeRow ? '<br/>' : ''!!}
            <form style="display:inline-block; margin:0; padding:0;" action="/forum/communityboards/edit/{{$thread['id']}}" method="POST" id="fedit_{{$thread['id']}}">
                @csrf
                <input type="hidden" name="page" value="{{request()->page}}">
                <div class="navibutton" >
                    <a href="" onClick="document.getElementById('fedit_{{$thread['id']}}').submit();return false;">EDIT {{$thread['first_post'] != $thread['id'] ? "POST" : "THREAD"}}</a>
                </div>
            </form>
        @endif
        @if($loggedInAccount)
            {!!$removeRow || $editRow ? '<br/>' : ''!!}<a href="{{route('forum.newpost', ['id' => $thread_id, 'quote' => $thread['id']])}}">QUOTE</a>
        @else
            {{request()->session()->put('url.intended', '/forum/communityboards/thread/'.$thread_id)}}
            <a href="/account/accountmanagement">Login</a>
        @endif
        </td></tr>
    @endforeach
    </table><br /><a href="/forum/communityboards/newpost/{{$thread_id}}"><img src="{{asset('images')}}/forum/post.gif" border="0" /></a><br />
    {{$threads->links()}}<br />{!!($threads->lastPage() > 1 ? '<br/>' : '')!!}
</x-layout>