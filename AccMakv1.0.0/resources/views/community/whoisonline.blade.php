<x-layout :$pageTitle :$subtopic>
    @php
        $layout_path = asset(config('custom.layout_path'));
        $number_of_players_online = 0;
        $vocations_online_count = array(0,0,0,0,0); // change it if you got more then 5 vocations
        $players_rows = '';
        foreach($onlinePlayers as $player)
        {
            $account = $player->getAccount;
            $vocations_online_count[$player['vocation']] += 1;
            $bgcolor = (($number_of_players_online++ % 2 == 1) ? config('custom.darkborder') : config('custom.lightborder'));
            $skull = '';
            if ($player['skull'] == 4)
                $skull = " <img style='border: 0;' src='".asset('images')."/skulls/redskull.gif'/>";
            else if ($player['skull'] == 5)
                $skull = " <img style='border: 0;' src='".asset('images')."/skulls/blackskull.gif'/>";

            $players_rows .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=5%><img src="'.config('custom.outfit_images_url').'?id='.$player['looktype'].'&addons='.$player['lookaddons'].'&head='.$player['lookhead'].'&body='.$player['lookbody'].'&legs='.$player['looklegs'].'&feet='.$player['lookfeet'].'" alt=""/></td><TD WIDTH=65%><A HREF="/community/characters/'.rawurlencode($player['name']).'">'.htmlspecialchars($player['name']).'</A> <img src="'.config('custom.flag_images_url').$account['flag'].config('custom.flag_images_extension').'" title="Country: '.$account['flag'].'" alt="'.$account['flag'].'" />'.$skull.'</TD><TD WIDTH=10%>'.$player['level'].'</TD><TD WIDTH=20%>'.htmlspecialchars(Website::getVocationName($player->promotion ? $player->vocation+4 : $player->vocation)).'</TD></TR>';
        }
    @endphp
    @if(config('custom.private-servlist.com_server_id') > 0)
        <TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD WIDTH=10% CLASS=white><center><B>Players Online Chart</B></TD></TR></TABLE><table align="center"><tr><td><img src="http://private-servlist.com/server-chart/{{config('custom.private-servlist.com_server_id')}}.png" width="500px" /></td></tr></table>
    @endif
    @if($server_status['serverStatus_online'] != 1)
        <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR={{config('custom.darkborder')}}><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently <b>{{htmlspecialchars($server_config['serverName'])}}</b> is offline.</TD></TR></TABLE></TD></TR></TABLE><BR>
    @else
        @if($number_of_players_online == 0)
            <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR={{config('custom.darkborder')}}><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently no one is playing on <b>{{htmlspecialchars($server_config['serverName'])}}</b>.</TD></TR></TABLE></TD></TR></TABLE><BR>
        @else
            <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR={{config('custom.darkborder')}}><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently {{$number_of_players_online}} players are online - <b>{{$server_status['serverStatus_players']}} {{(($server_status['serverStatus_players'] > 1) ? 'are' : 'is')}} active</b> and <b>{{($number_of_players_online-$server_status['serverStatus_players'])}} {{(($number_of_players_online-$server_status['serverStatus_players'] > 1) ? 'are' : 'is')}} AFK</b> on <b>{{htmlspecialchars($server_config['serverName'])}}</b>.</TD></TR></TABLE></TD></TR></TABLE><BR>
            <table width="200" cellspacing="1" cellpadding="0" border="0" align="center">
                <tbody>
                    <tr>
                        <tr bgcolor="{{config('custom.darkborder')}}">
                        <td><img src="{{asset('images')}}/vocations/sorcerer.png" /></td>
                        <td><img src="{{asset('images')}}/vocations/druid.png" /></td>
                        <td><img src="{{asset('images')}}/vocations/paladin.png" /></td>
                        <td><img src="{{asset('images')}}/vocations/knight.png" /></td>
                    </tr>
                    <tr>
                        <tr bgcolor="{{config('custom.vdarkborder')}}">
                        <td style="text-align: center;"><strong style="color:white">Sorcerers</strong></td>
                        <td style="text-align: center;"><strong style="color:white">Druids</strong></td>
                        <td style="text-align: center;"><strong style="color:white">Paladins</strong></td>
                        <td style="text-align: center;"><strong style="color:white">Knights</strong></td>
                    </tr>
                    <tr>
                        <TR BGCOLOR="{{config('custom.lightborder')}}">
                        <td style="text-align: center;">{{$vocations_online_count[1]}}</td>
                        <td style="text-align: center;">{{$vocations_online_count[2]}}</td>
                        <td style="text-align: center;">{{$vocations_online_count[3]}}</td>
                        <td style="text-align: center;">{{$vocations_online_count[4]}}</td>
                    </tr>
                </tbody>
            </table>
            <div style="text-align: center;">&nbsp;</div>

            <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}">
                <TD CLASS="white"><b>Outfit</b></TD>
                <TD><A HREF="{{route('whoisonline', ['order' => (request()->order == 'nameasc' ? 'namedesc' : 'nameasc')])}}" CLASS=white>Name</A></TD>
                <TD><A HREF="{{route('whoisonline', ['order' => (request()->order == 'levelasc' ? 'leveldesc' : 'levelasc')])}}" CLASS=white>Level</A></TD>
                <TD><A HREF="{{route('whoisonline', ['order' => (request()->order == 'vocationasc' ? 'vocationdesc' : 'vocationasc')])}}" CLASS=white>Vocation</TD></TR>{!!$players_rows!!}</TABLE>
            <BR>
            <FORM ACTION="/community/characters" METHOD=post>
            @csrf
            <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Search Character</B></TD></TR>
            <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
            <TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE="" SIZE="29" MAXLENGTH="29"></TD><TD>
            <INPUT TYPE="image" NAME="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>
        @endif
    @endif
</x-layout>