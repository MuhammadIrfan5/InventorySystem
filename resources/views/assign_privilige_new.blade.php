@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">Assign Permissions</h1>
                    </div>
                    {{--                    <div class="col-md-2 col-lg-2 text-right">--}}
                    {{--                        <a href="#" class="btn btn-success">View List</a>--}}
                    {{--                    </div>--}}
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                    @elseif(session('error-msg'))
                    <div class="alert alert-danger">
                        {{ session('error-msg') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-1 col-lg-1"></div>
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <form method="POST" action="{{ url('assign_priviliges_new') }}">
                            @csrf
                            <div class="card border-0 rounded-lg mt-3">
                                <div class="card-body">

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="user_id">User</label>
                                                <select class="custom-select user_id" id="user_id" name="user_id">
                                                    <option value=0>Select User here</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->emp_no }}
                                                            - {{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-danger">{{ $errors->first('user_id') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <input type="submit" name="add_privilige" value="Add Permissions"
                                               class="btn btn-primary btn-block">
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 rounded-lg mt-3">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th class="inv_type setup_type budget_type sla_type">Type</th>
                                                <th class="inv_perm setup_perm budget_perm sla_perm">Permission</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td class="inv" id="inv">
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input inv_id"--}}
{{--                                                               name='inv_id'--}}
{{--                                                               value="inventory">--}}
                                                        <input type="hidden" name="inv_id" value="inventory">
                                                        <label class="form-check-label" for="">Inventory</label>
                                                    </div>
                                                </td>
                                                <td class="inv_type">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input inv_type_id"
                                                               name='inv_type_id[]'
                                                               value="form">
                                                        <label class="form-check-label" for="">Forms</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input inv_type_id"
                                                               name='inv_type_id[]'
                                                               value="report">
                                                        <label class="form-check-label" for="">Reports</label>
                                                    </div>
                                                </td>
                                                <td class="inv_perm">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input inv_perm_id"
                                                               name='inv_perm_id[]'
                                                               value="add">
                                                        <label class="form-check-label" for="">Add</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input inv_perm_id"
                                                               name='inv_perm_id[]'
                                                               value="edit">
                                                        <label class="form-check-label" for="">Edit</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input inv_perm_id"
                                                               name='inv_perm_id[]'
                                                               value="delete">
                                                        <label class="form-check-label" for="">Delete</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input inv_perm_id"
                                                               name='inv_perm_id[]'
                                                               value="view">
                                                        <label class="form-check-label" for="">View</label>
                                                    </div>
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input inv_perm_id"--}}
{{--                                                               name='inv_perm_id[]'--}}
{{--                                                               value="all">--}}
{{--                                                        <label class="form-check-label" for="">All</label>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="sla" id="sla">
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input sla_id"--}}
{{--                                                               name='sla_id'--}}
{{--                                                               value="sla">--}}
                                                        <input type="hidden" name="sla_id" value="sla">
                                                        <label class="form-check-label" for="">SLA</label>
                                                    </div>
                                                </td>
                                                <td class="sla_type">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input sla_type_id"
                                                               name='sla_type_id[]'
                                                               value="form">
                                                        <label class="form-check-label" for="">Forms</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input sla_type_id"
                                                               name='sla_type_id[]'
                                                               value="report">
                                                        <label class="form-check-label" for="">Reports</label>
                                                    </div>
                                                </td>
                                                <td class="sla_perm">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input sla_perm_id"
                                                               name='sla_perm_id[]'
                                                               value="add">
                                                        <label class="form-check-label" for="">Add</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input sla_perm_id"
                                                               name='sla_perm_id[]'
                                                               value="edit">
                                                        <label class="form-check-label" for="">Edit</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input sla_perm_id"
                                                               name='sla_perm_id[]'
                                                               value="delete">
                                                        <label class="form-check-label" for="">Delete</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input sla_perm_id"
                                                               name='sla_perm_id[]'
                                                               value="view">
                                                        <label class="form-check-label" for="">View</label>
                                                    </div>
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input sla_perm_id"--}}
{{--                                                               name='sla_perm_id[]'--}}
{{--                                                               value="all">--}}
{{--                                                        <label class="form-check-label" for="">All</label>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="setup" id="setup">
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input setup_id"--}}
{{--                                                               name='setup_id'--}}
{{--                                                               value="setup">--}}
                                                        <input type="hidden" name="setup_id" value="setup">
                                                        <label class="form-check-label" for="">Setup</label>
                                                    </div>
                                                </td>
                                                <td class="setup_type">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input setup_type_id"
                                                               name='setup_type_id[]'
                                                               value="form">
                                                        <label class="form-check-label" for="">Forms</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input setup_type_id"
                                                               name='setup_type_id[]'
                                                               value="report">
                                                        <label class="form-check-label" for="">Reports</label>
                                                    </div>
                                                </td>
                                                <td class="setup_perm">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input setup_perm_id"
                                                               name='setup_perm_id[]'
                                                               value="add">
                                                        <label class="form-check-label" for="">Add</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input setup_perm_id"
                                                               name='setup_perm_id[]'
                                                               value="edit">
                                                        <label class="form-check-label" for="">Edit</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input setup_perm_id"
                                                               name='setup_perm_id[]'
                                                               value="delete">
                                                        <label class="form-check-label" for="">Delete</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input setup_perm_id"
                                                               name='setup_perm_id[]'
                                                               value="view">
                                                        <label class="form-check-label" for="">View</label>
                                                    </div>
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input setup_perm_id"--}}
{{--                                                               name='setup_perm_id[]'--}}
{{--                                                               value="all">--}}
{{--                                                        <label class="form-check-label" for="">All</label>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="budget" id="budget">
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input budget_id"--}}
{{--                                                               name='budget_id'--}}
{{--                                                               value="budget">--}}
                                                        <input type="hidden" name="budget_id" value="budget"/>
                                                        <label class="form-check-label" for="">Budget</label>
                                                    </div>
                                                </td>
                                                <td class="budget_type">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input budget_type_id"
                                                               name='budget_type_id[]'
                                                               value="form">
                                                        <label class="form-check-label" for="">Forms</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input budget_type_id"
                                                               name='budget_type_id[]'
                                                               value="report">
                                                        <label class="form-check-label" for="">Reports</label>
                                                    </div>
                                                </td>
                                                <td class="budget_perm">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input budget_perm_id"
                                                               name='budget_perm_id[]'
                                                               value="add">
                                                        <label class="form-check-label" for="">Add</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input budget_perm_id"
                                                               name='budget_perm_id[]'
                                                               value="edit">
                                                        <label class="form-check-label" for="">Edit</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input budget_perm_id"
                                                               name='budget_perm_id[]'
                                                               value="delete">
                                                        <label class="form-check-label" for="">Delete</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input budget_perm_id"
                                                               name='budget_perm_id[]'
                                                               value="view">
                                                        <label class="form-check-label" for="">View</label>
                                                    </div>
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input budget_perm_id"--}}
{{--                                                               name='budget_perm_id[]'--}}
{{--                                                               value="all">--}}
{{--                                                        <label class="form-check-label" for="">All</label>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="invoice" id="invoice">
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input invoice_id"--}}
{{--                                                               name='invoice_id'--}}
{{--                                                               value="invoice">--}}
                                                        <input type="hidden" name="invoice_id" value="invoice"/>
                                                        <label class="form-check-label" for="">Invoice</label>
                                                    </div>
                                                </td>
                                                <td class="invoice_type">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input invoice_type_id"
                                                               name='invoice_type_id[]'
                                                               value="form">
                                                        <label class="form-check-label" for="">Forms</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input invoice_type_id"
                                                               name='invoice_type_id[]'
                                                               value="report">
                                                        <label class="form-check-label" for="">Reports</label>
                                                    </div>
                                                </td>
                                                <td class="invoice_perm">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input invoice_perm_id"
                                                               name='invoice_perm_id[]'
                                                               value="add">
                                                        <label class="form-check-label" for="">Add</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input invoice_perm_id"
                                                               name='invoice_perm_id[]'
                                                               value="edit">
                                                        <label class="form-check-label" for="">Edit</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input invoice_perm_id"
                                                               name='invoice_perm_id[]'
                                                               value="delete">
                                                        <label class="form-check-label" for="">Delete</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input invoice_perm_id"
                                                               name='invoice_perm_id[]'
                                                               value="view">
                                                        <label class="form-check-label" for="">View</label>
                                                    </div>
                                                    <div class="form-check">
{{--                                                        <input type="checkbox" class="form-check-input invoice_perm_id"--}}
{{--                                                               name='invoice_perm_id[]'--}}
{{--                                                               value="all">--}}
{{--                                                        <label class="form-check-label" for="">All</label>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        // $('.inv_type').hide();//addClass('unselectable');
        // $('.inv_perm').hide();//addClass('unselectable');
        // $('.sla_type').hide();//addClass('unselectable');
        // $('.sla_perm').hide();//addClass('unselectable');
        // $('.setup_type').hide();
        // $('.setup_perm').hide();
        // $('.budget_perm').hide();
        // $('.budget_type').hide();
        // $('.invoice_perm').hide();
        // $('.invoice_type').hide();
        $(document).ready(function () {

            const values = [];
            const permission_element = document.querySelector('#privilige_id');
            var permission_choice = new Choices(permission_element, {
                removeItemButton: true,
                maxItemCount: 100,
                searchResultLimit: 100,
                renderChoiceLimit: 100
            });

            $('#user_id').on("change", function () {
                $('.user_id').css('font-weight', 'bold');
            });
            // $('.inv_id').click(function () {
            //     if ($(this).is(":checked")) {
            //         $('.inv_type').show();
            //         $('.inv_perm').show();
            //     }
            //     if (!$(this).is(":checked")) {
            //         $('.inv_type').hide();//addClass('unselectable');
            //         $('.inv_perm').hide();//addClass('unselectable');
            //     }
            // });
            //
            // $('.sla_id').click(function () {
            //     if ($(this).is(":checked")) {
            //         $('.sla_type').show();
            //         $('.sla_perm').show();
            //     }
            //     if (!$(this).is(":checked")) {
            //         $('.sla_type').hide();
            //         $('.sla_perm').hide();
            //     }
            // });
            //
            // $('.setup_id').click(function () {
            //     if ($(this).is(":checked")) {
            //         $('.setup_type').show();
            //         $('.setup_perm').show();
            //     }
            //     if (!$(this).is(":checked")) {
            //         $('.setup_type').hide();
            //         $('.setup_perm').hide();
            //     }
            // });
            // $('.budget_id').click(function () {
            //     if ($(this).is(":checked")) {
            //         $('.budget_perm').show();
            //         $('.budget_type').show();
            //     }
            //     if (!$(this).is(":checked")) {
            //         $('.budget_perm').hide();
            //         $('.budget_type').hide();
            //     }
            // });
            //
            // $('.invoice_id').click(function () {
            //     if ($(this).is(":checked")) {
            //         $('.invoice_perm').show();
            //         $('.invoice_type').show();
            //     }
            //     if (!$(this).is(":checked")) {
            //         $('.invoice_perm').hide();
            //         $('.invoice_type').hide();
            //     }
            // });

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

@endsection
