<x-layout :$pageTitle :$subtopic>
    @php
        $layout_path = asset(config('custom.layout_path'));
        $world_name = $server_config['serverName'];

        $skill = 'experience';
        if(request()->has('skill'))
            $skill = (string) request()->skill;

        switch($skill)
        {
            case "fist":
                $id = 0;
                $skill_name = 'Fist Fighting';
                break;
            case "club":
                $id = 1;
                $skill_name = 'Club Fighting';
                break;
            case "sword":
                $id = 2;
                $skill_name = 'Sword Fighting';
                break;
            case "axe":
                $id = 3;
                $skill_name = 'Axe Fighting';
                break;
            case "distance":
                $id = 4;
                $skill_name = 'Distance Fighting';
                break;
            case "shielding":
                $id = 5;
                $skill_name = 'Shielding';
                break;
            case "fishing":
                $id = 6;
                $skill_name = 'Fishing';
                break;
            case "magic":
                $id = 7;
                $skill_name = 'Magic Level';
                break;
            default:
                $id = 8;
                $skill_name = 'Experience';
                break;
        }

        $vocation = 'all';
        if(request()->has('vocation'))
            $vocation = (string) request()->vocation;

        switch($vocation)
        {
            case "sorcerer":
                $vocation_id = 1;
                $vocation_name = 'Sorcerer';
                break;
            case "druid":
                $vocation_id = 2;
                $vocation_name = 'Druid';
                break;
            case "paladin":
                $vocation_id = 3;
                $vocation_name = 'Paladin';
                break;
            case "knight":
                $vocation_id = 4;
                $vocation_name = 'Knight';
                break;
            default:
                $vocation_id = null;
                $vocation_name = 'All';
                break;
        }

        $players = $highscores;
        $startimage = ' <img style="margin: 14px 0px;" src="'.asset('images').'/star.png" width="11" height="11" border="0"/>';
    @endphp
    <br><TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><TR><TD><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD><TD><CENTER><H2>Ranking for {{htmlspecialchars($skill_name)}} sorted by <u>{{htmlspecialchars(($vocation_name == "All" ? "Players" : $vocation_name))}}</u> on {{htmlspecialchars($world_name)}}</H2></CENTER></TD></TR></TABLE>
    @php 
        $number_of_rows1 = 0;
        $vocs = array(0 => 'all', 1 => 'sorcerer', 2 => 'druid', 3 => 'paladin', 4 => 'knight');
        $bgcolor1 = (($number_of_rows1++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
    @endphp
    <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD COLSPAN=5 CLASS=white><center><B>Choose a vocation</center></B></TD></TR><tr bgcolor="{{$bgcolor1}}">
    @foreach($vocs as $vid => $voc)
        <td><center><A HREF="{{route('highscores', ['vocation' => $voc, 'skill' => $skill])}}">{{ucwords($voc)}}<br><img src="{{asset("images")}}/highscore/{{$voc}}.gif" border="0"/></A>{!!($vocation_id == $vid ? $startimage : '')!!}</center></td>
    @endforeach
    </tr></TABLE><br>
    @php 
        $bgcolor1 = (($number_of_rows1++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
        $skills = array(8 => 'experience', 7 => 'magic', 0 => 'fist', 1 => 'club', 2 => 'sword', 3 => 'axe', 4 => 'distance', 5 => 'shielding', 6 => 'fishing');
    @endphp
    <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD COLSPAN=9 CLASS=white><B><center>Choose a skill</center></B></TD></TR><tr bgcolor={{$bgcolor1}}>
    @foreach($skills as $sid => $sname)
        <td><center><A HREF="{{route('highscores', ['vocation' => $vocation, 'skill' => $sname])}}"><small>{{ucwords($sname)}}</small><br><img src="{{asset("images")}}/skills/{{$sname}}.gif" width="28" height="28" border="1" style="color:black"/></A>{!!($sid == $id ? $startimage : '')!!}</center></td>
    @endforeach
    </tr></TABLE><br>
    <TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD CLASS=white><B>Rank</B></TD><TD CLASS=white><b>Outfit</b></TD><TD WIDTH=75% CLASS=white><B>Name</B></TD><TD WIDTH=15% CLASS=white><b><center>Level</center></B></TD>
    @if($skill == "experience")
        <TD CLASS=white><b><center>Experience</center></B></TD>
    @endif
    </TR>
    @php $number_of_rows = 0; @endphp
    @foreach($players as $player)
        @php
            $account = $player->getAccount;
            if($skill == "magic")
                $value = $player->maglevel;
            elseif($skill == "experience")
                $value = $player->level;
            else
                $value = $player->value;
            $bgcolor = (($number_of_rows++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
        @endphp
        <tr bgcolor="{{$bgcolor}}"><td style="text-align:right">{{($offset + $number_of_rows)}}.</td><TD><img src="{{config('custom.outfit_images_url')}}?id={{$player->looktype}}&addons={{$player->lookaddons}}&head={{$player->lookhead}}&body={{$player->lookbody}}&legs={{$player->looklegs}}&feet={{$player->lookfeet}}" alt=""/></TD><td><a href="/community/characters/{{rawurlencode($player->name)}}">{!!($player->online > 0 ? '<font color="green">'.htmlspecialchars($player->name).'</font>' : '<font color="red">'.htmlspecialchars($player->name).'</font>')!!}</a> <img src="{{config('custom.flag_images_url').$account->flag.config('custom.flag_images_extension')}}" title="Country: {{$account->flag}}" alt="{{$account->flag}}" /><br><small>{{$player->level}} {{Website::getVocationName($player->promotion ? $player->vocation+4 : $player->vocation)}}</small></td><td><center>{{$value}}</center></td>
        @if($skill == "experience")
            <td><center>{{$player->experience}}</center></td>
        @endif
        </tr>
    @endforeach
    </TABLE>
    @if(count($players) > 0)
        <TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR><TD WIDTH=100%>{{$players->links()}}</TD></TR></TABLE>
    @else
        <TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR BGCOLOR={{config('custom.darkborder')}}><td colspan="4{{($skill == "experience" ? '+1' : '')}}"><center>Currently there are no {{($vocation == "all" ? 'player' : $vocation)}}s on {{$world_name}}.</center></TD></tr></table>
    @endif
</x-layout>

