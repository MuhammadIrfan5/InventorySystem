@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Assets Repairing</h1>
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
                                        <form  method="POST" action="{{ url('repair_inventory') }}">
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
                                                <select class="custom-select subcategory" id="subcategory" name="subcategory_id">
                                                <option value=0>Select Sub Category here</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                            </div>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                                <div class="col-md-6">
                                                <label class="small mb-1" for="item">Item List</label>
                                                <select class="custom-select repair_item" id="item" name="item_id">
                                                    <option value="">Select Item here</option>
                                                    @foreach ($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}">{{ $inventory->product_sn }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('item_id') }}</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="date">Date</label>
                                                        <input class="form-control py-2" id="date" name="date" type="date" placeholder="Enter date here" />
                                                        <span class="small text-danger">{{ $errors->first('date') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="a_price">Actual Price Value</label>
                                                        <input class="form-control py-2 a_price" id="a_price" name="actual_price_value" type="text" placeholder="Enter Actual Price Value here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('actual_price_value') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="small mb-1" for="price">Repairing Cost</label>
                                                        <input class="form-control py-2 t_seperator" id="price" name="price_value" type="text" placeholder="Enter Repairing Cost here" />
                                                        <span class="small text-danger">{{ $errors->first('price_value') }}</span>
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
                                            <input type="submit" name="repair_inventory" value="Repair" class="btn btn-primary btn-block">
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
                        $(".p_date").val(data.purchase_date);
                        console.log('date =='+data.purchase_date);
                        console.log(data.user);
                        if (data.user) {
                            $(".department").val(data.user.department);
                            $(".last_user").val(data.user.name);
                            $(".last_user_id").val(data.user.id);
                        }
                    }
                    // console.log(data);
                });
            });

            $(".repair_item").on("change", function () {
                var id = $(this).val();
                $.get("{{ url('get_price') }}/" + id, function (data) {
                    $('.a_price').val(data);
                });
            });


        });
    </script>

@endsection
