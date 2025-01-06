@extends("master")

@section("content")
    <style>
        .field_size{
            height: 30px;
            padding: 0px 10px;
        }
    </style>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                    </div>
                    <div class="col-md-6 col-lg-6">

                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                Invoice Inventories
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form method="GET" action="{{ url('show_invoice_inventory_list') }}">
                                        @csrf
                                        <tr>
                                            <td>
                                                Year
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" id="years" name="year_id">
                                                    <option value="">All</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->id }}" {{ $year_id == $year->id ? 'selected' : ''  }}>{{ $year->year }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Category
                                            </td>
                                            <td>
                                                <select class="custom-select field_size category" id="category" name="category_id">
                                                    <option value="">All</option>
                                                    <option value=0>Select Sub Category here</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Sub-Category
                                            </td>
                                            <td>
                                                <select class="custom-select field_size subcategory" id="subcategory" name="subcategory_id">
                                                    <option value="">All</option>
                                                    <option value=0>Select Sub Category here</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('subcategory_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Vendor
                                            </td>
                                            <td>
                                                <select class="custom-select field_size category" id="vendor" name="vendor_id">
                                                    <option value="">All</option>
                                                    <option value=0>Select Vendor here</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}" {{ $vendor_id == $vendor->id ? 'selected' : ''  }}>{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Invoice Issue Date
                                            </td>
                                            <td>
                                                <input class="form-control field_size" name="from_date" type="date" placeholder="Enter date here" />
                                                <span class="small text-danger">{{ $errors->first('from_date') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Invoice To Date
                                            </td>
                                            <td>
                                                <input class="form-control field_size" name="to_date" type="date" placeholder="Enter date here" />
                                                <span class="small text-danger">{{ $errors->first('to_date') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right"><button type="submit" class="btn btn-primary">Show</button></td>
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
                <div class="card mb-4 mt-5">
                    <div class="card-body">
                        @if(empty($inventories))
                        @else
                            <a class="btn btn-sm btn-danger ml-1 mb-2 float-right" href="{{ url('invoiceinventoryexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_invoice_inventory/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Year</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Category</th>
{{--                                    <th>Make</th>--}}
{{--                                    <th>Model</th>--}}
{{--                                    <th>Product S#</th>--}}
{{--                                    <th>Purchase Date</th>--}}
                                    <th>Sub Category</th>
                                    <th>Vendor</th>
                                    <th>Price</th>
                                    <th>Tax(%)</th>
{{--                                    <th>Price After Tax(%)</th>--}}
{{--                                    <th>Po Number</th>--}}
                                    <th>Contract Issue Date</th>
                                    <th>Contract End Date</th>
{{--                                    <th>Issued to</th>--}}
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($inventories as $inventory)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ empty($inventory->year->year)?'':$inventory->year->year }}</td>
                                        <td>{{ empty($inventory->invoice_number)?'':$inventory->invoice_number }}</td>
                                        <td>{{ empty($inventory->invoice_date)?'':$inventory->invoice_date }}</td>
                                        <td>
                                            @foreach($inventory->cat_name as $cat)
                                                {{ \App\Category::find($cat)['category_name'] }},
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($inventory->sub_cat_name as $sub_cat)
                                                {{ \App\Subcategory::find($sub_cat)['sub_cat_name'] }},
                                            @endforeach
                                        </td>
                                        <td>{{ empty($inventory->vendor->vendor_name)?'':$inventory->vendor->vendor_name }}</td>
{{--                                        <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>--}}
{{--                                        <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>--}}
{{--                                        <td>{{ $inventory->product_sn }}</td>--}}
{{--                                        <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>--}}
{{--                                        <td>{{ empty($inventory->subcategory)?'':$inventory->subcategory->sub_cat_name }}</td>--}}
                                        <td>{{ number_format(round($inventory->item_price),2) }}</td>
                                        <td>{{ $inventory->tax }}%</td>
{{--                                        <td>{{ number_format(round($inventory->item_price_tax),2) }}</td>--}}
{{--                                        <td>{{ empty($inventory->po_number)?'':$inventory->po_number }}</td>--}}
                                        <td>{{ empty($inventory->contract_issue_date)?'':$inventory->contract_issue_date }}</td>
                                        <td>{{ empty($inventory->contract_end_date)?'':$inventory->contract_end_date }}</td>

{{--                                        <td>{{ empty($inventory->user)? 'Not Issued Yet':$inventory->user->name }}</td>--}}
                                    </tr>
                                @endforeach
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
    @include('layouts.customScript')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#years').on('change', function () {
                var id = $(this).val();
                var category = $('#category');
                category.empty();
                var report = $('#category').data('reports');
                if (report == 1) {
                    category.append('<option value="" class="o1">All</option>');
                } else {
                    category.append('<option value=0 class="o1">Select Category here</option>');
                }
                $.get("{{ url('get_catBy_year_invoice') }}/" + id , function (data) {
                    console.log(id+ " hello");
                    $.each(data, function (i, item) {
                        category.append($('<option>', {
                            value: item.id,
                            text: item.category_name
                        }));
                    });
                });
            });

            $('#category').on('change', function () {
                var id = $(this).val();
                var year_id = $('#years').val();
                var sub_category = $('#subcategory');
                sub_category.empty();
                var report = $('#subcategory').data('reports');
                if (report == 1) {
                    sub_category.append('<option value="" class="o1">All</option>');
                } else {
                    sub_category.append('<option value=0 class="o1">Select Sub-Category here</option>');
                }
                $.get("{{ url('get_subcatBy_year_invoice') }}/" + id +"/" + year_id , function (data) {
                    $.each(data, function (i, item) {
                        sub_category.append($('<option>', {
                            value: item.id,
                            text: item.sub_cat_name
                        }));
                    });
                });
            });

            {{--$('#subcategory').on('change', function () {--}}
            {{--    var id = $(this).val();--}}
            {{--    var year_id = $('#years').val();--}}
            {{--    var category_id = $('#category').val();--}}
            {{--    var vendor = $('#vendor');--}}
            {{--    vendor.empty();--}}
            {{--    var report = $('#vendor').data('reports');--}}
            {{--    if (report == 1) {--}}
            {{--        vendor.append('<option value="" class="o1">All</option>');--}}
            {{--    } else {--}}
            {{--        vendor.append('<option value=0 class="o1">Select Sub-Category here</option>');--}}
            {{--    }--}}
            {{--    console.log("subcategory_id "+ id +" category_id/" + category_id +" year_id/" + year_id );--}}
            {{--    $.get("{{ url('get_vendorBy_year_invoice') }}/" + id +"/" + category_id +"/" + year_id , function (data) {--}}
            {{--        $.each(data, function (i, item) {--}}
            {{--            vendor.append($('<option>', {--}}
            {{--                value: item.id,--}}
            {{--                text: item.vendor_name--}}
            {{--            }));--}}
            {{--        });--}}
            {{--    });--}}
            {{--});--}}

        });
    </script>

@endsection
