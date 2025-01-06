@extends("master")
@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                @if(auth()->user()->role_id == 1)
                    {{--                    <h1 class="mt-4 text-center">Welcome <b>{{ auth()->user()->name }} </b></h1>--}}
                    <h1 class="mt-4 text-center"></h1>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    {{--                                <i class="fa-pie-chart"></i>--}}
                                    {{--                                ITEM IN STOCK--}}
                                </div>
                                <div class="chart-container">
                                    {{--                                <div id="piechart" style="width: 80%; height: 400px;"></div>--}}
                                    <div id="chartContainer" style="height: 500px; width: 100%;"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--                    <div class="card">--}}
                    {{--                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>--}}
                    {{--                    </div>--}}
                    {{--                    </div>--}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div id="chartContainer123" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div id="barChartValue" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    {{--                <div class="row">--}}
                <!-- PRODUCT LIST -->
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">Recently Added Products</h3>--}}

{{--                            <div class="card-tools">--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                                    <i class="fas fa-minus"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
{{--                                    <i class="fas fa-times"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-header -->--}}
{{--                        <div class="card-body p-0">--}}
{{--                            <ul class="products-list product-list-in-card pl-2 pr-2">--}}
{{--                                <li class="item">--}}
{{--                                    <div class="product-info">--}}
{{--                                        <a href="javascript:void(0)" class="product-title">Samsung TV--}}
{{--                                            <span class="badge badge-warning float-right">$1800</span></a>--}}
{{--                                        <span class="product-description">Samsung 32" 1080p 60Hz LED Smart HDTV.</span>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                                <!-- /.item -->--}}
{{--                                <li class="item">--}}
{{--                                    <div class="product-info">--}}
{{--                                        <a href="javascript:void(0)" class="product-title">Bicycle--}}
{{--                                            <span class="badge badge-info float-right">$700</span></a>--}}
{{--                                        <span class="product-description">26" Mongoose Dolomite Men's 7-speed, Navy Blue.</span>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                                <!-- /.item -->--}}
{{--                                <li class="item">--}}
{{--                                    <div class="product-info">--}}
{{--                                        <a href="javascript:void(0)" class="product-title">--}}
{{--                                            Xbox One <span class="badge badge-danger float-right">$350</span>--}}
{{--                                        </a>--}}
{{--                                        <span class="product-description">Xbox One Console Bundle with Halo Master Chief Collection.</span>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                                <!-- /.item -->--}}
{{--                                <li class="item">--}}
{{--                                    <div class="product-info">--}}
{{--                                        <a href="javascript:void(0)" class="product-title">PlayStation 4--}}
{{--                                            <span class="badge badge-success float-right">$399</span></a>--}}
{{--                                        <span class="product-description">PlayStation 4 500GB Console (PS4)</span>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                                <!-- /.item -->--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-body -->--}}
{{--                        <div class="card-footer text-center">--}}
{{--                            <a href="javascript:void(0)" class="uppercase">View All Products</a>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-footer -->--}}
{{--                    </div>--}}
                    <!-- /.card -->
                    {{--                </div>--}}
                    {{--                <div class="row">--}}
                    {{--                    <div class="card mb-4">--}}
                    {{--                        <div class="card-header">--}}
                    {{--                            <i class="fas fa-table mr-1"></i>--}}
                    {{--                            Ten latest Inventory items--}}
                    {{--                        </div>--}}
                    {{--                        <div class="card-body">--}}
                    {{--                            <div class="table-responsive">--}}
                    {{--                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">--}}
                    {{--                                    <thead>--}}
                    {{--                                    <tr>--}}
                    {{--                                        <th>S.No</th>--}}
                    {{--                                        <th>Product S#</th>--}}
                    {{--                                        <th>Make</th>--}}
                    {{--                                        <th>Model</th>--}}
                    {{--                                        <th>Purchase Date</th>--}}
                    {{--                                        <th>Category</th>--}}
                    {{--                                        <th>Price</th>--}}
                    {{--                                        <th>Created at</th>--}}
                    {{--                                    </tr>--}}
                    {{--                                    </thead>--}}

                    {{--                                    <tbody>--}}
                    {{--                                    <?php $i = 1; ?>--}}
                    {{--                                    @foreach ($inventories as $inventory)--}}
                    {{--                                        <tr>--}}
                    {{--                                            <td class='text-align-right'>{{ $i++ }}</td>--}}
                    {{--                                            <td>{{ $inventory->product_sn }}</td>--}}
                    {{--                                            <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>--}}
                    {{--                                            <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>--}}
                    {{--                                            <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>--}}
                    {{--                                            <td>{{ $inventory->category_id?$inventory->category->category_name:'' }}</td>--}}
                    {{--                                            <td class='text-align-right'>{{ $inventory->item_price }}</td>--}}
                    {{--                                            <td>{{ date('d-M-Y' ,strtotime($inventory->created_at)) }}</td>--}}
                    {{--                                        </tr>--}}
                    {{--                                    @endforeach--}}
                    {{--                                    </tbody>--}}
                    {{--                                </table>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                @else
                    <h1 class="mt-4 text-center">Welcome <b>{{ auth()->user()->name }} </b></h1>
                @endif
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website {{date("Y")}}</div>
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://cdnjs.com/libraries/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        <?php
        $threshold = array();
        $dataPoint = array();
        foreach ($pieChart1 as $d) {
            $dataPoint[] = [
                "label" => $d['month_name'],
                "y" => $d['count'],
            ];
        }
        foreach ($barChart as $d) {
            $threshold[] = [
                "label" => $d['subcategoryName'],
                "y" => $d['threshold'],
            ];
        }

        ?>
        $(document).ready(function () {
            var chart2 = new CanvasJS.Chart("barChartValue", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Total Dispatch IN"
                },
                axisY: {
                    title: "Quantity"
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,##0.## items",
                    dataPoints: <?php echo json_encode($branchInventory, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();
            /*Stacked Bar*/
            let chart1 = new CanvasJS.Chart("chartContainer123", {
                animationEnabled: true,
                exportEnabled: false,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Item Reorder level"
                },
                axisX: {
                    reversed: true
                },
                axisY: {
                    axisYIndex: 1,
                    includeZero: true
                },
                toolTip: {
                    shared: true
                },
                data: [{
                    type: "stackedBar",
                    name: "Test",
                },
                    {
                        type: "stackedBar",
                        name: "Inventory",
                        dataPoints: <?php echo json_encode($dataPoint, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        type: "stackedBar",
                        name: "Threshold",
                        // indexLabel: "#total",
                        indexLabelPlacement: "outside",
                        indexLabelFontSize: 15,
                        indexLabelFontWeight: "bold",
                        dataPoints: <?php echo json_encode($threshold, JSON_NUMERIC_CHECK); ?>
                    }]
            });
            chart1.render();

            let chart = new CanvasJS.Chart("chartContainer", {
                theme: "light2",
                animationEnabled: true,
                title: {
                    text: "Items in Stock"
                },
                <?php
                    foreach ($pieChart as $d) {
                        $dataPoints[] = [
                            "label" => $d['month_name'],
                            "symbol" => $d['month_name'],
                            "y" => $d['count'],
                        ];
                    }
                    ?>
                data: [{
                    type: "doughnut",
                    indexLabel: "{symbol} - {y}",
                    yValueFormatString: "#,##0.0\"%\"",
                    showInLegend: false,
                    legendText: "{label} : {y}",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        });
    </script>
@endsection
