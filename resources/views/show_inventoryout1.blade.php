@extends("master")

@section("content")
    <style>
        .field_size {
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
                                Inventory OUT
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form method="GET" action="#" id="filterForm" class="filteredForm">
                                        <tr>
                                            <td>
                                                From Issuance
                                            </td>
                                            <td>
                                                <input class="form-control field_size" name="from_issuance" type="date"
                                                       placeholder="Enter date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('from_issuance') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                To Issuance
                                            </td>
                                            <td>
                                                <input class="form-control field_size" name="to_issuance" type="date"
                                                       placeholder="Enter date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('to_issuance') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Category
                                            </td>
                                            <td>
                                                <select class="custom-select category" id="category" name="category_id">
                                                    <option value="">All</option>
                                                    @foreach ($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Item Category
                                            </td>
                                            <td>
                                                <select class="custom-select field_size subcategory"
                                                        name="subcategory_id" data-reports="1">
                                                    <option value="">All</option>
                                                    @foreach ($subcategories as $subcategory)
                                                        <option
                                                            value="{{ $subcategory->id }}">{{ $subcategory->sub_cat_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('subcategory_id') }}</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Location
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="location_id">
                                                    <option value="">All</option>
                                                    @foreach ($locations as $location)
                                                        <option
                                                            value="{{ $location->id }}">{{ $location->location }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('location_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Inventory Type
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="inventorytype_id">
                                                    <option value="">All</option>
                                                    @foreach ($invtypes as $invtype)
                                                        <option
                                                            value="{{ $invtype->id }}">{{ $invtype->inventorytype_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('inventorytype_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Make
                                            </td>
                                            <td>
                                                <select class="custom-select make field_size" id="make" name="make_id">
                                                    <option value="">All</option>
                                                    @foreach ($makes as $make)
                                                        <option value="{{ $make->id }}">{{ $make->make_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('make_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Model
                                            </td>
                                            <td>
                                                <select class="custom-select model field_size" id="model"
                                                        name="model_id">
                                                    <option value="">All</option>

                                                </select>
                                                <span class="small text-danger">{{ $errors->first('model_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Store
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" id="store" name="store_id">
                                                    <option value="">All</option>
                                                    @foreach ($stores as $store)
                                                        <option
                                                            value="{{ $store->id }}">{{ $store->store_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('store_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Item Nature
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" id="nature"
                                                        name="itemnature_id">
                                                    <option value="">All</option>
                                                    @foreach ($itemnatures as $itemnature)
                                                        <option
                                                            value="{{ $itemnature->id }}">{{ $itemnature->itemnature_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('item_nature') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Vendor
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" id="vendor" name="vendor_id">
                                                    <option value="">All</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option
                                                            value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Purchase Date From
                                            </td>
                                            <td>
                                                <input class="form-control field_size" id="p_date" name="purchase_date_from"
                                                       type="date" placeholder="Enter purchase date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('purchase_date_from') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Purchase Date To
                                            </td>
                                            <td>
                                                <input class="form-control field_size" id="p_date" name="purchase_date_to"
                                                       type="date" placeholder="Enter purchase date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('purchase_date_to') }}</span>
                                            </td>
                                        </tr>                                        <tr>
                                            <td>
                                                Warranty Check
                                            </td>
                                            <td>
                                                <input class="form-control field_size" id="warrentycheck"
                                                       name="warrenty_check" type="text"
                                                       placeholder="Enter Warrenty Check here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('warrenty_check') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Departments
                                            </td>
                                            <td>
                                                <select class="custom-select field_size deptout" id="deptout"
                                                        name="department_id">
                                                    <option value="">All</option>
                                                    @foreach ($departments as $id=>$department)
                                                        <option value="{{ $id }}">{{ $department }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('dept_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Users
                                            </td>

                                            <td>
                                                <select class="custom-select field_size empout" id="empout"
                                                        name="issued_to">
                                                    <option value="">All</option>
                                                    @foreach ($employees as $employee)
                                                        <option
                                                            value="{{ $employee->emp_code }}">{{ $employee->emp_code.' - '.$employee->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('issued_to') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Years
                                            </td>

                                            <td>
                                                <select class="custom-select" name="year_id">
                                                    <option value="">All</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" class="text-right">
                                                <button id="filter" type="button" class="btn btn-primary">Show</button>
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

                <br/>
                <br/>
                @if (session('success-msg'))
                    <div class="alert alert-success">
                        {{ session('success-msg') }}
                    </div>
                @elseif(session('error-msg'))
                    <div class="alert alert-error">
                        {{ session('error-msg') }}
                    </div>
                @endif

                <div class="card mb-4 mt-5">
                    <div class="card-body">
                        <div class="hallowee">
                            <a id="btn_pdf" class="btn btn-sm btn-danger mb-2 ml-1 float-right">Print <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <a id="btn_excel" class="btn btn-sm btn-danger mb-2 float-right ">CSV <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            {{--                            <a class="btn btn-sm btn-danger mb-2 mr-2 float-right"--}}
                            {{--                               href="{{ url('generate_barcode/'.json_encode($filters)) }}">Generate QRCode <i--}}
                            {{--                                    class="fa fa-download" aria-hidden="true"></i></a>--}}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="empTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Item Category</th>
                                    <th>Product S#</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Issued To</th>
                                    <th>Location</th>
                                    <th>Issued By</th>
                                    <th>Issue Date</th>
                                    <th>Initial Status</th>
                                    <th>Current Condition</th>
                                    <th>Current Consumer</th>
                                    <th>Confirm/Reject Status</th>
                                    <th>Confirm/Reject Remarks</th>
                                    <th>Confirm/Reject Date</th>
                                    <th>Base Remarks</th>
                                    <th>Issuance Remarks</th>
                                    <th>Re-Verification</th>

                                    {{--                                    <th>S.No</th>--}}
                                    {{--                                    <th>Item Category</th>--}}
                                    {{--                                    <th>Product S#</th>--}}
                                    {{--                                    <th>Make</th>--}}
                                    {{--                                    <th>Model</th>--}}
                                    {{--                                    <th>Issue to</th>--}}
                                    {{--                                    <th>Location</th>--}}
                                    {{--                                    <th>Issue By</th>--}}
                                    {{--                                    <th>Issue Date</th>--}}
                                    {{--                                    <th>Initial Status</th>--}}
                                    {{--                                    <th>Current Condition</th>--}}
                                    {{--                                    <th>Current Consumer</th>--}}
                                    {{--                                    <th>Confirm/Reject Status</th>--}}
                                    {{--                                    <th>Confirm/Reject Remarks</th>--}}
                                    {{--                                    <th>Confirm/Reject Date</th>--}}
                                    {{--                                    <th>Base Remarks</th>--}}
                                    {{--                                    <th>Issuance Remarks</th>--}}
                                    {{--                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'reverification_email') == true)--}}
                                    {{--                                        <th>Re-verification</th>--}}
                                    {{--                                    @endif--}}
                                </tr>
                                </thead>
                                <tbody>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        let reportTable;
        $(document).ready(function () {
            // DataTable
            reportTable = $('#empTable').DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    "url": "{{route('inventoryOut1.List')}}",
                    "data": function (d) {
                        var unindexed_array = $("#filterForm").serializeArray();
                        $.map(unindexed_array, function (n, i) {
                            d[n['name']] = n['value'];
                        });
                    },
                },
                buttons: ['copy', 'excel', 'pdf', 'colvis'],
                columns: [
                    {data: "id",},
                    {data: "itemCategory",},
                    {data: "ProductS",},
                    {data: "Make",},
                    {data: "Model"},
                    {data: "issued_to"},
                    {data: "location"},
                    {data: "issued_by"},
                    {data: "issue_date"},
                    {data: "inventorytype"},
                    {data: "devicetype"},
                    {data: "current_consumer"},
                    {data: "inventoryIssueRecord"},
                    {data: "receive_remarks"},
                    {data: "received_at"},
                    {data: "remarks"},
                    {data: "issue_remarks"},
                    {data: "email"},
                ],

            });
        });
        $("#btn_pdf").click(function () {
            var unindexedArray = $(".filteredForm").serializeArray();
            var indexed_array = {};
            $.map(unindexedArray, function (n, i) {
                indexed_array[n['name']] = n['value'];
            });
            document.location.href = "{{url('inventoryoutexport1/')}}" + "/" + JSON.stringify(indexed_array);
        });
        $("#btn_excel").click(function () {
            var unindexedArray = $(".filteredForm").serializeArray();
            var indexed_array = {};
            $.map(unindexedArray, function (n, i) {
                indexed_array[n['name']] = n['value'];
            });
            document.location.href = "{{url('export_inventoryout1/')}}" + "/" + JSON.stringify(indexed_array);
        });

        $("#filter").click(function (e) {
            reportTable.ajax.reload();
        });
        $("#category").on("change", function () {
            var id = $(this).val();
            var report = $('.subcategory').data('reports');
            var subcategory = $('.subcategory');
            subcategory.empty();
            if (report == 1) {
                subcategory.append('<option value="" class="o1">All</option>');
            } else {
                subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');
            }
            $.get("{{ url('subcat_by_category') }}/" + id, function (data) {

                $.each(data, function (index, value) {
                    subcategory.append(
                        $('<option></option>').val(value.id).html(value.sub_cat_name)
                    );
                });
            });
        });
    </script>
    {{--    <script type="text/javascript">--}}
    {{--        $(document).ready(function () {--}}

    {{--            $(".deptout").on("change", function () {--}}
    {{--                var id = $(this).val();--}}
    {{--                var empout = $('.empout');--}}
    {{--                empout.empty();--}}
    {{--                empout.append('<option value="" class="o1">All</option>');--}}
    {{--                $.get("{{ url('employees_by_dept') }}/" + id, function (data) {--}}
    {{--                    $.each(data, function (index, value) {--}}
    {{--                        empout.append(--}}
    {{--                            $('<option></option>').val(value.emp_code).html(value.emp_code + ' - ' + value.name)--}}
    {{--                        );--}}
    {{--                    });--}}
    {{--                });--}}
    {{--            });--}}

    {{--            --}}{{--$("#btn_submit").on("click", function () {--}}
    {{--            --}}{{--    var table = $('#dataTable').DataTable({--}}
    {{--            --}}{{--        processing: true,--}}
    {{--            --}}{{--        serverSide: true,--}}
    {{--            --}}{{--        ajax: "{{ route('inventory_out') }}",--}}
    {{--            --}}{{--        columns: [--}}
    {{--            --}}{{--            {data: 'id', name: 'id'},--}}
    {{--            --}}{{--            {data: 'file_original_name', name: 'Name'},--}}
    {{--            --}}{{--            {data: 'file_extension', name: 'Extension', searchable: true},--}}
    {{--            --}}{{--            {data: 'file_size', name: 'Size'},--}}
    {{--            --}}{{--            {data: 'action', name: 'action', orderable: true, searchable: true},--}}
    {{--            --}}{{--        ],--}}
    {{--            --}}{{--        "pageLength": 5--}}
    {{--            --}}{{--    });--}}
    {{--            --}}{{--});--}}

    {{--        });--}}
    {{--    </script>--}}

@endsection
