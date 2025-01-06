<!DOCTYPE html>
<html>
<head>
	<title>asset repairing report</title>
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
<table cellpadding="0" cellspacing="0" style="width:100%;">
            
            <tr class="text-center">
                <td class="text-center" style="width:85%; padding-left: 100px;">
                    <h2>EFULife Assurance Ltd.</h2>
                    <h2 style="font-weight:normal; line-height:1px;">Inventory Asset Repairing Report</h2>
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
                                                <th>Sub Category</th>
                                                <th>Product S#</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Issued to</th>
                                                <th>Location</th>
                                                <th>Repairing Date</th>
                                                <th>Actual Price</th>
                                                <th>Repairing Cost</th>
                                                <th>Cumulative Cost</th>
                                                <th>Initial Status</th>
                                                <th>Current Condition</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($repairs as $repair)
                                        <?php
                                        $total = $repair->actual_price_value+$repair->price_value;
                                        ?>
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ empty($repair->subcategory)?'':$repair->subcategory->sub_cat_name }}</td>
                                                <td>{{ empty($repair->item)?'':$repair->item->product_sn }}</td>
                                                <td>{{ empty($repair->item->make)?'':$repair->item->make->make_name }}</td>
                                                <td>{{ empty($repair->item->model)?'':$repair->item->model->model_name }}</td>
                                                <td>{{ empty($repair->item->user)?'':$repair->item->user->name }}</td>
                                                <td>{{ empty($repair->item->location)?'':$repair->item->location->location }}</td>
                                                <td>{{ date('d-M-Y' ,strtotime($repair->date)) }}</td>
                                                <td class='text-align-right'>{{ number_format($repair->actual_price_value,2) }}</td>
                                                <td class='text-align-right'>{{ number_format($repair->price_value,2) }}</td>
                                                <td class='text-align-right'>{{ number_format($total,2) }}</td>
                                                <td>{{ empty($repair->item->inventorytype)?'':$repair->item->inventorytype->inventorytype_name }}</td>
                                                <td>{{ empty($repair->item->devicetype)?'':$repair->item->devicetype->devicetype_name }}</td>
                                                <td>{{ $repair->remarks }}</td>
                                                
                                            </tr>
                                        @endforeach  
                                        </tbody>
                                    </table>
</body>
</html>