<div class="col-lg-4">
    <div class="card border-0 rounded-0 p-lg-4 bg-light">
        <div class="card-body">
            <h5 class="text-uppercase mb-4">Cart total</h5>
            <ul class="list-unstyled mb-0">
                @if ($Total != 0)
                    <li class="d-flex align-items-center justify-content-between"><strong
                            class="text-uppercase small font-weight-bold">Subtotal</strong>
                        <span class="text-muted small">${{$Subtotal}}</span></li>
                    <li class="my-2"></li>

                    <li class="d-flex align-items-center justify-content-between mb-4"
                    ><strong class="text-uppercase small font-weight-bold">Tax</strong><span>${{$Tax}}</span>
                    </li>
                    <li>
                    <li class="border-bottom my-2"></li>

                    @if(session()->has('coupon'))
                        <li class="d-flex align-items-center justify-content-between">
                            <strong class="small font-weight-bold">Discount
                                <small>({{ getNumbers()->get('discount_code') }})</small></strong>
                            <span class="text-muted small ">-${{ $cart_discount }}</span>
                        <li class="border-bottom my-2"></li>
                        <li class="border-bottom my-2"></li>
                    @endif

                    @if(session()->has('shipping'))

                        <li class="d-flex align-items-center justify-content-between">
                            <strong class="small font-weight-bold">Shipping
                                <small>({{ getNumbers()->get('shipping_code') }})</small></strong>
                            <span class="text-muted small">${{ $cart_shipping }}</span>
                        </li>
                        <li class="border-bottom my-2"></li>
                    @endif

                    <li class="d-flex align-items-center justify-content-between mb-4"
                    ><strong class="text-uppercase small font-weight-bold">Total</strong><span>${{$Total}}</span>
                    </li>
                @else
                    <li class="align-items-center justify-content-center mb-4">
                        <span>Your cart is empty!</span>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
