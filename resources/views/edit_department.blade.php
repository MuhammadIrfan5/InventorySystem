@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Edit Department</h1>
                        </div>
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('department') }}" class="btn btn-success">View List</a>
                        </div>
                    </div>
                    <hr />
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
                                        <form  method="POST" action="{{ url('department/'.$department->id) }}">
                                        @method('PUT')
                                        @csrf
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">Department Name</label>
                                                        <input class="form-control py-4" id="inputFirstName" type="text" name="department_name" value="{{$department->department_name}}" placeholder="Enter department name here" Required="required" />
                                                        <span class="small text-danger">{{ $errors->first('department_name') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputdepartment_code">Department Code</label>
                                                        <input class="form-control py-4" id="inputdepartment_code" type="text" name="department_code" value="{{$department->department_code}}" placeholder="Enter department code here" Required="required" />
                                                        <span class="small text-danger">{{ $errors->first('department_code') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputcost_center">Department Cost Center</label>
                                                        <input class="form-control py-4" id="inputcost_center" type="text" name="department_cost_center" value="{{$department->department_cost_center}}" placeholder="Enter department cost center here" Required="required" />
                                                        <span class="small text-danger">{{ $errors->first('department_cost_center') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" name="update_department" value="Update Department" class="btn btn-primary btn-block">
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
