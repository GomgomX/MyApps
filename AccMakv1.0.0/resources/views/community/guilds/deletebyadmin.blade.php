<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(!isset($action))
        <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" > <div class="CaptionInnerContainer" >        
        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>    
        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <div class="Text" >Guild Deleted</div>
        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >          
        <table style="width:100%;" ><tr><td>Are you sure you want delete guild <b>{{htmlspecialchars($guild_name)}}</b>?<br>
        If your guild owns a guild hall, all items inside will be destroyed.<br>Make sure the guild hall has been cleared before disbanding the guild.<br>
        <form style="display:inline-block; margin:0; padding:0;" action="/community/guilds/deleteguildbyadmin" METHOD=post>
        @csrf
        @method('delete')
        <input type="hidden" name="id" value="{{$guild_id}}">
        <br>
        <input type="submit" value="Yes, delete"></form>
        </td></tr></table></div></table></div></td></tr><br/><center>
        <form action="/community/guilds" METHOD=post>
        @csrf
        <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
        <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
    @elseif($action == "guild_deleted")
        <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" ><div class="CaptionContainer" ><div class="CaptionInnerContainer" >
        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>       
        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <div class="Text" >Guild Deleted</div>
        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div></div><tr><td><div class="InnerTableContainer" >
        <table style="width:100%;" ><tr><td>Guild with ID <b>{{$guild_id}}</b> has been deleted.</td></tr></table></div></table></div></td></tr><br/><center>
        <form action="/community/guilds" METHOD=post>
        @csrf
        <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
        <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
    @endif
</x-layout>