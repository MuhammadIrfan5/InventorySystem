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
                                SLA Report
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form method="GET" action="{{ url('sla_complain_report') }}">
                                        @csrf
                                        <tr>
                                            <td>
                                                Vendor
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" id="vendor" name="vendor_id">
                                                    <option value="">All</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('vendor_id') }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Sub Category
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
                                                Budget Type
                                            </td>
                                            <td>
                                                <select class="custom-select field_size" name="type_id">
                                                    <option value="">All</option>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('type_id') }}</span>
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
                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>
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
                        @if(empty($reorders))
                        @else
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right" href="{{ url('slacomplainexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_sla_complain/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Service Name</th>
                                    <th>Vendor</th>
                                    <th>Issue Product SN</th>
                                    <th>Issue Product Make</th>
                                    <th>Issue Product Model</th>
                                    <th>Issued To</th>
                                    <th>Replace Product SN</th>
                                    <th>Replace Product Make</th>
                                    <th>Replace Product Model</th>
                                    <th>Type</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($reorders as $sla)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ $sla->subcategory->sub_cat_name ?? 'No Subcategory'}}</td>
                                        <td>{{ $sla->vendor->vendor_name ?? 'No Vendor'}}</td>
                                        <td>{{ $sla->issue_product_sn ?? 'No SN'}}</td>
                                        <td>{{ \App\Makee::find($sla->issue_make_id)['make_name'] ?? 'No Make'}}</td>
                                        <td>{{ \App\Modal::find($sla->issue_model_id)['model_name'] ?? 'No Model'}}</td>
                                        <td>{{ \App\Employee::where('emp_code', $sla->issued_to)->first()['name'] ?? 'Not Issued'}}</td>
                                        <td>{{ $sla->replace_product_sn ?? 'No SN'}}</td>
                                        <td>{{ \App\Makee::find($sla->replace_product_make_id)['make_name'] ?? 'No Make'}}</td>
                                        <td>{{ \App\Modal::find($sla->replace_product_model_id)['model_name'] ?? 'No Model'}}</td>
                                        @if($sla->replace_type == 1)
                                            <td>Replace</td>
                                        @elseif($sla->replace_type == 2)
                                            <td>Repair</td>
                                        @else
                                            <td>Non Repairable</td>
                                        @endif
                                        <td>{{ \App\User::find($sla->added_by)['name'] ?? 'No Name'}}</td>
                                        <td>{{$sla->created_at ?? 'No Date'}}</td>
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
