<section class="pt-5">
  <header class="text-center">
    <p class="small text-muted small text-uppercase mb-1">Carefully created collections</p>
    <h2 class="h5 text-uppercase mb-4">Browse our categories</h2>
  </header>
  <div class="row">
    @if(isset($product_categories) && $product_categories->count() > 0)
      @foreach($product_categories as $index => $category)
        <div class="col-md-4 mb-4 {{ $index % 3 === 2 ? '' : 'mb-md-0' }}">
          <a class="category-item" href="{{ route('frontend.shop', $category->slug) }}">
            <img class="img-fluid" src="{{ asset('backend/uploads/product_categories/' . $category->cover) }}" alt="{{ $category->name ?? 'Category' }}">
            <strong class="category-item-title">{{ $category->name ?? 'Category' }}</strong>
          </a>
        </div>
      @endforeach
    @else
      <p class="text-center w-100">No categories available at the moment.</p>
    @endif
  </div>
</section>
