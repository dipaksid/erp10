@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="container">
        <!-- Page Heading Start -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Customer Group</h1>
        </div>
        <!-- Page Heading END -->

        @include('partials/messages')

        <form id="groupform" method="post" action="{{ action('App\Http\Controllers\CustomerGroupsController@update', $id) }}">
            @csrf
            @method('PATCH')
            <input name="lastrunno" type="hidden" value="{{ (($group->category)?$group->category->lastrunno:"") }}">
            <input name="categorycode" type="hidden" value="{{ (($group->category)?$group->category->categorycode:"") }}">
            <div class="row form-group">
                <div class="col-6">
                    <label for="groupcode">Group Code:</label>
                    <input type="text" seq="1" class="form-control enterseq" name="groupcode" value="{{$group->groupcode}}" maxlength="20" />
                    <span class="text-danger">{{ $errors->first('groupcode') }}</span>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    <label for="title">Description:</label>
                    <input type="text" seq="2" class="form-control enterseq" name="description" value="{{$group->description}}" maxlength="200" />
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    <label for="system_id">System ID: <span style="color:red;">*</span>:</label>
                    <select class="form-control enterseq" seq="3" name="category_id">
                        <option value=""> -- Selection --</option>
                        @if($categorylist)
                            @foreach ($categorylist as $rcatg)
                                <option value="{{$rcatg['id']}}" {{ (($rcatg['id']==$group['categoryid'])?"selected":"") }}>{{$rcatg['description']}}</option>
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
                        @if($companylist)
                            @foreach ($companylist as $rcompany)
                                <option value="{{$rcompany['id']}}" {{ (($rcompany['id']==$group['companyid'])?"selected":"") }}>{{$rcompany['companycode']." - ".$rcompany['companyname']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    <label for="companyid">Service Form Folder Name: :</label>
                    <input type="text" seq="5" class="form-control enterseq" name="foldername" value="{{$group->foldername}}" maxlength="200" />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-4">
                    <label for="title">Customers:</label>
                    <select class="form-control customerAutoSelect enterseq overflow-ellipsis" seq="6" name="customerid"
                            placeholder="Customer search"
                            data-url="{{ action('App\Http\Controllers\CustomerGroupsController@customerList') }}">
                    </select>
                </div>
                <div class="col-4">
                    <br>
                    <label for="title"><span class="cfgpass">
                    @if($group->cfgpassword!="")
                        <a href="javascript:void(0);" onclick="js_openfile('{{ url('/').$group->cfgfile }}');">{{$group->cfgpassword}}</a>
                        <a href="javascript:void(0);" onclick="js_remove_cfg('{{$group->id}}')" class="btn btn-danger">Remove</a>
                        </span>  &nbsp; &nbsp; &nbsp; &nbsp;</label>
                    @else
                        </span>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>
                    @endif
                    <a href="javascript:void(0);" onclick="js_serial_act()" class="btn btn-primary">CFG</a>
                    <a href="javascript:void(0);" onclick="js_print_page()" class="btn btn-primary">Print</a>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-12">
                    <table class="table" id="tblcust">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col"></th>
                            <th></th>
                            <th>
                                <select class="form-control" name="allcontract_typ">
                                    <option value="">-- Selection --</option>
                                    <option value="1">Yearly</option>
                                    <option value="2">Monthly</option>
                                    <option value="3">Bi-Monthly</option>
                                    <option value="4">Half Yearly</option>
                                    <option value="5">Quarterly</option>
                                </select>
                            </th>
                            <th><input type="text" class="form-control" name="allamount" onKeyPress="return js_validate_amt_dec(event);"></th>
                            <th>
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="allinc_hw" value="N">
                                    <input type="checkbox" class="custom-control-input enterseq" name="allcinc_hw" id="cinc_hw">
                                    <label class="custom-control-label enterseq" for="allinc_hw"></label>
                                </div>
                            </th>
                            <th>
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="allpay_before" value="N">
                                    <input type="checkbox" class="custom-control-input enterseq" name="allcpay_before" id="cpay_before">
                                    <label class="custom-control-label enterseq" for="allpay_before"></label>
                                </div>
                            </th>
                            <th>
                                <button type="button" class="btn btn-primary" id="btnSaveAllServ">Save</button>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
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
                            <th>VPN Address</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($groupdetail->exists())
                            @php
                                $ilp=0;
                            @endphp
                            @foreach ($groupdetail->get() as $nd=> $rdet)
                                @if($rdet->customer)
                                    @php
                                        $ilp++;
                                    @endphp
                                    <tr>
                                        <td scope="row"><input type='hidden' name='detid[]' value='{{$rdet->id}}'><input type='hidden' name='cust[]' value='{{$rdet->customerid}}'><span>{{$ilp}}</span></td>
                                        @if($rdet->customerservices($group->categoryid)->exists())
                                            <td><a href="javascript:void(0);" onclick="js_edit_service('{{(($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->id:"")}}')">{{ (($rdet->customer->exists())?$rdet->customer->companycode."-".$rdet->customer->companyname:"") }}</a></td>
                                        @else
                                            <td>{{ (($rdet->customer->exists())?$rdet->customer->companycode."-".$rdet->customer->companyname:"") }}</td>
                                        @endif
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?(($rdet->customerservices($group->categoryid)->first()->contract_typ==1)?"Yearly":(($rdet->customerservices($group->categoryid)->first()->contract_typ==2)?"Monthly":(($rdet->customerservices($group->categoryid)->first()->contract_typ==3)?"Bi-Monthly":(($rdet->customerservices($group->categoryid)->first()->contract_typ==4)?"Half Yearly":(($rdet->customerservices($group->categoryid)->first()->contract_typ==5)?"Quarterly":""))))):"") }}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->amount:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->inc_hw:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->pay_before:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->start_date:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->end_date:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->service_date:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->soft_license:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->vpnaddress:"")}}</td>
                                        <td>{{ (($rdet->customerservices($group->categoryid)->exists())?$rdet->customerservices($group->categoryid)->first()->active:"")}}</td>
                                        <td class="text-center ">
                                            <button class="btn btn-danger" type="button" onclick="js_delete(this);">Delete</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr class="empty">
                                <td class="text-center" colspan="3">No Record Found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ action('App\Http\Controllers\CustomerGroupsController@index') }}?searchvalue={{((isset($input['searchvalue']))?$input['searchvalue']:'')}}&page={{((isset($input['page']))?$input['page']:'')}}" class="btn btn-secondary btn-xs">Back</a>
            <button type="submit" seq="7" class="btn btn-primary enterseq">Update</button>
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
                                    <label for="vpnaddress">VPN Address:</label>
                                    <input type="text" class="form-control" name="vpnaddress">
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
        <!-- Modal -->
        <div class="modal fade" id="serializationModal" tabindex="-1" role="dialog" aria-labelledby="serializationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="serializationModalLabel">Serialization</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="fileupload">CFG File Upload:</label>
                                    <input type="file" class="form-control" name="fileupload">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="customername">Group Name:</label>
                                    <input type="text" class="form-control" name="customername" readOnly="readOnly">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="serial_no">Serial Number: <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="serial_no" value="{{$group->serial_no}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="exp_dat">Expire Date: <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="exp_dat" value="{{$group->exp_dat}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="agentid">Agent: <span style="color:red;">*</span>:</label>
                                    <select class="form-control agentAutoSelect overflow-ellipsis" name="agentid"  placeholder="Agent search..." data-url="{{ action('App\Http\Controllers\CustomerGroupsController@agentlist') }}" autocomplete="off"></select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="soft_lic">Software License: <span style="color:red;">*</span>:</label>
                                    <input type="text" class="form-control" name="soft_lic" value="{{$group->soft_lic}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="curpassword">Current password:</label>
                                    <input type="text" class="form-control" name="curpassword" value="{{$group->cfgpassword}}" readOnly="readOnly">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="newpassword">New password:</label>
                                    <input type="text" class="form-control" name="newpassword" value="{{$group->cfgpassword}}" readOnly="readOnly">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnSaveSerial">Save</button>
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
                        maxlength: "Your group code maxlength should be 20 characters long."
                    },
                    description: {
                        required: "Please enter description",
                        maxlength: "The description should be 200 characters long"
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
                var input = $("<input>").attr("name", "agentid").attr("type", "hidden").val($("input[name='agentid']").val());
                $('#groupform').append($(input));
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
                                $("select[name='category_id']").focus();
                            } else if($(this).attr("name")=="category_id"){
                                $(".customerAutoSelect").focus();
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
            $("select[name='category_id']").bind("change",function(evt){
                data="categoryid="+$(this).val();
                $.ajax({
                    url:"{{ action('App\Http\Controllers\CustomerGroupsController@categorylist') }}",
                    data: data,
                    dataType: "json"
                }).done(function( data ) {
                    $("input[name='lastrunno']").val(data.lastrunno);
                    $("input[name='categorycode']").val(data.categorycode);
                })
            })
            $('.customerAutoSelect').autoComplete({minLength:2,
                events: {
                    searchPost: function (resultFromServer) {
                        setTimeout(function(){
                            if(!$('.customerAutoSelect').next().find('a').eq(0).hasClass("disabled")){
                                $('.customerAutoSelect').next().find('a').eq(0).addClass("active");
                            }
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
                            $("button").filter("[seq='6']").focus();
                        },500);
                    }
                    return false;
                }
            })
            $('.customerAutoSelect').on('change', function (e, datum) {
                if($("input[name='customerid']").val()!=""){
                    var data="categoryid="+$("select[name='category_id']").val()+"&customerid="+$("input[name='customerid']").val();
                    $.ajax({
                        url: "{{action('App\Http\Controllers\CustomerGroupsController@custservice')}}",
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

            $("input[name='fileupload']").bind("change",function(evt){
                var formd = new FormData();
                formd.append("_token", $("input[name='_token']").val());
                formd.append("hidAction", "uploadcfg");
                formd.append("catg", $("input[name='categorycode']").val());
                formd.append("id", $("input[name='groupcode']").val());
                formd.append("compnam", $("input[name='description']").val());
                formd.append("cfg_file",$(this).get(0).files[0]);
                $.ajax({
                    url: "{{ action('App\Http\Controllers\CustomerGroupsController@store') }}",
                    type: "POST",
                    data: formd,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false   // tell jQuery not to set contentType
                }).done(function( data ) {
                    if(data.msg!=undefined){
                        alert(data.msg);
                        //$("input[name='serial_no']").val("");
                        $("input[name='curpassword']").val("");
                        $("input[name='exp_dat']").val("");
                        $(".agentAutoSelect").val("");
                        $(".agentAutoSelect").prop("readOnly",false);
                    } else {
                        $("input[name='exp_dat']").val(data.exp_dat);
                        $("input[name='serial_no']").val(data.serial_no);
                        $("input[name='curpassword']").val(data.curpassword);
                        $("input[name='newpassword']").val(data.curpassword);
                        $(".agentAutoSelect").val("");
                        $(".agentAutoSelect").prop("readOnly",false);
                        $("input[name='newpassword']").prop("readOnly",false);
                        $(".agentAutoSelect").focus();
                    }
                    $("input[name='fileupload']").val('');
                    return false;
                });
            })
            $('.agentAutoSelect').autoComplete({minLength:2,
                events: {
                    searchPost: function (resultFromServer) {
                        setTimeout(function(){
                            $('.agentAutoSelect').next().find('a').eq(0).addClass("active");
                        },100)
                        return resultFromServer;
                    }
                }
            });
            $('.agentAutoSelect').keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    if(!$("input[name='newpassword']").prop("readOnly")){
                        $("input[name='newpassword']").select();
                    } else {
                        $('#btnSaveSerial').focus();
                    }

                    return false;
                } else if(keycode==38){
                    $("input[name='exp_dat']").select();
                    return false;
                }
            })
            $('.agentAutoSelect').on('change', function (e, datum) {
                setTimeout(function(){
                    if($('.agentAutoSelect').val()==""){
                        $('.agentAutoSelect').focus();
                    } else {
                        if($("input[name='newpassword']").prop('readonly')){
                            $('#btnSaveSerial').focus();
                        } else {
                            $("input[name='newpassword']").select();
                        }
                    }
                },300);
                return false;
            });
            $('.agentAutoSelect').on('autocomplete.select', function (e, datum) {
                $(this).change();
                return false;
            })
            $("input[name='serial_no']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("input[name='exp_dat']").select();
                    return false;
                }
            })
            $("input[name='exp_dat']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    js_date_format($(this));
                    $('.agentAutoSelect').focus();
                    return false;
                } else if(keycode==38){
                    $("input[name='serial_no']").select();
                    return false;
                }
            })
            $("input[name='newpassword']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $('#btnSaveSerial').focus();
                    return false;
                } else if(keycode==38){
                    if(!$(".agentAutoSelect").prop("readOnly")){
                        $(".agentAutoSelect").select();
                    }
                    return false;
                }
            })
            $('#btnSaveSerial').keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $(this).click();
                    return false;
                }
            })
            $('#btnSaveSerial').click(function(event){
                data = "_token="+$("input[name='_token']").val();
                data +="&hidAction=savecfg";
                data +="&category_id="+$("select[name='category_id']").val();
                data +="&companycode="+$("input[name='groupcode']").val();
                data +="&system_id="+$("input[name='categorycode']").val();
                data +="&serial_no="+$("input[name='serial_no']").val();
                data +="&exp_dat="+$("input[name='exp_dat']").val();
                data +="&curpassword="+$("input[name='curpassword']").val();
                data +="&newpassword="+$("input[name='newpassword']").val();
                data +="&companyname="+$("input[name='description']").val();
                data +="&soft_lic="+$("input[name='soft_lic']").val();
                data +="&groupid={{$id}}";
                if($("input[name='serial_no']").val()==""){
                    $("input[name='serial_no']").select();
                    return false;
                }
                if($("input[name='exp_dat']").val()==""){
                    $("input[name='exp_dat']").select();
                    return false;
                }
                $.ajax({
                    url:"{{action('App\Http\Controllers\CustomerGroupsController@store')}}",
                    type:'post',
                    dataType: 'json',
                    data:data,
                    beforeSend: function(){
                        $('#modalLoading').modal('show');
                    },
                    success: function(json){
                        setTimeout(function(){ $("#modalLoading .close").click(); },100);
                        alert(json.msg);
                        if(json.newpassword!=undefined){
                            $("input[name='curpassword']").val(json.newpassword);
                            $("input[name='newpassword']").val(json.newpassword);
                        } else {
                            $("#serializationModal .close").click();
                        }
                        $("input[name='newpassword']").prop("readOnly",false);
                        $("input[name='newpassword']").select();

                        return false;
                    }
                });
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
            /*$("input[name='inc_hw']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("label[for='pay_before']").focus();
                    return false;
                } else if(keycode==38){
                    $("select[name='amount']").select();
                    return false;
                }
            })
            $("input[name='pay_before']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("input[name='start_date']").select();
                    return false;
                } else if(keycode==38){
                    $("label[for='inc_hw']").focus();
                    return false;
                }
            })*/
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
                    $("input[name='vpnaddress']").select();
                    return false;
                } else if(keycode==38){
                    $("input[name='service_date']").select();
                    return false;
                }
            })
            $("input[name='vpnaddress']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("#btnSaveServices").focus();
                    return false;
                } else if(keycode==38){
                    $("input[name='soft_license']").select();
                    return false;
                }
            })
            $("#btnSaveServices").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $(this).click();
                    return false;
                } else if(keycode==38){
                    $("input[name='vpnaddress']").select();
                    return false;
                }
            })
            $("#btnSaveServices").click(function(event){
                var data=$('#custservices').serialize();

                $.ajax({
                    url: "{{action('App\Http\Controllers\CustomerGroupsController@savecustservice')}}",
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
            $("#btnSaveAllServ").click(function(event){
                var data="contract_typ="+$('select[name="allcontract_typ"]').val();
                data+="&amount="+$("input[name='allamount']").val();
                data+="&inc_hw="+$("input[name='allinc_hw']").val();
                data+="&pay_before="+$("input[name='allpay_before']").val();
                data+="&groupid={{$id}}";
                data+="&categoryid="+$("select[name='category_id']").val();
                $.ajax({
                    url: "{{action('App\Http\Controllers\CustomerGroupsController@savegroupcustservice')}}",
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
            /*$("input[name='active']").keydown(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==13){
                    $("#btnSaveServices").focus();
                    return false;
                } else if(keycode==38){
                    $("input[name='soft_license']").select();
                    return false;
                }
            })*/
        })
        function js_edit_service(id){
            if(id!=""){
                var data="serviceid="+id;
                $.ajax({
                    url: "{{action('App\Http\Controllers\CustomerGroupsController@custservice')}}",
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
                        $("input[name='vpnaddress']").val(json.vpnaddress);
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
        function js_remove_cfg(id){
            data = "_token="+$("input[name='_token']").val();
            data +="&hidAction=deletecfg";
            data +="&group_id="+id;
            $.ajax({
                url:"{{action('App\Http\Controllers\CustomerGroupsController@store')}}",
                type:'post',
                dataType: 'json',
                data:data,
                beforeSend: function(){
                    $('#modalLoading').modal('show');
                },
                success: function(json){
                    setTimeout(function(){ $("#modalLoading .close").click(); },100);
                    alert(json.msg);
                    window.location.reload();
                    return false;
                }
            });
        }
        function js_openfile(file){
            window.open(file,'downloadfile');
        }
        function js_add_customer(data, name){
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
                trrow+="<td>"+data.vpnaddress+"</td>";
                trrow+="<td>"+data.active+"</td>";
                trrow+="<td class=\"text-center\">";
                trrow+="<button class=\"btn btn-danger\" type=\"button\" onclick=\"js_delete(this);\">Delete</button>";
                trrow+="</td>";
                trrow+="</tr>";
                $("table#tblcust tbody").append(trrow);
                $(".customerAutoSelect").select();
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
        function js_serial_act(){
            $("input[name='customername']").val($("input[name='description']").val());

            $("#serializationModal").modal("show");
            if($("input[name='serial_no']").val()!="") {
                $("input[name='serial_no']").prop("readOnly",true);
                $("input[name='exp_dat']").prop("readOnly",true);
                if("{{$group->agentid}}"!="") {
                    $("input[name='agentid']").val("{{$group->agentid}}");
                    $(".agentAutoSelect").val("{{(($group->agent)?$group->agent->agentcode."-".$group->agent->name:"")}}");
                    $("input[name='agentid']").prop("readOnly",true);
                    $(".agentAutoSelect").prop("readOnly",true);
                } else {
                    $(".agentAutoSelect").prop("readOnly",false);
                    $("input[name='agentid']").prop("readOnly",false);
                }
                $("input[name='newpassword']").prop("readOnly",false);
                setTimeout(function(){ $("input[name='newpassword']").select(); },1000);
            } else {
                var lastrunno=$("input[name='lastrunno']").val();
                $("input[name='serial_no']").val(parseInt(lastrunno,10)+1);
                $("input[name='serial_no']").prop("readOnly",false);
                $("input[name='exp_dat']").prop("readOnly",false);
                $("input[name='agentid']").prop("readOnly",false);
                setTimeout(function(){ $("input[name='serial_no']").select(); },1000);
            }
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
        function js_print_page(){
            window.open("{{url('/')}}/customergroup/printpdffile/{{$id}}"+"?"+Math.random().toString(36).substring(7));
        }
    </script>
@endsection
