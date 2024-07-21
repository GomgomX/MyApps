<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(!auth()->guard('account')->check())
        @if($errors->any())
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        @foreach($errors->all() as $error)
            <li>{!!$error!!}
        @endforeach
        </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
        @endif 
        Please enter your account name and your password.<br/>
        <a href="/account/createaccount" >Create an account</a> if you do not have one yet.
        <br/><br/>
        <form action="/account/accountmanagement/login" method="post">
            @csrf
            <div class="TableContainer">  
                <table class="Table1" cellpadding="0">
                    <div class="CaptionContainer">      
                        <div class="CaptionInnerContainer">
                            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
                            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
                            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
                            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        
                            <div class="Text">Account Login</div>        
                            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        
                            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
                            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
                            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
                        </div>    
                    </div>    
                    <tr>      
                        <td>        
                            <div class="InnerTableContainer">          
                                <table style="width:100%;" cellspacing="8">
                                    <tr><td class="LabelV">
                                        <span >Account Name:</span></td><td style="width:100%;" >
                                            <input type="password" name="account" size="35" maxlength="30" >
                                            {{-- @error('account')
                                            <div class="color: red">{{$message}}</div>
                                            @enderror --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="LabelV">
                                            <span >Password:</span>
                                        </td>
                                        <td>
                                            <input type="password" name="password" size="35" maxlength="29" >
                                            {{-- @error('password')
                                                <div class="color: red">{{$message}}</div>
                                            @enderror --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="width:100%;">
                                            {{-- reCAPTCHA type Challenge (v2) -> I'm not a robot" Checkbox --}}
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! NoCaptcha::display() !!}
                                            {{-- @error('g-recaptcha-response')
                                                <div class="color: red">{{$message}}</div>  
                                            @enderror --}}
                                        </td>
                                    </tr>
                                </table>
                                <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0>
                                    <TR><TD>
                                    <input type="checkbox" name="remember"{{old('remember') ? ' checked="checked"' : ""}}>Remember me
                                    </TD></tr>
                                </table>
                            </div>  
                        </table>
                    </div>
                </td>
            </tr>
            <br/>
            <table width="100%" >
                <tr align="center" >
                    <td>
                        <table border="0" cellspacing="0" cellpadding="0" >
                            <tr>
                                <td style="border:0px;" >
                                    <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" >
                                            </div>
                                            <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif" >
                                        </div>
                                    </div>
                                </td>
                                <tr>
                                    </form>
                                </table>
                            </td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" >
                                    <form action="/account/lostaccount" method="post">
                                        @csrf
                                        <tr>
                                            <td style="border:0px;" >
                                                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" >
                                                        </div>
                                                        <input class="ButtonText" type="image" name="Account lost?" alt="Account lost?" src="{{$layout_path}}/images/buttons/_sbutton_accountlost.gif">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>
                                </table>
                            </td>
                        </tr>
                </table>
    @else
        @php $account_logged = auth()->guard('account')->user(); @endphp
        @if(!isset($action))
            @if(session('action') && (session('action') == "undeleted" && session('action') == "registered"))
                @if($errors->any())
                    <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                    @foreach($errors->all() as $error)
                        <li>{!!$error!!}
                    @endforeach
                    </div><div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
                @endif 
                <center>
                <table border="0" cellspacing="0" cellpadding="0" >
                <form action="/account/accountmanagement" method="post" ><tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                @csrf
                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
            @else
                @php
                    $account_reckey = $account_logged->key;
                    if($account_logged->premdays > 0)
                        $account_status = '<b><font color="green">Premium Account, '.$account_logged->premdays.' days left</font></b>';
                    else
                        $account_status = '<b><font color="red">Free Account</font></b>';
                    if(empty($account_reckey))
                        $account_registred = '<b><font color="red">No</font></b>';
                    else
                        if(config('custom.generate_new_reckey') && config('custom.send_emails'))
                            $account_registred = '<b><font color="green">Yes ( <a href="/account/accountmanagement/newreckey"> Buy new Rec key </a> )</font></b>';
                        else
                            $account_registred = '<b><font color="green">Yes</font></b>';
                    $account_created = $account_logged->created;
                    $account_email = $account_logged->email;
                    $account_email_new_time = $account_logged->email_new_time;
                    if($account_email_new_time > 1)
                        $account_email_new = $account_logged->email_new;
                    $account_rlname = $account_logged->rlname;
                    $account_location = $account_logged->location;
                    $accountBanned = $account_logged->banned();
                    if($accountBanned)
                        if($accountBanned->expires > 0)
                            $welcome_msg = '<font color="red">Your account is banished until '.date("j F Y, G:i:s", $accountBanned->expires).'!</font>';
                        else
                            $welcome_msg = '<font color="red">Your account is banished FOREVER!</font>';
                    else
                        $welcome_msg = 'Welcome to your account!';
                @endphp
                
                <div class="SmallBox" >  <div class="MessageContainer" >
                    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" />
                </div><div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>
                <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div><div class="Message" >      
                    <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>
                    <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>
                    <table>
                        <td width="100%"><nobr>[<a href="#General+Information" >General Information</a>]</nobr> <nobr>[<a href="#Public+Information" >Public Information</a>]</nobr> <nobr>[<a href="#Characters" >Characters</a>]</nobr></td><td><table border="0" cellspacing="0" cellpadding="0" >
                        <form action="/account/accountmanagement/logout" method="post">
                        @csrf
                        <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" >
                                </div><input class="ButtonText" type="image" name="Logout" alt="Logout" src="{{$layout_path}}/images/buttons/_sbutton_logout.gif" >
                            </div></div></td></tr></form></table></td></tr>
                        </table>    
                    </div><div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    
                    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    
                    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div>
                    <br/><center><table><tr><td><img src="{{$layout_path}}/images/content/headline-bracer-left.gif" /></td>
                        <td style="text-align:center;vertical-align:middle;horizontal-align:center;font-size:17px;font-weight:bold;" >{!!$welcome_msg!!}
                            <br/></td><td><img src="{{$layout_path}}/images/content/headline-bracer-right.gif" /></td></tr></table><br/>
                        </center>

                @if(empty($account_reckey))
                    <div class="SmallBox" ><div class="MessageContainer" >    
                        <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>
                        <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>
                        <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>
                        <div class="Message" ><div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>
                        <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>
                        <table><tr><td class="LabelV" >Hint:</td><td style="width:100%;" > 
                            <font color="red"> <b><h3>CREATE YOUR RECOVERY KEY AND SAVE IT! WE CANT GIVE YOUR CHAR BACK IF YOU GOT HACKED!!!!!! Click on "Register Account" and get your free recovery key today!</b></font></h3>
                        </td></tr></table><div align="center" >
                            <table border="0" cellspacing="0" cellpadding="0" >
                                <form action="/account/accountmanagement/registeraccount" method="post">
                                @csrf
                                <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)">
                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);">
                                        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                        <input class="ButtonText" type="image" name="Register Account" alt="Register Account" src="{{$layout_path}}/images/buttons/_sbutton_registeraccount.gif" ></div></div></td></tr></form></table></div></div>    
                                        <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>
                                        <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>
                                        <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  
                                    </div></div><br/>
                @endif
                @if($account_email_new_time > 1)
                    @if($account_email_new_time < time())
                        @php $account_email_change = '<br>(You can accept <b>'.htmlspecialchars($account_email_new).'</b> as a new email.)'; @endphp
                    @else
                        @php $account_email_change = ' <br>You can accept <b>new e-mail after '.date("j F Y", $account_email_new_time).".</b>"; @endphp
                        <div class="SmallBox" >  <div class="MessageContainer" >    
                            <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    
                            <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    
                            <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    
                            <div class="Message" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      
                            <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>
                            <table><tr><td class="LabelV" >Note:</td>
                                <td style="width:100%;" >A request has been submitted to change the email address of this account to <b>{{htmlspecialchars($account_email_new)}}</b>. After <b>{{date("j F Y, G:i:s", $account_email_new_time)}}</b> you can accept the new email address and finish the process. Please cancel the request if you do not want your email address to be changed! Also cancel the request if you have no access to the new email address!</td></tr></table>
                                <div align="center" ><table border="0" cellspacing="0" cellpadding="0" >
                                    <form action="/account/accountmanagement/changeemail" method="post" >
                                        @csrf
                                        <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                <input class="ButtonText" type="image" name="Edit" alt="Edit" src="{{$layout_path}}/images/buttons/_sbutton_edit.gif" ></div></div>
                                            </td></tr></form></table></div>    </div>    
                                            <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    
                                            <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    
                                            <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  
                                        </div></div><br/><br/>
                    @endif
                @endif
                <a name="General+Information"></a>
                <div class="TopButtonContainer" ><div class="TopButton" ><a href="#top" >	<image style="border:0px;" src="{{$layout_path}}/images/content/back-to-top.gif" /></a></div></div>
                    <div class="TableContainer" ><table class="Table3" cellpadding="0" cellspacing="0" >    
                        <div class="CaptionContainer" ><div class="CaptionInnerContainer" >  
                            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                            <div class="Text" >General Information</div><span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div>    </div>
                            <tr>      <td>        <div class="InnerTableContainer" >          
                                <table style="width:100%;" ><tr><td><div class="TableShadowContainerRightTop" >  
                                    <div class="TableShadowRightTop" style="background-image:url({{$layout_path}}/images/content/table-shadow-rt.gif);" ></div></div>
                                    <div class="TableContentAndRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-rm.gif);" >
                                        <div class="TableContentContainer" >    <table class="TableContent" width="100%" >
                                            <tr style="background-color:{{config('custom.darkborder')}};" >
                                            <td class="LabelV" >Email Address:</td><td style="width:90%;" >{{htmlspecialchars($account_email).(isset($account_email_change) ? $account_email_change : '')}}</td></tr>
                                            <tr style="background-color:{{config('custom.lightborder')}};" ><td class="LabelV" >Created:</td><td>{{date("j F Y, G:i:s", $account_created)}}</td></td>
                                                <tr style="background-color:{{config('custom.darkborder')}};" ><td class="LabelV" >Last Login:</td><td>{{date("j F Y, G:i:s", time())}}</td></tr>
                                                <tr style="background-color:{{config('custom.lightborder')}};" ><td class="LabelV" >Account Status:</td><td>{!!$account_status!!}</td></tr>
                                                <tr style="background-color:{{config('custom.darkborder')}};" ><td class="LabelV" >Registred:</td><td>{!!$account_registred!!}</td></tr></table>
                                            </div></div><div class="TableShadowContainer" ><div class="TableBottomShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bm.gif);" >
                                                <div class="TableBottomLeftShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bl.gif);" ></div>
                                                <div class="TableBottomRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-br.gif);" ></div>  </div></div></td></tr>
                                                <tr><td><table class="InnerTableButtonRow" cellpadding="0" cellspacing="0" ><tr><td><table border="0" cellspacing="0" cellpadding="0" >
                                                    <form action="/account/accountmanagement/changepassword" method="post" >
                                                    @csrf
                                                        <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                                <input class="ButtonText" type="image" name="Change Password" alt="Change Password" src="{{$layout_path}}/images/buttons/_sbutton_changepassword.gif" ></div></div></td></tr>
                                                            </form></table></td>
                @if(config('custom.can_change_email'))
                        <td><table border="0" cellspacing="0" cellpadding="0" >
                        <form action="/account/accountmanagement/changeemail" method="post" >
                            @csrf
                            <tr><td style="border:0px;" >
                                <input type="hidden" name="newemail" value="">
                                <input type="hidden" name="newemaildate" value=0 >
                                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                        <input class="ButtonText" type="image" name="Change Email" alt="Change Email" src="{{$layout_path}}/images/buttons/_sbutton_changeemail.gif" >
                                    </div></div></td></tr></form>	
                                </table></td>
                @endif
                <td width="100%"></td>
                @if(empty($account_reckey))
                    <td><table border="0" cellspacing="0" cellpadding="0" >
                        <form action="/account/accountmanagement/registeraccount" method="post" >
                            @csrf
                            <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                <input class="ButtonText" type="image" name="Register Account" alt="Register Account" src="{{$layout_path}}/images/buttons/_sbutton_registeraccount.gif" ></div></div></td></tr></form></table></td>
                @endif
                </tr></table></td></tr></table></div></table></div></td></tr><br/><a name="Public+Information" ></a><div class="TopButtonContainer" ><div class="TopButton" ><a href="#top" ><image style="border:0px;" src="{{$layout_path}}/images/content/back-to-top.gif" /></a></div></div><div class="TableContainer" >  <table class="Table5" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >      
                    <div class="CaptionInnerContainer" ><span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                        <div class="Text" >Public Information</div><span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div>    </div>    
                        <tr>      <td>        <div class="InnerTableContainer" >     
                            <table style="width:100%;" ><tr><td><div class="TableShadowContainerRightTop" >  <div class="TableShadowRightTop" style="background-image:url({{$layout_path}}/images/content/table-shadow-rt.gif);" ></div></div>
                                <div class="TableContentAndRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-rm.gif);" >
                                    <div class="TableContentContainer" >    <table class="TableContent" width="100%" ><tr><td>
                                        <table style="width:100%;"><tr><td class="LabelV" >Real Name:</td><td style="width:90%;">{{$account_rlname}}</td></tr>
                                            <tr><td class="LabelV" >Location:</td><td style="width:90%;">{{$account_location}} &nbsp;&nbsp;<img src="{{config('custom.flag_images_url').$account_logged['flag'].config('custom.flag_images_extension')}}" title="Country: {{$account_logged['flag']}}" alt="{{$account_logged['flag']}}"/></td></tr></table></td>
                                            <td align=right><table border="0" cellspacing="0" cellpadding="0" >
                                                <form action="/account/accountmanagement/changeinfo" method="post" >
                                                    @csrf
                                                    <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                            <input class="ButtonText" type="image" name="Edit" alt="Edit" src="{{$layout_path}}/images/buttons/_sbutton_edit.gif" ></div></div>
                                                        </td></tr>
                                                    </form>
                                                </table></td></tr>    </table>  </div></div>
                                                        <div class="TableShadowContainer" >  <div class="TableBottomShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bm.gif);" >   
                                                            <div class="TableBottomLeftShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bl.gif);" ></div>   
                                                            <div class="TableBottomRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-br.gif);" ></div>  </div></div></td></tr>          </table>        </div>  </table>
                                                        </div></td></tr><br/>
                <a name="Characters" ></a><div class="TopButtonContainer" ><div class="TopButton" ><a href="#top" ><image style="border:0px;" src="{{$layout_path}}/images/content/back-to-top.gif" /></a></div></div><div class="TableContainer" >
                    <table class="Table3" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" ><div class="CaptionInnerContainer" >        
                        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                        <div class="Text" >Characters</div>        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span><span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span><span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div>    </div>   
                        <tr>      <td>        <div class="InnerTableContainer" >          <table style="width:100%;" >
                            <tr><td><div class="TableShadowContainerRightTop" >
                                <div class="TableShadowRightTop" style="background-image:url({{$layout_path}}/images/content/table-shadow-rt.gif);" ></div></div>
                                <div class="TableContentAndRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-rm.gif);" >
                                    <div class="TableContentContainer" >    <table class="TableContent" width="100%" ><tr class="LabelH" ><td style="width:65%" >Name</td>
                                        <td style="width:15%" >Level</td><td style="width:7%">Status</td><td style="width:5%">&#160;</td></tr>
                
                @php 
                    $account_players = $account_logged->getPlayers;
                    $player_number_counter = 0;
                @endphp
                @foreach($account_players as $account_player)
                    @php $player_number_counter++; @endphp
                    <tr style="background-color:{{is_int($player_number_counter / 2) ? config('custom.darkborder') : config('custom.lightborder')}}">
                    <td><NOBR>{{$player_number_counter}}.&#160;{{htmlspecialchars($account_player->name)}}
                    
                    @if($account_player->deleted)
                        <font color="red"><b> [ DELETED ] </b> <a href="/account/accountmanagement/undelete/{{rawurlencode($account_player->name)}}">>> UNDELETE <<</a></font>
                    @endif
                    </NOBR></td><td><NOBR>{{$account_player->level}} {{htmlspecialchars(Website::getVocationName($account_player->vocation))}}</NOBR></td>
                    @if(!$account_player->online)
                        <td><font color="red"><b>Offline</b></font></td>
                    @else
                        <td><font color="green"><b>Online</b></font></td>
                    @endif
                    <td>[<a href="/account/accountmanagement/editcharacter/{{rawurlencode($account_player->name)}}" >Edit</a>]</td></tr>
                @endforeach
                @if($player_number_counter == 0)
                <tr style="background-color:{{is_int($player_number_counter / 2) ? config('custom.lightborder') : config('custom.darkborder')}}"><td colspan="4">
                    <font color="red">Your account doesn't have characters. Create one!</font></td></tr>
                @endif
                </table>  </div></div><div class="TableShadowContainer" >  
                    <div class="TableBottomShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bm.gif);" >    
                        <div class="TableBottomLeftShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bl.gif);" ></div>    
                        <div class="TableBottomRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-br.gif);" ></div>  </div></div></td>
                    </tr><tr><td><table class="InnerTableButtonRow" cellpadding="0" cellspacing="0" ><tr><td>
                        <table border="0" cellspacing="0" cellpadding="0" >
                            <form action="/account/accountmanagement/createcharacter" method="post" >
                                @csrf
                                <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                        <input class="ButtonText" type="image" name="Create Character" alt="Create Character" src="{{$layout_path}}/images/buttons/_sbutton_createcharacter.gif" ></div></div></td></tr>
                            </form></table></td>
                                        <td style="width:100%;" ></td><td><table border="0" cellspacing="0" cellpadding="0" >
                            <form action="/account/accountmanagement/deletecharacter" method="post" >
                                @csrf
                                                <tr><td style="border:0px;" >
                                                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                    <input class="ButtonText" type="image" name="Delete Character" alt="Delete Character" src="{{$layout_path}}/images/buttons/_sbutton_deletecharacter.gif" ></div></div></td></tr></form></table></td></tr></table></td></tr>          </table>       
                                                </div>  </table></div></td></tr>
            @endif
        @elseif($action == "logoutaccount")
            @php $guest = request()->session()->get('url.intended'); @endphp
            @if($guest != null)
                You must log out to continue. Some actions are not permitted unless you're logged out.
            @else
                Are you sure you want to log out?
            @endif
            <br><br>
            <table style="width:100%;" ><tr align="center">
            <td><table border="0" cellspacing="0" cellpadding="0" >
                <form action="/account/accountmanagement/logout" method="post">
                @csrf
                <tr><td style="border:0px;" ><tr>
            <td style="border:0px;" >
                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);"><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" >
                    </div>
                    <input class="BigButtonText" type="submit" value="Logout"></div>
                </div></td><tr></form></table></td>
            @if(!$guest)
                <td><table border="0" cellspacing="0" cellpadding="0" >
                <form action="/account/accountmanagement/" method="post" ><tr><td style="border:0px;" >
                    @csrf
                    <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                        <input class="BigButtonText" type="submit" value="Back"></div></div></td></tr></form>
            </table></td>
            @endif
            </tr></table>
        @elseif($action == "changepassword")
            @if($errors->any())
            <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
            </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            @endif
            Please enter your current password and a new password. For your security, please enter the new password twice.<br/><br/>
            <form action="/account/accountmanagement/updatepassword" method="post">
                @csrf
                @method('put')
                <div class="TableContainer" ><table class="Table1" cellpadding="0" cellspacing="0" >    
                    <div class="CaptionContainer" ><div class="CaptionInnerContainer" >
                        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                        <div class="Text" >Change Password</div><span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div>    </div>  
                        <tr>      <td>        <div class="InnerTableContainer" >         
                            <table style="width:100%;" ><tr><td class="LabelV" ><span >New Password:</span></td><td style="width:90%;" >
                                <input type="password" name="password" size="30" maxlength="29" ></td></tr><tr>
                                    <td class="LabelV" ><span >New Password Again:</span></td><td>
                                    <input type="password" name="password_confirmation" size="30" maxlength="29" ></td></tr>
                                    <tr><td class="LabelV" ><span >Current Password:</span></td><td><input type="password" name="oldpassword" size="30" maxlength="29" ></td></tr>
                                </table>        </div>  </table></div></td></tr><br/>
                                <table style="width:100%;" ><tr align="center"><td><table border="0" cellspacing="0" cellpadding="0" ><tr>
                                    <td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                            <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif" ></div>
                                        </div></td><tr></form></table></td><td><table border="0" cellspacing="0" cellpadding="0" >
                                            <form action="/account/accountmanagement/" method="post" ><tr><td style="border:0px;" >
                                                @csrf
                                                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                    <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form>
                                                </table></td></tr></table>
        @elseif($action == "passwordchanged")
            <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >      
                <div class="CaptionInnerContainer" >        
                    <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
                    <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
                    <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>      
                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>      
                        <div class="Text" >Password Changed</div>       
                        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>    
                            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>  
                                    <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>   
                                        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                                            </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >       
                                                <table style="width:100%;" ><tr><td>Your password has been changed.
                        @if(config('custom.send_emails') && config('custom.send_mail_when_change_password'))
                            @if($emailSent)
                                <br /><small>Your new password was sent to email address <b>{{htmlspecialchars(auth()->guard('account')->user()->email)}}</b>.</small>
                            @else
                                <br /><small>An error occorred while sending email with password!</small>
                            @endif
                        @endif
                </td></tr>          </table>        </div>  </table></div></td></tr><br/><center>
                    <table border="0" cellspacing="0" cellpadding="0" >
                        <form action="/account/accountmanagement/" method="post" >
                            @csrf
                            <tr><td style="border:0px;" >
                            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                    <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" >
                                </div></div></td></tr></form></table></center>
        
        @elseif($action == "createcharacter")
            @if($errors->any())
                <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
                </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            @endif
            <script type="text/javascript" src="{{$layout_path}}/createcharactervalidation.js"></script>
            Please choose a name{{count(config('custom.newchar_vocations')) > 1 ? ', vocation' : ''}} and sex for your character. <br/>In any case the name must not violate the naming conventions stated in the <a href="/account/tibiarules" target="_blank" >{{htmlspecialchars($server_config['serverName'])}} Rules</a>, or your character might get deleted or name locked.
            @if(count(auth()->guard('account')->user()->getPlayers) >= config('custom.max_players_per_account'))
                <b><font color="red"> You have maximum number of characters per account on your account. Delete one before you create new.</font></b>
            @endif
            
            <br/><br/>
            <form action="/account/accountmanagement/storecharacter" method="post" >
                @csrf
                <div class="TableContainer" >  <table class="Table3" cellpadding="0" cellspacing="0" >    
                    <div class="CaptionContainer" >      
                        <div class="CaptionInnerContainer" >
                            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                            <div class="Text" >Create Character</div>        
                            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                        </div>    </div><tr>      <td>        <div class="InnerTableContainer" >          
                            <table style="width:100%;" ><tr><td><div class="TableShadowContainerRightTop" >  <div class="TableShadowRightTop" style="background-image:url({{$layout_path}}/images/content/table-shadow-rt.gif);" ></div></div><div class="TableContentAndRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-rm.gif);" >  
                                <div class="TableContentContainer" ><table class="TableContent" width="100%" ><tr class="LabelH" ><td style="width:50%;" >
                                    <span >Name</td><td><span >Sex</td></tr><tr class="Odd" ><td>
                                    <input id="charname" style="width:206px;float:left;" name="newcharname" size="30" maxlength="29" onBlur="checkName()"><div id="charname_indicator" class="InputIndicator" style="background-image:url({{$layout_path}}/images/nok.gif);"></div><br><span id="charname_errormessage" class="FormFieldError"></span></TD>
                        <td>
            <input type="radio" name="newcharsex" value="1" {{old('newcharsex') == 1 ? 'checked="checked" ' : ''}}>male<br/>
            <input type="radio" name="newcharsex" value="0" {{old('newcharsex') == "0" ? 'checked="checked" ' : ''}}>female<br/><br/></td></tr></table></div></div></table></div>
            
            @if(count(config('custom.newchar_towns')) > 1 || count(config('custom.newchar_vocations')) > 1)
                <div class="InnerTableContainer" >          <table style="width:100%;" ><tr>
            @endif
            @if(count(config('custom.newchar_vocations')) > 1)
                <td><table class="TableContent" width="100%" ><tr class="Odd" valign="top"><td width="160"><br /><b>Select your vocation:</b></td><td><table class="TableContent" width="100%" >
                @foreach(config('custom.newchar_vocations') as $char_vocation_key => $sample_char)
                    <tr><td><input type="radio" name="newcharvocation" value="{{$char_vocation_key}}" {{old('newcharvocation') == $char_vocation_key ? 'checked="checked"' : ''}}>{{htmlspecialchars(Website::getVocationName($char_vocation_key))}}</td></tr>
                @endforeach
                </table></table></td>
            @endif
            @if(count(config('custom.newchar_towns')) > 1)
                <td><table class="TableContent" width="100%" ><tr class="Odd" valign="top"><td width="160"><br /><b>Select your city:</b></td><td><table class="TableContent" width="100%">
                @foreach(config('custom.newchar_towns') as $town_id)
                    <tr><td><input type="radio" name="newchartown" value="{{$town_id}}" {{old('newchartown') == $town_id ? 'checked="checked" ' : ''}}>{{htmlspecialchars(config('custom.towns_list')[$town_id])}}</td></tr>
                @endforeach
                </table></table></td>
            @endif
            @if(count(config('custom.newchar_towns')) > 1 || count(config('custom.newchar_vocations')) > 1)
                </tr></table></div>
            @endif
            </table></div></td></tr><br/><table style="width:100%;" ><tr align="center" ><td><table border="0" cellspacing="0" cellpadding="0" ><tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                    <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif" ></div></div></td><tr>
                    </form></table></td><td>
                        <table border="0" cellspacing="0" cellpadding="0" >
                            <form action="/account/accountmanagement" method="post" >
                            @csrf
                            <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr>
                            </form></table></td></tr></table>
        @elseif($action=="deletecharacter")
            @if($errors->any())
                <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
                </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            @endif
            To delete a character enter the name of the character and your password.<br/><br/>
            <form action="/account/accountmanagement/markdeleted" method="post">
                @csrf
                @method('delete')
                <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    
                    <div class="CaptionContainer" >      <div class="CaptionInnerContainer" >      
                        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span> 
                                <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span> 
                                        <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>    
                                            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>     
                                            <div class="Text" >Delete Character</div>     
                                                <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span> 
                                                        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>     
                                                            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>   
                                                                <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      </div>    </div> 
                                                                    <tr>      <td>        <div class="InnerTableContainer" >     
                                                                            <table style="width:100%;" ><tr><td class="LabelV" ><span >Character Name:</td><td style="width:90%;" >
                                                    <input name="delete_name" value="" size="30" maxlength="29" ></td></tr>
                                                                                <tr><td class="LabelV" ><span >Password:</td><td>
                                                <input type="password" name="delete_password" size="30" maxlength="29" ></td></tr>          </table>        </div>
                                                </table></div></td></tr>
                                                                                    <br/><table style="width:100%" ><tr align="center" ><td>
                                                                                        <table border="0" cellspacing="0" cellpadding="0" ><tr><td style="border:0px;" >
                                                                                            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                                                                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                                                    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                                                                    <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif" ></div></div></td><tr>
                                                                                                        </form></table></td><td><table border="0" cellspacing="0" cellpadding="0" >
                                                                                                            <form action="/account/accountmanagement" method="post" ><tr><td style="border:0px;" >
                                                                                                                @csrf
                                                                                                                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                                                                                    <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                                                                        <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                                                                                        <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr>
                                                                                                                    </form></table></td></tr></table>
        @elseif($action=='markeddeleted')
            <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >   
                <div class="CaptionContainer" >      <div class="CaptionInnerContainer" >    
                        <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>   
                            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>  
                                <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>      
                                            <div class="Text" >Character Deleted</div>     
                                                <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>   
                                                    <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>   
                                                        <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>    
                                                            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>  
                                                                </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >       
                                                                    <table style="width:100%;" ><tr><td>The character <b>{{htmlspecialchars($deleted_char)}}</b> has been deleted.</td></tr>      
                                                                        </table>        </div>  </table></div></td></tr><br><center><table border="0" cellspacing="0" cellpadding="0" >
                                                                            <form action="/account/accountmanagement" method="post" >
                                                                                @csrf
                                                                                <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                                                <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
        @elseif($action=="undeleted")
            <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >    
                <div class="CaptionInnerContainer" >        
                    <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>  
                        <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span> 
                                <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>       
                                        <div class="Text" >Character Undeleted</div>        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>   
                                            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>    
                                                <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>   
                                                        <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >     
                                                                <table style="width:100%;" ><tr><td>The character <b>{{htmlspecialchars($undeleted_char)}}</b> has been undeleted.</td></tr>          </table>        </div>  </table></div></td></tr><br><center>
                                                                    <table border="0" cellspacing="0" cellpadding="0" >
                                                                        <form action="/account/accountmanagement" method="post" ><tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                                            @csrf
                                                                            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
        @elseif($action=="charactercreated")
            <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >      
                <div class="CaptionInnerContainer" >        
                    <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>       
                    <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
                    <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>      
                        <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>   
                            <div class="Text" >Character Created</div>        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>     
                                <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>     
                                    <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
                                    <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      </div>    </div> 
                                        <tr>      <td>        <div class="InnerTableContainer" >       
                                            <table style="width:100%;" ><tr><td>The character <b>{{$newchar_name}}</b> has been created.<br/>Please select the outfit when you log in for the first time.<br/><br/><b>See you on {{$server_config['serverName']}}!</b></td></tr>          </table>        </div>  </table></div></td></tr><br/>
                                            <center><table border="0" cellspacing="0" cellpadding="0" >
                                                <form action="/account/accountmanagement" method="post" >
                                                    @csrf
                                                    <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
        @elseif($action == "registeraccount")
            @if($errors->any())
                <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
                </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            @endif
            Please enter your password to generate recovery key for your account.<br/>
            <br/><form action="/account/accountmanagement/generatekey" method="post" >
                @csrf
                <div class="TableContainer" >  
                    <table class="Table1" cellpadding="0" cellspacing="0" > 
                        <div class="CaptionContainer" >      <div class="CaptionInnerContainer" >       
                            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>     
                                <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>       
                                <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>    
                                    <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>       
                                    <div class="Text" >Generate recovery key</div>    
                                        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>    
                                            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>     
                                                <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>       
                                                <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>    
                                                </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >        
                                                    <table style="width:100%;" ><tr><td class="LabelV" ><span >Password:</td><td><input type="password" name="password" size="30" maxlength="29" >
                                                    </td></tr>          </table>        </div>  </table></div></td></tr><br/><table style="width:100%" >
                                                        <tr align="center" ><td><table border="0" cellspacing="0" cellpadding="0" ><tr><td style="border:0px;" >
                                                            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                                    <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif" ></div></div></td><tr></form></table>
                                                                    </td><td><table border="0" cellspacing="0" cellpadding="0" >
                                                                        <form action="/account/accountmanagement" method="post" >
                                                                            @csrf
                                                                            <tr><td style="border:0px;" >
                                                                        <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                                                                            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                                                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                                                                <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form>
                                                                            </table></td></tr></table>
            
        @elseif($action == "keygenerated")
            <div class="TableContainer" >  <table class="Table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >    
                <div class="CaptionInnerContainer" >        
                    <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
                    <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>  
                            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>    
                                <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>  
                                    <div class="Text" >Account Registered</div>     
                                        <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        
                                        <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>   
                                            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>     
                                                <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>    
                                                </div>    </div>    <tr>      <td>        <div class="InnerTableContainer" >        
                                                    <table style="width:100%;" >You can now recover your account if you have lost access to it by using the following key<br/><br/>
                                                        <font size="5">&nbsp;&nbsp;&nbsp;<b>Recovery Key: {{$key}}</b></font><br/><br/><br/><b>
                                                            Important:</b><ul><li>Write down this recovery key carefully.</li><li>Store it at a safe place!</li>
            @if(config('custom.send_emails') && config('custom.send_mail_when_generate_reckey'))
                @if($emailSent)
                    <br /><small>Your recovery key was sent to your email address <b>{{$email}}</b>.</small>
                @else
                    <br /><small>An error occorred while sending email with recovery key! You will not receive e-mail with this key.</small>
                @endif
            @endif
            </ul>          </table>        </div>  </table></div></td></tr><br/><center>
                <table border="0" cellspacing="0" cellpadding="0" >
                    <form action="/account/accountmanagement" method="post" >
                        @csrf
                        <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                                <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr>
                            </form></table></center>
        @elseif($action == "changeinfo")
            @if($errors->any())
            <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
            </div>    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
            @endif
            Here you can tell other players about yourself. This information will be displayed alongside the data of your characters. If you do not want to fill in a certain field, just leave it blank.<br/>
            <br/><form action="/account/accountmanagement/saveinfo" method=post>
            @csrf
            @method('put')
            <div class="TableContainer" ><table class="Table1" cellpadding="0" cellspacing="0" ><div class="CaptionContainer" ><div class="CaptionInnerContainer" >
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text">Change Public Information</div>
            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            </div></div><tr><td><div class="InnerTableContainer">
            <table style="width:100%;"><tr><td class="LabelV">Real Name:</td><td style="width:90%;" >
            <input name="real_name" value="{{$account_logged->rlname}}" size="30" maxlength="65"></td></tr><tr>
            <td class="LabelV" >Location:</td><td>
            <input name="location" value="{{$account_logged->location}}" size="30" maxlength="30"></td></tr>
            <tr><td class="LabelV">Country:</td><td>
            <select name="country">
                @php $countries = ['af' => 'Afghanistan', 'al' => 'Albania', 'dz' => 'Algeria', 'ad' => 'Andorra', 'ao' => 'Angola', 'ai' => 'Anguilla', 'ar' => 'Argentina', 'am' => 'Armenia', 'au' => 'Australia', 'at' => 'Austria', 'az' => 'Azerbaijan', 'bs' => 'Bahamas', 'bh' => 'Bahrain', 'bd' => 'Bangladesh', 'bb' => 'Barbados', 'by' => 'Belarus', 'be' => 'Belgium', 'bj' => 'Benin', 'bt' => 'Bhutan', 'bo' => 'Bolivia', 'ba' => 'Bosnia and Herzegovina', 'bw' => 'Botswana', 'br' => 'Brazil', 'bg' => 'Bulgaria', 'bf' => 'Burkina Faso', 'kh' => 'Cambodia', 'cm' => 'Cameroon', 'ca' => 'Canada', 'td' => 'Chad', 'cl' => 'Chile', 'cn' => 'China', 'co' => 'Colombia', 'cd' => 'Congo', 'cr' => 'Costa Rica', 'hr' => 'Croatia', 'cu' => 'Cuba', 'cz' => 'Czech Republic', 'dk' => 'Denmark', 'do' => 'Dominican Republic', 'ec' => 'Ecuador', 'eg' => 'Egypt', 'ee' => 'Estonia', 'et' => 'Ethiopia', 'fj' => 'Fiji', 'fi' => 'Finland', 'fr' => 'France', 'ga' => 'Gabon', 'de' => 'Germany', 'gh' => 'Ghana', 'gr' => 'Greece', 'ht' => 'Haiti', 'hk' => 'Hong Kong', 'hu' => 'Hungary', 'id' => 'Indonesia', 'iq' => 'Iraq', 'ie' => 'Ireland', 'il' => 'Israel', 'it' => 'Italy', 'jm' => 'Jamaica', 'jp' => 'Japan', 'kz' => 'Kazakhstan', 'lv' => 'Latvia', 'lt' => 'Lithuania', 'lu' => 'Luxembourg', 'mx' => 'Mexico', 'ma' => 'Morocco', 'nl' => 'Netherlands', 'nz' => 'New Zealand', 'no' => 'Norway', 'om' => 'Oman', 'pk' => 'Pakistan', 'pa' => 'Panama', 'pg' => 'Papua New Guinea', 'py' => 'Paraguay', 'pe' => 'Peru', 'pl' => 'Poland', 'pt' => 'Portugal', 'pr' => 'Puerto Rico', 'qa' => 'Qatar', 'ro' => 'Romania', 'ru' => 'Russian Federation', 'sk' => 'Slovakia', 'za' => 'South Africa', 'es' => 'Spain', 'se' => 'Sweden', 'ch' => 'Switzerland', 'tw' => 'Taiwan', 'tr' => 'Turkey', 'ua' => 'Ukraine', 'gb' => 'United Kingdom', 'us' => 'United States', 'uy' => 'Uruguay', 've' => 'Venezuela', 'vn' => 'Vietnam', 'zm' => 'Zambia', 'zw' => 'Zimbabwe']; @endphp
                @foreach ($countries as $value => $text)
                    <option value="{{$value}}"{{$account_logged->flag == $value ? ' selected' : ''}}>{{$text}}</option>
                @endforeach
            </select>
            </td></tr><tr></table></div></table></div></td></tr><br/>
            <table width="100%"><tr align="center"><td><table border="0" cellspacing="0" cellpadding="0" ><tr><td style="border:0px;" >
            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif" ></div></div></td><tr></form></table></td><td>
            <table border="0" cellspacing="0" cellpadding="0">
            <form action="/account/accountmanagement" method="post">
            @csrf<tr><td style="border:0px;"><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)"><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></td></tr></table>
        @elseif($action == "saveinfo")
            <div class="TableContainer" ><table class="Table1" cellpadding="0" cellspacing="0" ><div class="CaptionContainer" ><div class="CaptionInnerContainer" >
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text">Public Information Changed</div>
            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            </div></div><tr><td><div class="InnerTableContainer"><table style="width:100%;"><tr><td>Your public information has been updated.</td></tr></table></div></table></div></td></tr><br>
            <center><table border="0" cellspacing="0" cellpadding="0">
            <form action="/account/accountmanagement" method="post">
            @csrf
            <tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
        @elseif($action == "editcharacter")
            @if($errors->any())
                <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}
                @endforeach
                </div><div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
                <center>
                <table border="0" cellspacing="0" cellpadding="0" >
                <form action="/account/accountmanagement" method="post" ><tr><td style="border:0px;" ><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
                @csrf
                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
            @else
                Here you can see and edit the information about your character.<br/>If you do not want to specify a certain field, just leave it blank.<br/><br/>
                <form action="/account/accountmanagement/savecharacter" method="post">
                @csrf
                @method('put')
                <div class="TableContainer"> <table class="Table5" cellpadding="0" cellspacing="0"><div class="CaptionContainer"><div class="CaptionInnerContainer">
                <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                <div class="Text">Edit Character Information</div>
                <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
                <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
                <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
                <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div></div><tr><td>
                <div class="InnerTableContainer"><table style="width:100%;"><tr><td><div class="TableShadowContainerRightTop">
                <div class="TableShadowRightTop" style="background-image:url({{$layout_path}}/images/content/table-shadow-rt.gif);"></div></div>
                <div class="TableContentAndRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-rm.gif);"><div class="TableContentContainer"><table class="TableContent" width="100%">
                <tr><td class="LabelV">Name:</td><td style="width:80%;">{{htmlspecialchars($player->name)}}</td></tr><tr><td class="LabelV">Hide Account:</td><td>
                <input type="checkbox" name="account_visible" value="1"{{$player->hide_char ? ' checked="checked"' : ''}}> check to hide your account information</td></tr></table></div></div><div class="TableShadowContainer">
                <div class="TableBottomShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bm.gif);">
                <div class="TableBottomLeftShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bl.gif);"></div>
                <div class="TableBottomRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-br.gif);"></div></div></div></td></tr>
                <tr><td><div class="TableShadowContainerRightTop"><div class="TableShadowRightTop" style="background-image:url({{$layout_path}}/images/content/table-shadow-rt.gif);"></div></div>
                <div class="TableContentAndRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-rm.gif);"><div class="TableContentContainer">
                <table class="TableContent" width="100%"><tr><td class="LabelV"><span>Comment:</span></td><td style="width:80%;">
                <textarea name="comment" rows="10" cols="50" wrap="virtual" maxlength="1600">{{$player->comment}}</textarea><br>[Max. {{config('custom.character_comment_lines_limit')}} lines, Max. {{config('custom.character_comment_chars_limit')}} chars]</td></tr></table></div></div><div class="TableShadowContainer">
                <div class="TableBottomShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bm.gif);" >
                <div class="TableBottomLeftShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-bl.gif);" ></div>
                <div class="TableBottomRightShadow" style="background-image:url({{$layout_path}}/images/content/table-shadow-br.gif);" ></div></div></div></td></tr></td></tr>
                </table></div></table></div></td></tr><br/><table style="width:100%"><tr align="center"><td>
                <table border="0" cellspacing="0" cellpadding="0"><tr><td style="border:0px;">
                <input type="hidden" name="name" value="{{htmlspecialchars($player->name)}}">
                <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)">
                <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);">
                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);"></div>
                <input class="ButtonText" type="image" name="Submit" alt="Submit" src="{{$layout_path}}/images/buttons/_sbutton_submit.gif"></div></div></td><tr></form></table></td>
                <td><table border="0" cellspacing="0" cellpadding="0">
                <form action="/account/accountmanagement" method="post">
                @csrf
                <tr><td style="border:0px;"><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)"><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);">
                <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);"></div>
                <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></td></tr></table>
            @endif
        @elseif($action == "savecharacter")
            <div class="TableContainer"><table class="Table1" cellpadding="0" cellspacing="0">
            <div class="CaptionContainer"><div class="CaptionInnerContainer">
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text">Character Information Changed</div>
            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span></div></div><tr><td>
            <div class="InnerTableContainer"><table style="width:100%;"><tr><td>The character information has been updated.</td></tr></table></div></table></div></td></tr>
            <br><center><table border="0" cellspacing="0" cellpadding="0">
            <form action="/account/accountmanagement" method="post">
            @csrf
            <tr><td style="border:0px;"><div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)"><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
            <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></td></tr></form></table></center>
        @endif
    @endif
</x-layout>