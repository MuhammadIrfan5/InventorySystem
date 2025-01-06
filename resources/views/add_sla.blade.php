@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Add SLA / Subscription</h1>
                    </div>
                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'view_sla') == true)
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{url('sla')}}" class="btn btn-success">View List</a>
                        </div>
                    @endif
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @elseif(session('error-msg'))
                    <div class="alert alert-danger">
                        {{ session('error-msg') }}
                    </div>
                @endif
                {{--                @if (session('error-msg'))--}}

                {{--                @endif--}}
                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card border-0 rounded-lg mt-3">
                            <div class="card-body">
                                <form method="POST" action="{{ url('sla') }}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type_id">Type</label>
                                                <select class="custom-select type_id" id="type_id" name="type_id">
                                                    <option value=0>Select Type here</option>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="year_id">Year</label>
                                                <select class="custom-select year_id" id="year_id" name="year_id">
                                                    <option value=0>Select Year here</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category_id">
                                                    <option selected>Service Level Agreement</option>
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub-Category</label>
                                                <select class="custom-select subcategory1" id="subcategory_id"
                                                        name="subcategory_id">
                                                    <option selected>Select Sub Category</option>
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="vendor_id">Vendor</label>
                                                <select class="custom-select vendorid" id="vendors_id" name="vendor_id">
                                                    <option value=0>Select Vendor here</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option
                                                            value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="qty">Quantity</label>
                                                <input class="form-control" name="qty" type="text"/>
                                                <span class="small text-danger">{{ $errors->first('qty') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="agreement_start_date">Agreement Start
                                                    Date</label>
                                                <input class="form-control py-2 purchase_date"
                                                       id="contract_issue_date" name="agreement_start_date" type="date"
                                                       placeholder="Enter Agreement Start date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('agreement_start_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="agreement_end_date">Agreement End
                                                    Date</label>
                                                <input class="form-control py-2 purchase_date"
                                                       id="contract_end_date" name="agreement_end_date" type="date"
                                                       placeholder="Enter Agreement End date here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('agreement_end_date') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="current_dollar_rate">Current Dollar
                                                    Rate</label>
                                                <input class="form-control py-2 current_dollar_rate"
                                                       id="current_dollar_rate" name="current_dollar_rate" type="number"
                                                       placeholder="Enter Current Dollar Rate here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('current_dollar_rate') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="current_sla_cost">Current SLA
                                                    Cost</label>
                                                <input class="form-control py-2 t_seperator current_sla_cost"
                                                       id="current_sla_cost" name="current_sla_cost" type="text"
                                                       placeholder="Enter Current SLA Cost here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('current_dollar_rate') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="remarks">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="8"
                                                          placeholder="Enter Remarks here"></textarea>
                                                <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" id="btn_add_inventory_invoice" name="add_sla"
                                               value="Add Service Level Agreement"
                                               class="btn btn-primary btn-block">
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

            $('#type_id').on('change', function () {

                var id = $(this).val();
                var report = $('#year_id').data('reports');
                var years = $('#year_id');
                years.empty();
                if (report == 1) {
                    years.append('<option value="" class="o1">All</option>');
                } else {
                    years.append('<option value=0 class="o1">Select Year here</option>');
                }
                console.log("{{url()->current()}}");
                var current_url = "{{url()->current()}}";
                var url_id = current_url.substring(current_url.lastIndexOf('/') + 1)
                var new_url
                if ("{{url()->current()}}" == "http://inventory.efulife.online/add_sla" || "{{url()->current()}}" == "http://inventory.efulife.online/sla/" + url_id) {
                    new_url = "{{  url('get_year_by_type') }}/" + id;
                }
                if ("{{url()->current()}}" == "http://inventory.efulife.online/add_sla_log" || "{{url()->current()}}" == "http://inventory.efulife.online/slalog/" + url_id) {
                    new_url = "{{  url('get_year_by_type_SLA') }}/" + id
                } else {
                    new_url = "{{  url('get_year_by_type') }}/" + id
                }
                // console.log(new_url);
                $.get(new_url
                    , function (data) {
                        $.each(data, function (i, item) {
                            $('#year_id').append($('<option>', {
                                value: item.id,
                                text: item.year
                            }));
                        });

                    });
            });

            $('.year_id').on('change', function () {
                var type_id = $('#type_id').val();
                var id = $(this).val();
                var report = $('.subcategory1').data('reports');
                var subcategory1 = $('.subcategory1');
                subcategory1.empty();
                if (report == 1) {
                    subcategory1.append('<option value="" class="o1">All</option>');
                } else {
                    subcategory1.append('<option value=0 class="o1">Select Sub Category here</option>');
                }
                var current_url = "{{url()->current()}}";
                var url_id = current_url.substring(current_url.lastIndexOf('/') + 1)
                if ("{{url()->current()}}" == "http://inventory.efulife.online/add_sla" || "{{url()->current()}}" == "http://inventory.efulife.online/sla/" + url_id) {
                    var my_url = "{{  url('get_subcat_by_year') }}/" + id + "/" + type_id;
                    console.log('myurl: ' + my_url);
                } else {
                    var my_url = "{{  url('get_subcat_by_year_SLA') }}/" + id + "/" + type_id;
                    console.log('myurl: ' + my_url);
                }
                $.get(
                    my_url
                    , function (data) {
                        $.each(data, function (i, item) {
                            $('.subcategory1').append($('<option>', {
                                value: item.id,
                                text: item.sub_cat_name
                            }));
                        });

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
    @include('layouts.customScript')

@endsection
