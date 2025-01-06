@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Edit Employee</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('employee') }}" class="btn btn-success">View List</a>
                    </div>
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-1 col-lg-1"></div>
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <div class="card border-0 rounded-lg mt-3">
                            <div class="card-body">
                                <form method="POST" action="{{ url('employee/'.$employee->id) }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="emp_no">Employee Code</label>
                                                <input class="form-control" id="emp_code"
                                                       value="{{$emp_data[0]->emp_code}}" name="emp_code" type="text"
                                                       placeholder="Enter employee code here"/>
                                                <span class="small text-danger">{{ $errors->first('emp_code') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="name">Name</label>
                                                <input class="form-control" id="name" value="{{$employee->name}}"
                                                       name="name" type="text" placeholder="Enter name here" readonly/>
                                                <span class="small text-danger">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="designation">Designation</label>
                                                <input class="form-control" id="designation"
                                                       value="{{$employee->designation}}" name="designation" type="text"
                                                       placeholder="Enter Designation here" readonly/>
                                                <span class="small text-danger">{{ $errors->first('designation') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="department">Department</label>
                                                <input class="form-control" id="department"
                                                       value="{{$employee->department}}" name="department" type="text"
                                                       placeholder="Enter Department here" readonly/>
                                                <span class="small text-danger">{{ $errors->first('department') }}</span>

                                                <input name="dept_id" id="dept_id" type="hidden"
                                                       value='{{$employee->dept_id}}'/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="location">Location</label>
                                                <input class="form-control location" id="location"
                                                       value="{{$employee->location}}" name="location" type="text"
                                                       placeholder="Enter Location here" readonly/>
                                                <span class="small text-danger">{{ $errors->first('location') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="hod">HOD Name</label>
                                                <input class="form-control" id="hod" name="hod"
                                                       value="{{$employee->hod}}" type="text"
                                                       placeholder="Enter HOD name here" readonly/>
                                                <span class="small text-danger">{{ $errors->first('hod') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="email">Email Address</label>
                                                <input class="form-control" id="email" value="{{$employee->email}}"
                                                       name="email" type="text" placeholder="Enter Email here"/>
                                                <span class="small text-danger">{{ $errors->first('email') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="status">Status</label>
                                                <input class="form-control" id="status" value="{{$employee->status}}"
                                                       name="status" type="text" placeholder="Enter Status here"
                                                       readonly/>
                                                <span class="small text-danger">{{ $errors->first('status') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="branches">Branch</label>
                                                {{--                                                        <select class="custom-select" id="branches" name="branch_id[]"--}}
                                                {{--                                                                style="width: 100%;">--}}
                                                <select class="form-control" id="edit_branches" name="branch_id[]"
                                                        multiple="multiple"
                                                        placeholder="Select Branch Here">
                                                    <option value=null>Select Branch here</option>
                                                    @foreach($branches['Login'] as $key => $branch)
                                                        <option value="{{$branch['DEPARTMENT_ID']}}">{{$branch['DEPARTMENT']}}</option>
                                                    @endforeach
                                                    @foreach($emp_data as $emp)
                                                        <option value="{{$emp->branch_id}}"
                                                                selected>{{$emp->branch_name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('branch_id') }}</span>
                                                <input name="branch_name" id="branch" type="hidden"
                                                       value='{{$emp_data[0]->branch_name ?? ''}}'/>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" value="Save Employee" class="btn btn-primary btn-block">
                                    </div>
                                </form>
                            </div>
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
    <script type="text/javascript">
        $(document).ready(function () {

            const branches_element = document.querySelector('#edit_branches');
            var multipleCancelButton_branch = new Choices(branches_element, {
                removeItemButton: true,
                maxItemCount: 1000,
                searchResultLimit: 1000,
                renderChoiceLimit: 1000
            });

            $('#edit_branches').change(function () {
                const selected = document.querySelectorAll('#edit_branches option:checked');
                const values = Array.from(selected).map(el => el.text);
                $('#branch').val(values)
                // $('#branch').val($("#branches option:selected").text());
            });

            let link = '<?php echo \DB::table('links')->get()[0]->url;?>';

            $('#emp_code').keydown(function (e) {
                var code = e.keyCode || e.which;
                if (code === 9 || code === 13) {
                    e.preventDefault();
                    var emp_code = $('#emp_code').val();

                    var settings = {

                        "url": link + "empdata.php?uid=" + emp_code,
                        "method": "GET",
                        "timeout": 0,
                    };
                    $.ajax(settings).done(function (response) {
                        if (response.Login != null) {
                            var res = response.Login[0];
                            $('#name').val(res.EMPLOYEE_NAME);
                            $('#designation').val(res.DESIGNATION);
                            $('#department').val(res.DEPARTMENT);
                            $('#dept_id').val(res.DEPARTMENT_ID);
                            $('.location').val(res.LOCATION);
                            $('#hod').val(res.HOD_NAME);
                            $('#email').val(res.EMPLOYEE_EMAIL);
                            $('#status').val(res.EMPLOYEE_STATUS);
                        } else {
                            alert('Entered employee code does not exists!');
                        }
                    });
                }
            });

        });
    </script>

@endsection
