<?php

namespace App\Http\Controllers;
use App\LinkedSubcategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\SystemLogs;
class SubcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:sub_category', ['only' => ['index']]);
        $this->middleware('permission:add_subcategory', ['only' => ['store']]);
        $this->middleware('permission:delete_subcategory', ['only' => ['destroy']]);
        $this->middleware('permission:edit_subcategory',   ['only' => ['show','update']]);
    }
    public function index()
    {
        $category = Subcategory::all();
        return view('subcategories', ['subcategories' => $category]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:null',
            'sub_cat_name' => 'required',
            'threshold' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
//        dd($request->all());
//        $fields = array('category_id'=>$request->category_id,'sub_cat_name'=>$request->sub_cat_name,
//            'threshold'=>$request->threshold,'status'=>1,'is_budget_collection' => $request->is_budget_collection
//        , 'subcat_desc' => $request->subcat_desc);
//        $create = Subcategory::create($fields);

        $subcategory =new Subcategory();
        $subcategory->category_id = $request->category_id;
        $subcategory->sub_cat_name = $request->sub_cat_name;
        $subcategory->threshold = $request->threshold;
        $subcategory->status = $request->is_status??0;
        $subcategory->is_budget_collection = $request->is_budget_collection;
        $subcategory->subcat_desc = $request->subcat_desc;
//        $subcategory->is_fixed = $request->is_fixed;
        $subcategory->approx_price_pkr = $request->approx_amount_pkr;
        $subcategory->approx_price_dollar = $request->approx_amount_dollar;
        $subcategory->price_updated_at = $request->price_updated_at;
        $subcategory->save();
        if($request->subcategory_id != null) {
            $count_subcat = count($request->subcategory_id);

            for ($i = 0; $i < $count_subcat; $i++) {
                LinkedSubcategory::create([
                    'subcategory_id' => $subcategory->id,
                    'linked_category_id' => $request->category_id_new,
                    'linked_subcategory_id' => $request->subcategory_id[$i],
                    'user_id' => auth()->user()->id,
//                    'is_fixed' => $request->is_fixed == 1 ? '1' : '0',
                ]);
            }
        }

        if($subcategory){
            $log = SystemLogs::Add_logs('subcategories',$request->all() ,'insert');
            return redirect()->back()->with('msg', 'Sub Category Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add sub category, Try Again!');
        }
    }

    public function show($id)
    {
        $subcategory = Subcategory::find($id);
        $category = Category::orderBy('category_name', 'asc')->get();
        $linked_category = LinkedSubcategory::where('subcategory_id',$subcategory->id)->first()['linked_category_id'];
        $linked_subcategory = LinkedSubcategory::where('subcategory_id',$subcategory->id)->get();
        return view('edit_subcategory', ['categories'=> $category, 'subcategory'=> $subcategory,'linked_category' => $linked_category,'linked_subcategory' => $linked_subcategory]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'sub_cat_name' => 'required',
            'threshold' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

//        $update = Subcategory::where('id', $id)->update(['category_id'=>$request->category_id,'sub_cat_name'=>$request->sub_cat_name, 'threshold'=>$request->threshold, 'status'=>$request->status,'is_budget_collection' => $request->is_budget_collection,'subcat_desc' => $request->subcat_desc]);

        $subcategory =Subcategory::find($id);
        $subcategory->category_id = $request->category_id;
        $subcategory->sub_cat_name = $request->sub_cat_name;
        $subcategory->threshold = $request->threshold;
        $subcategory->status = 1;
        $subcategory->is_budget_collection = $request->is_budget_collection;
        $subcategory->subcat_desc = $request->subcat_desc;
//        $subcategory->is_fixed = $request->is_fixed;
        $subcategory->approx_price_pkr = $request->approx_amount_pkr;
        $subcategory->approx_price_dollar = $request->approx_amount_dollar;
        $subcategory->price_updated_at = $request->price_updated_at;
        $subcategory->update();
        if($request->subcategory_id != null) {
            $delete= LinkedSubcategory::where('subcategory_id',$subcategory->id)->delete();
            $count_subcat = count($request->subcategory_id);
            for ($i = 0; $i < $count_subcat; $i++) {
                LinkedSubcategory::create(
                    [
                        'subcategory_id' => $subcategory->id,
                        'linked_category_id' => $request->category_id_new,
                        'linked_subcategory_id' => $request->subcategory_id[$i],
                        'user_id' => auth()->user()->id,
//                        'is_fixed' => $request->is_fixed == 1 ? '1' : '0',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
//                DB::table('linked_subcategory')->updateOrInsert(
//                    ['subcategory_id' => $subcategory->id, 'linked_category_id' => $request->category_id_new, 'linked_subcategory_id' => $request->subcategory_id[$i]],
//                    [
//                        'subcategory_id' => $subcategory->id,
//                        'linked_category_id' => $request->category_id_new,
//                        'linked_subcategory_id' => $request->subcategory_id[$i],
//                        'user_id' => auth()->user()->id,
//                        'is_fixed' => $request->is_fixed == 1 ? '1' : '0',
//                        'created_at' => Carbon::now(),
//                        'updated_at' => Carbon::now(),
//                    ]
//                );
            }
        }else{
            $delete= LinkedSubcategory::where('subcategory_id',$subcategory->id)->delete();
        }

        if($subcategory){
            $log = SystemLogs::Add_logs('subcategories',Subcategory::find($id) ,'update');
            return redirect()->back()->with('msg', 'Sub Category Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update sub category, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Subcategory::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $linked_subcat = LinkedSubcategory::where('subcategory_id',$id)->delete();
        $log = SystemLogs::Add_logs('subcategories',$find ,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Sub Category Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete sub category, Try Again!');
    }
}
