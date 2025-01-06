@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">SLA Complain Log</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{url('slalog')}}" class="btn btn-success">View List</a>
                    </div>
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
                                <form method="POST" action="{{ url('slalog') }}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type_id">Type</label>
                                                <select class="custom-select type_id" id="type_id" name="type_id">
                                                    <option value=0>Select Type here</option>
                                                    @foreach ($data['types'] as $type)
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
                                                <select class="custom-select category" id="category_id" >
                                                    <option selected>Service Level Agreement</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub-Category</label>
                                                <select class="custom-select subcategory1" id="subcategory_id" name="subcategory_id">
                                                    <option selected>Select Sub Category</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="vendor_id_sla">Vendor</label>
{{--                                                <select class="custom-select vendor_id_sla" id="vendor_id_sla" name="vendor_id">--}}
{{--                                                    <option selected>Select Vendor here</option>--}}
{{--                                                </select>--}}
                                                <input class="form-control vendor_id_sla_name" readonly id="vendor_id_sla_name" type="text" placeholder="Vendor" Required="required" />
                                                <input class="form-control vendor_id_sla" readonly id="vendor_id_sla" type="hidden" name="vendor_id" placeholder="Vendor" Required="required" />
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="current_sla_cost">Current SLA Cost</label>
                                                <input class="form-control current_sla_cost" readonly id="current_sla_cost_log" type="text" name="current_sla_cost_log" placeholder="Current SLA Cost" Required="required" />
                                                <span class="small text-danger">{{ $errors->first('current_sla_cost') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="vendor_id_sla">SLA Type</label>
                                                    <select class="custom-select sla_type" id="sla_type" name="sla_type">
                                                        <option selected value="0">Select SLA Type here</option>
                                                        @foreach($data['sla_types'] as $type)
                                                            <option value="{{$type->id}}">{{ $type->type }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                    </div>

{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="make_id">Make</label>--}}
{{--                                                <input class="form-control py-2" id="make_id" name="make_id" type="text" placeholder="Make" readonly/>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('make_id') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="model_id">Model</label>--}}
{{--                                                <input class="form-control py-2" id="model_id" name="model_id" type="text" placeholder="Model" readonly/>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('model_id') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="issued_to">Issued To</label>--}}
{{--                                                <input class="form-control py-2" id="issued_to" name="issued_to" type="text" placeholder="Issued To" readonly/>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('issued_to') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="issue_description">Problem / Issue</label>--}}
{{--                                                <textarea class="form-control" id="issue_description" name="issue_description" rows="5" placeholder="Enter Problem/Issue here"></textarea>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('issue_description') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="issue_occur_date">Issue Occur on Date</label>--}}
{{--                                                <input class="form-control py-2 issue_occur_date"--}}
{{--                                                       id="issue_occur_date" max="{{ date('Y-m-d')}}" name="issue_occur_date" type="datetime-local"--}}
{{--                                                />--}}
{{--                                                <span--}}
{{--                                                        class="small text-danger">{{ $errors->first('issue_occur_date') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="visit_date_time">Engineer Visit Date Time</label>--}}
{{--                                                <input class="form-control py-2 visit_date_time"--}}
{{--                                                       id="visit_date_time" name="visit_date_time" max="{{ date('Y-m-d')}}" type="datetime-local"--}}
{{--                                                />--}}
{{--                                                <span--}}
{{--                                                        class="small text-danger">{{ $errors->first('visit_date_time') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="engineer_detail">Engineer Details</label>--}}
{{--                                                <textarea class="form-control engineer_detail" id="engineer_detail" name="engineer_detail" rows="5" placeholder="Enter Engineer Details here"></textarea>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('engineer_detail') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="handed_over_date">Handed Over Date</label>--}}
{{--                                                <input class="form-control py-2 handed_over_date" id="handed_over_date" max="{{ date('Y-m-d')}}" name="handed_over_date" type="date"/>--}}
{{--                                                <span--}}
{{--                                                        class="small text-danger">{{ $errors->first('handed_over_date') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <label class="small mb-1" for="replace_type">Replace type</label>--}}
{{--                                            <select class="custom-select replace_type" id="replace_type" name="replace_type">--}}
{{--                                                <option value=0>Select Replace Type here</option>--}}
{{--                                                <option value="1">Replace</option>--}}
{{--                                                <option value="2">Repair</option>--}}
{{--                                                <option value="3">Non Repairable</option>--}}
{{--                                            </select>--}}
{{--                                            <span class="small text-danger">{{ $errors->first('replace_type') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="replace_product_sn">Replace With</label>--}}
{{--                                                <select class="custom-select replace_product_sn" id="replace_product_sn" name="replace_product_sn">--}}
{{--                                                    <option value=0>Select Product Serial Number here</option>--}}
{{--                                                    @foreach ($data['issue_product_sn'] as $product_sn)--}}
{{--                                                        <option--}}
{{--                                                                value="{{ $product_sn->id }}">{{ $product_sn->product_sn }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('replace_product_sn') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="replace_product_make_id">Make</label>--}}
{{--                                                <input class="form-control py-2" id="replace_product_make_id" name="replace_product_make_id" type="text" placeholder="Make" readonly/>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('replace_product_make_id') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="	replace_product_model_id">Model</label>--}}
{{--                                                <input class="form-control py-2" id="replace_product_model_id" name="replace_product_model_id" type="text" placeholder="Model" readonly/>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('	replace_product_model_id') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="issue_resolve_date">Issue Resolve Date</label>--}}
{{--                                                <input class="form-control py-2 issue_resolve_date"--}}
{{--                                                       id="issue_resolve_date" max="{{ date('Y-m-d')}}" name="issue_resolve_date" type="datetime-local"/>--}}
{{--                                                <span class="small text-danger">{{ $errors->first('issue_resolve_date') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="form-row">
                                        <div class="card mb-4 mt-5">
                                            <div class="card-body">
                                                <div class="col-md-4">
                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                    id="addRow_sla" >
                                                                <i class="fas fa-plus"></i> Add
                                                            </button>
                                                          </div>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                    id="deleteRow" style="margin-left: 10px;">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                    id="reload_tbl" style="margin-left: 10px;">
                                                                <i class="fas fa-trash"></i> Reload
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered " id="sla_log_dataTable"
                                                           width="100%" >
                                                        <thead>

                                                        <tr>
                                                            <th>Delete</th>
                                                            <th >Product Serial Number</th>
                                                            <th >Make</th>
                                                            <th >Model</th>
                                                            <th >Issued To</th>
                                                            <th >Problem/Issue</th>
                                                            <th>Issue Occur on Date</th>
                                                            <th>Engineer Visit Date Time</th>
                                                            <th>Engineer Details</th>
                                                            <th>Handed Over Date</th>
                                                            <th>Replace type</th>
                                                            <th>Replace With</th>
                                                            <th>Make</th>
                                                            <th>Model</th>
                                                            <th>Issue Resolve Date</th>
                                                            <th>Current Dollar Rate</th>
                                                            <th>Cost Occured</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" id="btn_add_inventory_invoice" name="add_sla_log" value="Add Service Level Agreement Log"
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
        var sla_table;
        var counter = 1;
        var category_data;
        var make_data;
        var data;
        var data_make;
        var data_sn;
        var data_sn_rep;
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

            $('.subcategory1').on('change', function () {
                // alert('here');
                var type_id = $('#type_id').val();
                var year_id = $('.year_id').val();
                var sub_cat_id = $('.subcategory1').val();

                var id = $(this).val();
                var report = $('.vendor_id_sla').data('reports');
                var vendor_id = $('.vendor_id_sla');
                vendor_id.empty();
                if (report == 1) {
                    vendor_id.append('<option value="" class="o1">All</option>');
                } else {
                    vendor_id.append('<option value=0 class="o1">Select Sub Category here</option>');
                }
                $.get("{{ url('get_vendor_by_sub_cat_year') }}/" + id + "/" + type_id + "/" + year_id, function (data) {
                    $('#vendor_id_sla').val(data[0].id);
                    $('#vendor_id_sla_name').val(data[0].vendor_name);
                    $.get("{{ url('get_sla_total_cost') }}/" + type_id + "/" + year_id + "/" + sub_cat_id , function (data) {
                        // console.log('sla_ '+data.consumed_sla_cost);
                        if(data.consumed_sla_cost != null){
                            var rem = parseInt(data.current_sla_cost) - parseInt(data.consumed_sla_cost)
                            var num_parts = rem.toString().split(".");
                            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#current_sla_cost_log').val(num_parts.join("."));
                            // $('#current_sla_cost_log').val(rem)
                        }else{
                            var rem_cost =data.current_sla_cost;
                            var num_parts = rem_cost.toString().split(".");
                            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#current_sla_cost_log').val(num_parts.join("."));
                            // $('#current_sla_cost_log').val(data.current_sla_cost)
                        }
                    });

                });
            });

            var issue_product_sn = $('.issue_product_sn_' + counter); //+counter+""
            issue_product_sn.empty();
            issue_product_sn.append('<option value=0 class="o1">Select Category here</option>');
            jQuery.ajaxSetup({async: false});
            $.get("{{ url('get_product_sn') }}", function (product_sn_data) {
                data_sn = product_sn_data
            });

            var issue_product_sn_rep = $('.replace_product_sn_' + counter); //+counter+""
            issue_product_sn_rep.empty();
            issue_product_sn_rep.append('<option value=0 class="o1">Select Category here</option>');
            jQuery.ajaxSetup({async: false});
            $.get("{{ url('get_product_sn') }}", function (product_sn_data) {
                data_sn_rep = product_sn_data
            });


            sla_table = $('#sla_log_dataTable').DataTable();
            $('#addRow_sla').on('click', function () {
                var sn_row = "<select class='custom-select issue_product_sn_" + counter + " ' style='width: auto;' name='issue_product_sn[]' id='issue_product_sn_" + counter + "'>";
                sn_data = data_sn
                $.each(sn_data, function (i, item) {
                    sn_row += "<option value='" + item.id + "'>" + item.product_sn + "</option>";
                });
                sn_row += "</select>";

                var sn_row_rep = "<select class='custom-select replace_product_sn_" + counter + " ' style='width: auto;' name='replace_product_sn[]' id='replace_product_sn_" + counter + "'>";
                sn_data_rep = data_sn
                $.each(sn_data_rep, function (i, item) {
                    sn_row_rep += "<option value='" + item.id + "'>" + item.product_sn + "</option>";
                });
                sn_row_rep += "</select>";

                sla_table.row.add([
                    '<div class="form-group"  required id="defaultCheck" style="margin-left:30px;padding:10px 20px 20px 20px; width: auto; height: 10px;"><input type="checkbox" class="form-check-input filled-in" id="filledInCheckbox"></div>',
                    '<div class="form-group" >' + sn_row + '</div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 sla_make_' + counter + '" id="sla_make_id_' + counter + '" name="make_id[]" type="text" placeholder="Make" readonly/></div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 sla_model_' + counter + '" id="sla_model_' + counter + '" name="model_id[]" type="text" placeholder="Model" readonly/></div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 sla_issued_to_' + counter + '" id="sla_issued_to_' + counter + '" name="issued_to[]" type="text" placeholder="Issued To" readonly/></div>',
                    '<div class="form-group" style="width: 300px;"><textarea class="form-control sla_issue_description_' + counter + '" id="sla_issue_description_' + counter + '" name="issue_description[]" rows="3" placeholder="Enter Problem/Issue here"></textarea></div>',
                    '<div class="form-group" ><input class="form-control py-2 issue_occur_date_' + counter + '" id="issue_occur_date_' + counter + '"  name="issue_occur_date[]" type="datetime-local" /></div>',
                    '<div class="form-group"><input class="form-control py-2 visit_date_time_' + counter + '" id="visit_date_time_' + counter + '" name="visit_date_time[]"  type="datetime-local" /></div>',
                    '<div class="form-group" style="width: 300px;"><textarea class="form-control engineer_detail_' + counter + '" id="engineer_detail_' + counter + '" name="engineer_detail[]" rows="3" placeholder="Enter Engineer Details here"></textarea></div>',
                    '<div class="form-group"><input class="form-control py-2 handed_over_date_' + counter + '" id="handed_over_date_' + counter + '" name="handed_over_date[]" type="date"/></div>',
                    '<div class="form-group" style="width: 250px;"><select class="custom-select replace_type" id="replace_type" name="replace_type[]"> ' + '<option value=0>Select Replace Type here</option><option value="1">Replace</option><option value="2">Repair</option><option value="3">Non Repairable</option></select></div>',
                    '<div class="form-group">' + sn_row_rep + '</div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 replace_product_make_id_' + counter + '" id="replace_product_make_id_' + counter + '" name="replace_product_make_id[]" type="text" placeholder="Make" readonly/></div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 replace_product_model_id_' + counter + '" id="replace_product_model_id_' + counter + '" name="replace_product_model_id[]" type="text" placeholder="Model" readonly/></div>',
                    '<div class="form-group"><input class="form-control py-2 issue_resolve_date_' + counter + '" id="issue_resolve_date_' + counter + '" name="issue_resolve_date[]" type="datetime-local"/></div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 current_dollar_rate' + counter + '" id="current_dollar_rate' + counter + '" name="current_dollar_rate[]" type="text" onfocusout="t_seperator_dynamic(this)" placeholder="Current Dollar Rate"/></div>',
                    '<div class="form-group" style="width: 250px;"><input class="form-control py-2 cost_occured' + counter + '" id="cost_occured' + counter + '" name="cost_occured[]" onfocusout="t_seperator_dynamic(this)" type="text" placeholder="Cost Occured"/></div>',
                ]).draw();
                sla_table.row(counter).draw();


                $('.issue_product_sn_' + counter).on('change', function () {
                    var id = $(this).val();
                    $.get("{{ url('get_make_model_by_psn') }}/" + id, function (data) {
                        $('#sla_make_id_' + (counter - parseInt(1))).attr("value", data.make_name);
                        $('#sla_model_' + (counter - parseInt(1))).attr("value", data.model_name);
                        $('#sla_issued_to_' + (counter - parseInt(1))).attr("value", data.issued_to ? data.issued_to : null);
                    });
                });

                $('.replace_product_sn_' + counter).on('change', function () {
                    var id = $(this).val();
                    $.get("{{ url('get_make_model_by_psn') }}/" + id, function (data) {
                        $('#replace_product_make_id_' + (counter - parseInt(1))).val(data.make_name);
                        $('#replace_product_model_id_' + (counter - parseInt(1))).val(data.model_name);
                    });
                });
                counter++;
            });

            // Automatically add a first row of data
            $('#addRow_sla').click();

            $('#reload_tbl').click(function () {
                sla_table.draw();
            });

            $('#sla_log_dataTable tbody').on('click', 'tr', function () {
                $(this).toggleClass('selected');
            });

            $('#deleteRow').click(function () {
                var rows = sla_table
                    .rows('.selected')
                    .remove()
                    .draw();
            });

        });

        function t_seperator_dynamic(obj){
            var value = obj.value;
            console.log(value);
            var num_parts = value.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            obj.value = num_parts.join(".");
        }

    </script>
    @include('layouts.customScript')
@endsection
