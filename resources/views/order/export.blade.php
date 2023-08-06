<!DOCTYPE html>
<html>
    <head>
        <title>Export Excel</title>
    </head>
    <body>
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="10" style="text-align:center;">
                    @if(isset($month))
                        <h3>Report Order Of {{date("F", mktime(0, 0, 0, $month, 10))}} {{$year}}</h3>
                    @else
                        <h3>Report Order Of {{$year}}</h3>
                    @endif
                    </th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th>Receipt Number</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Payment</th>
                    <th>Refund</th>
                    <th>Discount</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $key=>$order)
            <?php $numProducts = count($order['product']); ?>
            @foreach ($order['product'] as $productIndex => $product)
            <tr>
                @if($productIndex===0)
                    <td style="text-align:left" rowspan="{{ $numProducts }}">
                    {{$order['receipt_number']}}
                    </td>
                    <td rowspan="{{ $numProducts }}">
                    <center>{{$order['date']}}</center>
                    </td>
                    <td rowspan="{{ $numProducts }}">
                    <center>{{$order['time']}}</center>
                    </td>
                    <td rowspan="{{ $numProducts }}">
                    <center>{{$order['event_type']}}</center>
                    </td>
                    <td rowspan="{{ $numProducts }}">
                    <center>{{$order['payment_method']}}</center>
                    </td>
                    @if(is_null($order['refund']))
                    <td rowspan="{{ $numProducts }}">
                    <center>-</center>
                    </td>
                    @else
                    <td style="text-align:right" rowspan="{{ $numProducts }}">
                    Rp. {{ number_format($order['refund'],0,',','.')}},-
                    </td>
                    @endif
                    @if(is_null($order['discount']))
                    <td rowspan="{{ $numProducts }}">
                    <center>-</center>
                    </td>
                    @else
                    <td style="text-align:right" rowspan="{{ $numProducts }}">
                    Rp. {{ number_format($order['discount'],0,',','.')}},-
                    </td>
                    @endif
                @endif
                <td>
                <center>{{$product['product_name']}}</center>
                </td>
                <td>
                <center>{{$product['qty']}}</center>
                </td>
                @if($productIndex===0)
                    <td style="text-align:right" rowspan="{{ $numProducts }}">
                    Rp. {{ number_format($order['total_amount'],0,',','.')}},-
                    </td>
                @endif
            </tr>
            @endforeach
            @endforeach
            <tr style="background-color:green;">
                <td colspan="9" style="text-align:right;">
                   <center><h3>Total</h3></center>
                </td>
                <td style="text-align:right">
                <h3>Rp. {{ number_format($sum,0,',','.')}},-</h3>
                </td>
            </tr>
            </tbody>
        </table>
    </body>
</html>
