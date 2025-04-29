<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ShowProductComponent extends Component
{
    use LivewireAlert;

    public $product;
    public $quantity = 1;

    public function mount($product){
        $this->product = $product;
    }

    public function decreaseQuantity(){
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    public function increaseQuantity(){
        if ($this->product->quantity > $this->quantity) {
            $this->quantity++;
        } else {
            $this->alert('warning', 'This is Maximum quantity Warning is Max!');
        }
    }

    public function addToCart(){
        $duplicate = Cart::instance('default')->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->product->id;
        });
        if ($duplicate->isNotEmpty()) {
            $this->alert('warning', 'Product Already in Cart');
        }else{
            Cart::instance('default')->add($this->product->id, $this->product->name, $this->quantity, $this->product->price)->associate(Product::class);
            $this->quantity = 1;
            $this->emit('updateCart');
            $this->alert('success', 'Product Added to Cart successfully');
        }
    }



    public function addToWishList(){
        $duplicate = Cart::instance('wishlist')->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->product->id;
        });
        if ($duplicate->isNotEmpty()) {
            $this->alert('warning', 'Product Already in Wishlist');
        }else{
            Cart::instance('wishlist')->add($this->product->id, $this->product->name, 1, $this->product->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product Added to Wishlist successfully');
        }
    }

    public function render()
    {
        return view('livewire.frontend.show-product-component');
    }
}
