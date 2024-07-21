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
    <a href="/forum/communityboards">Boards</a> >> <b>{{config('custom.forum_sections')[$section_id]}}</b><br /><br />
    <a href="/forum/communityboards/newtopic/{{$section_id}}"><img src="{{asset('images')}}/forum/topic.gif" border="0" /></a>
    @if(count($last_threads))
        <br />{{$last_threads->links()}}<br />{!!($last_threads->lastPage() > 1 ? '<br/>' : '')!!}
        @php 
            $loggedInAccount = auth()->guard('account')->user();
            $number_of_rows = 0; 
        @endphp
        <table width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}" align="center"><td><font color="white" size="1"><b>Thread</b></font></td><td><font color="white" size="1"><b>Thread Starter</b></font></td><td><font color="white" size="1"><b>Replies</b></font></td><td><font color="white" size="1"><b>Views</b></font></td><td><font color="white" size="1"><b>Last Post</b></font></td></tr>
        @foreach($last_threads as $thread)
            @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
            <tr bgcolor="{{$bgcolor}}"><td>
            @if($loggedInAccount && $loggedInAccount->page_access >= config('custom.access_admin_panel'))
                <a href="/forum/communityboards/remove/{{$thread->id}}" onclick="return confirm('Are you sure you want remove thread > {{htmlspecialchars($thread->post_topic)}} <?')"><font color="red">[REMOVE]</font></a>
            @endif
            <a href="/forum/communityboards/thread/{{$thread->id}}">{{htmlspecialchars($thread->post_topic)}}</a><br /><small>{!!htmlspecialchars(substr(Functions::removeBBCode($thread->post_text), 0, 50))!!}...</small></td><td><a href="/community/characters/{{rawurlencode($thread->name)}}">{{$thread->name}}</a></td><td>{{(int) $thread->replies}}</td><td>{{(int) $thread->views}}</td><td>
            @if($thread->last_post > 0)
                @php $last_post = $last_posts->where('first_post', $thread->id)->first(); @endphp
                @if(isset($last_post->name))
                    {{date('d.m.y H:i:s', $last_post->post_date)}}<br />by <a href="/community/characters/{{rawurlencode($last_post->name)}}">{{$last_post->name}}</a>
                @else
                    No posts.
                @endif
            @else
                {{date('d.m.y H:i:s', $thread->post_date)}}<br />by <a href="/community/characters/{{rawurlencode($thread->name)}}">{{$thread->name}}</a>
            @endif
            </td></tr>
        @endforeach
        </table><br /><a href="/forum/communityboards/newtopic/{{$section_id}}"><img src="{{asset('images')}}/forum/topic.gif" border="0" /></a>
    @else
        <h3>No threads in this board.</h3>
    @endif
</x-layout>