@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Assets Disposal/By Back Form</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('dispose') }}" class="btn btn-success">View List</a>
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
                                <form method="POST" action="{{ url('dispose') }}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category" name="category_id">
                                                    <option value=0>Select Category here</option>
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
                                                    <option value=0>Select Sub Category here</option>
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
                                                <option value="">Select Item here</option>

                                            </select>
                                            <span class="small text-danger">{{ $errors->first('inventory_id') }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="date">Dispose/By Back Date</label>
                                                <input class="form-control py-2" id="date" name="dispose_date"
                                                       max="{{ date('Y-m-d')}}" type="date"
                                                       placeholder="Enter dispose date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('dispose_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="p_date">Purchase Date</label>
                                                <input class="form-control py-2 p_date" id="p_date"
                                                       max="{{ date('Y-m-d')}}" name="purchase_date" type="text"
                                                       placeholder="Enter purchase date here" readonly/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('purchase_date') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label class="small mb-1" for="reason">Reason to Dispose</label>
                                            <select class="custom-select" id="reason" name="disposalstatus_id">
                                                <option value="">Select Reason here</option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}">{{ $status->d_status }}</option>
                                                @endforeach
                                            </select>
                                            <span
                                                class="small text-danger">{{ $errors->first('disposalstatus_id') }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="department">Department</label>
                                                <input class="form-control py-2 department" id="department"
                                                       name="department" type="text" placeholder="Enter department here"
                                                       readonly/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('department') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="last_user">Last User</label>
                                                <input class="form-control py-2 last_user" id="last_user"
                                                       name="last_user" type="text" placeholder="Enter last_user here"
                                                       readonly/>
                                                <span class="small text-danger">{{ $errors->first('last_user') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="remarks">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="4"
                                                          placeholder="Enter Remarks here"></textarea>
                                                <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" value="Submit Disposal" class="btn btn-primary btn-block">
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
                $.get("{{ url('get_unassigned_items') }}/" + id, function (data) {
                    $.each(data, function (index, value) {
                        item_list.append(
                            $('<option></option>').val(value.id).html(value.product_sn)
                        );
                    });
                });
            });

            $(".item_list").on("change", function () {
                var id = $(this).val();
                $.get("{{ url('single_item') }}/" + id, function (data) {
                    if (data) {
                        console.log('date =='+data.purchase_date);
                        console.log(data.userName);
                        if (data) {
                            $(".p_date").val(data.purchase_date);
                            $(".department").val(data.departmentName);
                            $(".last_user").val(data.userName);
                            $(".last_user_id").val(data.userId);
                        }
                    }
                    // console.log(data);
                });
            });


        });
    </script>

@endsection
