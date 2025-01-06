<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
$grand_u_pkr_to = 0;
$grand_u_pkr_from = 0;
$grand_qty_to = 0;
$grand_qty_from = 0;
$grand_u_dollar_to = 0;
$grand_u_dollar_from = 0;
?>
<div class="card mb-4 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            {{--                                                        id="capex_datatable"--}}
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="6" style="text-align: center; font-size: 20px;">EFU Life Assurance
                        Ltd.
                    </th>
                    <th colspan="6"> {{ $filters->category_name ?? 'Category Name' }} </th>
                </tr>
                <tr>
                    <th colspan="12" style="text-align: center; font-size: 20px;">Proposed IT Budget
                        : {{$filters->to_year_name ?? ''}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: center;">One-OFF EXPENDITURE (CAPEX)</th>
                    <th colspan="4" style="text-align: center;">
                        {{$filters->to_year_name ?? ''}}
                    </th>
                    <th colspan="4" style="text-align: center;">{{$filters->from_year_name ?? ''}}</th>
                </tr>
                <tr>
                    <th colspan="2">Item</th>
                    <th colspan="2">Unit price PKR</th>
                    <th colspan="2">Qty</th>
                    <th colspan="2">Unit Price Dollar</th>
                    <th colspan="2">Qty</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 1;
                $c_unit_unit_price_pkr_from = 0;
                $c_unit_price_pkr_to = 0;
                $c_qty_from = 0;
                $c_qty_to = 0;
                $c_unit_unit_price_dollar_from = 0;
                $c_unit_price_dollar_to = 0;
                ?>

                @foreach($data['capex_budget_year'] as $key => $budget)
                    <tr>
                        <td colspan="2">{{ empty($budget->subcategory) ? 'Subcategory Not Found': $budget->subcategory->sub_cat_name }}</td>
                        <td colspan="2" class='text-align-right'>{{ number_format($budget->to_year_u_price_dollar,2)}}$</td>
                        <td colspan="2" class='text-align-right'>{{number_format($budget->to_year_budget_qty,2) }}</td>
                        <td colspan="2" class='text-align-right'>{{number_format($budget->from_year_u_price_dollar ,2) ?? ''}}$
                        </td>
                        <td colspan="2" class='text-align-right'> {{ number_format($budget->from_year_budget_qty,2) }}</td>
                    </tr>
                    <?php
                    $c_unit_unit_price_pkr_from += $budget->from_year_u_price_pkr;
                    $c_unit_price_pkr_to += $budget->to_year_u_price_pkr;
                    $c_qty_from += $budget->from_year_budget_qty;
                    $c_qty_to += $budget->to_year_budget_qty;
                    $c_unit_unit_price_dollar_from += $budget->from_year_u_price_dollar;
                    $c_unit_price_dollar_to += $budget->to_year_u_price_dollar;
                    ?>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right;">Total</th>
                    <td colspan="2" style="text-align:right;">{{number_format($c_unit_price_dollar_to,2)}}</td>
                    <td colspan="2" style="text-align:right;">{{ number_format($c_qty_to,2) }}</td>
                    <td colspan="2" class='text-align-right'>{{number_format($c_unit_unit_price_dollar_from,2)}}</td>
                    <td colspan="2" style="text-align:right;">{{ number_format($c_qty_from,2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card mb-2 mt-1">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="4" style="text-align: center;">Recurring EXPENDITURE (OPEX)</th>
                    <th colspan="4" style="text-align: center;">
                        {{ $filters->to_year_name ?? ''}}
                    </th>
                    <th colspan="4" style="text-align: center;">{{ $filters->from_year_name ?? ''}}</th>
                </tr>
                <tr>
                    <th colspan="2">Item</th>
                    <th colspan="2">Unit price Dollar</th>
                    <th colspan="2">Qty</th>
                    <th colspan="2">Unit Price Dollar</th>
                    <th colspan="2">Qty</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 1;
                $o_unit_unit_price_pkr_from = 0;
                $o_unit_price_pkr_to = 0;
                $o_qty_from = 0;
                $o_qty_to = 0;
                $o_unit_unit_price_dollar_from = 0;
                $o_unit_price_dollar_to = 0 ;
                ?>

                @foreach ($data['opex_budget_year'] as $budget)

                    <tr>
                        <td colspan="2">{{ empty($budget->subcategory) ? 'Subcategory Not Found': $budget->subcategory->sub_cat_name }}</td>
                        <td colspan="2" class='text-align-right'>{{ number_format($budget->to_year_u_price_dollar,2)}}$</td>
                        <td colspan="2" class='text-align-right'>{{number_format($budget->to_year_budget_qty,2) }}</td>
                        <td colspan="2" class='text-align-right'>{{number_format($budget->from_year_u_price_dollar ,2) ?? ''}}$
                        </td>
                        <td colspan="2" class='text-align-right'> {{ number_format($budget->from_year_budget_qty,2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <?php
                $o_unit_unit_price_pkr_from += $budget->from_year_u_price_pkr;
                $o_unit_price_pkr_to += $budget->to_year_u_price_pkr;
                $o_qty_from += $budget->from_year_budget_qty;
                $o_qty_to += $budget->to_year_budget_qty;
                $o_unit_unit_price_dollar_from += $budget->from_year_u_price_dollar;
                $o_unit_price_dollar_to += $budget->to_year_u_price_dollar;
                ?>
                <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right;">Total</th>
                    <td colspan="2" style="text-align:right;">{{number_format($c_unit_price_dollar_to,2)}}</td>
                    <td colspan="2" style="text-align:right;">{{ number_format($o_qty_to,2) }}</td>
                    <td colspan="2" class='text-align-right'>{{number_format($c_unit_unit_price_dollar_from,2)}}</td>
                    <td colspan="2" style="text-align:right;">{{ number_format($o_qty_from,2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="card mb-2 mt-1">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered"  width="100%" cellspacing="0">
                <tbody>
                </tbody>
                <?php
                $grand_u_pkr_from += $o_unit_unit_price_pkr_from + $c_unit_unit_price_pkr_from;
                $grand_u_pkr_to += $o_unit_price_pkr_to + $c_unit_price_pkr_to;
                $grand_qty_to += $o_qty_to + $o_qty_to;
                $grand_qty_from += $o_qty_from + $c_qty_from;
                $grand_u_dollar_from += $o_unit_unit_price_dollar_from + $c_unit_unit_price_dollar_from;
                $grand_u_dollar_to += $o_unit_price_dollar_to + $c_unit_price_dollar_to;
                ?>
                <tfoot>
                <tr>
                    <th colspan='2' style="text-align:right;">Grand Total</th>
                    <td colspan="2" class='text-align-right'
                        style="text-align: right;">{{ number_format($grand_u_pkr_to,2) }}</td>
                    <td colspan="2" class='text-align-right'
                        style="text-align: right;">{{ number_format($grand_qty_to,2) }}</td>
                    <td colspan="2" class='text-align-right'
                        style="text-align: right;">{{number_format($grand_u_pkr_from,2)}}</td>
                    <td colspan="2" class='text-align-right'
                        style="text-align: right;">{{ number_format($grand_qty_from,2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>

