<!DOCTYPE html>
<html>
<head>
	<title>dispatch in report</title>
    <style>
    .secondary-table{
        width:100%;
        border-spacing: 0px;
    }
    .secondary-table tr th, .secondary-table tr td{
        border: 1px solid;
        font-size: 14px;
    }
    .text-center{
                text-align: center;
            }
    .font-14{
        font-size: 14px;
    }     
    .text-padding{
        padding: 50px;
        width: 25%;
    }
    .text-border-top{
        border-top:1px solid black;
    }
    </style>
</head>
<body>
<?php
$fields = (array)json_decode($filters);
$from = isset($fields['from_date'])?$fields['from_date']:null;
$to = isset($fields['to_date'])?$fields['to_date']:null;
?>
<table cellpadding="0" cellspacing="0" style="width:100%;">
            <tr class="text-center">
                <td class="text-center" style="width:85%; padding-left: 100px;">
                    <h2>EFULife Assurance Ltd.</h2>
                    <h2 style="font-weight:normal; line-height:1px;">Dispatch IN Report</h2>
                    <p style="font-size: 12px;"><b>From Date:</b>{{ empty($from)?'-':date('d-M-Y', strtotime($from)) }} <b>To Date:</b>{{ empty($to)?'-':date('d-M-Y', strtotime($to)) }}</p>
                </td>
                <td style="width:15%;">
                <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>
                <p style="font-size: 12px;"><b>Printed:</b></p>
                <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>
                </td>
            </tr>
        </table> <br> 
                                    <table class="secondary-table">
                                    <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Date IN</th>
                                                <th>Item</th>
                                                <th>Product S#</th>
                                                <th>Assigned To</th>
                                                <th>Branch Name</th>
                                                <th>BR. Code</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Other Accessories</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($dispatches as $disp)
                                            <tr>
                                                <td class='text-align-right'>{{ $i++ }}</td>
                                                <td>{{ date('d-M-Y', strtotime($disp->dispatchin_date)) }}</td>
                                                <td>{{ !empty($disp->subcategory)?$disp->subcategory->sub_cat_name:'' }}</td>
                                                <td>{{ !empty($disp->inventory)?$disp->inventory->product_sn:'' }}</td>
                                                <td>{{ !empty($disp->user)?$disp->user->name:'' }}</td>
                                                <td>{{ !empty($disp->user)?$disp->user->branch:'' }}</td>
                                                <td>{{ !empty($disp->user)?$disp->user->branch_id:'' }}</td>
                                                <td>{{ !empty($disp->inventory->make)?$disp->inventory->make->make_name:'' }}</td>
                                                <td>{{ !empty($disp->inventory->model)?$disp->inventory->model->model_name:'' }}</td>
                                                <td>{{ !empty($disp->inventory)?$disp->inventory->other_accessories:'' }}</td>
                                            </tr>
                                        @endforeach 
                                        </tbody>
                                    </table>                               
</body>
</html>