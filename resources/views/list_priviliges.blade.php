@extends("master")

@section("content")

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">

                @if (session('msg'))
                    <div class="alert alert-success mt-4">
                        {{ session('msg') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-2 col-lg-2">

                    </div>
                    <div class="col-md-8 col-lg-8">

                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                Select User
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                    <tr>
                                        <form method="GET" action="{{ url('show_priviliges_by_user') }}">
                                            @csrf
                                            <td>
                                                <select class="custom-select singled" name="user_id" required>
                                                    <option value='0'>All</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{$user->id == $selected_user ? 'selected' : ''}}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span
                                                    class="small text-danger">{{ $errors->first('user_id') }}</span>
                                            </td>
                                            <td>
                                                <button type="submit" name="show" class="btn btn-primary">Show</button>
                                            </td>
                                        </form>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 mt-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Privilige ID</th>
                                    <th>Privilige Title</th>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($permission_list as $data)
                                    <tr>
                                        <td>{{ $data->privilige->id ?? '' }}</td>
                                        <td>{{ $data->privilige->privilige_title ?? '' }}</td>
                                        <td>{{ $data->user->name ?? '' }}</td>
                                        <td>{{ $data->role->role ?? '' }}</td>
                                        <td class="text-center">
{{--                                            <a href="{{ url('priviliges/'.$data->id) }}"--}}
{{--                                               class="btn btn-sm btn-success">--}}
{{--                                                <svg width="1em" height="1em" viewBox="0 0 16 16"--}}
{{--                                                     class="bi bi-pencil" fill="currentColor"--}}
{{--                                                     xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                    <path fill-rule="evenodd"--}}
{{--                                                          d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>--}}
{{--                                                </svg>--}}
{{--                                            </a>--}}
                                            <form method="POST" action="{{ url('priviliges/'.$data->id) }}"
                                                  class="d-inline-block prompt_delete">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                         class="bi bi-trash" fill="currentColor"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd"
                                                              d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
    $('.singled').select2();
    });
    </script>
@endsection
