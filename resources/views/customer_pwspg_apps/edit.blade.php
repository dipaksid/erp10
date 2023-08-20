@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="container">

            <!-- Page Heading Start -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">PWS PG APP Services</h1>
            </div>
            <!-- Page Heading End -->

            @include('partials/messages')

            <form id="pwspgappform" method="post" action="{{action('App\Http\Controllers\CustomerPGAppsController@update', $id)}}" >
                @csrf
                @method('PATCH')

                <input name="userid" type="hidden" value="{{$id}}">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="username">User ID:</label>
                        <input type="text" seq="1" class="form-control enterseq" id="username" name="username" maxlength="30" value="{{$customerpwspgapp->username}}" onkeypress="return js_validate_alphanumeric(event);"/>
                        <span class="text-danger">{{ $errors->first('username') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="password">Password:</label>
                        <input type="text" seq="2" class="form-control enterseq" id="password" name="password" value="{{$customerpwspgapp->password}}" maxlength="30"/>
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="mob_pho">Mobile Phone No:</label>
                        <input type="text" seq="3" class="form-control enterseq" id="mob_pho" name="mob_pho" value="{{$customerpwspgapp->mob_pho}}" maxlength="20"/>
                        <span class="text-danger">{{ $errors->first('mob_pho') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="first_name">Name:</label>
                        <input type="text" seq="4" class="form-control enterseq" id="first_name" name="first_name" value="{{$customerpwspgapp->first_name}}" maxlength="30"/>
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="idle_tim">Idle Minutes:</label>
                        <input type="text" seq="5" class="form-control enterseq" id="idle_tim" name="idle_tim" value="{{$customerpwspgapp->idle_tim}}" maxlength="5"/>
                        <span class="text-danger">{{ $errors->first('idle_tim') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="access_pdf">Display PDF [Y/N]:</label>
                        <input type="text" seq="6" class="form-control enterseq" id="access_pdf" name="access_pdf" value="{{$customerpwspgapp->access_pdf}}" maxlength="1"/>
                        <span class="text-danger">{{ $errors->first('access_pdf') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="email">Email:</label>
                        <input type="text" seq="7" class="form-control enterseq" id="email" name="email" value="{{$customerpwspgapp->email}}" maxlength="60"/>
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="title">Customers:</label>
                        <select class="form-control customerAutoSelect enterseq overflow-ellipsis" seq="8" name="customerid"
                                placeholder="Customer search"
                                autocomplete="off">
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="title">API URL:</label>
                        <div class="form-group row">
                            <label for="tapiurl" class="col-sm-3 col-form-label">http://<span id="vpnaddress"></span>/</label>
                            <div class="col-sm-6">
                                <input type="text" seq="9" class="form-control enterseq" id="tapiurl" name="tapiurl" value="pws" maxlength="100">
                            </div>
                            <label for="tapiurl" class="col-sm-3 col-form-label">/pgapp</label>
                        </div>

                    </div>
                    <div class="col-1">
                        <label for="title">&nbsp;</label>
                        <div class="form-group row">
                            <a class="btn" href="javascript:void(0);" onclick="js_add_customer($('input[name=\'customerid\']').val(),$('.customerAutoSelect').val())">
                                <i class="fas fa-fw fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-12">
                        <table class="table" id="tblcust">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th>Customer</th>
                                <th>API URL</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $arrpgappid=explode(",",$customerpwspgapp->pgappid);
                                $arrcustomerid=explode(",",$customerpwspgapp->customerid);
                                $arrcustomer=explode(",",$customerpwspgapp->customer);
                                $arrapiurl=explode(",",$customerpwspgapp->apiurl);
                            @endphp
                            @if($arrpgappid)
                                @foreach($arrpgappid as $lkey=> $pgappid)
                                    <tr>
                                        <td scope="row"><input type='hidden' name='pgappid[]' value='{{$pgappid}}'><input type='hidden' name='cust[]' value='{{$arrcustomerid[$lkey]}}'><span>{{($lkey+1)}}</span></td>
                                        <td>{{$arrcustomer[$lkey]}}</td>
                                        <td><input type='hidden' name='apiurl[]' value='{{$arrapiurl[$lkey]}}'>{{$arrapiurl[$lkey]}}</td>
                                        <td class="text-center col-2">
                                            <button class="btn btn-danger" type="button" onclick="js_delete(this);">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="empty">
                                    <td class="text-center" colspan="4">No Record Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ action('App\Http\Controllers\CustomerPGAppsController@index') }}?searchvalue={{((isset($input['searchvalue']))?$input['searchvalue']:'')}}&page={{((isset($input['page']))?$input['page']:'')}}" class="btn btn-secondary btn-xs">Back</a>
                <button type="submit" seq="10" class="btn btn-primary enterseq">Update</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script>
    <script type="text/javascript">
        var bsubmit = false;
        if ($("#pwspgappform").length > 0) {
            $("#pwspgappform").validate({
                rules: {
                    username: {
                        required: true,
                        maxlength: 30
                    },
                    first_name: {
                        required: true,
                        maxlength:100
                    },
                    password: {
                        required: true,
                        maxlength:30
                    },
                    mob_pho: {
                        required: true,
                        maxlength:20
                    }
                },
                messages: {
                    username: {
                        required: "Please enter group code",
                        maxlength: "Your group code maxlength should be 30 characters long."
                    },
                    first_name: {
                        required: "Please enter description",
                        maxlength: "The description should be 60 characters long"
                    },
                    password: {
                        required: "Please enter password",
                        maxlength:"The password should be 30 characters long"
                    },
                    mob_pho: {
                        required: "Please enter mobile phone no",
                        maxlength:"The mobile phone no should be 20 characters long"
                    }
                },
            })
            $("#pwspgappform").submit(function(evt){
                if($("input[name='cust[]']").length==0) {
                    alert("Customer are compulsory!");
                    $(".customerAutoSelect").focus();
                    return false;
                }
            })
        }
        $(document).ready(function(evt){
            $("#username").blur(function(evt){
                if($(this).val().length>0){
                    for(var ss=0; ss<$(this).val().length; ss++){
                        if(!js_valid_alpha($(this).val()[ss])){
                            $(this).val('{{$customerpwspgapp->username}}');
                            $(this).select();
                            return false;
                        }
                    }
                }
                return false;
            })
            $(".enterseq").each(function(i){
                $(this).keydown(function(event){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    switch(keycode) {
                        case 13:
                            if($(this).is("input") && $(this).attr("name")=="username"){
                                for(var ss=0; ss<$(this).val().length; ss++){
                                    if(!js_valid_alpha($(this).val()[ss])){
                                        $(this).val('');
                                        $(this).select();
                                        return false;
                                    }
                                }
                                $(this).val($(this).val().toUpperCase());
                            } else if($(this).attr("name")=="tapiurl"){
                                js_add_customer($("input[name='customerid']").val(),$('.customerAutoSelect').val());
                                $("#vpnaddress").html('')
                                return false;
                            } else if($(this).is("input") && $(this).attr("name")!="password") {
                                $(this).val($(this).val().toUpperCase());
                            } else if($(this).is("button[type='submit']")) {
                                $(this).click();
                                return false;
                            }
                            if($(this).attr("name")=="first_name"){
                                $(".customerAutoSelect").select();
                            } else {
                                var dd = parseInt($(this).attr("seq"),10)+1;
                                if( $(".enterseq").filter("[seq='"+dd+"']").length>0){
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
                            }
                            return false;
                            break;
                        case 38:
                            var ded = ($(this).is("input[type='checkbox']"))?2:1;
                            var dd = (parseInt($(this).attr("seq"),10)>0)?(parseInt($(this).attr("seq"),10)-ded):parseInt($(this).attr("seq"),10);

                            while(dd>0){
                                if($("input[type='text']").filter("[seq='"+dd+"']").length>0){
                                    $("input[type='text']").filter("[seq='"+dd+"']").select();
                                    dd=0;
                                } else if($("select").filter("[seq='"+dd+"']").length>0){
                                    $("select").filter("[seq='"+dd+"']").focus();
                                    dd=0;
                                } else if($(".enterseq").filter("[seq='"+dd+"']").is("label")){
                                    $("label").filter("[seq='"+dd+"']").focus();
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
                    return false;
                }
            })
            $('.customerAutoSelect').on('change', function (e, datum) {
                setTimeout(function(){
                    $('#tapiurl').select();
                },300);
                return false;
            });
            $('.customerAutoSelect').on('autocomplete.select', function (e, datum) {
                $('.customerAutoSelect').parent().find("div.dropdown-menu").empty();
                $("#vpnaddress").html(datum.vpnaddress);
                $(this).change();
                return false;
            })
        })

        function js_add_customer(id,name){

            var apiurl = "http://"+$("#vpnaddress").html()+"/"+$("input[name='tapiurl']").val()+"/pgapp";
            if(name!="" && id!=""){
                if( $("table#tblcust tbody tr.empty").length>0){
                    $("table#tblcust tbody tr.empty").remove();
                }
                var bcheck=false;
                $("input[name='cust[]']").each(function(i){
                    if($(this).val()==id){
                        bcheck=true;
                    }
                })
                if(!bcheck) {
                    var ncount=$("table#tblcust tbody tr").length;
                    var trrow="<tr>";
                    trrow+="<td scope=\"row\"><input type='hidden' name='cust[]' value='"+id+"'><span>"+(ncount+1)+"</span></td>";
                    trrow+="<td>"+name+"</td>";
                    trrow+="<td><input type='hidden' name='apiurl[]' value='"+apiurl+"'>"+apiurl+"</td>";
                    trrow+="<td class=\"text-center col-2\">";
                    trrow+="<button class=\"btn btn-danger\" type=\"button\" onclick=\"js_delete(this);\">Delete</button>";
                    trrow+="</td>";
                    trrow+="</tr>";
                    $("table#tblcust tbody").append(trrow);
                } else {
                    alert("Duplicated!");
                }
                $("#vpnaddress").html('');
                $(".customerAutoSelect").val('');
                $("input[name='customerid']").val('');
                $("#tapiurl").val("pws");
                $(".customerAutoSelect").focus();
            }
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
                trrow += "<td class=\"text-center\" colspan=\"4\">No Record Found</td>";
                trrow += "</tr>";
                $("table#tblcust tbody").append(trrow);
            }
            return false;
        }

        function js_validate_alphanumeric(e) {
            var keyChar = String.fromCharCode(e.which || e.keyCode);
            return js_valid_alpha(keyChar);
        }

        function js_valid_alpha(charc) {
            var alpha = /[A-Za-z0-9]/;
            return alpha.test(charc) ? charc : false;
        }
    </script>
@endsection
