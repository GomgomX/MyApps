<x-layout :$pageTitle :$subtopic>
    <a href="/forum/communityboards">Boards</a> >> <a href="/forum/communityboards/board/{{$thread['section']}}">{{config('custom.forum_sections')[$thread['section']]}}</a> >> <a href="/forum/communityboards/thread/{{$thread['id']}}">{{htmlspecialchars($thread['post_topic'])}}</a> >> <b>Post new reply</b><br /><h3>{{htmlspecialchars($thread['post_topic'])}}</h3>
    @if($errors->any())
        <font color="red" size="2"><b>Errors occured:</b>
        @foreach($errors->all() as $error)
            <br />* {!!$error!!}
        @endforeach
        </font><br /><br />
    @endif

    @php $number_of_rows = 0; @endphp
    <form action="/forum/communityboards/addnewpost" method="POST">
    @csrf
    <input type="hidden" name="thread_id" value="{{$thread['id']}}"/>
    <table width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="2"><font color="white"><b>Post New Reply</b></font></td></tr><tr bgcolor="{{config('custom.darkborder')}}"><td width="180"><b>Character:</b></td><td>
    <select name="char_id"><option value="0">(Choose character)</option>
    @foreach(auth()->guard('account')->user()->getPlayers as $player)
        <option value="{{$player->id}}"{{old('char_id') == $player->id ? ' selected="selected"' : ''}}>{{$player->name}}</option>
    @endforeach
    </select></td></tr><tr bgcolor="{{config('custom.lightborder')}}"><td><b>Topic:</b></td><td>
    <input type="text" name="topic" value="{{htmlspecialchars(old('topic'))}}" size="40" maxlength="60"/> (Optional)</td></tr>
    <tr bgcolor="{{config('custom.darkborder')}}"><td valign="top" width="21%"><b>Message:</b><font size="1">
    <br />You can use:<br />[player]Name[/player]<br />[url]URL[/url]<br />[url=URL]text[/url]<br />[img]URL[/img]<br />[code]Code[/code]<br />[*] -> &#8226;<br />[b]<b>Text</b>[/b]<br />[s]<small>Text</small>[/s]<br />[i]<i>Text</i>[/i]<br />[u]<u>Text</u>[/u]<br />[r]<s>Text</s>[/r]<br /><center>[c]Text[/c]</center><br />Smileys:<br /><table border="0" cellpadding=2 cellspacing="0" width="100%"><tr><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/1.gif" />&nbsp; <font size=1>:D</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/2.gif" />&nbsp; <font size=1>(H)</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/11.gif" />&nbsp; <font size=1>:up:</font></td></tr><tr><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/3.gif" />&nbsp; <font size=1>:O</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/4.gif" />&nbsp; <font size=1>|-)</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/12.gif" />&nbsp; <font size=1>:down:</font></td></tr><tr><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/5.gif" />&nbsp; <font size=1>:(</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/6.gif" />&nbsp; <font size=1>:@</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/13.gif" />&nbsp; <font size=1>:arr:</font></td></tr><tr><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/7.gif" />&nbsp; <font size=1>8-)</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/8.gif" />&nbsp; <font size=1>:)</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/14.gif" />&nbsp; <font size=1>:idea:</font></td></tr><tr><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/9.gif" />&nbsp; <font size=1>:P</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/10.gif" />&nbsp; <font size=1>;)</font></td><td colspan=1 align="left"><img src="{{asset('images')}}/forum/smile/15.gif" />&nbsp; <font size=1>???</font></td></tr></table></td><td>
    <textarea rows="10" cols="60" name="text" maxlength="10000">{{(old('text') ? old('text') : (!empty($text) ? $text : ''))}}</textarea><br />(Max. 10,000 letters)</td></tr>
    <tr bgcolor="{{config('custom.lightborder')}}"><td valign="top">Options:</td><td><label>
    <input type="checkbox" name="smile"{{old('smile') ? ' checked="checked"' : ""}} value="1"/>Disable Smileys in This Post </label></td></tr></table><center>
    <br>
    <input type="submit" value="Post Reply" /></center>
    </form>
    <table width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="2"><font color="white"><b>Last {{config('custom.forum_last_posts')}} posts from thread: {{htmlspecialchars($thread['post_topic'])}}</b></font></td></tr>
    @foreach($threads as $thread)
        @php if(is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
        <tr bgcolor="{{$bgcolor}}"><td width="21%">{{$thread['name']}}</td><td style="word-wrap: break-word;word-break: break-word;overflow-wrap: break-word;">{!!Functions::showPost(htmlspecialchars($thread['post_topic']), htmlspecialchars($thread['post_text']), $thread['post_smile'])!!}</td></tr>
    @endforeach
    </table>
</x-layout>