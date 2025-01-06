@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Assets Disposal/By Back Form</h1>
                        </div>
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('dispose') }}" class="btn btn-success">View List</a>
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
                                        <form  method="POST" action="{{ url('dispose/'.$disposal->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category" name="category_id">
                                                    @foreach ($categories as $category)
                                                    @if($disposal->category_id == $category->id)
                                                    <option value="{{ $category->id }}" selected>{{ $category->category_name }}</option>
                                                    @else
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="subcategory">Sub Category</label>
                                                <select class="custom-select subcategory d_subcategory" id="subcategory" name="subcategory_id">
                                                <option value={{ $disposal->subcategory->id }}>{{ $disposal->subcategory->sub_cat_name }}</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('subcategory_id') }}</span>
                                            </div>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                                <div class="col-md-4">
                                                <label class="small mb-1" for="item">Item List</label>
                                                <select class="custom-select item_list" id="item" name="inventory_id">

                                                    <option value="{{ $disposal->inventory->id }}">{{ $disposal->inventory->product_sn }}</option>

                                                </select>
                                                <span class="small text-danger">{{ $errors->first('inventory_id') }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="date">Dispose Date</label>
                                                        <input class="form-control py-2" max="{{date('Y-m-d')}}" id="date" name="dispose_date" type="date" value="{{ $disposal->dispose_date }}" placeholder="Enter dispose date here" />
                                                        <span class="small text-danger">{{ $errors->first('dispose_date') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="p_date">Purchase Date</label>
                                                        <input class="form-control py-2 p_date" max="{{date('Y-m-d')}}" id="p_date" name="purchase_date" type="text" value="{{ $disposal->inventory->purchase_date }}" placeholder="Enter purchase date here" readonly/>
                                                        <span class="small text-danger">{{ $errors->first('purchase_date') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="reason">Reason to Dispose</label>
                                                <select class="custom-select" id="reason" name="disposalstatus_id">
                                                    @foreach ($statuses as $status)
                                                    @if($disposal->disposalstatus_id == $status->id)
                                                    <option value="{{ $status->id }}" selected>{{ $status->d_status }}</option>
                                                    @else
                                                    <option value="{{ $status->id }}">{{ $status->d_status }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('disposalstatus_id') }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="department">Department</label>
                                                        <input class="form-control py-2 department" id="department" name="department" type="text" placeholder="Enter department here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('department') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="last_user">Last User</label>
                                                        <input class="form-control py-2 last_user" id="last_user" name="last_user" type="text" placeholder="Enter last_user here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('last_user') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="hdate">Handover s</label>
                                                        <input class="form-control py-2" max="{{date('Y-m-d')}}" id="hdate" name="handover_date" type="date" value="{{ $disposal->handover_date }}" placeholder="Enter handover date here" />
                                                        <span class="small text-danger">{{ $errors->first('handover_date') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="remarks">Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Enter Remarks here">{{ $disposal->remarks }}</textarea>
                                                        <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" value="Submit Disposal" class="btn btn-primary btn-block">
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
