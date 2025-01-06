<!DOCTYPE html>
<html>
<head>
	<title>bin card report</title>
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
                    <h2 style="font-weight:normal; line-height:1px;">Inventory Bin Card Report</h2>
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
                                                <th>Item Category</th>
                                                <th>Product S#</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Location</th>
                                                <th>Initial Status</th>
                                                <th>ActionBy</th>
                                                <th>Remarks</th>
                                                <th>ActionDate</th>
                                                <th>Actual Price</th>
                                                <th>Cost Price</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($inventories as $inventory)
                                            <tr>
                                                <td class='text-align-right'>{{ $i++ }}</td>
                                                <td>{{ $inventory->subcategory_id?$inventory->subcategory->sub_cat_name:'' }}</td>
                                                <td>{{ $inventory->product_sn }}</td>
                                                <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
                                                <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
                                                <td>{{ empty($inventory->location)?'':$inventory->location->location }}</td>
                                                <td>{{ empty($inventory->inventorytype)?'':$inventory->inventorytype->inventorytype_name }}</td>
                                                <td>{{ empty($inventory->added_by)?'':$inventory->added_by->name }}</td>
                                                <td>{{ $inventory->remarks }}</td>
                                                <td>{{ date('Y-m-d', strtotime($inventory->updated_at)) }}</td>
                                                <td class='text-align-right'>{{ number_format(round($inventory->item_price),2) }}</td>
                                                <td>{{ empty($inventory->repairing)?'':$inventory->repairing->price_value }}</td>
                                                <td>{{ empty($inventory->repairing)?'':$inventory->repairing->remarks }}</td>
                                            </tr>
                                        @endforeach 
                                        </tbody>
                                    </table>
</body>
</html>