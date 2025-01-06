<!DOCTYPE html>
<html>
<head>
    <title>{{ $category_name->category_name }} report</title>
    <style>
        .secondary-table{
            width:100%;
            border-spacing: 0px;
        }
        .secondary-table tr th, .secondary-table tr td{
            border: 1px solid;
            font-size: 14px;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>

<?php
$grand_u_d = 0;
$grand_u_p = 0;
$grand_t_d = 0;
$grand_t_p = 0;
$grand_qty = 0;
$grand_con_qty = 0;
$grand_con_pkr = 0;
$grand_dollar_amount = 0;
?>

{{--<table cellpadding="0" cellspacing="0" style="width:100%;">--}}

{{--    <tr class="text-center">--}}
{{--        <td class="text-center" style="width:85%; padding-left: 100px;">--}}
{{--            <h2>EFULife Assurance Ltd.</h2>--}}
{{--            <h2 style="font-weight:normal; line-height:1px;">{{ $category }} Report</h2>--}}
{{--            <p style="padding:0; margin:0;" class="font-14"><b>Proposed IT Budget - {{ $year }}</b></p>--}}
{{--        </td>--}}
{{--        <td style="width:15%;">--}}
{{--            <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>--}}
{{--            <p style="font-size: 12px;"><b>Printed:</b></p>--}}
{{--            <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--</table> <br>--}}
<div class="card mb-4 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            {{--                            id="capex_datatable"--}}
            <table class="table table-bordered"  width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="6" style="text-align: left; font-size: 20px; border-color: white;">EFU Life Assurance Ltd. </th>
                    <th colspan="6" style="text-align: right; font-size: 20px;border-color: white;">Technology Department</th>
                </tr>
                <tr>
                    {{--                                    <th colspan="1" style="border-color: white;"></th>--}}
                    <th colspan="12" style="text-align: center; font-size: 20px;border-color: white;">Proposed IT Budget : {{$year ?? ''}}</th>
                    {{--                                    <th colspan="2" style="border-color: white;"></th>--}}
                </tr>
                <tr>
                    {{--                                    <th colspan="1" style="border-color: white;"></th>--}}
                    {{--                                    End User Hardware - Head Office--}}
                    <th colspan="12" style="text-align: center; font-size: 20px;border-color: white;"> {{$category_name->category_name.' Report' ?? 'End User Hardware - Head Office'}}</th>
                    {{--                                    <th colspan="2" style="border-color: white;"></th>--}}
                </tr>
                <tr>
                    {{--                                    <th colspan="1" style="border-color: white;"></th>--}}
                    <th colspan="12" style="text-align: center; font-size: 25px; border-color: white;">CAPITAL EXPENDITURE </th>
                    {{--                                    <th colspan="2" style="border-color: white;"></th>--}}
                </tr>
                <tr style="background-color: yellow;">
                    <th>Item</th>
                    <th>Description</th>
                    <th>Unit Cost $</th>
                    <th>Unit Cost PKR</th>
                    <th>QTY</th>
                    <th>Consumed QTY</th>
                    <th>One Off PKR</th>
                    <th>One Off Consumed PKR</th>
                    <th>Dollar Amount</th>
                    <th>Remarks</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 1;
                $c_unit_b_d = 0;
                $c_unit_b_p = 0;
                $c_total_b_d = 0;
                $c_total_b_p = 0;
                $c_qty = 0;
                $c_t_consume = 0;
                $c_t_rem = 0;
                $c_con_qty = 0;
                $c_con_pkr = 0;
                $c_con_dollar_rate = 0;
                ?>

                @foreach($capex_budget_items as $key => $budget)
                    <tr>
                        <td>{{ empty($budget->subcategory)?'':$budget->subcategory->sub_cat_name }}</td>
                        <td>{{ $budget->mydescription }}</td>
                        <td class='text-align-right'>{{ ($budget->mytotal_price_dollar != 0 ? str_replace(",", "",number_format($budget->mytotal_price_dollar/$budget->myqty,2)) : 0) }}</td>
                        <td class='text-align-right'>{{ ($budget->mytotal_price_pkr != 0 ? str_replace(",", "",number_format($budget->mytotal_price_pkr/$budget->myqty,2)) : 0 ) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->myqty,2)) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->consumed_qty,2)) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_pkr,2)) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->consumed_pkr,2)) }}</td>
                        {{--                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_dollar,2)) }}</td>--}}
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->dollar_amount,2)) }}</td>
                        <td>{{ $budget->remarks }}</td>
                    </tr>
                    <?php
                    $c_unit_b_d += ($budget->mytotal_price_dollar != 0 ? ($budget->mytotal_price_dollar/$budget->myqty) : 0 );
                    $c_unit_b_p += ($budget->mytotal_price_pkr != 0 ? ($budget->mytotal_price_pkr/$budget->myqty) : 0);
                    $c_total_b_d += $budget->mytotal_price_dollar;
                    $c_total_b_p += $budget->mytotal_price_pkr;
                    $c_qty += $budget->myqty;
                    $c_con_qty += $budget->consumed_qty;
                    $c_con_pkr += $budget->consumed_pkr;
                    $c_con_dollar_rate += $budget->dollar_amount;
                    ?>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan='2' style="text-align:right;">Total</th>
                    <td>{{ number_format($c_unit_b_d,2) }}</td>
                    <td>{{ number_format($c_unit_b_p,2) }}</td>
                    <td class='text-align-right'>{{ number_format($c_qty,2) }}</td>
                    <td>{{ number_format($c_con_qty,2) }}</td>
                    {{--                                    <td>{{ number_format($c_total_b_d,2) }}</td>--}}
                    <td>{{ number_format($c_total_b_p,2) }}</td>
                    <td>{{ number_format($c_con_pkr,2) }}</td>
                    <td>{{ number_format($c_con_dollar_rate,2) }}</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
{{--                    id="dataTable_opex"--}}
<div class="card mb-2 mt-1">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered"  width="100%" cellspacing="0">
                <thead>
                {{--                                <tr>--}}
                {{--                                    <th colspan="5" style="text-align: left; font-size: 15px;">EFU Life Assurance Ltd. </th>--}}
                {{--                                    <th colspan="3" style="text-align: right; font-size: 15px;">Technology Department</th>--}}
                {{--                                </tr>--}}
                {{--                                <tr>--}}
                {{--                                    <th colspan="1"></th>--}}
                {{--                                    <th colspan="5" style="text-align: center; font-size: 15px;">Proposed IT Budget : {{$filters->year_name ?? ''}}</th>--}}
                {{--                                    <th colspan="2"></th>--}}
                {{--                                </tr>--}}
                {{--                                <tr>--}}
                {{--                                    <th colspan="1"></th>--}}
                {{--                                    <th colspan="5" style="text-align: center; font-size: 15px;">End User Hardware - Head Office </th>--}}
                {{--                                    <th colspan="2"></th>--}}
                {{--                                </tr>--}}
                <tr>
                    {{--                                    <th colspan="1"></th>--}}
                    <th colspan="8" style="text-align: center; font-size: 25px;">OPERATIONAL EXPENDITURE </th>
                    {{--                                    <th colspan="2"></th>--}}
                </tr>
                <tr style="background-color: yellow;">
                    <th>Item</th>
                    <th>Description</th>
                    <th>Unit Cost $</th>
                    <th>Unit Cost PKR</th>
                    <th>QTY</th>
                    <th>Consumed QTY</th>
                    <th>One Off PKR</th>
                    <th>One Off Consumed PKR</th>
                    <th>Dollar Amount</th>
                    <th>Remarks</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 1;
                $unit_b_d = 0;
                $unit_b_p = 0;
                $total_b_d = 0;
                $total_b_p = 0;
                $qty = 0;
                $t_consume = 0;
                $t_rem = 0;
                $o_con_qty = 0;
                $o_con_pkr = 0;
                $o_con_dollar_rate = 0;
                ?>

                @foreach ($opex_budget_items as $key => $budget)

                    <tr>
                        <td>{{ empty($budget->subcategory)?'':$budget->subcategory->sub_cat_name }}</td>
                        <td>{{ $budget->mydescription }}</td>
                        <td class='text-align-right'>{{ ($budget->mytotal_price_dollar != 0 ? str_replace(",", "",number_format($budget->mytotal_price_dollar/$budget->myqty,2)) : 0) }}</td>
                        <td class='text-align-right'>{{ ($budget->mytotal_price_pkr != 0 ? str_replace(",", "",number_format($budget->mytotal_price_pkr/$budget->myqty,2)) : 0 ) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->myqty,2)) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->consumed_qty,2)) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_pkr,2)) }}</td>
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->consumed_pkr,2)) }}</td>
                        {{--                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_dollar,2)) }}</td>--}}
                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->dollar_amount,2)) }}</td>
                        <td>{{ $budget->remarks }}</td>
                    </tr>
                    <?php
                    $unit_b_d +=($budget->mytotal_price_dollar != 0 ? ($budget->mytotal_price_dollar/$budget->myqty) : 0);
                    $unit_b_p += ($budget->mytotal_price_pkr != 0 ? ( $budget->mytotal_price_pkr/$budget->myqty ) : 0);
                    $total_b_d +=$budget->mytotal_price_pkr;
                    $total_b_p += $budget->mytotal_price_dollar;
                    $qty += $budget->myqty;
                    $o_con_qty += $budget->consumed_qty;
                    $o_con_pkr += $budget->consumed_pkr;
                    $o_con_dollar_rate += $budget->dollar_amount;
                    ?>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan='2' style="text-align:right;">Total</th>
                    <td>{{ number_format($unit_b_d,2) }}</td>
                    <td>{{ number_format($unit_b_p,2) }}</td>
                    <td class='text-align-right'>{{ number_format($qty,2) }}</td>
                    <td>{{ number_format($o_con_qty,2) }}</td>
                    {{--                                    <td>{{ number_format($c_total_b_d,2) }}</td>--}}
                    <td class='text-align-right'>{{ number_format($total_b_p,2) }}</td>
                    <td class='text-align-right'>{{ number_format($o_con_pkr,2) }}</td>
                    <td class='text-align-right'>{{ number_format($o_con_dollar_rate,2) }}</td>
                    <td></td>
                </tr>
                </tfoot>
                </tfoot>
                <?php
                $grand_u_d += $c_unit_b_d+$unit_b_d;
                $grand_u_p += $c_unit_b_p+$unit_b_p;
                $grand_t_d += $c_total_b_d+$total_b_d;
                $grand_t_p += $c_total_b_p+$total_b_p;
                $grand_qty += $c_qty+$qty;
                $grand_con_qty += $c_con_qty+$o_con_qty;
                $grand_con_pkr += $c_con_pkr+$o_con_pkr;
                $grand_dollar_amount += $c_con_dollar_rate+$o_con_dollar_rate;
                ?>
                <tfoot>
                <tr>
                    <th colspan='2' style="text-align:right;">Grand Total</th>
                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_u_d,2) }}</td>
                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_u_p,2) }}</td>
                    <td class='text-align-right' style="text-align: right;">{{ str_replace(",", "",number_format($grand_qty,2)) }}</td>
                    <td class='text-align-right' style="text-align: right;">{{ str_replace(",", "",number_format($grand_con_qty,2)) }}</td>
                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_t_p,2) }}</td>
                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_con_pkr,2) }}</td>
                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_dollar_amount,2) }}</td>
                    {{--                                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_t_p,2) }}</td>--}}
                    <td></td>
                </tr>
                </tfoot>
                {{--                <tfoot>--}}
                {{--                <tr>--}}
                {{--                    <th colspan='2' class="text-right">Grand Total</th>--}}

                {{--                    <td class="text-right">{{ number_format($grand_unit_dollar_c,2) }}</td>--}}
                {{--                    <td class="text-right">{{ number_format($grand_unit_dollar_c,2) }}</td>--}}
                {{--                    <td class="text-right">{{ number_format($grand_c_p,2) }}</td>--}}
                {{--                    <td class="text-right">{{ number_format($grand_c_qty,2) }}</td>--}}
                {{--                    <td class="text-right">{{ number_format($grand_r_p,2) }}</td>--}}
                {{--                    <td class="text-right">{{ number_format($grand_r_qty,2) }}</td>--}}
                {{--                </tr>--}}
                {{--                </tfoot>--}}

            </table>
        </div>
    </div>
</div>

</body>
</html>
