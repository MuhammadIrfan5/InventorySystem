<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Inventory;
use App\Makee;
use App\Modal;
use App\SLA;
use App\SlaLogType;
use App\Subcategory;
use App\Type;
use App\User;
use App\SLAComplainLog;
use App\Vendor;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SlaComplainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sla_log = SLAComplainLog::all();
        return view('sla_log', ['data' => $sla_log]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function get_sla_total_cost($type_id,$year_id,$sub_cat_id){

        $sla= SLA::where('type_id',$type_id)->where('year_id',$year_id)
            ->where('subcategory_id',$sub_cat_id)->first();
        if($sla != null){
            return $sla;
        }
        return 0;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'required',
            'vendor_id' => 'required',
            'type_id' => 'required',
            'year_id' => 'required',
            'issue_product_sn' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $added_by = auth()->id();

        $fields = $request->except([
            'make_id',
            'model_id',
            'issued_to',
            'replace_product_make_id',
            'replace_product_model_id'
        ]);


        if(count($request->issue_product_sn)){
            $consumed_cost = 0;
            for($i=0;$i<count($request->issue_product_sn);$i++){
                $inventory = Inventory::find($request->issue_product_sn);
                $replace_inventory = Inventory::find($request->replace_product_sn);
                SLAComplainLog::create(
                    [
                        'category_id' => 163 ,
                        'subcategory_id' => $request->subcategory_id ,
                        'added_by' => $added_by,
                        'type_id' =>  $request->type_id,
                        'year_id' => $request->year_id,
                        'vendor_id' => $request->vendor_id,
                        'sla_type'  => ($request->sla_type == '0' ? null : $request->sla_type),
                        'issue_product_sn' => $inventory[$i]->product_sn ?? null,
                        'issue_make_id' => $inventory[$i]->make_id ?? null,
                        'issue_model_id' => $inventory[$i]->model_id ?? null,
                        'issued_to' => $inventory[$i]->issued_to ?? null,
                        'issue_description' => $request->issue_description[$i] ?? null,
                        'issue_occur_date' => $request->issue_occur_date[$i] ?? null,
                        'visit_date_time' => $request->visit_date_time[$i] ?? null,
                        'engineer_detail' => $request->engineer_detail[$i] ?? null,
                        'handed_over_date' => $request->handed_over_date[$i] ?? null,
                        'replace_type' => $request->replace_type[$i] ?? null,
                        'replace_product_sn' => $replace_inventory[$i]->product_sn ?? null,
                        'replace_product_make_id' => $replace_inventory[$i]->make_id?? null,
                        'replace_product_model_id' => $replace_inventory[$i]->model_id ?? null,
                        'issue_resolve_date' => $request->issue_resolve_date[$i] ?? null,
                        'cost_occured' => str_replace(",", "", $request->cost_occured[$i]) ?? null,
                        'current_dollar_rate' => str_replace(",", "", $request->current_dollar_rate[$i]) ?? null,
                        'status' => 1
                    ]);
                $consumed_cost += str_replace(",", "", $request->cost_occured[$i]);
            }
            $sla = SLA::where('type_id',$request->type_id)->where('year_id',$request->year_id)
                ->where('subcategory_id',$request->subcategory_id)->first();
            $sla->consumed_sla_cost = $sla->consumed_sla_cost + $consumed_cost;
            $sla->update();
            return redirect()->back()->with('msg', 'Service Level Agreement Complain Log Added Successfully!');
        }
        else{
            return redirect()->back()->with('error-msg', 'Could not add Service Level Agreement Complain Log, Try Again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sla_log = SLAComplainLog::find($id);
        $data = array();
        $vendors = array();
        $data['subcategories'] = Subcategory::where('status',1)->where('category_id','163')->orderBy('sub_cat_name', 'asc')->get();
        //$data['vendors'] = Vendor::orderBy('vendor_name', 'asc')->get();
        $sla_vendors = SLAComplainLog::select('vendor_id')->distinct()->orderBy('vendor_id','ASC')->get();
        foreach ($sla_vendors as $vendor){
            $vendors[] = Vendor::where('id', $vendor->vendor_id)->orderBy('vendor_name', 'asc')->first();
        }

        $data['types'] = Type::orderBy('type', 'asc')->get();
        $data['years'] = Year::where('locked', null)->orderBy('year', 'asc')->get();
        $make = Makee::where('status',1)->orderBy('make_name', 'asc')->get();
        $modal= Modal::where('status',1)->orderBy('model_name', 'asc')->get();
        $employee= Employee::all();
        $data['issue_product_sn'] = Inventory::select('id','product_sn')->distinct()->orderBy('product_sn', 'asc')->get();
        $data['sla_types'] = SlaLogType::all();
        return view('edit_sla_log', ['data' => $data, 'sla_log' => $sla_log , 'employee' => $employee , 'make' => $make,'modal' => $modal,'vendors' => $vendors]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'required',
            'vendor_id' => 'required',
            'type_id' => 'required',
            'year_id' => 'required',
//            'visit_date_time' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $sla_log = SLAComplainLog::find($id);
        $added_by = auth()->id();
        $fields = $request->except([
            'make_id',
            'model_id',
            'issued_to',
            'replace_product_make_id',
            'replace_product_model_id'
        ]);
        unset($fields['_token']);
        unset($fields['_method']);
        unset($fields['add_sla_log']);
        $create = SLAComplainLog::where('id', $sla_log->id)->update($fields);

        if($create){
            $inventory = Inventory::find($fields['issue_product_sn']);
            $replace_inventory = Inventory::find($fields['replace_product_sn']);

            SLAComplainLog::where('id', $sla_log->id)->update(
                [
                    'category_id' => 163 ,
                    'issue_product_sn' => $inventory->product_sn ?? null,
                    'replace_product_sn' => $replace_inventory->product_sn ?? null,
                    'issue_make_id' => $inventory->make_id ?? null,
                    'issue_model_id' => $inventory->model_id ?? null,
                    'issued_to' => $inventory->issued_to ?? null,
                    'replace_product_make_id' => $replace_inventory->make_id ?? null,
                    'replace_product_model_id' => $replace_inventory->model_id ?? null,
                    'status' => 1
                ]
            );
            return redirect()->back()->with('msg', 'Service Level Agreement Complain Log Updated Successfully!');
        }
        else{
            return redirect()->back()->with('error-msg', 'Could not update Service Level Agreement Complain Log, Try Again!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = SLAComplainLog::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        return $find->delete() ? redirect()->back()->with('msg', 'Sla Log Deleted Successfully!') : redirect()->back()->with('error-msg', 'Could not delete sla log, Try Again!');
    }
}
