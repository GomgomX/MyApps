<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
    @endif
    <center><h2>Change guild description</h2></center>
    <center>You can modify your guild description below. This will be displayed on the guilds page on the website.
    This can be useful if you want to display recruitment messages, accomplishments or anything else that describes your guild.<BR><BR>
    <form action="/community/guilds/savedescription" method="POST">
    @csrf
    @method('patch')
    <input type="hidden" name="id" value="{{$guild->id}}"/>
    <textarea name="description" cols="60" rows="{{(config('custom.guild_description_lines_limit')-1)}}" maxlength="550">{!!$guild->description!!}</textarea><br>
    <br>(Max. {{config('custom.guild_description_lines_limit')}} lines, Max. {{config('custom.guild_description_chars_limit')}} chars) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Save" /></form></center>
    <br><center>
    <form action="/community/guilds/manage/{{$guild->id}}" METHOD=post>
    @csrf
    <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
    <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
</x-layout>