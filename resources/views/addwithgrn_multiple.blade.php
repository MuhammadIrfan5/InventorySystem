@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Add With GRN (Multiple)</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('pendings') }}" class="btn btn-success">View List</a>
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
                                <form  method="POST" action="{{ url('added_with_grn_multiple') }}">
                                    @csrf
                                    <input type='hidden' name='status' value='0'>
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="location">Location</label>
                                                <select class="custom-select" id="location" name="location_id">
                                                    <option value=0>Select Location here</option>
                                                    @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}">{{ $location->location }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('location_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="store">Store</label>
                                                <select class="custom-select" id="store" name="store_id">
                                                    <option value=0>Select Store here</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('store_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="invtype">Initial Status</label>
                                                <select class="custom-select" id="invtype" name="inventorytype_id">
                                                    <option value=0>Select Initial Status here</option>
                                                    @foreach ($inventorytypes as $inventorytype)
                                                        <option value="{{ $inventorytype->id }}">{{ $inventorytype->inventorytype_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('inventorytype_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="pro">Bulk Quantity</label>--}}
{{--                                                <input class="form-control py-2 bulk_qty" id="bulk_qty" name="bulk_qty" type="text" placeholder="Enter bulk quantity here" />--}}
{{--                                                <span class="small text-danger">{{ $errors->first('bulk_qty') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="small mb-1" for="remarks">Product S/N</label>
                                                <textarea class="form-control pro sn_id_area" id="pro" name="product_sn" rows="3" placeholder="Enter product s/n here with (,) seperation"></textarea>
                                                <span class="small text-danger pro_msg">{{ $errors->first('product_sn') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="small mb-1" for="pro">Product S/N</label>--}}
{{--                                                <input class="form-control py-2 pro sn_id" id="pro" name="product_sn" type="text" placeholder="Enter product s/n here" />--}}
{{--                                                <span class="small text-danger pro_msg">{{ $errors->first('product_sn') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type">Budget Type</label>
                                                <select class="custom-select" id="type" name="type_id">
                                                    <option value=0>Select Budget type here</option>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="year">Year</label>
                                                <select class="custom-select" id="year" name="year_id">
                                                    <option value=0>Select Year here</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="make">Make</label>
                                                <select class="custom-select make" id="make" name="make_id">
                                                    <option value=0>Select Make here</option>
                                                    @foreach ($makes as $make)
                                                        <option value="{{ $make->id }}">{{ $make->make_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('make_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="model">Model</label>
                                                <select class="custom-select model" id="model" name="model_id">
                                                    <option value=0>Select Model here</option>

                                                </select>
                                                <span class="small text-danger">{{ $errors->first('model_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="vendor">Vendor</label>
                                                <select class="custom-select" id="vendor" name="vendor_id">
                                                    <option value=0>Select Vendor here</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type">Current Condition</label>
                                                <select class="custom-select" id="type" name="devicetype_id" disabled>
                                                    <option value=0>Select Current Condition here</option>
                                                    @foreach ($devicetypes as $devicetype)
                                                        <option value="{{ $devicetype->id }}">{{ $devicetype->devicetype_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('devicetype_id') }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="nature">Item Nature</label>
                                                <select class="custom-select" id="nature" name="itemnature_id">
                                                    <option value=0>Select Item Nature here</option>
                                                    @foreach ($itemnatures as $itemnature)
                                                        <option value="{{ $itemnature->id }}">{{ $itemnature->itemnature_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('itemnature_id') }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="p_date">Purchase Date</label>
                                                <input class="form-control py-2 purchase_date calculatewarrantyend" id="p_date" max="{{ date('Y-m-d')}}" name="purchase_date" type="date" placeholder="Enter purchase date here" />
                                                <span class="small text-danger">{{ $errors->first('purchase_date') }}</span>
                                            </div>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="small mb-1" for="remarks">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="8" placeholder="Enter Remarks here"></textarea>
                                                <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="price">Item Price</label>
                                                <input class="form-control py-2 t_seperator item_price" id="price" name="item_price" type="text" placeholder="Enter Item Price here" />
                                                <span class="small text-danger">{{ $errors->first('item_price') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="tax">TAX(%)</label>
                                                <input class="form-control py-2" id="tax" name="tax" type="number" placeholder="Enter Tax(%) here" />
                                                <span class="small text-danger">{{ $errors->first('tax') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="item_price_tax">Item Price After TAX(%)</label>
                                                <input class="form-control py-2" id="item_price_tax" name="item_price_tax" type="text"
                                                       placeholder="Item Price After Tax%" readonly/>
                                                <span class="small text-danger">{{ $errors->first('item_price_tax') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="rate">Dollar Rate</label>
                                                <input class="form-control py-2 t_seperator" id="rate" name="dollar_rate" type="text" placeholder="Enter dollar rate here" />
                                                <span class="small text-danger">{{ $errors->first('dollar_rate') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="ccost">Current Cost</label>
                                                <input class="form-control py-2" id="ccost" name="current_cost" type="text" placeholder="Enter Current Cost here" />
                                                <span class="small text-danger">{{ $errors->first('current_cost') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="po">PO Number</label>
                                                <input class="form-control py-2" id="po" name="po_number" type="text" placeholder="Enter PO Number here" />
                                                <span class="small text-danger">{{ $errors->first('po_number') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="Warrenty">Warrenty Period(Months)</label>
                                                <input class="form-control py-2 Warrenty calculatewarrantyend" id="Warrenty" name="warrenty_period" type="number" placeholder="Enter Warrenty Period here" />
                                                <span class="small text-danger">{{ $errors->first('warrenty_period') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="current_location">Current Location</label>
                                                <input class="form-control py-2" id="current_location" name="current_location" type="text" placeholder="Enter Current Location here" />
                                                <span class="small text-danger">{{ $errors->first('current_location') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="current_consumer">Current Consumer</label>
                                                <input class="form-control py-2" id="current_consumer" name="current_consumer" type="text" placeholder="Enter Current Consumer here" />
                                                <span class="small text-danger">{{ $errors->first('current_consumer') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="warrentyend">Warranty End</label>
                                                <input class="form-control py-2 warrentyend" id="warrentyend" name="warranty_end" type="text" placeholder="Enter Warranty End here" readonly />
                                                <span class="small text-danger">{{ $errors->first('warranty_end') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="licence">Licence Key</label>
                                                <input class="form-control py-2" id="licence" name="licence_key" type="text" placeholder="Enter Licence Key here" />
                                                <span class="small text-danger">{{ $errors->first('licence_key') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="SLA">SLA</label>
                                                <input class="form-control py-2" id="SLA" name="sla" type="text" placeholder="Enter SLA here" />
                                                <span class="small text-danger">{{ $errors->first('sla') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="warrentycheck">Warrenty Check</label>
                                                <input class="form-control py-2" id="warrentycheck" name="warrenty_check" type="text" placeholder="Enter Warrenty Check here" />
                                                <span class="small text-danger">{{ $errors->first('warrenty_check') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="operating_system">Operating System</label>
                                                <input class="form-control py-2" id="operating_system" name="operating_system" type="text" placeholder="Enter Operating System here" />
                                                <span class="small text-danger">{{ $errors->first('operating_system') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="SAP_tag">SAP Tag</label>
                                                <input class="form-control py-2" id="SAP_tag" name="SAP_tag" type="text" placeholder="Enter SAP Tag here" />
                                                <span class="small text-danger">{{ $errors->first('SAP_tag') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="Capacity">Capacity</label>
                                                <input class="form-control py-2" id="Capacity" name="capacity" type="text" placeholder="Enter Capacity here" />
                                                <span class="small text-danger">{{ $errors->first('capacity') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="hard_drive">Hard Drive</label>
                                                <input class="form-control py-2" id="hard_drive" name="hard_drive" type="text" placeholder="Enter Hard Drive here" />
                                                <span class="small text-danger">{{ $errors->first('hard_drive') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="Processor">Processor</label>
                                                <input class="form-control py-2" id="Processor" name="processor" type="text" placeholder="Enter Processor here" />
                                                <span class="small text-danger">{{ $errors->first('processor') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="process_generation">Process Generation</label>
                                                <input class="form-control py-2" id="process_generation" name="process_generation" type="text" placeholder="Enter Process Generation here" />
                                                <span class="small text-danger">{{ $errors->first('process_generation') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="display_type">Display Type</label>
                                                <input class="form-control py-2" id="display_type" name="display_type" type="text" placeholder="Enter Display Type here" />
                                                <span class="small text-danger">{{ $errors->first('display_type') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="DVD_rom">DVD Rom</label>
                                                <input class="form-control py-2" id="DVD_rom" name="DVD_rom" type="text" placeholder="Enter DVD Rom here" />
                                                <span class="small text-danger">{{ $errors->first('DVD_rom') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="RAM">RAM</label>
                                                <input class="form-control py-2" id="RAM" name="RAM" type="text" placeholder="Enter RAM here" />
                                                <span class="small text-danger">{{ $errors->first('RAM') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="invoice">Invoice Number</label>
                                                <input class="form-control py-2" id="invoice" name="invoice_number" type="text" placeholder="Enter Invoice Number here" />
                                                <span class="small text-danger">{{ $errors->first('invoice_number') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="invoice_date">Invoice Date</label>
                                                <input class="form-control py-2" max="{{ date('Y-m-d')}}" id="invoice_date" name="invoice_date" type="date" placeholder="Enter Invoice Date here" />
                                                <span class="small text-danger">{{ $errors->first('invoice_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="Insurance">Insurance</label>
                                                <input class="form-control py-2" id="Insurance" name="insurance" type="text" placeholder="Enter Insurance here" />
                                                <span class="small text-danger">{{ $errors->first('insurance') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="challan">Delivery Challan</label>
                                                <input class="form-control py-2" id="challan" name="delivery_challan" type="text" placeholder="Enter Delivery Challan here" />
                                                <span class="small text-danger">{{ $errors->first('delivery_challan') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="challan_date">Delivery Challan Date</label>
                                                <input class="form-control py-2" max="{{ date('Y-m-d')}}" id="challan_date" name="delivery_challan_date" type="date" placeholder="Enter Delivery Challan Date here" />
                                                <span class="small text-danger">{{ $errors->first('delivery_challan_date') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-row mt-5">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="other">Other Accessories</label>
                                                <textarea class="form-control" id="other" name="other_accessories" rows="3" placeholder="Enter Other Accessories here"></textarea>
                                                <span class="small text-danger">{{ $errors->first('other_accessories') }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="good_condition">Good Condition</label>
                                                <select class="custom-select" id="good_condition" name="good_condition">
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('good_condition') }}</span>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="verification" name="verification">
                                                <label class="form-check-label" for="verification">Verification</label>
                                                <span class="small text-danger">{{ $errors->first('verification') }}</span>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1">
                                        </div> -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="purpose">Purpose</label>
                                                <textarea class="form-control" id="purpose" name="purpose" rows="3" placeholder="Enter Purpose here"></textarea>
                                                <span class="small text-danger">{{ $errors->first('purpose') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" name="add_inventory" value="Add with GRN" class="btn btn-primary btn-block">
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
        var newvalue = '';
        $(document).ready(function () {
            console.log("check="+"imirfan");
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

            $(".make").on("change", function () {
                var id = $(this).val();
                $.get("{{ url('model_by_make') }}/" + id, function (data) {

                    var model = $('.model');
                    model.empty();
                    model.append('<option value=0 class="o1">Select Model here</option>');
                    $.each(data, function (index, value) {
                        model.append(
                            $('<option></option>').val(value.id).html(value.model_name)
                        );
                    });
                });
            });

            $('.sn_id_area').keydown(function (e) {
                var code = e.keyCode || e.which;
                if (code === 9 || code === 13) {
                    e.preventDefault();
                    const sn_no = + new Date();
                    // var value = $('.sn_id_area').val('SN-'+sn_no);
                    newvalue= newvalue.concat('SN-'+sn_no,",");
                    $('.sn_id_area').val(newvalue);
                }
            });

            $('#tax').keyup(function (e) {
                e.preventDefault();
                // var item_price_after_tax = 0;
                var price = $('.item_price').val();
                var itemprice = price.replace(/,/g, "");
                var tax = $(this).val();

                var item_price_after_tax = parseInt(itemprice * (tax / 100)) + parseInt(itemprice)
                $('#item_price_tax').val(item_price_after_tax.toString().replace(/,/g, ''));
            });
            $('#tax').change(function (e) {
                e.preventDefault();
                // var item_price_after_tax = 0;
                var price = $('.item_price').val();
                var itemprice = price.replace(/,/g, "");
                var tax = $(this).val();

                var item_price_after_tax = parseInt(itemprice * (tax / 100)) + parseInt(itemprice)
                $('#item_price_tax').val(item_price_after_tax.toString().replace(/,/g, ''));
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
