@extends("master")

@section("content")
    <?php
    $grand_u_d = 0;
    $grand_u_p = 0;
    $grand_t_d = 0;
    $grand_t_p = 0;
    $grand_qty = 0;
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
                                <a class="btn btn-danger mt-3" href="{{ url('lock_budget/'.$filter->id) }}"><i class="fa fa-lock" aria-hidden="true"></i> Lock Budget</a>
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
                                    <tr>
                                        <form method="GET" action="{{ url('budget_by_year_category') }}">
                                            @csrf
                                            <td>
                                                <select class="custom-select" name="category_id" required>
                                                    <option value=0>Select Category here</option>
                                                    @foreach ($categories as $category)
                                                        @if($category->id == $filters->catid)
                                                            <option value="{{ $category->id }}" selected>{{ $category->category_name }}</option>
                                                        @else
                                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </td>
                                            <td>
                                                <select class="custom-select" name="year_id" required>
                                                    <option value=0>Select Year here</option>
                                                    @foreach ($years as $year)
                                                        @if($year->id == $filters->yearid)
                                                            <option value="{{ $year->id }}" selected>{{ $year->year }}</option>
                                                        @else
                                                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </td>
                                            <td><button type="submit" name="show" class="btn btn-primary">Show</button></td>
                                        </form>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        @if(empty($capex_budget))
                        @else
                            <a class="btn btn-sm btn-danger mt-3 ml-3 float-right" href="{{ url('export_budget_subcat/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mt-3 float-right" href="{{ url('itemexport_subcategory/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                        @endif
                        <span class="text-danger">{{ $errors->first('inv_id') }}</span>
                    </div>
                </div>
                <div class="card mb-4 mt-3">
                    <div class="card-body">
                        <div class="table-responsive">
{{--                            id="capex_datatable"--}}
                            <table class="table table-bordered"  width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th colspan="5" style="text-align: left; font-size: 20px; border-color: white;">EFU Life Assurance Ltd. </th>
                                    <th colspan="3" style="text-align: right; font-size: 20px;border-color: white;">Technology Department</th>
                                </tr>
                                <tr>
{{--                                    <th colspan="1" style="border-color: white;"></th>--}}
                                    <th colspan="8" style="text-align: center; font-size: 20px;border-color: white;">Proposed IT Budget : {{$filters->year_name ?? ''}}</th>
{{--                                    <th colspan="2" style="border-color: white;"></th>--}}
                                </tr>
                                <tr>
{{--                                    <th colspan="1" style="border-color: white;"></th>--}}
{{--                                    End User Hardware - Head Office--}}
                                    <th colspan="8" style="text-align: center; font-size: 20px;border-color: white;"> {{$category_name->category_name ?? 'End User Hardware - Head Office'}}</th>
{{--                                    <th colspan="2" style="border-color: white;"></th>--}}
                                </tr>
                                <tr>
{{--                                    <th colspan="1" style="border-color: white;"></th>--}}
                                    <th colspan="8" style="text-align: center; font-size: 25px; border-color: white;">CAPITAL EXPENDITURE </th>
{{--                                    <th colspan="2" style="border-color: white;"></th>--}}
                                </tr>
                                <tr style="background-color: yellow;">
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Unit Cost $</th>
                                    <th>Unit Cost PKR</th>
                                    <th>QTY</th>
                                    <th>One Off PKR</th>
                                    <th>One Off Dollar</th>
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
                                ?>

                                @foreach($capex_budget as $key => $budget)
                                    <tr>
                                        <td>{{ empty($budget->subcategory)?'':$budget->subcategory->sub_cat_name }}</td>
                                        <td>{{ $budget->mydescription }}</td>
                                        <td class='text-align-right'>{{ ($budget->mytotal_price_dollar != 0 ? str_replace(",", "",number_format($budget->mytotal_price_dollar/$budget->myqty,2)) : 0) }}</td>
                                        <td class='text-align-right'>{{ ($budget->mytotal_price_pkr != 0 ? str_replace(",", "",number_format($budget->mytotal_price_pkr/$budget->myqty,2)) : 0 ) }}</td>
                                        <td class='text-align-right'>{{str_replace(",", "",number_format($budget->myqty,2)) }}</td>
                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_pkr,2)) }}</td>
                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_dollar,2)) }}</td>
                                        <td>{{ $budget->remarks }}</td>
                                    </tr>
                                    <?php
                                    $c_unit_b_d += ($budget->mytotal_price_dollar != 0 ? ($budget->mytotal_price_dollar/$budget->myqty) : 0 ) ;
                                    $c_unit_b_p += ($budget->mytotal_price_pkr != 0 ? ($budget->mytotal_price_pkr/$budget->myqty) : 0);
                                    $c_total_b_d += $budget->mytotal_price_dollar;
                                    $c_total_b_p += $budget->mytotal_price_pkr;
                                    $c_qty += $budget->myqty;
                                    ?>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan='2' style="text-align:right;">Total</th>
                                    <td>{{ number_format($c_unit_b_d,2) }}</td>
                                    <td>{{ number_format($c_unit_b_p,2) }}</td>
                                    <td class='text-align-right'>{{ number_format($c_qty,2) }}</td>
                                    <td>{{ number_format($c_total_b_d,2) }}</td>
                                    <td>{{ number_format($c_total_b_p,2) }}</td>
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
                                    <th>One Off PKR</th>
                                    <th>One Off Dollar</th>
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
                                ?>

                                @foreach ($opex_budget as $key => $budget)

                                    <tr>
                                        <td>{{ empty($budget->subcategory)?'':$budget->subcategory->sub_cat_name }}</td>
                                        <td>{{ $budget->mydescription }}</td>
                                        <td class='text-align-right'>{{ ($budget->mytotal_price_dollar != 0 ? str_replace(",", "",number_format($budget->mytotal_price_dollar/$budget->myqty,2)) : 0) }}</td>
                                        <td class='text-align-right'>{{ ($budget->mytotal_price_pkr != 0 ? str_replace(",", "",number_format($budget->mytotal_price_pkr/$budget->myqty,2)) : 0 ) }}</td>
                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->myqty,2)) }}</td>
                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_pkr,2)) }}</td>
                                        <td class='text-align-right'>{{ str_replace(",", "",number_format($budget->mytotal_price_dollar,2)) }}</td>
                                        <td>{{ $budget->remarks }}</td>
                                    </tr>
                                    <?php
                                    $unit_b_d +=($budget->mytotal_price_dollar != 0 ? ($budget->mytotal_price_dollar/$budget->myqty) : 0);
                                    $unit_b_p += ($budget->mytotal_price_pkr != 0 ? ( $budget->mytotal_price_pkr/$budget->myqty ) : 0);
                                    $total_b_d +=$budget->mytotal_price_pkr;
                                    $total_b_p += $budget->mytotal_price_dollar;
                                    $qty += $budget->myqty;
                                    ?>
                                @endforeach
                                </tbody>
                                </tfoot>
                                <?php
                                $grand_u_d += $c_unit_b_d+$unit_b_d;
                                $grand_u_p += $c_unit_b_p+$unit_b_p;
                                $grand_t_d += $c_total_b_d+$total_b_d;
                                $grand_t_p += $c_total_b_p+$total_b_p;
                                $grand_qty += $c_qty+$qty;
                                ?>
                                <tfoot>
                                <tr>
                                    <th colspan='2' style="text-align:right;">Grand Total</th>
                                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_u_d,2) }}</td>
                                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_u_p,2) }}</td>
                                    <td class='text-align-right' style="text-align: right;">{{ str_replace(",", "",number_format($grand_qty,2)) }}</td>
                                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_t_d,2) }}</td>
                                    <td class='text-align-right' style="text-align: right;">{{ number_format($grand_t_p,2) }}</td>
                                </tr>
                                </tfoot>
                                <tfoot>
                                <tr>
                                    <th colspan='2' style="text-align:right;">Total</th>
                                    <td class='text-align-right'>{{ number_format($unit_b_d,2) }}</td>
                                    <td class='text-align-right'>{{ number_format($unit_b_p,2) }}</td>
                                    <td class='text-align-right'>{{ str_replace(",", "",number_format($qty,2)) }}</td>
                                    <td class='text-align-right'>{{ number_format($total_b_d,2) }}</td>
                                    <td class='text-align-right'>{{ number_format($total_b_p,2) }}</td>
                                </tr>
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
