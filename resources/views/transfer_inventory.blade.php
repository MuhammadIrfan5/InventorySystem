@extends("master")

@section("content")

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">

                @if (session('msg'))
                    <div class="alert alert-success mt-4">
                        {{ session('msg') }}
                    </div>
            @endif

            <!-- <form  method="POST" action="{{ url('transfer') }}"> -->
                <form id="form" method="" action="">
                    @csrf
                    <div class="row justify-content-center">

                        @if(isset($from_emp))
                            <div class="col-md-10 col-lg-10">
                                <table class="table table-borderless">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="card mt-3">
                                                <div class="card-header bg-primary text-white">
                                                    From Employee
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="emp_no">Employee
                                                                    Code</label>
                                                                <input class="form-control" id="from_e_code"
                                                                       name="from_employee_code" type="text"
                                                                       value="{{ $from_emp->emp_code }}" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('from_employee_code') }}</span>
                                                                @if (session('from_emp_code'))
                                                                    <span class="small text-danger">{{ session('from_emp_code') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="name">Name</label>
                                                                <input class="form-control" name="name" type="text"
                                                                       value="{{ $from_emp->name }}" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('name') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1"
                                                                       for="designation">Designation</label>
                                                                <input class="form-control" name="designation"
                                                                       type="text" value="{{ $from_emp->designation }}"
                                                                       readonly/>
                                                                <span class="small text-danger">{{ $errors->first('designation') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1"
                                                                       for="department">Department</label>
                                                                <select class="custom-select" name="department">
                                                                    @foreach($emp_dep as $emp)
                                                                        <option value="{{$emp->branch_id}}">{{ $emp->branch_name  }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="small text-danger">{{ $errors->first('department') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1"
                                                                       for="location">Location</label>
                                                                <input class="form-control" name="location" type="text"
                                                                       value="{{ $from_emp->location }}" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('location') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="hod">HOD Name</label>
                                                                <input class="form-control" name="hdd" type="text"
                                                                       value="{{ $from_emp->hod }}" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('hdd') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="email">Email
                                                                    Address</label>
                                                                <input class="form-control" name="email" type="text"
                                                                       value="{{ $from_emp->email }}" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('email') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="status">Status</label>
                                                                <input class="form-control" name="status" type="text"
                                                                       value="{{ $from_emp->status }}" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('status') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="card mt-3">
                                                <div class="card-header bg-primary text-white">
                                                    To Employee
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="emp_no">Employee
                                                                    Code</label>
                                                                <input class="form-control" id="emp_no"
                                                                       name="to_employee_code" type="text"
                                                                       placeholder="Enter To Employee Code here"/>
                                                                <span class="small text-danger">{{ $errors->first('to_employee_code') }}</span>
                                                                @if (session('to_emp_code'))
                                                                    <span class="small text-danger">{{ session('to_emp_code') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="name">Name</label>
                                                                <input class="form-control" id="name" name="name"
                                                                       type="text" placeholder="Enter name here"
                                                                       readonly/>
                                                                <span class="small text-danger">{{ $errors->first('name') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1"
                                                                       for="designation">Designation</label>
                                                                <input class="form-control" id="designation"
                                                                       name="designation" type="text"
                                                                       placeholder="Enter Designation here" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('designation') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1"
                                                                       for="department">Department</label>
{{--                                                                <input class="form-control" id="department"--}}
{{--                                                                       name="department" type="text"--}}
{{--                                                                       placeholder="Enter Department here" readonly/>--}}
{{--                                                                <span class="small text-danger">{{ $errors->first('department') }}</span>--}}

{{--                                                                <input name="dept_id" id="dept_id" type="hidden"--}}
{{--                                                                       value=''/>--}}

                                                                <select class="custom-select" id="branches_issue" name="branch_id">
                                                                    <option value=null>Select Branch here</option>
                                                                </select>
                                                                <span class="small text-danger">{{ $errors->first('department') }}</span>
                                                                <input name="to_department_id" id="dept_id" type="hidden" value=''/>
                                                                <input name="branch" id="branches_issue_hidden" type="hidden"
                                                                       value=''/>
                                                                <input name="branch_name" id="branche_name_issue_hidden"
                                                                       type="hidden" value=''/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1"
                                                                       for="location">Location</label>
                                                                <input class="form-control" id="location"
                                                                       name="location" type="text"
                                                                       placeholder="Enter Location here" readonly/>
                                                                <span class="small text-danger">{{ $errors->first('location') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="hod">HOD Name</label>
                                                                <input class="form-control" id="hod" name="hod"
                                                                       type="text" placeholder="Enter HOD name here"
                                                                       readonly/>
                                                                <span class="small text-danger">{{ $errors->first('hod') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="email">Email
                                                                    Address</label>
                                                                <input class="form-control" id="email" name="email"
                                                                       type="text" placeholder="Enter Email here"
                                                                       readonly/>
                                                                <span class="small text-danger">{{ $errors->first('email') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="small mb-1" for="status">Status</label>
                                                                <input class="form-control" id="status" name="status"
                                                                       type="text" placeholder="Enter Status here"
                                                                       readonly/>
                                                                <span class="small text-danger">{{ $errors->first('status') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                @else
                                    <div class="col-md-6 col-lg-6">
                                        <div class="card mt-3">
                                            <div class="card-header bg-primary text-white">
                                                Transfer Inventory
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <input class="form-control" id="from_e_code"
                                                                   name="from_employee_code" type="text"
                                                                   value="{{ isset($from_emp)?$from_emp->id:null }}"
                                                                   placeholder="Enter From Employee Code here"/>
                                                            <span class="small text-danger">{{ $errors->first('from_employee_code') }}</span>
                                                            @if (session('from_emp_code'))
                                                                <span class="small text-danger">{{ session('from_emp_code') }}</span>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            <button type="button" id="show" name="show"
                                                                    class="btn btn-primary">Show
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr>
                                                <td>
                                                <input type="hidden" name="from_employee" value="{{ isset($from_emp)?$from_emp->id:null }}">
                                                <input class="form-control" id="to_e_code" name="to_employee_code" type="text" placeholder="Enter To Employee Code here" />
                                                <span class="small text-danger">{{ $errors->first('to_employee_code') }}</span>
                                                @if (session('to_emp_code'))
                                                        <span class="small text-danger">{{ session('to_emp_code') }}</span>
                                                @endif
                                                            </td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                            </div>
                            <div class="card mb-4 mt-3">
                                @if(isset($from_emp))
                                    <div class="card-body">
                                        <span class="text-danger">{{ $errors->first('inv_id') }}</span>
                                        <div class="table-responsive">
                                            <table class="table table-bordered tabled" id="dataTable2" width="100%"
                                                   cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Product S#</th>
                                                    <th>Make</th>
                                                    <th>Model</th>
                                                    <th>Purchase Date</th>
                                                    <th>Category</th>
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Price</th>
                                                    <th>Dollar Rate</th>
                                                    <th>Created at</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($inventories as $inventory)
                                                    <tr>
                                                        <td>
                                                            @if(isset($filter))
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                           id="inv{{ $inventory->id }}" name="inv_id[]"
                                                                           value="{{ $inventory->id }}">
                                                                    <label class="form-check-label"
                                                                           for="inv{{ $inventory->id }}">{{ $i++ }}</label>
                                                                </div>
                                                            @else
                                                                {{ $i++ }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $inventory->product_sn }}</td>
                                                        <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
                                                        <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
                                                        <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>
                                                        <td>{{ $inventory->category_id?$inventory->category->category_name:'' }}</td>
                                                        <td class='text-align-right'>{{ $inventory->user->emp_code ?? 'no user' }}</td>
                                                        <td>{{ $inventory->user->name ?? 'user'}}</td>
                                                        <td class='text-align-right'>{{ number_format(round($inventory->item_price),2) }}</td>
                                                        <td class='text-align-right'>{{ number_format($inventory->dollar_rate,2) }}</td>
                                                        <td>{{ date('d-M-Y' ,strtotime($inventory->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(isset($filter))
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="remarks" name="remarks" rows="4"
                                                  placeholder="Enter Remarks here"></textarea>
                                        <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                    </div>
                                </div>

                                <div class="form-group mt-4 mb-0">
                                    <button type="button" name="transfer_inventory" class="btn btn-primary btn-block"
                                            id="transfer">Transfer Inventory
                                    </button>
                                </div>
                    @endif
                </form>
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

            {{--$("#show").click(function () {--}}
            {{--        $("#form").attr({method: 'GET', action: '{{ url("filter_inventory") }}'})--}}
            {{--    $("#form").submit();--}}
            {{--});--}}

            $("#show").click(function () {
                $("#form").attr({method: 'GET', action: '{{ url("filter_inventory") }}'})
                $("#form").submit();
            });
            $("#transfer").click(function () {
                $("#form").attr({method: 'POST', action: '{{ url("transfer") }}'})
                $("#form").submit();
            });

            $('#emp_no').keydown(function (e) {
                var code = e.keyCode || e.which;
                if (code === 9 || code === 13) {
                    e.preventDefault();
                    var emp_no = $('#emp_no').val();
                    var settings = {
                        "url": "{{ url('get_employee') }}/" + emp_no,
                        "method": "GET",
                        "timeout": 0,
                    };
                    $.ajax(settings).done(function (response) {
                        if (response != 0) {
                            var settings_issue_form = {
                                "url": "{{ url('get_employee_branch') }}/" + emp_no,
                                "method": "GET",
                                "timeout": 0,
                            };
                            var branch = $('#branches_issue');
                            branch.empty();
                            branch.append('<option value=0 class="o1">Select Branch here</option>');
                            var res = response;
                            $('#name').val(res.name);
                            $('#designation').val(res.designation);
                            $('#department').val(res.department);
                            $('#dept_id').val(res.dept_id);
                            $('.location').val(res.location);
                            $('#hod').val(res.hod);
                            $('#email').val(res.email);
                            $('#status').val(res.status);
                            $.ajax(settings_issue_form).done(function (response) {
                                if (response != 0) {
                                    var res = response;
                                    var branch = $('#branches_issue');
                                    $.each(res, function (index, value) {
                                        branch.append(
                                            $('<option></option>').val(value.branch_id).html(value.branch_name)
                                        );
                                    });
                                }
                            });
                        } else {
                            var branch = $('#branches_issue');
                            branch.empty();
                            alert('Entered employee code does not exists!');
                        }
                    });
                }
            });

            $('#branches_issue').change(function () {
                $('#branches_issue_hidden').val($("#branches_issue option:selected").val());
                $('#branche_name_issue_hidden').val($("#branches_issue option:selected").text());

                const selected = document.querySelectorAll('#branches_issue option:checked');
                const values = Array.from(selected).map(el => el.text);
                $('#branch_name').val(values)
            });

            $('#emp_dept_dropdown').change(function () {
                $('#branches_issue_hidden').val($("#emp_dept_dropdown option:selected").val());
                $('#branche_name_issue_hidden').val($("#emp_dept_dropdown option:selected").text());
                $('#dept_id_return').val($("#emp_dept_dropdown option:selected").val());
            });


            $(".t_seperator").focusout(function () {
                var value = $(this).val();

                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).val(num_parts.join("."));
                //alert(num_parts.join("."));
            });

            $('#dataTable2').dataTable({
                "bPaginate": false
            });
        });

    </script>

@endsection
