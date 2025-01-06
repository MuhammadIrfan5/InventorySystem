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
            <h2>EFULife Assurance Ltd.</h2>
            <h2 style="font-weight:normal; line-height:1px;">EFULIFE IT BUDGET REQUIREMENT</h2>
            <p style="padding:0; margin:0;" class="font-14"><b>Proposed IT Budget - {{ $other_data['year'] }}</b></p>
        </td>
        <td style="width:20%;">
            <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>
            <p style="font-size: 12px;"><b>Printed:</b></p>
            <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>
        </td>
    </tr>
</table>
<br>
{{--@foreach($types as $key=>$type)--}}
<div class="card mb-4 mt-3">
    <div class="card-body">
        <div class="text-left" style="border:1px solid; margin-top:10px;">
            <h4>Name : {{ $other_data['emp_name'] }}</h4>
            <h4>Department :{{' '. auth()->user()->department }}</h4>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="secondary-table " cellpadding="1" cellspacing="1">
                <thead>
                <tr>
                    <th>Items</th>
                    <th>New/PRF Qty</th>
                    <th>Upgrade Qty</th>
{{--                    <th>Budget Type</th>--}}
{{--                    <th>Project Year</th>--}}
                    <th>Approx Cost</th>
                    <th style="width:20%">Remarks</th>
                </tr>
                </thead>
                @foreach($reportCategory as $key=> $item)
                    <input type="hidden"
                           value="{{ $linked=\App\LinkedSubcategory::where('subcategory_id',$item['id'])->get() }}">
                    <tbody>
                    <tr style="border: 1px solid black">
                        <td class="text-r firstLine" colspan="1">
                            {{($item['sub_cat_name'])}}
                            @if(count($linked)>0)
                                <ul style="border-top: 2px solid black; list-style: none;">
                                    @foreach($linked as $i)
                                        <li style="border-bottom: 2px solid black ;margin-left: -40px"> {{\App\Subcategory::find($i['linked_subcategory_id'])['sub_cat_name']}}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $reportCategoryValue[$key]['new_qty'] }}
                            @if(count($linked)>0)
                                <ul style="border-top: 2px solid black; list-style: none; margin-left: -40px">
                                    @foreach($linked as $i)
                                        <li style="border-bottom: 2px solid black"> {{ $reportCategoryValue[$key]['new_qty'] }} </li>
                                        {{--                                        <li> {{\App\Subcategory::find($i['linked_subcategory_id'])['sub_cat_name']}}</li>--}}
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td class="text-center">{{ $reportCategoryValue[$key]['upgraded_qty'] }}
                            @if(count($linked)>0)
                                <ul style="border-top: 2px solid black; list-style: none; margin-left: -40px">
                                    @foreach($linked as $i)
                                        <li style="border-bottom: 2px solid black"> {{"-"}}
                                        </li>
                                        {{--                                        <li> {{\App\Subcategory::find($i['linked_subcategory_id'])['sub_cat_name']}}</li>--}}
                                    @endforeach
                                </ul>
                            @endif
                        </td>
{{--                        <td class="text-center">{{$reportCategoryValue[$key]['types_id']}}</td>--}}
{{--                        <td class="text-center">{{ $reportCategoryValue[$key]['previous_year'] }}</td>--}}
                        <td class="text-right">{{"$ ".number_format( $reportCategoryValue[$key]['approx_cost'],2) }}</td>
                        <td class="text-center">{{ $reportCategoryValue[$key]['remarks'] }}</td>
                    </tr>
                    </tbody>
                @endforeach
                <tfoot>
                <td colspan="3" class="footer text-right"><b>Total</td>
                <td colspan="3" class="footer text-center"><b>{{"$ ".$other_data['total']}}</td>
                </tfoot>
            </table>
        </div>
        <div class="text-left" style="border:1px solid; margin-top:10px;">
            <p><b>Other Requirements :</b> {{$other_data['other_req'] ?? "Data Not Filled By ".$other_data['emp_name']}}
            </p>
        </div>
        <div class="text-left" style="border:1px solid; margin-top:10px;">
            <p><b>New Project Planning
                    :</b> {{$other_data['new_budget'] ?? "Data Not Filled By ".$other_data['emp_name']}}</p>
        </div>
        <div class="text-left">
            <p><b>Note : These are listed prices and may vary at the time of
                    procurement due to Government rules and
                    regulations, taxes, Dollar rates, availability, etc.</b></p>
            <p><b>Note : During the budget year 2023 if you require additional resources other
                    than what you have mentioned above that will be processed through a certain
                    level of approval which will take sometime.
                </b></p>
        </div>
    </div>
</div>
{{--@endforeach--}}
</body>
</html>
