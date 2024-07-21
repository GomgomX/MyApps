<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(!isset($action))
        @if($errors->any())
            <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
            @foreach($errors->all() as $error)
                <li>{!!$error!!}
            @endforeach
            </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);"/></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
            <FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
            @csrf
            <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
        @else
            @if(session('error_same_page'))
                <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                <li>{!!session('error_same_page')!!}
                </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            @endif
            <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD CLASS=white><B>Leave guild</B></TD></TR>
            <TR BGCOLOR={{config('custom.lightborder')}}><TD WIDTH=100%>Select character to leave guild:</TD></TR>
            <TR BGCOLOR={{config('custom.darkborder')}}><TD>
            <form style="display:inline-block; margin:0; padding:0;" action="/community/guilds/leaveguild" METHOD="post">
            @csrf
            @method('put')
            <input type="hidden" name="id" value="{{$guild_id}}">
            @php sort($players_in_guild) @endphp
            @foreach($players_in_guild as $player_to_leave)
                <input type="radio" name="name" value="{{htmlspecialchars($player_to_leave)}}"/>{{htmlspecialchars($player_to_leave)}}<br>
            @endforeach
            </TD></TR></TABLE><br>
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><tr><td valign="top">
            <center><INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></form></td>
            <td><FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
            @csrf
            <center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></FORM></td></tr></table>
        @endif
    @elseif($action == "left_guild")
        <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD CLASS=white><B>Leave guild</B></TD></TR>
        <TR BGCOLOR={{config('custom.darkborder')}}><TD WIDTH=100%>Character with name <b>{{htmlspecialchars($player_name)}}</b> left guild <b>{{htmlspecialchars($guild_name)}}</b>.</TD></TR></TABLE>
        <br/><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
        <FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
        @csrf
        <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
    @endif
</x-layout>