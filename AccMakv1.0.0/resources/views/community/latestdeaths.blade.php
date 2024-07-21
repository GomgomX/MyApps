<x-layout :$pageTitle :$subtopic>
    @php 
        $latest_deaths_count = 0;
        $players_rows = '';
        if(count($latest_deaths))
        {
            foreach($latest_deaths as $death)
            {
                $player = $death->getPlayer()->where('deleted', 0)->first();
                if(!$player) {
                    continue;
                }

                $latest_deaths_count++;
                if(is_int($latest_deaths_count / 2))
                    $bgcolor = config('custom.darkborder');
                else
                    $bgcolor = config('custom.lightborder');

                $players_rows .= '<TR BGCOLOR="'.$bgcolor.'"><TD><center>'.$latest_deaths_count.'.</center></TD><TD><center>'.date("j-m-Y", $death->date).'<br>'.date("g:i:s (A)", $death->date).'</center></TD><TD><center><a href="/community/characters/'.rawurlencode($player->name).'"><b>'.$player->name.'</b></a> ';
                $killers = $death->getKillers;
                $i = 0;
                $count = count($killers);
                foreach($killers as $killer)
                {
                    $i++;
                    if($killer->player_name != "")
                    {
                        if($i == 1)
                            $players_rows .= 'was killed at level <b>'.$death->level.'</b> by ';
                        else if($i == $count)
                            $players_rows .= ' and by ';
                        else
                            $players_rows .= ', ';
                        
                        if($killer->monster_name != "")
                            $players_rows .= $killer->monster_name.' summoned by ';

                        if($killer->player_exists == 0)
                            $players_rows .= '<a href="/community/characters/'.rawurlencode($killer->player_name).'">';

                        $players_rows .= $killer->player_name;
                        if($killer->player_exists == 0)
                            $players_rows .= '</a>';
                    }
                    else
                    {
                        if($i == 1)
                            $players_rows .= 'died at level <b>'.$death->level.'</b> by ';
                        else if($i == $count)
                            $players_rows .= ' and by ';
                        else
                            $players_rows .= ', ';

                        $players_rows .= $killer->monster_name;
                    }
                }

                $players_rows .= '.</TR>';
            }
        }
    @endphp

    @if($latest_deaths_count == 0)
        <br><center><b><font size="4">Latest deaths on <i>{{$server_config['serverName']}}</i></font></b></center><br/><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD width="10%" CLASS="white"><center><b>Order</b></center><TD width="20%" CLASS="white"><center><b>Date</b></center></TD><TD class="white"><center><b>Death</b></center></TD></TR><TR BGCOLOR="{{config('custom.darkborder')}}"><TD colspan="3"><center>No one died on {{$server_config['serverName']}} so far.</center></td></tr></TABLE>
    @else
        <br><center><b><font size="4">Latest deaths on <i>{{$server_config['serverName']}}</i></font></b></center><br/><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="{{config('custom.vdarkborder')}}"><TD width="10%" CLASS="white"><center><b>Order</b></center><TD width="20%" CLASS="white"><center><b>Date</b></center></TD><TD class="white"><center><b>Death</b></center></TD></TR>{!!$players_rows!!}</TABLE>
    @endif
</x-layout>