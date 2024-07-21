<x-layout :$pageTitle :$subtopic>
    @if(config('custom.shop_system'))
        @php 
            $loggedInAccount = auth()->guard('account')->user();
            $user_premium_points = $loggedInAccount->premium_points;
            $number_of_rows = 0;
        @endphp
        
        @if(!isset($action) || (isset($action) && (in_array($action, ['item', 'mage', 'paladin', 'knight', 'weapon', 'shield', 'container']))))
            @php
                if(empty($action))
                {
                    $action = 'all';
                }
            @endphp

            @if(count($offer_list['item']) > 0 || count($offer_list['mage']) > 0 || count($offer_list['paladin']) > 0 || count($offer_list['knight']) > 0 || count($offer_list['weapon']) > 0 || count($offer_list['shield']) > 0 || count($offer_list['container']) > 0)
                <TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=4><TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white colspan="2"><B>Choose a categorie: </B>
                <a href="/shop/shopoffer" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'all' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">ALL</a>
                @if(count($offer_list['item']) > 0)
                    <a href="/shop/shopoffer/item" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'item' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">ITEMS</a>
                @endif
                @if(count($offer_list['mage']) > 0) 
                    <a href="/shop/shopoffer/mage" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'mage' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">MAGE</a>
                @endif
                @if(count($offer_list['paladin']) > 0) 
                    <a href="/shop/shopoffer/paladin" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'paladin' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">PALADIN</a>
                @endif
                @if(count($offer_list['knight']) > 0) 
                    <a href="/shop/shopoffer/knight" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'knight' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">KNIGHT</a>
                @endif
                @if(count($offer_list['weapon']) > 0) 
                    <a href="/shop/shopoffer/weapon" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'weapon' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">WEAPONS</a>
                @endif
                @if(count($offer_list['shield']) > 0) 
                    <a href="/shop/shopoffer/shield" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'shield' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">SHIELDS</a>
                @endif
                @if(count($offer_list['container']) > 0) 
                    <a href="/shop/shopoffer/container" style="font-size:12;padding: 8px 5px 6px 5px; margin: 5px 1px 0px 1px; background-color: {{$action == 'container' ? '#505050; color: #FFFFFF' : '#303030; color: #aaaaaa'}};">CONTAINERS</a>
                @endif
                </TD></TR></TD></TR></table><table BORDER=0 CELLPaDDING="4" CELLSPaCING="1" style="width:100%;font-weight:bold;text-align:center;"><tr style="background:#505050;"><td colspan="3" style="height:px;"></td></tr></table>
            @endif

            @if((count($offer_list['item']) > 0) && ($action == 'item' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['item'] as $item)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$item['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$item['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($item['name'])!!}</b> ({{$item['points']}} points)<br />{!!$item['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="itemform_{{$item['id']}}">
                    @csrf
                    <input type="hidden" name="buy_id" value="{{$item['id']}}">
                    <div class="navibutton">
                    <a href="" onClick="itemform_{{$item['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>{!!$action == 'all' ? '<br>' : ''!!}
            @endif
            
            @if(count($offer_list['mage']) > 0 && ($action == 'mage' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['mage'] as $mage)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$mage['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$mage['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($mage['name'])!!}</b> ({{$mage['points']}} points)<br />{!!$mage['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="mageform_{{$mage['id']}}">
                     @csrf
                    <input type="hidden" name="buy_id" value="{{$mage['id']}}"><div class="navibutton">
                    <a href="" onClick="mageform_{{$mage['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>{!!$action == 'all' ? '<br>' : ''!!}
            @endif
            
            @if(count($offer_list['paladin']) > 0 && ($action == 'paladin' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['paladin'] as $paladin)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$paladin['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$paladin['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($paladin['name'])!!}</b> ({{$paladin['points']}} points)<br />{!!$paladin['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="palaform_{{$paladin['id']}}">
                     @csrf
                    <input type="hidden" name="buy_id" value="{{$paladin['id']}}"><div class="navibutton">
                    <a href="" onClick="palaform_{{$paladin['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>{!!$action == 'all' ? '<br>' : ''!!}
            @endif

            @if(count($offer_list['knight']) > 0 && ($action == 'knight' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['knight'] as $knight)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$knight['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$knight['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($knight['name'])!!}</b> ({{$knight['points']}} points)<br />{!!$knight['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="kniform_{{$knight['id']}}">
                     @csrf
                    <input type="hidden" name="buy_id" value="{{$knight['id']}}"><div class="navibutton"><a href="" onClick="kniform_{{$knight['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>{!!$action == 'all' ? '<br>' : ''!!}
            @endif

            @if(count($offer_list['weapon']) > 0 && ($action == 'weapon' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['weapon'] as $weapon)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$weapon['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$weapon['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($weapon['name'])!!}</b> ({{$weapon['points']}} points)<br />{!!$weapon['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="weatform_{{$weapon['id']}}">
                     @csrf
                    <input type="hidden" name="buy_id" value="{{$weapon['id']}}"><div class="navibutton"><a href="" onClick="weatform_{{$weapon['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>{!!$action == 'all' ? '<br>' : ''!!}
            @endif

            @if(count($offer_list['shield']) > 0 && ($action == 'shield' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['shield'] as $shield)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$shield['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$shield['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($shield['name'])!!}</b> ({{$shield['points']}} points)<br />{!!$shield['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="shiform_{{$shield['id']}}">
                     @csrf
                    <input type="hidden" name="buy_id" value="{{$shield['id']}}"><div class="navibutton"><a href="" onClick="shiform_{{$shield['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>{!!$action == 'all' ? '<br>' : ''!!}
            @endif

            @if(count($offer_list['container']) > 0 && ($action == 'container' || $action == 'all'))
                <table border="0" cellpadding="4" cellspacing="1" width="100%"><tr bgcolor="{{config('custom.vdarkborder')}}"><td width="8%" align="center" class="white"><b>Points</b></td><td width="9%" align="center" class="white"><b>Picture</b></td><td width="350" align="left" class="white"><b>Description</b></td><td width="250" align="center" class="white"><b>Select product</b></td></tr>
                @foreach($offer_list['container'] as $container)
                    @php if(!is_int($number_of_rows / 2)) { $bgcolor = config('custom.darkborder'); } else { $bgcolor = config('custom.lightborder'); } $number_of_rows++; @endphp
                    <tr bgcolor="{{$bgcolor}}"><td align="center"><b>{{$container['points']}}</b></td><td align="center"><img src="{{config('custom.item_images_url').$container['item_id'].config('custom.item_images_extension')}}"></td><td><b>{!!htmlspecialchars($container['name'])!!}</b> ({{$container['points']}} points)<br />{!!$container['description']!!}</td><td align="center">
                    <form action="/shop/shopoffer/buy" method="POST" name="contform_{{$container['id']}}">
                     @csrf
                    <input type="hidden" name="buy_id" value="{{$container['id']}}"><div class="navibutton"><a href="" onClick="contform_{{$container['id']}}.submit();return false;">BUY</a></div></form>
                    </td></tr>
                @endforeach
                </table>
            @endif

            @if(count($offer_list['item']) > 0 || count($offer_list['mage']) > 0 || count($offer_list['paladin']) > 0 || count($offer_list['knight']) > 0 || count($offer_list['weapon']) > 0 || count($offer_list['shield']) > 0 || count($offer_list['container']) > 0)
            <table BORDER=0 CELLPaDDING="4" CELLSPaCING="1" style="width:100%;font-weight:bold;text-align:center;">
                <tr style="background:#505050;">
                    <td colspan="3" style="height:px;"></td>
                </tr>
                </table>
            @endif
        @elseif($action == 'select_player')
            @if(!empty($errormessage))
                <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
                <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white><B>Information</B></TD></TR>
                <TR><TD BGCOLOR="{{config('custom.lightborder')}}" ALIGN=left><b><font color="red">{!!$errormessage!!}</font></b></TD></TR>
                </table>
            @else
                @if($errors->any())
                    <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
                    <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white><B>Information</B></TD></TR>
                    @foreach($errors->all() as $error)
                        <TR><TD BGCOLOR="{{config('custom.lightborder')}}" ALIGN=left><font color="red">{!!$error!!}</font></TD></TR>
                    @endforeach
                    </table><br>
                @endif
                <table border="0" cellpadding="4" cellspacing="1" width="100%">
                <tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="2" class="white"><b>Selected Offer</b></td></tr>
                <tr bgcolor="{{config('custom.lightborder')}}"><td width="100"><b>Name:</b></td><td width="550">{{$buy_offer['name']}}</td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td width="100"><b>Description:</b></td><td width="550">{!!$buy_offer['description']!!}</td></tr>
                </table><br/>
                <form action="/shop/shopoffer/select" method="POST">
                @csrf
                <input type="hidden" name="buy_id" value="{{$buy_offer['id']}}">
                <table border="0" cellpadding="4" cellspacing="1" width="100%">
                <tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="2" class="white"><b>Give item to player from your account</b></td></tr>
                <tr bgcolor="{{config('custom.lightborder')}}"><td width="110"><b>Name:</b></td><td width="550">
                <select name="buy_name">
                @php $players_from_logged_acc = $loggedInAccount->getPlayers; @endphp
                @if(count($players_from_logged_acc) > 0)
                    @foreach($players_from_logged_acc as $player)
                        <option>{{htmlspecialchars($player->name)}}</option>
                    @endforeach
                @else
                    You don't have any character on your account.
                @endif
                </select>&nbsp;<input type="submit" value="Give"></td></tr>
                </table>
                </form>
                <br />
                <form action="/shop/shopoffer/select" method="POST">
                @csrf
                <input type="hidden" name="buy_id" value="{{$buy_offer['id']}}">
                <table border="0" cellpadding="4" cellspacing="1" width="100%">
                <tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="2" class="white"><b>Give item to other player</b></td></tr>
                <tr bgcolor="{{config('custom.lightborder')}}"><td width="110"><b>To player:</b></td><td width="550">
                <input type="text" name="buy_name"> - name of player</td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td width="110"><b>From:</b></td><td width="550">
                <input type="text" name="buy_from">&nbsp;<input type="submit" value="Give"> - your nick, \'empty\' = Anonymous</td></tr>
                </table>
                </form>
            @endif
        @elseif($action == 'confirm_transaction')
                <table border="0" cellpadding="4" cellspacing="1" width="100%">
                <tr bgcolor="{{config('custom.vdarkborder')}}"><td colspan="3" class="white"><b>Confirm Transaction</b></td></tr>
                <tr bgcolor="{{config('custom.lightborder')}}"><td width="100"><b>Name:</b></td><td width="550" colspan="2">{{$buy_offer['name']}}</td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td width="100"><b>Description:</b></td><td width="550" colspan="2">{!!$buy_offer['description']!!}</td></tr>
                <tr bgcolor="{{config('custom.lightborder')}}"><td width="100"><b>Cost:</b></td><td width="550" colspan="2"><b>{{htmlspecialchars($buy_offer['points'])}} premium points</b> from your account</td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td width="100"><b>For Player:</b></td><td width="550" colspan="2"><font color="red">{{htmlspecialchars($data['buy_name'])}}</font></td></tr>
                <tr bgcolor="{{config('custom.lightborder')}}"><td width="100"><b>From:</b></td><td width="550" colspan="2"><font color="red">{{htmlspecialchars($data['buy_from'])}}</font></td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td colspan="3"></td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td width="100"><b>Transaction?</b></td><td width="275" align="left">
                <form action="/shop/shopoffer/additem" method="POST">
                @csrf
                <input type="submit" value="Accept"></form></td>
                <td align="right">
                <form action="/shop/shopoffer" method="POST">
                @csrf
                <input type="submit" value="Cancel"></form></td></tr>
                <tr bgcolor="{{config('custom.darkborder')}}"><td colspan="3"></td></tr>
                </table>
        @elseif($action == "item_added")
            <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
            <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white><B>Item added!</B></TD></TR>
            <TR><TD BGCOLOR="{{config('custom.lightborder')}}" ALIGN=left><b>{{$buy_offer['name']}}</b> added to player <b>{{htmlspecialchars($player_name)}}</b> for <b>{{$buy_offer['points']}} premium points</b> from your account.<br />Now you have <b>{{$user_premium_points}} premium points</b>.
            <br /><a href="/shop/shopoffer">GO TO SHOP OFFER</a></TD></TR>
            </table>
        @endif
        <br><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white><B>Premium Points</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.lightborder')}}" ALIGN=left><b>{!!($user_premium_points > 0 ? '<font color="green">You have '.$user_premium_points.' premium points</font>' : '<font color="red">You don\'t have premium points</font>')!!}</b></TD></TR>
        </table>
    @else
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=center CLASS=white ><B>Shop Information</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}"><center>Shop is currently closed. [to admin: edit it in 'config/config.php']</TD></TR>
        </table>
    @endif
</x-layout>
