<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use App\Models\ProductCategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class ShopProductsComponent extends Component
{
    use withPagination, LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $paginateLimit = 12;
    public $sortingBy = 'default';

    public $slugCategory;
    public $slugTag;

    public function mount($slugCategory = null, $slugTag = null)
    {
        $this->slugCategory = $slugCategory;
        $this->slugTag = $slugTag;
    }


    public function addToCart($productId)
    {
        $product = Product::whereId($productId)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();

        if ($product) {
            $duplicate = Cart::instance('default')->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });

            if ($duplicate->isNotEmpty()) {
                $this->alert('warning', 'Product Already in Cart');

            } else {
                Cart::instance('default')->add($product->id, $product->name, 1, $product->price)->associate(Product::class);
                $this->emit('updateCart');
                $this->alert('success', 'Product Added to Cart successfully');
            }
        }
    }


    public function addToWishList($productId)
    {
        $product = Product::whereId($productId)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();

        if ($product) {
            $duplicate = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });

            if ($duplicate->isNotEmpty()) {
                $this->alert('warning', 'Product Already in Wishlist');
            } else {
                Cart::instance('wishlist')->add($product->id, $product->name, 1, $product->price)->associate(Product::class);
                $this->emit('updateCart');
                $this->alert('success', 'Product Added to Wishlist successfully');
            }
        }
    }

    public function render()
    {
        try {
            // Validate the provided slugs
            if ($this->slugCategory !== null && $this->slugCategory !== 'tag' && $this->slugTag !== null) {
                throw new \Exception('Invalid combination of category and tag');
            }
            if ($this->slugCategory === 'tag' && $this->slugTag === null) {
                throw new \Exception('Missing tag slug');
            }

            // Sorting logic
            switch ($this->sortingBy) {
                case 'popularity':
                    $sort_field = 'id';
                    $sort_type = 'asc';
                    break;
                case 'low-high':
                    $sort_field = 'price';
                    $sort_type = 'asc';
                    break;
                case 'high-low':
                    $sort_field = 'price';
                    $sort_type = 'desc';
                    break;
                default:
                    $sort_field = 'id';
                    $sort_type = 'asc';
            }

            // Products query
            $products = Product::with('firstMedia');

            if ($this->slugCategory === null && $this->slugTag === null) {
                $products = $products->ActiveCategory();
            } elseif ($this->slugCategory !== null && $this->slugCategory !== 'tag' && $this->slugTag === null) {
                $category = ProductCategory::whereSlug($this->slugCategory)->whereStatus(true)->first();

                if (!$category) {
                    throw new \Exception('Category not found');
                }

                if (is_null($category->parent_id)) {
                    $children_category_id = ProductCategory::whereParentId($category->id)
                        ->whereStatus(true)->pluck('id')->toArray();

                    $products = $products->whereHas('category', function ($query) use ($children_category_id) {
                        $query->whereIn('id', $children_category_id);
                    });
                } else {
                    $products = $products->with('category')->whereHas('category', function ($query) {
                        $query->where([
                            ['slug', '=', $this->slugCategory],
                            ['status', '=', true],
                        ]);
                    });
                }
            } elseif ($this->slugTag !== null && $this->slugCategory === 'tag') {
                $tag = \App\Models\Tag::whereSlug($this->slugTag)->whereStatus(true)->first();

                if (!$tag) {
                    throw new \Exception('Tag not found');
                }

                $products = $products->with('tags')->whereHas('tags', function ($query) {
                    $query->where([
                        ['slug', '=', $this->slugTag],
                        ['status', '=', true],
                    ]);
                });
            }

            $products = $products->Active()
                ->HasQuantity()
                ->orderBy($sort_field, $sort_type)
                ->paginate($this->paginateLimit);

            return view('livewire.frontend.shop-products-component', [
                'products' => $products,
            ]);

        } catch (\Exception $e) {
            // رجّع صفحة 404 أو رسالة مخصصة
            abort(404);
            // أو لو حابب تبعت رسالة مخصصة تقدر تستخدم:
            // return view('errors.custom-message', ['message' => $e->getMessage()]);
        }
    }

}
