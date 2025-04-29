<div x-data="{ activeOrderId: @entangle('activeOrderId') }">
    <h2 class="h5 text-uppercase mb-4">Orders</h2>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Order Ref.</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th class="col-2">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->ref_id }}</td>
                    <td>{{ $order->currency() . ' ' . $order->total }}</td>
                    <td>{!! $order->statusWithLabel() !!}</td>
                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                    <td class="text-right">
                        <button
                            type="button"
                            wire:click="displayOrder('{{ $order->id }}')"
                            @click="activeOrderId = {{ $order->id }}"
                            class="btn btn-success btn-sm">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>

                {{-- Order Details --}}
                <tr x-show="activeOrderId === {{ $order->id }}" x-on:click.away="activeOrderId = null">
                    <td colspan="5">
                        @if($order && $order->id == optional($this->order)->id)
                            <div class="border rounded shadow p-3">


                                {{-- Order Details --}}
                                <h6>Order Details - {{ $order->ref_id }}</h6>
                                <table class="table mb-3">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $order->currency() . ' ' . number_format($product->price, 2) }}</td>
                                            <td>{{ $product->pivot->quantity }}</td>
                                            <td>{{ $order->currency() . ' ' . number_format($product->price * $product->pivot->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                        <td>{{ $order->currency() . ' ' . number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    @if($order->discount_code)
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Discount ({{ $order->discount_code }})</strong></td>
                                            <td>{{ $order->currency() . ' ' . number_format($order->discount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax</strong></td>
                                        <td>{{ $order->currency() . ' ' . number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping</strong></td>
                                        <td>{{ $order->currency() . ' ' . number_format($order->shipping, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td>{{ $order->currency() . ' ' . number_format($order->total, 2) }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                {{-- Transactions --}}
                                <h6>Transactions</h6>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Return</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->status($transaction->transaction) }}</td>
                                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if ($loop->last && $transaction->transaction == \App\Models\OrderTransaction::FINISHED)
                                                    @php
                                                        $daysSince = now()->diffInDays($transaction->created_at);
                                                        $remaining = 5 - $daysSince;
                                                    @endphp
                                                    @if($remaining > 0)
                                                        <button wire:click="requestReturnOrder('{{ $order->id }}')" class="btn btn-link">
                                                            you can return order in {{ $remaining }} day{{ $remaining > 1 ? 's' : '' }}
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>


                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No orders found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
