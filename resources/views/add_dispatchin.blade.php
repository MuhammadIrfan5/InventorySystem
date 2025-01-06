@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Dispatch IN Form</h1>
                        </div>
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('dispatchin') }}" class="btn btn-success">View List</a>
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
                                        <form  method="POST" action="{{ url('dispatchin') }}">
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category" name="category_id">
                                                    <option value=0>Select Category here</option>
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub Category</label>
                                                <select class="custom-select subcategory dinout_subcategory" id="subcategory" name="subcategory_id" data-action="in">
                                                <option value=0>Select Sub Category here</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
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
                                                        <label class="small mb-1" for="date">Dispatch IN Date</label>
                                                        <input class="form-control py-2" id="date" name="dispatchin_date" type="date" placeholder="Enter dispatch in date here" />
                                                        <span class="small text-danger">{{ $errors->first('dispatchin_date') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label class="small mb-1" for="reason">Memo Attached</label>
                                                <select class="custom-select" id="reason" name="memo">
                                                    <option value="">Select here</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('memo') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="department">Department</label>
                                                        <input class="form-control py-2 department" id="departmentName" name="department" type="text" placeholder="Enter department here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('department') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="last_user">Assigned User</label>
                                                        <input class="form-control py-2 last_user" id="assigned_user" name="assigned_user" type="text" placeholder="Enter Assigned user here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('assigned_user') }}</span>
                                                        <input type="hidden" class="form-control py-2 last_user_id" id="assigned_user_id" name="assigned_user_id" type="text" placeholder="Enter Assigned user here" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="remarks">Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Enter Remarks here"></textarea>
                                                        <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" value="Submit Dispatch IN" class="btn btn-primary btn-block">
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


            $(".dinout_subcategory").on("change", function () {
                var id = $(this).val();
                var action = $(this).data("action");
                var url;
                var item_list = $('.item_list');
                item_list.empty();
                item_list.append('<option value=0 class="o1">Select Item here</option>');

                url = "{{ url('get_assigned_items') }}/" + id + "/" + action;

                $.get(url, function (data) {
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
                        $(".p_date").val(data.purchase_date);
                        $("#departmentName").val(data.departmentName);
                        $(".last_user").val(data.userName);
                        $(".last_user_id").val(data.userId);
                    }
                    // console.log(data);
                });
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
