<x-layout :$pageTitle :$subtopic>
    @php 
        $layout_path = asset(config('custom.layout_path'));
        $max_image_size_b = config('custom.guild_image_size_kb') * 1024;
    @endphp
    @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
    @endif
    <center><h2>Change guild logo</h2>You can upload a guild logo below. This will be displayed on the guilds page.<BR><BR><b>Current logo:</b><BR><img src="{{$guild->getGuildLogoLink()}}" HEIGHT="64" WIDTH="64"><BR><BR>
    <form enctype="multipart/form-data" action="/community/guilds/savelogo" method="POST">
    @csrf
    @method('patch')
    <input type="hidden" name="id" value="{{$guild->id}}"/>
    <input type="hidden" name="MAX_FILE_SIZE" value="{{$max_image_size_b}}"/>
    Select new logo: <input type="file" name="newlogo"/>
    @php
        $foundExtensions = [];
        foreach(config('custom.guild_image_allowed_types') as $extension) {
            if(!in_array($extension, $foundExtensions)) {
                $foundExtensions[] = $extension;
            }
        }
        $foundExtensionsCount = count($foundExtensions);
        $allowedExtensions = '';
        foreach($foundExtensions as $index => $extension) {
            $allowedExtensions .= ($index+1 == $foundExtensionsCount ? ' and ' : ($index+1 > 1 ? ', ' : '')).'<b>'.$extension.'</b>';
        }
    @endphp
    <input type="submit" value="Upload" /></form>Only <b>{!!$allowedExtensions!!}</b> images are allowed. Max. size: <b>{{config('custom.guild_image_size_kb')}} KB</b><br>
    <br/>
    <form action="/community/guilds/manage/{{$guild->id}}" METHOD=post>
    @csrf
    <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
    <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
</x-layout>