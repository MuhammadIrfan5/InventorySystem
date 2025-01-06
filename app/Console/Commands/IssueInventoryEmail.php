<?php

namespace App\Console\Commands;

use App\Employee;
use App\Inventory;
use App\InventoryIssueRecord;
use App\Makee;
use App\Modal;
use App\Subcategory;
use App\SystemLogs;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class IssueInventoryEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issueEmail:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email After Seven Days of Inventory Issuance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        date_default_timezone_set('Asia/karachi');
        $before_seven = Carbon::now()->subDays(7)->format('Y-m-d');
        $issue = InventoryIssueRecord::where('issued_at', $before_seven)->where('received_status', 0)->get();
        if (count($issue) > 0) {
            foreach ($issue as $issue_data) {
                $user = Employee::find($issue_data->employee_id);
                if ($user != null) {
                    $inventory = Inventory::find($issue_data->inventory_id);
                    $email_data = [
                        'emp_id' => $issue_data->employee_id,
                        'emp_code' => $issue_data->employee_code,
                        'emp_email' => $user->email,
                        'emp_name' => $user->name,
                        'email_message' => 'Inventory has been issued.',
                        'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                        'issue_PK' => $issue_data->id,
                        'make' => Makee::find($inventory->make_id)['make_name'],
                        'product_sn' => $inventory->product_sn,
                        'subcategory' => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
                    ];
                    if (isset($user)) {
                        $data = array(
                            'user' => $email_data['emp_name'],
                            'message' => $email_data['email_message'],
                            'data' => $email_data,
                            'url' => Url::signedRoute('received-email', ['issued_PK' => $issue_data->id, 'emp_id' => $issue_data->employee_id, 'status' => 'yes']),
                            'url_reject' => Url::signedRoute('received-email', ['issued_PK' => $issue_data->id, 'emp_id' => $issue_data->employee_id, 'status' => 'no']),
                        );
                        $user_emails = ['muhammadirfan5891@gmail.com', 'irfannadeem5@gmail.com'];
//                        'itstore@efulife.com'
                        Mail::send('emails.user_email', ['data' => $data], function ($message) use ($data) {
                            $message->to($data['user'])->subject
                            ('Inventory Verification');
                            $message->from('itstore@efulife.com', 'Support IT Store');
                        });
                        $log = SystemLogs::Add_logs('email', $data, 'email');
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        \Log::info("Cron is working fine!");
    }
}
