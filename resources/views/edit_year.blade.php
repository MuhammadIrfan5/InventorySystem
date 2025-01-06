@extends("master")

@section("content")
<div id="layoutSidenav_content">
                <main>
                    <div class="container">
                    <div class="row mt-4">
                        <div class="col-md-10 col-lg-10">
                            <h1 class="">Edit Year</h1>
                        </div>
                        <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('years') }}" class="btn btn-success">View List</a>
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
                                        <form  method="POST" action="{{ url('years/'.$year->id) }}">
                                        @csrf
                                        @method('PUT')
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">Year</label>
                                                        <input class="form-control" id="inputFirstName" type="text" name="year" value="{{ $year->year }}" placeholder="Enter year here" Required="required" />
                                                        <span class="small text-danger">{{ $errors->first('year') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="year_start_date">Start Date</label>
                                                        <input class="form-control py-2 year_start_date"
                                                               id="year_start_date" value="{{$year->year_start_date}}" name="year_start_date" type="date"
                                                               placeholder="Enter start date here"/>
                                                        <span
                                                            class="small text-danger">{{ $errors->first('year_start_date') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="year_end_date">End Date</label>
                                                        <input class="form-control py-2 year_end_date" id="p_date"
                                                               name="year_end_date" value="{{$year->year_end_date}}" type="date"
                                                               placeholder="Enter end date here"/>
                                                        <span
                                                            class="small text-danger">{{ $errors->first('year_end_date') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" {{ $year->is_current_year == 1 ? 'checked' : ''}} value="1" name="is_current_year" class="custom-control-input" id="customSwitch3">
                                                            <label class="custom-control-label" for="customSwitch3">Is Current Year</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" {{ $year->locked == 1 ? 'checked' : ''}} value="1" name="lock_budget" class="custom-control-input" id="customSwitch4">
                                                            <label class="custom-control-label" for="customSwitch4">Lock Budget</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" {{ $year->inventory_allowed == 1 ? 'checked' : ''}} value="1" name="inventory_allowed" class="custom-control-input" id="customSwitch5">
                                                            <label class="custom-control-label" for="customSwitch5">Inventory Allowed</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" {{ $year->is_budget_collection == 1 ? 'checked' : ''}}  value="1" name="is_budget_collection" class="custom-control-input" id="customSwitch6">
                                                            <label class="custom-control-label" for="customSwitch6">Budget Collection</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                            <input type="submit" name="edit_year" value="Edit Year" class="btn btn-primary btn-block">
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
