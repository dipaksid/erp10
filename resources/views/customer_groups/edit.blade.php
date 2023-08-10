@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="container">
            <!-- Page Heading START -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Customer Group</h1>
            </div>
            <!-- Page Heading END -->
            @include('partials/messages')

            <form id="groupform" method="post" action="{{ action('App\Http\Controllers\CustomerGroupsController@update', $customer_group->id) }}" >
                {{csrf_field()}}
                <input name="_method" type="hidden" value="PATCH">
                <input name="lastrunno" type="hidden" value="{{(($customer_group->category)?$customer_group->category->lastrunno:"")}}">
                <input name="categorycode" type="hidden" value="{{(($customer_group->category)?$customer_group->category->categorycode:"")}}">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="groupcode">Group Code:</label>
                        <input type="text" seq="1" class="form-control enterseq" name="groupcode" value="{{$customer_group->groupcode}}" maxlength="20" />
                        <span class="text-danger">{{ $errors->first('groupcode') }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="title">Description:</label>
                        <input type="text" seq="2" class="form-control enterseq" name="description" value="{{$customer_group->description}}" maxlength="200" />
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
                                    <option value="{{$rcatg['id']}}" {{ (($rcatg['id']==$customer_group['customer_categories_id'])?"selected":"") }}>{{$rcatg['description']}}</option>
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
                                    <option value="{{$rcompany['id']}}" {{ (($rcompany['id']==$customer_group['companyid'])?"selected":"") }}>{{$rcompany['companycode']." - ".$rcompany['companyname']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="companyid">Service Form Folder Name: :</label>
                        <input type="text" seq="5" class="form-control enterseq" name="foldername" value="{{$customer_group->foldername}}" maxlength="200" />
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-4">
                        <label for="title">Customers:</label>
                        <select class="form-control customerAutoSelect enterseq overflow-ellipsis" seq="6" name="customerid"
                                placeholder="Customer search"
                                data-url="{{ action('App\Http\Controllers\CustomerGroupsController@customerList') }}"></select>
                    </div>
                    <div class="col-4">
                        <br>
                        <label for="title"><span class="cfgpass">
                    @if($customer_group->cfgpassword!="")
                                    <a href="javascript:void(0);" onclick="js_openfile('{{ url('/').$customer_group->cfgfile }}');">{{$customer_group->cfgpassword}}</a>
                                    <a href="javascript:void(0);" onclick="js_remove_cfg('{{$customer_group->id}}')" class="btn btn-danger">Remove</a>
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
                                    <input type="text" class="form-control customerAutoSelect2"  id="autocomplete-input" placeholder="Search for a customer...">

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
                                            @if($rdet->customerservices($customer_group->categoryid)->exists())
                                                <td><a href="javascript:void(0);" onclick="js_edit_service('{{(($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->id:"")}}')">{{ (($rdet->customer->exists())?$rdet->customer->companycode."-".$rdet->customer->companyname:"") }}</a></td>
                                            @else
                                                <td>{{ (($rdet->customer->exists())?$rdet->customer->companycode."-".$rdet->customer->companyname:"") }}</td>
                                            @endif
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?(($rdet->customerservices($customer_group->categoryid)->first()->contract_typ==1)?"Yearly":(($rdet->customerservices($customer_group->categoryid)->first()->contract_typ==2)?"Monthly":(($rdet->customerservices($customer_group->categoryid)->first()->contract_typ==3)?"Bi-Monthly":(($rdet->customerservices($customer_group->categoryid)->first()->contract_typ==4)?"Half Yearly":(($rdet->customerservices($customer_group->categoryid)->first()->contract_typ==5)?"Quarterly":""))))):"") }}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->amount:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->inc_hw:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->pay_before:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->start_date:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->end_date:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->service_date:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->soft_license:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->vpnaddress:"")}}</td>
                                            <td>{{ (($rdet->customerservices($customer_group->categoryid)->exists())?$rdet->customerservices($customer_group->categoryid)->first()->active:"")}}</td>
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
                <a href="{{ action('App\Http\Controllers\CustomerGroupsController@index') }}?searchvalue={{((isset($input['searchvalue']))?$input['searchvalue']:'')}}&page={{((isset($input['page']))?$input['page']:'')}}" class="btn btn-secondary btn-xs">Back</a> <button type="submit" seq="7" class="btn btn-primary enterseq">Update</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        // var $j = jQuery.noConflict();
        // $j(document).ready(function() {
        //     // $("#autocomplete-input").autocomplete({
        //     //     source: ["Apple", "Banana", "Baan", "Cherry", "Date", "Fig", "Grape", "Lemon"],
        //     //     minLength: 2 // Minimum characters before autocomplete suggestions appear
        //     // });
        //     $j("#autocomplete-input").autocomplete({
        //         source: function(request, response) {
        //             $j.ajax({
        //                 url: "/customer-groups/customerlist",
        //                 dataType: "json",
        //                 data: {
        //                     term: request.term
        //                 },
        //                 success: function(data) {
        //                     response(data);
        //                 }
        //             });
        //         },
        //         minLength: 2 // Minimum characters before autocomplete suggestions appear
        //     });
        // });
        var $j = jQuery.noConflict();
        $j(document).ready(function() {
            // $("#autocomplete-input").autocomplete({
            //     source: ["Apple", "Banana", "Baan", "Cherry", "Date", "Fig", "Grape", "Lemon"],
            //     minLength: 2 // Minimum characters before autocomplete suggestions appear
            // });
            $j('#autocomplete-input').autocomplete({
                source: "{{ route('customer-groups.customerlist') }}",
                display: ['name'], // Adjust this based on your data structure
                templates: {
                    suggestion: function (suggestion) {
                        return suggestion.name; // Adjust this based on your data structure
                    }
                },
                minLength:2,
                events: {
                    searchPost: function (resultFromServer) {
                        setTimeout(function(){
                            if(!$('#autocomplete-input').next().find('a').eq(0).hasClass("disabled")){
                                $('#autocomplete-input').next().find('a').eq(0).addClass("active");
                            }
                        },100)
                    return resultFromServer;
                }
            }

            });
            // $j("#autocomplete-input").autocomplete({
            //     source: function(request, response) {
            //         $j.ajax({
            //             url: "/customer-groups/customerlist",
            //             dataType: "json",
            //             data: {
            //                 term: request.term
            //             },
            //             success: function(data) {
            //                 response(data);
            //             }
            //         });
            //     },
            //     minLength: 2 // Minimum characters before autocomplete suggestions appear
            // });
        });
    </script>
@endsection
