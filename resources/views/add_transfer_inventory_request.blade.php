@extends("master")

@section("content")

    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Transfer Inventory Request</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('list_transfer_inventory_request') }}" class="btn btn-success">View List</a>
                        </div>
                </div>
                <hr />
                @if (session('msg'))
                    <div class="alert alert-success mt-4">
                        {{ session('msg') }}
                    </div>
                @endif
                <form method="POST" action="{{ url('transfer_inventory_request') }}">
                    @csrf
                    <div class="row justify-content-center">
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
                                                            <label class="small mb-1" for="from_emp_no">Employee
                                                                Code</label>
                                                            <input class="form-control" id="from_emp_no"
                                                                   name="from_employee_code" type="text"
                                                                   placeholder="Enter From Employee Code here" required/>
                                                            <span
                                                                class="small text-danger">{{ $errors->first('from_employee_code') }}</span>
                                                            @if (session('from_employee_code'))
                                                                <span
                                                                    class="small text-danger">{{ session('to_employee_code') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="name">Name</label>
                                                            <input class="form-control" id="name" name="name"
                                                                   type="text" placeholder="Enter name here"
                                                                   readonly/>
                                                            <span
                                                                class="small text-danger">{{ $errors->first('name') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1"
                                                                   for="location">Location</label>
                                                            <input class="form-control location" id="location"
                                                                   name="location" type="text"
                                                                   placeholder="Enter Location here" readonly/>
                                                            <span
                                                                class="small text-danger">{{ $errors->first('location') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="email">Email
                                                                Address</label>
                                                            <input class="form-control" id="email" name="email"
                                                                   type="text" placeholder="Enter Email here"
                                                                   readonly/>
                                                            <span
                                                                class="small text-danger">{{ $errors->first('email') }}</span>
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
                                                            <label class="small mb-1" for="to_emp_no">Employee
                                                                Code</label>
                                                            <input class="form-control" id="to_emp_no"
                                                                   name="to_employee_code" type="text"
                                                                   placeholder="Enter To Employee Code here" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="name">Name</label>
                                                            <input class="form-control" id="to_name" name="to_name"
                                                                   type="text" placeholder="Enter name here"
                                                                   required/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1"
                                                                   for="location">Location</label>
                                                            <input class="form-control to_location" id="to_location"
                                                                   name="to_location" type="text"
                                                                   placeholder="Enter Location here" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="email">Email
                                                                Address</label>
                                                            <input class="form-control" id="to_email" name="to_email"
                                                                   type="text" placeholder="Enter Email here"
                                                                   required/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <div class="card mb-4 mt-3 transfer_items">
                        <div class="card-header">
                            <h2 class="card-title font-weight-bold">To be completed by initiative/requesting office</h2>
                        </div>
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
                                            <th>Current Condition</th>
                                        </tr>
                                        </thead>
                                        <tbody class="items_list">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="4"
                                                  placeholder="Enter Remarks here"></textarea>
                            <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                        </div>
                    </div>
                    <div class="form-group mt-4 mb-0">
                        <input type="submit" name="transfer_inventory_request" value="Transfer Inventory"
                               class="btn btn-primary btn-block">
                    </div>
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
            $(".transfer_items").hide();
            $('#from_emp_no').keydown(function (e) {

                var code = e.keyCode || e.which;
                if (code === 9 || code === 13) {
                    e.preventDefault();
                    var emp_no = $('#from_emp_no').val();
                    var settings = {
                        "url": "{{ url('get_employee_with_inventory') }}/" + emp_no,
                        "method": "GET",
                        "timeout": 0,
                    };
                    $.ajax(settings).done(function (response) {
                        if (response != 0) {
                            $(".transfer_items").show();
                            var settings_issue_form = {
                                "url": "{{ url('get_employee_branch') }}/" + emp_no,
                                "method": "GET",
                                "timeout": 0,
                            };
                            var res = response['user'];
                            $('#name').val(res.name);
                            $('#designation').val(res.designation);
                            $('#department').val(res.department);
                            $('.location').val(res.location);
                            $('#hod').val(res.hod);
                            $('#email').val(res.email);
                            $('#status').val(res.status);
                        } else {
                            var branch = $('#branches_issue');
                            branch.empty();
                            alert('Entered employee code does not exists!');
                        }

                        $(".items_list").empty();
                        var data = response['inventory'];
                        console.log(data)
                        if (data == "0") {
                            $(".items_list").append(`
                <tr>
                <td style='text-align: center;' colspan='13'>
                Budget not available for selected inventory!
                </td>
                </tr>
                `);
                        } else {
                            var i = 1;
                            $.each(data, function (key, value) {
                                $(".items_list").append(`
                <tr>
                <td class='text-align-right'>
                <input type="checkbox" class="form-check-input" id="budget_id" name='inventoryId[]' value="` + value.id + `">
                <label class="form-check-label" for="budget_id">` + i + `</label></td>
                <td>` + value.ProductS + `</td>
                <td>` + value.Make + `</td>
                <td>` + value.Model + `</td>
                <td>` + value.currentCondition + `</td>
                </tr>
                `);
                                i++;
                            });
                        }
                    });
                }
            });

            $('#to_emp_no').keydown(function (e) {
                var code = e.keyCode || e.which;
                if (code === 9 || code === 13) {
                    e.preventDefault();
                    var emp_no = $('#to_emp_no').val();
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
                            var res = response;
                            $('#to_name').val(res.name);
                            $('#to_department').val(res.department);
                            $('.to_location').val(res.location);
                            $('#to_email').val(res.email);
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
                            $('#to_name').val('');
                            $('#to_department').val('');
                            $('.to_location').val('');
                            $('#to_email').val('');
                            alert('Entered employee code does not exists!');

                        }
                    });
                }
            });
        });

    </script>

@endsection
