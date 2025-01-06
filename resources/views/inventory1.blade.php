@extends("master")
@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Inventories List</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('add_inventory') }}" class="btn btn-success">Add
                            <svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
                <div class="card mb-4 mt-5">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="empTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product S#</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Purchase Date</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Dollar Rate</th>
                                    <th>Issued</th>
                                    <th>Created at</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            // DataTable
            $('#empTable').DataTable({
                "processing": true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    // "processing": "Loading. Please wait..."
                },
                "serverSide": true,
                ajax: "{{route('inventory.Datatable')}}",
                columns: [
                    { data: "id"},
                    { data: "ProductS"},
                    { data: "Make"},
                    { data: "Model"},
                    { data: "PurchaseDate"},
                    { data: "Category"},
                    { data: "Price"},
                    { data: "DollarRate"},
                    { data: "Issued"},
                    { data: "CreatedAt"},
                    { data: "edit"},
                ],
            });
        });
    </script>
@endsection
