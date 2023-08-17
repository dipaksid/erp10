@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div>
            <!-- Page Heading START-->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Customer Service</h1>
            </div>
            <!-- Page Heading END-->

            @include('partials/messages')

            <form id="serviceform" method="post" action="{{ action('App\Http\Controllers\CustomerServicesController@update', $customer->id) }}" >
                @csrf
                @method('PATCH')

                <input name="customerid" type="hidden" value="{{ $id }}">

                <div class="row form-group">
                    <div class="col-3">
                        <label for="companycode">Customer Code:</label>
                        <input type="text" class="form-control" id="companycode" name="companycode" readOnly="readOnly" value="{{$customer->companycode}}" maxlength="30"/>
                        <span class="text-danger">{{ $errors->first('companycode') }}</span>
                    </div>
                    <div class="col-6">
                        <label for="companyname">Customer Name</label>
                        <input type="text" class="form-control" id="companyname" name="companyname" readOnly="readOnly" value="{{$customer->companyname}}" maxlength="200"/>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-12">
                        <table class="table" id="tblcust">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th style="width:80px;">Category</th>
                                <th style="width:90px;">Amount </th>
                                <th style="width:90px;">Period Type</th>
                                <th style="width:50px; padding:1px; text-align:center;">Include <br>Hardware<br>[Y/N]</th>
                                <th style="width:50px; padding:1px; text-align:center;">Pay <br>Before <br>Service<br>[Y/N]</th>
                                <th style="width:150px;">Start Date</th>
                                <th style="width:150px;">End Date</th>
                                <th style="width:120px;">Service<br>Pay Date</th>
                                <th>Software<br>License<br> Per PC</th>
                                <th>POS<br>License<br> Per PC</th>
                                <th>VPN Address </th>
                                <th style="text-align:center;">Active</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($categories)
                                @foreach($categories as $k => $rcategory)
                                    <tr>
                                        <td class="pl-1 pr-0" scope="col">{{($k+1)}}</td>
                                        <td class="pl-1 pr-0" style="width:80px;">
                                            <input type="hidden" name="keepserviceid_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->id:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepamount_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->amount:'')}}" disabled="disabled">
                                            <input type="hidden" name="keeprmk_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->rmk:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepcontract_typ_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->contract_typ:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepinc_hw_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->inc_hw:'N')}}" disabled="disabled">
                                            <input type="hidden" name="keeppay_before_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->pay_before:'N')}}" disabled="disabled">
                                            <input type="hidden" name="keepstart_date_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->start_date:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepend_date_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->end_date:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepservice_date_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->service_date:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepsoft_license_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->soft_license:'')}}" disabled="disabled">
                                            <input type="hidden" name="keeppos_license_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->pos_license:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepvpnaddress_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->vpnaddress:'')}}" disabled="disabled">
                                            <input type="hidden" name="keepactive_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->active:'N')}}" disabled="disabled">

                                            @if(isset($service[$rcategory->id]))
                                                <a href="javascript:void(0);" id="serialclick_{{$service[$rcategory->id]->id}}" onclick="js_serial_act(this,'{{$service[$rcategory->id]->id}}','{{$service[$rcategory->id]->serial_no}}','{{$service[$rcategory->id]->exp_dat}}','{{$service[$rcategory->id]->agentid}}','{{$service[$rcategory->id]->agentname}}','{{$service[$rcategory->id]->cfgpassword}}','{{$service[$rcategory->id]->lastrunno}}','{{$rcategory->id}}');">{{$rcategory->categorycode}}</a> <br>
                                                <span id="cfgpf_{{$service[$rcategory->id]->id}}">
                                                    @if($service[$rcategory->id]->serial_no!="")
                                                        {{$service[$rcategory->id]->cfgpassword}}<br><a href="javascript:void(0);" onclick="js_openfile('{{ url('/').$service[$rcategory->id]->cfgfile }}');" class="btn btn-primary">CFG</a>
                                                        <br><a href="javascript:void(0);" onclick="js_remove_cfg('{{$service[$rcategory->id]->id}}','{{$service[$rcategory->id]->lastrunno}}','{{$rcategory->id}}')" class="btn btn-danger">Remove</a>
                                                        <a href="javascript:void(0);" onclick="js_downloadpkpb_qr('{{$service[$rcategory->id]->id}}')" class="btn btn-info">PKPB QR</a>
                                                    @endif
                                                </span>
                                            @else
                                                {{$rcategory->categorycode}}
                                            @endif
                                        </td>
                                        <td class="pl-1 pr-0" style="width:90px;">
                                            @can('CUSTOMER SERVICE PRICE')
                                                <input type="text" class="form-control enterseq" disabled="disabled" seq="1" name="amount_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->amount:'')}}" onKeyPress="return js_validate_amt_dec(event);" maxlength="15"/>
                                            @endcan


                                        </td>
                                        <td class="pl-1 pr-0" style="width:90px;">
                                            <select class="form-control enterseq" seq="3" disabled="disabled" name="contract_typ_{{$rcategory->id}}">
                                                <option value="">-- Selection --</option>
                                                <option value="1" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->contract_typ=="1" )?'selected':'')}}>Yearly</option>
                                                <option value="2" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->contract_typ=="2" )?'selected':'')}}>Monthly</option>
                                                <option value="3" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->contract_typ=="3" )?'selected':'')}}>Bi-Monthly</option>
                                                <option value="4" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->contract_typ=="4" )?'selected':'')}}>Half Yearly</option>
                                                <option value="5" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->contract_typ=="5" )?'selected':'')}}>Quarterly</option>
                                            </select>
                                        </td>
                                        <td class="pl-1 pr-0" style="width:50px; text-align:center;">
                                            <div class="custom-control custom-switch">
                                                <input type="hidden" name="inc_hw_{{$rcategory->id}}" disabled="disabled" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->inc_hw:'N')}}">
                                                <input type="checkbox" class="custom-control-input enterseq" disabled="disabled" onclick="js_click(this)" seq="5" name="cinc_hw_{{$rcategory->id}}" id="inc_hw_{{$rcategory->id}}" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->inc_hw=="Y" )?'checked="checked"':'')}}>
                                                <label class="custom-control-label enterseq" seq="4" for="inc_hw_{{$rcategory->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="pl-1 pr-0" style="width:50px; text-align:center;">
                                            <div class="custom-control custom-switch">
                                                <input type="hidden" name="pay_before_{{$rcategory->id}}" disabled="disabled" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->pay_before:'N')}}">
                                                <input type="checkbox" class="custom-control-input enterseq" disabled="disabled" onclick="js_click(this)" seq="7" name="cpay_before_{{$rcategory->id}}" id="pay_before_{{$rcategory->id}}" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->pay_before=="Y" )?'checked="checked"':'')}}>
                                                <label class="custom-control-label enterseq" seq="6" for="pay_before_{{$rcategory->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="pl-1 pr-0" style="width:150px;">
                                            <input type="text" class="form-control custom-control-inline enterseq datepicker" style="margin:0 !important;" disabled="disabled" seq="8" name="start_date_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->start_date:'')}}" maxlength="10"/>
                                            <!--<label class="input-group-addon btn col-1" for="testdate">
                                               <span class="fa fa-calendar"></span>
                                            </label>-->
                                        </td>
                                        <td class="pl-1 pr-0" style="width:150px;">
                                            <input type="text" class="form-control custom-control-inline enterseq datepicker" style="margin:0 !important;" disabled="disabled" seq="9" name="end_date_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->end_date:'')}}" maxlength="10"/>
                                            <!--<label class="input-group-addon btn col-1" for="testdate">
                                               <span class="fa fa-calendar"></span>
                                            </label>-->
                                        </td>
                                        <td class="pl-1 pr-0" style="width:120px;">
                                            <input type="text" class="form-control custom-control-inline enterseq datepicker" style="margin:0 !important;" disabled="disabled" seq="10" name="service_date_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->service_date:'')}}" maxlength="10"/>
                                            <!--<label class="input-group-addon btn col-1" for="testdate">
                                               <span class="fa fa-calendar"></span>
                                            </label>-->
                                        </td>
                                        <td class="pl-1 pr-0" style="width:80px;">
                                            <input type="number" class="form-control enterseq" style="width:75px;" disabled="disabled" seq="11" name="soft_license_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->soft_license:'')}}" maxlength="10"/>
                                        </td>
                                        <td class="pl-1 pr-0" style="width:80px;">
                                            <input type="number" class="form-control enterseq" style="width:75px;" disabled="disabled" seq="12" name="pos_license_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->pos_license:'')}}" maxlength="10"/>
                                        </td>
                                        <td class="pl-1 pr-0">
                                            <input type="text" class="form-control enterseq" disabled="disabled" seq="13" name="vpnaddress_{{$rcategory->id}}" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->vpnaddress:'')}}" maxlength="100"/>
                                        </td>
                                        <td class="pl-1 pr-0" style="text-align:center;">
                                            <div class="custom-control custom-switch">
                                                <input type="hidden" name="active_{{$rcategory->id}}" disabled="disabled" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->active:'N')}}">
                                                <input type="checkbox" class="custom-control-input enterseq" disabled="disabled" onclick="js_click(this)" seq="15" name="cactive_{{$rcategory->id}}" id="active_{{$rcategory->id}}" {{((isset($service[$rcategory->id]) && $service[$rcategory->id]->active=="Y" )?'checked="checked"':'')}}>
                                                <label class="custom-control-label enterseq" seq="14" for="active_{{$rcategory->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="pl-1 pr-0">
                            <span class="addmodify">
                              @if(isset($service[$rcategory->id]))
                                    <a href="javascript:void(0);" onclick="js_add(this);" class="btn btn-primary">Edit</a>
                                    <a href="javascript:void(0);" onclick="js_delete(this, '{{$rcategory->id}}');" class="btn btn-danger">Delete</a>
                                @else
                                    <a href="javascript:void(0);" onclick="js_add(this);" class="btn btn-primary">Add</a>
                                @endif
                            </span>
                                            <span class="action d-none" >
                              <a href="javascript:void(0);" onclick="js_save(this, '{{$rcategory->id}}');" seq="16" class="btn btn-primary enterseq">Save</a>
                              <a href="javascript:void(0);" onclick="js_cancel(this, '{{$rcategory->id}}');" class="btn btn-danger ">Cancel</a>
                            </span>
                                        </td>
                                    </tr>
                                    @if(isset($rcategory->b_rmk) && $rcategory->b_rmk=="Y")
                                        <tr class="additional">
                                            <td class="pl-1 pr-0" scope="col" style="border-top:none;"></td>
                                            <td class="pl-1 pr-0" style="width:80px; border-top:none;"></td>
                                            <td class="pl-1 pr-0" style="width:300px; border-top:none;" colspan="3">
                                                <input type="text" class="form-control enterseq" disabled="disabled" seq="2" name="rmk_{{$rcategory->id}}" placeholder="Remarks" value="{{((isset($service[$rcategory->id]) )?$service[$rcategory->id]->rmk:'')}}" maxlength="70"/>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ action('App\Http\Controllers\CustomerServicesController@index') }}?searchvalue={{((isset($input['searchvalue']))?$input['searchvalue']:'')}}&page={{((isset($input['page']))?$input['page']:'')}}" class="btn btn-secondary btn-xs">Back</a>
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
                                        <label for="system_id">System ID:</label>
                                        <input type="hidden" class="form-control" name="category_id">
                                        <input type="hidden" class="form-control" name="service_id">
                                        <input type="text" class="form-control" name="system_id" readOnly="readOnly">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="customername">Customer Name:</label>
                                        <input type="text" class="form-control" name="customername" readOnly="readOnly">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="serial_no">Serial Number: <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="serial_no">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="exp_dat">Expire Date:: <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="exp_dat" value="31/12/2100">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="agentid">Agent: <span style="color:red;">*</span>:</label>
                                        <select class="form-control agentAutoSelect overflow-ellipsis" name="agentid" placeholder="Agent search..." data-url="{{ route('customerservice.agentlist') }}" autocomplete="off"></select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="curpassword">Current password:</label>
                                        <input type="text" class="form-control" name="curpassword" readOnly="readOnly">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="newpassword">New password:</label>
                                        <input type="text" class="form-control" name="newpassword" readOnly="readOnly">
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
        var bsubmit = false;
        if ($("#serviceform").length > 0) {
            $("#serviceform").submit(function(evt){
                if(!bsubmit){
                    return false;
                }
                $("#serviceform").validate({
                    rules: {
                        groupcode: {
                            required: true,
                            maxlength: 20
                        },
                        description: {
                            required: true,
                            maxlength:200
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
                        }
                    },
                })
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
                                bsubmit=true;
                                $(this).click();
                                return false;
                            } else if($(this).is("a")) {
                                $(this).click();
                                return false;
                            }
                            var dd = parseInt($(this).attr("seq"),10)+1;
                            if( $(".enterseq").filter("[seq='"+dd+"']:not(:disabled)").length>0){
                                if($(".enterseq").filter("[seq='"+dd+"']").is("input[type='text']")) {
                                    $("input[type='text']").filter("[seq='"+dd+"']").select();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("input[type='number']")) {
                                    $("input[type='number']").filter("[seq='"+dd+"']").select();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("select")){
                                    $("select").filter("[seq='"+dd+"']").focus();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("checkbox")){
                                    $("checkbox").filter("[seq='"+dd+"']").select();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("button")){
                                    $("button").filter("[seq='"+dd+"']").focus();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("label")){
                                    $("label").filter("[seq='"+dd+"']").focus();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("a")){
                                    $("a").filter("[seq='"+dd+"']").focus();
                                }
                            } else {
                                ww=2;
                                dd =  parseInt($(this).attr("seq"),10)+ww;
                                while($(".enterseq").filter("[seq='"+dd+"']:not(:disabled)").length==0){
                                    ww++;
                                    dd =  parseInt($(this).attr("seq"),10)+ww;
                                }
                                if($(".enterseq").filter("[seq='"+dd+"']").is("input[type='text']")) {
                                    $("input[type='text']").filter("[seq='"+dd+"']").select();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("input[type='number']")) {
                                    $("input[type='number']").filter("[seq='"+dd+"']").select();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("select")){
                                    $("select").filter("[seq='"+dd+"']").focus();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("checkbox")){
                                    $("checkbox").filter("[seq='"+dd+"']").select();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("button")){
                                    $("button").filter("[seq='"+dd+"']").focus();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("label")){
                                    $("label").filter("[seq='"+dd+"']").focus();
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("a")){
                                    $("a").filter("[seq='"+dd+"']").focus();
                                }
                            }

                            if($(this).attr("name").substr(0,11)=="start_date_"){
                                js_date_format($(this));
                                if($(this).val()!=""){
                                    if($("select[name='contract_typ_"+$(this).attr("name").substr(11)+"']").val()=="1") {
                                        var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                                        var ndt = add_years(dt,1);
                                        $("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                                    } else if($("select[name='contract_typ_"+$(this).attr("name").substr(11)+"']").val()=="2") {
                                        var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                                        var ndt = add_months(dt,1);
                                        $("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                                    } else if($("select[name='contract_typ_"+$(this).attr("name").substr(11)+"']").val()=="3") {
                                        var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                                        var ndt = add_months(dt,2);
                                        $("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                                    } else if($("select[name='contract_typ_"+$(this).attr("name").substr(11)+"']").val()=="4") {
                                        var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                                        var ndt = add_months(dt,6);
                                        $("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                                    } else if($("select[name='contract_typ_"+$(this).attr("name").substr(11)+"']").val()=="5") {
                                        var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                                        var ndt = add_months(dt,3);
                                        $("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                                    }
                                }
                                if($("input[name='pay_before_"+$(this).attr("name").substr(11)+"']").val()=="Y"){
                                    //var dt = new Date($(this).val().substr(6,4),(parseInt($(this).val().substr(3,2),10)-1),$(this).val().substr(0,2) );
                                    //var ndt = add_dates(dt,1);
                                    $("input[name='service_date_"+$(this).attr("name").substr(11)+"']").val($(this).val());
                                } else {
                                    var dt = new Date($("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val().substr(6,4),(parseInt($("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val().substr(3,2),10)-1),$("input[name='end_date_"+$(this).attr("name").substr(11)+"']").val().substr(0,2));
                                    var ndt = add_dates(dt,1);
                                    $("input[name='service_date_"+$(this).attr("name").substr(11)+"']").val(ndt.getDate().toString().padStart(2,"0")+"/"+(ndt.getMonth()+1).toString().padStart(2,"0")+"/"+ndt.getFullYear());
                                }
                            } else if($(this).attr("name").substr(0,9)=="end_date_" || $(this).attr("name").substr(0,13)=="service_date_"){
                                js_date_format($(this));
                            }
                            return false;
                            break;
                        case 38:
                            var ded = ($(this).is("input[type='checkbox']"))?2:1;
                            var dd = (parseInt($(this).attr("seq"),10)>0)?(parseInt($(this).attr("seq"),10)-ded):parseInt($(this).attr("seq"),10);

                            while(dd>0){
                                if($("input[type='text']").filter("[seq='"+dd+"']:not(:disabled)").length>0){
                                    $("input[type='text']").filter("[seq='"+dd+"']:not(:disabled)").select();
                                    dd=0;
                                } else if($("select").filter("[seq='"+dd+"']:not(:disabled)").length>0){
                                    $("select").filter("[seq='"+dd+"']:not(:disabled)").focus();
                                    dd=0;
                                } else if($(".enterseq").filter("[seq='"+dd+"']:not(:disabled)").is("label")){
                                    $("label").filter("[seq='"+dd+"']:not(:disabled)").focus();
                                    dd=0;
                                }
                                dd--;
                            }
                    }
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

            $("input[name='fileupload']").bind("change",function(evt){
                var formd = new FormData();
                formd.append("_token", $("input[name='_token']").val());
                formd.append("hidAction", "uploadcfg");
                formd.append("catg", $("input[name='system_id']").val());
                formd.append("id", $("input[name='companycode']").val());
                formd.append("compnam", $("input[name='companyname']").val());
                formd.append("cfg_file",$(this).get(0).files[0]);
                $.ajax({
                    url: "{{action('App\Http\Controllers\CustomerServicesController@store')}}",
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
                data +="&service_id="+encodeURIComponent($("input[name='service_id']").val());
                data +="&category_id="+encodeURIComponent($("input[name='category_id']").val());
                data +="&companycode="+encodeURIComponent($("input[name='companycode']").val());
                data +="&system_id="+encodeURIComponent($("input[name='system_id']").val());
                data +="&serial_no="+encodeURIComponent($("input[name='serial_no']").val());
                data +="&exp_dat="+encodeURIComponent($("input[name='exp_dat']").val());
                data +="&agentid="+encodeURIComponent($("input[name='agentid']").val());
                data +="&curpassword="+encodeURIComponent($("input[name='curpassword']").val());
                data +="&newpassword="+encodeURIComponent($("input[name='newpassword']").val());
                data +="&companyname="+encodeURIComponent($("input[name='companyname']").val());
                data +="&soft_license="+encodeURIComponent($("input[name='soft_license_"+$("input[name='category_id']").val()+"']").val());
                if($("input[name='serial_no']").val()==""){
                    $("input[name='serial_no']").select();
                    return false;
                }
                if($("input[name='exp_dat']").val()==""){
                    $("input[name='exp_dat']").select();
                    return false;
                }
                if($("input[name='agentid']").val()==""){
                    $(".agentAutoSelect").focus();
                    return false;
                }
                $.ajax({
                    url:"{{action('App\Http\Controllers\CustomerServicesController@store')}}",
                    type:'post',
                    dataType: 'json',
                    data:data,
                    beforeSend: function(){
                        $('#modalLoading').modal('show');
                    },
                    success: function(json){
                        setTimeout(function(){ $("#modalLoading .close").click(); },100);
                        alert(json.msg);
                        $("input[name='curpassword']").val(json.newpassword);
                        $("input[name='newpassword']").val(json.newpassword);
                        if(json.cfgfile!=undefined){
                            js_openfile("{{ url('/') }}"+json.cfgfile);
                        }
                        $("span#cfgpf_"+$("input[name='service_id']").val()).html(json.newpassword+"<br><a href=\"javascript:void(0);\" onclick=\"js_openfile('{{ url('/') }}"+json.cfgfile+"');\" class=\"btn btn-primary\">CFG</a><br><a href=\"javascript:void(0);\" onclick=\"js_remove_cfg('"+$("input[name='service_id']").val()+"','"+json.lastrunno+"','"+json.category_id+"')\" class=\"btn btn-danger\">Remove</a>");
                        $("input[name='newpassword']").prop("readOnly",false);
                        $("input[name='serial_no']").prop("readOnly",true);
                        $("input[name='exp_dat']").prop("readOnly",true);
                        $(".agentAutoSelect").prop("readOnly",true);
                        $("input[name='agentid']").prop("readOnly",true);
                        $("input[name='newpassword']").select();
                        $("a#serialclick_"+$("input[name='service_id']").val()).attr("onclick","");
                        $("a#serialclick_"+$("input[name='service_id']").val()).click(function(){
                            js_serial_act(this,$("input[name='service_id']").val(),$("input[name='serial_no']").val(),$("input[name='exp_dat']").val(),$("input[name='agentid']").val(),$(".agentAutoSelect").val(),$("input[name='newpassword']").val(),'',$("input[name='category_id']").val());
                            return false;
                        });
                        return false;
                    }
                });
            })
            /*$("input.datepicker").datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy'
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
                var e = jQuery.Event("keydown");
                e.which = 13; // # Some key code value
                $(this).trigger(e);
            });*/
        })

        function js_downloadpkpb_qr(serviceid) {
            data = "_token="+$("input[name='_token']").val();
            data +="&hidAction=dwpkpbqr";
            data +="&service_id="+serviceid;
            $.ajax({
                url:"{{action('App\Http\Controllers\CustomerServicesController@store')}}",
                type:'post',
                dataType: 'json',
                data:data,
                beforeSend: function(){
                    $('#modalLoading').modal('show');
                },
                success: function(json){
                    setTimeout(function(){ $("#modalLoading .close").click(); },500);
                    window.open("{{ url('/') }}"+json.qrcode,'qrcodefile');
                    return false;
                }
            });
        }
        function js_remove_cfg(service_id,lastrunno,catgid){
            data = "_token="+$("input[name='_token']").val();
            data +="&hidAction=deletecfg";
            data +="&service_id="+service_id;
            $.ajax({
                url:"{{action('App\Http\Controllers\CustomerServicesController@store')}}",
                type:'post',
                dataType: 'json',
                data:data,
                beforeSend: function(){
                    $('#modalLoading').modal('show');
                },
                success: function(json){
                    setTimeout(function(){ $("#modalLoading .close").click(); },100);
                    alert(json.msg);
                    $("span#cfgpf_"+service_id).html('');
                    $("a#serialclick_"+service_id).attr("onclick","");
                    $("a#serialclick_"+service_id).click(function(evt){
                        js_serial_act(this,service_id,"","","","","",lastrunno,catgid);
                        return false;
                    });
                    return false;
                }
            });
        }
        function js_openfile(file){
            window.open(file+"?"+Date.now(),'downloadfile');
        }

        function js_add(obj){
            $(obj).closest("tr").find("input").attr( "disabled", false );
            $(obj).closest("tr").find("label").attr( "disabled", false );
            $(obj).closest("tr").find("select").attr( "disabled", false );
            $(obj).closest("tr").find(".enterseq").filter("[seq='1']").focus();
            $(obj).closest("span").hide();
            $(obj).closest("td").find("span.action").removeClass("d-none");
            if($(obj).closest("tr").next().attr("class")=="additional"){
                $(obj).closest("tr").next().find("input").attr( "disabled", false );
            }
        }
        function js_click(obj){
            if($(obj).prop("checked")){
                $(obj).closest("td").find("input[type='hidden']").val("Y");
            } else {
                $(obj).closest("td").find("input[type='hidden']").val("N");
            }
        }
        function js_cancel(obj,categoryid){
            $(obj).closest("tr").find("input").attr( "disabled", true );
            $(obj).closest("tr").find("label").attr( "disabled", true );
            $(obj).closest("tr").find("select").attr( "disabled", true );
            $(obj).closest("span").addClass("d-none");
            $(obj).closest("td").find("span.addmodify").show();
            if($(obj).closest("tr").find("input[name='amount_"+categoryid+"']").length>0) {
                $(obj).closest("tr").find("input[name='amount_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepamount_"+categoryid+"']").val());
            }
            /*if($(obj).closest("tr").find("input[name='rmk_"+categoryid+"']").length>0) {
              $(obj).closest("tr").find("input[name='rmk_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keeprmk_"+categoryid+"']").val());
            }*/
            if($(obj).closest("tr").next().attr("class")=="additional" && $(obj).closest("tr").next().find("input[name='rmk_"+categoryid+"']").length>0){
                $(obj).closest("tr").next().find("input[name='rmk_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keeprmk_"+categoryid+"']").val());
            }
            $(obj).closest("tr").find("select[name='contract_typ_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepcontract_typ_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='inc_hw_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepinc_hw_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='pay_before_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keeppay_before_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='start_date_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepstart_date_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='end_date_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepend_date_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='service_date_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepservice_date_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='soft_license_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepsoft_license_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='pos_license_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keeppos_license_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='vpnaddress_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keevpnaddress_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='active_"+categoryid+"']").val($(obj).closest("tr").find("input[name='keepactive_"+categoryid+"']").val());
            if($(obj).closest("tr").find("input[name='keepinc_hw_"+categoryid+"']").val()=="Y"){
                $(obj).closest("tr").find("#inc_hw_"+categoryid).prop("checked",true);
            } else {
                $(obj).closest("tr").find("#inc_hw_"+categoryid).prop("checked",false);
            }
            if($(obj).closest("tr").find("input[name='keeppay_before_"+categoryid+"']").val()=="Y"){
                $(obj).closest("tr").find("#pay_before_"+categoryid).prop("checked",true);
            } else {
                $(obj).closest("tr").find("#pay_before_"+categoryid).prop("checked",false);
            }
            if($(obj).closest("tr").find("input[name='keepactive_"+categoryid+"']").val()=="Y"){
                $(obj).closest("tr").find("#active_"+categoryid).prop("checked",true);
            } else {
                $(obj).closest("tr").find("#active_"+categoryid).prop("checked",false);
            }
            if($(obj).closest("tr").next().attr("class")=="additional"){
                $(obj).closest("tr").next().find("input").attr( "disabled", true );
            }
        }
        function js_save(obj, categoryid){
            serviceid=$(obj).closest("tr").find("input[name='keepserviceid_"+categoryid+"']").val();
            data = "_token="+$("input[name='_token']").val();
            if($(obj).closest("tr").find("input[name='amount_"+categoryid+"']").length>0) {
                data +="&amount="+$(obj).closest("tr").find("input[name='amount_"+categoryid+"']").val();
            }
            if($(obj).closest("tr").next().attr("class")=="additional" && $(obj).closest("tr").next().find("input[name='rmk_"+categoryid+"']").length>0){
                data +="&rmk="+$(obj).closest("tr").next().find("input[name='rmk_"+categoryid+"']").val();
            }
            data +="&contract_typ="+$(obj).closest("tr").find("select[name='contract_typ_"+categoryid+"']").val();
            data +="&inc_hw="+$(obj).closest("tr").find("input[name='inc_hw_"+categoryid+"']").val();
            data +="&pay_before="+$(obj).closest("tr").find("input[name='pay_before_"+categoryid+"']").val();
            data +="&start_date="+$(obj).closest("tr").find("input[name='start_date_"+categoryid+"']").val();
            data +="&end_date="+$(obj).closest("tr").find("input[name='end_date_"+categoryid+"']").val();
            data +="&service_date="+$(obj).closest("tr").find("input[name='service_date_"+categoryid+"']").val();
            data +="&soft_license="+$(obj).closest("tr").find("input[name='soft_license_"+categoryid+"']").val();
            data +="&pos_license="+$(obj).closest("tr").find("input[name='pos_license_"+categoryid+"']").val();
            data +="&vpnaddress="+$(obj).closest("tr").find("input[name='vpnaddress_"+categoryid+"']").val();
            data +="&active="+$(obj).closest("tr").find("input[name='active_"+categoryid+"']").val();
            data += "&customerid="+$("input[name='customerid']").val();
            data += "&categoryid="+categoryid;
            if(serviceid==""){
                $.ajax({
                    url:"{{action('App\Http\Controllers\CustomerServicesController@store')}}",
                    type:'post',
                    dataType: 'json',
                    data:data,
                    beforeSend: function(){
                        $('#modalLoading').modal('show');
                    },
                    success: function(json){
                        setTimeout(function(){ $("#modalLoading .close").click(); alert(json.msg); },500);

                        $(obj).closest("tr").find("input[name='keepserviceid_"+categoryid+"']").val(json.id);
                        $(obj).closest("tr").find("input").attr( "disabled", true );
                        $(obj).closest("tr").find("label").attr( "disabled", true );
                        $(obj).closest("tr").find("select").attr( "disabled", true );
                        $(obj).closest("span").addClass("d-none");
                        $(obj).closest("td").find("span.addmodify").show();
                        $(obj).closest("td").find("span.addmodify a").html('Edit');
                        $(obj).closest("td").find("span.addmodify").append('<a href="javascript:void(0);" onclick="js_delete(this, \''+categoryid+'\');" class="btn btn-danger">Delete</a>');
                        if($(obj).closest("tr").next().attr("class")=="additional"){
                            $(obj).closest("tr").next().find("input").attr( "disabled", true );
                        }
                        js_keep_value(obj,categoryid);
                        window.location.reload();
                        return false;
                    }
                });
            } else {
                data+="&_method=PUT";
                $.ajax({
                    url:"{{action('App\Http\Controllers\CustomerServicesController@update','')}}/"+serviceid,
                    type:'put',
                    dataType: 'json',
                    data:data,
                    beforeSend: function(){
                        $('#modalLoading').modal('show');
                    },
                    success: function(json){
                        setTimeout(function(){ $("#modalLoading .close").click(); alert(json.msg); },500);

                        $(obj).closest("tr").find("input").attr( "disabled", true );
                        $(obj).closest("tr").find("label").attr( "disabled", true );
                        $(obj).closest("tr").find("select").attr( "disabled", true );
                        if($(obj).closest("tr").next().attr("class")=="additional"){
                            $(obj).closest("tr").next().find("input").attr( "disabled", true );
                        }
                        $(obj).closest("span").addClass("d-none");
                        $(obj).closest("td").find("span.addmodify").show();
                        js_keep_value(obj,categoryid);
                        return false;
                    }
                });
            }
        }
        function js_delete(obj,categoryid){
            var serviceid=$(obj).closest("tr").find("input[name='keepserviceid_"+categoryid+"']").val();
            var data = "_token="+$("input[name='_token']").val();
            data+="&_method=DELETE";
            $.ajax({
                url:"{{action('App\Http\Controllers\CustomerServicesController@destroy','')}}/"+serviceid,
                type:'delete',
                dataType: 'json',
                data:data,
                beforeSend: function(){
                    $('#modalLoading').modal('show');
                },
                success: function(json){
                    setTimeout(function(){ $("#modalLoading .close").click(); },100);
                    alert(json.msg);
                    $(obj).closest("tr").find("input").val('');
                    $(obj).closest("tr").find("select").val('');
                    $(obj).closest("tr").find("#inc_hw_"+categoryid).prop("checked",false);
                    $(obj).closest("tr").find("#pay_before_"+categoryid).prop("checked",false);
                    $(obj).closest("tr").find("#active_"+categoryid).prop("checked",false);
                    $(obj).closest("tr").find("input").attr( "disabled", true );
                    $(obj).closest("tr").find("label").attr( "disabled", true );
                    $(obj).closest("tr").find("select").attr( "disabled", true );
                    if($(obj).closest("tr").next().attr("class")=="additional"){
                        $(obj).closest("tr").next().find("input").attr( "disabled", false );
                    }
                    $(obj).closest("td").find("span.addmodify a.btn-primary").html('Add');
                    $(obj).closest("td").find("span.addmodify a.btn-danger").remove();
                    $(obj).closest("td").find("span.addmodify").show();
                    return false;
                }
            });
        }
        function js_keep_value(obj,categoryid){
            if($(obj).closest("tr").find("input[name='amount_"+categoryid+"']").length>0){
                $(obj).closest("tr").find("input[name='keepamount_"+categoryid+"']").val($(obj).closest("tr").find("input[name='amount_"+categoryid+"']").val());
            }
            /*if($(obj).closest("tr").find("input[name='rmk_"+categoryid+"']").length>0){
              $(obj).closest("tr").find("input[name='keeprmk_"+categoryid+"']").val($(obj).closest("tr").find("input[name='rmk_"+categoryid+"']").val());
            }*/
            if($(obj).closest("tr").next().attr("class")=="additional" && $(obj).closest("tr").next().find("input[name='rmk_"+categoryid+"']").length>0){
                $(obj).closest("tr").find("input[name='keeprmk_"+categoryid+"']").val($(obj).closest("tr").next().find("input[name='rmk_"+categoryid+"']").val());
            }
            $(obj).closest("tr").find("input[name='keepcontract_typ_"+categoryid+"']").val($(obj).closest("tr").find("select[name='contract_typ_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepinc_hw_"+categoryid+"']").val($(obj).closest("tr").find("input[name='inc_hw_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keeppay_before_"+categoryid+"']").val($(obj).closest("tr").find("input[name='pay_before_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepstart_date_"+categoryid+"']").val($(obj).closest("tr").find("input[name='start_date_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepend_date_"+categoryid+"']").val($(obj).closest("tr").find("input[name='end_date_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepservice_date_"+categoryid+"']").val($(obj).closest("tr").find("input[name='service_date_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepsoft_license_"+categoryid+"']").val($(obj).closest("tr").find("input[name='soft_license_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keeppos_license_"+categoryid+"']").val($(obj).closest("tr").find("input[name='pos_license_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepvpnaddress_"+categoryid+"']").val($(obj).closest("tr").find("input[name='vpnaddress_"+categoryid+"']").val());
            $(obj).closest("tr").find("input[name='keepactive_"+categoryid+"']").val($(obj).closest("tr").find("input[name='active_"+categoryid+"']").val());
        }
        function js_serial_act(obj, serviceid, serial_no, exp_dat, agentid, agentname, curpassword, lastrunno, category_id){
            $("input[name='customername']").val($("input[name='companyname']").val());
            $("input[name='system_id']").val($(obj).html());
            $("input[name='service_id']").val(serviceid);
            $("input[name='category_id']").val(category_id);
            $("#serializationModal").modal("show");
            if(serial_no!="") {
                $("input[name='serial_no']").val(serial_no);
                $("input[name='exp_dat']").val(exp_dat);
                $("input[name='agentid']").val(agentid);
                $(".agentAutoSelect").val(agentname);
                $("input[name='serial_no']").prop("readOnly",true);
                $("input[name='exp_dat']").prop("readOnly",true);
                $("input[name='agentid']").prop("readOnly",true);
                $(".agentAutoSelect").prop("readOnly",true);
                $("input[name='curpassword']").val(curpassword);
                $("input[name='newpassword']").val(curpassword);
                $("input[name='newpassword']").prop("readOnly",false);
                setTimeout(function(){ $("input[name='newpassword']").select(); },1000);
            } else {
                $("input[name='serial_no']").val(parseInt(lastrunno,10)+1);
                $("input[name='serial_no']").prop("readOnly",false);
                $("input[name='exp_dat']").prop("readOnly",false);
                $("input[name='agentid']").prop("readOnly",false);
                $(".agentAutoSelect").prop("readOnly",false);
                $("input[name='newpassword']").prop("readOnly",true);
                $("input[name='curpassword']").val('');
                $("input[name='newpassword']").val('');
                $("input[name='agentid']").val('');
                $(".agentAutoSelect").val('');
                setTimeout(function(){ $("input[name='serial_no']").select(); },1000);
            }
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
        /*******************************************************************************************/
        // JS Date Format
        /*******************************************************************************************/
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
    </script>
@endsection
