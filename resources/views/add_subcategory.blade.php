@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Add Sub Category</h1>
                        </div>
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('sub_category') }}" class="btn btn-success">View List</a>
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
                                        <form  method="POST" action="{{ url('sub_category') }}">
                                        @csrf
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <label class="small mb-1" for="categories">Category</label>
                                                    <select class="custom-select" id="categories" name="category_id">
                                                        <option value=0>Select Category here</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">Sub Category Name</label>
                                                        <input class="form-control" id="inputFirstName" type="text" name="sub_cat_name" placeholder="Enter sub category name here" Required="required" />
                                                        <span class="small text-danger">{{ $errors->first('sub_cat_name') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="approx_amount_pkr">Approx Amount (PKR)</label>
                                                        <input class="form-control" id="approx_amount_pkr" type="text" name="approx_amount_pkr" placeholder="Enter Approx Amount PKR" />
                                                        <span class="small text-danger">{{ $errors->first('approx_amount_pkr') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="approx_amount_dollar">Approx Amount ($)</label>
                                                        <input class="form-control" id="approx_amount_dollar" type="text" name="approx_amount_dollar" placeholder="Enter Approx Amount Dollar"/>
                                                        <span class="small text-danger">{{ $errors->first('approx_amount_dollar') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="price_updated_at">Price Updated On</label>
                                                        <input class="form-control" id="price_updated_at" type="datetime-local" name="price_updated_at"/>
                                                        <span class="small text-danger">{{ $errors->first('price_updated_at') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="threshold">Threshold</label>
                                                        <input class="form-control" id="threshold" type="text" name="threshold" placeholder="Enter Threshold here" Required="required" />
                                                        <span class="small text-danger">{{ $errors->first('threshold') }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="subcat_desc">Subcategory Description</label>
                                                        <textarea class="form-control" id="subcat_desc" name="subcat_desc" rows="4" placeholder="Enter Subcategory Description here"></textarea>
                                                        <span class="small text-danger">{{ $errors->first('subcat_desc') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="category">Reference Category</label>
                                                        <select class="custom-select" id="category" name="category_id_new">
                                                            <option value=0>Select Category here</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="small text-danger">{{ $errors->first('category_id_new') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="subcategory">Reference Sub Category</label>
                                                        <select class="custom-select subcategory" id="subcategory" multiple name="subcategory_id[]" style="width: 100%;">
                                                            <option value=0>Select Sub Category here</option>
{{--                                                            @foreach ($categories as $category)--}}
{{--                                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>--}}
{{--                                                            @endforeach--}}
                                                        </select>
                                                        <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" value="1" name="is_budget_collection" class="custom-control-input" id="customSwitch5">
                                                            <label class="custom-control-label" for="customSwitch5">Budget Collection</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" value="1" name="is_status" class="custom-control-input" id="customSwitch6">
                                                            <label class="custom-control-label" for="customSwitch6">Status</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" name="add_sub_category" value="Add Sub Category" class="btn btn-primary btn-block">
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
        var subcats_array = [];
        $(document).ready(function () {

            const subcategory_element = document.querySelector('#subcategory');
            var multipleCancelButton = new Choices(subcategory_element, {
                removeItemButton: true,
                maxItemCount: 10,
                searchResultLimit: 100,
                renderChoiceLimit: 100
            });

            $("#category").on("change",function(){
                var id = $(this).val();
                var report = $('.subcategory').data('reports');
                var subcategory = $('.subcategory');
                multipleCancelButton.setChoices(
                    [
                        { value : "" , label: ""}
                        // { value: value.sub_cat_name, label: value.sub_cat_name},
                    ],
                    'value',
                    'label',
                    true,
                );
                if(report == 1){
                    subcategory.append('<option value="" class="o1">All</option>');
                }
                else{
                    subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');
                }
                $.get("{{ url('subcat_by_category') }}/"+id, function(data){
                    // console.log("data is => "+ data);
                    // subcats_array.push(data);
                    $.each( data, function(index, value){
                        multipleCancelButton.setChoices(
                            [
                                { value: value.id, label: value.sub_cat_name},
                            ],
                            'value',
                            'label',
                            false,
                        );
                        // subcategory.append(
                        //     $('<option></option>').val(value.id).html(value.sub_cat_name)
                        // );
                    });
                });
            });

        });

    </script>
    @include('layouts.customScript')

@endsection
