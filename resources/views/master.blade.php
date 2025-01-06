<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>IMS - EFU Life</title>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.css"/>
    {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
    {{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
    {{--        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"
            crossorigin="anonymous"></script>
    <style>
        .text-align-right {
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }

        .unselectable {
            background-color: #ddd;
            cursor: not-allowed;
        }


    </style>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('dashboard') }}">Inventory System</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i>
    </button>

    <!-- Navbar-->
    <ul class="navbar-nav d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </div>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                {{--                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)--}}
                <!-- Inventories -->
                    <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
                    @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Inventory') > 0)
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventories"
                           aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Inventories
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="inventories" aria-labelledby="headingOne"
                             data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion">
                                <!-- Inventory Forms -->
                                @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Inventory','form') > 0)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#inventory_forms" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Forms
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="inventory_forms" aria-labelledby="headingOne"
                                         data-parent="#inventories">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <!-- Inventory Forms -->
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_inventory') == true)
                                                <a class="nav-link" href="{{ url('add_inventory') }}">Add</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_vendor_term') == true)
                                                <a class="nav-link" href="{{ url('add_vendor_term') }}">Add Vendor
                                                    Term</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_with_grn') == true)
                                                <a class="nav-link" href="{{ url('add_with_grn') }}">Add with GRN</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_with_grn_multiple') == true)
                                            <a class="nav-link" href="{{ url('add_with_grn_multiple') }}">Add with GRN
                                                Multiple</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_with_grn_bulk') == true)
                                            <a class="nav-link" href="{{ url('add_with_grn_bulk') }}">Add with GRN
                                                Bulk</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'pendings') == true)
                                                <a class="nav-link" href="{{ url('pendings') }}">Pending GRNs</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'inventory_index') == true)
                                                <a class="nav-link" href="{{ url('inventory') }}">All Inventories</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'issue_inventory') == true)
                                                <a class="nav-link" href="{{ url('issue_inventory') }}">Issue
                                                    Inventory</a>
                                                <a class="nav-link" href="{{ url('issue_inventory_bulk') }}">Issue
                                                    Inventory Bulk</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'issue_with_gin') == true)
                                                <a class="nav-link" href="{{ url('issue_with_gin') }}">Issue with
                                                    GIN</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'pending_gins') == true)
                                                <a class="nav-link" href="{{ url('pending_gins') }}">Pending GINs</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'transfer_inventory') == true)
                                                <a class="nav-link" href="{{ url('transfer_inventory') }}">Transfer
                                                    Inventory</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'return_inventory') == true)
                                                <a class="nav-link" href="{{ url('return_inventory') }}">Return
                                                    Inventory</a>
                                            @endif
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                               data-target="#repairing" aria-expanded="false"
                                               aria-controls="pagesCollapseAuth">
                                                Asset Repairing
                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i>
                                                </div>
                                            </a>
                                            <div class="collapse" id="repairing" aria-labelledby="headingOne"
                                                 data-parent="#inventory_forms">
                                                <nav class="sb-sidenav-menu-nested nav">
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'repair') == true)
                                                        <a class="nav-link" href="{{ url('repair') }}"> Add Asset
                                                            Repairing</a>
                                                    @endif
                                                <!-- <a class="nav-link" href="{{ url('add_disposal') }}">Repairing</a> -->
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'repair_items') == true)
                                                        <a class="nav-link" href="{{ url('repair_items') }}">List
                                                            Repairing</a>
                                                    @endif
                                                </nav>
                                            </div>
                                            <!-- Disposal -->
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                               data-target="#invoicing_forms" aria-expanded="false"
                                               aria-controls="pagesCollapseAuth">
                                                Disposal/By Back
                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i>
                                                </div>
                                            </a>
                                            <div class="collapse" id="invoicing_forms" aria-labelledby="headingOne"
                                                 data-parent="#inventory_forms">
                                                <nav class="sb-sidenav-menu-nested nav">
                                                    <!-- Inventory Forms -->
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_disposal') == true)
                                                        <a class="nav-link" href="{{ url('add_disposal') }}">Add
                                                            Disposal/By
                                                            Back</a>
                                                    @endif
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'dispose') == true)
                                                        <a class="nav-link" href="{{ url('dispose') }}">List
                                                            Disposals/By
                                                            Back</a>
                                                    @endif
                                                </nav>
                                            </div>
                                            <!-- Dispatch IN -->
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                               data-target="#din"
                                               aria-expanded="false" aria-controls="pagesCollapseAuth">
                                                Dispatch IN
                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i>
                                                </div>
                                            </a>
                                            <div class="collapse" id="din" aria-labelledby="headingOne"
                                                 data-parent="#inventory_forms">
                                                <nav class="sb-sidenav-menu-nested nav">
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_dispatchin') == true)
                                                        <a class="nav-link" href="{{ url('add_dispatchin') }}">Add
                                                            Dispatch
                                                            IN</a>
                                                    @endif
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'dispatchin') == true)
                                                        <a class="nav-link" href="{{ url('dispatchin') }}">List Dispatch
                                                            IN</a>
                                                    @endif
                                                </nav>
                                            </div>
                                            <!-- Dispatch OUT -->
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                               data-target="#dout" aria-expanded="false"
                                               aria-controls="pagesCollapseAuth">
                                                Dispatch OUT
                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i>
                                                </div>
                                            </a>
                                            <div class="collapse" id="dout" aria-labelledby="headingOne"
                                                 data-parent="#inventory_forms">
                                                <nav class="sb-sidenav-menu-nested nav">
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_dispatchout') == true)
                                                        <a class="nav-link" href="{{ url('add_dispatchout') }}">Add
                                                            Dispatch
                                                            OUT</a>
                                                    @endif
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'dispatchout') == true)
                                                        <a class="nav-link" href="{{ url('dispatchout') }}">List
                                                            Dispatch
                                                            OUT</a>
                                                    @endif
                                                </nav>
                                            </div>
                                            <!-- Previous Inventory -->
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_previous_inventory') == true)

                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                               data-target="#prev_Int" aria-expanded="false"
                                               aria-controls="pagesCollapseAuth">
                                                Previous Inventory
                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i>
                                                </div>
                                            </a>
                                            <div class="collapse" id="prev_Int" aria-labelledby="headingOne"
                                                 data-parent="#inventory_forms">
                                                <nav class="sb-sidenav-menu-nested nav">
                                                        <a class="nav-link" href="{{ url('add_previous_inventory') }}">Add
                                                            Previous Inventory</a>
                                                    @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'previous_inventory') == true)
                                                        <a class="nav-link" href="{{ url('previous_inventory') }}">List
                                                            Previous Inventory</a>
                                                    @endif
                                                </nav>
                                            </div>
                                            @endif

                                            <!--Inventory flow -->
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'inventory_flow') == true)
                                                <a class="nav-link" href="{{ url('inventory_flow') }}">Inventory Life
                                                    Cycle</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                                @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Inventory','report') > 0)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#inventory_reports" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Reports
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="inventory_reports" aria-labelledby="headingOne"
                                         data-parent="#inventories">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'get_grns') == true)
                                                <a class="nav-link" href="{{ url('get_grns') }}">GRN</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'get_gins') == true)
                                                <a class="nav-link" href="{{ url('get_gins') }}">GIN</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_inventory_list') == true)
                                                <a class="nav-link" href="{{ url('show_inventory_list') }}">Inventory
                                                    report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'edit_logs') == true)
                                                <a class="nav-link" href="{{ url('edit_logs') }}">Inventory Edit
                                                    Logs</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'inventory_in') == true)
                                                <a class="nav-link" href="{{ url('inventory_in') }}">Inventory In</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'inventory_out') == true)
                                                <a class="nav-link" href="{{ url('inventory_out') }}">Inventory Out</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'balance_report') == true)
                                                <a class="nav-link" href="{{ url('balance_report') }}">Balance
                                                    report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'bin_card') == true)
                                                <a class="nav-link" href="{{ url('bin_card') }}">Bin Card report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'asset_repairing') == true)
                                                <a class="nav-link" href="{{ url('asset_repairing') }}">Asset Repairing
                                                    report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'dispatchin_report') == true)
                                                <a class="nav-link" href="{{ url('dispatchin_report') }}">Dispatch IN
                                                    report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'dispatchout_report') == true)
                                                <a class="nav-link" href="{{ url('dispatchout_report') }}">Dispatch OUT
                                                    report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'vendor_buying') == true)
                                                <a class="nav-link" href="{{ url('vendor_buying') }}">Average Vendor
                                                    Buying</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'reorder-level') == true)
                                                <a class="nav-link" href="{{ url('reorder-level') }}">Reorder Level</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'disposal') == true)
                                                <a class="nav-link" href="{{ url('disposal') }}">Asset Disposal/By Back
                                                    report</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            </nav>
                        </div>
                    @endif
                    {{--                    @endif--}}

                    {{--                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 4)--}}
                    {{--                        Invoicing Menu--}}
                    @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Invoice') > 0)
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#invoicing"
                           aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Invoicing
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="invoicing" aria-labelledby="headingOne"
                             data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion">
                                <!-- Inventory Forms -->
                                @if(\App\UserPrivilige::count_privilige_subtype(auth()->id(),'Invoice','form') > 0)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#invoicing_forms" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Forms
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="invoicing_forms" aria-labelledby="headingOne"
                                         data-parent="#invoicing">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <!-- Inventory Forms -->
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_invoice_recording') == true)
                                                <a class="nav-link" href="{{ url('add_invoice_recording') }}">Add
                                                    Invoice</a>
                                            @endif

                                        </nav>
                                    </div>
                                @endif
                                @if(\App\UserPrivilige::count_privilige_subtype(auth()->id(),'Invoice','report') > 0)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#invocing_reports" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Reports
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="invocing_reports" aria-labelledby="headingOne"
                                         data-parent="#invoicing">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_invoice_inventory_list') == true)
                                                <a class="nav-link" href="{{ url('show_invoice_inventory_list') }}">Invoice
                                                    report</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            </nav>
                        </div>
                    @endif
                    {{--                    @endif--}}
                    {{--                            END INVOICING--}}
                    {{--                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 5)--}}
                    {{--                            SLA MANAGEMENT--}}

                    @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Sla') > 0)

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#sla_manage"
                           aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            SLA & Vendor Management
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="sla_manage" aria-labelledby="headingOne"
                             data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion">
                                <!-- Inventory Forms -->
                                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                   data-target="#sla_ven_forms" aria-expanded="false"
                                   aria-controls="pagesCollapseAuth">
                                    SLA
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="sla_ven_forms" aria-labelledby="headingOne"
                                     data-parent="#sla_ven_forms">
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#inventory_sla_forms" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Forms
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="inventory_sla_forms" aria-labelledby="headingOne"
                                         data-parent="#sla_ven_forms">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <!-- Inventory Forms -->
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_sla') == true)
                                                <a class="nav-link" href="{{ url('add_sla') }}">Add SLA /
                                                    Subscription</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_sla_log') == true)
                                                <a class="nav-link" href="{{url('add_sla_log')}}">SLA Complain Log</a>
                                            @endif
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#invocing_sla_reports" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Reports
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="invocing_sla_reports" aria-labelledby="headingOne"
                                         data-parent="#sla_ven_forms">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'sla_report') == true)
                                                <a class="nav-link" href="{{ url('sla_report') }}">SLA Report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'sla_complain_report') == true)
                                                <a class="nav-link" href="{{ url('sla_complain_report') }}">SLA Complain
                                                    Report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'sla_consumption_report') == true)
                                                <a class="nav-link" href="{{ url('sla_consumption_report') }}">SLA
                                                    Consumption
                                                    Report</a>
                                            @endif
                                        </nav>
                                    </div>
                                </div>

                                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                   data-target="#sla_vend_forms" aria-expanded="false"
                                   aria-controls="pagesCollapseAuth">
                                    Vendor
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="sla_vend_forms" aria-labelledby="headingOne"
                                     data-parent="#sla_ven_forms">
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#vend_forms" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Forms
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="vend_forms" aria-labelledby="headingOne"
                                         data-parent="#sla_vend_forms">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <!-- Inventory Forms -->
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_vendor') == true)
                                                <a class="nav-link" href="{{ url('add_vendor') }}">Add Vendor</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'vendor') == true)
                                                <a class="nav-link" href="{{ url('vendor') }}">List Vendors</a>
                                            @endif
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#ven_reports" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Reports
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="ven_reports" aria-labelledby="headingOne"
                                         data-parent="#sla_vend_forms">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'sla_report') == true)
                                                <a class="nav-link" href="{{ url('sla_report') }}">SLA Report</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'sla_complain_report') == true)
                                                <a class="nav-link" href="{{ url('sla_complain_report') }}">SLA Complain
                                                    Report</a>
                                            @endif
                                        </nav>
                                    </div>
                                </div>

                            </nav>
                        </div>
                        {{--                    @endif--}}

                    <!-- User Management -->
                        {{--                    @if(Auth::user()->role_id == 1)--}}
                    @endif

                    @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Setup') > 0)
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                           aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Setup
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                             data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <!-- Category -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_category') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'category') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#categories" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Categories
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="categories" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_category') == true)
                                                <a class="nav-link" href="{{ url('add_category') }}">Add Category</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'category') == true)
                                                <a class="nav-link" href="{{ url('category') }}">List Category</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Sub Category -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_subcategory') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'sub_category') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#subcategories" aria-expanded="false"
                                       aria-controls="pagesCollapseAuth">
                                        Sub Categories
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="subcategories" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_subcategory') == true)
                                                <a class="nav-link" href="{{ url('add_subcategory') }}">Add Sub
                                                    Category</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'sub_category') == true)
                                                <a class="nav-link" href="{{ url('sub_category') }}">List Sub
                                                    Category</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Users -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_user') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'user') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#users"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Users
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="users" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_user') == true)
                                                <a class="nav-link" href="{{ url('add_user') }}">Add User</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'user') == true)
                                                <a class="nav-link" href="{{ url('user') }}">List Users</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif

                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_department') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'view_department') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#departments"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Departments
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="departments" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_department') == true)
                                                <a class="nav-link" href="{{ url('add_department') }}">Add
                                                    Department</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'view_department') == true)
                                                <a class="nav-link" href="{{ url('department') }}">List Department</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif

                            <!-- Employees -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_employee') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'employee') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#emp"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Employees
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="emp" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_employee') == true)
                                                <a class="nav-link" href="{{ url('add_employee') }}">Add Employee</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'employee') == true)
                                                <a class="nav-link" href="{{ url('employee') }}">List Employees</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Branch -->
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#branch" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Branch
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="branch" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="{{ url('add_branch') }}">Add Branch</a>
                                            <a class="nav-link" href="{{ url('branch') }}">List Branch</a>
                                        </nav>
                                    </div> -->
                                <!-- Department -->
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#department" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Department
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="department" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="{{ url('add_department') }}">Add Department</a>
                                            <a class="nav-link" href="{{ url('department') }}">List Department</a>
                                        </nav>
                                    </div> -->
                                <!-- Location -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_location') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'location') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#location" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Location
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="location" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_location') == true)
                                                <a class="nav-link" href="{{ url('add_location') }}">Add Location</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'location') == true)
                                                <a class="nav-link" href="{{ url('location') }}">List Location</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Make -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_make') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'make') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#make"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Make
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="make" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_make') == true)
                                                <a class="nav-link" href="{{ url('add_make') }}">Add Make</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'make') == true)
                                                <a class="nav-link" href="{{ url('make') }}">List Makes</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Model -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_model') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'model') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#model"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Model
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="model" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_model') == true)
                                                <a class="nav-link" href="{{ url('add_model') }}">Add Model</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'model') == true)
                                                <a class="nav-link" href="{{ url('model') }}">List Model</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Device Type -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_devicetype') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'devicetype') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dtype"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Current Condition
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="dtype" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_devicetype') == true)
                                                <a class="nav-link" href="{{ url('add_devicetype') }}">Add Current
                                                    Condition</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'devicetype') == true)
                                                <a class="nav-link" href="{{ url('devicetype') }}">List Current
                                                    Conditions</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Item Nature -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_itemnature') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'itemnature') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#nature"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Item Nature
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="nature" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_itemnature') == true)
                                                <a class="nav-link" href="{{ url('add_itemnature') }}">Add Item
                                                    Nature</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'itemnature') == true)
                                                <a class="nav-link" href="{{ url('itemnature') }}">List Item Natures</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Inventory Type -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_inventorytype') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'inventorytype') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#invtype"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Initial Status
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="invtype" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_inventorytype') == true)
                                                <a class="nav-link" href="{{ url('add_inventorytype') }}">Add Initial
                                                    Status</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'inventorytype') == true)
                                                <a class="nav-link" href="{{ url('inventorytype') }}">List Initial
                                                    Status</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Role -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'role') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#role"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Role
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="role" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'role') == true)
                                            <!-- <a class="nav-link" href="{{ url('add_role') }}">Add Role</a> -->
                                                <a class="nav-link" href="{{ url('role') }}">List Role</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Store -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_store') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'store') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#store"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Store
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="store" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_store') == true)
                                                <a class="nav-link" href="{{ url('add_store') }}">Add Store</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'store') == true)
                                                <a class="nav-link" href="{{ url('store') }}">List Store</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Vendor -->

                                <!-- Disposal Status -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_d_status') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'disposalstatus') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                       data-target="#d_status" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Disposal Status
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="d_status" aria-labelledby="headingOne"
                                         data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_d_status') == true)
                                                <a class="nav-link" href="{{ url('add_d_status') }}">Add Status</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'disposalstatus') == true)
                                                <a class="nav-link" href="{{ url('disposalstatus') }}">List Status</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_type') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'types') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Type"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Budget Type
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="Type" aria-labelledby="headingOne"
                                         data-parent="#collapsePages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_type') == true)
                                                <a class="nav-link" href="{{ url('add_type') }}">Add Type</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'types') == true)
                                                <a class="nav-link" href="{{ url('types') }}">List types</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                            <!-- Year -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_year') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'years') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Year"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Budget Year
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="Year" aria-labelledby="headingOne"
                                         data-parent="#collapsePages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_year') == true)
                                                <a class="nav-link" href="{{ url('add_year') }}">Add Year</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'years') == true)
                                                <a class="nav-link" href="{{ url('years') }}">List Years</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif

                            <!-- Dollar -->
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_dollar_price') == true || \App\UserPrivilige::get_single_privilige(auth()->id(),'dollars') == true)
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dollar"
                                       aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Dollar Price
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="dollar" aria-labelledby="headingOne"
                                         data-parent="#collapsePages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_dollar_price') == true)
                                                <a class="nav-link" href="{{ url('add_dollar_price') }}">Add Price</a>
                                            @endif
                                            @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'dollars') == true)
                                                <a class="nav-link" href="{{ url('dollars') }}">List Prices</a>
                                            @endif
                                        </nav>
                                    </div>
                                @endif
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'budget_transfer') == true)
                                    <a class="nav-link" href="{{ url('budget_transfer') }}">Budget Versioning</a>
                                @endif
                                @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'transfer_product_sn') == true)
                                    <a class="nav-link" href="{{ url('transfer_product_sn') }}">Inventory Carry
                                        Forward</a>
                                @endif
                            </nav>
                        </div>
                        {{--                    @endif--}}

                        {{--                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)--}}
                    <!-- Budget System -->
                        <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
                    @endif

                    @if(\App\UserPrivilige::count_privilige_type(auth()->id(),'Budget') > 0)
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#budget"
                       aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Budget System
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="budget" aria-labelledby="headingOne"
                         data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion">
                            <!-- Inventory Forms -->
{{--                            @if(\App\UserPrivilige::count_privilige_subtype(auth()->id(),'Budget','form') > 0)--}}
                                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                   data-target="#invoicing_forms" aria-expanded="false"
                                   aria-controls="pagesCollapseAuth">
                                    Forms
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="invoicing_forms" aria-labelledby="headingOne"
                                     data-parent="#budget">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_budget') == true)
                                            <a class="nav-link" href="{{ url('add_budget') }}">Add Budget</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_budget_collection') == true)
                                            <a class="nav-link" href="{{ url('add_budget_collection') }}">Add Budget Collection</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'add_it_budget_plan') == true)
                                            <a class="nav-link" href="{{ url('add_budget_plan') }}">Add Budget Plan</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_auth_user_plan') == true)
                                            <a class="nav-link" href="{{ url('show_auth_user_plan') }}">List Budget
                                                Plan</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_budget') == true)
                                            <a class="nav-link" href="{{ url('show_budget') }}">Show Budget</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'budget_collection') == true)
                                            <a class="nav-link" href="{{ url('budget_collection') }}">Show Budget
                                                Collection</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'swapping') == true)
                                            <a class="nav-link" href="{{ url('swapping') }}">Budget Swapping</a>
                                        @endif
                                    </nav>
                                </div>
{{--                            @endif--}}
                            @if(\App\UserPrivilige::count_privilige_subtype(auth()->id(),'Budget','report') > 0)
                                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                   data-target="#budget_reports" aria-expanded="false"
                                   aria-controls="pagesCollapseAuth">
                                    Reports
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="budget_reports" aria-labelledby="headingOne"
                                     data-parent="#budget">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_subcategory_budget') == true)
                                            <a class="nav-link" href="{{ url('show_subcategory_budget') }}">Budget By
                                                Sub-category Report</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'capexOpexSummaryDollar') == true)
                                            <a class="nav-link" href="{{ url('capexOpexSummaryDollar') }}">Capex Opex
                                                Summary By Year</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_subcategory_budget_advance') == true)
                                            <a class="nav-link" href="{{ url('show_subcategory_budget_advance') }}">Advance
                                                Report</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'show_subcategory_budget_summary') == true)
                                            <a class="nav-link"
                                               href="{{ url('show_subcategory_budget_summary') }}">Summary</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'budget_comparison') == true)
                                            <a class="nav-link" href="{{ url('budget_comparison') }}">Budget
                                                Comparison</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'summary') == true)
                                            <a class="nav-link" href="{{ url('summary') }}">Summary $</a>
                                        @endif
                                        @if(\App\UserPrivilige::get_single_privilige(auth()->id(),'summary2') == true)
                                            <a class="nav-link" href="{{ url('summary2') }}">Summary PKR</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                        </nav>
                    </div>
                    @endif
                    {{--                        <div class="collapse" id="budget" aria-labelledby="headingOne" data-parent="#sidenavAccordion">--}}

                    {{--                            <nav class="sb-sidenav-menu-nested nav" id="sidenavAccordionPages_budget">--}}

                    {{--                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#budget_sub"--}}
                    {{--                                   aria-expanded="false" aria-controls="pagesCollapseAuth">--}}
                    {{--                                    Budget by sub-Category--}}
                    {{--                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>--}}
                    {{--                                </a>--}}
                    {{--                                <div class="collapse" id="budget_sub" aria-labelledby="headingOne"--}}
                    {{--                                     data-parent="#sidenavAccordionPages_budget">--}}
                    {{--                                    <nav class="sb-sidenav-menu-nested nav">--}}

                    {{--                                    </nav>--}}
                    {{--                                </div>--}}
                    {{--                                --}}{{--                                <a class="nav-link" href="{{ url('show_subcategory_budget') }}">Show Budget by--}}
                    {{--                                --}}{{--                                    sub-Category</a>--}}



                    {{--                                <!-- Type -->--}}
                    {{--                            </nav>--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}

                    @if(auth()->user()->id == 81 || auth()->user()->id == 1)
                    <!-- Budget System -->
                        <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#priviliges"
                           aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Priviliges System
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="priviliges" aria-labelledby="headingOne"
                             data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion">
                                <!-- Inventory Forms -->
                                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                   data-target="#privilige_forms" aria-expanded="false"
                                   aria-controls="pagesCollapseAuth">
                                    Forms
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="privilige_forms" aria-labelledby="headingOne"
                                     data-parent="#priviliges">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="{{ url('assign_priviliges_view') }}">Assign
                                            Priviliges</a>
                                        <a class="nav-link" href="{{ url('replicate_priviliges') }}">Replicate
                                            Priviliges</a>
                                        <a class="nav-link" href="{{ url('assign_priviliges') }}">Assign
                                            Priviliges By Group</a>
                                        <a class="nav-link" href="{{ url('show_priviliges_by_user') }}">List
                                            Priviliges</a>
                                    </nav>
                                </div>

                                {{--                                <a class="nav-link collapsed" href="#" data-toggle="collapse"--}}
                                {{--                                   data-target="#priviliges_reports" aria-expanded="false"--}}
                                {{--                                   aria-controls="pagesCollapseAuth">--}}
                                {{--                                    Reports--}}
                                {{--                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>--}}
                                {{--                                </a>--}}
                                {{--                                <div class="collapse" id="priviliges_reports" aria-labelledby="headingOne"--}}
                                {{--                                     data-parent="#priviliges">--}}
                                {{--                                    <nav class="sb-sidenav-menu-nested nav">--}}
                                {{--                                        <a class="nav-link" href="{{ url('show_subcategory_budget') }}">List Priviliges</a>--}}
                                {{--                                    </nav>--}}
                                {{--                                </div>--}}
                            </nav>
                        </div>


                        {{--                        <div class="collapse" id="budget" aria-labelledby="headingOne" data-parent="#sidenavAccordion">--}}

                        {{--                            <nav class="sb-sidenav-menu-nested nav" id="sidenavAccordionPages_budget">--}}

                        {{--                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#budget_sub"--}}
                        {{--                                   aria-expanded="false" aria-controls="pagesCollapseAuth">--}}
                        {{--                                    Budget by sub-Category--}}
                        {{--                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>--}}
                        {{--                                </a>--}}
                        {{--                                <div class="collapse" id="budget_sub" aria-labelledby="headingOne"--}}
                        {{--                                     data-parent="#sidenavAccordionPages_budget">--}}
                        {{--                                    <nav class="sb-sidenav-menu-nested nav">--}}

                        {{--                                    </nav>--}}
                        {{--                                </div>--}}
                        {{--                                --}}{{--                                <a class="nav-link" href="{{ url('show_subcategory_budget') }}">Show Budget by--}}
                        {{--                                --}}{{--                                    sub-Category</a>--}}



                        {{--                                <!-- Type -->--}}
                        {{--                            </nav>--}}
                        {{--                        </div>--}}
                    @endif
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>

                {{ Auth::user()->name }}
            </div>
        </nav>
    </div>
    @yield("content")
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="{{ asset('assets/assets/demo/chart-area-demo.js') }}"></script>--}}
{{--<script src="{{ asset('assets/assets/demo/chart-bar-demo.js') }}"></script>--}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/assets/demo/datatables-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
</body>
</html>
@yield("page-script")
{{--<script type="text/javascript">--}}
{{--    var counter = 1;--}}
{{--    var category_data;--}}
{{--    var make_data;--}}
{{--    var data;--}}
{{--    var data_make;--}}
{{--    var data_sn;--}}
{{--    var data_sn_rep;--}}
{{--    var t;--}}
{{--    $(document).ready(function () {--}}

{{--        var multipleCancelButton = new Choices('#pre_year', {--}}
{{--            removeItemButton: true,--}}
{{--            maxItemCount: 2,--}}
{{--            searchResultLimit: 15,--}}
{{--            renderChoiceLimit: 15--}}
{{--        });--}}

{{--        var multipleCancelButton_branch = new Choices('#branches', {--}}
{{--            removeItemButton: true,--}}
{{--            maxItemCount: 1000,--}}
{{--            searchResultLimit: 1000,--}}
{{--            renderChoiceLimit: 1000--}}
{{--        });--}}

{{--        var multipleCancelButton_branch = new Choices('#edit_branches', {--}}
{{--            removeItemButton: true,--}}
{{--            maxItemCount: 1000,--}}
{{--            searchResultLimit: 1000,--}}
{{--            renderChoiceLimit: 1000--}}
{{--        });--}}

{{--        $('#pre_year').change(function (e) {--}}
{{--            e.preventDefault();--}}
{{--            var from = $("#pre_year").val();--}}
{{--            $('input[name="prev_year_id"]').val(from);--}}
{{--            console.log(from + " values of dropdown");--}}
{{--        });--}}

{{--        // show budget category--}}
{{--        // $('#inv_dataTable').DataTable({--}}
{{--        //     paging: false,--}}
{{--        //     searching: true,--}}
{{--        //     info: false,--}}
{{--        //     scrollX: false,--}}
{{--        //     scrollCollapse: true--}}
{{--        // });--}}


{{--        $('#dataTable_opex').DataTable({--}}
{{--            paging: true,--}}
{{--            searching: true,--}}
{{--            "info": false,--}}
{{--            scrollX: false,--}}
{{--            scrollCollapse: true,--}}
{{--            "footerCallback": function (row, data, start, end, display) {--}}
{{--                var api = this.api();--}}
{{--                nb_cols = api.columns().nodes().length;--}}
{{--                var j = 4;--}}
{{--                var numbers = [2, 3, 4, 5, 6];--}}
{{--                for (let i = 0; i < numbers.length; i++) {--}}
{{--                    var pageTotal = api--}}
{{--                        .column(numbers[i], {page: 'current'})--}}
{{--                        .data()--}}
{{--                        .reduce(function (a, b) {--}}
{{--                            var new_a;--}}
{{--                            var new_b;--}}
{{--                            if (numbers[i] == 3) {--}}
{{--                                return Number(a) + Number(b)--}}
{{--                            } else {--}}
{{--                                return Number(Number(a) + Number(b)).toFixed(2);--}}
{{--                            }--}}
{{--                        }, 0);--}}
{{--                    // Update footer--}}
{{--                    $(api.column(numbers[i]).footer()).html(pageTotal);--}}

{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--        $('#capex_datatable').DataTable({--}}
{{--            paging: true,--}}
{{--            searching: true,--}}
{{--            "info": false,--}}
{{--            scrollX: false,--}}
{{--            scrollCollapse: true,--}}
{{--            "footerCallback": function (row, data, start, end, display) {--}}
{{--                var api = this.api();--}}
{{--                nb_cols = api.columns().nodes().length;--}}
{{--                var j = 4;--}}
{{--                var numbers = [5, 6, 7, 8, 9, 10, 11];--}}
{{--                for (let i = 0; i < numbers.length; i++) {--}}
{{--                    var pageTotal = api--}}
{{--                        .column(numbers[i], {page: 'current'})--}}
{{--                        .data()--}}
{{--                        .reduce(function (a, b) {--}}
{{--                            var new_a;--}}
{{--                            var new_b;--}}
{{--                            if (numbers[i] == 5 || numbers[i] == 10 || numbers[i] == 11) {--}}
{{--                                return Number(a) + Number(b)--}}
{{--                            } else {--}}
{{--                                return Number(Number(a) + Number(b)).toFixed(2);--}}
{{--                            }--}}
{{--                        }, 0);--}}
{{--                    // Update footer--}}
{{--                    $(api.column(numbers[i]).footer()).html(pageTotal);--}}

{{--                }--}}
{{--            }--}}
{{--        });--}}

{{--        //End show budget category--}}

{{--        var invoice_category = $('.category-' + counter); //+counter+""--}}
{{--        invoice_category.empty();--}}
{{--        invoice_category.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--        jQuery.ajaxSetup({async: false});--}}
{{--        $.get("{{ url('get_category') }}", function (category_data) {--}}
{{--            data = category_data--}}
{{--        });--}}

{{--        var invoice_make = $('#make' + counter);--}}
{{--        invoice_make.empty();--}}
{{--        invoice_make.append('<option value=0 class="o1">Select Make here</option>');--}}
{{--        jQuery.ajaxSetup({async: false});--}}
{{--        $.get("{{ url('get_make') }}", function (make_data) {--}}
{{--            data_make = make_data--}}
{{--            // $.each(data, function (i, item) {--}}
{{--            //     $('.make').append($('<option>', {--}}
{{--            //         value: item.id,--}}
{{--            //         text: item.make_name--}}
{{--            //     }));--}}
{{--            // });--}}
{{--        });--}}
{{--        // var t = t;--}}
{{--        var category_data = category_data;--}}
{{--        var make_data = make_data;--}}
{{--        let link = '<?php echo \DB::table('links')->get()[0]->url;?>';--}}

{{--        // "url": "https://devinv.efulife.com/branchdataall.php?uid=1",--}}
{{--        // "url": "https://cloud.efulife.com:8080/devinv/empdata.php?uid="+emp_code,--}}
{{--        // "url": "https://devinv.efulife.com/deptdataall.php?uid=1",--}}
{{--        $("#show").click(function () {--}}
{{--            $("#form").attr({method: 'GET', action: '{{ url("filter_inventory") }}'})--}}
{{--            $("#form").submit();--}}
{{--        });--}}
{{--        $("#transfer").click(function () {--}}
{{--            $("#form").attr({method: 'POST', action: '{{ url("transfer") }}'})--}}
{{--            $("#form").submit();--}}
{{--        });--}}

{{--        $("#rshow").click(function () {--}}
{{--            $("#rform").attr({method: 'GET', action: '{{ url("filter_return") }}'})--}}
{{--            $("#rform").submit();--}}
{{--        });--}}
{{--        $("#return").click(function () {--}}
{{--            $("#rform").attr({method: 'POST', action: '{{ url("return") }}'})--}}
{{--            $("#rform").submit();--}}
{{--        });--}}

{{--        $("#addRow").click(function () {--}}
{{--            var invoice_category = $('.category-' + counter); //+counter+""--}}
{{--            invoice_category.empty();--}}
{{--            invoice_category.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--            $.get("{{ url('get_category') }}", function (category_data) {--}}
{{--                // category_data = data--}}
{{--                $.each(category_data, function (i, item) {--}}
{{--                    $('.category-' + counter).append($('<option>', {--}}
{{--                        value: item.id,--}}
{{--                        text: item.category_name--}}
{{--                    }));--}}
{{--                });--}}
{{--            });--}}

{{--            var invoice_make = $('#make');--}}
{{--            invoice_make.empty();--}}
{{--            invoice_make.append('<option value=0 class="o1">Select Make here</option>');--}}
{{--            $.get("{{ url('get_make') }}", function (data) {--}}
{{--                make_data = data--}}
{{--                $.each(data, function (i, item) {--}}
{{--                    $('.make').append($('<option>', {--}}
{{--                        value: item.id,--}}
{{--                        text: item.make_name--}}
{{--                    }));--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--        t = $('#invoice_dataTable').DataTable();--}}
{{--        // Add Invoice Scripts--}}
{{--        $('#addRow').on('click', function () {--}}
{{--            var category_row = "<select class='custom-select' style='width: auto;' name='category_id[]' onchange='get_subcat(this)' id='category-" + counter + "'>";--}}
{{--            category_data = data--}}
{{--            $.each(category_data, function (i, item) {--}}
{{--                category_row += "<option value='" + item.id + "'>" + item.category_name + "</option>";--}}
{{--            });--}}
{{--            category_row += "</select>";--}}

{{--            var make_row = "<select class='custom-select' style='width: auto;' name='make_id[]' onchange='get_model(this)' id='make-" + counter + "'>";--}}
{{--            make_data = data_make--}}
{{--            $.each(data_make, function (i, item) {--}}
{{--                make_row += "<option value='" + item.id + "'>" + item.make_name + "</option>";--}}
{{--            });--}}
{{--            make_row += "</select>";--}}
{{--            t.row.add([--}}
{{--                '<div class="form-group"  required id="defaultCheck" style="margin-left:30px;padding:10px 20px 20px 20px; width: auto; height: 10px;"><input type="checkbox" class="form-check-input filled-in" id="filledInCheckbox"></div>',--}}
{{--                '<div class="form-group" style="width: 100%;">' + category_row + '</div>',--}}
{{--                '<div class="form-group"><select required class="custom-select subcategory' + counter + '" style="width: auto;" id="subcategory' + counter + '" name="subcategory_id[]"> <option value=0>Select Sub Category here </option></select></div>',--}}
{{--                // '<div class="form-group">'+make_row+'</div>',--}}
{{--                // '<div class="form-group"><select class="custom-select model'+counter+'" style="width: auto;" id="model'+counter+'" name="model_id[]"> <option value=0>Select Model here</option></select></div>',--}}
{{--                '<div class="form-group"><input  required class="form-control py-2 t_seperator item_price" style="width: auto;" id="price" name="item_price[]" type="text" placeholder="Enter Item Price here"/></div>',--}}
{{--                '<div class="form-group"><input required class="form-control py-2" id="tax" name="tax[]" style="width: auto;" type="text" placeholder="Enter Tax(%) here"/></div>',--}}
{{--                '<div class="form-group"><input required class="form-control py-2 t_seperator" id="rate" style="width: auto;" name="dollar_rate[]" type="text" placeholder="Enter dollar rate here"/></div>',--}}
{{--                '<div class="form-group"><input required class="form-control py-2 purchase_date" style="width: auto;" id="contract_issue_date" name="contract_issue_date[]" type="date"placeholder="Enter Contract Issue date here"/></div>',--}}
{{--                '<div class="form-group"><input required class="form-control py-2 purchase_date" style="width: auto;" id="contract_end_date" name="contract_end_date[]" type="date"placeholder="Enter Contract End date here"/></div>',--}}
{{--                // '<div class="form-group"><input required class="form-control py-2 Warrenty calculatewarrantyend'+counter+'" style="width: auto;" id="Warrenty" onkeyup="calculate_warranty(this)" e name="warrenty_period[]" type="number"placeholder="Enter Warrenty Period here"/></div>',--}}
{{--                // '<div class="form-group"><input required class="form-control py-2 warrentyend'+counter+'" style="width: auto;" id="warrentyend'+counter+'" name="warranty_end[]" type="text" placeholder="Enter Warranty End here" readonly/></div>',--}}
{{--            ]).draw();--}}
{{--            t.row(counter).draw();--}}
{{--            counter++;--}}
{{--        });--}}

{{--        // Automatically add a first row of data--}}
{{--        $('#addRow').click();--}}

{{--        $('#reload_tbl').click(function () {--}}
{{--            t.draw();--}}
{{--        });--}}

{{--        $('#invoice_dataTable tbody').on('click', 'tr', function () {--}}
{{--            $(this).toggleClass('selected');--}}
{{--        });--}}

{{--        $('#deleteRow').click(function () {--}}
{{--            // t.row('.selected').remove().draw(false);--}}
{{--            var rows = t--}}
{{--                .rows('.selected')--}}
{{--                .remove()--}}
{{--                .draw();--}}
{{--        });--}}

{{--        // END--}}
{{--        $('#emp_code').keydown(function (e) {--}}
{{--            var code = e.keyCode || e.which;--}}
{{--            if (code === 9 || code === 13) {--}}
{{--                e.preventDefault();--}}
{{--                var emp_code = $('#emp_code').val();--}}

{{--                var settings = {--}}

{{--                    "url": link + "empdata.php?uid=" + emp_code,--}}
{{--                    "method": "GET",--}}
{{--                    "timeout": 0,--}}
{{--                };--}}
{{--                $.ajax(settings).done(function (response) {--}}
{{--                    if (response.Login != null) {--}}
{{--                        var res = response.Login[0];--}}
{{--                        $('#name').val(res.EMPLOYEE_NAME);--}}
{{--                        $('#designation').val(res.DESIGNATION);--}}
{{--                        $('#department').val(res.DEPARTMENT);--}}
{{--                        $('#dept_id').val(res.DEPARTMENT_ID);--}}
{{--                        $('.location').val(res.LOCATION);--}}
{{--                        $('#hod').val(res.HOD_NAME);--}}
{{--                        $('#email').val(res.EMPLOYEE_EMAIL);--}}
{{--                        $('#status').val(res.EMPLOYEE_STATUS);--}}
{{--                    } else {--}}
{{--                        alert('Entered employee code does not exists!');--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--        $('#contract_end_date').change(function (e) {--}}
{{--            e.preventDefault();--}}
{{--            var from = $("#contract_issue_date").val();--}}
{{--            var to = $("#contract_end_date").val();--}}

{{--            if (Date.parse(from) > Date.parse(to)) {--}}
{{--                $('#btn_add_inventory_invoice').hide();--}}
{{--                alert("End date must be greater");--}}
{{--            } else {--}}
{{--                $('#btn_add_inventory_invoice').show();--}}
{{--            }--}}

{{--        });--}}

{{--        $('#tax').keyup(function (e) {--}}
{{--            e.preventDefault();--}}
{{--            // var item_price_after_tax = 0;--}}
{{--            var price = $('.item_price').val();--}}
{{--            var itemprice = price.replace(/,/g, "");--}}
{{--            var tax = $(this).val();--}}

{{--            var item_price_after_tax = parseInt(itemprice * (tax / 100)) + parseInt(itemprice)--}}
{{--            $('#item_price_tax').val(item_price_after_tax.toString().replace(/,/g, ''));--}}
{{--        });--}}

{{--        $('#tax').change(function (e) {--}}
{{--            e.preventDefault();--}}
{{--            // var item_price_after_tax = 0;--}}
{{--            var price = $('.item_price').val();--}}
{{--            var itemprice = price.replace(/,/g, "");--}}
{{--            var tax = $(this).val();--}}

{{--            var item_price_after_tax = parseInt(itemprice * (tax / 100)) + parseInt(itemprice)--}}
{{--            $('#item_price_tax').val(item_price_after_tax.toString().replace(/,/g, ''));--}}
{{--        });--}}


{{--        $('#vendor_id').change(function (e) {--}}
{{--            e.preventDefault();--}}
{{--            var category_id = $('.invoice_category_id1').children("option:selected").val()--}}
{{--            var subcategory_id = $('.invoice_subcategory1').children("option:selected").val()--}}
{{--            var type_id = $('#type_id').children("option:selected").val()--}}
{{--            var year_id = $('.invoice_year_id').children("option:selected").val()--}}
{{--            var vendor_id = $(this).children("option:selected").val()--}}
{{--            var settings = {--}}
{{--                "_token": "{{ csrf_token() }}",--}}
{{--                "url": "{{ route('check_vendor_term')}}",--}}
{{--                "method": "POST",--}}
{{--                "timeout": 0,--}}
{{--                "data": {'_token' : '{{ csrf_token() }}',--}}
{{--                    'category_id':category_id,--}}
{{--                    'subcategory_id':subcategory_id,--}}
{{--                    'type_id':type_id,--}}
{{--                    'year_id':year_id,--}}
{{--                    'vendor_id':vendor_id,--}}
{{--                },--}}
{{--            };--}}
{{--            $.ajax(settings).done(function (response) {--}}
{{--                if (response != 0) {--}}
{{--                    var res = response;--}}
{{--                    if (res.code == 200) {--}}
{{--                        alert('Selected Vendor Term exists!');--}}
{{--                    } else {--}}
{{--                        alert('Selected Vendor Term does not exists!');--}}
{{--                    }--}}
{{--                } else {--}}
{{--                    alert('Selecetd Vendor Term does not exists!');--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        $('#type_id').on('change', function () {--}}

{{--            var id = $(this).val();--}}
{{--            var report = $('#year_id').data('reports');--}}
{{--            var years = $('#year_id');--}}
{{--            years.empty();--}}
{{--            if (report == 1) {--}}
{{--                years.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                years.append('<option value=0 class="o1">Select Year here</option>');--}}
{{--            }--}}
{{--            console.log("{{url()->current()}}");--}}
{{--            var current_url = "{{url()->current()}}";--}}
{{--            var url_id = current_url.substring(current_url.lastIndexOf('/') + 1)--}}
{{--            var new_url--}}
{{--            if ("{{url()->current()}}" == "http://inventory.efulife.online/add_sla" || "{{url()->current()}}" == "http://inventory.efulife.online/sla/" + url_id) {--}}
{{--                new_url = "{{  url('get_year_by_type') }}/" + id;--}}
{{--            }--}}
{{--            if ("{{url()->current()}}" == "http://inventory.efulife.online/add_sla_log" || "{{url()->current()}}" == "http://inventory.efulife.online/slalog/" + url_id) {--}}
{{--                new_url = "{{  url('get_year_by_type_SLA') }}/" + id--}}
{{--            } else {--}}
{{--                new_url = "{{  url('get_year_by_type') }}/" + id--}}
{{--            }--}}
{{--            // console.log(new_url);--}}
{{--            $.get(new_url--}}
{{--                , function (data) {--}}
{{--                    $.each(data, function (i, item) {--}}
{{--                        $('#year_id').append($('<option>', {--}}
{{--                            value: item.id,--}}
{{--                            text: item.year--}}
{{--                        }));--}}
{{--                    });--}}

{{--                });--}}
{{--        });--}}


{{--        $('.year_id').on('change', function () {--}}
{{--            var type_id = $('#type_id').val();--}}
{{--            var id = $(this).val();--}}
{{--            var report = $('.subcategory1').data('reports');--}}
{{--            var subcategory1 = $('.subcategory1');--}}
{{--            subcategory1.empty();--}}
{{--            if (report == 1) {--}}
{{--                subcategory1.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                subcategory1.append('<option value=0 class="o1">Select Sub Category here</option>');--}}
{{--            }--}}
{{--            var current_url = "{{url()->current()}}";--}}
{{--            var url_id = current_url.substring(current_url.lastIndexOf('/') + 1)--}}
{{--            if ("{{url()->current()}}" == "http://inventory.efulife.online/add_sla" || "{{url()->current()}}" == "http://inventory.efulife.online/sla/" + url_id) {--}}
{{--                var my_url = "{{  url('get_subcat_by_year') }}/" + id + "/" + type_id;--}}
{{--                console.log('myurl: ' + my_url);--}}
{{--            } else {--}}
{{--                var my_url = "{{  url('get_subcat_by_year_SLA') }}/" + id + "/" + type_id;--}}
{{--                console.log('myurl: ' + my_url);--}}
{{--            }--}}
{{--            $.get(--}}
{{--                my_url--}}
{{--                , function (data) {--}}
{{--                    $.each(data, function (i, item) {--}}
{{--                        $('.subcategory1').append($('<option>', {--}}
{{--                            value: item.id,--}}
{{--                            text: item.sub_cat_name--}}
{{--                        }));--}}
{{--                    });--}}

{{--                });--}}
{{--        });--}}

{{--        $('.invoice_year_id').on('change', function () {--}}
{{--            var type_id = $('#type_id').val();--}}
{{--            var id = $(this).val();--}}
{{--            var report = $('.invoice_category_id1').data('reports');--}}
{{--            var invoice_category_id1 = $('.invoice_category_id1');--}}
{{--            invoice_category_id1.empty();--}}
{{--            if (report == 1) {--}}
{{--                invoice_category_id1.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                invoice_category_id1.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--            }--}}
{{--            $.get("{{ url('get_cat_by_year') }}/" + id + "/" + type_id, function (data) {--}}
{{--                $.each(data, function (i, item) {--}}
{{--                    $('.invoice_category_id1').append($('<option>', {--}}
{{--                        value: item.id,--}}
{{--                        text: item.category_name--}}
{{--                    }));--}}
{{--                });--}}

{{--            });--}}
{{--        });--}}

{{--        $('.issue_product_sn').on('change', function () {--}}
{{--            var id = $(this).val();--}}
{{--            $.get("{{ url('get_make_model_by_psn') }}/" + id, function (data) {--}}
{{--                $('#make_id').attr("value", data.make_name);--}}
{{--                $('#model_id').attr("value", data.model_name);--}}
{{--                $('#issued_to').attr("value", data.issued_to ? data.issued_to : null);--}}
{{--            });--}}
{{--        });--}}

{{--        $('.replace_product_sn').on('change', function () {--}}
{{--            var id = $(this).val();--}}
{{--            $.get("{{ url('get_make_model_by_psn') }}/" + id, function (data) {--}}
{{--                $('#replace_product_make_id').val(data.make_name);--}}
{{--                $('#replace_product_model_id').val(data.model_name);--}}
{{--            });--}}
{{--        });--}}

{{--        $('.category_id1').on('change', function () {--}}
{{--            var year_id = $('.invoice_year_id').val();--}}
{{--            var type_id = $('#type_id').val();--}}
{{--            var id = $(this).val();--}}
{{--            var report = $('.subcategory1').data('reports');--}}
{{--            var subcategory1 = $('.subcategory1');--}}
{{--            subcategory1.empty();--}}
{{--            if (report == 1) {--}}
{{--                subcategory1.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                subcategory1.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--            }--}}
{{--            $.get("{{ url('get_subcat_by_cat_year') }}/" + id + "/" + type_id + "/" + year_id, function (data) {--}}
{{--                ;--}}
{{--                $.each(data, function (i, item) {--}}
{{--                    $('.subcategory1').append($('<option>', {--}}
{{--                        value: item.id,--}}
{{--                        text: item.sub_cat_name--}}
{{--                    }));--}}
{{--                });--}}

{{--            });--}}
{{--        });--}}

{{--        $('.invoice_category_id1').on('change', function () {--}}
{{--            var year_id = $('.invoice_year_id').val();--}}
{{--            var type_id = $('#type_id').val();--}}
{{--            var id = $(this).val();--}}
{{--            var report = $('.invoice_subcategory1').data('reports');--}}
{{--            var invoice_subcategory1 = $('.invoice_subcategory1');--}}
{{--            invoice_subcategory1.empty();--}}
{{--            if (report == 1) {--}}
{{--                invoice_subcategory1.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                invoice_subcategory1.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--            }--}}
{{--            $.get("{{ url('get_subcat_by_cat_year') }}/" + id + "/" + type_id + "/" + year_id, function (data) {--}}
{{--                ;--}}
{{--                $.each(data, function (i, item) {--}}
{{--                    $('.invoice_subcategory1').append($('<option>', {--}}
{{--                        value: item.id,--}}
{{--                        text: item.sub_cat_name--}}
{{--                    }));--}}
{{--                });--}}

{{--            });--}}
{{--        });--}}

{{--        $('.subcategory1').on('change', function () {--}}
{{--            // alert('here');--}}
{{--            var type_id = $('#type_id').val();--}}
{{--            var year_id = $('.year_id').val();--}}
{{--            var sub_cat_id = $('.subcategory1').val();--}}

{{--            var id = $(this).val();--}}
{{--            var report = $('.vendor_id_sla').data('reports');--}}
{{--            var vendor_id = $('.vendor_id_sla');--}}
{{--            vendor_id.empty();--}}
{{--            if (report == 1) {--}}
{{--                vendor_id.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                vendor_id.append('<option value=0 class="o1">Select Sub Category here</option>');--}}
{{--            }--}}
{{--            $.get("{{ url('get_vendor_by_sub_cat_year') }}/" + id + "/" + type_id + "/" + year_id, function (data) {--}}
{{--                $('#vendor_id_sla').val(data[0].id);--}}
{{--                $('#vendor_id_sla_name').val(data[0].vendor_name);--}}
{{--                // $.each(data, function (i, item) {--}}
{{--                //     $('.vendor_id_sla').append($('<option>', {--}}
{{--                //         value: item.id,--}}
{{--                //         text: item.vendor_name--}}
{{--                //     }));--}}
{{--                // });--}}
{{--                $.get("{{ url('get_sla_total_cost') }}/" + type_id + "/" + year_id + "/" + sub_cat_id , function (data) {--}}
{{--                    // console.log('sla_ '+data.consumed_sla_cost);--}}
{{--                    if(data.consumed_sla_cost != null){--}}
{{--                        var rem = parseInt(data.current_sla_cost) - parseInt(data.consumed_sla_cost)--}}
{{--                        var num_parts = rem.toString().split(".");--}}
{{--                        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");--}}
{{--                        $('#current_sla_cost_log').val(num_parts.join("."));--}}
{{--                        // $('#current_sla_cost_log').val(rem)--}}
{{--                    }else{--}}
{{--                        var rem_cost =data.current_sla_cost;--}}
{{--                        var num_parts = rem_cost.toString().split(".");--}}
{{--                        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");--}}
{{--                        $('#current_sla_cost_log').val(num_parts.join("."));--}}
{{--                        // $('#current_sla_cost_log').val(data.current_sla_cost)--}}
{{--                    }--}}
{{--                });--}}

{{--            });--}}
{{--        });--}}

{{--        $('#vendor_id_sla').on('change', function () {--}}
{{--            var type_id = $('#type_id').val();--}}
{{--            var year_id = $('.year_id').val();--}}
{{--            var sub_cat_id = $('.subcategory1').val();--}}
{{--            // var vendor_id = $(this).val();--}}
{{--            // console.log('year '+ year_id + 'and cat ' + type_id + 'and- vendor' + vendor_id +' and subcat'+ sub_cat_id);--}}

{{--            $.get("{{ url('get_sla_total_cost') }}/" + type_id + "/" + year_id + "/" + sub_cat_id , function (data) {--}}
{{--                // console.log('sla_ '+data.consumed_sla_cost);--}}
{{--                if(data.consumed_sla_cost != null){--}}
{{--                    var rem = parseInt(data.current_sla_cost) - parseInt(data.consumed_sla_cost)--}}
{{--                    var num_parts = rem.toString().split(".");--}}
{{--                    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");--}}
{{--                    $('#current_sla_cost_log').val(num_parts.join("."));--}}
{{--                    // $('#current_sla_cost_log').val(rem)--}}
{{--                }else{--}}
{{--                    var rem_cost =data.current_sla_cost;--}}
{{--                    var num_parts = rem_cost.toString().split(".");--}}
{{--                    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");--}}
{{--                    $('#current_sla_cost_log').val(num_parts.join("."));--}}
{{--                    // $('#current_sla_cost_log').val(data.current_sla_cost)--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}


{{--        $('#emp_no').keydown(function (e) {--}}
{{--            var code = e.keyCode || e.which;--}}
{{--            if (code === 9 || code === 13) {--}}
{{--                e.preventDefault();--}}
{{--                var emp_no = $('#emp_no').val();--}}
{{--                var settings = {--}}
{{--                    "url": "{{ url('get_employee') }}/" + emp_no,--}}
{{--                    "method": "GET",--}}
{{--                    "timeout": 0,--}}
{{--                };--}}
{{--                $.ajax(settings).done(function (response) {--}}
{{--                    if (response != 0) {--}}
{{--                        var settings_issue_form = {--}}
{{--                            "url": "{{ url('get_employee_branch') }}/" + emp_no,--}}
{{--                            "method": "GET",--}}
{{--                            "timeout": 0,--}}
{{--                        };--}}
{{--                        var branch = $('#branches_issue');--}}
{{--                        branch.empty();--}}
{{--                        branch.append('<option value=0 class="o1">Select Branch here</option>');--}}
{{--                        var res = response;--}}
{{--                        $('#name').val(res.name);--}}
{{--                        $('#designation').val(res.designation);--}}
{{--                        $('#department').val(res.department);--}}
{{--                        $('#dept_id').val(res.dept_id);--}}
{{--                        $('.location').val(res.location);--}}
{{--                        $('#hod').val(res.hod);--}}
{{--                        $('#email').val(res.email);--}}
{{--                        $('#status').val(res.status);--}}
{{--                        $.ajax(settings_issue_form).done(function (response) {--}}
{{--                            if (response != 0) {--}}
{{--                                var res = response;--}}
{{--                                var branch = $('#branches_issue');--}}
{{--                                $.each(res, function (index, value) {--}}
{{--                                    branch.append(--}}
{{--                                        $('<option></option>').val(value.branch_id).html(value.branch_name)--}}
{{--                                    );--}}
{{--                                });--}}
{{--                            }--}}
{{--                        });--}}
{{--                    } else {--}}
{{--                        var branch = $('#branches_issue');--}}
{{--                        branch.empty();--}}
{{--                        alert('Entered employee code does not exists!');--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--        var settings = {--}}
{{--            "url": link + "branchdataall.php?uid=1",--}}
{{--            "method": "GET",--}}
{{--            "timeout": 0,--}}
{{--        };--}}
{{--        $.ajax(settings).done(function (response) {--}}
{{--            if (response.Login != null) {--}}
{{--                var res = response.Login;--}}
{{--                var branch = $('#branches');--}}
{{--                $.each(res, function (index, value) {--}}
{{--                    branch.append(--}}
{{--                        $('<option selected></option>').val(value.BRANCH_ID).html(value.BRANCH_NAME)--}}
{{--                    );--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--        $('#branches').change(function () {--}}
{{--            const selected = document.querySelectorAll('#branches option:checked');--}}
{{--            const values = Array.from(selected).map(el => el.text);--}}
{{--            $('#branch').val(values)--}}
{{--            // $('#branch').val($("#branches option:selected").text());--}}
{{--        });--}}

{{--        $('#edit_branches').change(function () {--}}
{{--            const selected = document.querySelectorAll('#edit_branches option:checked');--}}
{{--            const values = Array.from(selected).map(el => el.text);--}}
{{--            $('#branch').val(values)--}}
{{--            // $('#branch').val($("#branches option:selected").text());--}}
{{--        });--}}

{{--        // var settings_issue = {--}}
{{--        //     "url": link + "branchdataall.php?uid=1",--}}
{{--        //     "method": "GET",--}}
{{--        //     "timeout": 0,--}}
{{--        // };--}}
{{--        // $.ajax(settings_issue).done(function (response) {--}}
{{--        //     if (response.Login != null) {--}}
{{--        //         var res = response.Login;--}}
{{--        //         var branch = $('#branches_issue');--}}
{{--        //         $.each(res, function (index, value) {--}}
{{--        //             branch.append(--}}
{{--        //                 $('<option></option>').val(value.BRANCH_ID).html(value.BRANCH_NAME)--}}
{{--        //             );--}}
{{--        //         });--}}
{{--        //     }--}}
{{--        // });--}}

{{--        $('#branches_issue').change(function () {--}}
{{--            $('#branches_issue_hidden').val($("#branches_issue option:selected").val());--}}
{{--            $('#branche_name_issue_hidden').val($("#branches_issue option:selected").text());--}}

{{--            const selected = document.querySelectorAll('#branches_issue option:checked');--}}
{{--            const values = Array.from(selected).map(el => el.text);--}}
{{--            $('#branch_name').val(values)--}}
{{--        });--}}

{{--        $('#emp_dept_dropdown').change(function () {--}}
{{--            $('#branches_issue_hidden').val($("#emp_dept_dropdown option:selected").val());--}}
{{--            $('#branche_name_issue_hidden').val($("#emp_dept_dropdown option:selected").text());--}}
{{--            $('#dept_id_return').val($("#emp_dept_dropdown option:selected").val());--}}
{{--        });--}}

{{--        $(".make").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            $.get("{{ url('model_by_make') }}/" + id, function (data) {--}}

{{--                var model = $('.model');--}}
{{--                model.empty();--}}
{{--                model.append('<option value=0 class="o1">Select Model here</option>');--}}
{{--                $.each(data, function (index, value) {--}}
{{--                    model.append(--}}
{{--                        $('<option></option>').val(value.id).html(value.model_name)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--        $(".category").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            var report = $('.subcategory').data('reports');--}}
{{--            var subcategory = $('.subcategory');--}}
{{--            subcategory.empty();--}}
{{--            if (report == 1) {--}}
{{--                subcategory.append('<option value="" class="o1">All</option>');--}}
{{--            } else {--}}
{{--                subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');--}}
{{--            }--}}
{{--            $.get("{{ url('subcat_by_category') }}/" + id, function (data) {--}}

{{--                $.each(data, function (index, value) {--}}
{{--                    subcategory.append(--}}
{{--                        $('<option></option>').val(value.id).html(value.sub_cat_name)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--        $("#category").on("change",function(){--}}
{{--            var id = $(this).val();--}}
{{--            var report = $('.subcategory').data('reports');--}}
{{--            var subcategory = $('.subcategory');--}}
{{--            subcategory.empty();--}}
{{--            if(report == 1){--}}
{{--                subcategory.append('<option value="" class="o1">All</option>');--}}
{{--            }--}}
{{--            else{--}}
{{--                subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');--}}
{{--            }--}}
{{--            $.get("{{ url('subcat_by_category') }}/"+id, function(data){--}}

{{--                $.each( data, function(index, value){--}}
{{--                    subcategory.append(--}}
{{--                        $('<option></option>').val(value.id).html(value.sub_cat_name)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}


{{--        var settings = {--}}
{{--            "url": link + "deptdataall.php?uid=1",--}}
{{--            "method": "GET",--}}
{{--            "timeout": 0,--}}
{{--        };--}}
{{--        $.ajax(settings).done(function (response) {--}}
{{--            if (response.Login != null) {--}}
{{--                var res = response.Login;--}}
{{--                var dept_id = $('#dept_id');--}}
{{--                $.each(res, function (index, value) {--}}
{{--                    dept_id.append(--}}
{{--                        $('<option></option>').val(value.DEPARTMENT_ID).html(value.DEPARTMENT)--}}
{{--                    );--}}
{{--                });--}}
{{--                var from_dept = $('#from_dept');--}}
{{--                $.each(res, function (index, value) {--}}
{{--                    from_dept.append(--}}
{{--                        $('<option></option>').val(value.DEPARTMENT_ID).html(value.DEPARTMENT)--}}
{{--                    );--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}
{{--        $('#dept_id').change(function () {--}}
{{--            var dept_name = $('#dept').val($("#dept_id option:selected").text());--}}
{{--            $('#swap_dept_to_name').val($("#dept_id option:selected").text());--}}

{{--        });--}}


{{--        $("#year").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            $.get("{{ url('pkr_by_year') }}/" + id, function (data) {--}}
{{--                var value = data.pkr_val;--}}
{{--                $('#pkr').val(value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));--}}
{{--            });--}}
{{--        });--}}

{{--        $('#u_dollar').keyup(function () {--}}
{{--            var u_dollar = $(this).val();--}}
{{--            var qty = $('#qty').val();--}}
{{--            var p = $('#pkr').val();--}}
{{--            var dollar = u_dollar.replace(",", "");--}}
{{--            var pkr = p.replace(",", "");--}}

{{--            var total_dollar = dollar * qty;--}}
{{--            var total_pkr = total_dollar * pkr;--}}
{{--            //   console.log(qty);--}}
{{--            //   console.log(dollar);--}}
{{--            //   console.log(pkr);--}}
{{--            //   console.log(total_dollar);--}}
{{--            //   console.log(total_pkr);--}}
{{--            $('#t_dollar').val(total_dollar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));--}}
{{--            $('#t_pkr').val(total_pkr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));--}}
{{--        });--}}

{{--        $('#qty').keyup(function () {--}}
{{--            var qty = $(this).val();--}}
{{--            var d = $('#u_dollar').val();--}}
{{--            var p = $('#pkr').val();--}}
{{--            var dollar = d.replace(",", "");--}}
{{--            var pkr = p.replace(",", "");--}}

{{--            var total_dollar = dollar * qty;--}}
{{--            var total_pkr = total_dollar * pkr;--}}
{{--            //   console.log(qty);--}}
{{--            //   console.log(dollar);--}}
{{--            //   console.log(pkr);--}}
{{--            //   console.log(total_dollar);--}}
{{--            //   console.log(total_pkr);--}}
{{--            $('#t_dollar').val(total_dollar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));--}}
{{--            $('#t_pkr').val(total_pkr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));--}}
{{--        });--}}

{{--        $('.pro').keydown(function (e) {--}}
{{--            var code = e.keyCode || e.which;--}}
{{--            if (code === 9 || code === 13) {--}}
{{--                e.preventDefault();--}}

{{--                var product = $(this).val();--}}
{{--                $.get("{{ url('check_product') }}/" + product, function (data) {--}}
{{--                    if (data == 1) {--}}
{{--                        $(".pro_msg").text('Already exists!');--}}
{{--                    } else {--}}
{{--                        $(".pro_msg").text('');--}}
{{--                    }--}}
{{--                    //console.log(data);--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--        $(".repair_item").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            $.get("{{ url('get_price') }}/" + id, function (data) {--}}
{{--                $('.a_price').val(data);--}}
{{--            });--}}
{{--        });--}}
{{--        $(".subcategory").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            var repair_item = $('.repair_item');--}}
{{--            repair_item.empty();--}}
{{--            repair_item.append('<option value=0 class="o1">Select Item here</option>');--}}
{{--            $.get("{{ url('get_inv_items') }}/" + id, function (data) {--}}
{{--                // console.log(data);--}}
{{--                $.each(data, function (index, value) {--}}
{{--                    repair_item.append(--}}
{{--                        $('<option></option>').val(value.id).html(value.product_sn)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--        $(".item_list").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            $.get("{{ url('single_item') }}/" + id, function (data) {--}}
{{--                if (data) {--}}
{{--                    $(".p_date").val(data.purchase_date);--}}
{{--                    if (data.user) {--}}
{{--                        $(".department").val(data.user.department);--}}
{{--                        $(".last_user").val(data.user.name);--}}
{{--                        $(".last_user_id").val(data.user.id);--}}
{{--                    }--}}
{{--                }--}}
{{--                // console.log(data);--}}
{{--            });--}}
{{--        });--}}
{{--        $(".d_subcategory").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            var item_list = $('.item_list');--}}
{{--            item_list.empty();--}}
{{--            item_list.append('<option value=0 class="o1">Select Item here</option>');--}}
{{--            $.get("{{ url('get_unassigned_items') }}/" + id, function (data) {--}}
{{--                $.each(data, function (index, value) {--}}
{{--                    item_list.append(--}}
{{--                        $('<option></option>').val(value.id).html(value.product_sn)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--        $(".dinout_subcategory").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            var action = $(this).data("action");--}}
{{--            var url;--}}
{{--            var item_list = $('.item_list');--}}
{{--            item_list.empty();--}}
{{--            item_list.append('<option value=0 class="o1">Select Item here</option>');--}}

{{--            url = "{{ url('get_assigned_items') }}/" + id + "/" + action;--}}

{{--            $.get(url, function (data) {--}}
{{--                $.each(data, function (index, value) {--}}
{{--                    item_list.append(--}}
{{--                        $('<option></option>').val(value.id).html(value.product_sn)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--        $(".t_seperator").focusout(function () {--}}
{{--            var value = $(this).val();--}}

{{--            var num_parts = value.toString().split(".");--}}
{{--            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");--}}
{{--            $(this).val(num_parts.join("."));--}}
{{--            //alert(num_parts.join("."));--}}
{{--        });--}}

{{--        $(".budget_items").hide();--}}
{{--        // $(".issue_year").on("change",function(){--}}
{{--        //     var year_id = $(this).val();--}}
{{--        //     var inv_id = $('.invid').val();--}}
{{--        // $(".invid").click(function(){--}}
{{--        $("#dataTable").on("click", ".invid", function () {--}}
{{--            var inv_id = $("input[type='radio']:checked").val();--}}
{{--            var dept_id = $('#dept_id').val();--}}
{{--            var dept_id = $('#branches_issue_hidden').val();--}}
{{--            console.log(inv_id + ' : ' + dept_id);--}}
{{--            $.get("{{ url('get_budget_items') }}/" + inv_id + "/" + dept_id, function (data) {--}}
{{--                $(".items_list").empty();--}}
{{--                if (data == "0") {--}}
{{--                    $(".items_list").append(`--}}
{{--                <tr>--}}
{{--                <td style='text-align: center;' colspan='13'>--}}
{{--                Budget not available for selected inventory!--}}
{{--                </td>--}}
{{--                </tr>--}}
{{--                `);--}}
{{--                } else {--}}
{{--                    var i = 1;--}}
{{--                    $.each(data, function (key, value) {--}}
{{--                        $(".items_list").append(`--}}
{{--                <tr>--}}
{{--                <td class='text-align-right'>--}}
{{--                <input type="radio" class="form-check-input" id="budget_id" name='budget_id[]' value="` + value.id + `">--}}
{{--                <label class="form-check-label" for="budget_id">` + i + `</label></td>--}}
{{--                <td>` + value.type.type + `</td>--}}
{{--                <td>` + value.subcategory.sub_cat_name + `</td>--}}
{{--                <td>` + value.department + `</td>--}}
{{--                <td>` + value.description + `</td>--}}
{{--                <td class='text-align-right'>` + value.qty + `</td>--}}
{{--                <td>` + value.unit_price_dollar + `</td>--}}
{{--                <td>` + value.unit_price_pkr + `</td>--}}
{{--                <td>` + (value.unit_price_dollar * value.qty) + `</td>--}}
{{--                <td>` + (value.unit_price_pkr * value.qty) + `</td>--}}
{{--                <td class='text-align-right'>` + value.consumed + `</td>--}}
{{--                <td class='text-align-right'>` + value.remaining + `</td>--}}
{{--                <td>` + value.remarks + `</td>--}}
{{--                </tr>--}}
{{--                `);--}}
{{--                        i++;--}}
{{--                    });--}}
{{--                }--}}
{{--                $(".budget_items").show();--}}
{{--            });--}}
{{--        });--}}

{{--        $(".prompt_delete").on('submit', function (event) {--}}
{{--            var r = confirm("Are you sure You want to delete this item?");--}}
{{--            if (r == false) {--}}
{{--                event.preventDefault();--}}
{{--            }--}}
{{--        });--}}

{{--        $(".calculatewarrantyend").on('blur', function () {--}}
{{--            var p_date = $('.purchase_date').val();--}}
{{--            var warrenty = $('.Warrenty').val();--}}
{{--            if (p_date && warrenty) {--}}
{{--                var w_end = $('.warrentyend');--}}
{{--                var res = p_date.split("-");--}}
{{--                var num = parseInt(res[1]) + parseInt(warrenty);--}}
{{--                var d = new Date(res[0], num, res[2]);--}}
{{--                var year = d.getFullYear();--}}
{{--                var month = d.getMonth();--}}
{{--                var date = d.getDate();--}}
{{--                // var result = month+'/'+date+'/'+year;--}}
{{--                var result = year + '-' + month + '-' + date;--}}
{{--                w_end.val(result);--}}
{{--            }--}}

{{--        });--}}

{{--        $(".deptout").on("change", function () {--}}
{{--            var id = $(this).val();--}}
{{--            var empout = $('.empout');--}}
{{--            empout.empty();--}}
{{--            empout.append('<option value="" class="o1">All</option>');--}}
{{--            $.get("{{ url('employees_by_dept') }}/" + id, function (data) {--}}
{{--                $.each(data, function (index, value) {--}}
{{--                    empout.append(--}}
{{--                        $('<option></option>').val(value.emp_code).html(value.emp_code + ' - ' + value.name)--}}
{{--                    );--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--        $('.filter_budget').change(function () {--}}
{{--            //alert($(this).val());--}}
{{--            var year = $('#year').val();--}}
{{--            var category = $('#category').val();--}}
{{--            var subcategory = $('#subcategory').val();--}}
{{--            var from_dept = $('#from_dept').val();--}}
{{--            if (year && category && subcategory && from_dept) {--}}
{{--                var tbl_td;--}}
{{--                var fields = {--}}
{{--                    "_token": "{{ csrf_token() }}",--}}
{{--                    'year_id': year,--}}
{{--                    'category_id': category,--}}
{{--                    'sub_cat_id': subcategory,--}}
{{--                    'from_dept': from_dept--}}
{{--                }--}}
{{--                $.post("{{ url('get_budget') }}", fields, function (res) {--}}
{{--                    if (res.length > 0) {--}}

{{--                        $.each(res, function (index, value) {--}}
{{--                            console.log('value ' + index + ' :' + value.qty);--}}
{{--                            tbl_td = '<tr><td class="checkbox' + value.id + '" style="text-align:center"><input type="radio" name="radio_budget_id"  value="' + value.id + '" style="text-align:center;" onchange="get_value_radio(this)"/></td><td class="available_qty" style="text-align:center;">' + value.qty + '</td>  <td class="available_con" style="text-align:center;">' + value.consumed + '</td> <td class="available_rem" style="text-align:center;">' + value.remaining + '</td></tr>';--}}
{{--                            $('.available_budget').append(tbl_td);--}}
{{--                            $(".available_budget").show('slow');--}}
{{--                            // $('#filter_budget > tbody:last-child').append('<tr> <td class="available_qty" style="text-align:center;">' + value.qty + '</td>  <td class="available_con" style="text-align:center;">' + value.consumed + '</td> <td class="available_rem" style="text-align:center;">' + value.remaining + '</td>');--}}
{{--                            // $(".available_qty").html(value.qty);--}}
{{--                            // $(".available_con").html(value.consumed);--}}
{{--                            // $(".available_rem").html(value.remaining);--}}
{{--                            // $(".available_budget").show('slow');--}}
{{--                            // $("#qty").attr('max', value.remaining);--}}
{{--                        });--}}

{{--                        // $(".available_qty").html(res.qty);--}}
{{--                        // $(".available_con").html(res.consumed);--}}
{{--                        // $(".available_rem").html(res.remaining);--}}
{{--                        $(".available_budget").show('slow');--}}
{{--                        // $("#qty").attr('max', res.remaining);--}}
{{--                        // $('#from_remarks').val(res[0].remarks);--}}
{{--                        // $('#from_description').val(res.description);--}}
{{--                    } else {--}}
{{--                        $('#available_budget_td').empty()--}}
{{--                        alert("Selected budget does not exists!");--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--        $('.btn_swap').click(function () {--}}
{{--            var year = $('#year').val();--}}
{{--            var category = $('#category').val();--}}
{{--            var subcategory = $('#subcategory').val();--}}
{{--            var from_dept = $('#from_dept').val();--}}
{{--            var to_dept = $('#dept_id').val();--}}
{{--            var from_remarks = $('#from_remarks').val();--}}
{{--            var from_description = $('#from_description').val();--}}
{{--            var to_remarks = $('#to_remarks').val();--}}
{{--            var to_description = $('#to_description').val();--}}
{{--            var radio_budget_id = $('#budgeted_item_pk').val();--}}
{{--            var budget_qty = $('#budget_qty').val();--}}
{{--            var swap_dept_to_name = $('#swap_dept_to_name').val();--}}
{{--            var tbl_td;--}}
{{--            var fields = {--}}
{{--                "_token": "{{ csrf_token() }}",--}}
{{--                'year_id': year,--}}
{{--                'category_id': category,--}}
{{--                'sub_cat_id': subcategory,--}}
{{--                'from_dept': from_dept,--}}
{{--                'to_dept': to_dept,--}}
{{--                'from_remarks': from_remarks,--}}
{{--                'to_remarks': to_remarks,--}}
{{--                'from_description': from_description,--}}
{{--                'to_description': to_description,--}}
{{--                'qty': budget_qty,--}}
{{--                'radio_budget_id': radio_budget_id,--}}
{{--                'swap_dept_to_name': swap_dept_to_name,--}}
{{--            }--}}
{{--            $.ajax({--}}
{{--                url: "{{ url('swapping2') }}",--}}
{{--                type: "POST",--}}
{{--                data: fields,--}}
{{--                success: function (response) {--}}
{{--                    if (response.code == 403) {--}}
{{--                        $('#msgs').html("<div class='alert alert-danger'>" + response.message + "</div>");--}}
{{--                    } else {--}}
{{--                        $('#msgs').html("<div class='alert alert-success'>" + response.message + "</div>");--}}
{{--                        setTimeout(function () {// wait for 5 secs--}}
{{--                            location.reload(); // then reload the page--}}
{{--                        }, 5000);--}}
{{--                    }--}}
{{--                }--}}
{{--                // ,--}}
{{--                // error: function(error) {--}}
{{--                //     $('#msgs').html("<div class='alert alert-danger'>"+error+"</div>");--}}
{{--                // }--}}
{{--            });--}}
{{--        });--}}
{{--        // $('#budget_qty').focusin(function () {--}}
{{--        //     var qty_value = $('#budget_qty').val();--}}
{{--        //--}}
{{--        //     var new_qty = check_qty(qty_value);--}}
{{--        //     console.log(new_qty);--}}
{{--        // });--}}


{{--        $('.showdetails').click(function () {--}}
{{--            var cat_id = $(this).data('cat_id');--}}
{{--            var type_id = $(this).data('type_id');--}}
{{--            var year_id = $('.year_id').val();--}}
{{--            $.get("{{ url('budgetdetails') }}/" + cat_id + "/" + type_id + "/" + year_id, function (data) {--}}
{{--                $('.detail_body').html(data);--}}
{{--            });--}}
{{--        });--}}

{{--        var issue_product_sn = $('.issue_product_sn_' + counter); //+counter+""--}}
{{--        issue_product_sn.empty();--}}
{{--        issue_product_sn.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--        jQuery.ajaxSetup({async: false});--}}
{{--        $.get("{{ url('get_product_sn') }}", function (product_sn_data) {--}}
{{--            data_sn = product_sn_data--}}
{{--        });--}}

{{--        var issue_product_sn_rep = $('.replace_product_sn_' + counter); //+counter+""--}}
{{--        issue_product_sn_rep.empty();--}}
{{--        issue_product_sn_rep.append('<option value=0 class="o1">Select Category here</option>');--}}
{{--        jQuery.ajaxSetup({async: false});--}}
{{--        $.get("{{ url('get_product_sn') }}", function (product_sn_data) {--}}
{{--            data_sn_rep = product_sn_data--}}
{{--        });--}}


{{--        // SLA LOG TABLE--}}

{{--        sla_table = $('#sla_log_dataTable').DataTable();--}}
{{--        $('#addRow_sla').on('click', function () {--}}
{{--            var sn_row = "<select class='custom-select issue_product_sn_" + counter + " ' style='width: auto;' name='issue_product_sn[]' id='issue_product_sn_" + counter + "'>";--}}
{{--            sn_data = data_sn--}}
{{--            $.each(sn_data, function (i, item) {--}}
{{--                sn_row += "<option value='" + item.id + "'>" + item.product_sn + "</option>";--}}
{{--            });--}}
{{--            sn_row += "</select>";--}}

{{--            var sn_row_rep = "<select class='custom-select replace_product_sn_" + counter + " ' style='width: auto;' name='replace_product_sn[]' id='replace_product_sn_" + counter + "'>";--}}
{{--            sn_data_rep = data_sn--}}
{{--            $.each(sn_data_rep, function (i, item) {--}}
{{--                sn_row_rep += "<option value='" + item.id + "'>" + item.product_sn + "</option>";--}}
{{--            });--}}
{{--            sn_row_rep += "</select>";--}}

{{--            sla_table.row.add([--}}
{{--                '<div class="form-group"  required id="defaultCheck" style="margin-left:30px;padding:10px 20px 20px 20px; width: auto; height: 10px;"><input type="checkbox" class="form-check-input filled-in" id="filledInCheckbox"></div>',--}}
{{--                '<div class="form-group" >' + sn_row + '</div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 sla_make_' + counter + '" id="sla_make_id_' + counter + '" name="make_id[]" type="text" placeholder="Make" readonly/></div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 sla_model_' + counter + '" id="sla_model_' + counter + '" name="model_id[]" type="text" placeholder="Model" readonly/></div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 sla_issued_to_' + counter + '" id="sla_issued_to_' + counter + '" name="issued_to[]" type="text" placeholder="Issued To" readonly/></div>',--}}
{{--                '<div class="form-group" style="width: 300px;"><textarea class="form-control sla_issue_description_' + counter + '" id="sla_issue_description_' + counter + '" name="issue_description[]" rows="3" placeholder="Enter Problem/Issue here"></textarea></div>',--}}
{{--                '<div class="form-group" ><input class="form-control py-2 issue_occur_date_' + counter + '" id="issue_occur_date_' + counter + '"  name="issue_occur_date[]" type="datetime-local" /></div>',--}}
{{--                '<div class="form-group"><input class="form-control py-2 visit_date_time_' + counter + '" id="visit_date_time_' + counter + '" name="visit_date_time[]"  type="datetime-local" /></div>',--}}
{{--                '<div class="form-group" style="width: 300px;"><textarea class="form-control engineer_detail_' + counter + '" id="engineer_detail_' + counter + '" name="engineer_detail[]" rows="3" placeholder="Enter Engineer Details here"></textarea></div>',--}}
{{--                '<div class="form-group"><input class="form-control py-2 handed_over_date_' + counter + '" id="handed_over_date_' + counter + '" name="handed_over_date[]" type="date"/></div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><select class="custom-select replace_type" id="replace_type" name="replace_type[]"> ' + '<option value=0>Select Replace Type here</option><option value="1">Replace</option><option value="2">Repair</option><option value="3">Non Repairable</option></select></div>',--}}
{{--                '<div class="form-group">' + sn_row_rep + '</div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 replace_product_make_id_' + counter + '" id="replace_product_make_id_' + counter + '" name="replace_product_make_id[]" type="text" placeholder="Make" readonly/></div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 replace_product_model_id_' + counter + '" id="replace_product_model_id_' + counter + '" name="replace_product_model_id[]" type="text" placeholder="Model" readonly/></div>',--}}
{{--                '<div class="form-group"><input class="form-control py-2 issue_resolve_date_' + counter + '" id="issue_resolve_date_' + counter + '" name="issue_resolve_date[]" type="datetime-local"/></div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 current_dollar_rate' + counter + '" id="current_dollar_rate' + counter + '" name="current_dollar_rate[]" type="text" onfocusout="t_seperator_dynamic(this)" placeholder="Current Dollar Rate"/></div>',--}}
{{--                '<div class="form-group" style="width: 250px;"><input class="form-control py-2 cost_occured' + counter + '" id="cost_occured' + counter + '" name="cost_occured[]" onfocusout="t_seperator_dynamic(this)" type="text" placeholder="Cost Occured"/></div>',--}}
{{--            ]).draw();--}}
{{--            sla_table.row(counter).draw();--}}


{{--            $('.issue_product_sn_' + counter).on('change', function () {--}}
{{--                var id = $(this).val();--}}
{{--                $.get("{{ url('get_make_model_by_psn') }}/" + id, function (data) {--}}
{{--                    console.log("data :  " + data);--}}
{{--                    $('#sla_make_id_' + (counter - parseInt(1))).attr("value", data.make_name);--}}
{{--                    $('#sla_model_' + (counter - parseInt(1))).attr("value", data.model_name);--}}
{{--                    $('#sla_issued_to_' + (counter - parseInt(1))).attr("value", data.issued_to ? data.issued_to : null);--}}
{{--                });--}}
{{--            });--}}

{{--            console.log("replace_product_sn" + counter);--}}
{{--            $('.replace_product_sn_' + counter).on('change', function () {--}}
{{--                var id = $(this).val();--}}
{{--                $.get("{{ url('get_make_model_by_psn') }}/" + id, function (data) {--}}
{{--                    $('#replace_product_make_id_' + (counter - parseInt(1))).val(data.make_name);--}}
{{--                    $('#replace_product_model_id_' + (counter - parseInt(1))).val(data.model_name);--}}
{{--                });--}}
{{--            });--}}


{{--            counter++;--}}
{{--        });--}}

{{--        // Automatically add a first row of data--}}
{{--        $('#addRow_sla').click();--}}

{{--        $('#reload_tbl').click(function () {--}}
{{--            sla_table.draw();--}}
{{--        });--}}

{{--        $('#sla_log_dataTable tbody').on('click', 'tr', function () {--}}
{{--            $(this).toggleClass('selected');--}}
{{--        });--}}

{{--        $('#deleteRow').click(function () {--}}
{{--            // t.row('.selected').remove().draw(false);--}}
{{--            var rows = sla_table--}}
{{--                .rows('.selected')--}}
{{--                .remove()--}}
{{--                .draw();--}}
{{--        });--}}


{{--        // END SLA LOG TABLE--}}


{{--    });--}}
{{--    // function check_qty(value){--}}
{{--    //     const qty_value_const = $('#default_qty').val();--}}
{{--    //     console.log(qty_value_const,'qty of default' , value);--}}
{{--    //     if(qty_value_const < value){--}}
{{--    //         $('.budget_qty').css('border-color', 'red');--}}
{{--    //         $('.btn_swap').hide();--}}
{{--    //         alert("Quantity Exceeded");--}}
{{--    //     }else {--}}
{{--    //         $('.budget_qty').css('border-color', '');--}}
{{--    //         $('.btn_swap').show();--}}
{{--    //     }--}}
{{--    // }--}}

{{--    function get_subcat(obj) {--}}
{{--        var id = obj.value;--}}
{{--        var type_id = $("#type_id option:selected").val();--}}
{{--        var year_id = $("#year_id option:selected").val();--}}
{{--        var subcategory = $('#subcategory' + (counter - parseInt(1)));--}}
{{--        console.log('#subcategory' + (counter - parseInt(1)));--}}
{{--        subcategory.empty();--}}
{{--        subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');--}}
{{--        jQuery.ajaxSetup({async: false});--}}
{{--        $.get("{{ url('get_subcat_by_cat_year') }}/" + id + "/" + type_id + "/" + year_id, function (data) {--}}
{{--            console.log(data);--}}
{{--            $.each(data, function (i, item) {--}}
{{--                subcategory.append($('<option>', {--}}
{{--                    value: item.id,--}}
{{--                    text: item.sub_cat_name--}}
{{--                }));--}}
{{--            });--}}

{{--        });--}}
{{--    }--}}

{{--    function t_seperator_dynamic(obj){--}}
{{--        // var id = obj.value;--}}
{{--        var value = obj.value;--}}
{{--        console.log(value);--}}
{{--        var num_parts = value.toString().split(".");--}}
{{--        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");--}}
{{--        obj.value = num_parts.join(".");--}}

{{--    }--}}

{{--    function get_model(obj) {--}}
{{--        var id = obj.value;--}}
{{--        var model = $('#model' + (counter - parseInt(1)));--}}
{{--        console.log('#model' + (counter - parseInt(1)));--}}
{{--        model.empty();--}}
{{--        model.append('<option value=0 class="o1">Select Model here</option>');--}}
{{--        jQuery.ajaxSetup({async: false});--}}
{{--        $.get("{{ url('model_by_make') }}/" + id, function (data) {--}}
{{--            $.each(data, function (i, item) {--}}
{{--                model.append($('<option>', {--}}
{{--                    value: item.id,--}}
{{--                    text: item.model_name--}}
{{--                }));--}}
{{--            });--}}

{{--        });--}}
{{--    }--}}

{{--    function get_value_radio(obj) {--}}
{{--        var id = obj.value;--}}
{{--        if (id) {--}}
{{--            var fields = {--}}
{{--                "_token": "{{ csrf_token() }}",--}}
{{--                'id': id--}}
{{--            }--}}
{{--            $.post("{{ url('get_budget_single') }}", fields, function (res) {--}}

{{--                if (res) {--}}
{{--                    console.log(res);--}}
{{--                    $('#from_remarks').val(res.remarks);--}}
{{--                    $('#from_description').val(res.description);--}}
{{--                    $('#budget_qty').val(res.remaining);--}}
{{--                    $('#default_qty').val(res.remaining);--}}
{{--                    $('#budgeted_item_pk').val(res.id);--}}
{{--                } else {--}}
{{--                    $('#msgs').html("<div class='alert alert-danger'>Something Went Wrong ! </div>");--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}
{{--        return id;--}}
{{--    }--}}

{{--    // function calculate_warranty(obj){--}}
{{--    //     var p_date = document.getElementById('p_date').value;--}}
{{--    //     //$('.purchase_date').val();--}}
{{--    //     var warrenty = obj.value;--}}
{{--    //     if (p_date && warrenty) {--}}
{{--    //         var w_end = $('.warrentyend'+(counter-parseInt(1)));--}}
{{--    //         var res = p_date.split("-");--}}
{{--    //         var num = parseInt(res[1]) + parseInt(warrenty);--}}
{{--    //         var d = new Date(res[0], num, res[2]);--}}
{{--    //         var year = d.getFullYear();--}}
{{--    //         var month = d.getMonth();--}}
{{--    //         var date = d.getDate();--}}
{{--    //         // var result = month+'/'+date+'/'+year;--}}
{{--    //         var result = year + '-' + month + '-' + date;--}}
{{--    //         w_end.val(result);--}}
{{--    //     }--}}
{{--    // }--}}

{{--</script>--}}

