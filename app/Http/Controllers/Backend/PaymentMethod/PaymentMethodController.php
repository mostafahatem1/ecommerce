<?php

namespace App\Http\Controllers\Backend\PaymentMethod;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PaymentMethodRequest;
use App\Models\PaymentMethod;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentMethodController extends Controller
{
    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_payment_methods,show_payment_methods')){
            return redirect('admin/');
        }

        $payment_methods = PaymentMethod::query()
            ->when(\request()->keyword != '', function ($q){
                $q->search(\request()->keyword);
            })
            ->when(\request()->status != '', function ($q){
                $q->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backend.payment_methods.index', compact('payment_methods'));
    }

    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_payment_methods')){
            return redirect('admin/');
        }

        return view('backend.payment_methods.create');
    }

    public function store(PaymentMethodRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_payment_methods')){
            return redirect('admin/');
        }

        PaymentMethod::create($request->validated());
        Alert::toast('تم إضافة طريق دفع بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.payment_methods.index');

    }

    public function show(PaymentMethod $payment_method)
    {
        if (!auth()->user()->ability('admin', 'display_payment_methods')){
            return redirect('admin/');
        }
        return view('backend.payment_methods.show', compact('payment_method'));
    }

    public function edit(PaymentMethod $payment_method)
    {
        if (!auth()->user()->ability('admin', 'update_payment_methods')){
            return redirect('admin/');
        }

        return view('backend.payment_methods.edit', compact('payment_method'));
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $payment_method)
    {
        if (!auth()->user()->ability('admin', 'update_payment_methods')){
            return redirect('admin/');
        }

        $payment_method->update($request->validated());
        Alert::toast('تم تحديث طريق الدفع بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.payment_methods.index');
    }

    public function destroy(PaymentMethod $payment_method)
    {
        if (!auth()->user()->ability('admin', 'delete_payment_methods')){
            return redirect('admin/');
        }

        $payment_method->delete();
        Alert::toast('تم حذف طريق الدفع بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.payment_methods.index');
    }
}
