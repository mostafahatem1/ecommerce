<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CartComponent extends Component
{
    use LivewireAlert;


    protected $listeners = [
        'updateCart' => 'update_cart',
        'removeFromCart' => 'remove_from_cart',
        'moveToCart' => 'move_to_cart',
        'removeFromWishList' => 'remove_from_wishlist',

    ];
    public $cartCount;
    public $wishlistCount;

    public function mount(){
        $this->cartCount = Cart::instance('default')->count();
        $this->wishlistCount = Cart::instance('wishlist')->count();
    }

    public function update_cart(){
        $this->cartCount = Cart::instance('default')->count();
        $this->wishlistCount = Cart::instance('wishlist')->count();
    }


    public function remove_from_cart($rowId)
    {
        Cart::instance('default')->remove($rowId);
        $this->emit('updateCart');
        $this->alert('success', 'Item removed from your cart!');
        if (Cart::instance('default')->count() == 0){
            return redirect()->route('frontend.cart');
        }
    }

    public function move_to_cart($rowId)
    {

        $item = Cart::instance('wishlist')->get($rowId);
        $duplicate = Cart::instance('default')->search(function ($cartItem, $rId) use ($rowId) {
            return $rId === $rowId;
        });

        if ($duplicate->isNotEmpty()) {
            Cart::instance('wishlist')->remove($rowId);
            $this->alert('error', 'Product already exist.');
        } else {
            Cart::instance('default')->add($item->id, $item->name, 1, $item->price)->associate(Product::class);
            Cart::instance('wishlist')->remove($rowId);
            $this->alert('success', 'Product added in your cart successfully.');
        }

        $this->emit('updateCart');

        if (Cart::instance('wishlist')->count() == 0){
            return redirect()->route('frontend.wishlist');
        }

    }
    public function remove_from_wishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        $this->emit('updateCart');
        $this->alert('success', 'Item removed from your wishlist!');
        if (Cart::instance('wishlist')->count() == 0){
            return redirect()->route('frontend.wishlist');
        }
    }

    public function render()
    {
        return view('livewire.frontend.cart-component');
    }
}
