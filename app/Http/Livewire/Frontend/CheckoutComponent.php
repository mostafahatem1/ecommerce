<?php

namespace App\Http\Livewire\Frontend;

use App\Models\PaymentMethod;
use App\Models\ProductCoupon;
use App\Models\ShippingCompany;
use App\Models\UserAddress;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CheckoutComponent extends Component
{
    use LivewireAlert;

    protected $listeners = ['updateCart' => 'mount'];
    public $Subtotal ;
    public $Total ;
    Public $Tax;

    public $coupon_code;

    public $cart_discount;
    public $cart_coupon;

    public $addresses;
    public $customer_address_id = 0;

    public $shipping_companies;
    public $shipping_company_id = 0;
    public $cart_shipping;


    public $payment_methods;
    public $payment_method_id = 0;
    public $payment_method_code;





    public function mount(){
        $this->customer_address_id = session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        $this->shipping_company_id = session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->payment_method_id = session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';

        $this->Subtotal = getNumbers()->get('subtotal');
        $this->Tax =  getNumbers()->get('productTaxes');
        $this->cart_discount = getNumbers()->get('discount');
        $this->addresses =  auth()->user()->addresses ;
        $this->cart_shipping = getNumbers()->get('shipping');
        $this->Total = getNumbers()->get('total');
        if ($this->customer_address_id == '') {
            $this->shipping_companies = collect([]);
        } else {
            $this->updateShippingCompanies();
        }

        $this->payment_methods = PaymentMethod::whereStatus(true)->get();

    }

    public function applyCoupon()
    {
        if (getNumbers()->get('subtotal') > 0) {
            $coupon = ProductCoupon::whereCode($this->coupon_code)->whereStatus(true)->first();

            if (!$coupon) {
                $this->coupon_code = '';
                $this->alert('error', 'Coupon is invalid!');
            } else {
                $couponValue = $coupon->discount($this->Subtotal);
                if ($couponValue > 0) {
                    session()->put('coupon', [
                        'code' => $coupon->code,
                        'discount' => $couponValue,
                        'value' => $coupon->value,
                    ]);
                    $this->coupon_code = session()->get('coupon')['code'];
                    $this->emit('updateCart');
                    $this->alert('success', 'Coupon applied successfully!');
                } else {
                    $this->alert('error', 'Coupon is invalid!');
                }
            }
        } else {
            $this->coupon_code = '';
            $this->alert('error', 'Please add product to cart');
        }

    }

    public function removeCoupon()
    {
        if (session()->has('coupon')) {
            session()->forget('coupon');
            $this->coupon_code = '';
            $this->emit('updateCart');
            $this->alert('success', 'Coupon removed successfully!');
        }
    }

    ///////////////////////////// Get ShippingCompanies  By CustomerAddressId ////////////////////

    public function updateShippingCompanies(){
         $addressesCountry = UserAddress::where('id', $this->customer_address_id)->first();
        $this->shipping_companies = ShippingCompany::whereHas('countries', function ($query) use ($addressesCountry) {
            $query->where('country_id', $addressesCountry->country_id);
        })->get();

    }
    public function updatingCustomerAddressId()
    {
        session()->forget('saved_customer_address_id');
        session()->forget('saved_shipping_company_id');
        session()->forget('shipping');
        session()->put('saved_customer_address_id', $this->customer_address_id);

        $this->customer_address_id = session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        $this->shipping_company_id = session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->payment_method_id = session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';
        $this->emit('updateCart');
    }

    public function updatedCustomerAddressId()
    {
        session()->forget('saved_customer_address_id');
        session()->forget('saved_shipping_company_id');
        session()->forget('shipping');
        session()->put('saved_customer_address_id', $this->customer_address_id);

        $this->customer_address_id = session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        $this->shipping_company_id = session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->payment_method_id = session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';
        $this->emit('updateCart');
    }

    ////////// Put shipping_company_id In session  Get CustomerAddressId And get Cost Shipping///////

    public function updatingShippingCompanyId()
    {
        session()->forget('saved_shipping_company_id');
        session()->put('saved_shipping_company_id', $this->shipping_company_id);

        $this->customer_address_id = session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        $this->shipping_company_id = session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
       $this->payment_method_id = session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';
        $this->emit('updateCart');
    }

    public function updatedShippingCompanyId()
    {
        session()->forget('saved_shipping_company_id');
        session()->put('saved_shipping_company_id', $this->shipping_company_id);

        $this->customer_address_id = session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        $this->shipping_company_id = session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->payment_method_id = session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';
        $this->emit('updateCart');
    }


    public function updateShippingCost(){
        $selectedShippingCompany = ShippingCompany::whereId($this->shipping_company_id)->first();
        session()->put('shipping', [
            'code' => $selectedShippingCompany->code,
            'cost' => $selectedShippingCompany->cost,
        ]);
        $this->emit('updateCart');
        $this->alert('success', 'Shipping cost is applied successfully');
    }

    public function updatePaymentMethod(){
        $payment_method = PaymentMethod::whereId($this->payment_method_id)->first();
        $this->payment_method_code = $payment_method->code;
    }


    public function render()
    {
        return view('livewire.frontend.checkout-component');
    }
}
