<x-layout :$pageTitle :$subtopic>
    @php 
        $layout_path = asset(config('custom.layout_path'));
        $ranks = [];
        $players_with_lower_rank = [];
		$rid = 0;
	    $sid= 0;
        foreach($rank_list as $rank)
        {
            if($guild_leader || $rank->level < $level_in_guild)
            {
                $ranks[$rid]['0'] = $rank->id;
                $ranks[$rid]['1'] = $rank->name;
                $rid++;
                $players_with_rank = $rank->getPlayers;
                if(count($players_with_rank) > 0)
                {
                    foreach($players_with_rank as $player)
                    {
                        if($guild->getOwner->id != $player->id || $guild_leader)
                        {
                            $players_with_lower_rank[$sid]['0'] = htmlspecialchars($player->name);
                            $players_with_lower_rank[$sid]['1'] = htmlspecialchars($player->name).' ('.htmlspecialchars($rank->name).')';
                            $sid++;
                        }
                    }
                }
            }
        }
    @endphp
    @if(!isset($action))
        @if($errors->any())
            <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
            @foreach($errors->all() as $error)
                <li>{!!$error!!}
            @endforeach
            </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
        @endif
    @elseif($action == "rank_changed")
        <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >  <div class="CaptionInnerContainer" >        
        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        <div class="Text" >Rank Changed</div>        
        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        
        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
        </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >          
        <table style="width:100%;" ><tr><td>Rank of player <b>{{$player_name}}</b> has been changed to <b>{{$rank_name}}</b>.</td></tr>          </table>        </div>  </table></div></td></tr><br>
    @endif
    <FORM ACTION="/community/guilds/updaterank" METHOD=post>
    @csrf
    @method('put')
    <input type="hidden" name="id" value="{{$guild->id}}">
    <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
    <TR BGCOLOR={{config('custom.vdarkborder')}}><TD CLASS=white><B>Change Rank</B></TD></TR>
    <TR BGCOLOR={{config('custom.darkborder')}}><TD>Name: <SELECT NAME="name">
    @foreach($players_with_lower_rank as $player_to_list)
        <OPTION value="{{$player_to_list['0']}}">{{$player_to_list['1']}}
    @endforeach
    </SELECT>&nbsp;Rank:&nbsp;<SELECT NAME="rankid">
    @foreach($ranks as $rank)
        <OPTION value="{{htmlspecialchars($rank['0'])}}">{{htmlspecialchars($rank['1'])}}
    @endforeach
    </SELECT>&nbsp;&nbsp;&nbsp;<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD><TR>
    </TABLE></FORM><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
    <FORM ACTION="/community/guilds/{{$guild->id}}" METHOD=post>
    @csrf
    <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
</x-layout>