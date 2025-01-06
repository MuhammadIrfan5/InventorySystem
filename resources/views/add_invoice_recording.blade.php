@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Add Invoice</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('invoice') }}" class="btn btn-success">View List</a>
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
                                <form method="POST" action="{{ url('invoice_inventory') }}">
                                    @csrf
                                    <input type='hidden' name='status' value='1'>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type_id">Type</label>
                                                <select class="custom-select type_id" id="type_id" name="type_id">
                                                    <option value=0>Select Type here</option>
                                                    {{--                                                    @foreach ($types as $type)--}}
                                                    <option value="{{ $types->id }}">{{ $types->type }}</option>
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="year_id">Year</label>
                                                <select class="custom-select invoice_year_id" id="year_id"
                                                        name="year_id">
                                                    <option value=0>Select Year here</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="vendor_id">Vendor</label>
                                                <select class="custom-select" id="vendor_id" name="vendor_id">
                                                    <option value=0>Select Vendor here</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option
                                                            value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="po">PO Number</label>
                                                <input class="form-control py-2" id="po" name="po_number" type="text"
                                                       placeholder="Enter PO Number here"/>
                                                <span class="small text-danger">{{ $errors->first('po_number') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="p_date">Purchase Date</label>
                                                <input class="form-control py-2 purchase_date calculatewarrantyend"
                                                       id="p_date" name="purchase_date" type="date"
                                                       placeholder="Enter purchase date here" max="{{ date('Y-m-d')}}"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('purchase_date') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="invoice_number">Invoice Number</label>
                                                <input class="form-control py-2" id="invoice_number"
                                                       name="invoice_number"
                                                       type="text" placeholder="Enter Invoice Number here"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('invoice_number') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="invoice_date">Invoice Date
                                                </label>
                                                <input class="form-control py-2" id="invoice_date"
                                                       name="invoice_date" type="date"
                                                       placeholder="Enter Invoice Date here" max="{{ date('Y-m-d')}}"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('invoice_date') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                    <div class="form-row">--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <div class="form-group">--}}
                                    {{--                                                <input type="button" value="Add new row" class="btn btn-success btn-sm" id="addRow"/>--}}
                                    {{--                                                <span--}}
                                    {{--                                                        class="small text-danger">{{ $errors->first('no_rows') }}</span>--}}
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
                                                                    id="addRow">
                                                                <i class="fas fa-plus"></i> Add
                                                            </button>
                                                            {{--                                                    <input type="button" value="Add new row" class="btn btn-success btn-sm" id="addRow"/>--}}
                                                            {{--                                                    <span--}}
                                                            {{--                                                            class="small text-danger">{{ $errors->first('no_rows') }}</span>--}}
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
                                                    <table class="table table-bordered " id="invoice_dataTable"
                                                           width="100%">
                                                        <thead>

                                                        <tr>
                                                            <th>Delete</th>
                                                            <th class="col-md-6">Category</th>
                                                            <th class="col-md-6">Sub-Category</th>
                                                            {{--                                                            <th>Make</th>--}}
                                                            {{--                                                            <th>Model</th>--}}
                                                            <th>Item Pice</th>
                                                            <th>Tax(%)</th>
                                                            <th>Dollar Rate</th>
                                                            <th>Contract Issue Date</th>
                                                            <th>Contract End Date</th>
                                                            {{--                                                            <th>Warranty Period</th>--}}
                                                            {{--                                                            <th>Warranty End Date</th>--}}
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        {{--                                                        <tr>--}}
                                                        {{--                                                            <td class='text-align-right'>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <select class="custom-select category" id="category"--}}
                                                        {{--                                                                            name="category_id">--}}
                                                        {{--                                                                        <option value=0>Select Category here</option>--}}
                                                        {{--                                                                        @foreach ($categories as $category)--}}
                                                        {{--                                                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>--}}
                                                        {{--                                                                        @endforeach--}}
                                                        {{--                                                                    </select>--}}
                                                        {{--                                                                    <span class="small text-danger">{{ $errors->first('category_id') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <select class="custom-select subcategory"--}}
                                                        {{--                                                                            id="subcategory" name="subcategory_id">--}}
                                                        {{--                                                                        <option value=0>Select Sub Category here--}}
                                                        {{--                                                                        </option>--}}
                                                        {{--                                                                    </select>--}}
                                                        {{--                                                                    <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <select class="custom-select make" id="make"--}}
                                                        {{--                                                                            name="make_id">--}}
                                                        {{--                                                                        <option value=0>Select Make here</option>--}}
                                                        {{--                                                                        @foreach ($makes as $make)--}}
                                                        {{--                                                                            <option value="{{ $make->id }}">{{ $make->make_name }}</option>--}}
                                                        {{--                                                                        @endforeach--}}
                                                        {{--                                                                    </select>--}}
                                                        {{--                                                                    <span class="small text-danger">{{ $errors->first('make_id') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <select class="custom-select model" id="model"--}}
                                                        {{--                                                                            name="model_id">--}}
                                                        {{--                                                                        <option value=0>Select Model here</option>--}}
                                                        {{--                                                                    </select>--}}
                                                        {{--                                                                    <span class="small text-danger">{{ $errors->first('model_id') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td style="width:100px;!important;">--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2 t_seperator item_price" id="price"--}}
                                                        {{--                                                                           name="item_price" type="text"--}}
                                                        {{--                                                                           placeholder="Enter Item Price here"/>--}}
                                                        {{--                                                                    <span--}}
                                                        {{--                                                                            class="small text-danger">{{ $errors->first('item_price') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2" id="tax" name="tax" type="number"--}}
                                                        {{--                                                                           placeholder="Enter Tax(%) here"/>--}}
                                                        {{--                                                                    <span class="small text-danger">{{ $errors->first('tax') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2 t_seperator" id="rate"--}}
                                                        {{--                                                                           name="dollar_rate" type="text"--}}
                                                        {{--                                                                           placeholder="Enter dollar rate here"/>--}}
                                                        {{--                                                                    <span--}}
                                                        {{--                                                                            class="small text-danger">{{ $errors->first('dollar_rate') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2 purchase_date"--}}
                                                        {{--                                                                           id="contract_issue_date" name="contract_issue_date" type="date"--}}
                                                        {{--                                                                           placeholder="Enter Contract Issue date here"/>--}}
                                                        {{--                                                                    <span--}}
                                                        {{--                                                                            class="small text-danger">{{ $errors->first('contract_issue_date') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2 purchase_date"--}}
                                                        {{--                                                                           id="contract_end_date" name="contract_end_date" type="date"--}}
                                                        {{--                                                                           placeholder="Enter Contract End date here"/>--}}
                                                        {{--                                                                    <span--}}
                                                        {{--                                                                            class="small text-danger">{{ $errors->first('contract_end_date') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2 Warrenty calculatewarrantyend"--}}
                                                        {{--                                                                           id="Warrenty" name="warrenty_period" type="number"--}}
                                                        {{--                                                                           placeholder="Enter Warrenty Period here"/>--}}
                                                        {{--                                                                    <span--}}
                                                        {{--                                                                            class="small text-danger">{{ $errors->first('warrenty_period') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                            <td>--}}
                                                        {{--                                                                <div class="form-group">--}}
                                                        {{--                                                                    <input class="form-control py-2 warrentyend" id="warrentyend"--}}
                                                        {{--                                                                           name="warranty_end" type="text"--}}
                                                        {{--                                                                           placeholder="Enter Warranty End here" readonly/>--}}
                                                        {{--                                                                    <span--}}
                                                        {{--                                                                            class="small text-danger">{{ $errors->first('warranty_end') }}</span>--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </td>--}}
                                                        {{--                                                        </tr>--}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" id="btn_add_inventory_invoice" name="add_inventory"
                                               value="Add Invoice Inventory"
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

        var counter = 1;
        var category_data;
        var make_data;
        var data;
        var data_make;
        var data_sn;
        var data_sn_rep;
        var t;

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


            var invoice_category = $('.category-' + counter); //+counter+""
            invoice_category.empty();
            invoice_category.append('<option value=0 class="o1">Select Category here</option>');
            jQuery.ajaxSetup({async: false});
            $.get("{{ url('get_category') }}", function (category_data) {
                data = category_data
            });

            var invoice_make = $('#make' + counter);
            invoice_make.empty();
            invoice_make.append('<option value=0 class="o1">Select Make here</option>');
            jQuery.ajaxSetup({async: false});
            $.get("{{ url('get_make') }}", function (make_data) {
                data_make = make_data
                // $.each(data, function (i, item) {
                //     $('.make').append($('<option>', {
                //         value: item.id,
                //         text: item.make_name
                //     }));
                // });
            });
            // var t = t;
            var category_data = category_data;
            var make_data = make_data;


            $("#addRow").click(function () {
                var invoice_category = $('.category-' + counter); //+counter+""
                invoice_category.empty();
                invoice_category.append('<option value=0 class="o1">Select Category here</option>');
                $.get("{{ url('get_category') }}", function (category_data) {
                    // category_data = data
                    $.each(category_data, function (i, item) {
                        $('.category-' + counter).append($('<option>', {
                            value: item.id,
                            text: item.category_name
                        }));
                    });
                });

                var invoice_make = $('#make');
                invoice_make.empty();
                invoice_make.append('<option value=0 class="o1">Select Make here</option>');
                $.get("{{ url('get_make') }}", function (data) {
                    make_data = data
                    $.each(data, function (i, item) {
                        $('.make').append($('<option>', {
                            value: item.id,
                            text: item.make_name
                        }));
                    });
                });
            });




            t = $('#invoice_dataTable').DataTable();

            // Add Invoice Scripts
            $('#addRow').on('click', function () {
                var category_row = "<select class='custom-select' style='width: auto;' name='category_id[]' onchange='get_subcat(this)' id='category-" + counter + "'>";
                category_data = data
                $.each(category_data, function (i, item) {
                    category_row += "<option value='" + item.id + "'>" + item.category_name + "</option>";
                });
                category_row += "</select>";

                var make_row = "<select class='custom-select' style='width: auto;' name='make_id[]' onchange='get_model(this)' id='make-" + counter + "'>";
                make_data = data_make
                $.each(data_make, function (i, item) {
                    make_row += "<option value='" + item.id + "'>" + item.make_name + "</option>";
                });
                make_row += "</select>";
                t.row.add([
                    '<div class="form-group" id="defaultCheck" style="margin-left:30px;padding:10px 20px 20px 20px; width: auto; height: 10px;"><input type="checkbox" class="form-check-input filled-in" id="filledInCheckbox"></div>',
                    '<div class="form-group" style="width: 100%;">' + category_row + '</div>',
                    '<div class="form-group"><select required class="custom-select subcategory' + counter + '" style="width: auto;" id="subcategory' + counter + '" name="subcategory_id[]"> <option value=0>Select Sub Category here </option></select></div>',
                    // '<div class="form-group">'+make_row+'</div>',
                    // '<div class="form-group"><select class="custom-select model'+counter+'" style="width: auto;" id="model'+counter+'" name="model_id[]"> <option value=0>Select Model here</option></select></div>',
                    '<div class="form-group"><input  required class="form-control py-2 t_seperator item_price" style="width: auto;" id="price" name="item_price[]" type="text" placeholder="Enter Item Price here"/></div>',
                    '<div class="form-group"><input required class="form-control py-2" id="tax" name="tax[]" style="width: auto;" type="text" placeholder="Enter Tax(%) here"/></div>',
                    '<div class="form-group"><input required class="form-control py-2 t_seperator" id="rate" style="width: auto;" name="dollar_rate[]" type="text" placeholder="Enter dollar rate here"/></div>',
                    '<div class="form-group"><input required class="form-control py-2 purchase_date" style="width: auto;" id="contract_issue_date" name="contract_issue_date[]" type="date"placeholder="Enter Contract Issue date here"/></div>',
                    '<div class="form-group"><input required class="form-control py-2 purchase_date" style="width: auto;" id="contract_end_date" name="contract_end_date[]" type="date"placeholder="Enter Contract End date here"/></div>',
                    // '<div class="form-group"><input required class="form-control py-2 Warrenty calculatewarrantyend'+counter+'" style="width: auto;" id="Warrenty" onkeyup="calculate_warranty(this)" e name="warrenty_period[]" type="number"placeholder="Enter Warrenty Period here"/></div>',
                    // '<div class="form-group"><input required class="form-control py-2 warrentyend'+counter+'" style="width: auto;" id="warrentyend'+counter+'" name="warranty_end[]" type="text" placeholder="Enter Warranty End here" readonly/></div>',
                ]).draw();
                t.row(counter).draw();
                counter++;
            });

            // onfocusout="t_seperator_dynamic(this)"
            // Automatically add a first row of data
            $('#addRow').click();

            $('#reload_tbl').click(function () {
                t.draw();
            });

            $('#invoice_dataTable tbody').on('click', 'tr', function () {
                $(this).toggleClass('selected');
            });

            $('#deleteRow').click(function () {
                // t.row('.selected').remove().draw(false);
                var rows = t
                    .rows('.selected')
                    .remove()
                    .draw();
            });

            // $('#contract_end_date').change(function (e) {
            //     e.preventDefault();
            //     var from = $("#contract_issue_date").val();
            //     var to = $("#contract_end_date").val();
            //
            //     if (Date.parse(from) > Date.parse(to)) {
            //         $('#btn_add_inventory_invoice').hide();
            //         alert("End date must be greater");
            //     } else {
            //         $('#btn_add_inventory_invoice').show();
            //     }
            //
            // });



            $(".t_seperator").focusout(function () {
                var value = $(this).val();

                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).val(num_parts.join("."));
                //alert(num_parts.join("."));
            });
            //END SCRIPTS

            {{--$('.invoice_year_id').on('change', function () {--}}
            {{--    var type_id = $('#type_id').val();--}}
            {{--    var id = $(this).val();--}}
            {{--    var report = $('.invoice_category_id1').data('reports');--}}
            {{--    var invoice_category_id1 = $('.invoice_category_id1');--}}
            {{--    invoice_category_id1.empty();--}}
            {{--    if (report == 1) {--}}
            {{--        invoice_category_id1.append('<option value="" class="o1">All</option>');--}}
            {{--    } else {--}}
            {{--        invoice_category_id1.append('<option value=0 class="o1">Select Category here</option>');--}}
            {{--    }--}}
            {{--    $.get("{{ url('get_cat_by_year') }}/" + id + "/" + type_id, function (data) {--}}
            {{--        $.each(data, function (i, item) {--}}
            {{--            $('.invoice_category_id1').append($('<option>', {--}}
            {{--                value: item.id,--}}
            {{--                text: item.category_name--}}
            {{--            }));--}}
            {{--        });--}}

            {{--    });--}}
            {{--});--}}


        });
        function get_subcat(obj) {
            var id = obj.value;
            var type_id = $("#type_id option:selected").val();
            var year_id = $("#year_id option:selected").val();
            var subcategory = $('#subcategory' + (counter - parseInt(1)));
            console.log('#subcategory' + (counter - parseInt(1)));
            subcategory.empty();
            subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');
            jQuery.ajaxSetup({async: false});
            $.get("{{ url('get_subcat_by_cat_year') }}/" + id + "/" + type_id + "/" + year_id, function (data) {
                console.log(data);
                $.each(data, function (i, item) {
                    subcategory.append($('<option>', {
                        value: item.id,
                        text: item.sub_cat_name
                    }));
                });

            });
        }

        // function t_seperator_dynamic(obj){
        //     // var id = obj.value;
        //     var value = obj.value;
        //     console.log(value);
        //     var num_parts = value.toString().split(".");
        //     num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //     obj.value = num_parts.join(".");
        //
        // }


    </script>
    @include('layouts.customScript')

@endsection
