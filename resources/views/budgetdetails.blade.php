<?php $i = 1; ?>
@foreach ($inventories as $inventory)
    <tr>
        <td class='text-align-right'>{{ $i++ }}</td>
        <td>{{ $inventory->product_sn }}</td>
        <td>{{ $inventory->make_id?$inventory->make->make_name:'' }}</td>
        <td>{{ $inventory->model_id?$inventory->model->model_name:'' }}</td>
        <td>{{ date('d-M-Y' ,strtotime($inventory->purchase_date)) }}</td>
        <td>{{ $inventory->category_id?$inventory->category->category_name:'' }}</td>
        <td class='text-align-right'>{{ number_format(round($inventory->item_price),2) }}</td>
        <td class='text-align-right'>{{ number_format($inventory->dollar_rate,2) }}</td>
        <td>{{ $inventory->issued_to?$inventory->user->name:'' }}</td>
        <td>{{ date('d-M-Y' ,strtotime($inventory->created_at)) }}</td>
        
    </tr>
@endforeach  