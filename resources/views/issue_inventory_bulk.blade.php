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
                <form method="POST" action="{{ url('submitt_issue_with_bulk') }}">
                    @csrf
                    <div class="row justify-content-center">

                        <div class="col-md-8 col-lg-8">
                            <div class="card mt-3">
                                <div class="card-header bg-primary text-white">
                                    Issue Inventory
                                </div>
                                <div class="card-body">
                                    <div class="form-group">

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="emp_no">Employee Code</label>
                                                    <input class="form-control" id="emp_no" name="employee_code"
                                                           type="text" placeholder="Enter Employee Code here"
                                                           autofocus/>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('employee_code') }}</span>
                                                    @if (session('emp_code'))
                                                        <span class="small text-danger">{{ session('emp_code') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="name">Name</label>
                                                    <input class="form-control" id="name" name="name" type="text"
                                                           placeholder="Enter name here" readonly/>
                                                    <span class="small text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="designation">Designation</label>
                                                    <input class="form-control" id="designation" name="designation"
                                                           type="text" placeholder="Enter Designation here" readonly/>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('designation') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="branches_issue">Branch</label>
                                                    <select class="custom-select" id="branches_issue" name="branch_id">
                                                        <option value=null>Select Branch here</option>
                                                        {{--                                                            @foreach($branches['Login'] as $key => $branch)--}}
                                                        {{--                                                                <option value="{{$branch['BRANCH_ID']}}">{{$branch['BRANCH_NAME']}}</option>--}}
                                                        {{--                                                            @endforeach--}}
                                                    </select>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('branch_id') }}</span>
                                                    <input name="dept_id" id="dept_id" type="hidden" value=''/>
                                                        <input name="branch" id="branches_issue_hidden" type="hidden"
                                                           value=''/>
                                                    <input name="branch_name" id="branche_name_issue_hidden"
                                                           type="hidden" value=''/>

                                                </div>
                                            </div>
                                            {{--                                                <div class="col-md-6">--}}
                                            {{--                                                    <div class="form-group">--}}
                                            {{--                                                    <label class="small mb-1" for="department">Department</label>--}}
                                            {{--                                                        <input class="form-control" id="department" name="department" type="text" placeholder="Enter Department here" readonly />--}}
                                            {{--                                                        <span class="small text-danger">{{ $errors->first('department') }}</span>--}}

                                            {{--                                                        <input name="dept_id" id="dept_id" type="hidden" value='' />--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                </div>--}}
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="location">Location</label>
                                                    <input class="form-control location" id="location" name="location"
                                                           type="text" placeholder="Enter Location here" readonly/>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('location') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="hod">HOD Name</label>
                                                    <input class="form-control" id="hod" name="hod" type="text"
                                                           placeholder="Enter HOD name here" readonly/>
                                                    <span class="small text-danger">{{ $errors->first('hod') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="email">Email Address</label>
                                                    <input class="form-control" id="email" name="email" type="text"
                                                           placeholder="Enter Email here" readonly/>
                                                    <span class="small text-danger">{{ $errors->first('email') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="status">Status</label>
                                                    <input class="form-control" id="status" name="status" type="text"
                                                           placeholder="Enter Status here" readonly/>
                                                    <span
                                                        class="small text-danger">{{ $errors->first('status') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4 mt-3">
                        <div class="card-body">
                            <span class="text-danger">{{ $errors->first('inv_id') }}</span>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="check_all"></th>

                                        <th>Product S#</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Purchase Date</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Price</th>
                                        <th>Dollar Rate</th>
                                        <th>Created at</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($inventories as $inventory)
                                        <tr data-id="{{ $inventory->id }}">
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input invid" name='inv_id[]' value="{{ $inventory->id }}">
                                                    <label class="form-check-label" for="">{{ $i++ }}</label>
                                                </div>
                                            </td>
                                            <td>{{ $inventory->product_sn }}</td>
                                            <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
                                            <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
                                            <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>
                                            <td>{{ $inventory->category_id?$inventory->category->category_name:'' }}</td>
                                            <td>{{ $inventory->subcategory_id?$inventory->subcategory->sub_cat_name:'' }}</td>
                                            <td class='text-align-right'>{{ number_format(round($inventory->item_price),2) }}</td>
                                            <td class='text-align-right'>{{ number_format($inventory->dollar_rate,2) }}</td>
                                            <td>{{ date('d-M-Y' ,strtotime($inventory->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group">
                            <textarea class="form-control" id="remarks" name="remarks" rows="4"
                                      placeholder="Enter Remarks here"></textarea>
                            <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                        </div>
                    </div>
                    <input type="hidden" id="checkedIdsInput" name="inv_ids[]">


                    <!-- <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        <select class="custom-select issue_year" id="year" name="year_id" required>
                        <option value="">Select Year here</option>
                        @foreach ($years as $year)
                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                    <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                    </div>
                    </div> -->


                    <div class="card mb-4 mt-3 budget_items">
                        <div class="card-body">
                            <span class="text-danger">{{ $errors->first('budget_id') }}</span>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Type</th>
                                        <th>Item</th>
                                        <th>Dept</th>
                                        <th>Desc</th>
                                        <th>Qty</th>
                                        <th>Price Unit $</th>
                                        <th>Price Unit Rs</th>
                                        <th>Price Total $</th>
                                        <th>Price Total Rs</th>
                                        <th>Consumed</th>
                                        <th>Rem</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>

                                    <tbody class="items_list">
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="form-group mt-4 mb-0">
                        <input type="submit" name="issue_inventory" value="Issue Inventory"
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
        $('#dataTable').dataTable( {
            "pageLength": 200,
            "ordering": false

        } );
        $(document).ready(function () {
            $('#check_all').click(function () {
                var checkboxes = $(".invid");
                // console.log(checkboxes);

                // Check or uncheck all checkboxes based on the checkAll checkbox
                checkboxes.prop('checked', $(this).prop('checked'));
                var checkedIds = [];

                // Iterate over all checkboxes and check if they are checked
                $('.invid:checked').each(function() {
                    // Get the ID of the checked checkbox
                    var checkboxId = $(this).closest('tr').data('id');

                    // Push the ID into the checkedIds array
                    checkedIds.push(checkboxId);
                });

                // Display the checked IDs in the console
                console.log('here',checkedIds);
                $('#checkedIdsInput').val(checkedIds);

            });
            $('.invid').click(function() {
                var checkedIds = [];

                // Iterate over all checkboxes and check if they are checked
                $('.invid:checked').each(function() {
                    // Get the ID of the checked checkbox
                    var checkboxId = $(this).closest('tr').data('id');

                    // Push the ID into the checkedIds array
                    checkedIds.push(checkboxId);
                });

                // Display the checked IDs in the console
                console.log('here Single Check',checkedIds);
                $('#checkedIdsInput').val(checkedIds);
                // document.getElementById('checkedIdsInput').value =checkedIds;
                // Uncheck the checkAll checkbox if any of the individual checkboxes are unchecked
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }
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

            $(".budget_items").hide();
            $("#dataTable").on("click", ".invid", function () {
                // var inv_id = $("input[type='checkbox']:checked").val();
                var inv_id = $(this).closest('tr').data('id');
                console.log('id',inv_id)
                var dept_id = $('#branches_issue_hidden').val();
                // var dept_id = $('#branches_issue').val();
                console.log(inv_id + ' : ' + dept_id);
                if(inv_id != undefined && dept_id != ''){
                    $.get("{{ url('get_budget_items') }}/" + inv_id + "/" + dept_id, function (data) {
                        $(".items_list").empty();
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
                <input type="radio" class="form-check-input" id="budget_id" name='budget_id[]' value="` + value.id + `">
                <label class="form-check-label" for="budget_id">` + i + `</label></td>
                <td>` + value.type.type + `</td>
                <td>` + value.subcategory.sub_cat_name + `</td>
                <td>` + value.department + `</td>
                <td>` + value.description + `</td>
                <td class='text-align-right'>` + value.qty + `</td>
                <td>` + value.unit_price_dollar + `</td>
                <td>` + value.unit_price_pkr + `</td>
                <td>` + (value.unit_price_dollar * value.qty) + `</td>
                <td>` + (value.unit_price_pkr * value.qty) + `</td>
                <td class='text-align-right'>` + value.consumed + `</td>
                <td class='text-align-right'>` + value.remaining + `</td>
                <td>` + value.remarks + `</td>
                </tr>
                `);
                                i++;
                            });
                        }
                        $(".budget_items").show();
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
        });
    </script>

@endsection
