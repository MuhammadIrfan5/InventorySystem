<!DOCTYPE html>
<html>
<head>
	<title>inventory report</title>
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
                    <h2 style="font-weight:normal; line-height:1px;">Inventory OUT Report</h2>
                </td>
                <td style="width:15%;">
                <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>
                <p style="font-size: 12px;"><b>Printed:</b></p>
                <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>
                </td>
            </tr>
        </table>  <br>
                                    <table class="secondary-table">
                                    <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Item Category</th>
                                                <th>Product S#</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Issue to</th>
                                                <th>Location</th>
                                                <th>Issue By</th>
                                                <th>Issue Date</th>
                                                <th>Purchase Date</th>
                                                <th>Initial Status</th>
                                                <th>Current Condition</th>
                                                <th>Base Remarks</th>
                                                <th>Issuance Remarks</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($inventories as $inventory)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $inventory->subcategory_id?$inventory->subcategory->sub_cat_name:'' }}</td>
                                                <td>{{ $inventory->product_sn }}</td>
                                                <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
                                                <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
                                                <td>{{ empty($inventory->issued_to)?'':$inventory->employee->name }}</td>
                                                <td>{{ empty($inventory->location)?'':$inventory->employee->department }}</td>
                                                <td>{{ \App\User::find($inventory->issued_by)['name'] ??''}}</td>
{{--                                                <td>{{ empty($inventory->issue_date)?'':date('d-M-Y' ,strtotime($inventory->issue_date->created_at)) }}</td>--}}
                                                <td>{{ \App\Issue::where('inventory_id', $inventory->id)->select('created_at')->orderBy('id', 'desc')->first()['created_at'] }}</td>
                                                <td>{{ empty($inventory->purchase_date)?'':date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>
                                                <td>{{ empty($inventory->inventorytype_id)?'':$inventory->inventorytype->inventorytype_name }}</td>
                                                <td>{{ empty($inventory->devicetype_id)?'':$inventory->devicetype->devicetype_name }}</td>
                                                <td>{{ $inventory->remarks }}</td>
                                                <td>{{ empty($inv->issue->remarks)?"":$inv->issue->remarks }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
</body>
</html>
