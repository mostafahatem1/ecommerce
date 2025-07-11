@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- DataTales Table -->
    <div class="card shadow mb-4">
{{--        <div class="card-header py-3 d-flex">--}}
{{--            <h6 class="m-0 font-weight-bold text-primary">Tags</h6>--}}
{{--            <div class="ml-auto">--}}
{{--                <a href="{{ route('admin.product_reviews.create') }}" class="btn btn-primary">--}}
{{--                    <span class="icon text-white-50">--}}
{{--                        <i class="fa fa-plus"></i>--}}
{{--                    </span>--}}
{{--                    <span class="text">Add new Tag</span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}

        @include('backend.product_reviews.filter.filter')

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Message</th>
                    <th>Rating</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th class="text-center" style="width: 30px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>
                            {{ $review->name }}<br>
                            {{ $review->email }}<br>
                            <small>{{ $review->user->fullname }}</small>
                        </td>
                        <td>
                            {{ $review->title }}
                        </td>
                        <td><span class="badge badge-success">{{ $review->rating }}</span></td>
                        <td>{{ $review->product->name }}</td>
                        <td>{{ $review->status() }}</td>
                        <td>{{ $review->created_at }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.product_reviews.show', $review->id) }}" class="btn btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.product_reviews.edit', $review->id) }}" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $review->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form id="delete-form-{{ $review->id }}" action="{{ route('admin.product_reviews.destroy', $review) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No reviews found</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="float-right">
                            {!! $reviews->appends(request()->all())->links() !!}
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


