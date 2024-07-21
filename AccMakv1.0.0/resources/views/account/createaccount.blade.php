<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(!isset($action))
        @if($errors->any())
            <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
            @foreach($errors->all() as $error)
                <li>{!!$error!!}
            @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
        @endif
        <script type="text/javascript" src="{{$layout_path}}/createaccountvalidation.js"></script>
        To play on {{htmlspecialchars($server_config['serverName'])}} you need a account. 
        All you have to do to create your new account is to enter your email address, password to new account, verification code from picture and to agree to the terms presented below. 
        If you have done so, your account name, password and e-mail address will be shown on the following page and your account and password will be sent 
        to your email address along with further instructions.<BR><BR>
        <FORM ACTION="/account/createaccount/storeaccount" METHOD=post>
        @csrf
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Create a {{htmlspecialchars($server_config['serverName'])}} Account</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}"><TABLE BORDER=0 CELLSPACING=8 CELLPADDING=0>
        <TR><TD>
        <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0>
        <TR><TD width="150" valign="top"><B>Account name: </B></TD><TD colspan="2"><INPUT id="accountname" style="width:206px;float:left;" NAME="reg_name" SIZE=30 MAXLENGTH=30 onBlur="checkAccount()"><div id="accountname_indicator" class="InputIndicator" style="background-image:url({{$layout_path}}/images/nok.gif);"></div></TD></TR><TR><td></td><TD><span id="accountname_errormessage" class="FormFieldError"></span></TD></TR>
        <TR><TD width="150" valign="top"><B>Email address: </B></TD><TD colspan="2"><INPUT id="email" style="width:206px;float:left;" TYPE="email" NAME="reg_email" SIZE=30 MAXLENGTH=50 onBlur="checkEmail()"><div id="email_indicator" class="InputIndicator" style="background-image:url({{$layout_path}}/images/nok.gif);"></div></td></TR><TR><td></td><td><span id="email_errormessage" class="FormFieldError"></span></TD></TR>
        @if(!config('custom.create_account_verify_mail'))
          <TR><TD width="150" valign="top"><B>Password: </B></TD><TD colspan="2"><INPUT id="password1" style="width:206px;float:left;" TYPE="password" NAME="password" SIZE=30 MAXLENGTH=29 onBlur="checkPassword()"><div id="password1_indicator" class="InputIndicator" style="background-image:url({{$layout_path}}/images/nok.gif);"></div></TD></TR>
          <TR><TD width="150" valign="top"><B>Repeat password: </B></TD><TD colspan="2"><INPUT id="password2" style="width:206px;float:left;" TYPE="password" NAME="password_confirmation" SIZE=30 MAXLENGTH=29 onBlur="confirmPassword()"><div id="password2_indicator" class="InputIndicator" style="background-image:url({{$layout_path}}/images/nok.gif);"></div></TD></TR><TR><td></td><td><span id="password_errormessage" class="FormFieldError"></span></TD></TR>
        @endif
        @if(config('custom.select_flag'))
            <tr><td width="150" valign="top"><b>Country: </b></td><td colspan="2">
            <select name="reg_country">
            @php $countries = ['unknown' => 'Please choose your country', 'af' => 'Afghanistan', 'al' => 'Albania', 'dz' => 'Algeria', 'ad' => 'Andorra', 'ao' => 'Angola', 'ai' => 'Anguilla', 'ar' => 'Argentina', 'am' => 'Armenia', 'au' => 'Australia', 'at' => 'Austria', 'az' => 'Azerbaijan', 'bs' => 'Bahamas', 'bh' => 'Bahrain', 'bd' => 'Bangladesh', 'bb' => 'Barbados', 'by' => 'Belarus', 'be' => 'Belgium', 'bj' => 'Benin', 'bt' => 'Bhutan', 'bo' => 'Bolivia', 'ba' => 'Bosnia and Herzegovina', 'bw' => 'Botswana', 'br' => 'Brazil', 'bg' => 'Bulgaria', 'bf' => 'Burkina Faso', 'kh' => 'Cambodia', 'cm' => 'Cameroon', 'ca' => 'Canada', 'td' => 'Chad', 'cl' => 'Chile', 'cn' => 'China', 'co' => 'Colombia', 'cd' => 'Congo', 'cr' => 'Costa Rica', 'hr' => 'Croatia', 'cu' => 'Cuba', 'cz' => 'Czech Republic', 'dk' => 'Denmark', 'do' => 'Dominican Republic', 'ec' => 'Ecuador', 'eg' => 'Egypt', 'ee' => 'Estonia', 'et' => 'Ethiopia', 'fj' => 'Fiji', 'fi' => 'Finland', 'fr' => 'France', 'ga' => 'Gabon', 'de' => 'Germany', 'gh' => 'Ghana', 'gr' => 'Greece', 'ht' => 'Haiti', 'hk' => 'Hong Kong', 'hu' => 'Hungary', 'id' => 'Indonesia', 'iq' => 'Iraq', 'ie' => 'Ireland', 'il' => 'Israel', 'it' => 'Italy', 'jm' => 'Jamaica', 'jp' => 'Japan', 'kz' => 'Kazakhstan', 'lv' => 'Latvia', 'lt' => 'Lithuania', 'lu' => 'Luxembourg', 'mx' => 'Mexico', 'ma' => 'Morocco', 'nl' => 'Netherlands', 'nz' => 'New Zealand', 'no' => 'Norway', 'om' => 'Oman', 'pk' => 'Pakistan', 'pa' => 'Panama', 'pg' => 'Papua New Guinea', 'py' => 'Paraguay', 'pe' => 'Peru', 'pl' => 'Poland', 'pt' => 'Portugal', 'pr' => 'Puerto Rico', 'qa' => 'Qatar', 'ro' => 'Romania', 'ru' => 'Russian Federation', 'sk' => 'Slovakia', 'za' => 'South Africa', 'es' => 'Spain', 'se' => 'Sweden', 'ch' => 'Switzerland', 'tw' => 'Taiwan', 'tr' => 'Turkey', 'ua' => 'Ukraine', 'gb' => 'United Kingdom', 'us' => 'United States', 'uy' => 'Uruguay', 've' => 'Venezuela', 'vn' => 'Vietnam', 'zm' => 'Zambia', 'zw' => 'Zimbabwe']; @endphp
            @foreach ($countries as $value => $text)
                <option value="{{$value}}"{{old('reg_country') == $value ? ' selected' : ''}}>{{$text}}</option>
            @endforeach
        </select><BR><font {!!$errors->has('reg_country') ? ' color="red" ' :""!!}size="1" face="verdana,arial,helvetica">(Country is required)</font></td></tr>
        @endif
      @if(config('custom.recaptcha'))
        <tr>
          <td width="150"></td>
          <td colspan="2">
              {{-- reCAPTCHA type Challenge (v2) -> I'm not a robot" Checkbox --}}
              {!! NoCaptcha::renderJs() !!}
              {!! NoCaptcha::display() !!}
          </td>
        </tr>
      @endif
      </TABLE>
        </TD></TR>
        <TR><TD>
          <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0>
          <TR>
          <INPUT TYPE="checkbox" NAME="rules"{{old('rules') ? " checked" : ""}}/>
          <label for="rules">I agree to the <a target="blank" href="/account/serverrules">{{htmlspecialchars($server_config['serverName'])}} Rules</a>.</lable><BR>
          </TR>
          <TR><TD>
            If you fully agree to the terms, click on the "I Agree" button in order to create a {{htmlspecialchars($server_config['serverName'])}} account.<BR>
            If you do not agree to the terms or do not want to create a {{htmlspecialchars($server_config['serverName'])}} account, please click on the "Cancel" button.
          </TD></TR></TABLE>
        </TD></TR>
      </TABLE></TD></TR>
      </TABLE>
      <BR>
      <TABLE BORDER=0 WIDTH=100%>
        <TR><TD ALIGN=center>
          <IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=120 HEIGHT=1 BORDER=0><BR>
        </TD><TD ALIGN=center VALIGN=top>
          <INPUT TYPE=image NAME="I Agree" SRC="{{$layout_path}}/images/buttons/sbutton_iagree.gif" BORDER=0 WIDTH=120 HEIGHT=18>
          </FORM>
        </TD><TD ALIGN=center>
          <FORM ACTION="/news/latestnews" METHOD=post>
              @csrf
          <INPUT TYPE=image NAME="Cancel" SRC="{{$layout_path}}/images/buttons/sbutton_cancel.gif" BORDER=0 WIDTH=120 HEIGHT=18>
          </FORM>
        </TD><TD ALIGN=center>
          <IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=120 HEIGHT=1 BORDER=0><BR>
        </TD></TR>
      </TABLE>
      </TD>
      <TD><IMG SRC="{{$layout_path}}/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
      </TR>
      </TABLE>
@elseif($action == "accountcreated")
    @if(config('custom.send_emails') && config('custom.create_account_verify_mail'))
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Account Created</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
        <TABLE BORDER=0 CELLPADDING=1><TR><TD>
        Your account has been created. Check your e-mail.
        <BR>Your account name is <b>{{$account['account']}}</b>.
        <BR><BR><b><i>You will receive an e-mail on (<b>{{htmlspecialchars($account['email'])}}</b>) with your password.</b></i><br><BR>
        You will need the account name and your password to play on {{$server_config['serverName']}}.
        Please keep your account name and password in a safe place and
        never give your account name or password to anybody.<BR><BR>
        <br/><small>This information was sent to email address <b>{{htmlspecialchars($account['email'])}}</b>. Please check your inbox/spam folder.
    @else
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" CLASS=white><B>Account Created</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}">
        <TABLE BORDER=0 CELLPADDING=1><TR><TD>
        Your account has been created.
        <BR>Your account name is <b>{{$account['account']}}</b>
        <BR>Your password is <b>{{$account['password']}}</b>
        <BR><br>You will need the account name and your password to play on {{$server_config['serverName']}}.
        Please keep your account name and password in a safe place and never give your account name or password to anybody.<BR><BR>
        @if(config('custom.send_emails'))
            @if(config('custom.send_register_email'))
              @if($emailSent)
                  <br/><small>This information was sent to email address <b>{{htmlspecialchars($account['email'])}}</b>.</small>
              @else
                  <br/><small>An error occorred while sending email (<b>{{htmlspecialchars($account['email'])}}</b>)!</small>
              @endif
            @endif
            @if(config('custom.create_account_needs_verification'))
                <br/><small><u>A fresh verification link has been sent to your email address <b>{{htmlspecialchars($account['email'])}}</b>.
                <br>Log in to your account on {{$server_config['serverName']}} then access your email address and click the verification link to verify your email</u>.</small>
            @endif
        @endif
    @endif
    </TD></TR></TABLE></TD></TR></TABLE>
@endif
</x-layout>