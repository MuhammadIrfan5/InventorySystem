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
                                    <form method="GET" action="{{ url('inventory_out') }}">
                                        @csrf

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
                                        @if(auth()->user()->id == 81 || auth()->user()->id == 1)
                                            <tr>
                                                <td>
                                                    Category
                                                </td>
                                                <td>
                                                    <select class="custom-select category" id="category"
                                                            name="category_id">
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
                                                    <select class="custom-select make field_size" id="make"
                                                            name="make_id">
                                                        <option value="">All</option>
                                                        @foreach ($makes as $make)
                                                            <option
                                                                value="{{ $make->id }}">{{ $make->make_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('make_id') }}</span>
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
                                                    <span
                                                        class="small text-danger">{{ $errors->first('model_id') }}</span>
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
                                                    <span
                                                        class="small text-danger">{{ $errors->first('store_id') }}</span>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    Item Nature
                                                </td>
                                                <td>
                                                    <select class="custom-select field_size" id="nature"
                                                            name="item_nature_id">
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
                                                    <select class="custom-select field_size" id="vendor"
                                                            name="vendor_id">
                                                        <option value="">All</option>
                                                        @foreach ($vendors as $vendor)
                                                            <option
                                                                value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                                </td>

                                            </tr>
                                        @endif
                                        <tr>
                                            <td>
                                                Purchase Date From
                                            </td>
                                            <td>
                                                <input class="form-control field_size" id="p_date"
                                                       name="purchase_date_from"
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
                                                <input class="form-control field_size" id="p_date"
                                                       name="purchase_date_to"
                                                       type="date" placeholder="Enter purchase date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('purchase_date_to') }}</span>
                                            </td>
                                        </tr>
                                        @if(auth()->user()->id == 81 || auth()->user()->id == 1)
                                            <tr>
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
                                                    <option value="0">All</option>
                                                    @foreach ($departments as $id=>$department)
                                                        <option value="{{ $id }}">{{ $department }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('dept_id') }}</span>
                                            </td>
                                        </tr>
                                        @endif
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
                                        {{--                                        <tr>--}}
                                        {{--                                            <td>--}}
                                        {{--                                                Years--}}
                                        {{--                                            </td>--}}

                                        {{--                                            <td>--}}
                                        {{--                                                <select class="custom-select" name="year_id">--}}
                                        {{--                                                    <option value="">All</option>--}}
                                        {{--                                                    @foreach ($years as $year)--}}

                                        {{--                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>--}}

                                        {{--                                                    @endforeach--}}
                                        {{--                                                </select>--}}
                                        {{--                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>--}}
                                        {{--                                            </td>--}}
                                        {{--                                        </tr>--}}
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
                        @if(empty($inventories))
                        @else
                            @if(request()->user()['id']==81)
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right"
                               href="{{ url('inventoryoutexport/'.json_encode($filters)) }}">Print <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right"
                               href="{{ url('export_inventoryout/'.json_encode($filters)) }}">CSV <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 mr-2 float-right"
                               href="{{ url('generate_barcode/'.json_encode($filters)) }}">Generate QRCode <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                        @endif
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Item Category</th>
                                    <th>Product S#</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Issue to</th>
                                    <th>Location</th>
                                    <th>Issue By</th>
                                    <th>Issue Date</th>
                                    <th>Purchase Date</th>
                                    <th>Initial Status</th>
                                    <th>Current Condition</th>
                                    <th>Current Consumer</th>
                                    <th>Confirm/Reject Status</th>
                                    <th>Confirm/Reject Remarks</th>
                                    <th>Confirm/Reject Date</th>
                                    <th>Base Remarks</th>
                                    <th>Issuance Remarks</th>
                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'reverification_email') == true)
                                        <th>Re-verification</th>
                                    @endif
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($inventories as $inventory)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ $inventory->subcategory_id ? $inventory->subcategory->sub_cat_name:'' }}</td>
                                        <td>
                                            <a href="{{ url('item_detail/'.$inventory->id) }}">{{ $inventory->product_sn }}</a>
                                        </td>
                                        <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
                                        <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
                                        <td>{{ empty($inventory->issued_to)?'':$inventory->issued_to->name }}</td>
                                        <td>{{ empty($inventory->issued_to)?'':$inventory->issued_to->department }}</td>
                                        <td>{{ empty($inventory->issued_by)?'':$inventory->issued_by->name }}</td>
                                        <td>{{ empty($inventory->issue_date)?'':date('d-M-Y' ,strtotime($inventory->issue_date->created_at)) }}</td>
                                        <td>{{ empty($inventory->purchase_date)?'':date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>
                                        <td>{{ empty($inventory->inventorytype)?'':$inventory->inventorytype->inventorytype_name }}</td>
                                        <td>{{ empty($inventory->devicetype)?'':$inventory->devicetype->devicetype_name }}</td>
                                        <td>{{ $inventory->current_consumer }}</td>

                                        @if( $inventory->employee_status_data['received_status'] == 1 )
                                            <td><h4><span class="badge badge-success">Confirmed</span></h4></td>
                                        @elseif($inventory->employee_status_data['received_status'] == 2)
                                            <td><h4><span class="badge badge-warning">Rejected</span></h4></td>
                                        @else
                                            <td><h4><span class="badge badge-info">Pending</span></h4></td>
                                        @endif
                                        <td>
                                            {{$inventory->employee_status_data['receive_remarks'] ?? $inventory->employee_status_data['rejecter_remarks']}}
                                        </td>
                                        <td>
                                            {{$inventory->employee_status_data['received_at'] ?? $inventory->employee_status_data['rejecter_remarks_at']}}
                                        </td>
                                        <td>{{ $inventory->remarks }}</td>
                                        <td>{{ $inventory->issue_remarks['remarks'] }}</td>
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'reverification_email') == true)
                                            <td>

                                                @if($inventory->issued_to != null)
                                                    <form method="POST"
                                                          action="{{ url('reverification_email/'.$inventory->id) }}"
                                                          class="d-inline-block">
                                                        @csrf
                                                        <button type="submit"
                                                                class="{{$inventory->employee_status_data['received_status'] == 1 ? 'btn btn-success' : 'btn btn-primary'}}">
                                                            Email
                                                        </button>
                                                    </form>
                                                    {{--                                            <a href="{{ url('reverification_email/'.$inventory->issued_to->id.'/'.$inventory->id) }}" class="btn btn-sm btn-success">--}}
                                                    {{--                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-check" viewBox="0 0 16 16">--}}
                                                    {{--                                                    <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>--}}
                                                    {{--                                                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>--}}
                                                    {{--                                                </svg>--}}
                                                    {{--                                            </a>--}}
                                                @endif()
                                            </td>
                                        @endif
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

            $(".deptout").on("change", function () {
                var id = $(this).val();
                var empout = $('.empout');
                empout.empty();
                empout.append('<option value="" class="o1">All</option>');
                $.get("{{ url('employees_by_dept') }}/" + id, function (data) {
                    $.each(data, function (index, value) {
                        empout.append(
                            $('<option></option>').val(value.emp_code).html(value.emp_code + ' - ' + value.name)
                        );
                    });
                });
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

            {{--$("#btn_submit").on("click", function () {--}}
            {{--    var table = $('#dataTable').DataTable({--}}
            {{--        processing: true,--}}
            {{--        serverSide: true,--}}
            {{--        ajax: "{{ route('inventory_out') }}",--}}
            {{--        columns: [--}}
            {{--            {data: 'id', name: 'id'},--}}
            {{--            {data: 'file_original_name', name: 'Name'},--}}
            {{--            {data: 'file_extension', name: 'Extension', searchable: true},--}}
            {{--            {data: 'file_size', name: 'Size'},--}}
            {{--            {data: 'action', name: 'action', orderable: true, searchable: true},--}}
            {{--        ],--}}
            {{--        "pageLength": 5--}}
            {{--    });--}}
            {{--});--}}

        });
    </script>

@endsection
