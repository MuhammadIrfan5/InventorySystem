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
                            Asset Repairing
                            </div>
                                <div class="card-body">
                                <table class="table table-borderless">
                                        <tbody>
                                        <form method="GET" action="{{ url('asset_repairing') }}">
                                            @csrf
                                            <tr>
                                                <td>
                                                    Category
                                                </td>
                                                <td>
                                                <select class="custom-select field_size category" id="category" name="category_id">
                                                    <option value="">All</option>
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Item Category
                                                </td>
                                                <td>
                                                <select class="custom-select field_size subcategory" name="subcategory_id" data-reports="1">
                                                    <option value="">All</option>
                                                    @foreach ($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}">{{ $subcategory->sub_cat_name }}</option>
                                                    @endforeach
                                                    </select>
                                                    <span class="small text-danger">{{ $errors->first('subcategory_id') }}</span>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    Product SN
                                                </td>
                                                <td>
                                                <select class="custom-select field_size repair_item" name="item_id">
                                                    <option value="">All</option>
                                                    </select>
                                                    <span class="small text-danger">{{ $errors->first('product_sn') }}</span>
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
                            @if(empty($repairs))
                            @else
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right" href="{{ url('repairingexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_assetrepairing/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                            @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Item Category</th>
                                                <th>Product SN</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Issued to</th>
                                                <th>Location</th>
                                                <th>Repairing Date</th>
                                                <th>Actual Price</th>
                                                <th>Repairing Cost</th>
                                                <th>Cumulative Cost</th>
                                                <th>Initial Status</th>
                                                <th>Current Condition</th>
                                                <th>Remarks</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($repairs as $repair)
                                        <?php
                                        $total = $repair->actual_price_value+$repair->price_value;
                                        ?>
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ empty($repair->subcategory)?'':$repair->subcategory->sub_cat_name }}</td>
                                                <td>{{ empty($repair->item)?'':$repair->item->product_sn }}</td>
                                                <td>{{ empty($repair->item->make)?'':$repair->item->make->make_name }}</td>
                                                <td>{{ empty($repair->item->model)?'':$repair->item->model->model_name }}</td>
                                                <td>{{ empty($repair->item->user)?'':$repair->item->user->name }}</td>
                                                <td>{{ empty($repair->item->location)?'':$repair->item->location->location }}</td>
                                                <td>{{ date('d-M-Y' ,strtotime($repair->date)) }}</td>
                                                <td class='text-align-right'>{{ number_format($repair->actual_price_value,2) }}</td>
                                                <td class='text-align-right'>{{ number_format($repair->price_value,2) }}</td>
                                                <td class='text-align-right'>{{ number_format($total,2) }}</td>
                                                <td>{{ empty($repair->item->inventorytype)?'':$repair->item->inventorytype->inventorytype_name }}</td>
                                                <td>{{ empty($repair->item->devicetype)?'':$repair->item->devicetype->devicetype_name }}</td>
                                                <td>{{ $repair->remarks }}</td>

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
@section('page-script')
    @include('layouts.customScript')
@endsection
