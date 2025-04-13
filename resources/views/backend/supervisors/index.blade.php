@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Supervisor</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.supervisors.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add New Supervisor</span>
                </a>
            </div>
        </div>

        @include('backend.supervisors.filter.filter')

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email & Mobile</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th class="text-center" style="width: 30px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($supervisors as $supervisor)
                    <tr>
                        <td>
                            @if ($supervisor->user_image != '')
                                <img src="{{ asset('backend/uploads/supervisors/'. $supervisor->user_image) }}" width="60" height="60" alt="{{ $supervisor->name }}" class="rounded-circle">
                            @else
                                <img src="{{ asset('backend/uploads/supervisors/default.jpg') }}" width="60" height="60" alt="{{ $supervisor->name }}" class="rounded-circle">
                            @endif
                        </td>
                        <td>
                            {{ $supervisor->full_name }}<br>
                            <strong>{{ $supervisor->username }}</strong>
                        </td>
                        <td>
                            {{ $supervisor->email }}<br>
                            {{ $supervisor->mobile }}
                        </td>
                        <td>{{ $supervisor->status() }}</td>
                        <td>{{ $supervisor->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.supervisors.edit', $supervisor) }}" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $supervisor->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form id="delete-form-{{ $supervisor->id }}" action="{{ route('admin.supervisors.destroy', $supervisor) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No supervisors found</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="float-right">
                            {!! $supervisors->appends(request()->all())->links() !!}
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


