@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Previous IT Equipment Assignment</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('previous_inventory') }}" class="btn btn-success">View List</a>
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
                                <form method="POST" action="{{ url('previous_inventory/'.$previousEquipment->id) }}">
                                    @method('PUT')
                                    @csrf

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category" name="category_id">
                                                    <option value="{{$previousEquipment->category_id}}">{{$previousEquipment->category->category_name}}</option>
                                                    @foreach ($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub Category</label>
                                                <select class="custom-select subcategory d_subcategory" id="subcategory"
                                                        name="subcategory_id">
                                                    <option value="{{$previousEquipment->subcategory_id}}">{{$previousEquipment->subcategory->sub_cat_name}}</option>
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label class="small mb-1" for="item">Item List</label>
                                            <select class="custom-select item_list" id="item" name="inventory_id">
                                                <option value="{{$previousEquipment->inventory_id}}">{{$previousEquipment->inventory->product_sn}}</option>
                                            </select>
                                            <span class="small text-danger">{{ $errors->first('inventory_id') }}</span>
                                        </div>
{{--                                        {{dd($previousEquipment)}}--}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="branch">Branch Name</label>
                                                <select class="custom-select branch_id" id="branch_id" name="branch_id">
                                                    <option value="{{$previousEquipment->dept_id}}">{{\App\EmployeeBranch::select('branch_name')->where('branch_id',$previousEquipment->dept_id)->first()['branch_name']}}</option>
                                                    @foreach ($departments as $department)
                                                        <option
                                                            value="{{ $department->branch_id }}">{{ $department->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="employee_id">Employee</label>
                                                <select class="custom-select employee_id" id="employee_id" name="employee_id">
                                                    <option value="{{$previousEquipment->user_id}}">{{\App\Employee::where('emp_code',$previousEquipment->user_id)->first()['name']}}</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('employee_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="remarks">Remarks</label>
                                                <textarea  class="form-control" id="remarks" name="remarks" rows="4"
                                                          placeholder="Enter Remarks here">{{$previousEquipment->remarks}}</textarea>
                                                <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" value="Edit" class="btn btn-primary btn-block">
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
    @include('layouts.customScript')
    <script type="text/javascript">

        $(document).ready(function () {

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

            $(".d_subcategory").on("change", function () {
                var id = $(this).val();
                var item_list = $('.item_list');
                item_list.empty();
                item_list.append('<option value=0 class="o1">Select Item here</option>');
                $.get("{{ url('get_unassigned_items_Capex') }}/" + id, function (data) {
                    $.each(data, function (index, value) {
                        item_list.append(
                            $('<option></option>').val(value.id).html(value.product_sn)
                        );
                    });
                });
            });
            $("#branch_id").on("change", function () {
                var id = $(this).val();
                var item_list = $('.employee_id');
                item_list.empty();
                item_list.append('<option value=0 class="o1">Select Employee here</option>');
                $.get("{{ url('getEmployeeByBranchId') }}/" + id, function (data) {
                    $.each(data, function (index, value) {
                        item_list.append(
                            $('<option></option>').val(value.id).html(value.name)
                        );
                    });
                });
            });
            $(".item_list").on("change", function () {
                var id = $(this).val();
                $.get("{{ url('single_item') }}/" + id, function (data) {
                    if (data) {
                        $(".p_date").val(data.purchase_date);
                        console.log('date ==' + data.purchase_date);
                        console.log(data.user,data.user.department);
                        if (data.user) {
                            $(".department").val(data.user.department);
                            $(".last_user").val(data.user.name);
                            $(".last_user_id").val(data.user.id);
                        }
                    }
                    // console.log(data);
                });
            });
        });
    </script>

@endsection
