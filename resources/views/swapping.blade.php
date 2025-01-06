@extends("master")

@section("content")

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                    <div id="msgs" class="card-body mt-4"></div>
                <form id="swapping_form">
{{--                                        @csrf method="POST" action="{{ url('swapping2') }}"--}}
                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            budget Swapping
                        </div>

                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="year">Year</label>
                                        <select class="custom-select filter_budget" id="year" name="year_id">
                                            <option value=''>Select Year here</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                            @endforeach
                                        </select>
                                        <span class="small text-danger">{{ $errors->first('year_id') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="dept_id"> To Department/Branch</label>
                                        <select class="custom-select" id="dept_id" name="to_dept" required>
                                            <option value=0>Select Dept/Branch here</option>
                                        </select>
                                        <input type="hidden" id="swap_dept_to_name" name="swap_dept_to_name" value="">
                                        <span class="small text-danger">{{ $errors->first('to_dept') }}</span>
                                        <input type='hidden' id='dept' name='department' value=''>
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="category">Category</label>
                                        <select class="custom-select category filter_budget" id="category"
                                                name="category_id">
                                            <option value=''>Select Category here</option>
                                            @foreach ($categories as $category)
                                                <option
                                                    value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="small text-danger">{{ $errors->first('category_id') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="qty">Quantity</label>
                                        <input class="form-control py-2 budget_qty" id="budget_qty"
                                                name="qty" type="number"
                                               placeholder="Enter quantity here"/>
{{--                                        onkeyup="check_qty(this.value)"--}}
                                        <input type="hidden" id="default_qty" value=""/>
                                        <input type="hidden" id="budgeted_item_pk" value=""/>
                                        <span class="small text-danger">{{ $errors->first('qty') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="subcategory">Sub Category</label>
                                        <select class="custom-select subcategory filter_budget" id="subcategory"
                                                name="sub_cat_id">
                                            <option value=''>Select Sub Category here</option>
                                        </select>
                                        <span class="small text-danger">{{ $errors->first('sub_cat_id') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="from_dept">From Department/Branch</label>
                                        <select class="custom-select filter_budget" id="from_dept" name="from_dept">
                                            <option value=''>Select Dept/Branch here</option>
                                        </select>
                                        <span class="small text-danger">{{ $errors->first('from_dept') }}</span>
                                        <div class="table-responsive">
                                            <table class="table table-borderless available_budget"
                                                   style="display:none;">
                                                <tr>
                                                    <th style="text-align:center;">Action</th>
                                                    <th style="text-align:center;">Qty</th>
                                                    <th style="text-align:center;">Consumed</th>
                                                    <th style="text-align:center;">Remaining</th>
                                                </tr>
                                                <tr id="available_budget_td">
                                                    {{--                                                    <td class="available_qty" style="text-align:center;"></td>--}}
                                                    {{--                                                    <td class="available_con" style="text-align:center;"></td>--}}
                                                    {{--                                                    <td class="available_rem" style="text-align:center;"></td>--}}
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="from_remarks"> From Remarks</label>
                                        <textarea class="form-control" id="from_remarks" name="from_remarks" rows="4"
                                                  placeholder="Enter Remarks here"></textarea>
                                        <span class="small text-danger">{{ $errors->first('from_remarks') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="to_remarks"> To Remarks</label>
                                        <textarea class="form-control" id="to_remarks" name="to_remarks" rows="4"
                                                  placeholder="Enter Remarks here"></textarea>
                                        <span class="small text-danger">{{ $errors->first('to_remarks') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="from_description"> From Description</label>
                                        <textarea class="form-control" id="from_description" name="from_description"
                                                  rows="4" placeholder="Enter Description here"></textarea>
                                        <span class="small text-danger">{{ $errors->first('from_description') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="to_description"> To Description</label>
                                        <textarea class="form-control" id="to_description" name="to_description"
                                                  rows="4" placeholder="Enter Description here"></textarea>
                                        <span class="small text-danger">{{ $errors->first('to_description') }}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12 col-lg-12">
                                    {{--                                    type="submit" name="swap"--}}
                                    <button type="button" class="btn btn-success btn_swap float-right">Swap this
                                        budget
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
    @include('layouts.customScript')
    <script type="text/javascript">
        $(document).ready(function () {

            $("#category").on("change",function(){
                var id = $(this).val();
                var report = $('.subcategory').data('reports');
                var subcategory = $('.subcategory');
                subcategory.empty();
                if(report == 1){
                    subcategory.append('<option value="" class="o1">All</option>');
                }
                else{
                    subcategory.append('<option value=0 class="o1">Select Sub Category here</option>');
                }
                $.get("{{ url('subcat_by_category') }}/"+id, function(data){
                    console.log("Data =>",data);
                    $.each( data, function(index, value){
                        subcategory.append(
                            $('<option></option>').val(value.id).html(value.sub_cat_name)
                        );
                    });
                });
            });

            let link = '<?php echo \DB::table('links')->get()[0]->url;?>'
            var settings = {
                // "url": link + "deptdataall.php?uid=1",
                "url": link + "deptdataall.php?uid=1",
                "method": "GET",
                "timeout": 0,
            };
            $.ajax(settings).done(function (response) {
                var settings_dep = {
                    "url": "{{ url('get_department') }}",
                    "method": "GET",
                    "timeout": 0,
                };
                $.ajax(settings_dep).done(function (dep_response) {
                    if (response.Login != null) {
                        // var res = response.Login;
                        var deps = response.Login;
                        var res = [...deps,...dep_response];
                        var dept_id = $('#dept_id');
                        $.each(res, function (index, value) {
                            dept_id.append(
                                $('<option></option>').val(value.DEPARTMENT_ID).html(value.DEPARTMENT)
                            );
                        });
                        var from_dept = $('#from_dept');
                        $.each(res, function (index, value) {
                            from_dept.append(
                                $('<option></option>').val(value.DEPARTMENT_ID).html(value.DEPARTMENT)
                            );
                        });
                    }
                });
            });

            $('#dept_id').change(function () {
                var dept_name = $('#dept').val($("#dept_id option:selected").text());
                $('#swap_dept_to_name').val($("#dept_id option:selected").text());

            });

            $('.filter_budget').change(function () {
                //alert($(this).val());
                var year = $('#year').val();
                var category = $('#category').val();
                var subcategory = $('#subcategory').val();
                var from_dept = $('#from_dept').val();
                console.log("from Dept ==> ",from_dept,"==  Categroy id ==> "+category+" == Subcategory ==> "+subcategory+ " ==  Year ==> "+year);
                if (year && category && subcategory && from_dept) {
                    var tbl_td;
                    var fields = {
                        "_token": "{{ csrf_token() }}",
                        'year_id': year,
                        'category_id': category,
                        'sub_cat_id': subcategory,
                        'from_dept': from_dept
                    }
                    $.post("{{ url('get_budget') }}", fields, function (res) {
                        if (res.length > 0) {

                            $.each(res, function (index, value) {
                                console.log('value ' + index + ' :' + value.qty);
                                tbl_td = '<tr><td class="checkbox' + value.id + '" style="text-align:center"><input type="radio" name="radio_budget_id"  value="' + value.id + '" style="text-align:center;" onchange="get_value_radio(this)"/></td><td class="available_qty" style="text-align:center;">' + value.qty + '</td>  <td class="available_con" style="text-align:center;">' + value.consumed + '</td> <td class="available_rem" style="text-align:center;">' + value.remaining + '</td></tr>';
                                $('.available_budget').append(tbl_td);
                                $(".available_budget").show('slow');
                                // $('#filter_budget > tbody:last-child').append('<tr> <td class="available_qty" style="text-align:center;">' + value.qty + '</td>  <td class="available_con" style="text-align:center;">' + value.consumed + '</td> <td class="available_rem" style="text-align:center;">' + value.remaining + '</td>');
                                // $(".available_qty").html(value.qty);
                                // $(".available_con").html(value.consumed);
                                // $(".available_rem").html(value.remaining);
                                // $(".available_budget").show('slow');
                                // $("#qty").attr('max', value.remaining);
                            });

                            // $(".available_qty").html(res.qty);
                            // $(".available_con").html(res.consumed);
                            // $(".available_rem").html(res.remaining);
                            $(".available_budget").show('slow');
                            // $("#qty").attr('max', res.remaining);
                            // $('#from_remarks').val(res[0].remarks);
                            // $('#from_description').val(res.description);
                        } else {
                            $('#available_budget_td').empty()
                            alert("Selected budget does not exists!");
                        }
                    });
                }
            });



            $('#u_dollar').keyup(function () {
                var u_dollar = $(this).val();
                var qty = $('#qty').val();
                var p = $('#pkr').val();
                var dollar = u_dollar.replace(",", "");
                var pkr = p.replace(",", "");

                var total_dollar = dollar * qty;
                var total_pkr = total_dollar * pkr;
                $('#t_dollar').val(total_dollar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#t_pkr').val(total_pkr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });

            $('#qty').keyup(function () {
                var qty = $(this).val();
                var d = $('#u_dollar').val();
                var p = $('#pkr').val();
                var dollar = d.replace(",", "");
                var pkr = p.replace(",", "");

                var total_dollar = dollar * qty;
                var total_pkr = total_dollar * pkr;
                //   console.log(qty);
                //   console.log(dollar);
                //   console.log(pkr);
                //   console.log(total_dollar);
                //   console.log(total_pkr);
                $('#t_dollar').val(total_dollar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#t_pkr').val(total_pkr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });

            // $('#budget_qty').focusin(function () {
            //         var qty_value = $('#budget_qty').val();
            //         var new_qty = check_qty(qty_value);
            //          console.log(new_qty);
            //      });

            $('.btn_swap').click(function () {
                var year = $('#year').val();
                var category = $('#category').val();
                var subcategory = $('#subcategory').val();
                var from_dept = $('#from_dept').val();
                var to_dept = $('#dept_id').val();
                var from_remarks = $('#from_remarks').val();
                var from_description = $('#from_description').val();
                var to_remarks = $('#to_remarks').val();
                var to_description = $('#to_description').val();
                var radio_budget_id = $('#budgeted_item_pk').val();
                var budget_qty = $('#budget_qty').val();
                var swap_dept_to_name = $('#swap_dept_to_name').val();
                var tbl_td;
                var fields = {
                    "_token": "{{ csrf_token() }}",
                    'year_id': year,
                    'category_id': category,
                    'sub_cat_id': subcategory,
                    'from_dept': from_dept,
                    'to_dept': to_dept,
                    'from_remarks': from_remarks,
                    'to_remarks': to_remarks,
                    'from_description': from_description,
                    'to_description': to_description,
                    'qty': budget_qty,
                    'radio_budget_id': radio_budget_id,
                    'swap_dept_to_name': swap_dept_to_name,
                }
                $.ajax({
                    url: "{{ url('swapping2') }}",
                    type: "POST",
                    data: fields,
                    success: function (response) {
                        console.log('Fields ==> ',fields);
                        if (response.code == 403) {
                            $('#msgs').html("<div class='alert alert-danger'>" + response.message + "</div>");
                        } else {
                            $('#msgs').html("<div class='alert alert-success'>" + response.message + "</div>");
                            setTimeout(function () {// wait for 5 secs
                                location.reload(); // then reload the page
                            }, 5000);
                        }
                    }
                    // ,
                    // error: function(error) {
                    //     $('#msgs').html("<div class='alert alert-danger'>"+error+"</div>");
                    // }
                });
            });


            $(".t_seperator").focusout(function () {
                var value = $(this).val();

                var num_parts = value.toString().split(".");
                num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).val(num_parts.join("."));
                //alert(num_parts.join("."));
            });

        });

        function get_value_radio(obj) {
            var id = obj.value;
            if (id) {
                var fields = {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                }
                $.post("{{ url('get_budget_single') }}", fields, function (res) {

                    if (res) {
                        console.log(res);
                        $('#from_remarks').val(res.remarks);
                        $('#from_description').val(res.description);
                        $('#budget_qty').val(res.remaining);
                        $('#default_qty').val(res.remaining);
                        $('#budgeted_item_pk').val(res.id);
                    } else {
                        $('#msgs').html("<div class='alert alert-danger'>Something Went Wrong ! </div>");
                    }
                });
            }
            return id;
        }

        // function check_qty(value){
        //      const qty_value_const = $('#default_qty').val();
        //      console.log(qty_value_const,'qty of default' , value);
        //      if(qty_value_const < value){
        //         $('.budget_qty').css('border-color', 'red');
        //          $('.btn_swap').hide();
        //          alert("Quantity Exceeded");
        //      }else {
        //          $('.budget_qty').css('border-color', '');
        //          $('.btn_swap').show();
        //      }
        //  }

    </script>

@endsection
