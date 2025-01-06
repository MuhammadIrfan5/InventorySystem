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
                            Dispatch IN
                            </div>
                                <div class="card-body">
                                <table class="table table-borderless">
                                        <tbody>  
                                        <form method="GET" action="{{ url('dispatchin_report') }}">
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
                            @if(empty($dispatch))
                            @else
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right" href="{{ url('dispatchinexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_dispatchin/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                            @endif
                            
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Date IN</th>
                                                <th>Item</th>
                                                <th>Product S#</th>
                                                <th>Assigned To</th>
                                                <th>Branch Name</th>
                                                <th>BR. Code</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Other Accessories</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($dispatch as $disp)
                                            <tr>
                                                <td class='text-align-right'>{{ $i++ }}</td>
                                                <td>{{ date('d-M-Y', strtotime($disp->dispatchin_date)) }}</td>
                                                <td>{{ !empty($disp->subcategory)?$disp->subcategory->sub_cat_name:'' }}</td>
                                                <td><a href="{{ url('item_detail/'.$disp->inventory_id) }}">{{ !empty($disp->inventory)?$disp->inventory->product_sn:'' }}</a></td>
                                                <td>{{ !empty($disp->user)?$disp->user->name:'' }}</td>
                                                <td>{{ !empty($disp->user)?$disp->user->branch:'' }}</td>
                                                <td>{{ !empty($disp->user)?$disp->user->branch_id:'' }}</td>
                                                <td>{{ !empty($disp->inventory->make)?$disp->inventory->make->make_name:'' }}</td>
                                                <td>{{ !empty($disp->inventory->model)?$disp->inventory->model->model_name:'' }}</td>
                                                <td>{{ !empty($disp->inventory)?$disp->inventory->other_accessories:'' }}</td>
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