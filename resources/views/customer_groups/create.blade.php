@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="container">
        <!-- Page Heading Start -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Customer Groups</h1>
        </div>
        <!-- Page Heading End -->
        @include('partials/messages')

        <form id="groupform" method="post" action="{{ url('customer-groups') }}" >
            @csrf
            <div class="row form-group">
                <div class="col-3">
                    <label for="groupcode">Group Code:</label>
                    <input type="text" seq="1" class="form-control enterseq" id="groupcode" name="groupcode" maxlength="10" value="{{ old('groupcode') }}" />
                    <span class="text-danger">{{ $errors->first('groupcode') }}</span>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-9">
                    <label for="title">Description:</label>
                    <input type="text" seq="2" class="form-control enterseq" name="description" maxlength="60" value="{{ old('description') }}"/>
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    <label for="system_id">System ID: <span style="color:red;">*</span>:</label>
                    <select class="form-control enterseq" seq="3" name="category_id">
                        <option value=""> -- Selection --</option>
                        @if($data['categorylist'])
                            @foreach ($data['categorylist'] as $rcatg)
                                <option value="{{$rcatg['id']}}" {{ old('category_id') == $rcatg['id'] ? 'selected' : '' }}>
                                    {{ $rcatg['description'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    <label for="companyid">Bill From Company: <span style="color:red;">*</span>:</label>
                    <select class="form-control enterseq" seq="4" name="companyid">
                        <option value=""> -- Selection --</option>
                        @if($data['companylist'])
                            @foreach ($data['companylist'] as $rcompany)
                                <option value="{{$rcompany['id']}}" {{ old('companycode') == $rcompany['id'] ? 'selected' : '' }}>
                                    {{ $rcompany['companycode']." - ".$rcompany['companyname'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    <label for="companyid">Service Form Folder Name: :</label>
                    <input type="text" seq="5" class="form-control enterseq" name="foldername" maxlength="200" />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-5">
                    <label for="title">Customers:</label>
                    <select class="form-control customerAutoSelect enterseq overflow-ellipsis" seq="6" name="customerid"
                            placeholder="Customer search"
                            data-url="{{ action('App\Http\Controllers\CustomerGroupsController@customerList') }}"></select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-10">
                    <table class="table" id="tblcust">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th>Customers</th>
                            <th>Period Type</th>
                            <th>Amount</th>
                            <th>Include Hardware [Y/N]</th>
                            <th>Pay Before Service[Y/N]</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Service<br>Pay Date</th>
                            <th>Software License Per PC</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="empty">
                            <td class="text-center" colspan="12">No Record Found</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ action('App\Http\Controllers\CustomerGroupsController@index') }}" class="btn btn-secondary btn-xs">Back</a> <button type="submit" seq="7" class="btn btn-primary enterseq">Create</button>
        </form>

        <!-- Modal -->
        <div class="modal fade" id="modalLoading" role="dialog">
            <div class="modal-dialog">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                        <p>In progress.....</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalErrorMsg" role="dialog">
            <div class="modal-dialog">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                        <span class="errormsg"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="servicesModal" tabindex="-1" role="dialog" aria-labelledby="servicesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="servicesModalLabel">Services</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="custservices">
                            <input type="hidden" name="serviceid">
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="amount">Period Type:</label>
                                    <select class="form-control" name="contract_typ">
                                        <option value="">-- Selection --</option>
                                        <option value="1">Yearly</option>
                                        <option value="2">Monthly</option>
                                        <option value="3">Bi-Monthly</option>
                                        <option value="4">Half Yearly</option>
                                        <option value="5">Quarterly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="amount">Amount:</label>
                                    <input type="text" class="form-control" name="amount" onKeyPress="return js_validate_amt_dec(event);">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="inc_hw">Include Hardware [Y/N]: </label>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="inc_hw">
                                        <input type="checkbox" class="custom-control-input enterseq" name="cinc_hw" id="cinc_hw">
                                        <label class="custom-control-label enterseq" for="inc_hw"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="pay_before">Pay Before Service[Y/N]</label>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="pay_before">
                                        <input type="checkbox" class="custom-control-input enterseq" name="cpay_before" id="cpay_before">
                                        <label class="custom-control-label enterseq" for="pay_before"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="start_date">Start Date: </label>
                                    <input type="text" class="form-control" name="start_date">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="end_date">End Date:</label>
                                    <input type="text" class="form-control" name="end_date">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="service_date">Service Pay Date:</label>
                                    <input type="text" class="form-control" name="service_date">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="soft_license">Software License Per PC:</label>
                                    <input type="number" class="form-control" name="soft_license">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="active">Active [Y/N]</label>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="active">
                                        <input type="checkbox" class="custom-control-input enterseq" name="cactive" id="cactive">
                                        <label class="custom-control-label enterseq" for="active"></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnSaveServices">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script>
    <script type="text/javascript">
        if ($("#groupform").length > 0) {
            $("#groupform").validate({
                rules: {
                    groupcode: {
                        required: true,
                        maxlength: 20
                    },
                    description: {
                        required: true,
                        maxlength:200
                    },
                    category_id:{
                        required: true,
                    },
                    companyid:{
                        required: true,
                    }
                },
                messages: {
                    groupcode: {
                        required: "Please enter group code",
                        maxlength: "Your group code maxlength should be 10 characters long."
                    },
                    description: {
                        required: "Please enter description",
                        maxlength: "The description should be 60 characters long"
                    },
                    category_id:{
                        required: "Please select system id",
                    },
                    companyid:{
                        required: "Please select company",
                    }
                },
            })
            $("#groupform").submit(function(evt){
                $("input[type='text']").each(function(i){
                    $(this).val($(this).val().toUpperCase());
                })
            })
        }
        $(document).ready(function(evt){
            $(".enterseq").each(function(i){
                $(this).keydown(function(event){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    switch(keycode) {
                        case 13:
                            if($(this).is("input")) {
                                $(this).val($(this).val().toUpperCase());
                            } else if($(this).is("button[type='submit']")) {
                                $(this).click();
                                return false;
                            }
                            if($(this).attr("name")=="description"){
                                $(".customerAutoSelect").select();
                            } else {
                                var dd = parseInt($(this).attr("seq"),10)+1;
                                if( $(".enterseq").filter("[seq='"+dd+"']").length>0){
                                    if($(".enterseq").filter("[seq='"+dd+"']").is("input")) {
                                        $("input[type='text']").filter("[seq='"+dd+"']").select();
                                    } else if($(".enterseq").filter("[seq='"+dd+"']").is("select")){
                                        $("select").filter("[seq='"+dd+"']").focus();
                                    } else if($(".enterseq").filter("[seq='"+dd+"']").is("checkbox")){
                                        $("checkbox").filter("[seq='"+dd+"']").select();
                                    } else if($(".enterseq").filter("[seq='"+dd+"']").is("button")){
                                        $("button").filter("[seq='"+dd+"']").focus();
                                    }
                                }
                            }
                            break;
                        case 38:
                            var dd = (parseInt($(this).attr("seq"),10)>0)?(parseInt($(this).attr("seq"),10)-1):parseInt($(this).attr("seq"),10);
                            if($("input[type='text']").filter("[seq='"+dd+"']").length>0){
                                $("input[type='text']").filter("[seq='"+dd+"']").select();;
                            } else if($("select").filter("[seq='"+dd+"']").length>0){
                                $("select").filter("[seq='"+dd+"']").focus();;
                            }
                    }
                    if(keycode==13)
                        return false;
                })
            })
            if($(".enterseq").filter("[seq='1']").is("input")) {
                $("input[type='text']").filter("[seq='1']").select();
            } else if($(".enterseq").filter("[seq='1']").is("select")){
                $("select").filter("[seq='1']").focus();
            } else if($(".enterseq").filter("[seq='1']").is("checkbox")){
                $("checkbox").filter("[seq='1']").select();
            } else if($(".enterseq").filter("[seq='1']").is("button")){
                $("button").filter("[seq='1']").focus();
            }

            $('.customerAutoSelect').autoComplete({minLength:2,
                events: {
                    searchPost: function (resultFromServer) {
                        setTimeout(function(){
                            $('.customerAutoSelect').next().find('a').eq(0).addClass("active");
                        },100)
                        return resultFromServer;
                    }
                }
            });
            $('.customerAutoSelect').keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    if($(this).val()==""){
                        setTimeout(function(){
                            $("button").filter("[seq='4']").focus();
                        },500);
                    }
                    return false;
                }
            })
            $('.customerAutoSelect').on('change', function (e, datum) {
                if($("input[name='customerid']").val()!=""){
                    var data="categoryid="+$("select[name='category_id']").val()+"&customerid="+$("input[name='customerid']").val();
                    $.ajax({
                        url: "{{ action('App\Http\Controllers\CustomerGroupsController@custservice') }}",
                        type:'get',
                        dataType: 'json',
                        data:data,
                        beforeSend: function(){
                            $('#modalLoading').modal('show');
                        },
                        success: function(json){
                            setTimeout(function(){ $("#modalLoading .close").click(); },500);
                            js_add_customer(json,$('.customerAutoSelect').val());
                            $('.customerAutoSelect').select();
                            $('.customerAutoSelect').next().find("a").remove();

                            return false;
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            setTimeout(function(){ $("#modalLoading .close").click(); },500);
                            $('.customerAutoSelect').blur();
                            $('.customerAutoSelect').next().find("a").remove();
                            $("input[name='customerid_text']").val('');
                            $("span.errormsg").html("This customer didnt have "+$("select[name='category_id'] option:selected").text()+" service! Please add customer service!");
                            $('#modalErrorMsg').modal('show');

                            return false;
                        }
                    })
                } else {
                    alert("Invalid Customer!");
                }
                return false;
            });
            $('#modalErrorMsg').on('hidden.bs.modal', function () {
                $('.customerAutoSelect').select();
                return false;
            });
            $('.customerAutoSelect').on('autocomplete.select', function (e, datum) {
                $(this).change();
                return false;
            })
            $(".custom-switch").click(function(evt){
                if($("input[name='"+$(this).find("input[type='checkbox']").attr("name")+"']").prop("checked")){
                    $("input[name='"+$(this).find("input[type='checkbox']").attr("name")+"']").prop("checked",false);
                    $(this).find("input[type='hidden']").val("N");
                } else {
                    $("input[name='"+$(this).find("input[type='checkbox']").attr("name")+"']").prop("checked",true);
                    $(this).find("input[type='hidden']").val("Y");
                }
                return false;
            })
            $("select[name='contract_typ']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("input[name='amount']").select();
                    return false;
                }
            })
            $("input[name='amount']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("input[name='start_date']").select();
                    return false;
                } else if(keycode==38){
                    $("select[name='contract_typ']").select();
                    return false;
                }
            })
            $("input[name='start_date']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    js_date_format($(this));
                    if($(this).val()!=""){
                        if($("select[name='contract_typ']").val()=="1") {
                            var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                            var ndt = add_years(dt,1);
                            $("input[name='end_date']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                        } else if($("select[name='contract_typ").val()=="2") {
                            var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                            var ndt = add_months(dt,1);
                            $("input[name='end_date']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                        } else if($("select[name='contract_typ']").val()=="3") {
                            var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                            var ndt = add_months(dt,2);
                            $("input[name='end_date']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                        } else if($("select[name='contract_typ']").val()=="4") {
                            var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                            var ndt = add_months(dt,6);
                            $("input[name='end_date']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                        } else if($("select[name='contract_typ']").val()=="5") {
                            var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                            var ndt = add_months(dt,3);
                            $("input[name='end_date']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                        }
                    }
                    if($("input[name='pay_before']").val()=="Y"){
                        $("input[name='service_date']").val($(this).val());
                    } else {
                        var dt = new Date($("input[name='end_date']").val().substr(6,4),(parseInt($("input[name='end_date']").val().substr(3,2),10)-1),$("input[name='end_date']").val().substr(0,2));
                        var ndt = add_dates(dt,1);
                        $("input[name='service_date']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                    }
                    $("input[name='end_date']").select();
                    return false;
                } else if(keycode==38){
                    $("input[name='amount']").select();
                    return false;
                }
            })
            $("input[name='end_date']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    js_date_format($(this));
                    $("input[name='service_date']").select();
                    return false;
                } else if(keycode==38){
                    $("input[name='start_date']").select();
                    return false;
                }
            })
            $("input[name='service_date']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    js_date_format($(this));
                    $("input[name='soft_license']").select();
                    return false;
                } else if(keycode==38){
                    $("input[name='end_date']").select();
                    return false;
                }
            })
            $("input[name='soft_license']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("#btnSaveServices").focus();
                    return false;
                } else if(keycode==38){
                    $("input[name='service_date']").select();
                    return false;
                }
            })
            $("#btnSaveServices").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $(this).click();
                    return false;
                } else if(keycode==38){
                    $("input[name='soft_license']").select();
                    return false;
                }
            })
            $("#btnSaveServices").click(function(event){
                var data=$('#custservices').serialize();

                $.ajax({
                    url: "{{ action('App\Http\Controllers\CustomerGroupsController@savecustservice') }}",
                    type:'post',
                    dataType: 'json',
                    data:data,
                    success: function(json){
                        $("#servicesModal .close").click();
                        alert(json.msg);
                        window.location.reload();
                        return false;
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert("Save Service Error!");
                        return false;
                    }
                })
                return false;
            })
        })
        function js_edit_service(id){
            if(id!=""){
                var data="serviceid="+id;
                $.ajax({
                    url: "{{ action('App\Http\Controllers\CustomerGroupsController@custservice') }}",
                    type:'get',
                    dataType: 'json',
                    data:data,
                    beforeSend: function(){
                        //$('#modalLoading').modal('show');
                    },
                    success: function(json){
                        //$("#modalLoading .close").click();
                        $("#servicesModal").modal("show");
                        $("select[name='contract_typ']").val(json.contract_typ);
                        $("input[name='amount']").val(json.amount);
                        $("input[name='serviceid']").val(json.id);
                        $("input[name='inc_hw']").val(json.inc_hw);
                        if(json.inc_hw=="Y"){
                            if(!$("input[name='cinc_hw']").prop("checked")) {
                                $("input[name='cinc_hw']").parent().click();
                            }
                        } else {
                            if($("input[name='cinc_hw']").prop("checked")) {
                                $("input[name='cinc_hw']").parent().click();
                            }
                        }
                        $("input[name='pay_before']").val(json.pay_before);
                        if(json.pay_before=="Y"){
                            if(!$("input[name='cpay_before']").prop("checked")) {
                                $("input[name='cpay_before']").parent().click();
                            }
                        } else {
                            if($("input[name='cpay_before']").prop("checked")) {
                                $("input[name='cpay_before']").parent().click();
                            }
                        }
                        $("input[name='start_date']").val(json.start_date);
                        $("input[name='end_date']").val(json.end_date);
                        $("input[name='service_date']").val(json.service_date);
                        $("input[name='soft_license']").val(json.soft_license);
                        $("input[name='active']").val(json.active);
                        if(json.active=="Y"){
                            if(!$("input[name='cactive']").prop("checked")) {
                                $("input[name='cactive']").parent().click();
                            }
                        } else {
                            if($("input[name='cactive']").prop("checked")) {
                                $("input[name='cactive']").parent().click();
                            }
                        }
                        setTimeout(function(){ $("select[name='contract_typ']").focus(); },500);
                        return false;
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        setTimeout(function(){ $("#modalLoading .close").click(); },500);
                        alert("Invalid Services!");
                        return false;
                    }
                })
            }
        }
        function js_add_customer(data,name){
            if( $("table#tblcust tbody tr.empty").length>0){
                $("table#tblcust tbody tr.empty").remove();
            }
            var bcheck=false;
            $("input[name='cust[]']").each(function(i){
                if($(this).val()==data.customerid){
                    bcheck=true;
                }
            })
            if(!bcheck) {
                var ncount=$("table#tblcust tbody tr").length;
                var trrow="<tr>";
                trrow+="<td scope=\"row\"><input type='hidden' name='cust[]' value='"+data.customerid+"'><span>"+(ncount+1)+"</span></td>";
                if(data.id!="") {
                    trrow+="<td><a href=\"javascript:void(0);\" onclick=\"js_edit_service('"+data.id+"')\">"+name+"</a></td>";
                } else {
                    trrow+="<td>"+name+"</td>";
                }
                trrow+="<td>"+((data.contract_typ=="1")?"Yearly":((data.contract_typ=="2")?"Monthly":((data.contract_typ=="3")?"Bi-Monthly":((data.contract_typ=="4")?"Half Yearly":((data.contract_typ=="5")?"Quarterly":"")))))+"</td>";
                trrow+="<td>"+data.amount+"</td>";
                trrow+="<td>"+data.inc_hw+"</td>";
                trrow+="<td>"+data.pay_before+"</td>";
                trrow+="<td>"+data.start_date+"</td>";
                trrow+="<td>"+data.end_date+"</td>";
                trrow+="<td>"+data.service_date+"</td>";
                trrow+="<td>"+data.soft_license+"</td>";
                trrow+="<td>"+data.active+"</td>";
                trrow+="<td class=\"text-center\">";
                trrow+="<button class=\"btn btn-danger\" type=\"button\" onclick=\"js_delete(this);\">Delete</button>";
                trrow+="</td>";
                trrow+="</tr>";
                $("table#tblcust tbody").append(trrow);
                $(".customerAutoSelect").select();
                $('.customerAutoSelect').autoComplete('clear');
                $('.customerAutoSelect').val('');
                $(".dropdown-menu").empty();
            }
            $(".customerAutoSelect").val('');
            $("input[name='customerid']").val('');

            return false;
        }
        function js_delete(obj){
            $(obj).parent().parent().remove();
            if($("table#tblcust tbody tr").length>0){
                $("table#tblcust tbody tr").each(function(i){
                    $(this).find("td").eq(0).find('span').html((i+1));
                })
            } else {
                var trrow = "<tr class=\"empty\">";
                trrow += "<td class=\"text-center\" colspan=\"12\">No Record Found</td>";
                trrow += "</tr>";
                $("table#tblcust tbody").append(trrow);
            }
            return false;
        }
        function js_date_format(obj,check18) {
            check18 = (check18==undefined)?"N":check18;
            if($(obj).val().length>=1){
                if($(obj).val().length==8){
                    $(obj).val($(obj).val().substr(0,2)+"/"+$(obj).val().substr(2,2)+"/"+$(obj).val().substr(4,4));
                }
                if(!checkdate($(obj).val()) && $(obj).val()!="") {
                    $(obj).parent().find("label.error").empty().text('Please enter a correct date');
                    setTimeout(function(){$(obj).select();},500);
                } else {
                    $(obj).parent().find("label.error").empty();
                }
                if ($(obj).val().length!=10){
                    $(obj).val('');
                }
            }
            if(check18=="Y"){
                var age = system_datetime.substr(6,4)-$(obj).val().substr(6,4);
                var age_mth = system_datetime.substr(3,2)-$(obj).val().substr(3,2);
                var age_day = system_datetime.substr(0,2)-$(obj).val().substr(0,2);
                if(age<P_Blkag || (age==P_Blkag && age_mth<0) || (age==P_Blkag && age_mth==0 && age_day<0) ){
                    if(!alert("Customer Under Age !!!")) {
                        ddd = $(obj);
                        setTimeout("ddd.select()",500);
                        return false;
                    }
                    return false;
                }
            }
        }
        // validate date
        function checkdate(value) {
            var check = false;
            var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/
            if( re.test(value)){
                var adata = value.split('/');
                var gg = parseInt(adata[0],10);
                var mm = parseInt(adata[1],10);
                var aaaa = parseInt(adata[2],10);
                var xdata = new Date(aaaa,mm-1,gg);
                if ( ( xdata.getFullYear() == aaaa ) && ( xdata.getMonth () == mm - 1 ) && ( xdata.getDate() == gg ) )
                    check = true;
                else
                    check = false;
            } else
                check = false;
            return check;
        }
        function add_years(dt,n) {
            var fdt = new Date(dt.setFullYear(dt.getFullYear() + n));
            return new Date(fdt.setDate(fdt.getDate() - 1));
        }
        function add_months(dt,n) {
            var fdt = new Date(dt.setMonth(dt.getMonth() + n));
            return new Date(fdt.setDate(fdt.getDate() - 1));
        }
        function add_dates(dt,n){
            return new Date(dt.setDate(dt.getDate() + n));
        }
        function js_validate_amt_dec(e){
            if( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57))
                return false;
            //if( e.keyCode=="46" && $(e.target).val().substr($(e.target).getCursorPosition(),1)=="." )
            //return false;
            setTimeout(function() {
                js_delay_validate_amt_dec($(e.target),e.keyCode,e.which);
            }, 100)
            //setTimeout("js_delay_validate_amt_dec('"+$(e.target)+"','"+e.keyCode+"','"+e.which+"');",100);
            if(e.which==46)
                return false;
        }
        function js_delay_validate_amt_dec(nam,keycode,keywhich,dec_point) {
            dec_point=(dec_point==undefined)?2:dec_point;
            var ss = $(nam).val().split(".");
            if( (keycode=="46" || keycode=="8" || keycode=="0") && $(nam).val()=="" ) {
                $(nam).val("0.00");
                ss = $(nam).val().split(".");
            }
            if(ss[1]==undefined || ss[1].length<dec_point){
                $(nam).val((isNaN(parseFloat($(nam).val()).toFixed(dec_point)))?parseFloat("0").toFixed(dec_point):parseFloat($(nam).val()).toFixed(dec_point));
                $(nam).selectRange(ss[0].length);
            } else if(ss[1].length>=dec_point) {
                if( ($(nam)[0].selectionStart>ss[0].length && keycode!="37") || ($(nam)[0].selectionStart-1)>ss[0].length){
                    if(keycode=="37") {
                        $(nam).selectRange( ($(nam)[0].selectionStart-1), ($(nam)[0].selectionStart) );
                    }else if(keycode=="110" || keycode=="190"){

                    } else {
                        if(ss[1].length>=dec_point) {
                            var ssdd = $(nam).val().split(".");
                            if(dec_point=="1") {
                                $(nam).val( (isNaN(parseFloat(Math.floor($(nam).val()*100)/100).toFixed(dec_point)))?parseFloat("0").toFixed(dec_point):parseFloat((Math.floor($(nam).val())+String(ssdd[1])+"0")/100).toFixed(dec_point) );
                            } else {
                                $(nam).val( (isNaN(parseFloat(Math.floor($(nam).val()*100)/100).toFixed(dec_point)))?parseFloat("0").toFixed(dec_point):parseFloat(Math.floor($(nam).val())+String(ssdd[1])/100).toFixed(dec_point) );
                            }
                            $(nam).selectRange(($(nam).val().length-1),$(nam).val().length);
                        } else {
                            $(nam).selectRange($(nam)[0].selectionStart, ($(nam)[0].selectionStart+1) );
                        }
                    }
                } else if(keycode=="37" && $(nam)[0].selectionStart>ss[0].length) {
                    $(nam).selectRange(ss[0].length);
                } else if(ss[1].length>dec_point) {
                    $(nam).val( (isNaN(parseFloat(Math.floor($(nam).val()*100)/100).toFixed(dec_point)))?parseFloat("0").toFixed(dec_point):parseFloat(Math.floor($(nam).val()*100)/100).toFixed(dec_point) );
                    $(nam).selectRange($(nam).val().length);
                }
            }
            if(keywhich=="46"){
                $(nam).selectRange(ss[0].length+1, (ss[0].length+2) );
            }
        }
        $.fn.selectRange = function(start, end) {
            if(!end) end = start;
            return this.each(function() {
                if (this.setSelectionRange) {
                    this.focus();
                    this.setSelectionRange(start, end);
                } else if (this.createTextRange) {
                    var range = this.createTextRange();
                    range.collapse(true);
                    range.moveEnd('character', end);
                    range.moveStart('character', start);
                    range.select();
                }
            });
        };
    </script>
@endsection
