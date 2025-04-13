@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Coupons </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.product_coupons.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add new Coupons</span>
                </a>
            </div>
        </div>


        @include('backend.product_coupons.filter.filter')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Value</th>
                        <th>Use times</th>
                        <th>Validity date</th>
                        <th>Greater than</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th class="text-center" style="width: 30px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->value }} {{ $coupon->type == 'fixed' ? '$' : '%' }}</td>
                            <td>{{ $coupon->used_times . ' / ' . $coupon->use_times }}</td>
                            <td>{{ $coupon->start_date != '' ? $coupon->start_date->format('Y-m-d') . ' - ' . $coupon->expire_date->format('Y-m-d') : '-' }}</td>
                            <td>{{ $coupon->greater_than ?? '-' }}</td>
                            <td>{{ $coupon->status() }}</td>
                            <td>{{ $coupon->created_at->format('Y-m-d h:i a') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.product_coupons.edit', $coupon) }}" class="btn btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $coupon->id }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                <form id="delete-form-{{ $coupon->id }}" action="{{ route('admin.product_coupons.destroy', $coupon) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No coupons found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="8">
                            <div class="float-right">
                                {!! $coupons->appends(request()->all())->links() !!}
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection


