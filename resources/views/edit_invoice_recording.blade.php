@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Edit Invoice Recording</h1>
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
                                <form method="POST" action="{{ url('invoice/'.$invoice->id) }}">
                                    @method('PUT')
                                    @csrf
                                    <input type='hidden' name='status' value='1'>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type_id">Type</label>
                                                <select class="custom-select type_id" id="type_id" name="type_id">
                                                    <option value=0>Select Type here</option>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}" {{$type->id == $invoice->type_id ? 'selected' : ''}}>{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="year_id">Year</label>
                                                <select class="custom-select invoice_year_id" id="year_id" name="year_id">
                                                    <option value=0>Select Year here</option>
                                                    @foreach($years as $year)
                                                        @if($year->id == $invoice->year_id)
                                                            <option value={{$year->id}} selected>{{$year->year}}</option>
                                                        @endif()
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Category</label>
                                                <select class="custom-select invoice_subcategory1" id="category_id" name="category_id">
                                                    <option selected>Select Category</option>
                                                    @foreach($categories as $cat)
                                                        @if($cat->id == $invoice->category_id)
                                                            <option value={{$cat->id}} selected>{{$cat->category_name}}</option>
                                                        @endif()
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub-Category</label>
                                                <select class="custom-select subcategory1" id="subcategory_id" name="subcategory_id">
                                                    <option selected>Select Sub Category</option>
                                                    @foreach($subcategories as $sub)
                                                        @if($sub->id == $invoice->subcategory_id)
                                                            <option value={{$sub->id}} selected>{{$sub->sub_cat_name}}</option>
                                                        @endif()
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>

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
                                                                value="{{ $vendor->id }}" {{$vendor->id == $invoice->vendor_id ? 'selected' : ''}}>{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="make">Make</label>
                                                <select class="custom-select make" id="make" name="make_id">
                                                    <option value=0>Select Make here</option>
                                                    @foreach ($makes as $make)
                                                        <option value="{{ $make->id }}" {{$make->id == $invoice->make_id ? 'selected' : ''}}>{{ $make->make_name }}</option>
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
                                                    <option value="{{$models->id}}" {{$models->id == $invoice->model_id ? 'selected' : ''}}>{{$models->model_name}}</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('model_id') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        {{--                                        <div class="col-md-4">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <label class="small mb-1" for="type">Current Condition</label>--}}
                                        {{--                                                <select class="custom-select" id="type" name="devicetype_id">--}}
                                        {{--                                                    <option value=0>Select Current Condition here</option>--}}
                                        {{--                                                    @foreach ($devicetypes as $devicetype)--}}
                                        {{--                                                        <option--}}
                                        {{--                                                            value="{{ $devicetype->id }}">{{ $devicetype->devicetype_name }}</option>--}}
                                        {{--                                                    @endforeach--}}
                                        {{--                                                </select>--}}
                                        {{--                                                <span--}}
                                        {{--                                                    class="small text-danger">{{ $errors->first('devicetype_id') }}</span>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        {{--                                        <div class="col-md-4">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <label class="small mb-1" for="itemnature_id">Item Nature</label>--}}
                                        {{--                                                <input type="hidden" class="form-control py-2" disabled value="Recurring" id="itemnature_id" name="itemnature_id" type="text"--}}
                                        {{--                                                       placeholder="Recurring"/>--}}
                                        {{--                                                <span--}}
                                        {{--                                                    class="small text-danger">{{ $errors->first('itemnature_id') }}</span>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="po">PO Number</label>
                                                <input class="form-control py-2" id="po" name="po_number" value="{{$invoice->po_number}}" type="text"
                                                       placeholder="Enter PO Number here"/>
                                                <span class="small text-danger">{{ $errors->first('po_number') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="p_date">Purchase Date</label>
                                                <input class="form-control py-2 purchase_date calculatewarrantyend"
                                                       id="p_date" name="purchase_date" value="{{$invoice->purchase_date}}" type="date"
                                                       placeholder="Enter purchase date here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('purchase_date') }}</span>
                                            </div>
                                        </div>
                                        {{--                                            <div class="col-md-8">--}}
                                        {{--                                                    <div class="form-group">--}}
                                        {{--                                                        <label class="small mb-1" for="remarks">Remarks</label>--}}
                                        {{--                                                        <textarea class="form-control" id="remarks" name="remarks" rows="8" placeholder="Enter Remarks here"></textarea>--}}
                                        {{--                                                        <span class="small text-danger">{{ $errors->first('remarks') }}</span>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                            </div>--}}
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="price">Item Price</label>
                                                <input class="form-control py-2 t_seperator item_price" id="price"
                                                       name="item_price" type="text" value="{{$invoice->item_price}}"
                                                       placeholder="Enter Item Price here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('item_price') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="tax">TAX(%)</label>
                                                <input class="form-control py-2" id="tax" name="tax" value="{{$invoice->tax}}" type="number"
                                                       placeholder="Enter Tax(%) here"/>
                                                <span class="small text-danger">{{ $errors->first('tax') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="item_price_tax">Item Price After TAX(%)</label>
                                                <input class="form-control py-2" id="item_price_tax" value="{{$invoice->item_price_tax}}" name="item_price_tax" type="text"
                                                       placeholder="Item Price After Tax%" readonly/>
                                                <span class="small text-danger">{{ $errors->first('item_price_tax') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="small mb-1" for="rate">Dollar Rate</label>
                                                <input class="form-control py-2 t_seperator" id="rate"
                                                       name="dollar_rate" type="text" value="{{$invoice->dollar_rate}}"
                                                       placeholder="Enter dollar rate here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('dollar_rate') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="issue_date">Contract Issue Date</label>
                                                <input class="form-control py-2 purchase_date"
                                                       id="contract_issue_date" value="{{$invoice->contract_issue_date}}" name="contract_issue_date" type="date"
                                                       placeholder="Enter Contract Issue date here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('contract_issue_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="end_date">Contract End Date</label>
                                                <input class="form-control py-2 purchase_date"
                                                       id="contract_end_date" name="contract_end_date" value="{{$invoice->contract_end_date}}"  type="date"
                                                       placeholder="Enter Contract End date here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('contract_end_date') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="invoice_number">Invoice Number</label>
                                                <input class="form-control py-2" id="invoice_number" value="{{$invoice->invoice_number}}" name="invoice_number"
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
                                                       name="invoice_date" type="date" value="{{$invoice->invoice_date}}"
                                                       placeholder="Enter Invoice Date here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('invoice_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="Warrenty">Warrenty Period(Months)</label>
                                                <input class="form-control py-2 Warrenty calculatewarrantyend"
                                                       id="Warrenty" name="warrenty_period" type="number" value="{{$invoice->warrenty_period}}"
                                                       placeholder="Enter Warrenty Period here"/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('warrenty_period') }}</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="warrentyend">Warranty End</label>
                                                <input class="form-control py-2 warrentyend" id="warrentyend"
                                                       name="warranty_end" type="text" value="{{$invoice->warranty_end}}"
                                                       placeholder="Enter Warranty End here" readonly/>
                                                <span
                                                        class="small text-danger">{{ $errors->first('warranty_end') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="small mb-1" for="remarks">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="8" placeholder="Enter Remarks here">{{$invoice->remarks}}</textarea>
                                                <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" id="btn_add_inventory_invoice" name="add_inventory" value="Add Invoice Inventory"
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
