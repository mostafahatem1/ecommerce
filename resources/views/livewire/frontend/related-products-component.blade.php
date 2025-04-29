<div>
    <h2 class="h5 text-uppercase mb-4">Related products</h2>
    <div class="row">
        @forelse($relatedProducts as $relatedProduct)
            <!-- PRODUCT-->
            <div class="col-lg-3 col-sm-6">
                <div class="product text-center skel-loader">
                    <div class="d-block mb-3 position-relative"><a class="d-block" href="{{ route('frontend.product', $relatedProduct->slug) }}"><img class="img-fluid w-100" src="{{ asset('backend/uploads/products/'. $relatedProduct->firstMedia->file_name) }}" alt="{{ $relatedProduct->name }}" alt="..."></a>
                        <div class="product-overlay">
                            <ul class="mb-0 list-inline">
                                <li wire:click.prevent="addToWishList('{{$relatedProduct->id}}')" class="list-inline-item m-0 p-0"><a class="btn btn-sm btn-outline-dark" href="#"><i class="far fa-heart"></i></a></li>
                                <li wire:click.prevent="addToCart('{{$relatedProduct->id}}')" class="list-inline-item m-0 p-0"><a class="btn btn-sm btn-dark" href="#">Add to cart</a></li>
{{--                                <li class="list-inline-item mr-0"><a class="btn btn-sm btn-outline-dark" href="#productView" data-toggle="modal"><i class="fas fa-expand"></i></a></li>--}}
                                <li class="list-inline-item mr-0">
                                    <a class="btn btn-sm btn-outline-dark" wire:click.prevent="$emit('showProductModalAction','{{$relatedProduct->slug}}')" data-target="#productView" data-toggle="modal" href="#">
                                        <i class="fas fa-expand"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h6><a class="reset-anchor" href="{{ route('frontend.product', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a></h6>
                    <p class="small text-muted">${{ $relatedProduct->price }}</p>
                </div>
            </div>
        @empty
        @endforelse

    </div>
</div>
