<?php

namespace App\Http\Controllers\Backend\ProductReviews;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductReviewRequest;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductReviewController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_product_reviews, show_product_reviews')) {
            return redirect('admin/');
        }

        $reviews = ProductReview::with('user', 'product')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backend.product_reviews.index', compact('reviews'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_product_reviews')) {
            return redirect('admin/');
        }
    }


    public function store(Request $request)
    {
        if (!auth()->user()->ability('admin', 'create_product_reviews')) {
            return redirect('admin/');
        }
    }


    public function show(ProductReview $productReview)
    {
        if (!auth()->user()->ability('admin', 'display_product_reviews')) {
            return redirect('admin/');
        }

        return view('backend.product_reviews.show', compact('productReview'));
    }


    public function edit(ProductReview $productReview)
    {
        if (!auth()->user()->ability('admin', 'update_product_reviews')) {
            return redirect('admin/');
        }

        return view('backend.product_reviews.edit', compact('productReview'));
    }


    public function update(ProductReviewRequest $request, ProductReview $productReview)
    {
        if (!auth()->user()->ability('admin', 'update_product_reviews')) {
            return redirect('admin/');
        }

        $productReview->update($request->validated());
        Alert::toast('تم تحديث المرجع بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.product_reviews.index');
    }


    public function destroy(ProductReview $productReview)
    {
        if (!auth()->user()->ability('admin', 'delete_product_reviews')) {
            return redirect('admin/');
        }

        $productReview->delete();
        Alert::toast('تم حذف الرجع بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.product_reviews.index');
    }
}
