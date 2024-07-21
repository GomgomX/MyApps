<x-layout :$pageTitle :$subtopic>
	@php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(isset($player))
        @php
            $number_of_rows = 0;
            $account = $player->getAccount;
            $skull = '';
            if ($player->getSkull == 4)
                $skull = " <img style='border: 0;' src='".asset('images')."/skulls/redskull.gif'/>";
            else if ($player->getSkull == 5)
                $skull = " <img style='border: 0;' src='".asset('images')."/skulls/blackskull.gif'/>";
        @endphp
        <table border="0" cellspacing="1" cellpadding="4" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="2" style="font-weight:bold;color:white">Character Information</td></tr>
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td width="20%">Name:</td><td style="font-weight:bold;color:{{(($player->online) ? 'green' : 'red')}}">{{htmlspecialchars($player->name)}}{!!($player->deleted ? '<font color="red"> [DELETED]</font>' : '')!!} <img src="{{config('custom.flag_images_url').$account->flag.config('custom.flag_images_extension')}}" title="Country: {{$account->flag}}" alt="{{$account->flag}}"/>{{$skull}}
        @php $accountBanned = $account->banned(); @endphp
        @if($player->isBanned() || $accountBanned)
            <span style="color:red">[BANNED]</span>
        @endif
        @if($player->isNamelocked)
            <span style="color:red">[NAMELOCKED]</span>
        @endif
        </td></tr>

        @if(in_array($player->group_id, config('custom.groups_support')))
            @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
            <tr bgcolor="{{$bgcolor}}"><td>Group:</td><td>{{htmlspecialchars(Website::getGroupName($player->group_id))}}</td></tr>
        @endif
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Sex:</td><td>{{htmlspecialchars((($player->sex == 0) ? 'female' : 'male'))}}</td></tr>
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Profession:</td><td>{{htmlspecialchars(Website::getVocationName($player->promotion ? $player->vocation+4 : $player->vocation))}}</td></tr>
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Level:</td><td>{{htmlspecialchars($player->level)}}</td></tr>
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Residence:</td><td>{{htmlspecialchars(config('custom.towns_list')[$player->town_id])}}</td></tr>
        @if(!empty($player->rank_id))
            @php $guildRank = $player->getRank; @endphp
            @if($guildRank)
                @php $guild = $guildRank->getGuild @endphp
                @if($guild)
                    @php 
                        $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
                    @endphp
                    <tr bgcolor="{{$bgcolor}}"><td>Guild Membership:</td>
                    <td>{{htmlspecialchars($guildRank->name)}} of the <a href="/community/guilds/{{$guild->id}}">{{htmlspecialchars($guild->name)}}</a></td></tr>
                @endif
            @endif
        @endif

        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Balance:</td><td>{{htmlspecialchars($player->balance)}} gold coins</td></tr>
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Last login:</td><td>{{(($player->lastlogin > 0) ? date("j F Y, g:i a", $player->lastlogin) : 'Never logged in.')}}</td></tr>
        @if($player->created > 0)
            @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
            <tr bgcolor="{{$bgcolor}}"><td>Created:</td><td>{{date("j F Y, g:i a", $player->created)}}</td></tr>
        @endif
        @if(config('custom.show_vip') > 0)
            @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
            <tr bgcolor="{{$bgcolor}}"><td>VIP:</td><td>{!!(($account->vipdays < 1) ? '<span style="font-weight:bold;color:red">NOT VIP</span>' : '<span style="font-weight:bold;color:green">VIP</span>')!!}</td></tr>
        @endif
        @php
            $comment = $player->comment;
            $newlines = array("\r\n", "\n", "\r");
            for($i = 0; $i < config('custom.character_comment_lines_limit'); $i++) {
                $comment = preg_replace('/'.implode('|', array_map('preg_quote', $newlines)).'/', '<br/>', $comment, 1);
            }
            $comment = str_replace($newlines, '', $comment);
        @endphp
        @if(!empty($comment))
            @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
            <tr bgcolor="{{$bgcolor}}"><td>Comment:</td><td style="word-wrap: break-word;word-break: break-word;overflow-wrap: break-word;">{!!$comment!!}</td></tr>
        @endif
        
        @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
        <tr bgcolor="{{$bgcolor}}"><td>Outfit:</td><td><img src="{{config('custom.outfit_images_url').'?id='.$player->looktype.'&addons='.$player->lookaddons.'&head='.$player->lookhead.'&body='.$player->lookbody.'&legs='.$player->looklegs.'&feet='.$player->lookfeet}}" alt="" /></td></tr>
        </TABLE>

        <table width=100%><tr>
        @php $itemsList = $player->getItems()->inventory()->get(['pid', 'itemtype']); @endphp
        <td align=center><table with=100% style="border: solid 1px #888888;" CELLSPACING="1"><TR>	
        @php $list = array('2','1','3','6','4','5','9','7','10','8'); @endphp
        @foreach($list as $number_of_items_showed => $slot)
            @if($slot == '8')
                <td style="background-color: {{config('custom.darkborder')}}; text-align: center;">Soul:<br/>{{$player->soul}}</td>
            @endif
            @php $item = $itemsList->where('pid', $slot)->first() @endphp
            @if(!$item)
                <TD style="background-color: {{config('custom.darkborder')}};"><img src="{{config('custom.item_images_url').$slot.config('custom.item_images_extension')}}" width="45"/></TD>
            @else
                <TD style="background-color: {{config('custom.darkborder')}};"><img src="{{config('custom.item_images_url').$item->itemtype.config('custom.item_images_extension')}}" width="45"/></TD>
            @endif
            @if($number_of_items_showed % 3 == 2)
                </tr><tr>
            @endif
            @if($slot == '8')
                <td style="background-color: {{config('custom.darkborder')}}; text-align: center;">Cap:<br/>{{$player->cap}}</td>
            @endif
        @endforeach
        </tr></TABLE></td>

        @php
            $hpPercent = max(0, min(100, $player->health / max(1, $player->healthmax) * 100));
            $manaPercent = max(0, min(100, $player->mana / max(1, $player->manamax) * 100));
        @endphp
        <td align="center"><table width=100%><tr><td align="center"><table CELLSPACING="1" CELLPADDING="4" width="100%"><tr><td BGCOLOR="{{config('custom.lightborder')}}" align="left" width="20%"><b>Player Health:</b></td>
        <td BGCOLOR="{{config('custom.lightborder')}}" align="left">{{$player->health}}/{{$player->healthmax}}<div style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: red; width: {{$hpPercent}}%; height: 3px;"></td></tr>
        <tr><td BGCOLOR="{{config('custom.darkborder')}}" align="left"><b>Player Mana:</b></td><td BGCOLOR="{{config('custom.darkborder')}}" align="left">{{$player->mana}}/{{$player->manamax}}<div style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: blue; width: {{$manaPercent}}%; height: 3px;"></td></tr></table><tr>
        
        @php
            $expCurrent = Functions::getExpForLevel($player->level);
            $expNext = Functions::getExpForLevel($player->level + 1);
            $expLeft = bcsub($expNext, $player->experience, 0);

            $expLeftPercent = max(0, min(100, ($player->experience - $expCurrent) / ($expNext - $expCurrent) * 100));
        @endphp
        <tr><table CELLSPACING="1" CELLPADDING="4"><tr><td BGCOLOR="{{config('custom.lightborder')}}" align="left" width="20%"><b>Player Level:</b></td><td BGCOLOR="{{config('custom.lightborder')}}" align="left">{{$player->level}}</td></tr>
        <tr><td BGCOLOR="{{config('custom.darkborder')}}" align="left"><b>Player Experience:</b></td><td BGCOLOR="{{config('custom.darkborder')}}" align="left">{{$player->experience}} EXP.</td></tr>
        <tr><td BGCOLOR="{{config('custom.lightborder')}}" align="left"><b>To Next Level:</b></td><td BGCOLOR="{{config('custom.lightborder')}}" align="left">You need <b>{{$expLeft}} EXP</b> to Level <b>{{($player->level + 1)}}</b>.<div title="{{(100 - $expLeftPercent)}}% left" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: red; width: '.$expLeftPercent.'%; height: 3px;"></td></tr></table></td></tr></table></tr></TABLE></td>

        @if(config('custom.show_skills_info'))
            <center><strong>Skills</strong><table cellspacing="0" cellpadding="0" border="1" width="200">
                <tbody>
                    <tr>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "experience"])}}"><img src="{{asset('images')}}/skills/level.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "magic"])}}"><img src="{{asset('images')}}/skills/ml.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "fist"])}}"><img src="{{asset('images')}}/skills/fist.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "club"])}}"><img src="{{asset('images')}}/skills/club.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "sword"])}}"><img src="{{asset('images')}}/skills/sword.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "axe"])}}"><img src="{{asset('images')}}/skills/axe.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "distance"])}}"><img src="{{asset('images')}}/skills/dist.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "shielding"])}}"><img src="{{asset('images')}}/skills/def.gif" alt="" style="border-style: none"/></td>
                        <td style="text-align: center;"><a href="{{route('highscores', ['vocation' => "all", 'skill' => "fishing"])}}"><img src="{{asset('images')}}/skills/fish.gif" alt="" style="border-style: none"/></td>
                    </tr>
                    <tr>
                        <tr bgcolor="{{config('custom.darkborder')}}"><td style="text-align: center;"><strong>Level</strong></td>
                        <td style="text-align: center;"><strong>ML</strong></td>
                        <td style="text-align: center;"><strong>Fist</strong></td>
                        <td style="text-align: center;"><strong>Mace</strong></td>
                        <td style="text-align: center;"><strong>Sword</strong></td>
                        <td style="text-align: center;"><strong>Axe</strong></td>
                        <td style="text-align: center;"><strong>Dist</strong></td>
                        <td style="text-align: center;"><strong>Def</strong></td>
                        <td style="text-align: center;"><strong>Fish</strong></td>
                    </tr>
                    <tr>
                        <tr bgcolor="{{config('custom.lightborder')}}"><td style="text-align: center;">{{$player->level}}</td>
                        @php $skills = $player->getSkills()->get(['skillid', 'value']); @endphp
                        <td style="text-align: center;">{{$player->maglevel}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 0)->first()->value}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 1)->first()->value}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 2)->first()->value}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 3)->first()->value}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 4)->first()->value}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 5)->first()->value}}</td>
                        <td style="text-align: center;">{{$skills->where('skillid', 6)->first()->value}}</td>
                    </tr>
                </tbody>
            </table>
            <div style="text-align: center;">&nbsp;<br />&nbsp;</div></center>
        @endif

        <center><table cellspacing="0" cellpadding="0" border="1" width="100%">
            <tbody>
                <tr bgcolor="{{config('custom.darkborder')}}">
                    <td style="text-align: center;"><img src="/community/signature/{{$player->name}}" alt="Signature"/></td>
                </tr>
                <tr bgcolor="{{config('custom.lightborder')}}">
                    <td style="text-align: center;"><b>Link:</b><input type="text" size="50" value="{{htmlspecialchars($server_config['url'].'community/signature/'.rawurlencode($player->name))}}" /></td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center;">&nbsp;<br />&nbsp;</div></center>

        @if(!empty(config('custom.quests')) && is_array(config('custom.quests')) && count(config('custom.quests')) > 0)
            <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD align="left" COLSPAN=2 CLASS=white><B>Quests</B></TD></TD align="right"></TD></TR>
            @php $playerStorages = $player->getStorages()->whereIn('key' , array_values(config('custom.quests')))->get(['key', 'value']); @endphp
            @foreach(config('custom.quests') as $questName => $storageID)
                @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
                <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=95%>{{$questName}}</TD>
                @php $storage = $playerStorages->where('key', $storageID)->first(); @endphp
                @if($storage && $storage->value >= 1)
                    <TD><img src="{{asset('images')}}/true.png"/></TD></TR>
                @else
                    <TD><img src="{{asset('images')}}/false.png"/></TD></TR>
                @endif
            @endforeach
            </TABLE></td></tr></table><br />
        @endif

        @php
            $player_deaths = $player->getDeaths()->filter(20)->get(['id', 'date', 'level']);
            if(count($player_deaths))
            {
                echo '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.config('custom.vdarkborder').'"><TD COLSPAN=2 CLASS=white><B>Deaths</B></TD></TR>';
                $i = 0;
                $dead_add_content = '';
                foreach($player_deaths as $death)
                {
                    $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
                    $dead_add_content .= '<tr bgcolor="'.$bgcolor.'"><td width="20%">'.date("j M Y, H:i", $death->date).'</td><td> ';
                    $killers = $death->getKillers;
                    $i = 0;
                    $killers_count = count($killers);
                    if($killers_count)
                    {
                        foreach($killers as $killer)
                        {
                            $i++;
                            if($killer->player_name != "")
                            {
                                if($i == 1)
                                    $dead_add_content .= 'Killed at level <b>'.$death->level.'</b> by ';
                                else if($i == $killers_count)
                                    $dead_add_content .= ' and by ';
                                else
                                    $dead_add_content .= ', ';

                                if($killer->monster_name != "")
                                    $dead_add_content .= $killer->monster_name.' summoned by ';

                                if($killer->player_exists == 0)
                                    $dead_add_content .= '<a href="/community/characters/'.($killer->player_name).'">';

                                $dead_add_content .= $killer->player_name;
                                if($killer->player_exists == 0)
                                    $dead_add_content .= '</a>';
                            }
                            else
                            {
                                if($i == 1)
                                    $dead_add_content .= 'Died at level <b>'.$death->level.'</b> by ';
                                else if($i == $killers_count)
                                    $dead_add_content .= ' and by ';
                                else
                                    $dead_add_content .= ', ';

                                $dead_add_content .= $killer->monster_name;
                            }
                        }
                    }
                    $dead_add_content .= '.</td></tr>';
                }
                
                if($i > 0)
                    echo $dead_add_content.'</table></TABLE></br>';
            }
        @endphp

        @if(!$player->hide_char)
            <TABLE BORDER=0><TR><TD></TD></TR></TABLE><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD COLSPAN=2 CLASS=white><B>Account Information</B></TD></TR>
            @if($account->rlname)
                @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
                <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=20%>Real name:</TD><TD>{{$account->rlname}}</TD></TR>
            @endif
            @if($account->location)
                @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp   
                <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=20%>Location:</TD><TD>{{$account->location}}</TD></TR>
            @endif
            @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
            @if($account->page_lastday)
                <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=20%>Last login:</TD><TD>{{date("j F Y, g:i a", $account->page_lastday)}}</TD></TR>
            @else
                <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=20%>Last login:</TD><TD>Never logged in.</TD></TR>
            @endif
            @if($account->created)
                @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
                <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=20%>Created:</TD><TD>{{date("j F Y, g:i a", $account->created)}}</TD></TR>
            @endif
            @php $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder')); @endphp
            <TR BGCOLOR="{{$bgcolor}}"><TD>Account&#160;Status:</TD><TD>
            {!!($account->premdays > 0 ? "<b><font color='green'>Premium Account</font></b>" : "<b><font color='red'>Free Account</font></b>")!!}
            @if($accountBanned)
                @if($accountBanned->expires > 0)
                    <font color="red"> [Banished until {{date("j F Y, G:i", $accountBanned->expires)}}]</font>
                @else
                    <font color="red"> [Banished FOREVER]</font>
                @endif
            @endif
            </TD></TR></TABLE>
            
            <br><TABLE BORDER=0><TR><TD></TD></TR></TABLE><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD COLSPAN=5 CLASS=white><B>Characters</B></TD></TR>
            <TR BGCOLOR={{$bgcolor}}><TD><B>Name</B></TD><TD><B>Level</B></TD><TD><b>Status</b></TD><TD><B>&#160;</B></TD></TR>
            @php
                $account_players = $account->getPlayers;
                $player_number = 0;
            @endphp
            @foreach($account_players as $account_player)
                @if(!$player->hidechar)
                    @php
                        $player_number++;
                        $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
                        if(!$account_player->online)
                            $account_player_status = '<font color="red">Offline</font>';
                        else
                            $account_player_status = '<font color="green">Online</font>';
                    @endphp
                    <TR BGCOLOR="{{$bgcolor}}"><TD WIDTH=52%><NOBR>{!!$player_number.'.&#160;'.htmlspecialchars($account_player->name)!!}
                    {!!($account_player->deleted ? '<font color="red"> [DELETED]</font>' : '')!!}
                    </NOBR></TD><TD WIDTH=25%>{{$account_player->level}} {{htmlspecialchars(Website::getVocationName($account_player->promotion ? $account_player->vocation+4 : $account_player->vocation))}}</TD><TD WIDTH="8%"><b>{!!$account_player_status!!}</b></TD><TD>
                        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                            <FORM ACTION="/community/characters" METHOD=post>
                            @csrf
                            <TR><TD>
                            <INPUT TYPE="hidden" NAME="name" VALUE="{{htmlspecialchars($account_player->name)}}">
                            <INPUT TYPE=image NAME="View {{htmlspecialchars($account_player->name)}}" ALT="View {{htmlspecialchars($account_player->name)}}" SRC="{{$layout_path}}/images/buttons/sbutton_view.gif" BORDER=0 WIDTH=120 HEIGHT=18>
                            </TD></TR></FORM>
                        </TABLE></TD></TR>
                @endif
            @endforeach
            </TABLE></TD><TD><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD></TR></TABLE>
            <br>
            <br>
        @endif
    @endif
    
    @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
    @endif
    <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
    <FORM ACTION="/community/characters" METHOD=post>
    @csrf
    <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="{{config('custom.darkborder')}}">
    <TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD>
    <INPUT NAME="name" VALUE="" SIZE=29 MAXLENGTH=29></TD><TD>
        <INPUT TYPE=image NAME="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR>
        </TABLE></TD></TR>
    </FORM>
    </TABLE>
    </TABLE>
</x-layout>