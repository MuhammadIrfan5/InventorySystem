<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <?php
    $grand_u_d = 0;
    $grand_u_p = 0;
    $grand_t_d = 0;
    $grand_t_p = 0;
    $grand_qty = 0;
    $grand_t_prev = 0;
    $grand_precent = 0;
    ?>

                <div class="card mb-4 mt-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            {{--                            id="capex_datatable"--}}
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th colspan="8" style="text-align: center; font-size: 20px;">EFU Life Assurance
                                        Ltd.
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="8" style="text-align: center; font-size: 20px;">Proposed IT Budget
                                        : {{$filters->year_name ?? ''}}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" style="text-align: center;">One-OFF EXPENDITURE (CAPEX)</th>
                                    <th style="text-align: center;">
                                        @if(!empty($filters->prev_year_name))
                                            @forelse($filters->prev_year_name as $year_name)
                                                {{ $year_name->year }}
                                            @empty
                                                'Budget Name'
                                            @endforelse
                                        @endif
                                    </th>
                                    <th colspan="4" style="text-align: center;">{{$filters->year_name ?? ''}}</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Item</th>
                                    <th>PKR</th>
                                    <th>Normal</th>
                                    <th></th>
                                    <th>Total Budget in Dollar</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $i = 1;
                                $c_unit_b_d = 0;
                                $c_unit_b_p_prev = 0;
                                $c_unit_b_p = 0;
                                $c_total_b_d = 0;
                                $c_total_b_p = 0;
                                $c_qty = 0;
                                $c_t_consume = 0;
                                $c_t_rem = 0;
                                $perecnt_capex = 0
                                ?>

                                @foreach($data['capex_budget_year'] as $key => $budget)
                                    <tr>
                                        <td colspan="3">{{ empty($budget->category_id) ? '': $budget->category->category_name }}</td>
                                        <td class='text-align-right'>{{ number_format($budget->prev_year_budget_amount,2)}}</td>
                                        <td class='text-align-right'>{{number_format($budget->mytotal_price_pkr,2) }}</td>
                                        <td class='text-align-right'>{{number_format($budget->percentage ,2) ?? ''}}%
                                        </td>
                                        <td class='text-align-right'>
                                            Rs {{ number_format($budget->mytotal_price_dollar,2) }}</td>
                                    </tr>
                                    <?php
                                    //                                    $c_unit_b_d += $budget->mytotal_price_dollar/$budget->myqty;
                                    $c_unit_b_p += $budget->myunit_price_pkr;
                                    $c_total_b_d += $budget->mytotal_price_dollar;
                                    $c_unit_b_p_prev += $budget->prev_year_budget_amount;
                                    $perecnt_capex += $budget->percentage;
                                    //                                    $c_total_b_p += $budget->mytotal_price_pkr;
                                    //                                    $c_qty += $budget->myqty;
                                    ?>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan='3' style="text-align:right;">Total</th>
                                    <td style="text-align:right;">{{number_format($c_unit_b_p_prev,2)}}</td>
                                    <td style="text-align:right;">{{ number_format($c_unit_b_p,2) }}</td>
                                    <td class='text-align-right'>{{number_format($perecnt_capex,2)}}%</td>
                                    <td style="text-align:right;">{{ number_format($c_total_b_d,2) }}</td>
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
                                    <th colspan="3" style="text-align: center;">Recurring EXPENDITURE (OPEX)</th>
                                    <th style="text-align: center;">
                                        @if(!empty($filters->prev_year_name))
                                            @forelse($filters->prev_year_name as $year_name)
                                                {{ $year_name->year }}
                                            @empty
                                                'Budget Name'
                                            @endforelse
                                        @endif
                                    </th>
                                    <th colspan="4" style="text-align: center;">{{$filters->year_name ?? ''}}</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Item</th>
                                    <th>PKR</th>
                                    <th>Normal</th>
                                    <th></th>
                                    <th>Total Budget in Dollar</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $i = 1;
                                $unit_b_d = 0;
                                $unit_b_p = 0;
                                $unit_b_p_prev = 0;
                                $total_b_d = 0;
                                $total_b_p = 0;
                                $qty = 0;
                                $t_consume = 0;
                                $t_rem = 0;
                                $opex_precent = 0;
                                ?>

                                @foreach ($data['opex_budget_year'] as $budget)

                                    <tr>
                                        <td colspan="3">{{ empty($budget->category_id) ? '': $budget->category->category_name }}</td>
                                        <td class='text-align-right'>{{ number_format($budget->prev_year_budget_amount,2)}}</td>
                                        <td class='text-align-right'>{{ number_format($budget->mytotal_price_pkr,2) }}</td>
                                        <td class='text-align-right'>{{number_format($budget->percentage ,2) ?? ''}}%
                                        </td>
                                        <td class='text-align-right'>
                                            Rs {{ number_format($budget->mytotal_price_dollar,2) }}</td>
                                    </tr>
                                    <?php
                                    $unit_b_d += $budget->mytotal_price_dollar / $budget->myqty;
                                    $unit_b_p += $budget->mytotal_price_pkr / $budget->myqty;
                                    $unit_b_p_prev += $budget->prev_year_budget_amount;
                                    $total_b_d += $budget->mytotal_price_pkr;
                                    $total_b_p += $budget->mytotal_price_dollar;
                                    $qty += $budget->myqty;
                                    $opex_precent += $budget->percentage;
                                    ?>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <?php
                                $grand_u_d += $c_unit_b_d + $unit_b_d;
                                $grand_u_p += $c_unit_b_p + $unit_b_p;
                                $grand_t_d += $c_total_b_d + $total_b_d;
                                $grand_t_p += $c_total_b_p + $total_b_p;
                                $grand_t_prev += $c_unit_b_p_prev + $unit_b_p_prev;
                                $grand_qty += $c_qty + $qty;
                                $grand_precent += $perecnt_capex + $opex_precent;
                                ?>
                                <tr>
                                    <th colspan='3' style="text-align:right;">Total</th>
                                    <td style="text-align:right;">{{number_format($unit_b_p_prev,2)}}</td>
                                    <td style="text-align:right;">{{ number_format($unit_b_p,2) }}</td>
                                    <td class='text-align-right'>{{number_format($opex_precent,2)}}</td>
                                    <td style="text-align:right;">{{ number_format($total_b_d,2) }}</td>
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
                    $grand_u_d += $c_unit_b_d + $unit_b_d;
                    $grand_u_p += $c_unit_b_p + $unit_b_p;
                    $grand_t_d += $c_total_b_d + $total_b_d;
                    $grand_t_p += $c_total_b_p + $total_b_p;
                    $grand_t_prev += $c_unit_b_p_prev + $unit_b_p_prev;
                    $grand_qty += $c_qty + $qty;
                    $grand_precent += $perecnt_capex + $opex_precent;
                    ?>
                    <tfoot>
                    <tr>
                        <th colspan='3' style="text-align:right;">Grand Total</th>
                        <td class='text-align-right'
                            style="text-align: right;">{{ number_format($grand_t_prev,2) }}</td>
                        <td class='text-align-right'
                            style="text-align: right;">{{ number_format($grand_u_p,2) }}</td>
                        <td class='text-align-right'
                            style="text-align: right;">{{number_format($grand_precent,2)}}</td>
                        <td class='text-align-right'
                            style="text-align: right;">{{ number_format($grand_t_d,2) }}</td>
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
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>
                                            @if(!empty($data['prev_year_name']))
                                                @forelse($data['prev_year_name'] as $year_name)
                                                    {{ $year_name['name'] }}
                                                @empty
                                                    'Budget Name'
                                                @endforelse
                                            @endif
                                        </b></td>
                                    <td class='text-align-right'>{{number_format($grand_t_prev,2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Actual used -
                                            @if(!empty($data['prev_year_name']))
                                                @forelse($data['prev_year_name'] as $year_name)
                                                    {{ $year_name['name'] }}
                                                @empty
                                                    'Budget Name'
                                                @endforelse
                                            @endif
                                        </b></td>
                                    <td class='text-align-right'>
                                        @if(!empty($data['capex_budget_year']) || !empty($data['opex_budget_year']))
                                            {{number_format($data['actual_used'],2) ?? ''}}
                                        @else
                                            0.00
                                        @endif()
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Savings</b></td>
                                    <td class='text-align-right'>
                                        @if((!empty($data['capex_budget_year']) || !empty($data['opex_budget_year'])) && $grand_t_prev !=0)
                                            {{number_format((($grand_t_prev-$data['actual_used'])/$grand_t_prev)*100,2)}}%
                                        @else
                                            0.00
                                        @endif()
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Total Utilized in percent </b>
                                    </td>
                                    <td class='text-align-right'>{{number_format($c_unit_b_p_prev,2)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-2 mt-1">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>Dollar Conversion Rate {{$data['year_name'] ?? ''}}</b></td>
                                    <td class='text-align-right'>
                                        @if(!empty($data['dollar_rate']))
                                            {{$data['dollar_rate']->pkr_val}}
                                        @else
                                            0.00
                                        @endif()
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Increase in Budget Vs {{$data['year_name'] ?? ''}}</b></td>
                                    <td class='text-align-right'>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
</body>
</html>
