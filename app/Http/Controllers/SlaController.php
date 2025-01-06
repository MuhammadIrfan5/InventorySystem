<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Subcategory;
use App\Type;
use App\Vendor;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\SLA;
use Illuminate\Validation\Rule;

class SlaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_sla', ['only' => ['index']]);
        $this->middleware('permission:add_sla', ['only' => ['store']]);
        $this->middleware('permission:delete_sla', ['only' => ['destroy']]);
        $this->middleware('permission:edit_sla',   ['only' => ['show','update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sla = SLA::all();
        return view('show_sla', ['data' => $sla]);
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
            'qty' => 'required',
            'type_id' => 'required',
            'vendor_id' => 'required',
            'agreement_start_date' => 'required',
            'agreement_end_date' => 'required',
            'current_sla_cost' => 'required',
            'current_dollar_rate' => 'required',
        ], [
            'qty.required' => 'The quantity field is required.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $created_by = auth()->id();
        $fields = $request->all();
        $unique_vendor = SLA::where('vendor_id', $request->vendor_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->where('category_id', 163)
            ->first();
        $fields['current_sla_cost'] = str_replace(",", "", $fields['current_sla_cost']);
        if ($unique_vendor == null) {
            $create = SLA::create($fields);
        }else{
            return redirect()->back()->with('error-msg', 'Vendor with this sub category exists already, Try Again!');
        }
        if ($create) {
            SLA::where('id', $create->id)->update(['category_id' => 163, 'created_by' => $created_by]);
            return redirect()->back()->with('msg', 'Service Level Agreement Added Successfully!');
        } else {
            return redirect()->back()->with('error-msg', 'Could not add Service Level Agreement, Try Again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sla = SLA::find($id);
        $data = array();
        $data['subcategories'] = Subcategory::where('status', 1)->where('category_id', '163')->orderBy('sub_cat_name', 'asc')->get();
        $data['vendors'] = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['types'] = Type::orderBy('type', 'asc')->get();
        $data['years'] = Year::where('locked', null)->orderBy('year', 'asc')->get();
        return view('edit_sla', ['data' => $data, 'sla' => $sla]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $update = SLA::find($id);
        $validator = Validator::make($request->all(), [
            'qty' => 'required',
            'type_id' => 'required',
            'vendor_id' => 'required',
            'agreement_start_date' => 'required',
            'agreement_end_date' => 'required',
            'current_sla_cost' => 'required',
            'current_dollar_rate' => 'required',
        ], [
            'qty.required' => 'The quantity field is required.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
        $update->category_id = 163;
        $update->subcategory_id = $fields['subcategory_id'];
        $update->type_id = $fields['type_id'];
        $update->year_id = $fields['year_id'];
        $update->vendor_id = $fields['vendor_id'];
        $update->qty = $fields['qty'];
        $update->agreement_start_date = $fields['agreement_start_date'];
        $update->agreement_end_date = $fields['agreement_end_date'];
        $update->remarks = $fields['remarks'];
        $update->current_sla_cost = str_replace(",", "", $fields['current_sla_cost']);
        $update->current_dollar_rate = $fields['current_dollar_rate'];
        $update->updated_by = auth()->id();
        $update->update();
        if ($update) {
            return redirect()->back()->with('msg', 'Service Level Agreement Added Successfully!');
        } else {
            return redirect()->back()->with('error-msg', 'Could not add Service Level Agreement, Try Again!');
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
        $find = SLA::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        return $find->delete() ? redirect()->back()->with('msg', 'Sla Deleted Successfully!') : redirect()->back()->with('error-msg', 'Could not delete sla, Try Again!');

    }
}
