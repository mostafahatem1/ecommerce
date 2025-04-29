<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Tag;
use App\Traits\ImageRemoveTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    use ImageUploadTrait, ImageRemoveTrait;

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_products, show_products')) {
            return redirect('admin/');
        }
        $products = Product::with(['tags', 'firstMedia'])
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->where('status', \request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);


        return view('backend.products.index', compact('products'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_products')) {
            return redirect('admin/');
        }
        $categories = ProductCategory::whereStatus(1)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        return view('backend.products.create', compact('categories', 'tags'));
    }


    public function store(ProductRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_products')) {
            return redirect('admin/');
        }
        try {
        $input['name'] = $request->name;
        $input['description'] = $request->description;
        $input['price'] = $request->price;
        $input['quantity'] = $request->quantity;
        $input['product_category_id'] = $request->product_category_id;
        $input['featured'] = $request->featured;
        $input['status'] = $request->status;

        $product = Product::create($input);
        $product->tags()->attach($request->tags);

        if ($request->images && count($request->images) > 0) {
            $this->uploadMultiImage($request->images, $product->slug, 'products', $product);
        }

        Alert::toast('تم إضافة المنتج بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.products.index');
        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }

    }


    public function show($id)
    {
        return view('backend.products.show');
    }


    public function edit(Product $product)
    {
        if (!auth()->user()->ability('admin', 'update_products')) {
            return redirect('admin/');
        }
        $categories = ProductCategory::whereStatus(1)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        return view('backend.products.edit', compact('categories', 'tags', 'product'));
    }

    public function update(ProductRequest $request, Product $product)
    {

        if (!auth()->user()->ability('admin', 'update_products')) {
            return redirect('admin/');
        }
        try {
            $input['name'] = $request->name;
            $input['description'] = $request->description;
            $input['price'] = $request->price;
            $input['quantity'] = $request->quantity;
            $input['product_category_id'] = $request->product_category_id;
            $input['featured'] = $request->featured;
            $input['status'] = $request->status;

            $product->update($input);
            $product->tags()->sync($request->tags);

            if ($request->images && count($request->images) > 0) {

                $this->uploadMultiImage($request->images, $product->slug, 'products', $product,$product->media()->count() + 1);
            }

            Alert::toast('تم التعديل المنتج بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }
    }


    public function destroy(Product $product)
    {
        if (!auth()->user()->ability('admin', 'delete_products')) {
            return redirect('admin/');
        }

        if ($product->media()->count() > 0) {
          $this->deleteImages($product,'products','file_name');
        }
        $product->delete();

        Alert::toast('تم الحزف المنتج بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.products.index');

    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_products')) {
            return redirect('admin/');
        }

        $product = Product::findOrFail($request->product_id);
        $image = $product->media()->whereId($request->image_id)->first();
        if (File::exists('backend/uploads/products/' . $image->file_name)) {
            unlink('backend/uploads/products/' . $image->file_name);
        }
        $image->delete();
        return true;
    }
}
