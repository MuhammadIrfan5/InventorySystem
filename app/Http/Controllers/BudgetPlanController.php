<?php

namespace App\Http\Controllers;

use App\BudgetPlanIT;
use App\BudgetPlanRelation;
use App\Category;
use App\Employee;
use App\LinkedSubcategory;
use App\LinkedSubcatPlan;
use App\Subcategory;
use App\SystemLogs;
use App\User;
use App\Year;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use stdClass;
use function GuzzleHttp\Promise\all;

class BudgetPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:add_it_budget_plan', ['only' => ['store']]);
        $this->middleware('permission:edit_auth_user_budget_plan', ['only' => ['update']]);
        $this->middleware('permission:delete_auth_user_plan', ['only' => ['destroy']]);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_agree' => 'required',
            'year_id' => 'required|not_in:0',
            'optional_file' => 'file|mimes:jpg,jpeg,bmp,png,tif,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip,rar|max:50024'
//            mimes:doc,docx,csv,xlx,xls,xlxs|
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            unset($request->dataTable_length);
            $file = $request->optional_file;
            $file_path = "";
            $file_name = "";
            $extension = "";
            $file_size = "";
            if ($request->hasFile('optional_file')) {
                $file_name = $name = time() . '_' . $request->optional_file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $file_size = $request->file('optional_file')->getSize() / 1024;
                $fileStore = $file->move(storage_path('app/public/BudgetPlan/Files_') . auth()->user()->id . '/', $file_name . '_' . auth()->user()->emp_code);
                $file_path = 'BudgetPlan/Files-' . auth()->user()->id . '/' . $file_name;
            }
            $count = count($request->subcategory);
            $report_data = array();
//            $data = array();
            $budget_plan_id = DB::table('budget_plan')->insertGetId([
                'user_id' => auth()->user()->id,
                'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                'year_id' => $request->year_id,
                'optional_file_path' => $file_path,
                'file_name' => $file_name,
                'file_size' => $file_size,
                'file_extension' => $extension,
                'other_req' => $request->other_req,
                'new_budget' => $request->new_budget,
                'is_agree' => $request->is_agree,
                'agreed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $relation_data = array();
            if ($budget_plan_id) {
                for ($i = 0; $i < $count; $i++) {
                    if ($request->upgrade_qty[$i] != ""  || $request->new_qty[$i] != "" || $request->remarks[$i] != "" || $request->previousYear[$i] != "") {
                        $getLinkedSubcatIsNotCollection = LinkedSubcategory::where('subcategory_id', $request->subcategory[$i])
//                            ->whereNotIn('linked_subcategory_id', $request->subcategory)
                            ->get();
                        foreach ($getLinkedSubcatIsNotCollection as $linked) {
                            if ($getLinkedSubcatIsNotCollection) {
//                                   $approxCost = str_replace(',','',$request->approx_cost[$i]) * ($request->upgrade_qty[$i]+$request->new_qty[$i]) ;
//                                   $tenPercent = $approxCost+($approxCost*10)/100;
                                $relation_data = [
                                    'plan_budget_id' => $budget_plan_id,
                                    'user_id' => auth()->user()->id,
                                    'year_id' => $request->year_id,
                                    'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                                    'subcategory_id' => $linked->linked_subcategory_id,
                                    'category_id' => Subcategory::find($linked->linked_subcategory_id)['category_id'],
                                    'upgraded_qty' => 0,
                                    'linked_subcategory' => 0,
                                    'previous_year' => $request->previousYear[$i],
                                    'types_id' => $request->types_id[$i],
//                                    'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                                    'new_qty' => $request->new_qty[$i] ?? 0,
                                    'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                                    'remarks' => $request->remarks[$i],
                                ];
                                $data = [
                                    'user_id' => auth()->user()->id,
                                    'year_id' => $request->year_id,
                                    'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                                    'subcategory_id' => $linked->linked_subcategory_id,
                                    'category_id' => Subcategory::find($linked->linked_subcategory_id)['category_id'],
                                    'upgraded_qty' => 0,
                                    'previous_year' => $request->previousYear[$i],
                                    'types_id' => $request->types_id[$i],
//                                    'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                                    'new_qty' => $request->new_qty[$i] ?? 0,
                                    'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                                    'remarks' => $request->remarks[$i],
                                    'optional_file_path' => $file_path,
                                    'file_name' => $file_name,
                                    'file_size' => $file_size,
                                    'file_extension' => $extension,
                                    'other_req' => $request->other_req,
                                    'new_budget' => $request->new_budget,
                                    'is_agree' => $request->is_agree,
                                ];
                                $itPlan = BudgetPlanRelation::create($relation_data);
                                array_push($report_data, $data);
                            }
                        }
                        $relation_data = [
                            'plan_budget_id' => $budget_plan_id,
                            'user_id' => auth()->user()->id,
                            'year_id' => $request->year_id,
                            'linked_subcategory' => 1,
                            'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                            'subcategory_id' => $request->subcategory[$i],
                            'category_id' => Subcategory::find($request->subcategory[$i])['category_id'],
                            'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                            'types_id' => $request->types_id[$i] ?? '',
                            'previous_year' => $request->previousYear[$i] ?? 0,
                            'new_qty' => $request->new_qty[$i] ?? 0,
                            'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                            'remarks' => $request->remarks[$i],
                        ];
                        $data = [
//                        'plan_budget_id' => $budget_plan->id,
                            'user_id' => auth()->user()->id,
                            'year_id' => $request->year_id,
                            'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                            'subcategory_id' => $request->subcategory[$i],
                            'category_id' => Subcategory::find($request->subcategory[$i])['category_id'],
                            'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                            'types_id' => $request->types_id[$i]??'',
                            'previous_year' => $request->previousYear[$i] ?? 0,
                            'new_qty' => $request->new_qty[$i] ?? 0,
                            'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                            'remarks' => $request->remarks[$i],
                            'optional_file_path' => $file_path,
                            'file_name' => $file_name,
                            'file_size' => $file_size,
                            'file_extension' => $extension,
                            'other_req' => $request->other_req,
                            'new_budget' => $request->new_budget,
                            'is_agree' => $request->is_agree,
                        ];
                        $user = User::find(auth()->user()->id);
                        $itPlan = BudgetPlanRelation::create($relation_data);
                        array_push($report_data, $data);
                    }
                    $sum = 0;
                    foreach ($request->approx_cost as $val) {
                        if ($val != null && $val != 'null') {
                            $text = str_replace(",", "", $val);
                            $sum += (float)$text;
                        }
                    }
                }
                $other_data = [
                    'year' => Year::find($data["year_id"])['year'],
                    'employee_code' => $data["employee_code"],
                    'emp_name' => Employee::where('emp_code', $data["employee_code"])->first()['name'],
                    'other_req' => $data["other_req"],
                    'new_budget' => $data["new_budget"],
                    'total' => number_format($sum, 2),
                ];
                $reportCategory = array();
                $reportCategoryValue = array();
                foreach ($report_data as $report_datum) {
                    $reportData = DB::table('subcategories')->where('id', $report_datum['subcategory_id'])->where('is_budget_collection', 1)->first();
                    if ($reportData != null && $reportData != "null") {
                        $reportCategory[] = (array)$reportData;
                        $reportCategoryValue[] = $report_datum;
                    }
                }
                $unique_num = rand('9999', '99999');
                $pdf = PDF::loadView('budget_plan_report', ['data' => $data, 'other_data' => $other_data, 'report_data' => $report_data, 'reportCategory' => $reportCategory, "reportCategoryValue" => $reportCategoryValue])->save(public_path(str_replace(' ', '', ('BudgetPlanPdf/' . $unique_num . $user->name . 'budgetplan.pdf'))));
                $user->file_path = str_replace(' ', '', ('BudgetPlanPdf/' . $unique_num . $user->name . 'budgetplan.pdf'));
                $user->year = Year::find($request->year_id)['year'];
                Mail::send('emails.budget_plan_email', ['data' => $user], function ($message) use ($user) {
                    $message->to(auth()->user()->email)->subject
                    ('Budget IT Plan Email')
                        ->cc(["abdulwajid@efulife.com", "rizwanbukhari@efulife.com"]);
                    $message->from('itstore@efulife.com', 'Support IT Store');
                });
                $log = SystemLogs::Add_logs('email', null, 'email');
                return redirect()->back()->with('msg', 'Budget Plan Added Successfully!');
            } else {
                return redirect()->back()->with('msg', 'Something Went Wrong!');
            }
        }
    }


    public function show_auth_user_plan()
    {
        $budget_plan = BudgetPlanIT::where('user_id', auth()->user()->id)->get();
        return view('list_auth_user_plan', compact('budget_plan'));
    }

    public function send_email()
    {
        $path = "BudgetPlanPdf/49882JibranMasoodKhanbudgetplan.pdf";
        $user = User::find(1208);
        $user->file_path = $path;
        $data = $user->toArray();
        echo Mail::send('emails.budget_plan_email', ['data' => $data], function ($message) {
            $message->to(["muhammadirfan5891@gmail.com"])->subject('Budget IT Plan Email');
            $message->from('itstore@efulife.com', 'Support IT Store');
        });
        echo "here";
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_agree' => 'required',
            'year_id' => 'required|not_in:0',
            'optional_file' => 'file|mimes:jpg,jpeg,bmp,png,tif,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip,rar|max:50024'
//            mimes:doc,docx,csv,xlx,xls,xlxs|
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            unset($request->dataTable_length);
            $file = $request->optional_file;
            $file_path = "";
            $file_name = "";
            $extension = "";
            $file_size = "";
            if ($request->hasFile('optional_file')) {
                $file_name = $name = time() . '_' . $request->optional_file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $file_size = $request->file('optional_file')->getSize() / 1024;
                $fileStore = $file->move(storage_path('app/public/BudgetPlan/Files_') . auth()->user()->id . '/', $file_name . '_' . auth()->user()->emp_code);
                $file_path = 'BudgetPlan/Files-' . auth()->user()->id . '/' . $file_name;
            }
            $count = count($request->subcategory);
            $report_data = array();
//            $data = array();

            $budget_plan_id = BudgetPlanIT::find($id);
            $budget_plan_id->new_budget = $request->new_budget ?? $budget_plan_id->new_budget;
            $budget_plan_id->other_req = $request->other_req ?? $budget_plan_id->other_req;
            $budget_plan_id->update();
            $relation_data = array();
            if ($budget_plan_id) {
                for ($i = 0; $i < $count; $i++) {
                    if ($request->upgrade_qty[$i] != "" || $request->new_qty[$i] != "" || $request->new_qty[$i] != 0 || $request->upgrade_qty[$i] != 0) {
                        $getLinkedSubcatIsNotCollection = LinkedSubcategory::where('subcategory_id', $request->subcategory[$i])->whereNotIn('linked_subcategory_id', $request->subcategory)->get();
                        foreach ($getLinkedSubcatIsNotCollection as $linked) {
                            if ($getLinkedSubcatIsNotCollection) {
//                                   $approxCost = str_replace(',','',$request->approx_cost[$i]) * ($request->upgrade_qty[$i]+$request->new_qty[$i]) ;
//                                   $tenPercent = $approxCost+($approxCost*10)/100;
                                $relation_data = [
                                    'plan_budget_id' => $id,
                                    'user_id' => auth()->user()->id,
                                    'year_id' => $request->year_id,
                                    'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                                    'subcategory_id' => $linked->linked_subcategory_id,
                                    'category_id' => Subcategory::find($linked->linked_subcategory_id)['category_id'],
                                    'upgraded_qty' => 0,
//                                    'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                                    'new_qty' => $request->new_qty[$i] ?? 0,
                                    'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                                    'remarks' => $request->remarks[$i],
                                ];
                                $data = [
                                    'user_id' => auth()->user()->id,
                                    'year_id' => $request->year_id,
                                    'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                                    'subcategory_id' => $linked->linked_subcategory_id,
                                    'category_id' => Subcategory::find($linked->linked_subcategory_id)['category_id'],
                                    'upgraded_qty' => 0,
//                                    'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                                    'new_qty' => $request->new_qty[$i] ?? 0,
                                    'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                                    'remarks' => $request->remarks[$i],
                                    'optional_file_path' => $file_path,
                                    'file_name' => $file_name,
                                    'file_size' => $file_size,
                                    'file_extension' => $extension,
                                    'other_req' => $request->other_req,
                                    'new_budget' => $request->new_budget,
                                    'is_agree' => $request->is_agree,
                                ];
                                $itPlan = BudgetPlanRelation::updateOrCreate([
                                    'user_id' => auth()->user()->id,
                                    'plan_budget_id' => $id,
                                    'subcategory_id' => $linked->linked_subcategory_id,
                                ],
                                    $relation_data
                                );
                                array_push($report_data, $data);
                            }
                        }
                        $relation_data = [
                            'plan_budget_id' => $id,
                            'user_id' => auth()->user()->id,
                            'year_id' => $request->year_id,
                            'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                            'subcategory_id' => $request->subcategory[$i],
                            'category_id' => Subcategory::find($request->subcategory[$i])['category_id'],
                            'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                            'new_qty' => $request->new_qty[$i] ?? 0,
                            'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                            'remarks' => $request->remarks[$i],
                        ];
                        $data = [
//                        'plan_budget_id' => $budget_plan->id,
                            'user_id' => auth()->user()->id,
                            'year_id' => $request->year_id,
                            'employee_code' => Employee::where('email', auth()->user()->email)->first()['emp_code'],
                            'subcategory_id' => $request->subcategory[$i],
                            'category_id' => Subcategory::find($request->subcategory[$i])['category_id'],
                            'upgraded_qty' => $request->upgrade_qty[$i] ?? 0,
                            'new_qty' => $request->new_qty[$i] ?? 0,
                            'approx_cost' => $request->approx_cost[$i] != null ? str_replace(',', '', $request->approx_cost[$i]) : 0,
                            'remarks' => $request->remarks[$i],
                            'optional_file_path' => $file_path,
                            'file_name' => $file_name,
                            'file_size' => $file_size,
                            'file_extension' => $extension,
                            'other_req' => $request->other_req,
                            'new_budget' => $request->new_budget,
                            'is_agree' => $request->is_agree,
                        ];
                        $user = User::find(auth()->user()->id);
//                        $itPlan = BudgetPlanRelation::create($relation_data);
                        $itPlan = $newUser = BudgetPlanRelation::updateOrCreate([
                            'user_id' => auth()->user()->id,
                            'plan_budget_id' => $id,
                            'subcategory_id' => $request->subcategory[$i],
                        ],
                            $relation_data
                        );
                        array_push($report_data, $data);
                    } else {
                        $deleteIt = BudgetPlanRelation::whereIn('upgraded_qty', array('', 0))->whereIn('new_qty', array('', 0))->delete();
                    }
                    $sum = 0;
                    foreach ($request->approx_cost as $val) {
                        if ($val != null && $val != 'null') {
                            $text = str_replace(",", "", $val);
                            $sum += (float)$text;
                        }
                    }
                }
                $other_data = [
                    'year' => Year::find($data["year_id"])['year'],
                    'employee_code' => $data["employee_code"],
                    'emp_name' => Employee::where('emp_code', $data["employee_code"])->first()['name'],
                    'other_req' => $data["other_req"],
                    'new_budget' => $data["new_budget"],
                    'total' => number_format($sum, 2),
                ];
                $reportCategory = array();
                $reportCategoryValue = array();
                foreach ($report_data as $report_datum) {
                    $reportData = DB::table('subcategories')->where('id', $report_datum['subcategory_id'])->where('is_budget_collection', 1)->first();
                    if ($reportData != null && $reportData != "null") {
                        $reportCategory[] = (array)$reportData;
                        $reportCategoryValue[] = $report_datum;
                    }
                }
                $unique_num = rand('9999', '99999');
                $pdf = PDF::loadView('budget_plan_report', ['data' => $data, 'other_data' => $other_data, 'report_data' => $report_data, 'reportCategory' => $reportCategory, "reportCategoryValue" => $reportCategoryValue])->save(public_path(str_replace(' ', '', ('BudgetPlanPdf/' . $unique_num . $user->name . 'budgetplan.pdf'))));
                $user->file_path = str_replace(' ', '', ('BudgetPlanPdf/' . $unique_num . $user->name . 'budgetplan.pdf'));
                $user->year = Year::find($request->year_id)['year'];
//                ,"abdulwajid@efulife.com","jibranmasood@efulife.com"
                Mail::send('emails.budget_plan_email', ['data' => $user], function ($message) use ($user) {
                    $message->to(auth()->user()->email)->subject('Budget IT Plan Email')->cc(["abdulwajid@efulife.com", "jibranmasood@efulife.com", "rizwanbukhari@efulife.com"]);
                    $message->from('itstore@efulife.com', 'Support IT Store');
                });
                $log = SystemLogs::Add_logs('email', null, 'email');

                return redirect()->back()->with('msg', 'Budget Plan Update Successfully!');
            } else {
                return redirect()->back()->with('msg', 'Something Went Wrong!');
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = BudgetPlanIT::find($id)->delete();
        if ($delete) {
            $budgetPlanRelation = BudgetPlanRelation::where('plan_budget_id', $id)->delete();
            return redirect()->back()->with('msg', 'Budget Plan Deleted Successfully!');
        }
        return redirect()->back()->with('msg', 'Something Went Wrong!');
    }
}
