@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Dispatch IN Form</h1>
                        </div>
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('dispatchin') }}" class="btn btn-success">View List</a>
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
                                        <form  method="POST" action="{{ url('dispatchin/'.$dispatch->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="category">Category</label>
                                                <select class="custom-select category" id="category" name="category_id">
                                                    <option value=0>Select Category here</option>
                                                    @foreach ($categories as $category)
                                                    @if($dispatch->category_id == $category->id)
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
                                                <select class="custom-select subcategory dinout_subcategory" id="subcategory" name="subcategory_id" data-action="in">
                                                <option value="{{ $dispatch->subcategory->id }}">{{ $dispatch->subcategory->sub_cat_name }}</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                            </div>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                                <div class="col-md-4">
                                                <label class="small mb-1" for="item">Item List</label>
                                                <select class="custom-select item_list" id="item" name="inventory_id">
                                                <option value="{{ $dispatch->inventory->id }}">{{ $dispatch->inventory->product_sn }}</option>

                                                </select>
                                                <span class="small text-danger">{{ $errors->first('inventory_id') }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="date">Dispatch IN Date</label>
                                                        <input class="form-control py-2" max="{{date('Y-m-d')}}" id="date" name="dispatchin_date" type="date" value="{{ $dispatch->dispatchin_date }}" placeholder="Enter dispatch in date here" />
                                                        <span class="small text-danger">{{ $errors->first('dispatchin_date') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label class="small mb-1" for="reason">Memo Attached</label>
                                                <select class="custom-select" id="reason" name="memo">
                                                    <option value="">Select here</option>
                                                    <option value="yes" {{ $dispatch->memo == 'yes'?"selected":"" }}>Yes</option>
                                                    <option value="no" {{ $dispatch->memo == 'no'?"selected":"" }}>No</option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('memo') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="department">Department</label>
                                                        <input class="form-control py-2 department" id="department" name="department" type="text" placeholder="Enter department here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('department') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="last_user">Assigned User</label>
                                                        <input class="form-control py-2 last_user" id="assigned_user" name="assigned_user" type="text" placeholder="Enter Assigned user here" readonly />
                                                        <span class="small text-danger">{{ $errors->first('assigned_user') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="remarks">Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Enter Remarks here">{{ $dispatch->remarks }}</textarea>
                                                        <span class="small text-danger">{{ $errors->first('remarks') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" value="Submit Dispatch IN" class="btn btn-primary btn-block">
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
