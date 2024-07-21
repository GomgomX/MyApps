<x-layout :$pageTitle :$subtopic>
    @if(config('custom.shop_system'))
        @php
            $items_received_text = '';
            if(count($items_history_received))
            {
                foreach($items_history_received as $item_received)
                {
                    if($loggedInAccount->id == $item_received->to_account)
                        $char_color = 'green';
                    else
                        $char_color = 'red';
                    $items_received_text .= '<tr bgcolor="'.config('custom.lightborder').'"><td><font color="'.$char_color.'">'.htmlspecialchars($item_received->to_name).'</font></td><td>';
                    if($loggedInAccount->id == $item_received->from_account)
                        $items_received_text .= '<i>Your account</i>';
                    else
                        $items_received_text .= htmlspecialchars($item_received->from_nick);
                    $items_received_text .= '</td><td>'.htmlspecialchars($item_received->offer_name).'</td><td>'.date("j F Y, H:i:s", $item_received->trans_start).'</td>';
                    if($item_received->trans_real > 0)
                        $items_received_text .= '<td>'.date("j F Y, H:i:s", $item_received->trans_real).'</td>';
                    else
                        $items_received_text .= '<td><b><font color="red">Not yet</font></b></td>';
                    $items_received_text .= '</tr>';
                }
            }
        @endphp
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
            <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}"></TD></TR>
            <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white><center><B>Transactions History</B></center></TD></TR>
            <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}"></TD></TR>
            </table><br>
            
        @if(!empty($items_received_text))
            <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
            <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=left CLASS=white colspan="5"><B>Item Transactions</B></TD></TR>
            <tr bgcolor="{{config('custom.darkborder')}}"><td><b>To:</b></td><td><b>From:</b></td><td><b>Offer name</b></td><td><b>Bought</b></td><td><b>Received</b></td></tr>
            {!!$items_received_text!!}
            </table>
        @endif

        @if(empty($items_received_text))
            You did not buy/receive any item.
        @endif
    @else
        <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
        <TR><TD BGCOLOR="{{config('custom.vdarkborder')}}" ALIGN=center CLASS=white ><B>Shop Information</B></TD></TR>
        <TR><TD BGCOLOR="{{config('custom.darkborder')}}"><center>Shop is currently closed. [to admin: edit it in 'config/config.php']</TD></TR>
        </table>
    @endif
</x-layout>