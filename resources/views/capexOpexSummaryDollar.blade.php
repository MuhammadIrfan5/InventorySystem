<!DOCTYPE html>
<html>
<head>
    <title>report</title>
    <style>
        .secondary-table {
            width: 100%;
            border-spacing: 0px;
        }

        .secondary-table tr th, .secondary-table tr td {
            border: 1px solid;
            font-size: 14px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<table cellpadding="0" cellspacing="0" style="width:100%;">

    <tr class="text-center">
        <td class="text-center" style="width:85%; padding-left: 100px;">
            <h2>EFULife Assurance Ltd.</h2>
            <h2 style="font-weight:normal; line-height:1px;">Report</h2>
            <p style="padding:0; margin:0;" class="font-14"><b>Proposed IT Budget</b></p>
        </td>
        <td style="width:15%;">
            <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>
            <p style="font-size: 12px;"><b>Printed:</b></p>
            <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>
        </td>
    </tr>
</table>
<br>
<div class="card mb-4 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="secondary-table">
                <thead>
                <tr class="text-center">
                    <th>S.NO</th>
                    <th>Budget Year</th>
                    <th>Type</th>
                    <th>Dollar Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                ?>
                @foreach ($records as $budget)
                    <tr>
                        <td style="text-align:left;">{{ $i++ }}</td>
                        <td  class="text-right">{{ $budget['year']}}</td>
                        <td >{{ $budget['type']}}</td>
                        <td style="text-align:right">{{ number_format($budget['dollarAmount'],2)}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th style="text-align:right; width: 30px"><b>Capex Total</b></th>
                    <td style="text-align:right;width: 30px"><b>{{ $data['capexTotal'] }}</b></td>
                    <td class="text-right" style="width: 30px"><b>Opex Total</b></td>
                    <td style="text-align:right;width: 30px"><b>{{ $data['opexTotal'] }}</b></td>
                </tr>
                <tr>
                    <th colspan='2' style="text-align:right;"><b>Total</b></th>
                    <td colspan='2' style="text-align:right;"><b>{{ $data['total'] }}</b></td>
                </tr>
                </tfoot>
            </table>
{{--                                {{dd('us')}}--}}
        </div>
    </div>
</div>
</body>
</html>
