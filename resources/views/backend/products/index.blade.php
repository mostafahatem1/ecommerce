@extends('backend.layouts.master')

@section('title', 'Admin - Dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Product </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add new Product</span>
                </a>
            </div>
        </div>


        @include('backend.products.filter.filter')
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Feature</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th class="text-center" style="width: 30px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td>
                                @if ($product->firstMedia)
                                    <img src="{{ asset('backend/uploads/products/' . $product->firstMedia->file_name) }}" width="60" height="60" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('backend/uploads/products/no-image.jpg') }}" width="60" height="60" alt="{{ $product->name }}">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->featured() }}</td>
                            <td>{{ $product->quantity}}</td>
                            <td>{{ $product->price}}</td>
                            <td>{{ $product->tags->pluck('name')->join(', ') }}</td>
                            <td>{{ $product->status() }} </td>
                            <td>{{ $product->created_at }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger swal-confirm" data-id="{{ $product->id }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No categories found</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="12">
                                <div class="float-right">
                                    {!! $products->appends(request()->all())->links() !!}
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


