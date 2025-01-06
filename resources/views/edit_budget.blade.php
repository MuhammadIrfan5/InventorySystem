@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Edit Budget</h1>
                        </div>

                    </div>
                    <hr />
                    @if (session('msg'))
                        <div class="alert alert-success">
                            {{ session('msg') }}
                        </div>
                    @endif
                    <div class="row">

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="card border-0 rounded-lg mt-3">
                                    <div class="card-body">
                                        <form  method="POST" action="{{ url('budget/'.$budget->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category" name="category_id" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                    @foreach ($categories as $category)
                                                    @if($category->id == $budget->category_id)
                                                    <option value="{{ $category->id }}" selected>{{ $category->category_name }}</option>
                                                    @else
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub Category</label>
                                                <select class="custom-select subcategory" id="subcategory" name="sub_cat_id" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                @foreach ($subcategories as $subcategory)
                                                    @if($subcategory->id == $budget->subcategory_id)
                                                    <option value="{{ $subcategory->id }}" selected>{{ $subcategory->sub_cat_name }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="dept_type">Dept/Branch type</label>
                                                <select class="custom-select" id="dept_type" name="dept_branch_type" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                    <option value=0>Select type here</option>
                                                    <option value="head_office" <?php echo $budget->dept_branch_type=='head_office'?"selected":""; ?> >Head Office</option>
                                                    <option value="branch" <?php echo $budget->dept_branch_type=='branch'?"selected":""; ?> >Branch</option>
                                                    <option value="dr_site" <?php echo $budget->dept_branch_type=='dr_site'?"selected":""; ?> >DR Site</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('dept_branch_type') }}</span>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="dept_id">Department</label>
                                                <select class="custom-select" id="dept_id" name="dept_id" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                    <option value="{{ $budget->dept_id }}">{{ $budget->department }}</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('dept_id') }}</span>
                                                <input type='hidden' id='dept' name='department' value='{{ $budget->department }}'>
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type">Budget Type</label>
                                                <select class="custom-select" id="type" name="type_id" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                    <option value=0>Select Budget type here</option>
                                                    @foreach ($types as $type)
                                                    @if($type->id == $budget->type_id)
                                                    <option value="{{ $type->id }}" selected>{{ $type->type }}</option>
                                                    @else
                                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="year">Year</label>
                                                <select class="custom-select" id="year" name="year_id" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                <option value=0>Select Year here</option>
                                                @foreach ($years as $year)
                                                @if($year->id == $budget->year_id)
                                                <option value="{{ $year->id }}" selected>{{ $year->year }}</option>
                                                @else
                                                <option value="{{ $budget->year_id }}"selected>{{ $budget->year->year }}</option>
                                                @endif
                                                @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </div>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                        <label class="small mb-1" for="description">Description</label>
                                                        <textarea class="form-control" id="description" name="description" rows="8" placeholder="Enter Description here">{{ $budget->description }}</textarea>
                                                        <span class="small text-danger">{{ $errors->first('description') }}</span>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="u_dollar">Unit Price $</label>
                                                            <input class="form-control py-2" id="u_dollar" name="unit_dollar" type="text" value="{{ $budget->unit_price_dollar }}" placeholder="Enter unit price in $ here" <?php echo $budget->consumed>=1?"readonly":""; ?>/>
                                                            <span class="small text-danger">{{ $errors->first('unit_dollar') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="pkr">Unit Price PKR</label>
                                                            <input class="form-control py-2" id="pkr" name="unit_pkr" type="text" value="{{ $pkr->pkr_val }}" placeholder="Enter unit price in pkr here" readonly />
                                                            <span class="small text-danger">{{ $errors->first('unit_pkr') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="qty">Quantity</label>
                                                            <input class="form-control py-2" id="qty" name="qty" type="number" value="{{ $budget->qty }}" placeholder="Enter quantity here" <?php echo $budget->consumed>=1?"readonly":""; ?> />
                                                            <span class="small text-danger">{{ $errors->first('qty') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="t_dollar">Total Price $</label>
                                                            <input class="form-control py-2" id="t_dollar" name="total_dollar" type="text" value="{{ $budget->total_price_dollar }}" placeholder="Enter total price in $ here" readonly />
                                                            <span class="small text-danger">{{ $errors->first('total_dollar') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="small mb-1" for="t_pkr">Total Price PKR</label>
                                                            <input class="form-control py-2" id="t_pkr" name="total_pkr" type="text" value="{{ $budget->total_price_pkr }}" placeholder="Enter total price in pkr here" readonly />
                                                            <span class="small text-danger">{{ $errors->first('total_pkr') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="remarks">Remarks</label>
                                                    <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Enter Remarks here">{{ $budget->remarks }}</textarea>
                                                    <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="nature">Budget Nature</label>
                                                    <select class="custom-select" id="nature" name="budget_nature" <?php echo $budget->consumed>=1?"disabled":""; ?>>
                                                        <option value=0>Select budget nature here</option>
                                                        <option value="Original" <?php echo $budget->budget_nature == 'Original'?'selected':'' ?>>Original</option>
                                                        <option value="Adhoc" <?php echo $budget->budget_nature == 'Adhoc'?'selected':'' ?>>Adhoc</option>
                                                    </select>
                                                    <span class="small text-danger">{{ $errors->first('budget_nature') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" name="edit_budget" value="Edit Budget Item" class="btn btn-primary btn-block">
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

            const department_element = document.querySelector('#department_id_new');
            var multipleCancelButton = new Choices(department_element, {
                removeItemButton: true,
                maxItemCount: 10,
                searchResultLimit: 100,
                renderChoiceLimit: 100
            });

            $("#category").on("change",function(){
                var id = $(this).val();
                var report = $('.subcategory').data('reports');
                var subcategory = $('.subcategory');
                subcategory.empty();
                if(report == 1){
                    subcategory.append('<option value="" class="o1">All</option>');
                }
                else{
                    subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');
                }
                $.get("{{ url('subcat_by_category') }}/"+id, function(data){

                    $.each( data, function(index, value){
                        subcategory.append(
                            $('<option></option>').val(value.id).html(value.sub_cat_name)
                        );
                    });
                });
            });
            let link = '<?php echo \DB::table('links')->get()[0]->url;?>';
            var settings = {
                "url": link + "deptdataall.php?uid=1",
                "method": "GET",
                "timeout": 0,
            };
            $.ajax(settings).done(function (response) {
                var settings_dep = {
                    "url": "{{ url('get_department') }}",
                    "method": "GET",
                    "timeout": 0,
                };
                $.ajax(settings_dep).done(function (dep_response) {
                    if (response.Login != null) {
                        // console.log('department response => ',dep_response);
                        // console.log('login dep response => ',response.Login);
                        var deps = response.Login;
                        // var deps = response.Login;
                        var res = [...deps,...dep_response];
                        var dept_id = $('#dept_id');
                        $.each(res, function (index, value) {
                            dept_id.append(
                                $('<option></option>').val(value.DEPARTMENT_ID).html(value.DEPARTMENT_ID+'-'+value.DEPARTMENT)
                            );
                        });
                        var from_dept = $('#from_dept');
                        $.each(res, function (index, value) {
                            from_dept.append(
                                $('<option></option>').val(value.DEPARTMENT_ID).html(value.DEPARTMENT)
                            );
                        });
                    }
                });
            });
            $('#dept_id').change(function () {
                var dept_name = $('#dept').val($("#dept_id option:selected").text());
                $('#swap_dept_to_name').val($("#dept_id option:selected").text());

            });

            $("#year").on("change", function () {
                var id = $(this).val();
                $.get("{{ url('pkr_by_year') }}/" + id, function (data) {
                    var value = data.pkr_val;
                    $('#pkr').val(value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                });
            });


            $('#u_dollar').keyup(function () {
                var u_dollar = $(this).val();
                var qty = $('#qty').val();
                var p = $('#pkr').val();
                var dollar = u_dollar.replace(",", "");
                var pkr = p.replace(",", "");

                var total_dollar = dollar * qty;
                var total_pkr = total_dollar * pkr;
                $('#t_dollar').val(total_dollar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#t_pkr').val(total_pkr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });

            $('#qty').keyup(function () {
                var qty = $(this).val();
                var d = $('#u_dollar').val();
                var p = $('#pkr').val();
                var dollar = d.replace(",", "");
                var pkr = p.replace(",", "");

                var total_dollar = dollar * qty;
                var total_pkr = total_dollar * pkr;
                $('#t_dollar').val(total_dollar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#t_pkr').val(total_pkr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });


            $(".t_seperator").focusout(function () {
                var value = $(this).val();

                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).val(num_parts.join("."));
            });
        });
    </script>

@endsection
