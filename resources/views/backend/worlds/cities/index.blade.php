@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- DataTales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Cities</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add new City</span>
                </a>
            </div>
        </div>

        @include('backend.worlds.cities.filter.filter')

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>State</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 30px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->state->name }}</td>
                        <td>{{ $city->status() }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $city->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form id="delete-form-{{ $city->id }}" action="{{ route('admin.cities.destroy', $city) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No cities found</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4">
                        <div class="float-right">
                            {!! $cities->appends(request()->all())->links() !!}
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection


