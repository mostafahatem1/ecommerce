@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Shipping Companies</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.shipping_companies.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add new Shipping Company</span>
                </a>
            </div>
        </div>

        @include('backend.shipping_companies.filter.filter')

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Fast</th>
                    <th>Cost</th>
                    <th>Countries count</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 30px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($shipping_companies as $shipping_company)
                    <tr>
                        <td>{{ $shipping_company->name }}</td>
                        <td>{{ $shipping_company->code }}</a></td>
                        <td>{{ $shipping_company->description }}</a></td>
                        <td>{{ $shipping_company->fast() }}</a></td>
                        <td>{{ $shipping_company->cost }}</a></td>
                        <td>{{ $shipping_company->countries_count }}</td>
                        <td>{{ $shipping_company->status() }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.shipping_companies.edit', $shipping_company) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $shipping_company->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form id="delete-form-{{ $shipping_company->id }}" action="{{ route('admin.shipping_companies.destroy', $shipping_company) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No shipping companies found</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="8">
                        <div class="float-right">
                            {!! $shipping_companies->appends(request()->all())->links() !!}
                        </div>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
@endsection


