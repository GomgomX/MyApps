<x-layout pageTitle="Account Management" subtopic="accountmanagement">
    @php 
        $layout_path = asset(config('custom.layout_path')); 
        $alreadyVerfied = auth()->guard('account')->user()->hasVerifiedEmail();
    @endphp
    @if($alreadyVerfied && !session('email_verified') && $errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
    @endif
    <div class="TableContainer" ><table class="Table1" cellpadding="0" cellspacing="0" >    
        <div class="CaptionContainer" ><div class="CaptionInnerContainer" >
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text" >Account Verification</div><span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div>    </div>  
                <tr>      <td>        <div class="InnerTableContainer" >         
        <table style="width:100%;" ><tr><td>          

    @if($alreadyVerfied && !session('email_verified'))
        Your email is verified and you can perform actions that requie email verification.
    @else
        @if(session('resend_verification'))
            {!!session('resend_verification')!!}
        @elseif(session('email_verified'))
            {!!session('email_verified')!!}
        @else
            Your email is not verified and some actions are not permitted unless your email is verified.<br>If you didn't receive the verification email, click the Resend button to request another.
        @endif
    @endif
    
    </td>
        <tr>
</table>        </div>  </table></div></td></tr><br/>
<table style="width:100%;" ><tr align="center">
    @if(!$alreadyVerfied && !session('resend_verification'))
        <td><table border="0" cellspacing="0" cellpadding="0" >
            <form action="{{route('verification.resend')}}" method="post">
            @csrf
            <tr><td style="border:0px;">
            <tr>
        <td style="border:0px;">
            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);"><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" >
                </div>
                <input class="BigButtonText" type="submit" value="Resend"></div>
            </div></td><tr></form></table></td>
    @endif
            <td><table border="0" cellspacing="0" cellpadding="0">
            <form action="/account/accountmanagement/" method="post" ><tr><td style="border:0px;">
                @csrf
                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)">
                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                    <input class="BigButtonText" type="submit" value="Back"></div></div></td></tr></form>
        </table></td></tr></table>
</x-layout>