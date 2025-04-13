<?php

namespace App\Http\Controllers\Backend\ProductCoupons;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCouponRequest;
use App\Models\ProductCoupon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductCouponController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_product_coupons, show_product_coupons')) {
            return redirect('admin/');
        }

        $coupons = ProductCoupon::query()
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backend.product_coupons.index', compact('coupons'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_product_coupons')) {
            return redirect('admin/');
        }
        return view('backend.product_coupons.create');
    }


    public function store(ProductCouponRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_product_coupons')) {
            return redirect('admin/');
        }

        ProductCoupon::create($request->validated());
        Alert::toast('تم إضافة كوبون بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.product_coupons.index');
    }

    public function show($id)
    {
        if (!auth()->user()->ability('admin', 'display_product_coupons')) {
            return redirect('admin/');
        }

        return view('backend.product_coupons.show');
    }


    public function edit(ProductCoupon $productCoupon)
    {
        if (!auth()->user()->ability('admin', 'update_product_coupons')) {
            return redirect('admin/');
        }
        return view('backend.product_coupons.edit', compact('productCoupon'));
    }

    public function update(ProductCouponRequest $request, ProductCoupon $productCoupon)
    {
        if (!auth()->user()->ability('admin', 'update_product_coupons')) {
            return redirect('admin/');
        }

        $productCoupon->update($request->validated());
        Alert::toast('تم تحديث كوبون بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.product_coupons.index');
    }


    public function destroy(ProductCoupon $productCoupon)
    {
        if (!auth()->user()->ability('admin', 'delete_product_coupons')) {
            return redirect('admin/');
        }

        $productCoupon->delete();
        Alert::toast('تم حذف كوبون بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.product_coupons.index');
    }

}
