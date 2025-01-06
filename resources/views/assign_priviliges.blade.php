@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Assign Permissions</h1>
                    </div>
{{--                    <div class="col-md-2 col-lg-2 text-right">--}}
{{--                        <a href="#" class="btn btn-success">View List</a>--}}
{{--                    </div>--}}
                </div>
                <hr />
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-1 col-lg-1"></div>
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <div class="card border-0 rounded-lg mt-3">
                            <div class="card-body">
                                <form  method="POST" action="{{ url('priviliges') }}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="user_id">User</label>
                                                <select class="custom-select user_id" id="user_id" name="user_id">
                                                    <option value=0>Select User here</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('user_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="privilige_id">Permissions</label>
                                                <select class="form-control" id="privilige_id" multiple
                                                        name="privilige_id[]" placeholder="Select a Year"
                                                        style="width: 100%;">
                                                    @foreach ($priviliges as $privilige)
                                                        <option value="{{ $privilige->id }}">{{ $privilige->privilige_title }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('privilige_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" name="add_privilige" value="Add Permissions" class="btn btn-primary btn-block">
                                    </div>
                                </form>
                            </div>
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
            const values = [];
            const permission_element = document.querySelector('#privilige_id');
            var permission_choice = new Choices(permission_element, {
                removeItemButton: true,
                maxItemCount: 300,
                searchResultLimit: 300,
                renderChoiceLimit: 300
            });

            {{--$("#user_id").on("change", function () {--}}
            {{--    var id = $(this).val();--}}

            {{--    $.get("{{ url('get_user_unassigned_priviliges') }}/" + id, function (data) {--}}

            {{--        var privilige_id = $('#privilige_id');--}}
            {{--        // privilige_id.empty();--}}
            {{--        privilige_id.append('<option value=0 class="o1">Select Permissions here</option>');--}}
            {{--        $.each(data, function (index, value) {--}}
            {{--            values.push(value);--}}
            {{--            // permission_choice.setChoices(values);--}}
            {{--            model.append(--}}
            {{--                $('<option></option>').val(value.id).html(value.privilige_title)--}}
            {{--            );--}}
            {{--        });--}}
            {{--    });--}}
            {{--});--}}

        });

    </script>
    @include('layouts.customScript')
@endsection
