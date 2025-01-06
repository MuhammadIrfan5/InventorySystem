<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use App\User;
use App\Issue;
use App\Inventory;
use App\Modal;
use App\Makee;
use App\Subcategory;
use App\InventoryIssueRecord;
use App\SystemLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Karachi");
    }

    static function SendUserMail($data)
    {
        $user = Employee::find($data['emp_id']);
        if (isset($user)) {
            $data = array(
                'user' => $data['emp_name'],
                'message' => $data['email_message'],
                'data' => $data,
                'url' => Url::signedRoute('received-email', ['issued_PK' => $data['issue_PK'], 'emp_id' => $data['emp_id'], 'status' => 'yes']),
                'url_reject' => Url::signedRoute('received-email', ['issued_PK' => $data['issue_PK'], 'emp_id' => $data['emp_id'], 'status' => 'no']),
            );
//            'inventory_name' => $data['inventory_name'],
//            $user->email
            Mail::send('emails.user_email', ['data' => $data], function ($message) use ($user) {
                $message->to($user->email)->subject
                ('Inventory Verification');
                $message->from('itstore@efulife.com', 'Support IT Store');
            });
            $log = SystemLogs::Add_logs('email', $data, 'email');
            return true;
//            ->cc(['helpdesk@efulife.com','munirali@efulife.com','jibranmasood@efulife.com','itstore@efulife.com'])
        } else {
            return false;
        }
    }

    //Acknowledge Received Inventories
    public function inventory_received(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        } else {

            $find_issue = InventoryIssueRecord::where('id', $request->issued_PK)->where('employee_id', $request->emp_id)->first();
            if ($find_issue != null) {
                $employee = Employee::find($request->emp_id);
                $inventory = Inventory::find($find_issue->inventory_id);
                $data = [
                    'emp_name' => $employee->name,
                    'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                    'make' => Makee::find($inventory->make_id)['make_name'],
                    'product_sn' => $inventory->product_sn,
                    'subcategory' => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
                ];
                if ($find_issue->received_status == 0 && $request->status == 'yes') {
                    $flag = 'yes';
                    return view('emails.inventory_feedback', compact('data', 'find_issue', 'flag'));
//                    return view('emails.issued', compact('status'));
                } elseif ($find_issue->received_status == 0 && $request->status == 'no') {
                    $flag = 'no';
                    return view('emails.inventory_feedback', compact('data', 'find_issue', 'flag'));
                } else {
                    $status = 'You have already submitted this request”.';
                    return view('emails.issued', compact('status'));
                }
            } else {
                $status = 'Something went wrong!';
                return view('emails.issued', compact('status'));
            }
        }
    }

    public function save_feedback(Request $request, $id, $flag)
    {
        $find = InventoryIssueRecord::find($id);

        $validator = Validator::make($request->all(), [
            'feedback' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        if ($find != null) {
            if ($find->rejecter_remarks == null && $find->rejecter_remarks_at == null && $find->received_at == null && $flag == 'no') {
                InventoryIssueRecord::where('id', $id)->update(['rejecter_remarks' => $request->feedback, 'rejecter_remarks_at' => date('Y-m-d H:i:s'), 'received_status' => 2]);
                $log = SystemLogs::Add_logs('inventory_issue_status', InventoryIssueRecord::find($id), 'insert');
//                return redirect()->away('https://www.google.com');
                $status = 'Successfully recorded your rejection remarks';
                return view('emails.issued', compact('status'));
//                return redirect()->back()->with('msg', 'Remarks Recorded Successfully!');
            }
            if ($find->receive_remarks == null && $find->received_at == null && $find->rejecter_remarks_at == null && $flag == 'yes') {
                InventoryIssueRecord::where('id', $id)->update(['receive_remarks' => $request->feedback, 'received_at' => date('Y-m-d H:i:s'), 'received_status' => 1]);
                $log = SystemLogs::Add_logs('inventory_issue_status', InventoryIssueRecord::find($id), 'insert');
//                return redirect()->away('https://www.google.com');
                $status = 'Successfully recorded your confirmation remarks';
                return view('emails.issued', compact('status'));
//                return redirect()->back()->with('msg', 'Remarks Recorded Successfully!');
            } else {
                $status = 'Already submitted your remarks';
                return view('emails.issued', compact('status'));
//            return redirect()->back()->with('msg', 'Already Recorded Successfully!');
            }
        } else {
            return redirect()->away('https://www.google.com');
        }
    }

    public function inventory_reverify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        } else {

            $find = InventoryIssueRecord::find($request->issue_PK);
            $employee = Employee::find($request->emp_id);
            if ($employee != null) {
                $inventory = Inventory::find($request->inventory_id);
                $data = [
                    'inventory_id' => $request->inventory_id,
                    'emp_id' => $request->emp_id,
                    'emp_name' => $employee->name,
                    'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                    'make' => Makee::find($inventory->make_id)['make_name'],
                    'product_sn' => $inventory->product_sn,
                    'subcategory' => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
                ];
                if ($request->status == 'reverify_yes' && $find->received_status == 0) {
                    $flag = 'reverify_yes';
                    return view('emails.reverify_inventory_feedback', compact('data', 'employee', 'flag','find'));
//                    return view('emails.issued', compact('status'));
                } elseif ($request->status == 'reverify_no'  && $find->received_status == 0) {
                    $flag = 'reverify_no';
                    return view('emails.reverify_inventory_feedback', compact('data', 'employee', 'flag','find'));
                } else {
                    $status = 'You have already submitted this request.';
                    return view('emails.issued', compact('status'));
                }
            } else {
                $status = 'Something went wrong!';
                return view('emails.issued', compact('status'));
            }
        }
    }

    public function save_reverify_inventory_feedback(Request $request, $emp_id, $inv_id, $flag,$issue_pk)
    {
        $validator = Validator::make($request->all(), [
            'feedback' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $employee = Employee::find($emp_id);
        $inventory = Inventory::find($inv_id);
        $find = InventoryIssueRecord::find($issue_pk);
        if ($flag == 'reverify_no' && $employee != null && $inventory != null && $find->rejecter_remarks == null && $find->rejecter_remarks_at == null && $find->received_at == null) {
           $issue = InventoryIssueRecord::where('id', $issue_pk)->update(
                [
                    'received_status'     => 2,
                    'rejecter_remarks'    => $request->feedback,
                    'rejecter_remarks_at' => date('Y-m-d H:i:s'),
                ]
            );
           // $log = SystemLogs::Add_logs('inventory_issue_status', InventoryIssueRecord::find($issue->id), 'insert');
            $status = 'Successfully recorded your rejection remarks';
            return view('emails.issued', compact('status'));
        }
        if ($flag == 'reverify_yes' && $employee != null && $inventory != null && $find->receive_remarks == null && $find->received_at == null && $find->rejecter_remarks_at == null) {
            $issue = InventoryIssueRecord::where('id', $issue_pk)->update(
                [
                    'received_status'     => 1,
                    'receive_remarks'     => $request->feedback,
                    'received_at'         => date('Y-m-d H:i:s'),
                ]
            );
           // $log = SystemLogs::Add_logs('inventory_issue_status', InventoryIssueRecord::find($issue->id), 'insert');
            $status = 'Successfully recorded your confirmation remarks';
            return view('emails.issued', compact('status'));
        } else {
            $status = 'Already submitted your remarks';
            return view('emails.issued', compact('status'));
        }

    }


}
