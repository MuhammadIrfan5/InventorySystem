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
                                Summary
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <form  method="POST" action="{{ url('summary_by_Dollar') }}">
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="privilige_id">Year list</label>
                                                    <select class="form-control" multiple="multiple" id="privilige_id"
                                                            name="summary[]" placeholder="Select a Year"
                                                            style="width: 100%;">
                                                        @foreach ($yearList as $privilige)
                                                            <option value="{{ $privilige->id }}">{{ $privilige->year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-4 mb-0">
                                            <input type="submit" name="add_privilige" value="Search" class="btn btn-primary btn-block">
                                        </div>
                                    </form>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                    </div>
                </div>

                <br/>
                <br/>
                @if (session('success-msg'))
                    <div class="alert alert-success">
                        {{ session('success-msg') }}
                    </div>
                @elseif(session('error-msg'))
                    <div class="alert alert-error">
                        {{ session('error-msg') }}
                    </div>
                @endif

                <div class="card mb-4 mt-5">
                    <div class="card-body">

                        @if(empty($data->data))

                        @else
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right"
                               href="{{ url('downloadSummaryByYear/'.json_encode($data->data)) }}">Print <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right"
                               href="{{ url('export_capexOpexSummaryDollar/'.json_encode($data->data)) }}">CSV <i
                                    class="fa fa-download" aria-hidden="true"></i></a>
{{--                            <a class="btn btn-sm btn-danger mb-2 mr-2 float-right"--}}
{{--                               href="{{ url('generate_barcode/'.json_encode($filters)) }}">Generate QRCode <i--}}
{{--                                    class="fa fa-download" aria-hidden="true"></i></a>--}}
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Budget Year</th>
                                    <th>Type</th>
                                    <th>Dollar Amount $</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if(!empty($data->data))
                                <?php $i = 1;
                                    $capexTotal = 0;
                                    $opexTotal = 0;
                                    $bothTotal = 0;
                                ?>
                                @foreach ($data->data as $inventory)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ $inventory['year'] }}</td>
                                        <td>{{ $inventory['type']}}</td>
                                        <td class="text-right">{{ '$'. number_format($inventory['dollarAmount'],2) }}</td>
                                        @if($inventory['type'] == "Capital Expenditure")
                                            <?php $capexTotal += $inventory['dollarAmount'] ?>
                                        @else
                                           <?php $opexTotal += $inventory['dollarAmount']?>
                                        @endif()
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                    <th class="text-right" colspan="2"> Total </th>
                                    <th class="text-right" colspan="2"> {{ number_format($opexTotal +$capexTotal ,2) }} </th>
                                </tfoot>
                                <tfoot>
                                    <th >Capex Total </th>
                                    <th class="text-right"> {{ number_format($capexTotal ?? '0',2) }}</th>
                                    <th>Opex Total </th>
                                    <th class="text-right"> {{ number_format($opexTotal ?? '0',2) }} </th>
                                </tfoot>

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
    $('#privilige_id').select2();
    });
    $('#example').DataTable({
        paging: false,
        ordering: false,
        info: false,
    });

    </script>

@endsection
