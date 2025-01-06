<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Inventory;
use App\Dispatchout;
use App\Modal;
use App\Employee;
use App\Makee;
use Illuminate\Support\Facades\Auth;
use App\SystemLogs;
class DispatchoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");

        $this->middleware('permission:dispatchout', ['only' => ['index']]);
        $this->middleware('permission:add_dispatchout', ['only' => ['store']]);
        $this->middleware('permission:delete_dispatchout', ['only' => ['destroy']]);
        $this->middleware('permission:edit_dispatchout',   ['only' => ['show','update']]);
    }

    public function index()
    {
        $dispatch = Dispatchout::all();
        return view('dispatchout', ['dispatches' => $dispatch]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'inventory_id' => 'required',
            'dispatchout_date' => 'required',
            'insured' => 'required',
            'assigned_user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $dispatch=Dispatchout::where('inventory_id',$request->inventory_id)->whereDate('created_at','=',Carbon::today())->first();
        if(empty($dispatch)){
        $fields = array(
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'inventory_id' => $request->inventory_id,
            'dispatchout_date' => $request->dispatchout_date,
            'remarks' => $request->remarks,
            'insured' => $request->insured

        );
        $create = Dispatchout::create($fields);
        if($create){
            $update = Inventory::where('id', $request->inventory_id)->update(['devicetype_id'=>3]);
            $user = Employee::find($request->assigned_user_id);
            if (isset($user)) {
                $inventory = Inventory::find($request->inventory_id);
                $data = array(
                    'user' => $user->name,
                    'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                    'make' => Makee::find($inventory->make_id)['make_name'],
                    'product_sn' => $inventory->product_sn,
                    'subcategory' => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
                    'message' => "Dispatch OUT Email",
                    'dispatch_out_date' => $request->dispatchout_date
                );
//                ['helpdesk@efulife.com','munirali@efulife.com',','jibranmasood@efulife.com']
                Mail::send('emails.dispatch_out_email', ['data' => $data], function ($message) use ($user) {
                    $message->to($user->email)->cc(['helpdesk@efulife.com','munirali@efulife.com' ,'itstore@efulife.com'])->subject
                    ('Inventory Dispatch OUT');
                    $message->from('itstore@efulife.com', 'Support IT Store');
                });
                $log = SystemLogs::Add_logs('email', $data, 'email');
            }
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'dispatchouts',
                'meta_value'     => json_encode($request->all()),
                'action_perform' => 'insert',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Dispatch Out Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add dispatch out, Try Again!');
        }
        }
        else{
            return redirect()->back()->with('msg', 'Equipment already dispatch out today!');
        }
    }

    public function show($id)
    {
        $data = array();
        $data['dispatch'] = Dispatchout::find($id);
        $data['categories'] = Category::where('status',1)->orderBy('category_name', 'asc')->get();
        return view('edit_dispatchout', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'inventory_id' => 'required',
            'dispatchout_date' => 'required',
            'insured' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array(
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'inventory_id' => $request->inventory_id,
            'dispatchout_date' => $request->dispatchout_date,
            'remarks' => $request->remarks,
            'insured' => $request->insured
        );
        $update = Dispatchout::where('id', $id)->update($fields);
        if($update){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'dispatchouts',
                'meta_value'     => json_encode(Dispatchout::find($id)),
                'action_perform' => 'update',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Dispatch Out Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update dispatch out, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Dispatchout::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::create([
            'user_id'        => Auth()->id(),
            'email'          => Auth::user()->email,
            'table_name'     => 'dispatchouts',
            'meta_value'     => json_encode($find),
            'action_perform' => 'delete',
            'ip'             => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'url'            => url()->full()
        ]);
        return $find->delete() ? redirect()->back()->with('msg', 'Dispatch Out Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete dispatch out, Try Again!');
    }
}
