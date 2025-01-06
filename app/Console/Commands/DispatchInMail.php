<?php

namespace App\Console\Commands;

use App\EmployeeBranch;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Employee;
use App\Inventory;
use App\Modal;
use App\Makee;
use App\Subcategory;
use App\Dispatchin;
use PDF;

class DispatchInMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatchin:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Dispatch IN Email With Report';

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

        $inventories = Dispatchin::where('dispatchin_date', Carbon::today()->toDateString())->orderBy('id', 'desc')->get();
        foreach ($inventories as $inventory) {
            if (!empty($inventory->inventory)) {
                $user = Employee::where('emp_code', $inventory->inventory->issued_to)->first();
                if ($user) {
                    $branch_id_data = EmployeeBranch::where('branch_name', $user->department)->where('emp_code', $inventory->inventory->issued_to)->first();
                    $inventory->user = $user;
                    $inventory->user->branch_id = $branch_id_data->branch_id;
                }
            }
        }

        $file_name = time() . 'dispatchin_report.pdf';
        $pdf = PDF::loadView('daily_disptachin_report', ['dispatches' => $inventories])->setPaper('a4', 'landscape')->save(public_path('dispatchin_reports/' . $file_name));
//        $pdf->download('dispatchin_report.pdf');
        $date = Carbon::parse(Carbon::today()->toDateString(), 'Asia/karachi');
        $format = $date->isoFormat('Do MMMM YYYY');
        $data = array(
            'dispatch_in_date' => $format,
            'file_link' => $file_name,
            'live_url' => 'http://inventory.efulife.online/',
        );
//        $user_emails = ['muhammadirfan5891@gmail.com', 'jibranmasood@efulife.com', 'irfannadeem5@gmail.com'];
        $user_emails = ['helpdesk@efulife.com','munirali@efulife.com','itstore@efulife.com'];
        Mail::send('emails.dispatchin_report_email', ['data' => $data], function ($message) use ($user_emails) {
//
            $date = Carbon::parse(Carbon::today()->toDateString(), 'Asia/karachi');
            $format = $date->isoFormat('Do MMMM YYYY');
            $message->to('dispatchin@efulife.com')->subject
            ('Dispatch IN Report ' . $format);
            $message->from('itstore@efulife.com', 'Support IT Store');
        });
        \Log::info("Cron is working fine!");

    }
}
