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
                            Reorder levels
                            </div>
                                <div class="card-body">
                                <table class="table table-borderless">
                                        <tbody>
                                        <form method="GET" action="{{ url('reorder-level') }}">
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
                                                <select class="custom-select field_size subcategory" name="id" data-reports="1">
                                                    <option value="">All</option>
                                                    @foreach ($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}">{{ $subcategory->sub_cat_name }}</option>
                                                    @endforeach
                                                    </select>
                                                    <span class="small text-danger">{{ $errors->first('subcategory_id') }}</span>
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
                            <a class="btn btn-sm btn-danger mb-2 ml-1 float-right" href="{{ url('reorderexport/'.json_encode($filters)) }}">Print <i class="fa fa-download" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger mb-2 float-right" href="{{ url('export_reorderlevel/'.json_encode($filters)) }}">CSV <i class="fa fa-download" aria-hidden="true"></i></a>
                            @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Item</th>
                                                <th>Reorder Level</th>
                                                <th>Qty. in Stock</th>
                                                <th>Issued in Last 3 months</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($reorders as $reorder)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $reorder->sub_cat_name }}</td>
                                                <td style="text-align:center;">{{ $reorder->threshold }}</td>
                                                <td style="text-align:center;">{{ $reorder->in_stock }}</td>
                                                <td style="text-align:center;">{{ $reorder->issued_count }}</td>
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

            $("#category").on("change",function(){
                var id = $(this).val();
                var report = $('.subcategory').data('reports');
                var subcategory = $('.subcategory');
                subcategory.empty();
                if(report == 1){
                    subcategory.append('<option value="" class="o1">All</option>');
                }
                else{
                    subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');
                }
                $.get("{{ url('subcat_by_category') }}/"+id, function(data){

                    $.each( data, function(index, value){
                        subcategory.append(
                            $('<option></option>').val(value.id).html(value.sub_cat_name)
                        );
                    });
                });
            });

            $(".t_seperator").focusout(function () {
                var value = $(this).val();

                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).val(num_parts.join("."));
                //alert(num_parts.join("."));
            });


        });
    </script>

@endsection
