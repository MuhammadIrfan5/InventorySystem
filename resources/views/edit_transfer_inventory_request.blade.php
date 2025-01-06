@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Edit Transfer Inventory Request</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('list_transfer_inventory_request') }}" class="btn btn-success">View List</a>
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
                                <form  method="POST" action="{{ url('update_transfer_inventory_request') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$request->id}}">
                                    <div class="form-group">
                                        <label class="small mb-1" for="role">Change Status</label>
                                        <select class="custom-select" id="role" name="status">
                                            <option value='#' disabled>Select Status here</option>
                                            <option value='transferred' {{$request->status == 'transferred'  ? 'selected' : ''}}>Transferred</option>
                                            <option value='pending' {{$request->status == 'pending'  ? 'selected' : ''}}>Pending</option>
                                            <option value='rejected' {{$request->status == 'rejected'  ? 'selected' : ''}}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="4"
                                                      placeholder="Enter Remarks here"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" name="update_user" value="Status update" class="btn btn-primary btn-block">
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
