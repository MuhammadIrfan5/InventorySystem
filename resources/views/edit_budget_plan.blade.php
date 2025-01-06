@extends("master")

@section("content")
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-10 col-lg-10">
                        <h1 class="">EFULIFE IT BUDGET REQUIREMENT FORM</h1>
                        <h4>PLANNING FOR THE YEAR {{$years->year}}</h4>
                    </div>
                <!-- <div class="col-md-2 col-lg-2 text-right">
                            <a href="{{ url('inventory') }}" class="btn btn-success">View List</a>
                        </div> -->
                </div>
                <hr/>
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card border-0 rounded-lg mt-3">
                            <div class="card-body">
                                <form method="POST" action="{{ url('budgetplan/'.$budget_plan->id) }}" enctype="multipart/form-data">

                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <h3 class="">Section 1: Your Identification</h3>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputhod_name">Your Name</label>
                                                <input class="form-control py-4" id="inputhod_name"
                                                       value="{{ auth()->user()->name }}" type="text" readonly/>
                                                <span class="small text-danger">{{ $errors->first('hod_name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputdepartment">Department</label>
                                                <input class="form-control py-4" id="inputdepartment" type="text"
                                                       value="{{ auth()->user()->department }}" readonly/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('department') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{--                                                <label class="small mb-1" for="inputyear">Year</label>--}}
                                                {{--                                                <input class="form-control py-4" id="inputyear" type="text" value="{{$years->year}}" name="year" readonly/>--}}
                                                <input class="form-control py-4" id="inputyear" type="hidden"
                                                       value="{{$years->id}}" name="year_id" readonly/>
                                                {{--                                                <span class="small text-danger">{{ $errors->first('year_id') }}</span>--}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="">Section 2: Estimation</h3>
                                        <p>(Provide your required quantity for below IT Equipment)</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mainTable" id="dataTable"
                                                       width="100%" cellspacing="0">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 250px">Items</th>
                                                        <th>Approx Price/Item ($)</th>
                                                        {{--                                                        <th>Addons</th>--}}
                                                        <th>New/PRF Quantity</th>
                                                        <th>Upgrade Quantity</th>
                                                        <th style="width: 400px">Remarks</th>
                                                        <th style="width: 100px">Total Cost ($)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i = 1;
                                                    $getTotalApproxCost = 0;
                                                    ?>
                                                    @forelse($subcategories as $key => $sub_category)
                                                        <?php $getTotalApproxCost2 = 0;?>
                                                        <tr>
                                                            {{--                                                            <td>{{ $i++ }}</td>--}}
                                                            <td style="width: 250px">
                                                                <input class="form-control py-4" type="hidden"
                                                                       id="subcatApproxCost_{{ $sub_category->id }}"
                                                                       value="{{ \App\Subcategory::find($sub_category->id)['approx_price_dollar'] }}"/>
                                                                {{ $sub_category->sub_cat_name }}
                                                                (${{ \App\Subcategory::find($sub_category->id)['approx_price_dollar'] ?? 0 }}
                                                                )<br/>
                                                                {{ $sub_category->subcat_desc != "" ? "(".$sub_category->subcat_desc.")" : ""}}
                                                                <input class="form-control py-4" id="inputsubcategory"
                                                                       type="hidden" value="{{ $sub_category->id  }}"
                                                                       name="subcategorys[]"/><br/>
                                                                @foreach($linked_subcat as $sub_key => $link_sub)
                                                                    @foreach($link_sub as $sub => $sub_cat)
                                                                        @if($sub_cat->subcategory_id == $sub_category->id)
                                                                            @if($sub == 0)
                                                                                <br/> Following Items and their Prices
                                                                                are associated with this item.<br/>
                                                                            @endif
                                                                            <b>
                                                                                <ul>
                                                                                    <li>
                                                                                        {{--                                                                                   (${{ $getTotalApproxCost += \App\Budgetitem::whereIn("year_id",[83,85,87,88])->where("subcategory_id",$sub_cat->linked_subcategory_id)->whereNotNull('unit_price_dollar')->max('unit_price_dollar') ?? '0'}})--}}
                                                                                        {{ \App\Subcategory::find($sub_cat->linked_subcategory_id)['sub_cat_name']}}
                                                                                        (${{ \App\Subcategory::find($sub_cat->linked_subcategory_id)['approx_price_dollar'] }}
                                                                                        )
                                                                                        @php
                                                                                            $getTotalApproxCost += \App\Subcategory::find($sub_cat->linked_subcategory_id)['approx_price_dollar']
                                                                                        @endphp
                                                                                    </li>
                                                                                </ul>

                                                                            </b>
                                                                            {{--                                                                        <div class="form-group">--}}
                                                                            {{--                                                                            <input class="form-control py-4" id="new_edit_qty_{{ $key }}_{{$sub}}" {{ $sub_cat->is_fixed == "1" ? "disabled" : "" }} onchange="calculateCost(this.id)" placeholder="Enter Linked Subcat QTY" type="number" name="new_edit_qty[{{ $sub_cat->linked_subcategory_id }}][]"/>--}}
                                                                            {{--                                                                            <input class="form-control py-4" id="new_edit_qty" type="hidden" value="{{ $sub_cat->linked_subcategory_id }}" name="new_edit_qty_subcat_id[]"/>--}}
                                                                            {{--                                                                            <input type="text" class="form-control py-4" id="newEditQty_{{$key}}_{{$sub}}" type="hidden" value="" name="new_edit_qty_subcat_id[]"/>--}}
                                                                            {{--                                                                        </div>--}}
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </td>
                                                            <td class=" text-right">
                                                                @foreach($sub_category->linkedSubcategory as $item)
                                                                    @php
                                                                        $getTotalApproxCost2 += \App\Subcategory::find($item['linked_subcategory_id'])['approx_price_dollar']
                                                                    @endphp
                                                                @endforeach
                                                                {{--                                                                {{dd($sub_category->linkedSubcategory())}}--}}
                                                                {{--                                                                {{ number_format($approx_price[$key] == "0" ? "0" : ((($approx_price[$key] + $getTotalApproxCost)*10)/100)+$approx_price[$key] + $getTotalApproxCost,2)}}--}}
                                                                {{--                                                                <input class="form-control py-4" type="hidden" value="{{ number_format($approx_price[$key] == "0" ? "0" : ((($approx_price[$key] + $getTotalApproxCost)*10)/100)+$approx_price[$key] + $getTotalApproxCost,2) }}" readonly/>--}}
                                                                {{ number_format($sub_category->approx_price_dollar + $getTotalApproxCost2?? "0" ,2)}}
                                                                <input class="form-control py-4" type="hidden"
                                                                       value="{{ number_format($sub_category->approx_price_dollar == "0" ? "0" : ($sub_category->approx_price_dollar + $getTotalApproxCost),2) }}"
                                                                       readonly/>
                                                            </td>
                                                            {{--                                                            <td>--}}
                                                            {{--                                                                @foreach($linked_subcat as $sub_key => $link_sub)--}}
                                                            {{--                                                                    @foreach($link_sub as $sub => $sub_cat)--}}
                                                            {{--                                                                        @if($sub_cat->subcategory_id == $sub_category->id)--}}
                                                            {{--                                                                            {{ \App\Subcategory::find($sub_cat->linked_subcategory_id)['sub_cat_name']}}--}}
                                                            {{--                                                                            <div class="form-group">--}}
                                                            {{--                                                                                <input class="form-control py-4" id="new_edit_qty_{{ $key }}_{{$sub}}" onchange="calculateCost(this.id)" placeholder="Enter Linked Subcat QTY" type="number" name="new_edit_qty[{{ $sub_cat->linked_subcategory_id }}][]"/>--}}
                                                            {{--                                                                                <input class="form-control py-4" id="new_edit_qty" type="hidden" value="{{ $sub_cat->linked_subcategory_id }}" name="new_edit_qty_subcat_id[]"/>--}}
                                                            {{--                                                                            </div>--}}
                                                            {{--                                                                        @endif--}}
                                                            {{--                                                                    @endforeach--}}
                                                            {{--                                                                @endforeach--}}
                                                            {{--                                                            </td>--}}
                                                            <td>
                                                                    <div class="form-group">
                                                                    <input
                                                                        class="form-control py-4 inputNewQty_{{ $sub_category->id }}"
                                                                        min="0" id="inputNewQty_{{ $key }}"
                                                                        data-id="{{ $sub_category->id }}"
                                                                        onkeyup="calculateCost(this.id)"
                                                                        placeholder="Enter new quantity" type="number"
                                                                        value="{{ $sub_category->newQty }}"
                                                                        name="new_qtys[]"/>
                                                                    </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input
                                                                        class="form-control py-4 inputUpgradeQty_{{ $sub_category->id }}"
                                                                        min="0" id="inputUpgradeQty_{{ $key }}"
                                                                        data-id="{{ $sub_category->id }}"
                                                                        onkeyup="calculateCost(this.id)"
                                                                        placeholder="Enter upgrade quantity"
                                                                        value="{{ $sub_category->upgradedQty }}"
                                                                        type="number" name="upgrade_qtys[]"/>
                                                                </div>
                                                            </td>
                                                            <td style="width: 400px">
                                                                <div class="form-group">
                                                                    <input class="form-control py-4"
                                                                           id="inputRemarks_{{ $key }}"
                                                                           placeholder="Enter Remarks" type="text"
                                                                           name="remarkss[]"
                                                                           value="{{ $sub_category->remarks }}"
                                                                           data-id="{{ $sub_category->id }}"
                                                                           onkeyup="calculateCost(this.id)"/>
                                                                </div>
                                                            </td>
                                                            <td id="cost_{{ $sub_category->id}}" class="text-right"
                                                                style="width: 100px">
                                                                <div class="form-group">
                                                                    <input
                                                                        class="form-control py-4 cost_{{ $sub_category->id}} text-right"
                                                                        value="{{ $sub_category->approx_cost }}"
                                                                        type="text" id="totalCost_{{ $key }}" readonly/>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            No Subcategory Added Yet !
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th id="datatableFooter"></th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="">Section 3: New Project Planning</h3>
                                        <p>(Provide new project planning which are not in above list)</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="new_budgets">Requirements</label>
                                                <textarea class="form-control" id="new_budgets"
                                                          onkeyup="getOtherbudgetText(this.id)" name="new_budgets"
                                                          rows="3"
                                                          value="{{$budget_plan->new_budgets}}"
                                                          placeholder="Enter other requirements here" value>{{$budget_plan->new_budget}}</textarea>
                                                <span
                                                    class="small text-danger">{{ $errors->first('new_budgets') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="">Section 4: Any Other Requirements</h3>
                                        <p>(Provide any other requirements which are not in above list)</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="small mb-1" for="other_reqs">Requirements</label>
                                                <textarea class="form-control" id="other_reqs"
                                                          onkeyup="getOtherReqs(this.id)" name="other_reqs" rows="4"
                                                          value="{{$budget_plan->other_req}}"
                                                          placeholder="Enter other requirements here">{{$budget_plan->other_req}}</textarea>
                                                <span
                                                    class="small text-danger">{{ $errors->first('other_reqs') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="">Section 5: Attach Any File (Optional)</h3>
                                        <p>(Attach supporting file if needed.)</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputFile">File Upload</label>
                                                <input class="form-control" id="inputFile" type="file"
                                                       name="optional_file"/>
                                                <span
                                                    class="small text-danger">{{ $errors->first('optional_file') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="">Section 6: Undertaking</h3>
                                    </div>
                                    <div class="underTaking" style="border: 2px dashed; padding: 5px 5px 5px 5px">
                                        <p><b>(By selecting below agree button you are assuring that all above mentioned
                                                requirements are filled by you based on your next year budget
                                                requirements.)</b></p>
                                        <div class="form-group">
                                            <div
                                                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" value="1" name="is_agree"
                                                       class="custom-control-input" id="customSwitch6" {{ $budget_plan->is_agree == '1' ? "checked" : '' }} required>
                                                <label class="custom-control-label" for="customSwitch6">Agree
                                                    Yes/No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4 mb-0">
                                        {{--                                        <input type="submit" name="add_budget" value="Add Budget Plan" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block">--}}
                                    </div>
                                    {{--                                    Modal Work--}}
                                    <div class="form-group">

                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target=".bd-example-modal-xl">Verify Data
                                        </button>

                                        <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
                                             aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="form-row">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="dataTableModal"
                                                                       width="100%" cellspacing="0">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Items</th>
                                                                        <th>Approx Price/Item ($)</th>
                                                                        {{--                                                        <th>Addons</th>--}}
                                                                        <th>New/PRF Quantity</th>
                                                                        <th>Upgrade Quantity</th>
                                                                        <th style="width: 400px">Remarks</th>
                                                                        <th style="width: 100px">Total Cost ($)</th>
                                                                    </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                    <?php $i = 1;
                                                                    $getTotalApproxCost = 0;
                                                                    ?>
                                                                    @forelse($subcategories as $key => $sub_category)
                                                                        <?php $getTotalApproxCost2 = 0;?>
                                                                        <tr>
                                                                            {{--                                                            <td>{{ $i++ }}</td>--}}
                                                                            <td>
                                                                                <input class="form-control py-4"
                                                                                       readonly type="hidden"
                                                                                       id="subcatApproxCostModal_{{ $sub_category->id }}"
                                                                                       value="{{ \App\Subcategory::find($sub_category->id)['approx_price_dollar'] }}"/>
                                                                                {{ $sub_category->sub_cat_name }} <br/>
                                                                                {{ $sub_category->subcat_desc != "" ? "(".$sub_category->subcat_desc.")" : ""}}
                                                                                <input class="form-control py-4"
                                                                                       id="inputsubcategory"
                                                                                       type="hidden"
                                                                                       value="{{ $sub_category->id  }}"
                                                                                       name="subcategory[]"/><br/>
                                                                                @foreach($linked_subcat as $sub_key => $link_sub)
                                                                                    @foreach($link_sub as $sub => $sub_cat)
                                                                                        @if($sub_cat->subcategory_id == $sub_category->id)
                                                                                            @if($sub == 0)
                                                                                                <br/> Following Items
                                                                                                and
                                                                                                their Prices are
                                                                                                associated with this
                                                                                                item.<br/>
                                                                                            @endif
                                                                                            {{--                                                                                            <br/> Following Items and their Prices are associated with this item.<br/>--}}
                                                                                            <b>
                                                                                                <ul>
                                                                                                    <li>
                                                                                                        {{--                                                                                                (${{ $getTotalApproxCost += \App\Budgetitem::whereIn("year_id",[83,85,87,88])->where("subcategory_id",$sub_cat->linked_subcategory_id)->whereNotNull('unit_price_dollar')->max('unit_price_dollar') ?? '0'}})--}}
                                                                                                        {{ \App\Subcategory::find($sub_cat->linked_subcategory_id)['sub_cat_name']}}
                                                                                                        (${{ \App\Subcategory::find($sub_cat->linked_subcategory_id)['approx_price_dollar'] }}
                                                                                                        )
                                                                                                        @php
                                                                                                            $getTotalApproxCost += \App\Subcategory::find($sub_cat->linked_subcategory_id)['approx_price_dollar']
                                                                                                        @endphp
                                                                                                    </li>
                                                                                                </ul>

                                                                                            </b>
                                                                                            <br/>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endforeach
                                                                            </td>
                                                                            {{--                                                                            <td class=" text-right">--}}
                                                                            {{--                                                                                --}}{{--                                                                                {{ number_format($approx_price[$key] == "0" ? "0" : ((($approx_price[$key] + $getTotalApproxCost)*10)/100)+$approx_price[$key] + $getTotalApproxCost,2)}}--}}
                                                                            {{--                                                                                {{ number_format($sub_category->approx_price_dollar == "0" ? "0" : ($sub_category->approx_price_dollar + $getTotalApproxCost),2)}}--}}
                                                                            {{--                                                                                --}}{{--                                                                                <input class="form-control py-4" type="hidden" value="{{ number_format($approx_price[$key] == "0" ? "0" : $approx_price[$key] + $getTotalApproxCost,2) }}" readonly name="approx_cost[]"/>--}}
                                                                            {{--                                                                            </td>--}}
                                                                            <td class=" text-right">
                                                                                @foreach($sub_category->linkedSubcategory as $item)
                                                                                    @php
                                                                                        $getTotalApproxCost2 += \App\Subcategory::find($item['linked_subcategory_id'])['approx_price_dollar']
                                                                                    @endphp
                                                                                @endforeach
                                                                                {{--                                                                {{dd($sub_category->linkedSubcategory())}}--}}
                                                                                {{--                                                                {{ number_format($approx_price[$key] == "0" ? "0" : ((($approx_price[$key] + $getTotalApproxCost)*10)/100)+$approx_price[$key] + $getTotalApproxCost,2)}}--}}
                                                                                {{--                                                                <input class="form-control py-4" type="hidden" value="{{ number_format($approx_price[$key] == "0" ? "0" : ((($approx_price[$key] + $getTotalApproxCost)*10)/100)+$approx_price[$key] + $getTotalApproxCost,2) }}" readonly/>--}}
                                                                                {{ number_format($sub_category->approx_price_dollar + $getTotalApproxCost2?? "0" ,2)}}
                                                                                <input class="form-control py-4"
                                                                                       type="hidden"
                                                                                       value="{{ number_format($sub_category->approx_price_dollar == "0" ? "0" : ($sub_category->approx_price_dollar + $getTotalApproxCost),2) }}"
                                                                                       readonly/>
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                        <input
                                                                                            class="form-control py-4 inputModalNewQty_{{ $sub_category->id }}"
                                                                                            min="0"
                                                                                            id="inputModalNewQty_{{ $key }}"
                                                                                            onkeyup="calculateModalCost(this.id)"
                                                                                            data-id="{{ $sub_category->id }}"
                                                                                            placeholder="Enter new quantity"
                                                                                            type="number"
                                                                                            value="{{ $sub_category->newQty }}"
                                                                                            name="new_qty[]"/>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <input
                                                                                        class="form-control py-4 inputModalUpgradeQty_{{ $sub_category->id }}"
                                                                                        min="0"
                                                                                        id="inputModalUpgradeQty_{{ $key }}"
                                                                                        onkeyup="calculateModalCost(this.id)"
                                                                                        data-id="{{ $sub_category->id }}"
                                                                                        placeholder="Enter upgrade quantity"
                                                                                        type="number"
                                                                                        value="{{ $sub_category->upgradedQty }}"
                                                                                        name="upgrade_qty[]"/>
                                                                                </div>
                                                                            </td>
                                                                            <td style="width: 400px">
                                                                                <div class="form-group">
                                                                                    <input class="form-control py-4"
                                                                                           id="inputModalRemarks_{{ $key }}"
                                                                                           data-id="{{ $sub_category->id }}"
                                                                                           placeholder="Enter Remarks"
                                                                                           value="{{ $sub_category->remarks }}"
                                                                                           type="text"
                                                                                           name="remarks[]"/>
                                                                                </div>
                                                                            </td>
                                                                            <td id="costModal_{{ $sub_category->id}}"
                                                                                style="width: 100px">
                                                                                <div class="form-group">
                                                                                    <input
                                                                                        class="form-control py-4 costModal_{{ $sub_category->id}} text-right"
                                                                                        type="text"
                                                                                        value="{{ $sub_category->approx_cost }}"
                                                                                        id="totalCostModal_{{ $key }}"
                                                                                        name="approx_cost[]" readonly/>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            No Subcategory Added Yet !
                                                                        </tr>
                                                                    @endforelse
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th>Total</th>
                                                                        <th id="datatableFooterModal">$.</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="card-body">
                                                                <h3 class="">Section 3: New Project Planning</h3>
                                                                <p>(Provide new project planning which are not in above
                                                                    list)</p>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="small mb-1" for="new_budget">Requirements</label>
                                                                        <textarea class="form-control" id="new_budget"
                                                                                  name="new_budget" rows="3"
                                                                                  value="{{$budget_plan->new_budget}}"
                                                                                  placeholder="Enter other requirements here">{{$budget_plan->new_budget}}</textarea>
                                                                        <span
                                                                            class="small text-danger">{{ $errors->first('new_budget') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <h3 class="">Section 4: Any Other Requirements</h3>
                                                                <p>(Provide any other requirements which are not in
                                                                    above list)</p>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="small mb-1" for="other_req">Requirements</label>
                                                                        <textarea class="form-control" id="other_req"
                                                                                  name="other_req" rows="4"
                                                                                  value="{{$budget_plan->other_req}}"
                                                                                  placeholder="Enter other requirements here">{{$budget_plan->other_req}}</textarea>
                                                                        <span
                                                                            class="small text-danger">{{ $errors->first('other_req') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="btn-group">
                                                                <input type="submit" name="add_budget"
                                                                       value="Update Budget Plans"
                                                                       class="btn btn-primary btn-block">
                                                            </div>
                                                        </div>

                                                        {{--                                                        <div class="form-group mt-4 mb-2 mr-2">--}}
                                                        {{--                                                            <input type="submit" name="add_budget"--}}
                                                        {{--                                                                   value="Add Budget Plan"--}}
                                                        {{--                                                                   class="btn btn-primary btn-block">--}}
                                                        {{--                                                        </div>--}}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-left" style="border:1px solid; margin-top:10px;">
                                    <p><b>Note : These are listed prices and may vary at the time of
                                            procurement due to Government rules and
                                            regulations, taxes, Dollar rates, availability, etc.</p>
                                    <p>Note : During the budget year 2023 if you require additional resources other
                                        than what you have mentioned above that will be processed through a certain
                                        level of approval which will take sometime.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2020</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('page-script')
    <script type="text/javascript">

        {{--<input class="form-control py-4 cost_{{ $sub_category->id}} text-right" type="text" cost="" readonly/>--}}
        $('#dataTable').dataTable({
            paging: false
        });

        var savedID = 0;

        function sumTotal(tableId, valueId, footerID) {
            var total = 0;
            // let gg = document.querySelector('#' + tableId).lastElementChild.children;
            let gg = document.querySelector('#' + tableId).children[1].children;
            for (let i = 0; i < gg.length; i++) {
                let check = document.getElementById(valueId + i);
                if (isNaN(parseFloat(check.value)) !== true) {
                    total = total + parseFloat(check.value.replace(',', ''));
                }
            }
            document.getElementById(footerID).innerHTML = total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            if (footerID.includes("Modal") !== true) {
                document.getElementById("datatableFooterModal").innerHTML = total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            // console.log(footerID.includes("Modal"));
        }


        function calculateCost(id) {
            let multipliedValue = 0;
            let multipliedValueUpgrade = 0;
            let split = id.split("_")[1];
            let getId = document.getElementById(id);
            let subcategory_id = getId.getAttribute('data-id');
            // ApproxPrice
            const getValueOfApproxPrice = document.querySelector('#' + id).parentElement.parentElement.previousElementSibling.parentElement.childNodes[3].innerHTML
            const valueOfApproxPrice = parseFloat(getValueOfApproxPrice.replace(',', ''));
            const valueofApproxPriceSubCat = document.getElementById('subcatApproxCost_' + subcategory_id).value;
            // console.log("valueOfApproxPrice ",valueOfApproxPrice);
            const setCalculatedCost = document.querySelector('#' + id).parentElement.parentElement.previousElementSibling.parentElement.lastElementChild.lastElementChild.lastElementChild;

            //Getting values
            let valueOfNewPRFQty = isNaN(parseInt(document.getElementById("inputNewQty_" + split).value)) ? 0 : parseInt(document.getElementById("inputNewQty_" + split).value);

            let valueOfUpgradeQuantity = isNaN(parseInt(document.getElementById("inputUpgradeQty_" + split).value)) ? 0 : parseInt(document.getElementById("inputUpgradeQty_" + split).value);
            let valueOfRemarks = document.getElementById("inputRemarks_" + split).value;
            console.log('valueOfUpgradeQuantity', valueOfUpgradeQuantity);

            if (valueOfNewPRFQty > 0) {
                multipliedValue = valueOfApproxPrice * valueOfNewPRFQty;
            }
            if (valueOfUpgradeQuantity > 0) {
                multipliedValueUpgrade = valueofApproxPriceSubCat * valueOfUpgradeQuantity;
            }
            // multipliedValue = valueOfApproxPrice * (valueOfNewPRFQty + valueOfUpgradeQuantity);
            // let finalMultipliedValue = multipliedValue+(multipliedValue*10)/100;
            let finalMultipliedValue = multipliedValue
                + multipliedValueUpgrade
            ;

            //
            setCalculatedCost.value = isNaN(finalMultipliedValue) ? '' : finalMultipliedValue.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            setCalculatedCost.setAttribute('cost', isNaN(finalMultipliedValue) ? '' : finalMultipliedValue.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            // setCalculatedCost.innerHTML = isNaN(finalMultipliedValue) ? '' : finalMultipliedValue.toLocaleString();
            // setCalculatedCost.toLocaleString();
            //Find number of input Fields in First Column
            const firstColumn = document.querySelector('#' + id).parentElement.parentElement.previousElementSibling.parentElement.firstElementChild;
            const check = firstColumn.getElementsByTagName("input");

            {{--$.get("{{ url('linkedsubcat_by_category') }}/" + subcategory_id, function (data) {--}}
            {{--    $.each(data, function (index, value) {--}}
            {{--        let linkSubIdNewQty = document.getElementsByClassName("inputNewQty_" + value.linked_subcategory_id)[0];--}}
            {{--        let linkSubIdUpgradeQty = document.getElementsByClassName("inputUpgradeQty_" + value.linked_subcategory_id)[0];--}}

            {{--        if (linkSubIdNewQty !== undefined && linkSubIdUpgradeQty !== undefined) {--}}
            {{--            linkSubIdNewQty.value = valueOfNewPRFQty;--}}
            {{--            linkSubIdNewQty.min = valueOfNewPRFQty;--}}

            {{--            linkSubIdUpgradeQty.value = valueOfUpgradeQuantity;--}}

            {{--            let inputModalNewQty = document.getElementsByClassName('inputModalNewQty_' + value.linked_subcategory_id)[0];--}}

            {{--            if (inputModalNewQty !== undefined) {--}}
            {{--                inputModalNewQty.value = valueOfNewPRFQty;--}}
            {{--                inputModalNewQty.min = valueOfNewPRFQty;--}}
            {{--            }--}}

            {{--            // document.getElementsByClassName('inputModalNewQty_' + value.linked_subcategory_id)[0].value = valueOfNewPRFQty;--}}
            {{--            // document.getElementsByClassName('inputModalNewQty_' + value.linked_subcategory_id)[0].min = valueOfNewPRFQty;--}}
            {{--            document.getElementsByClassName('inputModalUpgradeQty_' + value.linked_subcategory_id)[0].value = valueOfUpgradeQuantity;--}}
            {{--        }--}}

            {{--        // linkSubIdNewQty.readOnly =true;--}}

            {{--        // document.getElementsByClassName('inputModalNewQty_' + value.linked_subcategory_id)[0].value = valueOfNewPRFQty;--}}
            {{--        // document.getElementsByClassName('inputModalUpgradeQty_' + value.linked_subcategory_id)[0].value = valueOfUpgradeQuantity;--}}


            {{--    });--}}
            {{--});--}}

            document.getElementById('inputModalNewQty_' + split).value = valueOfNewPRFQty;
            document.getElementById('inputModalUpgradeQty_' + split).value = valueOfUpgradeQuantity;
            // document.getElementById('costModal_' + subcategory_id).innerHTML = setCalculatedCost.innerHTML;
            document.getElementsByClassName('costModal_' + subcategory_id)[0].value = setCalculatedCost.value;
            document.getElementById('inputModalRemarks_' + split).value = valueOfRemarks;

            // document.getElementById('costModal_' + subcategory_id).innerHTML = setCalculatedCost.innerHTML;
            //
            // console.log("setCalculatedCost.innerHTML => ", setCalculatedCost.innerHTML);
            // console.log("setCalculatedCost.innerHTML => ", subcategory_id);


            // console.log("Ponka => ", Ponka * parseInt(document.getElementById(id).value));

            // let split = id.split("_");
            // let col_row_index = split[1];
            // var currentRow=$(this).closest("tr");
            // var col1=currentRow.find("td:eq(0)").html();
            // console.log("col => "+ Object.values(currentRow)[1]);
            // console.log("vcalue "+document.getElementById(id).value);
            // const valueOfApproxPrice = parseInt(document.querySelector('#'+id).parentElement.parentElement.previousElementSibling.innerHTML);

            //    Running the sum of value
            sumTotal('dataTable', 'totalCost_', 'datatableFooter');
        }

        function calculateModalCost(id) {
            let multipliedValue = 0;
            let multipliedValueUpgrade = 0;
            let split = id.split("_")[1];
            let getId = document.getElementById(id);
            let subcategory_id = getId.getAttribute('data-id');
            // console.log("subcategoryId =",subcategory_id);
            // ApproxPrice
            const getValueOfApproxPrice = document.querySelector('#' + id).parentElement.parentElement.previousElementSibling.parentElement.childNodes[3].innerHTML;
            const valueOfApproxPrice = parseFloat(getValueOfApproxPrice.replace(',', ''));
            const valueofApproxPriceSubCat = document.getElementById('subcatApproxCostModal_' + subcategory_id).value;
            // const setCalculatedCost = document.querySelector('#'+id).parentElement.parentElement.previousElementSibling.parentElement.lastElementChild;
            const setCalculatedCost = document.querySelector('#' + id).parentElement.parentElement.previousElementSibling.parentElement.lastElementChild.lastElementChild.lastElementChild;
            //Getting values
            let valueOfNewPRFQty = isNaN(parseInt(document.getElementById("inputModalNewQty_" + split).value)) ? 0 : parseInt(document.getElementById("inputModalNewQty_" + split).value);
            let valueOfUpgradeQuantity = isNaN(parseInt(document.getElementById("inputModalUpgradeQty_" + split).value)) ? 0 : parseInt(document.getElementById("inputModalUpgradeQty_" + split).value);

            if (valueOfNewPRFQty > 0) {
                multipliedValue = valueOfApproxPrice * valueOfNewPRFQty;
            }
            if (valueOfUpgradeQuantity > 0) {
                multipliedValueUpgrade = valueofApproxPriceSubCat * valueOfUpgradeQuantity;
            }
            // let multipliedValue = valueOfApproxPrice * (valueOfNewPRFQty + valueOfUpgradeQuantity);
            // let finalMultipliedValue = multipliedValue+(multipliedValue*10)/100;
            let finalMultipliedValue = multipliedValue;
            +multipliedValueUpgrade;
            // setCalculatedCost.innerHTML = isNaN(finalMultipliedValue) ? '' : finalMultipliedValue.toLocaleString();
            setCalculatedCost.value = isNaN(finalMultipliedValue) ? '' : finalMultipliedValue.toLocaleString();
            //Find number of input Fields in First Column
            const firstColumn = document.querySelector('#' + id).parentElement.parentElement.previousElementSibling.parentElement.firstElementChild;
            const check = firstColumn.getElementsByTagName("input")

            {{--$.get("{{ url('linkedsubcat_by_category') }}/" + subcategory_id, function (data) {--}}
            {{--    console.log(data);--}}
            {{--    $.each(data, function (index, value) {--}}
            {{--        let linkSubIdNewQty = document.getElementsByClassName("inputModalNewQty_" + value.linked_subcategory_id)[0];--}}
            {{--        let linkSubIdUpgradeQty = document.getElementsByClassName("inputUpgradeQty_" + value.linked_subcategory_id)[0];--}}

            {{--        // console.log("linkSubIdNewQty => ", linkSubIdNewQty);--}}
            {{--        // console.log("valueOfNewPRFQty => ", valueOfNewPRFQty);--}}

            {{--        if (linkSubIdNewQty !== undefined && linkSubIdUpgradeQty !== undefined) {--}}
            {{--            linkSubIdNewQty.value = valueOfNewPRFQty;--}}
            {{--            linkSubIdNewQty.min = valueOfNewPRFQty;--}}
            {{--            // linkSubIdUpgradeQty.value = valueOfUpgradeQuantity;--}}
            {{--            // linkSubIdNewQty.readOnly =true;--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            sumTotal('dataTableModal', 'totalCostModal_', 'datatableFooterModal');

        }

        function getOtherReqs(id) {

            let getOtherReqValue = document.getElementById(id).value;
            document.getElementById('other_req').value = getOtherReqValue;
            console.log(getOtherReqValue)
        }

        function getOtherbudgetText(id) {

            let getOtherbudgetTextValue = document.getElementById(id).value;
            document.getElementById('new_budget').value = getOtherbudgetTextValue;
            console.log(getOtherbudgetTextValue)
        }

        $(document).ready(function () {
            $("#customSwitch6").on('click', function (e) {
                console.log(e)
                var swit = $("#" + this.value);
                console.log(swit)
                // div.toggle("slow").siblings().hide("slow");
            });
        });
    </script>

@endsection
