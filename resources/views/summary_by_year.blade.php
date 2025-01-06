<!DOCTYPE html>
<html>
<head>
    <title>EFULIFE IT BUDGET REQUIREMENT</title>
    <style>

        .secondary-table {
            width: 100%;
            border-spacing: 0px;
        }

        .secondary-table tr th, .secondary-table tr td {
            border: 1px solid;
            font-size: 14px;
            border-spacing: 0px;
            border-collapse: collapse;
        }

        .inner-table {
            width: 100%;
            border-spacing: 0px;
            border: none;
        }

        .inner-table tr th, .inner-table tr td {
            width: 33%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<table cellpadding="0" cellspacing="0" style="width:100%;">

    <tr class="text-center">
        <td class="text-center" style="width:85%; padding-left: 100px;">
            <h2>EFU Life Assurance Ltd.</h2>
            <h2 style="font-weight:normal; line-height:1px;">EFULIFE IT SUMMARY BY YEAR</h2>
{{--            <p style="padding:0; margin:0;" class="font-14"><b>Proposed IT Budget - {{ $inventory['year'] }}</b></p>--}}
        </td>
        <td style="width:20%;">

            <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>
            <p style="font-size: 12px;"><b>Printed:</b></p>
            {{date_default_timezone_set('Asia/Karachi')}}
            <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:s a') }}</p>
        </td>
    </tr>
</table>
<br>
{{--@foreach($types as $key=>$type)--}}
<div class="card mb-4 mt-3">
    <div class="card-body">
        <div class="text-left" style="border:1px solid; margin-top:10px;">
{{--            <h4>Name : {{ $other_data['emp_name'] }}</h4>--}}
            <h4>Department :{{' '. auth()->user()->department }}</h4>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="secondary-table " cellpadding="1" cellspacing="1">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th>Budget Year</th>
                    <th>Type</th>
                    <th>Dollar Amount $</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($data))
                    <?php $i = 1;
                    $capexTotal = 0;
                    $opexTotal = 0;
                    $bothTotal = 0;
                    ?>
                    @foreach ($data as $inventory)
                        <tr >
                            <td>{{ $i++ }}</td>
                            <td >{{ $inventory['year'] }}</td>
                            <td>{{ $inventory['type']}}</td>
                            <td class="text-right">{{ '$'.number_format((int)$inventory['dollarAmount'])}}</td>
                            @if($inventory['type'] == "Capital Expenditure")
                                <?php $capexTotal += $inventory['dollarAmount'] ?>
                            @else
                                <?php $opexTotal += $inventory['dollarAmount']?>
                            @endif()
                        </tr>
                    @endforeach
                @endif
                <tr>
                <td style="border:1px solid" class="footer text-right"><b>Capex</td>
                <td style="border:1px solid"class="footer text-right"><b>{{"$ ".number_format((int)$capexTotal)}}</td>
                <td style="border:1px solid" class="footer text-right"><b>Opex</td>
                <td style="border:1px solid"class="footer text-right"><b>{{"$ ". number_format((int)$opexTotal) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="border:1px solid" class="footer text-right"><b>Grand Total</td>
                    <td class="footer text-right"><b>{{"$ ".number_format((int)$opexTotal+$capexTotal)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{--@endforeach--}}

</body>
</html>
