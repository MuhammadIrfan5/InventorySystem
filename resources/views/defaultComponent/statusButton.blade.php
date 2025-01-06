@if( $status == 1 )
    <td><h4><span class="badge badge-success">Confirmed</span></h4></td>
@elseif($status == 2)
    <td><h4><span class="badge badge-warning">Rejected</span></h4></td>
@elseif($status == 0)
    <td><h4><span class="badge badge-info">Pending</span></h4></td>
@endif
