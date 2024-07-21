<x-layout :$pageTitle :$subtopic :$header>
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
    @php 
        $paypal_currency = config('custom.paypal_currency');
        $base_premium_points = config('custom.paypal_base_premium_points');
        $use_sandbox = config('custom.paypal_use_sandbox')
    @endphp
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

    You may enter any amount to pay. You will receive <b>{{$base_premium_points}} Premium Points per 1 {{$paypal_currency}} paid</b>.
    <br>After you complete the payment you will receive {{$base_premium_points}} Premium Points for every {{$paypal_currency == 'USD' ? 'dollar' : "euro"}} paid into your account automatically. If you pay 3 {{$paypal_currency}} for example, you will receive {{$base_premium_points*3}} Premium Points.
    <br><br>
    <b>Here are the steps you need to complete your payment:</b><br>
    1. You will need a valid credit or debit card, or a PayPal account with the required balance you wish to pay.<br>
    2. Click on the orange PayPal button that appears.<br>
    3. PayPal will ask you how much you want to pay.<br>
    4. Complete the transaction on PayPal and then you will be redirected back to our site.<br>
    5. After the transaction is complete, the Premium Points will be automatically added to your account.<br>
    6. When you purchase an item from the shop, you will recieve it within a few seconds of purchase. You do not have to wait for any staff to be online.<br><br/>

    <b>If you have any questions or problems with your payment, please <a target="blank" href="/community/supportlist">contact</a> us in-game or send us an email at {{$server_config['ownerEmail']}}.</b>
    @php $account_logged = auth()->guard('account')->user(); @endphp
    <table>
    <form action="{{$use_sandbox ? config('custom.paypal_sandbox_url') : config('custom.paypal_url')}}" method="post">
    <input type="hidden" name="cmd" value="_xclick"> {{-- "_donations" --}}
    <input type="hidden" name="business" value="{{$use_sandbox ? config('custom.paypal_sandbox_receiver_email') : config('custom.paypal_receiver_email')}}">
    <input type="hidden" name="item_name" value="{{$server_config['serverName']}} Contribution (Account: {{$account_logged->name}})">
    <input type="hidden" name="custom" value="{{$account_logged->id}}">
    <input type="hidden" name="currency_code" value="{{$paypal_currency}}">
    <input type="hidden" name="cancel_return" value="https://{{$server_config['ip']}}/">
    <br/><br/>
    <center><input type="image" src="{{asset('images')}}/paypal/donate_button.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online."></center></form></table>
</x-layout>