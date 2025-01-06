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
            <h2 style="font-weight:normal; line-height:1px;">SLA Report</h2>
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
        <th>Agreement Start Date</th>
        <th>Agreement End Date</th>
        <th>POC Name</th>
        <th>POC Contact</th>
        <th>POC Email</th>
        <th>Created By</th>
    </tr>
    </thead>

    <tbody>
    <?php $i = 1; ?>
    @foreach ($sla_data as $sla)
        <tr>
            <td class='text-align-right'>{{ $i++ }}</td>
            <td>{{ $sla->subcategory->sub_cat_name ?? 'No Subcategory'}}</td>
            <td>{{ $sla->vendor->vendor_name ?? 'No Vendor'}}</td>
            <td>{{ $sla->agreement_start_date ?? 'No Start Date'}}</td>
            <td>{{ $sla->agreement_end_date ?? 'No End Date'}}</td>
            <td>{{ $sla->vendor->contact_person ?? 'No Contact Person'}}</td>
            <td>{{ $sla->vendor->cell ?? 'No Cell'}}</td>
            <td>{{ $sla->vendor->email ?? 'No Email'}}</td>
            <td>{{ \App\User::find($sla->created_by)['name'] ?? 'No Name'}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
