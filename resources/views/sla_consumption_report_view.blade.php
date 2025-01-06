<!DOCTYPE html>
<html>
<head>
    <title>balance report</title>
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
        table, th, td {
            border: 1px solid;
        }

    </style>
</head>
<body>
<table cellpadding="0" cellspacing="0" style="width:100%;">

    <tr class="text-center">
        <td class="text-center" style="width:85%; padding-left: 100px;">
            <h2>EFULife Assurance Ltd.</h2>
            <h2 style="font-weight:normal; line-height:1px;">SLA Complain Log Report</h2>
        </td>
        <td style="width:15%;">
            <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>
            <p style="font-size: 12px;"><b>Printed:</b></p>
            <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>
        </td>
    </tr>
</table>

<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th>S.No</th>
        <th>Category</th>
        <th>Vendor</th>
        <th>Total SLA Cost</th>
        @for($i=0;$i<12;$i++)
            <th> {{ $data['months'][$i] }}-{{$data['year_y']}}</th>
        @endfor
        <th>Expected Value</th>
        <th>Available</th>
        <th>Created At</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $i = 1;
    $total_sla_cost = 0;
    $jan = 0;
    $feb = 0;
    $mar = 0;
    $apr = 0;
    $may = 0;
    $jun = 0;
    $jul = 0;
    $aug = 0;
    $sept = 0;
    $oct = 0;
    $nov = 0;
    $dec  = 0;
    $expected_cost  = 0;
    $available_cost  = 0;
    ?>
    @foreach ($arr as $key => $log)
        <tr>
            <td class='text-align-right'>{{ $i++ }}</td>
            <td>{{ $log['sub_cat_id'] ?? '' }}</td>
            <td>{{ $log['vendor_id'] ?? 'No Vendor'}}</td>
            <td class="t_seperator text-align-right">{{ $log['sla']->current_sla_cost ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['1']['cost'] ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['2']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['3']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['4']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['5']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['6']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['7']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['8']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['9']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['10']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['11']['cost']  ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['12']['cost'] ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{  $log['sla']->consumed_sla_cost ?? '-'}}</td>
            <td class="t_seperator text-align-right">{{ ($log['sla']->current_sla_cost - $log['sla']->consumed_sla_cost) ?? '-'}}</td>
            <td>{{ $log['created_at'] .' / '. $log['created_at']->diffForHumans() ?? 'No Date'}}</td>
        </tr>
        <?php
        $total_sla_cost += $log['sla']->current_sla_cost ?? 0;
        $jan += $log['1']['cost'] ?? 0;
        $feb += $log['2']['cost'] ?? 0;
        $mar += $log['3']['cost'] ?? 0;
        $apr += $log['4']['cost'] ?? 0;
        $may += $log['5']['cost'] ?? 0;
        $jun += $log['6']['cost'] ?? 0;
        $jul += $log['7']['cost'] ?? 0;
        $aug += $log['8']['cost'] ?? 0;
        $sept += $log['9']['cost'] ?? 0;
        $oct += $log['10']['cost'] ?? 0;
        $nov += $log['11']['cost'] ?? 0;
        $dec += $log['12']['cost'] ?? 0;
        $expected_cost += $log['sla']->consumed_sla_cost ?? 0;
        $available_cost += ($log['sla']->current_sla_cost - $log['sla']->consumed_sla_cost) ?? 0;
        ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan='3' style="text-align:right;">Total</th>
        <td class="t_seperator text-align-right">{{$total_sla_cost == 0 ? '-' : $total_sla_cost}}</td>
        <td class="t_seperator text-align-right">{{ $jan == 0 ? '-' : $jan }}</td>
        <td class="t_seperator text-align-right">{{ $feb == 0 ? '-' : $feb}}</td>
        <td class="t_seperator text-align-right">{{ $mar == 0 ? '-' : $mar}}</td>
        <td class="t_seperator text-align-right">{{ $apr == 0 ? '-' : $apr}}</td>
        <td class="t_seperator text-align-right">{{ $may == 0 ? '-' : $may}}</td>
        <td class="t_seperator text-align-right">{{ $jun == 0 ? '-' : $jun}}</td>
        <td class="t_seperator text-align-right">{{ $jul == 0 ? '-' : $jul}}</td>
        <td class="t_seperator text-align-right">{{ $aug == 0 ? '-' : $aug}}</td>
        <td class="t_seperator text-align-right">{{ $sept == 0 ? '-' : $sept}}</td>
        <td class="t_seperator text-align-right">{{ $oct == 0 ? '-' : $oct}}</td>
        <td class="t_seperator text-align-right">{{ $nov == 0 ? '-' : $nov}}</td>
        <td class="t_seperator text-align-right">{{ $dec == 0 ? '-' : $dec}}</td>
        <td class="t_seperator text-align-right">{{ $expected_cost == 0 ? '-' : $expected_cost}}</td>
        <td class="t_seperator text-align-right">{{ $available_cost == 0 ? '-' : $available_cost}}</td>
        <td class="t_seperator text-align-right">-</td>
    </tr>
    </tfoot>
</table>

</body>
</html>
