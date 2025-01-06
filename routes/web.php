<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\BranchController;
// use App\Http\Controllers\DepartmentController;
// use App\Http\Controllers\LocationController;
// use App\Http\Controllers\ModelController;
// use App\Http\Controllers\RoleController;
// use App\Http\Controllers\StoreController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\EmailController;
//use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PDFController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\InventoryController;
// use App\Http\Controllers\VendorController;
// use App\Http\Controllers\MakeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('login');
    //return view('welcome');
});

Auth::routes();


Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::resource('/category', CategoryController::class);//->middleware('role:1');
Route::resource('/sub_category', SubcategoryController::class);//->middleware('role:1');
//Route::resource('/branch', BranchController::class)->middleware('role:1');
Route::resource('/department', DepartmentController::class);//->middleware('role:1');
Route::resource('/location', LocationController::class);//->middleware('role:1');
Route::resource('/link', LinkController::class);//->middleware('role:1');
Route::resource('/model', modelController::class);//->middleware('role:1');
Route::resource('/role', RoleController::class);//->middleware('role:1');
Route::resource('/store', StoreController::class);//->middleware('role:1');
Route::resource('/user', UserController::class);//->middleware('role:1');
Route::resource('/employee', EmployeeController::class);//->middleware('role:1');
Route::resource('/inventory', InventoryController::class);//->middleware(['permission:inventory_index'])->only('index');
Route::resource('/vendor', VendorController::class);//->middleware('role:1');
Route::resource('/make', MakeController::class);//->middleware('role:1');
Route::resource('/devicetype', DevicetypeController::class);//->middleware('role:1');
Route::resource('/itemnature', ItemnatureController::class);//->middleware('role:1');
Route::resource('/inventorytype', InventorytypeController::class);//->middleware('role:1');
Route::resource('/dollars', DollarController::class);
Route::resource('/types', TypeController::class);
Route::resource('/years', YearController::class);
Route::resource('/previous_inventory', PreviousInventoryController::class);
Route::resource('/budget', BudgetController::class);
Route::resource('/budget_collection', BudgetCollectionController::class);
Route::resource('/budgetplan',BudgetPlanController::class);
Route::resource('/disposalstatus', DisposalstatusController::class);
Route::resource('/dispose', DisposalController::class);
Route::resource('/dispatchin', DispatchinController::class);
Route::resource('/dispatchout', DispatchoutController::class);
Route::resource('/vendorterms', VendorTermController::class);
Route::resource('/sla', SlaController::class);
Route::resource('/slalog', SlaComplainController::class);
Route::resource('/invoice', InvoiceController::class);
/* Forms */
Route::get('/inventory1',[\App\Http\Controllers\InventoryController::class,'index1']);
Route::get('/inventory1-Datatable',[\App\Http\Controllers\InventoryController::class,'list'])->name('inventory.Datatable');
Route::get('/get_sla_total_cost/{type_id}/{year_id}/{sub_cat_id}', 'SlaComplainController@get_sla_total_cost');

Route::get('/add_category', [FormController::class, 'add_category'])->middleware(['permission:add_category']);//->middleware('role:1');
Route::get('/add_subcategory', [FormController::class, 'add_subcategory'])->middleware(['permission:add_subcategory']);//->middleware('role:1');
//Route::get('/add_branch', [FormController::class, 'add_branch'])->middleware('role:1');
Route::get('/add_department', [FormController::class, 'add_department']);//->middleware('role:1');
Route::get('/add_location', [FormController::class, 'add_location'])->middleware(['permission:add_location']);//->middleware('role:1');
Route::get('/add_model', [FormController::class, 'add_model'])->middleware(['permission:add_model']);//->middleware('role:1');
//Route::get('/add_role', [FormController::class, 'add_role'])->middleware('role:1');
Route::get('/add_store', [FormController::class, 'add_store'])->middleware(['permission:add_store']);//->middleware('role:1');
Route::get('/add_user', [FormController::class, 'add_user'])->middleware(['permission:add_user']);//->middleware('role:1');
Route::get('/add_vendor', [FormController::class, 'add_vendor'])->middleware(['permission:add_vendor']);//->middleware('role:1');
Route::get('/add_make', [FormController::class, 'add_make'])->middleware(['permission:add_make']);//->middleware('role:1');
Route::get('/add_employee', [FormController::class, 'add_employee'])->middleware(['permission:add_employee']);//->middleware('role:1');
Route::get('/edit_employee', [FormController::class, 'edit_employee']);//->middleware(['permission:edit_employee']);//->middleware('role:1');
Route::get('/add_devicetype', [FormController::class, 'add_devicetype'])->middleware(['permission:add_devicetype']);//->middleware('role:1');
Route::get('/add_itemnature', [FormController::class, 'add_itemnature'])->middleware(['permission:add_itemnature']);//->middleware('role:1');
Route::get('/add_inventorytype', [FormController::class, 'add_inventorytype'])->middleware(['permission:add_inventorytype']);//->middleware('role:1');
Route::get('/model_by_make/{id}', [FormController::class, 'model_by_make']);
Route::get('/get_user_except/{user_id}', [FormController::class, 'get_user_except']);
Route::get('/subcat_by_category/{id}', [FormController::class, 'subcat_by_category']);
Route::get('/getEmployeeByBranchId/{id}', [FormController::class, 'getEmployeeByBranchId']);
Route::get('/linkedsubcat_by_category/{id}', [FormController::class, 'linkedsubcat_by_category']);
Route::get('/get_make', [FormController::class, 'get_make']);
Route::get('/get_category', [FormController::class, 'get_category']);
Route::get('/get_year_by_type/{id}', [FormController::class, 'get_year_by_type']);
Route::get('/get_year_by_type_SLA/{id}', [FormController::class, 'get_year_by_type_SLA']);
Route::get('/get_subcat_by_year/{id}/{type_id}', [FormController::class, 'get_subcat_by_year']);
Route::get('/get_subcat_by_year_SLA/{id}/{type_id}', [FormController::class, 'get_subcat_by_year_SLA']);
Route::get('/get_vendor_by_sub_cat_year/{id}/{type_id}/{year_id}', [FormController::class, 'get_vendor_by_sub_cat_year']);
Route::get('/get_vendor_by_sub_cat_year_invoice/{id}/{type_id}/{year_id}/{category_id}', [FormController::class, 'get_vendor_by_sub_cat_year_invoice']);
Route::get('/get_cat_by_year/{id}/{type_id}', [FormController::class, 'get_cat_by_year']);
Route::get('/get_make_model_by_psn/{id}', [FormController::class, 'get_make_model_by_psn']);
Route::get('/get_subcat_by_cat_year/{id}/{type_id}/{year_id}', [FormController::class, 'get_subcat_by_cat_year']);

Route::get('/show_auth_user_plan', [\App\Http\Controllers\BudgetPlanController::class, 'show_auth_user_plan'])->middleware(['permission:show_auth_user_plan']);
Route::get('/send_email', [\App\Http\Controllers\BudgetPlanController::class, 'send_email']);
Route::get('/get_catBy_year_invoice/{year_id}', [FormController::class, 'get_catBy_year_invoice']);
Route::get('/get_subcatBy_year_invoice/{category_id}/{year_id}', [FormController::class, 'get_subcatBy_year_invoice']);
Route::get('/get_vendorBy_year_invoice/{subcategory_id}/{category_id}/{year_id}', [FormController::class, 'get_vendorBy_year_invoice']);


Route::get('/get_grns', 'GrnController@get_grns');//->middleware('role:1');
Route::post('/filter_grn', 'GrnController@filter_grn');//->middleware('role:1');
Route::get('/get_gins', 'GinController@get_gins');//->middleware('role:1');
Route::post('/filter_gin', 'GinController@filter_gin');//->middleware('role:1');
Route::get('generate-grn/{id}/{from}/{to}','PDFController@generateGRN');//->middleware('role:1');
Route::get('generate-gin/{id}/{from}/{to}','PDFController@generateGIN');//->middleware('role:1');
Route::post('vendor_term','VendorTermController@store_vendor_term');
Route::get('/add_vendor_term', [FormController::class, 'add_vendor_term']);//->middleware(['permission:add_vendor_term']);
Route::post('invoice_inventory','InvoiceController@add_invoice_recording');//->middleware(['permission:invoice_inventory']);;
Route::get('/add_invoice_recording', [FormController::class, 'add_invoice_recording'])->middleware(['permission:add_invoice_recording']);
Route::get('/add_sla', [FormController::class, 'add_sla'])->middleware(['permission:add_sla']);
Route::get('/add_sla_log', [FormController::class, 'add_sla_log'])->middleware(['permission:add_sla_log']);
Route::get('/add_inventory', [FormController::class, 'add_inventory'])->middleware(['permission:add_inventory']);
Route::get('/add_with_grn', [FormController::class, 'add_with_grn'])->middleware(['permission:add_with_grn']);
Route::get('/add_with_grn_multiple', [FormController::class, 'add_with_grn_multiple'])->middleware(['permission:add_with_grn_multiple']);
Route::get('/add_with_grn_bulk', [FormController::class, 'add_with_grn_bulk'])->middleware(['permission:add_with_grn_multiple']);
Route::post('/added_with_grn_multiple', [\App\Http\Controllers\InventoryController::class, 'add_with_grn_multiple']);
Route::post('/add_with_grn_bulk', [\App\Http\Controllers\InventoryController::class, 'add_with_grn_bulk']);
Route::get('/pendings', [FormController::class, 'pendings'])->middleware(['permission:pendings']);
Route::get('/pending_gins', [FormController::class, 'pending_gins'])->middleware(['permission:pending_gins']);
Route::get('/issue_inventory', [FormController::class, 'issue_inventory'])->middleware(['permission:issue_inventory']);
Route::get('/issue_inventory_bulk', [FormController::class, 'issue_inventory_bulk'])->middleware(['permission:issue_inventory']);
Route::get('/issue_with_gin', [FormController::class, 'issue_with_gin'])->middleware(['permission:issue_with_gin']);
Route::get('/transfer_inventory', [FormController::class, 'transfer_inventory'])->middleware(['permission:transfer_inventory']);
Route::get('/list_transfer_inventory_request', [FormController::class, 'show_transfer_inventory_request'])
//    ->middleware(['permission:transfer_inventory'])
;
Route::get('/edit_transfer_inventory_request/{id}', [FormController::class, 'edit_transfer_inventory_request']);
Route::post('/update_transfer_inventory_request', [FormController::class, 'update_transfer_inventory_request']);

Route::get('/return_inventory', [FormController::class, 'return_inventory'])->middleware(['permission:return_inventory']);
Route::get('/repair', [FormController::class, 'repair'])->middleware(['permission:repair']);
Route::get('/add_dollar_price', [FormController::class, 'add_dollar_price'])->middleware(['permission:add_dollar_price']);
Route::get('/add_year', [FormController::class, 'add_year'])->middleware(['permission:add_year']);
Route::get('/add_type', [FormController::class, 'add_type'])->middleware(['permission:add_type']);
Route::get('/add_budget', [FormController::class, 'add_budget'])->middleware(['permission:add_budget']);
Route::get('/add_budget_collection', [FormController::class, 'add_budget_collection'])
//    ->middleware(['permission:add_budget_collection'])
;
Route::get('/add_budget_plan', [FormController::class, 'add_budget_planing'])->middleware(['permission:add_it_budget_plan']);
Route::get('/edit_budget_planing/{id}', [FormController::class, 'edit_budget_planing'])->middleware(['permission:edit_auth_user_budget_plan']);
Route::get('/show_budget', [FormController::class, 'show_budget'])->middleware(['permission:show_budget']);
Route::get('/budget_by_year_category', 'BudgetController@budget_by_year_category')->middleware(['permission:show_subcategory_budget']);
Route::get('/budget_by_year_category_adv', 'BudgetController@budget_by_year_category_adv')
//    ->middleware(['permission:budget_by_year_category_adv'])
;
Route::get('/budget_by_year_category_summary', 'BudgetController@budget_by_year_category_summary')->middleware(['permission:budget_by_year_category_summary']);
Route::get('/budget_compare_category', 'BudgetController@budget_compare_category')->middleware(['permission:budget_compare_category']);
Route::get('/summary', [FormController::class, 'summary'])->middleware(['permission:summary']);
Route::get('/capexOpexSummaryDollar', [FormController::class, 'summaryDollar'])->middleware(['permission:capexOpexSummaryDollar']);
Route::get('/downloadSummaryByYear/{data}', [PDFController::class, 'generateSummaryByYear'])->middleware(['permission:capexOpexSummaryDollar']);
Route::get('/summary2', [FormController::class, 'summary2'])->middleware(['permission:summary2']);
Route::get('/pkr_by_year/{id}', [FormController::class, 'pkr_by_year']);//->middleware(['permission:pkr_by_year']);
Route::get('/show_subcategory_budget', [FormController::class, 'show_subcategory_budget'])->middleware(['permission:show_subcategory_budget']);
Route::get('/show_subcategory_budget_advance', [FormController::class, 'show_subcategory_budget_adv'])->middleware(['permission:show_subcategory_budget_advance']);
Route::get('/show_subcategory_budget_summary', [FormController::class, 'show_subcategory_budget_summary'])->middleware(['permission:show_subcategory_budget_summary']);
Route::get('/budget_comparison', [FormController::class, 'budget_comparison'])->middleware(['permission:budget_comparison']);
Route::get('/budget_by_year', 'BudgetController@budget_by_year');
Route::get('/budget_collection_by_year', 'BudgetCollectionController@budget_collection_by_year');
Route::post('/summary_by_year', 'BudgetController@summary_by_year');
Route::post('/summary_by_Dollar', 'BudgetController@summaryDollar');
Route::post('/summary_by_year2', 'BudgetController@summary_by_year2');
Route::get('/lock_budget/{id}', 'BudgetController@lock_budget');
Route::get('/budget_transfer', 'BudgetController@budget_transfer');
Route::post('/transfered', 'BudgetController@transfered');
Route::get('/swapping', 'BudgetController@swapping');
Route::post('/swapping2', 'BudgetController@swapping2');
Route::get('/transfer_product_sn', 'BudgetController@transfer_product_sn')->middleware(['permission:transfer_product_sn']);
Route::post('/transfer_product_sn2', 'BudgetController@transfer_product_sn2');
Route::post('/get_budget', 'BudgetController@get_budget');
Route::post('/get_budget_single', 'BudgetController@get_budget_single');
Route::get('/budgetdetails/{cat_id}/{type_id}/{year_id}', 'BudgetController@budgetdetails');
Route::get('/add_d_status', [FormController::class, 'add_d_status'])->middleware(['permission:add_d_status']);
Route::get('/add_disposal', [FormController::class, 'add_disposal'])->middleware(['permission:add_disposal']);
Route::get('/add_dispatchin', [FormController::class, 'add_dispatchin'])->middleware(['permission:add_dispatchin']);
Route::get('/add_dispatchout', [FormController::class, 'add_dispatchout'])->middleware(['permission:add_dispatchout']);
Route::get('/add_transfer_inventory_request', [FormController::class, 'add_transfer_inventory_request'])
//    ->middleware(['permission:add_dispatchout'])
;

//Route::get('/add_previous_equipment', [FormController::class, 'add_previous_equipment']);
Route::get('/add_previous_inventory', [FormController::class, 'add_previous_inventory'])->middleware(['permission:add_previous_inventory']);

Route::get('/email_view', 'InventoryController@email_view');
Route::get('send_email/{id}/{message}','EmailController@SendUserMail')->name('send_email');

Route::get('assign_priviliges_view',[FormController::class, 'assign_priviliges_view']);
Route::get('assign_priviliges',[FormController::class, 'assign_priviliges']);
Route::get('replicate_priviliges',[FormController::class, 'replicate_priviliges']);

Route::resource('/priviliges', UserPriviligeController::class);
Route::get('/show_priviliges_by_user', 'UserPriviligeController@show_priviliges_by_user');
Route::post('/replicate_repiviliges/', 'UserPriviligeController@replicate_repiviliges');
Route::post('/assign_priviliges_new/', 'UserPriviligeController@assign_priviliges_new');

Route::get('/get_product_sn/', [FormController::class, 'get_product_sn']);
Route::get('/get_department/', [\App\Http\Controllers\DepartmentController::class, 'get_department']);
Route::get('/inventory_flow', [FormController::class, 'inventory_flow'])->middleware(['permission:inventory_flow']);
Route::post('/check_vendor_term/', 'InventoryController@check_vendor_term')->name('check_vendor_term');
Route::post('/issue', 'FormController@submitt_issue')->middleware(['permission:issue_inventory']);
Route::post('/submitt_issue_with_bulk', 'FormController@submitt_issue_with_bulk')->middleware(['permission:issue_inventory']);
Route::get('/check_issue_email', 'FormController@check_issue_email');
Route::post('/submit_gin', 'FormController@submit_gin')->middleware(['permission:issue_with_gin']);
Route::post('/transfer', 'FormController@submitt_transfer')->middleware(['permission:transfer_inventory']);
Route::post('/transfer_inventory_request', [FormController::class, 'transfer_inventory_request'])
//    ->middleware(['permission:transfer_inventory'])
;
Route::get('/filter_inventory', 'FormController@filter_inventory');
Route::post('/return', 'FormController@submitt_return')->middleware(['permission:return_inventory']);
Route::get('/filter_return', 'FormController@filter_return');
Route::post('/repair_inventory', 'FormController@repair_inventory');
Route::post('/process_to_grn', 'GrnController@create_grn');
Route::post('/process_to_gin', 'GinController@create_gin');

//Acknowledge Received Inventories
Route::get('received/{issued_PK}/{emp_id}/{status}', [EmailController::class, 'inventory_received'])->name('received-email')->middleware('signed');
Route::get('reverify/{emp_id}/{issue_PK}/{status}', [EmailController::class, 'inventory_reverify'])->name('reverify-email')->middleware('signed');
Route::post('inventory_feedback/{id}/{status}','EmailController@save_feedback');
Route::post('reverify_inventory_feedback/{id}/{inv_id}/{status}/{issue_pk}','EmailController@save_reverify_inventory_feedback');

Route::get('/get_employee/{id}', 'EmployeeController@get_employee');
Route::get('/get_employee_with_inventory/{id}', 'EmployeeController@get_employee_with_inventory');
Route::get('/get_employee_new/{id}', 'FormController@get_employee_new');
Route::get('/get_employee_branch/{emp_code}', 'EmployeeController@get_employee_branch');
Route::get('generate-pdf','PDFController@generatePDF');

Route::get('budgetexport/{data}','PDFController@budgetexport');
Route::get('budgetexport2/{data}','PDFController@budgetexport2');
Route::get('itemexport/{data}','PDFController@itemexport');
Route::get('itemexport_subcategory/{data}','PDFController@itemexport_subcategory');
Route::get('itemexport_subcategory_adv/{data}','PDFController@itemexport_subcategory_adv');
Route::get('itemexport_subcategory_summary/{data}','PDFController@itemexport_subcategory_summary');
Route::get('compare_budget_subcategory/{data}','PDFController@compare_budget_subcategory');
Route::get('compare_budget_subcategory_new/{data}','PDFController@compare_budget_subcategory_new');

Route::get('/show_inventory_list', 'ReportController@show_inventory');
Route::get('/show_invoice_inventory_list', 'ReportController@show_invoice_inventory_list');
Route::get('inventoryexport/{data}','PDFController@inventoryexport');
Route::get('invoiceinventoryexport/{data}','PDFController@invoiceinventoryexport');
Route::get('/item_detail/{id}', 'InventoryController@item_detail');
Route::get('/single_item/{id}', 'InventoryController@single_item');
Route::get('/balance_report', 'ReportController@balance_report');
Route::get('/balanceexport/{data}','PDFController@balanceexport');
Route::get('/check_product/{pro}', 'InventoryController@check_product');
Route::get('/get_price/{id}', 'InventoryController@get_price');
Route::get('/get_inv_items/{id}', 'InventoryController@get_inv_items');
Route::get('/get_unassigned_items/{id}', 'InventoryController@get_unassigned_items');
Route::get('/get_unassigned_items_Capex/{id}', 'InventoryController@get_unassigned_items_Capex');
Route::get('/get_assigned_items/{id}/{action}', 'InventoryController@get_assigned_items');
Route::get('/get_budget_items/{inv_id}/{dept_id}', 'BudgetController@get_budget_items');
Route::get('/employees_by_dept/{dept_id}', 'EmployeeController@employees_by_dept');

Route::get('/edit_logs', 'ReportController@edit_logs');
Route::get('/editlogsexport/{data}','PDFController@editlogsexport');
Route::get('/inventory_in', 'ReportController@inventory_in');
Route::get('/inventoryinexport/{data}','PDFController@inventoryinexport');
Route::get('/sla_report', 'ReportController@sla_report');
Route::get('/sla_complain_report', 'ReportController@sla_complain_report');
Route::get('/sla_consumption_report', 'ReportController@sla_consumption_report');

Route::get('/generate_barcode/{data}', 'PDFController@generate_barcode');
Route::get('/dispatchoutexport_qrcode/{data}', 'PDFController@dispatchoutexport_qrcode');
Route::get('/inventory_detail/{inv_id}', 'InventoryController@inventory_detail')->name('inventory_detail');

Route::get('/slaexport/{data}','PDFController@slaexport');
Route::get('/slacomplainexport/{data}','PDFController@slacomplainexport');
Route::get('/slaconsumptionexport/{data}','PDFController@slaconsumptionexport');
Route::get('/inventory_out', 'ReportController@inventory_out');
Route::get('/inventory_out1', [ \App\Http\Controllers\ReportController::class,'inventoryOutIndex']);
Route::get('/inventory_out1-Datatable',[ \App\Http\Controllers\ReportController::class, 'inventoryOutList'])->name('inventoryOut1.List');
Route::get('/edit_inventory_out/{id}', [\App\Http\Controllers\ReportController::class, 'edit_inventory_out']);
Route::post('update_inventory_out',[\App\Http\Controllers\ReportController::class,'update_inventory_out']);
Route::post('reverification_email/{emp_id}','ReportController@reverification_email');
Route::get('/inventoryoutexport/{data}','PDFController@inventoryoutexport');
Route::get('/inventoryoutexport1/{data}','PDFController@inventoryoutexport1');
Route::get('/bin_card', 'ReportController@bin_card');
Route::get('/bincardexport/{data}','PDFController@bincardexport');
Route::get('/asset_repairing', 'ReportController@asset_repairing');
Route::get('/repair_items', [FormController::class, 'repair_items']);
Route::get('/edit_repair_items/{id}', [FormController::class, 'edit_repair_items'])->middleware(['permission:edit_repair_item']);
Route::post('/update_asset_repair/{id}', [FormController::class, 'update_asset_repair'])->middleware(['permission:edit_repair_item']);
Route::get('/repairingexport/{data}','PDFController@repairingexport');
Route::get('/disposal', 'ReportController@disposal');
Route::get('/dispatchin_report', 'ReportController@dispatchin_report');
Route::get('/dispatchout_report', 'ReportController@dispatchout_report');
Route::get('/disposalexport/{data}','PDFController@disposalexport');
Route::get('/dispatchinexport/{data}','PDFController@dispatchinexport');
Route::get('/dispatchoutexport/{data}','PDFController@dispatchoutexport');
Route::get('/vendor_buying', 'ReportController@vendor_buying');
Route::get('/vendor_buyingexport/{data}','PDFController@vendor_buyingexport');
Route::get('/activeinactive/{id}/{data}','UserController@activeinactive');

//test route
Route::get('/dispatchinexport_mail','PDFController@dispatchinexport_mail');
Route::get('/dispatchoutexport_mail','PDFController@dispatchoutexport_mail');

Route::get('/reorder-level','ReportController@reorder_level');
Route::get('/reorderexport/{data}','PDFController@reorderexport');

/* For Excel */
Route::get('export_summary/{year}', 'ExcelController@export_summary');
Route::get('export_complete_summary/', 'ExcelController@export_complete_summary');
Route::get('export_summary2/{year}', 'ExcelController@export_summary2');
Route::get('export_budget/{data}', 'ExcelController@export_budget');
Route::get('export_budget_subcat/{data}', 'ExcelController@export_budget_subcat');
Route::get('export_budget_subcat_adv/{data}', 'ExcelController@export_budget_subcat_adv');
Route::get('export_budget_summary_subcat/{data}', 'ExcelController@export_budget_summary_subcat');
Route::get('export_compare_budget_subcat/{data}', 'ExcelController@export_compare_budget_subcat');
Route::get('export_compare_budget_subcat_new/{data}', 'ExcelController@export_compare_budget_subcat_new');
Route::get('export_inventory/{data}', 'ExcelController@export_inventory');
Route::get('export_capexOpexSummaryDollar/{data}', 'ExcelController@export_capexOpexSummaryDollar');
Route::get('export_invoice_inventory/{data}', 'ExcelController@export_invoice_inventory');
Route::get('export_editlogs/{data}', 'ExcelController@export_editlogs');
Route::get('export_inventoryin/{data}', 'ExcelController@export_inventoryin');
Route::get('export_sla/{data}', 'ExcelController@export_sla');
Route::get('export_sla_complain/{data}', 'ExcelController@export_sla_complain');
Route::get('export_sla_consumption/{data}', 'ExcelController@export_sla_consumption');
Route::get('export_inventoryout/{data}', 'ExcelController@export_inventoryout');
Route::get('export_inventoryout1/{data}', 'ExcelController@export_inventoryout1');
Route::get('export_balance/{data}', 'ExcelController@export_balance');
Route::get('export_bincard/{data}', 'ExcelController@export_bincard');
Route::get('export_assetrepairing/{data}', 'ExcelController@export_assetrepairing');
Route::get('export_disposal/{data}', 'ExcelController@export_disposal');
Route::get('export_dispatchin/{data}', 'ExcelController@export_dispatchin');
Route::get('export_dispatchout/{data}', 'ExcelController@export_dispatchout');
Route::get('export_vendorbuying/{data}', 'ExcelController@export_vendorbuying');
Route::get('export_reorderlevel/{data}', 'ExcelController@export_reorderlevel');
Route::get('export_subcategory', 'ExcelController@export_subcategory');


//Route::get('/insertDept', [DashboardController::class, 'insertDept']);
Route::get('/run-test', [\App\Http\Controllers\InventoryController::class, 'test']);
