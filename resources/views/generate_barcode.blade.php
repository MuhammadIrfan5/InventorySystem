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
         table, th, td {
             padding: 0px;
         }
        table {
            border-spacing: 15px;
        }
    </style>
</head>
<body>66+
<?php
$fields = (array)json_decode($filters);
//$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
?>
{{--<table cellpadding="0" cellspacing="0" style="width:100%;">--}}

{{--    <tr class="text-center">--}}
{{--        <td class="text-center" style="width:85%; padding-left: 100px;">--}}
{{--            <h2>EFULife Assurance Ltd.</h2>--}}
{{--            <h2 style="font-weight:normal; line-height:1px;">Inventory OUT BarCode Report</h2>--}}
{{--        </td>--}}
{{--        <td style="width:15%;">--}}
{{--            <p style="font-size: 12px;"><b>Username:</b>{{ Auth::user()->name }}</p>--}}
{{--            <p style="font-size: 12px;"><b>Printed:</b></p>--}}
{{--            <p style="line-height: 0px; font-size: 12px;">{{ date('d-M-Y h:i:sa') }}</p>--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--</table>  <br>--}}

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                </div>
                <div class="col-md-6 col-lg-6">

                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            <table style="width:100%">
                                @foreach ($inventories as $key => $inventory)
    {{--                                <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($inventory->product_sn, $generatorPNG::TYPE_CODE_128)) }}">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-md-4 mb-4 mt-4">--}}
    {{--                                        {!! DNS1D::getBarcodeHTML('4445645656', 'UPCA') !!}--}}
    {{--                                        {{ $inventory->product_sn }}--}}
    {{--                                    </div>--}}
{{--                                    <div class="row">--}}
                                        @if ($key % 7 == 0)
                                        <tr>
                                            @endif
{{--                                                <td> {!! DNS2D::getBarcodeHTML($inventory->product_sn, 'QRCODE') !!} <br/> {{ $inventory->product_sn }}</td>--}}
{{--                                            \Illuminate\Support\Facades\URL::temporarySignedRoute('inventory_detail', \Illuminate\Support\Carbon::now()->addMinutes(5), ['inv_id' => $inventory->id]--}}
                                                <td><img src="data:image/png;base64,{{DNS2D::getBarcodePNG(\Illuminate\Support\Facades\URL::signedRoute('inventory_detail',['inv_id' => $inventory->id]), 'QRCODE')}}" alt="barcode" style="width: 80px; height: 80px;" /> <br/>
                                                    @if(strlen($inventory->product_sn) > 7)
                                                        <span>{{ substr($inventory->product_sn,strlen($inventory->product_sn)-7,strlen($inventory->product_sn)) }}</span>
                                                    @else
                                                        <span>{{ $inventory->product_sn }}</span>
                                                    @endif
                                                </td>
                                        @if (($key + 1) % 7 == 0)
                                        </tr>
{{--                                    </div>--}}
                                    @endif
                                @endforeach
                                    @if (($key + 1) % 7 != 0)
                                    </tr>
                                    @endif
                            </table>
                        </div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{--<table class="secondary-table">--}}
{{--    <thead>--}}
{{--    <tr>--}}
{{--        <th>S.No</th>--}}
{{--        <th>Item Category</th>--}}
{{--        <th>Product S#</th>--}}
{{--        <th>Make</th>--}}
{{--        <th>Model</th>--}}
{{--        <th>Issue to</th>--}}
{{--        <th>Location</th>--}}
{{--        <th>Issue By</th>--}}
{{--        <th>Issue Date</th>--}}
{{--        <th>Initial Status</th>--}}
{{--        <th>Current Condition</th>--}}
{{--        <th>Base Remarks</th>--}}
{{--        <th>Issuance Remarks</th>--}}
{{--    </tr>--}}
{{--    </thead>--}}

{{--    <tbody>--}}
{{--    <?php $i = 1; ?>--}}
{{--    @foreach ($inventories as $inventory)--}}
{{--        <tr>--}}
{{--            <td>{{ $i++ }}</td>--}}
{{--            <td>{!! DNS1D::getBarcodeHTML($inventory->product_sn, 'CODABAR') !!}</td>--}}
{{--            <td><img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($inventory->product_sn, $generatorPNG::TYPE_CODE_128)) }}"></td>--}}
{{--        </tr>--}}
{{--    @endforeach--}}
{{--    </tbody>--}}
{{--</table>--}}
</body>
</html>
