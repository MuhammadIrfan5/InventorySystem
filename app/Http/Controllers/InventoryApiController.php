<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeBranch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Subcategory;
use App\Inventory;
use App\User;
use App\Year;
use App\Type;
use App\Budgetitem as Budget;
use App\Employee;

class InventoryApiController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Karachi");
    }

    public function get_user_data(){

//        $validatedRules =[
//            'secret_key' => 'required|string|max:255',
//        ];
//        $data = $request->only(['secret_key']);
//        $validator = Validator::make($data, $validatedRules);
//        if ($validator->fails()) {
//            $messages = $validator->messages()->all();
//            return response([
//                'status' => true,
//                'data' => null,
//                'message' => $messages[0],
//            ], 403);
//        } else {
//            if (md5('1208_jibranmasoodkhan') == md5($data['secret_key'])) {
                $data = Inventory::all();
//                $users = Employee::all();
//
//                foreach ($users as $user) {
//                    $inventories = Inventory::where('issued_to', $user->emp_code)->get();
//                    foreach ($inventories as $inventory) {
//                        $inventory->user = $user;
//                        array_push($data, $inventory);
//                    }
//                }
                return response([
                    'status' => true,
                    'data' => $data,
                    'message' => "Employee Data",
                    'code' => '200'
                ], 200);
//            }else{
//                return response([
//                    'status' => true,
//                    'data' => null,
//                    'message' => "Invalid Attempt",
//                    'code' => '403'
//                ], 200);
//            }
//        }
    }
}
