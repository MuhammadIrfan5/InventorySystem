<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Inventory;
use App\Disposal;
use App\Disposalstatus;
use App\SystemLogs;
class DisposalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:disposal', ['only' => ['index']]);
        $this->middleware('permission:add_disposal', ['only' => ['store']]);
        $this->middleware('permission:edit_disposal', ['only' => ['show','update']]);
        $this->middleware('permission:delete_disposal',   ['only' => ['destroy']]);
    }

    public function index()
    {
        $disposal = Disposal::all();
        return view('disposal', ['disposals' => $disposal]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'inventory_id' => 'required',
            'dispose_date' => 'required',
            'disposalstatus_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array(
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'inventory_id' => $request->inventory_id,
            'dispose_date' => $request->dispose_date,
            'disposalstatus_id' => $request->disposalstatus_id,
            'handover_date' => $request->handover_date,
            'remarks' => $request->remarks

        );
        $create = Disposal::create($fields);
        if($create){
            if($request->disposalstatus_id == 4){
                $update = Inventory::where('id', $request->inventory_id)->update(['devicetype_id' => 5]);
            }else{
                $update = Inventory::where('id', $request->inventory_id)->update(['devicetype_id' => 1]);
            }

            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'disposals',
                'meta_value'     => json_encode($request->all()),
                'action_perform' => 'insert',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Disposal Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add Disposal, Try Again!');
        }
    }

    public function show($id)
    {
        $data = array();
        $data['disposal'] = Disposal::find($id);
        $data['categories'] = Category::where('status',1)->orderBy('category_name', 'asc')->get();
        //$data['inventories'] = Inventory::where('issued_to', NULL)->whereIn('status', [1,2])->orderBy('id', 'desc')->get();
        $data['statuses'] = Disposalstatus::all();
        //return $data;
        return view('edit_disposal', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'inventory_id' => 'required',
            'dispose_date' => 'required',
            'disposalstatus_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array(
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'inventory_id' => $request->inventory_id,
            'dispose_date' => $request->dispose_date,
            'disposalstatus_id' => $request->disposalstatus_id,
            'handover_date' => $request->handover_date,
            'remarks' => $request->remarks
        );
        $update = Disposal::where('id', $id)->update($fields);
        if($update){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'disposals',
                'meta_value'     => json_encode(Disposal::find($id)),
                'action_perform' => 'update',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Disposal Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update Disposal, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Disposal::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::create([
            'user_id'        => Auth()->id(),
            'email'          => Auth::user()->email,
            'table_name'     => 'disposals',
            'meta_value'     => json_encode($find),
            'action_perform' => 'delete',
            'ip'             => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'url'            => url()->full()
        ]);
        return $find->delete() ? redirect()->back()->with('msg', 'Disposal Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete Disposal, Try Again!');
    }
}
