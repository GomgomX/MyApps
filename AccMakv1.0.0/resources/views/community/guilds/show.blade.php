<x-layout :$pageTitle :$subtopic>
    @php
        $layout_path = asset(config('custom.layout_path'));
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
        @if(session('message_same_page'))
            <center>{!!session('message_same_page')!!}</center><br/>
        @endif
        @php
            $owner_name = '';
            $guild_owner = $guild->getOwner;
            if($guild_owner) {
                $owner_name = $guild_owner->name;
            }
            $rank_list = $guild->getRanks;
            $loggedInAccount = auth()->guard('account')->user();
            $guild_leader = false;
            $guild_vice = false;
            $players_from_account_ids = [];
            $players_from_account_in_guild_without_owner = 0;
            $level_in_guild = null;
            $account_players = [];
            if($loggedInAccount)
            {
                $account_players = $loggedInAccount->load('getPlayers.getRank')->getPlayers;
                foreach($account_players as $player)
                {
                    $players_from_account_ids[] = $player->id;
                    $player_rank = $player->getRank;
                    if($player_rank)
                    {
                        foreach($rank_list as $rank_in_guild)
                        {
                            if($rank_in_guild->id == $player_rank->id)
                            {
                                if($player_rank->level > 1)
                                {
                                    $guild_vice = true;
                                    $level_in_guild = $player_rank->level;
                                }
                                if($guild->ownerid == $player->id)
                                {
                                    $guild_vice = true;
                                    $guild_leader = true;
                                } else {
                                    $players_from_account_in_guild_without_owner++;
                                }
                            }
                        }
                    }
                }
            }
            $description = $guild->description;
            $newlines = array("\r\n", "\n", "\r");
            for($i = 0; $i < config('custom.guild_description_lines_limit'); $i++) {
                $description = preg_replace('/'.implode('|', array_map('preg_quote', $newlines)).'/', '<br/>', $description, 1);
            }
            $description = str_replace($newlines, '', $description);
        @endphp
        <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><TR><TD style="word-wrap: break-word;word-break: break-word;overflow-wrap: break-word;">
        <TABLE BORDER=0 WIDTH=100%>
        <TR><TD WIDTH=64><IMG SRC="{{$guild->getGuildLogoLink()}}" WIDTH=64 HEIGHT=64></TD>
        <TD ALIGN=center WIDTH=100%><H1>{{htmlspecialchars($guild->name)}}</H1></TD>
        <TD WIDTH=64><IMG SRC="{{$guild->getGuildLogoLink()}}" WIDTH=64 HEIGHT=64></TD></TR>
        </TABLE><BR>{!!$description!!}<BR><BR><a href="/community/characters/{{rawurlencode($owner_name)}}"><b>{{htmlspecialchars($owner_name)}}</b></a> is guild leader of <b>{{htmlspecialchars($guild->name)}}</b>.<BR>The guild was founded on {{htmlspecialchars($server_config['serverName'])}} on {{date("j F Y", $guild->creationdata)}}
        @if($guild_leader || (!empty($owner_name) && $loggedInAccount && $guild_owner->account_id == $loggedInAccount->id))
            <br><br><center><a href="/community/guilds/manage/{{$guild->id}}"><IMG SRC="{{$layout_path}}/images/buttons/sbutton_manageguild.png" BORDER=0 alt="Manage Guild"/></a></center>
        @else
            <BR>
        @endif
        <BR>
        <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
        <TR BGCOLOR={{config('custom.vdarkborder')}}><TD COLSPAN=3 CLASS=white><B>Guild Members</B></TD></TR>
        <TR BGCOLOR={{config('custom.vdarkborder')}}><TD CLASS=white WIDTH=30%><B>Rank</B></TD>
        <TD CLASS=white><B>Name and Title</B></TD></TR>
        @php $showed_players = 1; @endphp
        @foreach($rank_list as $rank)
            @php
                $players_with_rank = $rank->getPlayers;
                $players_with_rank_number = count($players_with_rank);
            @endphp
            @if($players_with_rank_number > 0)
                @php if(is_int($showed_players / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $showed_players++; @endphp
                <TR BGCOLOR="{{$bgcolor}}"><TD valign="top">{{$rank->name}}</TD>
                <TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
                @foreach($players_with_rank as $player)
                    <TR><TD>
                    <FORM style="display:inline-block; margin:0; padding:0;" ACTION="/community/guilds/changenick" METHOD="post">
                    @csrf
                    @method('patch')
                    <A HREF="/community/characters/{{rawurlencode($player->name)}}">{!!($player->online ? "<font color=\"green\">".htmlspecialchars($player->name)."</font>" : "<font color=\"red\">".htmlspecialchars($player->name)."</font>")!!}</A>
                    @php $guild_nick = $player->guildnick; @endphp
                    @if($loggedInAccount)
                        @if(in_array($player->id, $players_from_account_ids))
                            (<input type="text" name="nick" value="{!!htmlspecialchars($guild_nick)!!}" size="15" maxlength="45"><input type="hidden" name="name" value="{{$player->name}}"> <input type="submit" value="Change">)
                        @else
                            @if(!empty($guild_nick))
                                ({!!htmlspecialchars($guild_nick)!!})
                            @endif
                        @endif
                    @else
                        @if(!empty($guild_nick))
                             ({!!htmlspecialchars($guild_nick)!!})
                        @endif
                    @endif
                    @if($level_in_guild > $rank->level || $guild_leader)
                        @if($owner_name != $player->name)
                            <font size=1>[<a href="/community/guilds/kick/{{$guild->id}}/{{rawurlencode($player->name)}}">KICK</a>]</font>
                        @endif
                    @endif
                    </FORM></TD></TR>
                @endforeach
                </TABLE></TD></TR>
            @endif
        @endforeach
        </TABLE>
        @php 
            $invited_list = $guild->getInvites;
            $show_accept_invite = 0; 
        @endphp
        @if(count($invited_list) == 0)
            <BR><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD COLSPAN=2 CLASS=white><B>Invited Characters</B></TD></TR><TR BGCOLOR={{config('custom.lightborder')}}><TD>No invited characters found.</TD></TR></TABLE>
        @else
            <BR><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD COLSPAN=2 CLASS=white><B>Invited Characters</B></TD></TR>
            @php
                $showed_invited = 1;
            @endphp
            @foreach($invited_list as $invited_player)
                @php
                    $invitedPlayerName = $invited_player->getPlayer->name;
                    if(count($account_players) > 0)
                    {
                        foreach($account_players as $player_from_acc)
                        {
                            if($player_from_acc->name == $invitedPlayerName)
                            {
                                $show_accept_invite++;
                            }
                        }
                    }
                    if(is_int($showed_invited / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $showed_invited++;
                @endphp
                <TR bgcolor="{{$bgcolor}}"><TD><a href="/community/characters/{{rawurlencode($invitedPlayerName)}}">{{htmlspecialchars($invitedPlayerName)}}</a>
                @if($guild_vice)
                    [<a href="/community/guilds/cancelinvite/{{$guild->id}}/{{urlencode($invitedPlayerName)}}">Cancel Invitation</a>]
                @endif
                </TD></TR>
            @endforeach
            </TABLE>
        @endif
        <BR>
        <TABLE BORDER=0 WIDTH=100%><TR><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=15 HEIGHT=1 BORDER=0/></TD>
        @if(!$loggedInAccount)
            <TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
            {{request()->session()->put('url.intended', '/community/guilds/'.$guild->id)}}
            <FORM ACTION="/account/accountmanagement" METHOD=post>
            @csrf
            <TR><TD>
            <INPUT TYPE=image NAME="Login" ALT="Login" SRC="{{$layout_path}}/images/buttons/sbutton_login.gif" BORDER=0 WIDTH=120 HEIGHT=18>
            </TD></TR></FORM></TABLE></TD>
        @else
            @if($show_accept_invite > 0)
                <TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                <FORM ACTION="/community/guilds/accept/{{$guild->id}}" METHOD=post>
                @csrf
                <TR><TD>
                <INPUT TYPE=image NAME="Accept Invite" ALT="Accept Invite" SRC="{{$layout_path}}/images/buttons/sbutton_acceptinvite.png" BORDER=0 WIDTH=120 HEIGHT=18>
                </TD></TR></FORM></TABLE></TD>
            @endif
            @if($guild_vice)
                <TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                <FORM ACTION="/community/guilds/invite/{{$guild->id}}" METHOD=post>
                @csrf
                <TR><TD>
                <INPUT TYPE=image NAME="Invite Player" ALT="Invite Player" SRC="{{$layout_path}}/images/buttons/sbutton_inviteplayer.png" BORDER=0 WIDTH=120 HEIGHT=18>
                </TD></TR></FORM></TABLE></TD>
                <TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                <FORM ACTION="/community/guilds/changerank/{{$guild->id}}" METHOD=post>
                @csrf
                <TR><TD>
                <INPUT TYPE=image NAME="Change Rank" ALT="Change Rank" SRC="{{$layout_path}}/images/buttons/sbutton_changerank.png" BORDER=0 WIDTH=120 HEIGHT=18>
                </TD></TR></FORM></TABLE></TD>
            @endif
            @if($players_from_account_in_guild_without_owner > 0)
                <TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                <FORM ACTION="/community/guilds/leave/{{$guild->id}}" METHOD=post>
                @csrf
                <TR><TD>
                <INPUT TYPE=image NAME="Leave Guild" ALT="Leave Guild" SRC="{{$layout_path}}/images/buttons/sbutton_leaveguild.png" BORDER=0 WIDTH=120 HEIGHT=18>
                </TD></TR></FORM></TABLE></TD>
            @endif
        @endif
        <TD ALIGN=center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
        <FORM ACTION="/community/guilds" METHOD=post>
        @csrf
        <TR><TD>
        <INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18>
        </TD></TR></FORM></TABLE>
        </TD><TD ALIGN=center><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=15 HEIGHT=1 BORDER=0></TD></TR></TABLE>
        </TD></TR></TABLE></TABLE>
    @endif
</x-layout>