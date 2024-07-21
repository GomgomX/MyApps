<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
    </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
    @endif
    @if(!isset($action))
        If you have lost access to your account, this interface can help you. Of course, you need to prove that your claim to the account is justified.<BR><BR>
        <FORM ACTION="/account/lostaccount/resetpassword" METHOD="post">
        @csrf
        <TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Please enter your character name or email address</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
        <INPUT TYPE="text" NAME="name_email" SIZE="40" value="{{old('name_email')}}"><BR>
        </TD></TR>
        </TABLE><BR>
        <TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Choose method</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
        <INPUT TYPE="radio" NAME="action_type" VALUE="email"{{old('action_type') == "email" ? ' checked="checked"' : ''}}> I have access to the account's email address and I want to reset the password to my account.<BR>
        <INPUT TYPE="radio" NAME="action_type" VALUE="reckey"{{old('action_type') == "reckey" ? ' checked="checked"' : ''}}> I have a <b>recovery key</b> and want to reset the password to my account.<BR>
        </TD></TR>
        </TABLE>
        <BR>
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @elseif($action == "emailaddress")
        A reset password link will be sent to the email address {!!htmlspecialchars($character != "" ? ' of '.$character.'\'s account' : $email)!!} if you submit this form.<br/>
        Please note that you need to use this link within an hour. Otherwise, it will expire and you will have to request a new one.<BR>
        <br><FORM ACTION="/account/lostaccount/sendresetlink" METHOD="post">
        @csrf
        <TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Request new password</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
        {{$character != "" ? "Character" : "Email address"}}:&nbsp;<b>{{htmlspecialchars($character != "" ? $character : $email)}}</b>
        </TD></TR>
        </TABLE>
        <BR>
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @elseif($action == "emailsent")
        A reset password link has been sent to the email address {!!htmlspecialchars($character != "" ? ' of '.$character.'\'s account' : $email)!!}.<br/>
        You need to use this link within an hour. Otherwise, it will expire and you will have to request a new one.<BR>
        <br><FORM ACTION="/account/lostaccount" METHOD="post">
        @csrf
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @elseif($action == "validatetoken")
        Enter your email address and a new password to your account.<BR>
        <br><FORM ACTION="/account/lostaccount/updatepasswordbyemail" METHOD="post">
        @csrf
        @method('put')
        <INPUT type="hidden" name="token" value="{{$token}}">
        <INPUT TYPE="hidden" NAME="email" VALUE="{{$email}}">
        <TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Please enter your new password</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
            <table style="width:100%;" >
                {{-- <tr><td>
                <span >Email Address:</span></td><td>
                <INPUT TYPE="hidden" NAME="email" VALUE="{{old('email')}}" SIZE=40></td></tr> --}}
                <tr><td>
                <span >New Password:</span></td><td>
                <input type="password" name="password" size="30" maxlength="29" ></td></tr><tr>
                    <td><span >New Password Again:</span></td><td>
                    <input type="password" name="password_confirmation" size="30" maxlength="29" ></td></tr>
                </td></tr>
            </table>
        </TABLE>
        <BR>
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @elseif($action == "recoverykey")
        Enter your recovery key to reset the password of your account.<BR>
        <br><FORM ACTION="/account/lostaccount/checkrecoverykey" METHOD="post">
        @csrf
        <TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Please enter your recovery key</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
        {{$character != "" ? "Character" : "Email address"}}:&nbsp;<b>{{htmlspecialchars($character != "" ? $character : $account->email)}}</b><BR />
        Recovery key:&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="text" NAME="rkey" VALUE="" SIZE="40"><BR>
        </TD></TR>
        </TABLE>
        <BR>
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @elseif($action == "updatepassword")
        Enter a new password to your account.<BR>
        <br><FORM ACTION="/account/lostaccount/updatepassword" METHOD="post">
        @csrf
        @method('put')
        <TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Please enter your new password</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
            <table style="width:100%;" ><tr><td><span >New Password:</span></td><td>
                <input type="password" name="password" size="30" maxlength="29" ></td></tr><tr>
                    <td><span >New Password Again:</span></td><td>
                    <input type="password" name="password_confirmation" size="30" maxlength="29" ></td></tr>
                </td></tr>
            </table>
        </TABLE>
        <BR>
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @elseif($action == "passwordchanged")
        Your password was reset.<BR>
        @if(config('custom.send_emails') && config('custom.send_mail_when_change_password'))
            @if($emailSent)
                <br /><small>Your new password was sent to email address <b>{{$email}}</b>.</small>
            @else
                <br /><small>An error occorred while sending email with password!</small>
            @endif
        @endif
            @csrf
            <BR><BR>
        <form action="/account/accountmanagement" method="post" >
        @csrf
        <tr><td style="border:0px;" >
        <TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
        <INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="{{$layout_path}}/images/buttons/sbutton_login.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
        </TD></TR></FORM></TABLE>
    @endif
</x-layout>