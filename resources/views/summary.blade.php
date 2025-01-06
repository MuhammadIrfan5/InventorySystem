@extends("master")

@section("content")
<style>
    .inner-table{
        width:100%;
        border-spacing: 0px;
        border: none;
    }
    .inner-table tr th, .inner-table tr td{
        width:33%;
    }
    .text-center{
        text-align: center;
    }
    .text-right{
        text-align: right;
    }
    </style>
<?php
// $grand_u_d = 0;
// $grand_u_p = 0;
// $grand_t_d = 0;
// $grand_t_p = 0;
// $grand_c = 0;
// $grand_r = 0;
 ?>
 <?php
 $grand_t_d = 0;
 $grand_t_p = 0;
 $grand_qty = 0;
 $grand_c_d = 0;
 $grand_c_p = 0;
 $grand_c_qty = 0;
 $grand_r_d = 0;
 $grand_r_p = 0;
 $grand_r_qty = 0;
 ?>
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">

                    @if (session('msg'))
                        <div class="alert alert-success mt-4">
                            {{ session('msg') }}
                        </div>
                    @endif

                    <div class="row">
                    <div class="col-md-3 col-lg-3">
                    </div>
                    <div class="col-md-6 col-lg-6">

                            <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                            Select budget year
                            </div>
                                <div class="card-body">
                                <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                            <form method="POST" action="{{ url('summary_by_year') }}">
                                            @csrf

                                                <td>
                                                    <select class="custom-select year_id" name="year_id" required>
                                                    <option value=0>Select Year here</option>
                                                    @foreach ($years as $year)
                                                    @if($year->id == $filter)
                                                    <option value="{{ $year->id }}" selected>{{ $year->year }}</option>
                                                    @else
                                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endif
                                                    @endforeach
                                                    </select>
                                                    <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                                </td>
                                                <td><button type="submit" name="show" class="btn btn-primary">Show</button></td>
                                                </form>
                                            </tr>
                                        </tbody>
                                </table>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            @if(empty($types))
                            @else
                            <a class="btn btn-sm btn-danger mt-3 mb-1 ml-1 float-right" href="{{ url('budgetexport/'.$filter) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mt-3 mb-1 float-right" href="{{ url('export_summary/'.$filter) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                            @endif
                    </div>
                    </div>
                    @if(empty($types))
                    <div class="card mb-4 mt-3">
                            <div class="card-body">
                            <span class="text-danger">{{ $errors->first('inv_id') }}</span>
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Category</th>
                                                <th>Price Unit $</th>
                                                <th>Price Unit PKR</th>
                                                <th>Price Total $</th>
                                                <th>Price Total PKR</th>
                                                <th>Consumed</th>
                                                <th>Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                    @else
                    @foreach($types as $key=>$type)
                        <div class="card mb-4 mt-3">
                            <div class="card-body">
                            <h3><u>{{ $type->type }}</u></h3>

                            <span class="text-danger">{{ $errors->first('inv_id') }}</span>
                                <div class="table-responsive">

                                    <table class="table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th colspan="3">Total Budget</th>
                                                <th colspan="3">Consumed</th>
                                                <th colspan="3">Remaining</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Category</th>
                                                <th>Dollar</th>
                                                <th>PKR</th>
                                                <th>Quantity</th>
                                                <th>Dollar</th>
                                                <th>PKR</th>
                                                <th>Quantity</th>
                                                <th>Dollar</th>
                                                <th>PKR</th>
                                                <th>Quantity</th>
                                            </tr>
                                        <?php
                                        $i = 1;
                                        $total_b_d = 0;
                                        $total_b_p = 0;
                                        $total_qty = 0;

                                        $c_b_d = 0;
                                        $c_b_p = 0;
                                        $c_qty = 0;

                                        $r_b_d = 0;
                                        $r_b_p = 0;
                                        $r_qty = 0;
                                        ?>
                                        @foreach ($type->categories as $budget)
                                            <tr>
                                                <td>
                                                <span style="float:right;">{{ $i++ }}</span>
                                                <a style="float:left; position:absolute;" data-cat_id="{{ $budget->id }}" data-type_id="{{ $type->id }}" class="showdetails" data-toggle="modal" data-target="#budgetdetails">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>
</a>
                                                </td>
                                                <td>{{ $budget->category_name }}</td>
                                                <td class="text-right">{{ number_format($budget->total_price_dollar,2) }}</td>
                                                <td class="text-right">{{ number_format($budget->total_price_pkr,2)}}</td>
                                                <td class="text-right">{{ number_format($budget->qty,2) }}</td>
                                                <td class="text-right">{{ number_format(($budget->consumed_price_dollar),2) }}</td>
                                                <td class="text-right">{{ number_format(($budget->consumed_price_pkr),2) }}</td>
                                                <td class="text-right">{{ number_format($budget->consumed,2) }}</td>
                                                <td class="text-right">{{ number_format(($budget->remaining_price_dollar),2) }}</td>
                                                <td class="text-right">{{ number_format(($budget->remaining_price_pkr),2) }}</td>
                                                <td class="text-right">{{ number_format($budget->remaining,2) }}</td>
                                            </tr>
                                            <?php
                                            $total_b_d += $budget->total_price_dollar;
                                            $total_b_p += $budget->total_price_pkr;
                                            $total_qty += $budget->qty;
                                            $c_b_d += $budget->consumed_price_dollar;
                                            $c_b_p += $budget->consumed_price_pkr;
                                            $c_qty += $budget->consumed;
                                            $r_b_d += $budget->remaining_price_dollar;
                                            $r_b_p += $budget->remaining_price_pkr;
                                            $r_qty += $budget->remaining;
                                            ?>
                                        @endforeach
                                        </tbody>
                                            <tr>
                                                <th colspan='2' style="text-align:right;">Total</th>
                                                <td class="text-right">{{ number_format($total_b_d,2) }}</td>
                                                <td class="text-right">{{ number_format($total_b_p,2) }}</td>
                                                <td class="text-right">{{ number_format($total_qty,2) }}</td>
                                                <td class="text-right">{{ number_format($c_b_d,2) }}</td>
                                                <td class="text-right">{{ number_format($c_b_p,2) }}</td>
                                                <td class="text-right">{{ number_format($c_qty,2) }}</td>
                                                <td class="text-right">{{ number_format($r_b_d,2) }}</td>
                                                <td class="text-right">{{ number_format($r_b_p,2) }}</td>
                                                <td class="text-right">{{ number_format($r_qty,2) }}</td>
                                            </tr>
<?php
$grand_t_d += $total_b_d;
$grand_t_p += $total_b_p;
$grand_qty += $total_qty;
$grand_c_d += $c_b_d;
$grand_c_p += $c_b_p;
$grand_c_qty += $c_qty;
$grand_r_d += $r_b_d;
$grand_r_p += $r_b_p;
$grand_r_qty += $r_qty;
 ?>
 @if($key == 1)
                                        <tfoot>
                                            <tr>
                                                <th colspan='2' class="text-right">Grand Total</th>
                                                <td class="text-right">{{ number_format($grand_t_d,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_t_p,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_qty,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_c_d,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_c_p,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_c_qty,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_r_d,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_r_p,2) }}</td>
                                                <td class="text-right">{{ number_format($grand_r_qty,2) }}</td>
                                            </tr>
                                            </tfoot>
                                            @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif
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
<!-- Modal -->
<div class="modal fade" id="budgetdetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Budget Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                        </tr>
                    </thead>
                    <tbody class="detail_body">
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

            $('.showdetails').click(function () {
                var cat_id = $(this).data('cat_id');
                var type_id = $(this).data('type_id');
                var year_id = $('.year_id').val();
                $.get("{{ url('budgetdetails') }}/" + cat_id + "/" + type_id + "/" + year_id, function (data) {
                    $('.detail_body').html(data);
                });
            });

        });
    </script>

@endsection
