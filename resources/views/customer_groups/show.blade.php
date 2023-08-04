@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="container">
            <!-- Page Heading Start-->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Customer customerGroup</h1>
            </div>
            <!-- Page Heading End-->

            @include('partials/messages')

            <form method="post">
                <div class="row form-customerGroup">
                    <div class="col-3">
                        <label for="title">customerGroup Code:</label>
                        <input type="text" class="form-control" name="groupcode" value="{{ $customerGroup->groupcode }}" disabled />
                    </div>
                </div>
                <div class="row form-customerGroup">
                    <div class="col-9">
                        <label for="title">Description:</label>
                        <input type="text" class="form-control" name="description" value="{{ $customerGroup->description }}" disabled />
                    </div>
                </div>
                <div class="row form-customerGroup">
                    <div class="col-6">
                        <label for="system_id">System ID: <span style="color:red;">*</span>:</label>
                        <select class="form-control enterseq" seq="3" name="category_id" disabled>
                            <option value=""> -- Selection --</option>
                            @if($categorylist)
                                @foreach ($categorylist as $rcatg)
                                    <option value="{{$rcatg['id']}}" {{ (($rcatg['id']==$customerGroup['customer_categories_id'])?"selected":"") }}>{{$rcatg['description']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row form-customerGroup">
                    <div class="col-6">
                        <label for="companyid">Service Form Folder Name: :</label>
                        <input type="text" seq="5" class="form-control enterseq" name="foldername" value="{{$customerGroup->foldername}}" disabled />
                    </div>
                </div>
                <div class="row form-customerGroup">
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
                            </tr>
                            </thead>
                            <tbody>
                            @if($groupdetail->count())
                                @foreach ($groupdetail as $nd=> $rdet)
                                    @php
                                        $customerService = $rdet->customerService;
                                        $periodType = ($customerService) ? $customerService->period_type : "";
                                    @endphp
                                    <tr>
                                        <td>{{ $rdet->customerService(1)->get() }}</td>
                                        <td scope="row">
                                            <input type='hidden' name='detid[]' value='{{$rdet->id}}'>
                                            <input type='hidden' name='cust[]' value='{{$rdet->customerid}}'>
                                            <span>{{($nd+1)}}</span>
                                        </td>
                                        <td>{{$rdet->companycode."-".$rdet->companyname}}</td>
                                        <td>{{ ucfirst($periodType) }}</td>
                                        <td>{{ ($customerService) ? $customerService->amount : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->inc_hw : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->pay_before : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->start_date : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->end_date : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->service_date : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->soft_license : "" }}</td>
                                        <td>{{ ($customerService) ? $customerService->active : "" }}</td>
                                    </tr>
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
            </form>
        </div>
    </div>
@endsection
