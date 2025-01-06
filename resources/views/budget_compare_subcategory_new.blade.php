@extends("master")

@section("content")
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
            <table class="table table-bordered capex_budget_year" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="6" style="text-align: center; font-size: 20px;">EFU Life
                        Assurance
                        Ltd.
                    </th>
                    <th colspan="6"><h4>{{ $filters->category_name ?? 'Category Name' }}</h4>
                    </th>
                </tr>
                <tr>
                    <th colspan="12" style="text-align: center; font-size: 20px;">Proposed IT
                        Budget
                        : {{$filters->to_year_name ?? ''}}</th>
                </tr>
                <tr>
                    <th colspan="6" style="text-align: center;">One-OFF EXPENDITURE (CAPEX)</th>
                    <th colspan="6" style="text-align: center;">
                        {{$filters->to_year_name ?? ''}}
                    </th>
                </tr>
                <tr>
                    <th colspan="4">Item</th>
                    <th colspan="4">Unit price Dollar</th>
                    <th colspan="4">Qty</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 1;
                $c_qty_from = 0;
                $c_unit_unit_price_dollar_from = 0;
                ?>

                @foreach($capex_budget_year as $key => $budget)
                    <tr>
                        <td colspan="4">{{ empty($budget->subcategory) ? "No subcategory Found" : $budget->subcategory->sub_cat_name }}</td>
                        <td colspan="4"
                            class='text-align-right'>{{ number_format($budget->my_unit_price_dollar/$budget->total_rows,2)}}
                            $
                        </td>
                        <td colspan="4"
                            class='text-align-right'>{{number_format($budget->my_qty,2) }}</td>
                    </tr>
                    <?php
                    $c_qty_from += $budget->my_qty;
                    $c_unit_unit_price_dollar_from += $budget->my_unit_price_dollar / $budget->total_rows;
                    ?>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th style="text-align:right;">Total</th>
                    <td colspan="6"
                        style="text-align:right;">{{number_format($c_unit_unit_price_dollar_from,2)}}
                        $
                    </td>
                    <td colspan="4"
                        style="text-align:right;">{{ number_format($c_qty_from,2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card mb-4 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            {{--                                                        id="capex_datatable"--}}
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="6" style="text-align: center; font-size: 20px;">EFU Life
                        Assurance
                        Ltd.
                    </th>
                    <th colspan="6"><h4>{{ $filters->category_name ?? 'Category Name' }}</h4>
                    </th>
                </tr>
                <tr>
                    <th colspan="12" style="text-align: center; font-size: 20px;">Proposed IT
                        Budget
                        : {{$filters->from_year_name ?? ''}}</th>
                </tr>
                <tr>
                    <th colspan="6" style="text-align: center;">One-OFF EXPENDITURE (CAPEX)</th>
                    <th colspan="6" style="text-align: center;">
                        {{$filters->from_year_name ?? ''}}
                    </th>
                </tr>
                <tr>
                    <th colspan="4">Item</th>
                    <th colspan="4">Unit price Dollar</th>
                    <th colspan="4">Qty</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 1;
                $c_qty_to = 0;
                $c_unit_unit_price_dollar_to = 0;
                ?>

                @foreach($capex_budget_from as $key => $budget)
                    <tr>
                        <td colspan="4">{{ empty($budget->subcategory) ? "No subcategory Found" : $budget->subcategory->sub_cat_name }}</td>
                        <td colspan="4"
                            class='text-align-right'>{{ number_format($budget->year1_unit_price_dollar/$budget->total_rows,2)}}
                            $
                        </td>
                        <td colspan="4"
                            class='text-align-right'>{{number_format($budget->year1_qty,2) }}</td>
                    </tr>
                    <?php
                    $c_qty_to += $budget->year1_qty;
                    $c_unit_unit_price_dollar_to += $budget->year1_unit_price_dollar / $budget->total_rows;
                    ?>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th style="text-align:right;">Total</th>
                    <td colspan="6"
                        style="text-align:right;">{{number_format($c_unit_unit_price_dollar_to,2)}}
                        $
                    </td>
                    <td colspan="4"
                        style="text-align:right;">{{ number_format($c_qty_to,2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
