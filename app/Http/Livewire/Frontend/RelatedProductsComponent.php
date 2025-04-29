<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RelatedProductsComponent extends Component
{
    use LivewireAlert;

    public $relatedProducts;

    public function mount($relatedProducts){
        $this->relatedProducts = $relatedProducts;
    }

    public function addToCart($id){

            $product = Product::whereId($id)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
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

    public function addToWishList($id){
        $product = Product::whereId($id)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();

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



    public function render()
    {
        return view('livewire.frontend.related-products-component');
    }
}
