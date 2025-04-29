<?php

namespace App\Http\Controllers\Backend\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\User;
use App\Notifications\Backend\Order\OrderNotification;
use App\Services\OmnipayService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class OrderController extends Controller
{
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_orders, show_orders')) {
            return redirect('admin/');
        }

        $orders = Order::query()
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereOrderStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);


        return view('backend.orders.index', compact('orders'));
    }


    public function show(Order $order)
    {
        if (!auth()->user()->ability('admin', 'display_orders')) {
            return redirect('admin/');
        }

        $order_status_array = [
            '0' => 'New order',
            '1' => 'Paid',
            '2' => 'Under process',
            '3' => 'Finished',
            '4' => 'Rejected',
            '5' => 'Canceled',
            '6' => 'Refund requested',
            '7' => 'Returned order',
            '8' => 'Refunded',
        ];

        $order_status_array = array_filter($order_status_array, function ($v, $k) use ($order) {
            return $k >= $order->order_status;
        }, ARRAY_FILTER_USE_BOTH);

        return view('backend.orders.show', compact('order', 'order_status_array'));
    }

    public function edit(Order $order)
    {
        if (!auth()->user()->ability('admin', 'update_orders')) {
            return redirect('admin/');
        }

        // return view('backend.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if (!auth()->user()->ability('admin', 'update_orders')) {
            return redirect('admin/');
        }
        $customer = User::find($order->user_id);

        if ($request->order_status == Order::REFUNDED){
            $omniPay = new OmnipayService('PayPal_Express');
            $response = $omniPay->refund([
                'amount' => $order->total,
                'transactionReference' => $order->transactions()->where('transaction', OrderTransaction::PAYMENT_COMPLETED)->first()->transaction_number,
                'cancelUrl' => $omniPay->getCancelUrl($order->id),
                'returnUrl' => $omniPay->getReturnUrl($order->id),
                'notifyUrl' => $omniPay->getNotifyUrl($order->id),
            ]);

            if ($response->isSuccessful()) {
                $order->update(['order_status' => Order::REFUNDED]);
                $order->transactions()->create([
                    'transaction' => OrderTransaction::REFUNDED,
                    'transaction_number' => $response->getTransactionReference(),
                    'payment_result' => 'success'
                ]);

                $customer->notify(new OrderNotification($order));
                Alert::success('Order status updated To REFUNDED successfully');
                return back();

            }

        } else {

            $order->update(['order_status'=> $request->order_status]);

            $order->transactions()->create([
                'transaction' => $request->order_status,
                'transaction_number'=> null,
                'payment_result'=> null,
            ]);

            $customer->notify(new OrderNotification($order));
           Alert::success('Order status updated successfully');
            return back();

        }

    }

    public function destroy(Order $order)
    {
        if (!auth()->user()->ability('admin', 'delete_orders')) {
            return redirect('admin/');
        }
        $order->delete();
        Alert::toast('تم حذف الطلب بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.orders.index');
    }



}
