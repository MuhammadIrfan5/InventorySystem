@extends("master")

@section("content")
    {{--    <style>--}}
    {{--        .field_size {--}}
    {{--            height: 30px;--}}
    {{--            padding: 0px 10px;--}}
    {{--        }--}}
    {{--    </style>--}}
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                    </div>
                    <div class="col-md-6 col-lg-6">

                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                SLA Consumption Report
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form method="GET" action="{{ url('sla_consumption_report') }}">
                                        @csrf
                                        <tr>
                                            <td>
                                                Budget Year
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="year_id">
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right">
                                                <button type="submit" class="btn btn-primary">Show</button>
                                            </td>
                                        </tr>
                                    </form>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">

                    </div>
                </div>
                @if(empty($arr))

                @else
                    <div class="card mb-4 mt-5">
                        <div class="card-body">


                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right"
                               href="{{ url('slaconsumptionexport/'.json_encode($filters)) }}">Print <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
{{--                            <a class="btn btn-sm btn-danger mb-2 float-right"--}}
{{--                               href="{{ url('export_sla_consumption/'.json_encode($filters)) }}">CSV <i--}}
{{--                                    class="fa fa-download" aria-hidden="true"></i></a>--}}
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Category</th>
                                        <th>Vendor</th>
                                        <th>Total SLA Cost</th>
                                        @for($i=0;$i<12;$i++)
                                            <th> {{ $months[$i] }}-{{$year_y}}</th>
                                        @endfor
                                        <th>Expected Value</th>
                                        <th>Available</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    $i = 1;
                                    $total_sla_cost = 0;
                                    $jan = 0;
                                    $feb = 0;
                                    $mar = 0;
                                    $apr = 0;
                                    $may = 0;
                                    $jun = 0;
                                    $jul = 0;
                                    $aug = 0;
                                    $sept = 0;
                                    $oct = 0;
                                    $nov = 0;
                                    $dec = 0;
                                    $expected_cost = 0;
                                    $available_cost = 0;
                                    ?>
                                    @foreach ($arr as $key => $log)
                                        <tr>
                                            <td class='text-align-right'>{{ $i++ }}</td>
                                            <td>{{ $log['sub_cat_id'] ?? 'No Subcategory' }}</td>
                                            <td>{{ $log['vendor_id'] ?? 'No Vendor'}}</td>
                                            <td class="t_seperator text-align-right">{{ $log['sla']->current_sla_cost ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['1']['cost'] ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['2']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['3']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['4']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['5']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['6']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['7']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['8']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['9']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['10']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['11']['cost']  ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['12']['cost'] ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{  $log['sla']->consumed_sla_cost ?? '-'}}</td>
                                            <td class="t_seperator text-align-right">{{ ($log['sla']->current_sla_cost - $log['sla']->consumed_sla_cost) ?? '-'}}</td>
                                            <td>{{ $log['created_at'] .' / '. $log['created_at']->diffForHumans() ?? 'No Date'}}</td>
                                        </tr>
                                    <?php
                                        $total_sla_cost += $log['sla']->current_sla_cost ?? 0;
                                        $jan += $log['1']['cost'] ?? 0;
                                        $feb += $log['2']['cost'] ?? 0;
                                        $mar += $log['3']['cost'] ?? 0;
                                        $apr += $log['4']['cost'] ?? 0;
                                        $may += $log['5']['cost'] ?? 0;
                                        $jun += $log['6']['cost'] ?? 0;
                                        $jul += $log['7']['cost'] ?? 0;
                                        $aug += $log['8']['cost'] ?? 0;
                                        $sept += $log['9']['cost'] ?? 0;
                                        $oct += $log['10']['cost'] ?? 0;
                                        $nov += $log['11']['cost'] ?? 0;
                                        $dec += $log['12']['cost'] ?? 0;
                                        $expected_cost += $log['sla']->consumed_sla_cost ?? 0;
                                        $available_cost += ($log['sla']->current_sla_cost - $log['sla']->consumed_sla_cost) ?? 0;
                                    ?>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan='3' style="text-align:right;">Total</th>
                                        <td class="t_seperator text-align-right">{{$total_sla_cost == 0 ? '-' : $total_sla_cost}}</td>
                                        <td class="t_seperator text-align-right">{{ $jan == 0 ? '-' : $jan }}</td>
                                        <td class="t_seperator text-align-right">{{ $feb == 0 ? '-' : $feb}}</td>
                                        <td class="t_seperator text-align-right">{{ $mar == 0 ? '-' : $mar}}</td>
                                        <td class="t_seperator text-align-right">{{ $apr == 0 ? '-' : $apr}}</td>
                                        <td class="t_seperator text-align-right">{{ $may == 0 ? '-' : $may}}</td>
                                        <td class="t_seperator text-align-right">{{ $jun == 0 ? '-' : $jun}}</td>
                                        <td class="t_seperator text-align-right">{{ $jul == 0 ? '-' : $jul}}</td>
                                        <td class="t_seperator text-align-right">{{ $aug == 0 ? '-' : $aug}}</td>
                                        <td class="t_seperator text-align-right">{{ $sept == 0 ? '-' : $sept}}</td>
                                        <td class="t_seperator text-align-right">{{ $oct == 0 ? '-' : $oct}}</td>
                                        <td class="t_seperator text-align-right">{{ $nov == 0 ? '-' : $nov}}</td>
                                        <td class="t_seperator text-align-right">{{ $dec == 0 ? '-' : $dec}}</td>
                                        <td class="t_seperator text-align-right">{{ $expected_cost == 0 ? '-' : $expected_cost}}</td>
                                        <td class="t_seperator text-align-right">{{ $available_cost == 0 ? '-' : $available_cost}}</td>
                                        <td class="t_seperator text-align-right">-</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @endif
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
            var total;
            // $(".t_seperator").focusout(function () {
            $("td.t_seperator").each(function () {
                var value = $(this).text();
                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).text(num_parts.join("."));
                // total += parseInt($(this).text());
            })
        });
    </script>

@endsection
