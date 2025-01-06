@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">List User Budget Plan</h1>
                    </div>
                    <div class="col-md-2 col-lg-2 text-right">
                        <a href="{{ url('add_budget_plan') }}" class="btn btn-success">Add<svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg></a>
                    </div>
                </div>
                <hr />
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
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Year</th>
{{--                                    <th>Items</th>--}}
{{--                                    <th>New/PRF Qty</th>--}}
{{--                                    <th>Upgraded Qty</th>--}}
{{--                                    <th>Remarks</th>--}}
                                    <th>Created at</th>
                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'edit_auth_user_budget_plan') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'delete_auth_user_plan') == true)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($budget_plan as $plan)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ \App\User::find($plan->user_id)['name'] }}</td>
                                        <td>{{ \App\Year::find($plan->year_id)['year'] }}</td>
{{--                                        <td>--}}
{{--                                            {{ \App\Subcategory::find($plan->subcategory_id)['sub_cat_name'] }} <br/>--}}
{{--                                            {{ \App\Subcategory::find($plan->subcategory_id)['subcat_desc'] != "" ? "(".\App\Subcategory::find($plan->subcategory_id)['subcat_desc'].")" : ""}}--}}
{{--                                        </td>--}}
{{--                                        <td>{{ $plan->new_qty }}</td>--}}
{{--                                        <td>{{ $plan->upgraded_qty }}</td>--}}
{{--                                        <td>{{ $plan->remarks ?? "" }}</td>--}}
                                        <td>{{ $plan->agreed_at ?? "" }}</td>
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'edit_auth_user_budget_plan') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'delete_auth_user_plan') == true)
                                        <td class="text-center">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'edit_auth_user_budget_plan') == true)
                                            <a href="{{ url('edit_budget_planing/'.$plan->id) }}" class="btn btn-sm btn-success">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                </svg>
                                            </a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'delete_auth_user_plan') == true)
                                                <form method="POST" action="{{ url('budgetplan/'.$plan->id) }}" class="d-inline-block prompt_delete">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-primary btn-sm">
                                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                        </svg>
                                                    </button>
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
