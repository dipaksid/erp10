@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="container">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Company Settings
                @can('ADD COMPANY')
                    <a href="{{ url('company_settings/create') }}" class="btn btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Create
                    </a>
                @endcan
            </h1>
        </div>
        @include('partials/messages')
        <div class="row pt-2 pb-2 w-50">
            {{ $companySettings->links("pagination::bootstrap-4") }}
            @if($companySettings->hasMorePages() || (isset($filters['searchvalue']) && $filters["searchvalue"] != ""))
                <form action="{{ action('App\Http\Controllers\CompanySettingsController@index') }}">
                    <div class="col-12 ml-0">
                        <input class="form-control" placeholder="Search" name="searchvalue" value="{{((isset($filters['searchvalue'])) ? $filters['searchvalue'] : '')}}">
                    </div>
                </form>
            @endif
        </div>

        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th>Company Code</th>
                    <th>Company Name</th>
                    <th>Registration Code 1</th>
                    <th>Registration Code 2</th>
                    <th>Contact Person</th>
                    <th>Contact No</th>
                    <th>Default</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($companySettings) && $companySettings->count()>0)
                    @foreach ($companySettings as $irow=> $rcompanySettings)
                        <tr>
                            <th scope="row">{{ $irow+1 }}</th>
                            <td>{{ $rcompanySettings->companycode }}</td>
                            <td>{{ $rcompanySettings->companyname }}</td>
                            <td>{{ $rcompanySettings->registrationno }}</td>
                            <td>{{ $rcompanySettings->registrationno2 }}</td>
                            <td>{{ $rcompanySettings->contactperson }}</td>
                            <td>{{ $rcompanySettings->phoneno1 }}</td>
                            <td>{{ $rcompanySettings->b_default }}</td>
                            <td class="text-center col-2">
                                <div class="d-flex">
                                    @can('VIEW COMPANY')
                                        <a href="{{ action('App\Http\Controllers\CompanySettingsController@show',$rcompanySettings->id) }}" class="btn btn-primary">View</a>&nbsp;
                                    @endcan
                                    @can('EDIT COMPANY')
                                        <a href="{{ action('App\Http\Controllers\CompanySettingsController@edit',$rcompanySettings->id) }}" class="btn btn-primary">Edit</a>&nbsp;
                                    @endcan
                                    @can('DELETE COMPANY')
                                        <form class="deletefrom" action="{{ action('App\Http\Controllers\CompanySettingsController@destroy', $rcompanySettings->id)}}" method="post" id="deleteForm">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="8">No Record Found</td>
                    </tr>
                @endif
            </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function deleteUser() {
            document.getElementById('deleteForm').submit();
        }
    </script>
@endsection
