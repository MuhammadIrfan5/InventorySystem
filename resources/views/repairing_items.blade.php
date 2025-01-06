@extends("master")

@section("content")

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Repairing Items</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('repair') }}" class="btn btn-success">Add
                            <svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
                <div class="card mb-4 mt-5">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Items</th>
                                    <th>Remarks</th>
                                    <th>Actual Price</th>
                                    <th>Price Value</th>
                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'edit_repair_item') == true)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($repairing_items as $items)

                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ empty($items->category)?'':$items->category->category_name }}</td>
                                        <td>{{ empty($items->subcategory)?'':$items->subcategory->sub_cat_name }}</td>
                                        <td>{{ empty($items->item)?'':$items->item->product_sn }}</td>
                                        <td>{{ $items->remarks }}</td>
                                        <td>{{ number_format($items->actual_price_value,2) }}</td>
                                        <td>{{ number_format($items->price_value,2) }}</td>
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'edit_repair_item') == true)
                                            <td>

                                                @if(!$items->handover_date)
                                                    <a href="{{ url('edit_repair_items/'.$items->id) }}"
                                                       class="btn btn-sm btn-success">
                                                        <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                             class="bi bi-pencil" fill="currentColor"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                  d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                        </svg>
                                                    </a>

                                                    </form>
                                                @endif
                                            </td>
                                        @endif
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
