<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    @if(!empty($errormessage))
        <div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url({{$layout_path}}/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>
        <li>{!!$errormessage!!}
        </div><div class="BoxFrameHorizontal" style="background-image:url({{$layout_path}}/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></div></div></div><br/>
        <center><td><table border="0" cellspacing="0" cellpadding="0">
        <form action="/shop/buypoints/stripe" method="post"><tr><td style="border:0px;">
        @csrf
        <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
        <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
        <input class="BigButtonText" type="submit" value="Back"></div></div></td></tr></form>
        </table></td></center>
    @else
        @if(!isset($action))
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.message').hide();
                    $('.hideshow').click(function() {
                        if ($('.message').is(':visible')) {
                            $('.hideshow').text('Show');
                            $('.message').slideUp('slow');
                        } else {
                            $('.hideshow').text('Hide');
                            $('.message').slideDown('slow');
                        }
                    });
                });
            </script>
            <script src="https://js.stripe.com/v3/"></script>
            <br><center><FONT SIZE=2 COLOR=#8A0808><B>&raquo; <a href="#" class="hideshow">Show</a> Donation Rules that you automatically agree to have read and accepted upon donating &laquo;</B></FONT></center>
            <div class="message"><br>
            <b>You automatically agree to the following when a donation is made:</b></br>
            * If you are under 18 years old, you will have to have the expressed permission of your parents.</br>
            * You are not allowed to pay with money that does not belong to you.</br>
            * You automatically agree to our Donation Rules and that we reserve the right to change or modify them at any time.</br>
            * You are not <b>paying</b> for a service. You are making a donation and are likely to receive Premium Points to get in-game items for helping contribute to our server.</br>
            * We reserve the right to modify any of the in-game items at any time.</br>
            * We reserve the right to change the prices at any time.</br>
            * We reserve the right to reset the server whenever we want.</br>
            <u>You expressly understand and agree that we shall not be liable for any direct, indirect, special, incidental or exemplary damages, including but not limited to, damages for loss of profits, goodwill, use, data or other intangible loss</u>.
            </div><br>
            <b>Here are the steps you need to complete your payment:</b><br>
            1. You will need a valid credit or debit card with the required balance.<br>
            2. Select how many Premium Points you want to buy and click Buy Points button.<br>
            3. Complete the transaction on Stripe and then you will be redirected back to our site.<br>
            4. After the transaction is complete, the Premium Points will be automatically added to your account.<br>
            5. When you purchase an item from the shop, you will recieve it within a few seconds of purchase. You do not have to wait for any staff to be online.<br><br/>

            <b>If you have any questions or problems with your payment, please <a target="blank" href="/community/supportlist">contact</a> us in-game or send us an email at {{$server_config['ownerEmail']}}.</b>
            <br><br><form style="display:inline-block; margin:0; padding:0;" id="payment-form" action="{{route('stripe-checkout-session')}}" method="POST">
                @csrf
                <label for="points"><b>Select Points:</b></label>
                <select name="price_id" id="price_id">
                    <option value="price_1PcrmCIepVBCEmRUDVqLAuZ1">625 Premium Points - $5</option>
                    <option value="price_1PcrpaIepVBCEmRUxXQURg5x">1,250 Premium Points - $10</option>
                    <option value="price_1Pcrq4IepVBCEmRUocLQSRtp">1,875 Premium Points - $15</option>
                    <option value="price_1PcrqbIepVBCEmRUY8Bzyhbl">6,250 Premium Points - $50</option>
                    <option value="price_1PcrrJIepVBCEmRUK7fQfkD8">12,500 Premium Points - $100</option>
                </select> &nbsp;&nbsp;&nbsp;&nbsp;<div class="BigButton" style="display:inline-block; margin-bottom:-7; background-image:url({{$layout_path}}/images/buttons/sbutton.gif)"><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
                <input class="BigButtonText" type="submit" value="Buy Points"></div></div>
            </form>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    const stripe = Stripe('{{config('app.stripe.key')}}');
                    const paymentForm = document.getElementById('payment-form');

                    paymentForm.addEventListener('submit', async (event) => {
                        event.preventDefault();
                        
                        const priceId = document.getElementById('price_id').value;
            
                        try {
                            const response = await fetch("{{route('stripe-checkout-session')}}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                                },
                                body: JSON.stringify({price_id: priceId})
                            });
            
                            const result = await response.json();
            
                            if(result.sessionId) {
                                const {error} = await stripe.redirectToCheckout({sessionId: result.sessionId});
            
                                if(error) {
                                    console.error('Error redirecting to checkout:', error);
                                    alert(error.message);
                                }
                            } else {
                                console.error('No session ID returned:', result);
                                alert('No session ID returned.');
                            }
                        } catch(error) {
                            console.error('Error creating checkout session:', error);
                            alert('Error creating checkout session.');
                        }
                    });
                });
            </script>
        @elseif($action == "success")
            <div class="TableContainer"><table class="Table1" cellpadding="0" cellspacing="0"><div class="CaptionContainer" ><div class="CaptionInnerContainer">        
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text">Successful Payment</div>        
            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
            </div></div><tr><td><div class="InnerTableContainer" >          
            <table style="width:100%;"><tr><td>You have successfully purchased {{number_format($points)}} Premium Points for ${{$amount}} via Stripe. Go to the Shop Offer and spend them.</td></tr></table></div></table></div></td></tr><br>
            <table style="width:100%;" ><tr align="center">
            <td><table border="0" cellspacing="0" cellpadding="0" >
            <form action="/shop/shopoffer" method="post"><tr><td style="border:0px;">
            @csrf
            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="BigButtonText" type="submit" value="Shop Offer"></div></div></td></tr></form>
            </table></td>
            <td><table border="0" cellspacing="0" cellpadding="0" >
            <form action="/shop/buypoints" method="post"><tr><td style="border:0px;">
            @csrf
            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="BigButtonText" type="submit" value="Buy Points"></div></div></td></tr></form>
            </table></td>
            </tr></table>
        @elseif($action == "cancel")
            <div class="TableContainer"><table class="Table1" cellpadding="0" cellspacing="0"><div class="CaptionContainer"><div class="CaptionInnerContainer">        
            <span class="CaptionEdgeLeftTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
            <span class="CaptionEdgeRightTop" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
            <span class="CaptionBorderTop" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
            <span class="CaptionVerticalLeft" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>
            <div class="Text">Payment Canceled</div>        
            <span class="CaptionVerticalRight" style="background-image:url({{$layout_path}}/images/content/box-frame-vertical.gif);" /></span>        
            <span class="CaptionBorderBottom" style="background-image:url({{$layout_path}}/images/content/table-headline-border.gif);" ></span>        
            <span class="CaptionEdgeLeftBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>        
            <span class="CaptionEdgeRightBottom" style="background-image:url({{$layout_path}}/images/content/box-frame-edge.gif);" /></span>      
            </div></div><tr><td><div class="InnerTableContainer" >          
            <table style="width:100%;" ><tr><td>Your payment has been canceled.</td></tr></table></div></table></div></td></tr><br>
            <center><td><table border="0" cellspacing="0" cellpadding="0">
            <form action="/shop/buypoints/stripe" method="post"><tr><td style="border:0px;">
            @csrf
            <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" >
            <div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
            <input class="BigButtonText" type="submit" value="Back"></div></div></td></tr></form>
            </table></td></center>
        @endif
    @endif
</x-layout>