<x-layout :$pageTitle :$subtopic>
    @php
        $layout_path = asset(config('custom.layout_path'));
        $world_name = $server_config['serverName'];
        $loggedInAccount = auth()->guard('account')->user();
        $showed_guilds = 1;
    @endphp
    @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
        <FORM ACTION="/community/guilds" METHOD=post>
        @csrf
        <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
    @else
        <h2><center>Guilds on {{htmlspecialchars($world_name)}}</center></h2><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
        <TR BGCOLOR={{config('custom.vdarkborder')}}><TD COLSPAN=3 CLASS=white><B>Guilds on {{htmlspecialchars($world_name)}}</B></TD></TR>
        <TR BGCOLOR={{config('custom.darkborder')}}><TD WIDTH=64><B>Logo</B></TD>
        <TD WIDTH=80%><B>Description</B></TD>
        <TD WIDTH=56></TD></TR>
        @if(count($guilds_list) > 0)
            @foreach($guilds_list as $guild)
                @php 
                    if(is_int($showed_guilds / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $showed_guilds++;
                    $description = $guild->description;
                    $newlines = array("\r\n", "\n", "\r");
                    for($i = 0; $i < config('custom.guild_description_lines_limit'); $i++) {
                        $description = preg_replace('/'.implode('|', array_map('preg_quote', $newlines)).'/', '<br/>', $description, 1);
                    }
                    $description = str_replace($newlines, '', $description);
                @endphp
                <TR BGCOLOR="{{$bgcolor}}"><TD><IMG SRC="{{$guild->getGuildLogoLink()}}" WIDTH=64 HEIGHT=64></TD>
                <TD valign="top" style="word-wrap: break-word;word-break: break-word;overflow-wrap: break-word;"><B>{{htmlspecialchars($guild->name)}}</B><BR/>{!!$description!!}
                @if($loggedInAccount && $loggedInAccount->page_access >= config('custom.access_admin_panel'))
                    <br /><a href="/community/guilds/deletebyadmin/{{$guild->id}}">Delete this guild (for ADMIN only!)</a>
                @endif
                </TD><TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                <FORM ACTION="/community/guilds/{{$guild->id}}" METHOD=post>
                @csrf
                <TR><TD>
                <INPUT TYPE=image NAME="View" ALT="View" SRC="{{$layout_path}}/images/buttons/sbutton_view.gif" BORDER=0 WIDTH=120 HEIGHT=18>
                </TD></TR></FORM></TABLE>
                </TD></TR>
            @endforeach
        @else
            <TR BGCOLOR={{config('custom.lightborder')}}><TD><IMG SRC="{{asset('images')}}/default_guild_logo.gif" WIDTH=64 HEIGHT=64></TD>
            <TD valign="top"><B>Create guild</B><BR/>Currently there are no guilds on {{htmlspecialchars($world_name)}}. Create one!</TD>
            <TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
            <TR><TD>
            <a href="/community/guilds/create"><img src="{{$layout_path}}/images/buttons/sbutton_createguild.png" BORDER="0" WIDTH=120 HEIGHT=18/></a>
            </TD></TR></TABLE></TD></TR>
        @endif
        </TABLE><br>
        @if($loggedInAccount)
            <TABLE BORDER=0 WIDTH=100%><TR><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=80 HEIGHT=1 BORDER=0<BR></TD><TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
            <a href="/community/guilds/create"><img src="{{$layout_path}}/images/buttons/sbutton_createguild.png" BORDER="0" WIDTH=120 HEIGHT=18/></a></TABLE></TD><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=80 HEIGHT=1 BORDER=0<BR></TD></TR></TABLE>
        @else
            Before you can create guild you must login.<br><TABLE BORDER=0 WIDTH=100%><TR><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=80 HEIGHT=1 BORDER=0<BR></TD><TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
            {{request()->session()->put('url.intended', '/community/guilds')}}
            <FORM ACTION="/account/accountmanagement" METHOD=post>
            @csrf
            <TR><TD>
            <INPUT TYPE=image NAME="Login" ALT="Login" SRC="{{$layout_path}}/images/buttons/sbutton_login.gif" BORDER=0 WIDTH=120 HEIGHT=18>
            </TD></TR></FORM></TABLE></TD><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=80 HEIGHT=1 BORDER=0<BR></TD></TR></TABLE>
        @endif
    @endif
</x-layout>