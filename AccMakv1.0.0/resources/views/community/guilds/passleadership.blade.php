<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(!isset($action))
        @if($errors->any())
            <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
            @foreach($errors->all() as $error)
                <li>{!!$error!!}
            @endforeach
            </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
            <FORM ACTION="/community/guilds/manage/{{$guild_id}}" METHOD=post>
            @csrf
            <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>        
        @else
            <div class="TableContainer"><table class="Table1" cellpadding="0" cellspacing="0"><div class="CaptionContainer" ><div class="CaptionInnerContainer" >
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text">Pass Leadership</div>
            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div></div><tr><td>
            <div class="InnerTableContainer"><table style="width:100%;">
            <form action="/community/guilds/leadershippassed" METHOD=post>
            @csrf
            @method('patch')
            <input type="hidden" name="id" value="{{$guild_id}}"/>
            If you pass leadership of your guild to another player, they will have full access to control your guild. <b>This is not reversable!</b> If you want to give access to manage ranks, guild halls or guild descriptions then you should do this by assigning vice-leaders.<br><br>
            <tr><td width="150" valign="top"><b>Pass leadership to:</b> </b></TD><TD><SELECT name="name">
            @php sort($namesOfGuildPlayers); @endphp
            @foreach($namesOfGuildPlayers as $name)
                <OPTION>{{htmlspecialchars($name)}}</OPTION>
            @endforeach
            </SELECT> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Save"></TD></form>
            </td></tr></table></div></table></div></td></tr><br/><center>
            <form action="/community/guilds/manage/{{$guild_id}}" METHOD=post>
            @csrf
            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
        @endif
    @elseif($action == "leadership_passed")
        <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" ><div class="CaptionContainer" ><div class="CaptionInnerContainer" >
        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <div class="Text">Pass Leadership</div>
        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionEdgeLeftBottom" style="background-image:url{{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div></div><tr><td>
        <div class="InnerTableContainer"><table style="width:100%;"><tr><td>Leadership of guild <b>{{htmlspecialchars($guild_name)}}</b> has been passed to <b>{{htmlspecialchars($player_name)}}</b>.</td></tr></table></div></table></div></td></tr><br/><center>
        <form action="/community/guilds{{($newOwnerFromAccount ? '/manage' : '').'/'.$guild_id}}" METHOD=post>
        @csrf
        <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
        <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
    @endif
</x-layout>