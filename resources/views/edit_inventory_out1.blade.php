@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Edit Inventory out</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('inventory_out') }}" class="btn btn-success">View List</a>
                    </div>
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
{{--                @dd($inventory_out)--}}
                <div class="row">
                    <div class="col-md-1 col-lg-1"></div>
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <div class="card border-0 rounded-lg mt-3">
                            <div class="card-body">
                                <form method="POST" action="{{ url('update_inventory_out') }}">
                                    <input  type="hidden" name="id" value="{{ $inventory_out->id }}">
                                    @csrf
                                    {{--                                    <div class="form-row">--}}
                                    {{--                                        <div class="col-md-12">--}}
                                    {{--                                            <div class="form-group">--}}
                                    {{--                                                <label class="small mb-1" for="inputFirstName">Initial Status</label>--}}
                                    {{--                                                <input class="form-control py-4" id="inputFirstName" type="text" name="inventorytype_name" value="{{ $inventory_out->inventorytype_name }}" placeholder="Enter Initial Status here" Required="required" />--}}
                                    {{--                                                <span class="small text-danger">{{ $errors->first('inventorytype_name') }}</span>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="status">Status</label>
                                                <select class="custom-select" id="status" name="status">
                                                    <option
                                                        value="0" {{ $inventory_out->received_status == '0'? 'selected':''}}>
                                                        Pending
                                                    </option>
                                                    <option
                                                        value="2" {{ $inventory_out->received_status == '2'? 'selected':''}}>
                                                        Rejected
                                                    </option>
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('status') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" name="edit_inventorytype" value="Edit Initial Status"
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
