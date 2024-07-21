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
        <FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
        @csrf
        <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
    @else
		<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}>
        <TD CLASS=white><B>Cancel guild invitation</B></TD></TR><TR BGCOLOR={{config('custom.darkborder')}}>
        <TD WIDTH=100%>Are you sure you want to cancel guild invitation of player <b>{{$name}}</b>?</TD></TR></TABLE>
        <br/><center><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><TR>
        <FORM ACTION="/community/guilds/deleteinvite" METHOD=post>
        @csrf
        @method('delete')
        <input type="hidden" name="id" value="{{$guild_id}}">
        <input type="hidden" name="name" value="{{$name}}">
        <TD align="right" width="50%">
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18>&nbsp;&nbsp;</TD></FORM>
        <FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
        @csrf
        <TD>&nbsp;&nbsp;<INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></FORM></TABLE></center>
    @endif
@elseif($action == "invite_deleted")
    <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR={{config('custom.vdarkborder')}}><TD CLASS=white><B>Cancel guild invitation</B></TD></TR>
    <TR BGCOLOR={{config('custom.darkborder')}}><TD WIDTH=100%>Guild invitation of player <b>{{$name}}</b> has been canceled.</TD></TR></TABLE><br/><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>
    <FORM ACTION="/community/guilds/{{$guild_id}}" METHOD=post>
    @csrf
    <TR><TD><center><INPUT TYPE=image NAME="Back" ALT="Back" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center></TD></TR></FORM></TABLE>
@endif
</x-layout>