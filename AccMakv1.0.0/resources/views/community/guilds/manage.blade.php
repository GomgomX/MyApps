<x-layout :$pageTitle :$subtopic>
	@php
        $layout_path = asset(config('custom.layout_path'));
        $number_of_rows = 0;
    @endphp
    @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);"/></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
        <FORM ACTION="/community/guilds{{(isset($returnToManage) ? '/manage' : '')}}/{{$guild_id}}" METHOD=post>
        @csrf
        <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
    @else
        @if(session('message_same_page'))
            <center>{!!session('message_same_page')!!}</center>
        @endif
        <center><h2>Guild Manager - {{$guild->name}}</h2>Here you can change names of ranks, delete and add ranks, pass leadership to other guild member and delete guild.</center>
        <br/><table style='clear:both' border=0 cellpadding=0 cellspacing=0 width='100%'>
        <tr bgcolor={{config('custom.darkborder')}}><td width="170"><font color="red"><b>Option</b></font></td><td><font color="red"><b>Description</b></font></td></tr>
        <tr bgcolor={{config('custom.lightborder')}}><td width="170"><b><a href="/community/guilds/passleadership/{{$guild->id}}">Pass Leadership</a></b></td><td><b>Pass leadership of guild to other guild member.</b></td></tr>
        <tr bgcolor={{config('custom.darkborder')}}><td width="170"><b><a href="/community/guilds/delete/{{$guild->id}}">Delete Guild</a></b></td><td><b>Delete guild, kick all members.</b></td></tr>
        <tr bgcolor={{config('custom.lightborder')}}><td width="170"><b><a href="/community/guilds/changedescription/{{$guild->id}}">Change Description</a></b></td><td><b>Change description of guild.</b></td></tr>
        <tr bgcolor={{config('custom.darkborder')}}><td width="170"><b><a href="/community/guilds/changemotd/{{$guild->id}}">Change MOTD</a></b></td><td><b>Change MOTD of guild.</b></td></tr>
        <tr bgcolor={{config('custom.lightborder')}}><td width="170"><b><a href="/community/guilds/changelogo/{{$guild->id}}">Change Guild Logo</a></b></td><td><b>Upload new guild logo.</b></td></tr>
        </table>
        <br>
        <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" ><div class="CaptionContainer" ><div class="CaptionInnerContainer" >        
        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <div class="Text" >Add new rank</div>
        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
        </div></div><tr><td><div class="InnerTableContainer" ><table style="width:100%;" ><tr>
        <form action="/community/guilds/addrank" method="POST">
        @csrf
        <td width="120" valign="top">New rank name:</td><td>
        <input type="text" name="rank_name" size="20" maxlength="65">
        <input type="hidden" name="id" value="{{$guild->id}}">
        <input type="submit" value="Add"></td>
        </form></tr></table></div>  </table></div></td></tr>
        <center><h3>Change rank names and levels</h3></center>
        <form action="/community/guilds/saveranks" method=POST>
        @csrf
        @method('put')
        {{request()->session()->put('save_ranks', true)}}
        <table style='clear:both' border=0 cellpadding=0 cellspacing=0 width='100%'><tr bgcolor={{config('custom.vdarkborder')}}>
        <td rowspan="2" width="120" align="center"><font color="white"><b>Delete Rank</b></font></td><td rowspan="2" width="300"><font color="white"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name</b></font></td>
        <td colspan="3" align="center"><font color="white"><b>Level of RANK in guild</b></font></td></tr>
        <tr bgcolor={{config('custom.vdarkborder')}}><td align="center" bgcolor="red"><font color="white"><b>Leader (3)</b></font></td><td align="center" bgcolor="yellow"><font color="black"><b>Vice (2)</b></font></td><td align="center" bgcolor="green"><font color="white"><b>Member (1)</b></font></td></tr>
        <input type="hidden" name="id" value="{{$guild->id}}">
        @foreach($guild->getRanks()->orderBy('level', 'DESC')->get() as $rank)
            @php if(is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
            <tr bgcolor="{{$bgcolor}}"><td align="center">
            <a href="/community/guilds/deleterank/{{$guild->id}}/{{$rank->id}}" border="0">
            <img src="{{$layout_path}}/images/news/delete.png" border="0" alt="Delete Rank"></a></td><td>
            <input type="text" name="{{$rank->id}}_name" value="{{$rank->name}}" size="35" maxlength="40"></td>
            <td align="center"><input type="radio" name="{{$rank->id}}_level" value="3"{{$rank->level == 3 ? ' checked="checked"' : ''}}/></td>
            <td align="center"><input type="radio" name="{{$rank->id}}_level" value="2"{{$rank->level == 2 ? ' checked="checked"' : ''}}/></td>
            <td align="center"><input type="radio" name="{{$rank->id}}_level" value="1"{{$rank->level == 1 ? ' checked="checked"' : ''}}/></td>
            </tr>
        @endforeach
        <tr bgcolor={{config('custom.vdarkborder')}}><td>&nbsp;</td><td>&nbsp;</td><td colspan="3" align="center"><input type="submit" value="Save All"></td></tr></table></form>
        <h3>Information About Ranks:</h3><b>0. Owner of guild</b><br>This is the highest rank, only one person can be an owner. Player with this rank can:
        <li>Send and cancel invites to other players to join this guild.
        <li>Kick players from this guild.
        <li>Change ranks of all players in guild.
        <li>Delete guild or pass leadership to other guild member.
        <li>Change names, levels(leader, vice, member), add, change and delete ranks.
        <li>Change MOTD, guild logo and the description of guild.<hr>
        <b>3. Leader</b><br>The second highest rank of the guild. Player with this rank can:
        <li>Send and cancel invites to other players to join this guild.
        <li>Kick players from this guild if they are lower rank.
        <li>Change ranks of other players if they are vice-leader rank or below.<hr>
        <b>2. Vice Leader</b><br>The third highest rank in your guild. Player with this rank can:
        <li>Send and cancel invites to other players to join this guild.
        <li>Change ranks of other players if they are the 'member' rank.<hr>
        <b>1. Member</b><br>This is the lowest rank. Player with this rank:
        <li>Is a member of your guild, they don't have any special priviledges.
        <br/><br/><center>
        <form style="display:inline-block; margin:0; padding:0;" action="/community/guilds/{{$guild->id}}" METHOD=post>
        @csrf
        <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
        <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
    @endif
</x-layout>