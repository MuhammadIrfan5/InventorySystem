<?php

namespace App\Http\Controllers;

use App\Category;
use App\Inventory;
use App\Subcategory;
use App\Term;
use App\Type;
use App\Vendor;
use App\VendorTerm;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class VendorTermController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $terms = VendorTerm::all();
        foreach ($terms as $term){
            $term->vendor_id = Vendor::find($term->vendor_id);
            $term->category_id = Category::find($term->category_id);
            $term->subcategory_id = Subcategory::find($term->subcategory_id);
            $term->type_id = Type::find($term->type_id);
            $term->year_id = Year::find($term->year_id);
            $term->vendor_term_id = Term::find($term->vendor_term_id);
        }
        return view('vendor_terms', ['terms' => $terms]);
    }

    public function store_vendor_term( Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'vendor_id' => 'required|unique:vendortermrelation,vendor_id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
        $loggedin_user = Auth::id();
        $fields['added_by'] = $loggedin_user;
        $create = VendorTerm::create($fields);
        if($create){
            if($request->vendor_term_id == 1){
                $create->invoice_max_count = 12;
                $create->update();
            }
            else if($request->vendor_term_id == 2){
                $create->invoice_max_count = 1;
                $create->update();
            }
            else if($request->vendor_term_id == 3){
                $create->invoice_max_count = 4;
                $create->update();
            }
            else if($request->vendor_term_id == 4){
                $create->invoice_max_count = 2;
                $create->update();
            }
            else if($request->vendor_term_id == 5){
                $create->invoice_max_count = 1;
                $create->update();
            }
            return redirect()->back()->with('msg', 'Vendor Term Added Successfully!');
        }
        else{
            return redirect()->back()->with('error-msg', 'Could not add vendor term, Try Again!');
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $term = VendorTerm::find($id);
        return $term->delete() ? redirect()->back()->with('msg', 'Vendor Term Deleted Successfully!') : redirect()->back()->with('error-msg', 'Could not delete vendor term, Try Again!');
    }
}
