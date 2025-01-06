@extends("master")

@section("content")

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                @if (session('msg'))
                    <div class="alert alert-success mt-4">
                        {{ session('msg') }}
                    </div>
                @elseif(session('error-msg'))
                    <div class="alert alert-danger mt-4">
                        {{ session('error-msg') }}
                    </div>
                @endif
                <form method="POST" action="{{ url('transfer_product_sn2')}}">
                    @csrf
                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            Inventory Carry Forward
                        </div>

                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="from_year_id"> From Year</label>
                                        <select class="custom-select" id="from_year_id" name="from_year_id" required>
                                            <option value=0>Select Year here</option>
                                            @foreach($years as $year)
                                                <option value={{$year->id}}>{{$year->year}}</option>
                                            @endforeach
                                        </select>

                                        <span class="small text-danger">{{ $errors->first('from_year_id') }}</span>
                                        <input type='hidden' id='dept' name='department' value=''>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="to_year_id"> To Year</label>
                                        <select class="custom-select" id="to_year_id" name="to_year_id" required>
                                            <option value=0>Select Year here</option>
                                            @foreach($years as $year)
                                                <option value={{$year->id}}>{{$year->year}}</option>
                                            @endforeach
                                        </select>
                                        <span class="small text-danger">{{ $errors->first('to_year_id') }}</span>
                                        <input type='hidden' id='dept' name='department' value=''>
                                    </div>
                                </div>

                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12 col-lg-12">
                                    {{--                                    type="submit" name="swap"--}}
                                    <button type="submit" name="transfer_product"
                                            class="btn btn-success btn_swap float-right">Carry Forward
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
