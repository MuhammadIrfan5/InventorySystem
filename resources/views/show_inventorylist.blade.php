@extends("master")

@section("content")
    <style>
        .field_size {
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
                                Inventories
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form method="GET" action="{{ url('show_inventory_list') }}">
                                        @csrf
                                        <tr>
                                            <td>
                                                Item Category
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="category_id">
                                                    <option value="">All</option>
                                                    @foreach ($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('category_id') }}</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Location
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="location_id">
                                                    <option value="">All</option>
                                                    @foreach ($locations as $location)
                                                        <option
                                                            value="{{ $location->id }}">{{ $location->location }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('location_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Purchase From Date
                                            </td>
                                            <td>
                                                <input class="form-control field_size" name="from_date" type="date"
                                                       placeholder="Enter date here"/>
                                                <span class="small text-danger">{{ $errors->first('from_date') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Purchase To Date
                                            </td>
                                            <td>
                                                <input class="form-control field_size" name="to_date" type="date"
                                                       placeholder="Enter date here"/>
                                                <span class="small text-danger">{{ $errors->first('to_date') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Budget Type
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="type_id">
                                                    <option value="">All</option>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('location_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Budget Year
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="year_id">
                                                    <option value="">All</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('location_id') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right">
                                                <button type="submit" class="btn btn-primary">Show</button>
                                            </td>
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
                        @if(empty($inventories))
                        @else
                            @if(request()->user()['id']==81)
                                <a class="btn btn-sm btn-danger ml-1 mb-2 float-right"
                                   href="{{ url('inventoryexport/'.json_encode($filters)) }}">Print <i
                                        class="fa fa-download" aria-hidden="true"></i></a>
                                <a class="btn btn-sm btn-danger mb-2 float-right"
                                   href="{{ url('export_inventory/'.json_encode($filters)) }}">CSV <i
                                        class="fa fa-download" aria-hidden="true"></i></a>
                            @endif
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Type</th>
                                    <th>Year</th>
                                    <th>Product S#</th>
                                    <th>Purchase Date</th>
                                    <th>Sub Category</th>
                                    <th>Price</th>
                                    <th>Issued to</th>
                                    <th>Location</th>
                                    <th>Initial Status</th>
                                    <th>Current Condition</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($inventories as $inventory)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
                                        <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
                                        <td>{{ $inventory->type_id?$inventory->type->type:'' }}</td>
                                        <td>{{ $inventory->year_id?$inventory->year->year:'' }}</td>
                                        <td>
                                            <a href="{{ url('item_detail/'.$inventory->id) }}">{{ $inventory->product_sn }}</a>
                                        </td>
                                        <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>
                                        <td>{{ empty($inventory->subcategory)?'':$inventory->subcategory->sub_cat_name }}</td>
                                        <td>{{ number_format(round($inventory->item_price),2) }}</td>
                                        <td>{{ empty($inventory->user)?'':$inventory->user->name }}</td>
                                        <td>{{ empty($inventory->location)?'':$inventory->location->location }}</td>
                                        <td>{{ empty($inventory->inventorytype)?'':$inventory->inventorytype->inventorytype_name }}</td>
                                        <td>{{ empty($inventory->devicetype)?'':$inventory->devicetype->devicetype_name }}</td>
                                        <td>{{ $inventory->remarks }}</td>
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
