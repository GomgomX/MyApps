<x-layout :$pageTitle :$subtopic>
    @php
        $layout_path = asset(config('custom.layout_path'));
        $serverName = $server_config['serverName'];
    @endphp
    @if(!isset($action))
        @if($errors->any())
                <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
            </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
        @endif
        You can create a guild on {{$serverName}} for free as long as you're level {{config('custom.guild_need_level')}} or above. Which character do you want to become the guild leader?<BR><BR>
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Create a {{htmlspecialchars($serverName)}} guild</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}"><TABLE BORDER=0 CELLSPACING=8 CELLPADDING=0>
        <TR><TD>
        <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0>
        <FORM ACTION="/community/guilds/storeguild" METHOD=post>
        @csrf
        <TR><TD width="150" valign="top"><B>Leader: </B></TD><TD><SELECT name="name">
        @php sort($array_of_players_not_in_guild); @endphp
        @foreach($array_of_players_not_in_guild as $nick)
            <OPTION {{old('name') == $nick ? ' selected="selected"' : ''}}>{{htmlspecialchars($nick)}}</OPTION>
        @endforeach
        </SELECT><BR><font size="1" face="verdana,arial,helvetica">(Name of leader of new guild.)</font></TD></TR>
            <TR><TD width="150" valign="top"><B>Guild name: </B></TD><TD>
            <INPUT NAME="guild" VALUE="{{old('guild')}}" SIZE=30 MAXLENGTH=50><BR><font size="1" face="verdana,arial,helvetica">(Here write name of your new guild.)</font></TD></TR>
            </TABLE>
        </TD></TR>
        </TABLE></TD></TR>
        </TABLE>
        <BR>
        <TABLE BORDER=0 WIDTH=100%><TR><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=120 HEIGHT=1 BORDER=0></TD><TD ALIGN=center VALIGN=top>
        <INPUT TYPE=image NAME="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18>
        </FORM>
        </TD><TD ALIGN=center>
        <FORM style="display:inline-block; margin:0; padding:0;" ACTION="/community/guilds" METHOD=post>
        @csrf
        <INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18>
        </FORM>
        </TD><TD ALIGN=center><IMG SRC="{{$layout_path}}//images/blank.gif" WIDTH=120 HEIGHT=1 BORDER=0></TD></TR></TABLE></TD><TD><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD></TR>
        </TABLE>
    @elseif($action == "guild_created")
        <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD CLASS=white><B>Create guild</B></TD></TR>
        <TR BGCOLOR={{config('custom.darkborder')}}><TD WIDTH=100%><b>Congratulations!</b><br/>You have created guild <b>{{htmlspecialchars($guild_name)}}</b>. <b>{{htmlspecialchars($player_name)}}</b> is the leader of this guild. Now you can invite players, change logo, description and motd of guild. Press submit to open guild manager.</TD></TR></TABLE><br/>
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
        <FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
        @csrf
        <TR><TD><center><INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_Submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
    @endif
</x-layout>