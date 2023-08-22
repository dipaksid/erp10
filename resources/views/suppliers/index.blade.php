@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="">
            <!-- Page Heading Start -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4 d-flex">
                <h1 class="h3 mb-0 text-gray-800">Suppliers

                    @can('ADD SUPPLIER')
                        <a href="{{ url('supplier/create') }}" class="btn btn-success">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Create
                        </a>
                    @endcan

                </h1>
            </div>
            <!-- Page Heading END -->

            @include('partials/messages')

            <div class="d-flex">
                {{ $suppliers->links("pagination::bootstrap-4") }}
                @if($suppliers->hasMorePages() || (isset($input['searchvalue']) && $input["searchvalue"]!=""))
                    <form action="{{ action('App\Http\Controllers\SuppliersController@index') }}">
                        <div class="col-12">
                            <input class="form-control" placeholder="Search" name="searchvalue" value="{{((isset($input['searchvalue']))?$input['searchvalue']:'')}}">
                        </div>
                    </form>
                @endif
            </div>

            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th>Supplier Name</th>
                    <th>Area</th>
                    <th>Supplier Code</th>
                    <th>Registration No</th>
                    <th>Registration No 2</th>
                    <th>Contact Person</th>
                    <th>Phone 1</th>
                    <th>Phone 2</th>
                    <th>Email</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($suppliers) && $suppliers->count()>0)
                    @foreach ($suppliers as $irow=> $rsup)
                        <tr>
                            <th scope="row">{{ $irow+1 }}</th>
                            <td>{{$rsup->companyname}}</td>
                            <td>{{$rsup->description}}</td>
                            <td>{{$rsup->companycode}}</td>
                            <td>{{$rsup->registrationno}}</td>
                            <td>{{$rsup->registrationno2}}</td>
                            <td>{{$rsup->contactperson}}</td>
                            <td>{{$rsup->phoneno1}}</td>
                            <td>{{$rsup->phoneno2}}</td>
                            <td>{{$rsup->email}}</td>
                            <td class="text-center col-2">
                                <div class="d-flex">
                                    @can('VIEW SUPPLIER')
                                        <a href="{{action('App\Http\Controllers\SuppliersController@show',$rsup->id)}}?searchvalue={{((isset($input['searchvalue']))?$input['searchvalue']:'')}}&page={{((isset($input['page']))?$input['page']:'')}}"  class="btn btn-primary ">View</a>&nbsp;
                                    @endcan

                                    @can('EDIT SUPPLIER')
                                        <a href="{{action('App\Http\Controllers\SuppliersController@edit',$rsup->id)}}?searchvalue={{((isset($input['searchvalue']))?$input['searchvalue']:'')}}&page={{((isset($input['page']))?$input['page']:'')}}"  class="btn btn-primary ">Edit</a>&nbsp;
                                    @endcan

                                    @can('DELETE SUPPLIER')
                                        <form action="{{action('App\Http\Controllers\SuppliersController@destroy', $rsup->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="12">No Record Found</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
