<?php

namespace App\Http\Controllers\Backend\ProductCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCategoryRequest;
use App\Models\ProductCategory;
use App\Traits\ImageRemoveTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;


class ProductCategoriesController extends Controller
{
    use ImageUploadTrait, ImageRemoveTrait;

    public function index()
    {

        if (!auth()->user()->ability('admin', 'manage_product_categories, show_product_categories')) {
            return redirect('admin/');
        }
        $categories = ProductCategory::with(['parent']) // تحميل علاقة parent
        ->withCount('products')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->where('status', \request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);


        return view('backend.product_categories.index', compact('categories'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_product_categories')) {
            return redirect('admin/');
        }

        $main_categories = ProductCategory::where('parent_id', null)->get(['id', 'name']);

        return view('backend.product_categories.create', compact('main_categories'));
    }


    public function store(ProductCategoryRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_product_categories')) {
            return redirect('admin/');
        }
        try {

            $input['name'] = $request->name;
            $input['status'] = $request->status;
            $input['parent_id'] = $request->parent_id;

            if ($image = $request->file('cover')) {
                // upload new image
                $file_name = $this->uploadSingleImage($image, $request->name, 'product_categories');
                $input['cover'] = $file_name;
            }

            ProductCategory::create($input);

            Alert::toast('تم إضافة القسم بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.product_categories.index');

        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }
    }


    public function show($id)
    {
        if (!auth()->user()->ability('admin', 'display_product_categories')) {
            return redirect('admin/');
        }
        return view('backend.product_categories.show');
    }


    public function edit(ProductCategory $productCategory)
    {
        if (!auth()->user()->ability('admin', 'update_product_categories')) {
            return redirect('admin/');
        }
        $main_categories = ProductCategory::where('parent_id', null)->get(['id', 'name']);

        return view('backend.product_categories.edit', compact('productCategory', 'main_categories'));
    }


    public function update(ProductCategoryRequest $request, ProductCategory $productCategory)
    {
        if (!auth()->user()->ability('admin', 'update_product_categories')) {
            return redirect('admin/');
        }
        try {

            $input['name'] = $request->name;
            $input['slug'] = null;
            $input['status'] = $request->status;
            $input['parent_id'] = $request->parent_id;

            // when update the category image
            if ($image = $request->file('cover')) {
                // delete old image
                $this->deleteImages($productCategory, 'product_categories', 'cover');
                // upload new image
                $file_name = $this->uploadSingleImage($image, $request->name, 'product_categories');

                $input['cover'] = $file_name;
            }

            $productCategory->update($input);

            Alert::toast('تم تعديل القسم بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.product_categories.index');

        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }


    }


    public function destroy(ProductCategory $productCategory)
    {
        if (!auth()->user()->ability('admin', 'delete_product_categories')) {
            return redirect('admin/');
        }

        $this->deleteImages($productCategory, 'product_categories', 'cover');
        $productCategory->delete();
        Alert::toast('تم الحزف القسم بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.product_categories.index');
    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_product_categories')) {
            return redirect('admin/');
        }
        $categories = ProductCategory::findOrFail($request->product_category_id);
        $this->removeImage($categories, 'product_categories', 'cover');
        return true;
    }
}

