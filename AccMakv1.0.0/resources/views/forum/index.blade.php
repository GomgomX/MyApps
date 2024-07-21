<x-layout :$pageTitle :$subtopic>
    @php $number_of_rows = 0; @endphp
    @if($errors->any())
        <font color="red" size="2"><b>Errors occured:</b>
        @foreach($errors->all() as $error)
            <br />* {!!$error!!}
        @endforeach
        </font><br /><br />
    @endif
    <b>Boards</b>
    <table width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td><font color="white" size="1"><b>Board</b></font></td><td><font color="white" size="1"><b>Posts</b></font></td><td><font color="white" size="1"><b>Threads</b></font></td><td align="center"><font color="white" size="1"><b>Last Post</b></font></td></tr>
    @foreach(config('custom.forum_sections') as $id => $section)
        @php 
            $last_post = $lastPosts->where('section', $id)->first();
            if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++;
        @endphp
        <tr bgcolor="{{$bgcolor}}"><td><a href="/forum/communityboards/board/{{$id}}">{{$section}}</a><br /><small>{{config('custom.forum_section_desc')[$id]}}</small></td>
        <td>{{(isset($counters[$id]['posts']) ? $counters[$id]['posts'] : 0)}}</td>
        <td>{{(isset($counters[$id]['threads']) ? $counters[$id]['threads'] : 0)}}</td><td>
        @if(isset($last_post->name))
            {{date('d.m.y H:i:s', $last_post->post_date)}}<br />by <a href="/community/characters/{{rawurlencode($last_post->name)}}">{{$last_post->name}}</a>
        @else
            No posts
        @endif
        </td></tr>
    @endforeach
    </table>
</x-layout>