<!DOCTYPE html>
<html>
<head>
	<title>inventory reorder level</title>
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
                <td class="text-center" style="width:80%; padding-left: 100px;">
                    <h2>EFULife Assurance Ltd.</h2>
                    <h2 style="font-weight:normal; line-height:1px;">Inventory Reorder Level</h2>
                </td>
                <td style="width:20%;">
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
                    <th>Item</th>
                    <th>Reorder Level</th>
                    <th>Qty. in Stock</th>  
                    <th>Issued in Last 3 months</th>                         
                </tr>
            </thead>
            
            <tbody>
            <?php $i = 1; ?>
            @foreach ($reorders as $reorder)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $reorder->sub_cat_name }}</td>
                    <td style="text-align:center;">{{ $reorder->threshold }}</td>
                    <td style="text-align:center;">{{ $reorder->in_stock }}</td>
                    <td style="text-align:center;">{{ $reorder->issued_count }}</td>
                </tr>
            @endforeach   
            </tbody>                          
        </table>
</body>
</html>