@extends("master")

@section("content")
    <?php
    $grand_u_d = 0;
    $grand_u_p = 0;
    $grand_t_d = 0;
    $grand_t_p = 0;
    $grand_qty = 0;
    $grand_t_prev = 0;
    $grand_precent = 0;
    ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">

                @if (session('msg'))
                    <div class="alert alert-success mt-4">
                        {{ session('msg') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-2 col-lg-2">
                        @if(isset($filter))
                            @if($filter->locked == 1)
                                <a class="btn btn-success mt-3 text-white">Budget Locked</a>
                                <!-- <h3><span class="badge badge-success mt-3">Budget Locked</span></h3> -->
                            @else
                                <a class="btn btn-danger mt-3" href="{{ url('lock_budget/'.$filter->id) }}"><i
                                            class="fa fa-lock" aria-hidden="true"></i> Lock Budget</a>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-8 col-lg-8">

                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                Select budget year
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>

                                    <form method="GET" action="{{ url('budget_by_year_category_summary') }}">
                                        <tr>
                                            @csrf
                                            <td>
                                                <select class="form-control" id="pre_year" multiple
                                                        name="pre_year[]" placeholder="Select a Year"
                                                        style="width: 100%;">
                                                    @if(!empty($filters->prev_year_id))
                                                        @foreach($years as $key => $year)
                                                            @if(count($filters->prev_year_id) > $key )
                                                                <option value="{{ $filters->prev_year_id[$key]->id }}" selected
                                                                        >{{ $filters->prev_year_id[$key]->year }}</option>
{{--                                                                @elseif(count($filters->prev_year_id) == 1 && $key < 2)--}}
{{--                                                                <option value="{{ $filters->prev_year_id[$key]->id }}" selected--}}
{{--                                                                >{{ $filters->prev_year_id[$key]->year }}</option>--}}
                                                            @else
                                                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                        @endforeach
                                                    @endif


                                                    {{--                                                    @foreach ($years as $year)--}}
                                                    {{--                                                        @if(!empty($filters->prev_year_id))--}}
                                                    {{--                                                            @forelse($filters->prev_year_id as $new_year)--}}
                                                    {{--                                                                <option value="{{ $new_year->id }}"--}}
                                                    {{--                                                                        selected>{{ $new_year->year }}</option>--}}
                                                    {{--                                                            @empty--}}
                                                    {{--                                                                'Budget Name'--}}
                                                    {{--                                                            @endforelse--}}

                                                    {{--                                                        @else--}}
                                                    {{--                                                            <option value="{{ $year->id }}">{{ $year->year }}</option>--}}
                                                    {{--                                                        @endif--}}
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                                <input type="hidden" name="prev_year_id" id="prev_year_id">
                                                {{--                                                <select class="custom-select" name="prev_year_id" required>--}}
                                                {{--                                                    --}}
                                                {{--                                                </select>--}}
                                                <span class="small text-danger">{{ $errors->first('prev_year_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="custom-select" name="year_id" required>
                                                    <option value=0>Select Year here</option>
                                                    @foreach ($years as $year)
                                                        @if($year->id == $filters->yearid)
                                                            <option value="{{ $year->id }}"
                                                                    selected>{{ $year->year }}</option>
                                                        @else
                                                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="submit" name="show" class="btn btn-primary">Show</button>
                                            </td>
                                        </tr>
                                    </form>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        @if(empty($capex_budget_year))
                        @else
                            <a class="btn btn-sm btn-danger mt-3 ml-3 float-right"
                               href="{{ url('export_budget_summary_subcat/'.json_encode($filters)) }}">CSV <i
                                        class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mt-3 float-right"
                               href="{{ url('itemexport_subcategory_summary/'.json_encode($filters)) }}">Print <i
                                        class="fa fa-download" aria-hidden="true"></i></a>
                        @endif
                        <span class="text-danger">{{ $errors->first('inv_id') }}</span>
                    </div>
                </div>
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

                                @foreach($capex_budget_year as $key => $budget)
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

                                @foreach ($opex_budget_year as $budget)

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
                                </tfoot>
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
                                <tfoot>
                                <tr>
                                    <th colspan='3' style="text-align:right;">Total</th>
                                    <td style="text-align:right;">{{number_format($unit_b_p_prev,2)}}</td>
                                    <td style="text-align:right;">{{ number_format($unit_b_p,2) }}</td>
                                    <td class='text-align-right'>{{number_format($opex_precent,2)}}</td>
                                    <td style="text-align:right;">{{ number_format($total_b_d,2) }}</td>
                                </tr>
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
                                            @if(!empty($filters->prev_year_name))
                                                @forelse($filters->prev_year_name as $year_name)
                                                    {{ $year_name->year }}
                                                @empty
                                                    'Budget Name'
                                                @endforelse
                                            @endif
                                        </b></td>
                                    <td class='text-align-right'>{{number_format($grand_t_prev,2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Actual used -
                                            @if(!empty($filters->prev_year_name))
                                                @forelse($filters->prev_year_name as $year_name)
                                                    {{ $year_name->year }}
                                                @empty
                                                    'Budget Name'
                                                @endforelse
                                            @endif
                                        </b></td>
                                    <td class='text-align-right'>
                                        @if(!empty($capex_budget_year) || !empty($opex_budget_year))
                                            {{number_format($actual_used,2) ?? ''}}
                                        @else
                                            0.00
                                        @endif()
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Savings</b></td>
                                    <td class='text-align-right'>
                                        @if((!empty($capex_budget_year) || !empty($opex_budget_year)) && $grand_t_prev !=0)
                                            {{number_format((($grand_t_prev-$actual_used)/$grand_t_prev)*100,2)}}%
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
                                    <td colspan="3"><b>Dollar Conversion Rate {{$filters->year_name ?? ''}}</b></td>
                                    <td class='text-align-right'>
                                        @if(!empty($dollar_rate->pkr_val))
                                            {{$dollar_rate->pkr_val}}
                                        @else
                                            0.00
                                        @endif()
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Increase in Budget Vs {{$filters->year_name ?? ''}}</b></td>
                                    <td class='text-align-right'>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2020</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

            const year_element = document.querySelector('#pre_year');
            var multipleCancelButton = new Choices(year_element, {
                removeItemButton: true,
                maxItemCount: 2,
                searchResultLimit: 15,
                renderChoiceLimit: 15
            });

            $('#pre_year').change(function (e) {
                e.preventDefault();
                var from = $("#pre_year").val();
                $('input[name="prev_year_id"]').val(from);
                console.log(from + " values of dropdown");
            });

        });
    </script>

@endsection
