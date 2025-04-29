<?php

namespace App\Http\Livewire\Frontend;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartTotalComponent extends Component
{
    protected $listeners = ['updateCart' => 'mount'];
    public $Subtotal ;
    Public $Tax;
    public $cart_discount;
    public $cart_shipping;
    public $Total ;


    public function mount(){
        $this->Subtotal = getNumbers()->get('subtotal');
        $this->Tax = getNumbers()->get('productTaxes');
        $this->cart_discount = getNumbers()->get('discount');
        $this->cart_shipping = getNumbers()->get('shipping');
        $this->Total = getNumbers()->get('total');

    }

    public function render()
    {
        return view('livewire.frontend.cart-total-component');
    }
}
