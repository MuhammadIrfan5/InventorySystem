<!DOCTYPE html>
<html>
<head>
    <title>invoice inventory report</title>
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
            <h2 style="font-weight:normal; line-height:1px;">Invoice Inventory Report</h2>
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
        <th>Year</th>
        <th>Invoice Number</th>
        <th>Invoice Date</th>
        <th>Category</th>
        {{--                                    <th>Make</th>--}}
        {{--                                    <th>Model</th>--}}
        {{--                                    <th>Product S#</th>--}}
        {{--                                    <th>Purchase Date</th>--}}
        <th>Sub Category</th>
        <th>Vendor</th>
        <th>Price</th>
        <th>Tax(%)</th>
{{--        <th>Price After Tax(%)</th>--}}
        {{--                                    <th>Po Number</th>--}}
        <th>Contract Issue Date</th>
        <th>Contract End Date</th>
    </tr>
    </thead>

    <tbody>
    <?php $i = 1; ?>
    @foreach ($inventories as $inventory)
        <tr>
            <td class='text-align-right'>{{ $i++ }}</td>
            <td>{{ empty($inventory->year->year)?'':$inventory->year->year }}</td>
            <td>{{ empty($inventory->invoice_number)?'':$inventory->invoice_number }}</td>
            <td>{{ empty($inventory->invoice_date)?'':$inventory->invoice_date }}</td>
            <td>
                @foreach($inventory->cat_name as $cat)
                    {{ \App\Category::find($cat)['category_name'] }},
                @endforeach
            </td>
            <td>
                @foreach($inventory->sub_cat_name as $sub_cat)
                    {{ \App\Subcategory::find($sub_cat)['sub_cat_name'] }},
                @endforeach
            </td>
            <td>{{ empty($inventory->vendor->vendor_name)?'':$inventory->vendor->vendor_name }}</td>
            {{--                                        <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>--}}
            {{--                                        <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>--}}
            {{--                                        <td>{{ $inventory->product_sn }}</td>--}}
            {{--                                        <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>--}}
            {{--                                        <td>{{ empty($inventory->subcategory)?'':$inventory->subcategory->sub_cat_name }}</td>--}}
            <td>{{ number_format(round($inventory->item_price),2) }}</td>
            <td>{{ $inventory->tax }}%</td>
{{--            <td>{{ number_format(round($inventory->item_price_tax),2) }}</td>--}}
            {{--                                        <td>{{ empty($inventory->po_number)?'':$inventory->po_number }}</td>--}}
            <td>{{ empty($inventory->contract_issue_date)?'':$inventory->contract_issue_date }}</td>
            <td>{{ empty($inventory->contract_end_date)?'':$inventory->contract_end_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
