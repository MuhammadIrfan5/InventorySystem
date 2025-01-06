<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use App\SystemLogs;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:category', ['only' => ['index']]);
        $this->middleware('permission:add_category', ['only' => ['store']]);
        $this->middleware('permission:delete_category', ['only' => ['destroy']]);
        $this->middleware('permission:edit_category',   ['only' => ['show','update']]);
    }
    public function index()
    {
        $category = Category::orderBy('category_name', 'asc')->get();
        return view('categories', ['categories' => $category]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'threshold' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('category_name'=>$request->category_name,'threshold'=>$request->threshold,'status'=>1 ,'is_budget_collection' => $request->is_budget_collection );
        $create = Category::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('categories',$fields,'insert');
            return redirect()->back()->with('msg', 'Category Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add category, Try Again!');
        }
    }

    public function show($id)
    {
        $category = Category::find($id);
        return view('edit_category', ['category'=> $category]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'threshold' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Category::where('id', $id)->update(['category_name'=>$request->category_name, 'threshold'=>$request->threshold, 'status'=>$request->status,'is_budget_collection' => $request->is_budget_collection]);
        if($update){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'categories',
                'meta_value'     => json_encode(Category::find($id)),
                'action_perform' => 'update',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Category Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update category, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Category::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::create([
            'user_id'        => Auth()->id(),
            'email'          => Auth::user()->email,
            'table_name'     => 'categories',
            'meta_value'     => json_encode($find),
            'action_perform' => 'delete',
            'ip'             => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'url'            => url()->full()
        ]);
        return $find->delete() ? redirect()->back()->with('msg', 'Category Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete category, Try Again!');
    }
}
