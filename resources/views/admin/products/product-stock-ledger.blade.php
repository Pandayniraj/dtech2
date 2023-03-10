<table id="" class="table table-bordered std_table table-striped" style="width:100%;">
    <thead>

        @if (\Request::get('page') != '1')
            <tr colspan="7"><Strong>Previous Closing</strong></tr>
        @endif
        <tr style="background: #3c8dbc;color: #FFFFFF;">
            {{--        <th class="text-center" rowspan="2">Ord Num.</th> --}}
            <th class="text-center" style="width: 10%;" rowspan="2">Date</th>

            <th class="text-center" style="width: 10%;" rowspan="2">Party</th>
            <th class="text-center" style="width: 5%;" rowspan="2">Purchase store</th>
            <th class="text-center" style="width: 5%;" rowspan="2">sales store</th>
            <th class="text-center" style="width: 5%;" rowspan="2">Bill No.</th>

            {{--        <th class="text-center" rowspan="2">Ref No.#</th> --}}
            <th class="text-center" style="width: 10%;" rowspan="2">Tran Type</th>
            {{--        <th class="text-center" rowspan="2">Date</th> --}}
            {{--        <th class="text-center" rowspan="2">Location</th> --}}
            <th class="text-center" style="width: 10%;" colspan="3">Inwards</th>
            <th class="text-center" style="width: 15%;" colspan="3">Outwards</th>
            <th class="text-center" style="width: 15%" colspan="3">Sales</th>
            <th class="text-center" style="width: 15%;" colspan="3"><i class="fa fa- fa-hand-paper-o"></i> Closing
            </th>
        </tr>
        <tr style="background: #3c8dbc;color: #FFFFFF;">
            <td>Qty</td>
            <td>Rate</td>
            <td>Value</td>
            <td>Qty</td>
            <td>Rate</td>
            <td>Value</td>
            <td>Qty</td>
            <td>Rate</td>
            <td>Value</td>
            <td>Qty</td>
            <td>Rate</td>
            <td>Value</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $sum = 0;
        $sales = 0;
        $SalesAmount = 0;
        $StockIn = 0;
        $StockOut = 0;
        $totalAmount = 0;
        $StockInAmount = 0;
        $checkvalue = 0;
        $StockOutAmount = 0;
        if (isset($purchasePrice)) {
            $purchasePriceTemp1 = $purchasePrice;
            $purchasePriceTemp2 = $purchasePrice;
            $purchasePriceTemp3 = $purchasePrice;
        }
        ?>

        @if (count($transations) > 0)

            @foreach ($transations as $result)
                @if (isset($result))
                    <?php
                    
                    $reasons = \App\Models\AdjustmentReason::all();
                    if ($result->trans_type == PURCHINVOICE) {
                        $order = $result->get_purchase;
                        $type = 'Purchase';
                        $href = $order->id ? "/admin/purchase/{$order->id}?type={$order->purchase_type}" : null;
                    } elseif ($result->trans_type == SALESINVOICE) {
                        $order = $result->get_sales;
                        $type = 'Sales';
                        $href = $order->id ? "/admin/orders/{$order->id}" : null;
                    } elseif ($result->trans_type == OTHERSALESINVOICE) {
                        $order = $result->get_invoice;
                        $type = 'Tax Invoice';
                        $href = $order->id ? "/admin/invoice1/{$order->id}" : null;
                    } elseif ($result->trans_type == PURCHASEADDITIONALCOST) {
                        $order = $result->get_entries;
                        $type = 'Voucher Entry';
                        $href = $order->id ? '/admin/entries/show/' . $order->entrytype->label . '/' . $order->id : null;
                    } elseif ($result->trans_type == STOCKMOVEIN) {
                        $type = 'Transfer';
                        $href = null;
                    } elseif ($result->trans_type == STOCKMOVEOUT) {
                        $type = 'Transfer';
                        $href = null;
                    }
                    ?>

                    <tr>
                        <td align="center">
                            @if ($href)
                                <a href="{{ $href }}" target="_blank">
                                    {{ date('d M Y', strtotime($result->tran_date)) }}
                                </a>
                            @else
                                {{ date('d M Y', strtotime($result->tran_date)) }}
                            @endif
                        </td>

                        <td style="font-size: 16.5px" align="left">
                            {{ $result->trans_type == PURCHASEADDITIONALCOST ? $result->reference : $order->client->name ?? '' }}
                        </td>

                        <td>
                            @if ($result->qty > 0)
                                @php
                                    $purchasestore = \App\Models\PosOutlets::where('id', $result->store_id)->first()->name;
                                @endphp
                                {{ $purchasestore ? $purchasestore : '--' }}
                            @endif

                        </td>


                        <td>
                            @if ($result->qty < 0)
                                @php
                                    $salesstore = \App\Models\PosOutlets::where('id', $result->store_id)->first()->name;
                                @endphp
                                {{ $salesstore ? $salesstore : '--' }}
                            @endif

                        </td>

                        <td align="center">
                            @if ($href)
                                <a href="{{ $href }}" target="_blank">
                                    {{ $result->trans_type == PURCHASEADDITIONALCOST ? $order->number : $order->bill_no }}
                                </a>
                            @else
                                {{ $result->trans_type == PURCHASEADDITIONALCOST ? $order->number : $order->bill_no }}
                            @endif
                        </td>

                        <td align="center">
                            {{ $type }}
                            @foreach ($reasons as $reason)
                                @if ($reason->trans_type == $result->trans_type)
                                    {{ ucwords($reason->name) }}
                                @endif
                            @endforeach
                        </td>
                        {{-- forinward --}}
                        <td align="center">
                            @if ($result->qty > 0)
                                {{ number_format($result->qty, 2) }}
                                <?php
                                $StockIn += $result->qty;
                                ?>
                            @else
                                -
                            @endif
                        </td>
                        <td align="center">
                            @if ($result->qty > 0)
                                {{ number_format($result->price, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td align="center">
                            @if ($result->qty > 0)
                                {{ number_format($result->qty * $result->price, 2) }}
                                <?php
                                $StockInAmount += $result->qty * $result->price;
                                ?>
                            @elseif($result->qty == 0)
                                {{ number_format($result->price, 2) }}
                                <?php
                                $StockInAmount += $result->price;
                                ?>
                            @else
                                -
                            @endif
                        </td>
                        {{-- endofinward --}}

                        {{-- foroutward --}}
                        <td style="align:center;">

                            @if ($result->qty < 0)
                                @php

                                // dd($result,$purchasePriceTemp1);
                                    $remainingqty = 0;
                                    $more = 1;
                                    $loopCount = 0;
                                    while ($more != 0) {
                                        $loopCount++;
                                        if ($more == 1) {
                                            if (count($purchasePriceTemp1) == 0) {
                                                break;
                                            } else {
                                                
                                               if($remainingqty == 1){
                                                
                                                $data = \StockHelper::averageAmount($purchasePriceTemp1, $more);
                                            }
                                            else{
                                                $data = \StockHelper::averageAmount($purchasePriceTemp1, $result->qty);
                                            }
                                        }
                                        } else {
                                            if (count($purchasePriceTemp1) == 0) {
                                                break;
                                            } else {
                                                $data = \StockHelper::averageAmount($purchasePriceTemp1, $more);
                                            }
                                        }
                                        $purchasePriceTemp1 = $data['list'];
                                        $more = $data['more'];
                                        $remainingqty = $data['checkvalue'];
                                        // echo $totalvalue;
                                        if ($loopCount != 1) {
                                            echo '<br/>';
                                        }
                                        $StockOut += $data['qty'];
                                        echo $data['qty'], '.';
                                    }
                                @endphp
                            @else
                                --
                            @endif
                        </td>

                        <td align="center">
                            @if ($result->qty < 0)
                                @php
                                    $value = 0;
                                    $more = 1;
                                    $loopCount = 0;
                                    while ($more != 0) {
                                        $loopCount++;
                                        if ($more == 1) {
                                            if (count($purchasePriceTemp2) == 0) {
                                                break;
                                            } else {
                                                if($value==1){
                                                $data = \StockHelper::averageAmount($purchasePriceTemp2, $more);
                                                }
                                                else{
                                                    $data = \StockHelper::averageAmount($purchasePriceTemp2, $result->qty);
                                                }
                                            }
                                        } else {
                                            if (count($purchasePriceTemp2) == 0) {
                                                break;
                                            } else {
                                                $data = \StockHelper::averageAmount($purchasePriceTemp2, $more);
                                            }
                                        }
                                        $purchasePriceTemp2 = $data['list'];
                                        $more = $data['more'];
                                        $value = $data['checkvalue'];
                                        if ($loopCount != 1) {
                                            echo '<br/>';
                                        }
                                        echo $data['result'];
                                    }
                                @endphp
                                {{-- {{ number_format($result->price, 2) }} --}}
                            @else
                                --
                            @endif
                        </td>
                        <td align="center">
                            @if ($result->qty < 0)
                                @php
                                    $value = 0;
                                    $more = 1;
                                    $loopCount = 0;
                                    
                                    while ($more != 0) {
                                        $loopCount++;
                                        if ($more == 1) {
                                            if (count($purchasePriceTemp3) == 0) {
                                                break;
                                            } else {
                                                if($value==1){
                                                $data = \StockHelper::averageAmount($purchasePriceTemp3, $more);
                                                }else{
                                                    $data = \StockHelper::averageAmount($purchasePriceTemp3, $result->qty); 
                                                }
                                            }
                                        } else {
                                            if (count($purchasePriceTemp3) == 0) {
                                                break;
                                            } else {
                                                $data = \StockHelper::averageAmount($purchasePriceTemp3, $more);
                                            }
                                        }
                                        $purchasePriceTemp3 = $data['list'];
                                        $more = $data['more'];
                                        $value = $data['checkvalue'];
                                        if ($loopCount != 1) {
                                            echo '<br/>';
                                        }
                                        echo number_format($data['qty'] * $data['result'], 2);
                                        $StockOutAmount += $data['qty'] * $data['result'];
                                    }
                                    
                                @endphp
                            @else
                                --
                            @endif
                        </td>
                        {{-- endofoutward --}}

                        {{-- forsales --}}
                        <td align="center">
                            @if ($result->qty < 0)
                                {{ abs($result->qty) }}
                                <?php
                                $sales += $result->qty;
                                ?>
                            @else
                                --
                            @endif
                        </td>
                        <td align="center">

                            @if ($result->qty < 0)
                                {{ number_format($result->price, 2) }}
                            @else
                                --
                            @endif
                        </td>
                        <td align="center">
                            @if ($result->qty < 0)
                                {{ number_format(abs($result->qty) * $result->price, 2) }}
                                <?php
                                $SalesAmount += $result->qty * $result->price;
                                ?>
                            @else
                                --
                            @endif
                        </td>
                        {{-- endofsales --}}

                        {{-- forclosingstock --}}
                        @php
                            $closing_qty = $StockIn + $StockOut;
                            $remstock=$StockIn - $StockOut;

                            // dd($closing_qty,$StockIn,$StockOut);
                            if($result->qty>0)
                               {
                                $closingavgamt=$StockInAmount / $StockIn; 
                                }
                            else
                                {
                                $closingavgamt=$StockOutAmount/ $StockOut;
                                }
                               
                                // $closingbalance=$remstock*$closingavgamt;
                                $closingbalance=$StockInAmount-$StockOutAmount;
                        @endphp
                        <td align="center">{{ $StockIn - $StockOut }}</td>
                        @if(($StockIn - $StockOut==0))
                        <td align="center">0</td>
                        @else
                            {{-- <td align="center">{{ number_format($StockInAmount / $StockIn, 2) }}</td> --}}
                            <td align="center">{{ number_format($closingbalance / ($StockIn - $StockOut), 2) }}</td>
                        @endif
                        {{-- <td align="center">{{ number_format(($StockIn - $StockOut)*($StockInAmount/ $StockIn), 2) }}</td> --}}
                        <td align="center">{{ number_format(($closingbalance), 2) }}</td>
                        {{-- endofclosingstock --}}
                    </tr>
                @endif
            @endforeach

            <tr class="bg-gray" style="font-weight: bold;">
                <td colspan="6" align="right">Total</td>
                <td align="center">{{ number_format($StockIn, 2) }}</td>
                <td align="center">{{ number_format($StockInAmount / $StockIn, 2) }}</td>
                <td align="center">{{ number_format($StockInAmount, 2) }}</td>

                <td align="center">{{ number_format(abs($StockOut), 2) }}</td>
                @if ($StockOut == 0)
                    <td>0</td>
                @else
                    <td align="center">{{ number_format($StockOutAmount / $StockOut, 2) }}</td>
                @endif
                <td align="center">{{ number_format($StockOutAmount, 2) }}</td>

                <td align="center">{{ number_format(abs($StockOut), 2) }}</td>
                @if ($StockOut == 0)
                    <td>0</td>
                @else
                    <td align="center">{{ number_format(abs($SalesAmount) / $StockOut, 2) }}</td>
                @endif
                <td align="center">{{ number_format(abs($SalesAmount), 2) }}</td>
                <td align="center">{{ number_format($StockIn - $StockOut, 2) }}</td>
                @if($StockIn - $StockOut==0)
                <td align="center">0</td>
                @else
                <td align="center">
                    {{ number_format(($StockInAmount-$StockOutAmount) / ($StockIn-$StockOut), 2) }}
                </td>
                @endif
                <td align="center">{{ number_format(($StockInAmount - $StockOutAmount), 2) }}</td>
            </tr>
        @else
            <tr>
                <td colspan="9" class="text-center text-danger">No Transaction Yet</td>
            </tr>
        @endif

    </tbody>
</table>
