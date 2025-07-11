<?php

namespace App\Http\Livewire\Frontend\Customer;

use App\Models\Order;
use App\Models\OrderTransaction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class OrdersComponent extends Component
{
    use LivewireAlert;
    public $order;
    public $activeOrderId = null;

    public function displayOrder($id)
    {
        $this->order = Order::with('products')->find($id);
    }
    public function requestReturnOrder($id)
    {
        $order = Order::whereId($id)->first();

        $order->update([
            'order_status' => Order::REFUNDED_REQUEST
        ]);

        $order->transactions()->create([
            'transaction' => OrderTransaction::REFUNDED_REQUEST,
            'transaction_number' => $order->transactions()->whereTransaction(OrderTransaction::PAYMENT_COMPLETED)->first()->transaction_number,
        ]);



        $this->alert('success', 'Your request is sent successfully');
    }

    public function render()
    {
        return view('livewire.frontend.customer.orders-component', [
            'orders' => auth()->user()->orders
        ]);
    }
}

