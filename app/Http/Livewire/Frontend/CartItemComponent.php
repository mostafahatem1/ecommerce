<?php

namespace App\Http\Livewire\Frontend;

use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CartItemComponent extends Component
{
    use LivewireAlert;

    public $itemRowId;
    public $item_quantity = 1;

    public function mount()
    {

        $this->item_quantity = Cart::instance('default')->get($this->itemRowId)->qty ?? 1;
    }

    public function decreaseQuantity($rowId)
    {
        if ($this->item_quantity > 1) {
            $this->item_quantity--;
            Cart::instance('default')->update($rowId, $this->item_quantity);
            $this->emit('updateCart');
        }
    }

    public function increaseQuantity($rowId)
    {
        $cartItem = Cart::instance('default')->get($rowId);

        $product = $cartItem->model;

        if ($this->item_quantity < $product->quantity) {
            $this->item_quantity++;
            Cart::instance('default')->update($rowId, $this->item_quantity);
            $this->emit('updateCart');
        } else {
            $this->alert('warning', 'This is Maximum quantity Warning is Max!');
        }
    }

    public function removeFromCart($rowId)
    {
        $this->emit('removeFromCart', $rowId);
    }



    public function render()
    {
        return view('livewire.frontend.cart-item-component',[
            'item' => Cart::instance('default')->get($this->itemRowId)
        ]);
    }
}
