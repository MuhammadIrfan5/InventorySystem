@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">SLA Complain Log</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('add_sla_log') }}" class="btn btn-success">Add<svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg></a>
                    </div>
                </div>
                <hr />
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @elseif(session('error-msg'))
                    <div class="alert alert-danger">
                        {{ session('error-msg') }}
                    </div>
                @endif
                <div class="card mb-4 mt-5">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Name</th>
                                    <th>Sub-Category Name</th>
                                    <th>Budget Type</th>
                                    <th>Budget Year</th>
                                    <th>Vendor Name</th>
                                    <th>SLA Type</th>
                                    <th>Issue Product SN</th>
                                    <th>Issue Make</th>
                                    <th>Issue Model</th>
                                    <th>Issue Occur Date</th>
                                    <th>Visit Date & Time</th>
                                    <th>Handed Over Date</th>
                                    <th>Replace Type</th>
                                    <th>Replace Product SN</th>
                                    <th>Cost Occured</th>
                                    <th>Replace Make</th>
                                    <th>Replace Model</th>
                                    <th>Issue Resolve Date</th>
                                    <th>Craeted at</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($data as $sla)
                                    <tr>
                                        <td class='text-align-right'>{{ $i++ }}</td>
                                        <td>{{ $sla->category->category_name }}</td>
                                        <td>{{ $sla->subcategory->sub_cat_name }}</td>
                                        <td>{{ $sla->type->type }}</td>
                                        <td>{{ $sla->year->year }}</td>
                                        <td>{{ $sla->vendor->vendor_name }}</td>
                                        <td>{{ ($sla->sla_type == '1' ? \App\SlaLogType::find($sla->sla_type)->first()['type'] : 'Complain Log') }}</td>
                                        <td>{{ $sla->issue_product_sn }}</td>
                                        <td>{{ \App\Makee::select('make_name')->where('id',$sla->issue_make_id)->first()['make_name'] ?? '' }}</td>
                                        <td>{{ \App\Modal::select('model_name')->where('id',$sla->issue_model_id)->first()['model_name'] ?? '' }}</td>
                                        <td>{{ $sla->issue_occur_date }}</td>
                                        <td>{{ $sla->visit_date_time }}</td>
                                        <td>{{ $sla->handed_over_date }}</td>
                                        @if($sla->replace_type == 1)
                                            <td>Replace</td>
                                        @elseif($sla->replace_type == 1)
                                            <td>Repair</td>
                                        @else
                                            <td>Non Repairable</td>
                                        @endif
                                        <td>{{ $sla->replace_product_sn }}</td>
                                        <td class="t_seperator">{{ $sla->cost_occured }}</td>
                                        <td>{{ \App\Makee::select('make_name')->where('id',$sla->replace_product_make_id)->first()['make_name'] ?? '' }}</td>
                                        <td>{{ \App\Modal::select('model_name')->where('id',$sla->replace_product_model_id)->first()['model_name'] ?? '' }}</td>
                                        <td>{{ $sla->issue_resolve_date }}</td>
                                        <td>{{ date('d-M-Y' ,strtotime($sla->created_at)) }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('slalog/'.$sla->id) }}" class="btn btn-sm btn-success">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ url('slalog/'.$sla->id) }}" class="d-inline-block prompt_delete">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
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
    <script type="text/javascript">
        $(document).ready(function () {


            $("td.t_seperator").each(function () {
                var value = $(this).text();
                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).text(num_parts.join("."));
                // total += parseInt($(this).text());
            })
        });

    </script>

@endsection
