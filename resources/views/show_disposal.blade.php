@extends("master")

@section("content")
<style>
.field_size{
    height: 30px;
    padding: 0px 10px;
}
</style>
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                    <div class="row">
                    <div class="col-md-3 col-lg-3">
                    </div>
                    <div class="col-md-6 col-lg-6">

                            <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                            Asset Disposal
                            </div>
                                <div class="card-body">
                                <table class="table table-borderless">
                                        <tbody>
                                        <form method="GET" action="{{ url('disposal') }}">
                                            @csrf

                                            <tr>
                                                <td>
                                                    From Date
                                                </td>
                                                <td>
                                                    <input class="form-control field_size" name="from_date" type="date" placeholder="Enter date here" />
                                                    <span class="small text-danger">{{ $errors->first('from_date') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    To Date
                                                </td>
                                                <td>
                                                    <input class="form-control field_size" name="to_date" type="date" placeholder="Enter date here" />
                                                    <span class="small text-danger">{{ $errors->first('to_date') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Handover / Not Handover
                                                </td>
                                                <td>
                                                <select class="custom-select" id="handover" name="handover">
                                                    <option value="">All</option>
                                                    <option value="1">Handover</option>
                                                    <option value="2">Not Handover</option>
                                                </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-right"><button type="submit" class="btn btn-primary">Show</button></td>
                                            </tr>
                                        </form>
                                        </tbody>
                                </table>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">

                    </div>
                    </div>
                        <div class="card mb-4 mt-5">
                            <div class="card-body">
                            @if(empty($disposals))
                            @else
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right" href="{{ url('disposalexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_disposal/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                            @endif

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Sub Category</th>
                                                <th>Make / Model</th>
                                                <th>Product S#</th>
                                                <th>Disposal Status</th>
                                                <th>Purchase Date</th>
                                                <th>Disposal Date</th>
                                                <th>Handed Over Date</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($disposals as $disposal)
                                            <tr>
                                                <td class='text-align-right'>{{ $i++ }}</td>
                                                <td>{{ !empty($disposal->subcategory)?$disposal->subcategory->sub_cat_name:'' }}</td>
                                                <td>{{ !empty($disposal->inventory->make)?$disposal->inventory->make->make_name.' / '.$disposal->inventory->model->model_name:'' }}</td>
                                                <td><a href="{{ url('item_detail/'.$disposal->inventory_id) }}">{{ !empty($disposal->inventory)?$disposal->inventory->product_sn:'' }}</a></td>
                                                <td>{{ !empty($disposal->disposalstatus)?$disposal->disposalstatus->d_status:'' }}</td>
                                                <td>{{ !empty($disposal->inventory)?date('d-M-Y', strtotime($disposal->inventory->purchase_date)):'' }}</td>
                                                <td>{{ date('d-M-Y', strtotime($disposal->dispose_date)) }}</td>
                                                <td>{{ $disposal->handover_date == null?'Null':date('d-M-Y' ,strtotime($disposal->handover_date)) }}</td>
                                                <td>{{ $disposal->remarks }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
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
