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
                                    <form method="GET" action="{{ url('sla_report') }}">
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
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right" href="{{ url('slaexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_sla/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Service Name</th>
                                    <th>Vendor</th>
                                    <th>Agreement Start Date</th>
                                    <th>Agreement End Date</th>
                                    <th>POC Name</th>
                                    <th>POC Contact</th>
                                    <th>POC Email</th>
                                    <th>Created By</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($reorders as $sla)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ $sla->subcategory->sub_cat_name ?? 'No Subcategory'}}</td>
                                        <td>{{ $sla->vendor->vendor_name ?? 'No Vendor'}}</td>
                                        <td>{{ $sla->agreement_start_date ?? 'No Start Date'}}</td>
                                        <td>{{ $sla->agreement_end_date ?? 'No End Date'}}</td>
                                        <td>{{ $sla->vendor->contact_person ?? 'No Contact Person'}}</td>
                                        <td>{{ $sla->vendor->cell ?? 'No Cell'}}</td>
                                        <td>{{ $sla->vendor->email ?? 'No Email'}}</td>
                                        <td>{{ \App\User::find($sla->created_by)['name'] ?? 'No Name'}}</td>
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
