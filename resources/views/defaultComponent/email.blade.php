@if($receivedStatus==0)
    @if($employeeId != null)
    <form method="POST"
          action="{{ url('reverification_email/'.$inventoryId) }}"
          class="d-inline-block">
        @csrf
        <button type="submit"
                class="{{$receivedStatus == 0 ? 'btn btn-primary' : 'btn btn-success'}}">
            Email
        </button>
    </form>
@endif()
    @else
     <button type="submit"
                class="{{$receivedStatus == 1 ? 'btn btn-success' : 'btn btn-primary'}}">
            Email
        </button>
@endif()
{{--<td>--}}
{{--    @if($inventory->issued_to != null)--}}
{{--        <form method="POST"--}}
{{--              action="{{ url('reverification_email/'.$inventory->id) }}"--}}
{{--              class="d-inline-block">--}}
{{--            @csrf--}}
{{--            <button type="submit"--}}
{{--                    class="{{$inventory->employee_status_data['received_status'] == 1 ? 'btn btn-success' : 'btn btn-primary'}}">--}}
{{--                Email--}}
{{--            </button>--}}
{{--        </form>--}}
{{--    @endif()--}}
{{--</td>--}}
