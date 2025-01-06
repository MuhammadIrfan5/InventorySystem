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
                                Inventory Flow
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form method="GET" action="{{ url('inventory_flow') }}">
                                        @csrf
                                        <tr>
                                            <td>
                                                Product Serial Number
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="inventory_id">
                                                    <option value="">Select Product Serial Number</option>
                                                    @foreach ($inventories as $inventory)
                                                        <option
                                                            value="{{ $inventory->id }}" {{ ($inventory->id == $arr['inv']->id ? 'selected' : '') }}>{{ $inventory->id }}
                                                            --- {{ $inventory->product_sn }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('inventory_id') }}</span>
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
                    <div class="col-md-12 col-lg-12">
                        @if(!empty($arr))
                            <div class="card mt-3">
                                <div class="card-header bg-primary text-white">
                                    Inventory Flow Data
                                </div>
                                <div class="card-body">
                                    <h1>Inventory</h1>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Subcategory</th>
                                                <th>Year</th>
                                                <th>Vendor</th>
                                                <th>Model</th>
                                                <th>Make</th>
                                                <th>Device Type</th>
                                                <th>Purchase Date</th>
                                                <th>Item Price</th>
                                                <th>Dollar Rate</th>
                                                <th>Created At</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td>{{ $arr['inv']->category->category_name ?? 'No Category' }}</td>
                                                <td>{{ $arr['inv']->subcategory->sub_cat_name ?? 'No Subcategory' }}</td>
                                                <td>{{ $arr['inv']->year->year ?? 'No Subcategory' }}</td>
                                                <td>{{ $arr['inv']->vendor->vendor_name ?? 'No Vendor' }}</td>
                                                <td>{{ $arr['inv']->model->model_name ?? 'No Model' }}</td>
                                                <td>{{ $arr['inv']->make->make_name ?? 'No Make' }}</td>
                                                <td>{{ $arr['inv']->devicetype->devicetype_name ?? 'No Model' }}</td>
                                                <td>{{ $arr['inv']['purchase_date'] ?? 'No Date' }}</td>
                                                <td>{{ $arr['inv']['item_price'] ?? 'No Price' }}</td>
                                                <td>{{ $arr['inv']['dollar_rate'] ?? 'No Dollar Rate' }}</td>
                                                <td>{{ $arr['inv']['created_at'] .' / '.  $arr['inv']['created_at']->diffForHumans() ?? 'No Date'}}</td>
                                            </tr>
                                            @if(isset($arr['inv']['issue']))
                                                <tr>
                                                    <td colspan="2">
                                                        <h5>Action</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Issue</td>
                                                    @foreach($arr['inv']['issue'] as $issue)
                                                        <td>{{ $issue->employee_name ?? "No data"  }}
                                                            At {{ $issue->issued_at ?? "No data" }}</td>
                                                    @endforeach
                                                </tr>
                                            @else
                                            @endif
                                            @if(isset($arr['inv']['transfer']))
{{--                                                <tr>--}}
{{--                                                    <td colspan="2">--}}
{{--                                                        <h5>Action</h5>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
                                                <tr>
                                                    <td colspan="2"><h5>Transfer</h5></td>

                                                    @foreach($arr['inv']['transfer'] as $transfer)
                                                        <td>{{ $transfer->from_employee_name ?? "No data"  }}
                                                            To {{ $transfer->to_employee_name ?? "No data" }}</td>
                                                    @endforeach
                                                </tr>
                                            @else
                                            @endif
                                            @if(isset($arr['inv']['rturn']))
{{--                                                <tr>--}}
{{--                                                    <td colspan="3">--}}
{{--                                                        <h5>Action</h5>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
                                                <tr>
                                                    <td colspan="3">Return</td>

                                                    @foreach($arr['inv']['rturn'] as $rturn)
                                                        <td>{{ $rturn->return_by_name ?? "No data"  }} At {{ $rturn->created_at }}</td>
                                                    @endforeach
                                                </tr>
                                            @else
                                            @endif
                                            @if(isset($arr['inv']['dispose']))
{{--                                                <tr>--}}
{{--                                                    <td colspan="4">--}}
{{--                                                        <h5>Action</h5>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
                                                <tr>
                                                    <td colspan="4">Disposal</td>

                                                    @foreach($arr['inv']['dispose'] as $dispose)
                                                        <td>{{ $dispose->status ?? "No data"  }} at {{ $dispose->dispose_date }}</td>
                                                    @endforeach
                                                </tr>
                                            @else
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        @else
                            <div class="card mb-4 mt-5">
                                <div class="card-body">
                                    <h1>No Data Found !</h1>
                                </div>
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
