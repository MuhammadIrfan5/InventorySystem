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
//$fields = (array)json_decode($filters);
//$from = isset($fields['from_date'])?$fields['from_date']:null;
//$to = isset($fields['to_date'])?$fields['to_date']:null;
?>
<table cellpadding="0" cellspacing="0" style="width:100%;">

    <tr class="text-center">
        <td class="text-center" style="width:85%; padding-left: 100px;">
            <h2>EFULife Assurance Ltd.</h2>
            <h2 style="font-weight:normal; line-height:1px;">SLA Complain Log Report</h2>
            <p style="font-size: 12px;"><b>Date:</b>{{ date('d-M-Y') }} </p>
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
        <th>Service Name</th>
        <th>Vendor</th>
        <th>Issue Product SN</th>
        <th>Issue Product Make</th>
        <th>Issue Product Model</th>
        <th>Issued To</th>
        <th>Replace Product SN</th>
        <th>Replace Product Make</th>
        <th>Replace Product Model</th>
        <th>Type</th>
        <th>Created By</th>
        <th>Created At</th>
    </tr>
    </thead>

    <tbody>
    <?php $i = 1; ?>
    @foreach ($sla_complain_data as $sla)
        <tr>
            <td class='text-align-right'>{{ $i++ }}</td>
            <td>{{ $sla->subcategory->sub_cat_name ?? 'No Subcategory'}}</td>
            <td>{{ $sla->vendor->vendor_name ?? 'No Vendor'}}</td>
            <td>{{ $sla->issue_product_sn ?? 'No SN'}}</td>
            <td>{{ \App\Makee::find($sla->issue_make_id)['make_name'] ?? 'No Make'}}</td>
            <td>{{ \App\Modal::find($sla->issue_model_id)['model_name'] ?? 'No Model'}}</td>
            <td>{{ \App\Employee::where('emp_code', $sla->issued_to)->first()['name'] ?? 'Not Issued'}}</td>
            <td>{{ $sla->replace_product_sn ?? 'No SN'}}</td>
            <td>{{ \App\Makee::find($sla->replace_product_make_id)['make_name'] ?? 'No Make'}}</td>
            <td>{{ \App\Modal::find($sla->replace_product_model_id)['model_name'] ?? 'No Model'}}</td>
            @if($sla->replace_type == 1)
                <td>Replace</td>
            @elseif($sla->replace_type == 1)
                <td>Repair</td>
            @else
                <td>Non Repairable</td>
            @endif
            <td>{{ \App\User::find($sla->added_by)['name'] ?? 'No Name'}}</td>
            <td>{{$sla->created_at ?? 'No Date'}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
