<?php

namespace App\Http\Livewire\Frontend;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class ProductModalShared extends Component
{
    use LivewireAlert;


    protected $listeners = ['showProductModalAction'];
    public $productModalCount = false;
    public $productModal = [];
    public $productImages = [];

    public $quantity = 1;

    public function increaseQuantity()
    {
        if ($this->productModal->quantity > $this->quantity) {
            $this->quantity++;
        } else {
            $this->alert('warning', 'This is Maximum quantity Warning is Max!');

        }
    }
    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    public function addToCart(){
        $duplicate = Cart::instance('default')->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->productModal->id;
        });
        if ($duplicate->isNotEmpty()) {
            $this->alert('warning', 'Product Already in Cart');
            return;
        }else{
            Cart::instance('default')->add($this->productModal->id, $this->productModal->name, $this->quantity, $this->productModal->price)->associate(Product::class);
            $this->quantity = 1;
            $this->emit('updateCart');
            $this->alert('success', 'Product Added to Cart successfully');
        }
    }

    public function addToWishList(){
        $duplicate = Cart::instance('wishlist')->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->productModal->id;
        });
        if ($duplicate->isNotEmpty()) {
            $this->alert('warning', 'Product Already in Wishlist');

        }else{
            Cart::instance('wishlist')->add($this->productModal->id, $this->productModal->name, 1, $this->productModal->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product Added to Wishlist successfully');
        }
    }
    public function showProductModalAction($slug)
    {
        $this->productModal = Product::withAvg('reviews','rating')
            ->where('slug', $slug)
            ->Active()
            ->HasQuantity()
            ->ActiveCategory()
            ->firstOrFail();
        $this->productImages = $this->productModal->media;
        $this->quantity = 1;
        $this->productModalCount = true;

    }
    public function render()
    {
        return view('livewire.frontend.product-modal-shared');
    }
}
