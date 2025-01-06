@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Edit SLA Complain Log</h1>
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
                                <form method="POST" action="{{ url('slalog/'.$sla_log->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="type_id">Type</label>
                                                <select class="custom-select type_id" id="type_id" name="type_id">
                                                    <option value=0>Select Type here</option>
                                                    @foreach ($data['types'] as $type)
                                                        @if($type->id == $sla_log->type_id)
                                                        <option value="{{ $type->id }}" {{$type->id == $sla_log->type_id ? 'selected' : ''}} >{{ $type->type }}</option>
                                                        @else
                                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="year_id">Year</label>
                                                <select class="custom-select year_id" id="year_id" name="year_id">
                                                    @foreach($data['years'] as $year)
                                                        @if($year->id == $sla_log->year_id)
                                                            <option value="{{ $year->id }}" selected>{{ $year->year}}</option>
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
                                                    <option value=0>Select Sub Category here</option>
                                                    @foreach($data['subcategories'] as $sub_cat)
                                                        @if($sub_cat->id == $sla_log->subcategory_id)
                                                             <option value="{{$sub_cat->id}}" selected>{{$sub_cat->sub_cat_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="vendor_id">Vendor</label>
                                                <select class="custom-select vendorid" id="vendors_id" name="vendor_id">
{{--                                                    <option value=0>Select Vendor here</option>--}}
                                                    @foreach ($vendors as $vendor)
                                                        @if($vendor->id == $sla_log->vendor_id)
                                                        <option value="{{ $vendor->id }}" selected {{$vendor->id == $sla_log->vendor_id ? 'selected' : ''}}>{{ $vendor->vendor_name }}</option>
                                                        @else
                                                            <option></option>
                                                        @endif()
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="issue_product_sn">Product Serial Number</label>
                                                <select class="custom-select issue_product_sn" id="issue_product_sn" name="issue_product_sn">
                                                    <option value=0>Select Product Serial Number here</option>
                                                    @foreach ($data['issue_product_sn'] as $product_sn)
                                                        <option
                                                                value="{{ $product_sn->id }}" {{$product_sn->product_sn == $sla_log->issue_product_sn ? 'selected' : ''}}>{{ $product_sn->product_sn }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('issue_product_sn') }}</span>
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
                                                        <option value="{{$type->id}}" {{$type->id == $sla_log->sla_type ? 'selected' : ''}}>{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="make_id">Make</label>
                                                @foreach($make as $mymake)
                                                    @if($mymake->id == $sla_log->issue_make_id)
                                                         <input class="form-control py-2" id="make_id" name="make_id" type="text" value="{{$mymake->make_name ?? ''}}" placeholder="Make" readonly/>
                                                    @endif
                                                @endforeach
                                                <span class="small text-danger">{{ $errors->first('make_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="model_id">Model</label>
                                                @foreach($modal as $model)
                                                    @if($model->id == $sla_log->issue_model_id)
                                                    <input class="form-control py-2" id="model_id" name="model_id" value="{{$model->model_name ?? ''}}" type="text" placeholder="Model" readonly/>
                                                    @endif
                                                @endforeach
                                                    <span class="small text-danger">{{ $errors->first('model_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="issued_to">Issued To</label>
                                                @foreach($employee as $emp)
                                                    @if($emp->emp_code == $sla_log->issued_to)
                                                <input class="form-control py-2" id="issued_to" value="{{$emp->name.' / '.$emp->department ?? ''}}" name="issued_to" type="text" placeholder="Issued To" readonly/>
                                                @endif
                                                @endforeach
                                                        <span class="small text-danger">{{ $errors->first('issued_to') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="issue_description">Problem / Issue</label>
                                                <textarea class="form-control" id="issue_description" name="issue_description" rows="5" placeholder="Enter Problem/Issue here">{{$sla_log->issue_description}}</textarea>
                                                <span class="small text-danger">{{ $errors->first('issue_description') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="issue_occur_date">Issue Occur on Date</label>
                                                <input class="form-control py-2 issue_occur_date"
                                                       id="issue_occur_date" name="issue_occur_date"  value="{{date('Y-m-d\TH:i', strtotime($sla_log->issue_occur_date))}}" type="datetime-local"
                                                />
                                                <span
                                                        class="small text-danger">{{ $errors->first('issue_occur_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="visit_date_time">Engineer Visit Date Time</label>
                                                <input class="form-control py-2 visit_date_time"
                                                       id="visit_date_time" value="{{ date('Y-m-d\TH:i', strtotime($sla_log->visit_date_time)) }}" name="visit_date_time" type="datetime-local"
                                                />
                                                <span
                                                        class="small text-danger">{{ $errors->first('visit_date_time') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="engineer_detail">Engineer Details</label>
                                                <textarea class="form-control engineer_detail" id="engineer_detail" name="engineer_detail" rows="5" placeholder="Enter Engineer Details here">{{ $sla_log->engineer_detail }}</textarea>
                                                <span class="small text-danger">{{ $errors->first('engineer_detail') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="handed_over_date">Handed Over Date</label>
                                                <input class="form-control py-2 handed_over_date"
                                                       id="handed_over_date" value="{{ $sla_log->handed_over_date }}" name="handed_over_date" type="date"
                                                />
                                                <span
                                                        class="small text-danger">{{ $errors->first('handed_over_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="replace_type">Product Serial Number</label>
                                            <select class="custom-select replace_type" id="replace_type" name="replace_type">
                                                <option value=0>Select Replace Type here</option>
                                                <option value="1" {{$sla_log->replace_type == 1 ? 'selected' : ''}}>Replace</option>
                                                <option value="2" {{$sla_log->replace_type == 2 ? 'selected' : ''}}>Repair</option>
                                                <option value="3" {{$sla_log->replace_type == 3 ? 'selected' : ''}}>Non Repairable</option>
                                            </select>
                                            <span class="small text-danger">{{ $errors->first('replace_type') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="replace_product_sn">Product Serial Number</label>
                                                <select class="custom-select replace_product_sn" id="replace_product_sn" name="replace_product_sn">
                                                    <option value=0>Select Product Serial Number here</option>
                                                    @foreach ($data['issue_product_sn'] as $product_sn)
                                                        <option
                                                                value="{{ $product_sn->id }}" {{$product_sn->product_sn == $sla_log->issue_product_sn ? 'selected' : ''}}>{{ $product_sn->product_sn }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('replace_product_sn') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="replace_product_make_id">Make</label>
                                                @foreach($make as $mymake)
                                                    @if($mymake->id == $sla_log->replace_product_make_id)
                                                        <input class="form-control py-2"  value="{{$mymake->make_name ?? ''}}" id="replace_product_make_id" name="replace_product_make_id" type="text" placeholder="Make" readonly/>
                                                    @endif
                                                @endforeach
                                                <span class="small text-danger">{{ $errors->first('replace_product_make_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="small mb-1" for="	replace_product_model_id">Model</label>
                                                @foreach($modal as $model)
                                                    @if($model->id == $sla_log->replace_product_model_id)
                                                <input class="form-control py-2" value="{{$model->model_name ?? ''}}" id="replace_product_model_id" name="replace_product_model_id" type="text" placeholder="Model" readonly/>
                                                    @endif
                                                @endforeach
                                                        <span class="small text-danger">{{ $errors->first('	replace_product_model_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="issue_resolve_date">Issue Resolve Date</label>
                                                <input class="form-control py-2 issue_resolve_date"
                                                       id="issue_resolve_date" value="{{date('Y-m-d\TH:i', strtotime($sla_log->issue_resolve_date))}}" name="issue_resolve_date" type="datetime-local"/>
                                                <span class="small text-danger">{{ $errors->first('issue_resolve_date') }}</span>
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
