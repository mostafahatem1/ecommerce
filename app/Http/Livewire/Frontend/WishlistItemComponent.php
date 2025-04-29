<?php

namespace App\Http\Livewire\Frontend;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class WishlistItemComponent extends Component
{
    public $itemRowId;

    public function moveToCart($rowId){
        $this->emit('moveToCart', $rowId);
    }
    public function removeFromWishList($rowId){
        $this->emit('removeFromWishList', $this->itemRowId);
    }

    public function render()
    {
        return view('livewire.frontend.wishlist-item-component',[
            'item' => Cart::instance('wishlist')->get($this->itemRowId)
        ]);
    }
}
