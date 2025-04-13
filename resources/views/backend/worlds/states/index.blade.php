@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- DataTales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">States</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.states.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add new State</span>
                </a>
            </div>
        </div>

        @include('backend.worlds.states.filter.filter')

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Cities count</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 30px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($states as $state)
                    <tr>
                        <td>{{ $state->name }}</td>
                        <td>{{ $state->cities_count }}</td>
                        <td>{{ $state->country->name }}</td>
                        <td>{{ $state->status() }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.states.edit', $state) }}" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $state->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form id="delete-form-{{ $state->id }}" action="{{ route('admin.states.destroy', $state) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No states found</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="float-right">
                            {!! $states->appends(request()->all())->links() !!}
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


