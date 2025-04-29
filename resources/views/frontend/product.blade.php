@extends('frontend.layouts.master')

@section('title')
Detail-Ecommerce
@endsection

@section('content')
      <section class="py-5">
        <div class="container">
          <div class="row mb-5">
            <div class="col-lg-6">
              <!-- PRODUCT SLIDER-->
              <div class="row m-sm-0">
                <div class="col-sm-2 p-sm-0 order-2 order-sm-1 mt-2 mt-sm-0">
                  <div class="owl-thumbs d-flex flex-row flex-sm-column" data-slider-id="1">
                      @foreach($product->media as $media )
                          <div class="owl-thumb-item flex-fill mb-2 {{ !$loop->last ? 'mr-2 mr-sm-0' : null }}">
                              <img class="w-100"  src="{{ asset('backend/uploads/products/'.$media->file_name) }}?v={{ time() }}" alt="{{ $product->name }}">
                          </div>
                      @endforeach
                  </div>
                </div>
                <div class="col-sm-10 order-1 order-sm-2">
                  <div class="owl-carousel product-slider" data-slider-id="1">
                      @foreach($product->media as $media )
                          <a class="d-block"  href="{{ asset('backend/uploads/products/'.$media->file_name) }}?v={{ time() }}" data-lightbox="product" title="{{ $product->name }}">
                              <img class="img-fluid" src="{{ asset('backend/uploads/products/'.$media->file_name) }}?v={{ time() }}" alt="{{ $product->name }}">
                          </a>
                      @endforeach
                  </div>
                </div>
              </div>
            </div>
            <!-- PRODUCT DETAILS-->
            <div class="col-lg-6">
              <ul class="list-inline mb-2">
                  @if ($product->reviews_avg_rating != '')
                      @for ($i = 0; $i < 5; $i++)
                          <li   class="list-inline-item m-0"><i class="{{ round($product->reviews_avg_rating) <= $i ? 'far' : 'fas' }} fa-star fa-sm text-warning"></i></li>
                      @endfor
                  @else
                      <li class="list-inline-item m-0"><i class="far fa-star fa-sm text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="far fa-star fa-sm text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="far fa-star fa-sm text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="far fa-star fa-sm text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="far fa-star fa-sm text-warning"></i></li>
                  @endif
              </ul>
                <h1>{{ $product->name }}</h1>
                <p class="text-muted lead">${{ $product->price }}</p>
                <p class="text-small mb-4">{!! $product->description !!}  </p>
                <livewire:frontend.show-product-component :product="$product" />
                <br>
              <ul class="list-unstyled small d-inline-block">
                  <li class="px-3 py-2 mb-1 bg-white text-muted"><strong class="text-uppercase text-dark">Category:</strong><a class="reset-anchor ml-2" href="#">{{$product->category->name}}</a></li>
                  <li class="px-3 py-2 mb-1 bg-white text-muted"><strong class="text-uppercase text-dark">Tags:</strong>
                      @foreach($product->tags as $tag)
                          <a class="reset-anchor ml-2" href="#{{ $tag->id }}">{{$tag->name}}</a>
                      @endforeach

                  </li>
              </ul>
            </div>
          </div>
          <!-- DETAILS TABS-->
          <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Description</a></li>
            <li class="nav-item"><a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews</a></li>
          </ul>
          <div class="tab-content mb-5" id="myTabContent">

            {{--            Description                     --}}
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
              <div class="p-4 p-lg-5 bg-white">
                <h6 class="text-uppercase">Product description </h6>
                <p class="text-muted text-small mb-0">
                    {!! $product->description !!}
                </p>
              </div>
            </div>

              {{--            Reviews                     --}}
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
              <div class="p-4 p-lg-5 bg-white">
                <div class="row">
                  <div class="col-lg-8">
                      <div id="reviews-container">
                          {{-- عرض جميع المراجعات، وإخفاء ما بعد أول 3 --}}
                          @foreach ($product->reviews as $index => $review)
                              <div class="media mb-3 review-item {{ $index >= 3 ? 'd-none' : '' }}">
                                  <img class="rounded-circle" src="{{ asset($review->user->user_image ? 'backend/uploads/customers/' . $review->user->user_image : 'backend/uploads/customers/default.jpg') }}" alt="" width="50">
                                  <div class="media-body ml-3">
                                      <h6 class="mb-0 text-uppercase">{{ $review->user->fullname }}</h6>
                                      <p class="small text-muted mb-0 text-uppercase">{{ $review->created_at->format('d M, Y') }}</p>
                                      <ul class="list-inline mb-1 text-xs">
                                          @if ($review->rating)
                                              @for ($i = 0; $i < 5; $i++)
                                                  <li class="list-inline-item m-0">
                                                      <i class="{{ round($review->rating) <= $i ? 'far' : 'fas' }} fa-star fa-sm text-warning"></i>
                                                  </li>
                                              @endfor
                                          @else
                                              @for ($i = 0; $i < 5; $i++)
                                                  <li class="list-inline-item m-0"><i class="far fa-star fa-sm text-warning"></i></li>
                                              @endfor
                                          @endif
                                      </ul>
                                      <p class="mb-0">{{ $review->title }}</p>
                                      <p class="text-small mb-0 text-muted">{!! $review->message !!}</p>
                                  </div>
                              </div>
                          @endforeach

                          {{-- أزرار التحكم (عرض المزيد/عرض أقل) --}}
                          @if ($product->reviews->count() > 3)
                              <div class="text-center mt-3">
                                  <button id="show-more-btn" class="btn btn-primary btn-sm">Show More Reviews</button>
                                  <button id="show-less-btn" class="btn btn-secondary btn-sm d-none">Show Less</button>
                              </div>
                          @endif
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RELATED PRODUCTS-->
         <livewire:frontend.related-products-component :relatedProducts="$relatedProducts" />
        </div>
      </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reviews = Array.from(document.querySelectorAll('.review-item')); // جميع المراجعات
            const showMoreButton = document.getElementById('show-more-btn');
            const showLessButton = document.getElementById('show-less-btn');
            const batchSize = 3; // عدد المراجعات في كل دفعة
            let visibleCount = 3; // العدد الظاهر حاليًا

            // التحكم في عرض المزيد
            showMoreButton.addEventListener('click', function () {
                const nextBatchCount = visibleCount + batchSize;

                // إظهار عناصر جديدة حتى الحد المحسوب
                reviews.forEach((review, index) => {
                    if (index < nextBatchCount) {
                        review.classList.remove('d-none');
                    }
                });

                // تحديث عدد العناصر الظاهرة
                visibleCount = nextBatchCount;

                // إذا ظهرت جميع العناصر، نخفي زر "عرض المزيد"
                if (visibleCount >= reviews.length) {
                    showMoreButton.classList.add('d-none');
                }

                // إظهار زر "عرض أقل"
                showLessButton.classList.remove('d-none');
            });

            // التحكم في عرض أقل
            showLessButton.addEventListener('click', function () {
                const minVisibleCount = 3; // أقل عدد من العناصر التي يجب أن تبقى ظاهرة

                // إخفاء العناصر إلى العدد الأدنى
                reviews.forEach((review, index) => {
                    if (index >= minVisibleCount) {
                        review.classList.add('d-none');
                    }
                });

                // تحديث العدد الظاهر
                visibleCount = minVisibleCount;

                // إذا كانت العناصر المخفية >= العدد الأدنى، إخفاء زر "عرض أقل"
                showLessButton.classList.add('d-none');

                // إظهار زر "عرض المزيد"
                showMoreButton.classList.remove('d-none');
            });
        });
    </script>
@endsection
