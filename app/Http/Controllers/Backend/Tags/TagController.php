<?php

namespace App\Http\Controllers\Backend\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TagController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_tags, show_tags')) {
            return redirect('admin/');
        }
        $tags =   Tag::select(['id', 'name', 'status','slug']) // اسحب الأعمدة اللي فعلاً محتاجها فقط
        ->withCount('products')// Use the relationship count
            ->when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->where('status', \request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);

        return view('backend.tags.index', compact('tags'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_tags')) {
            return redirect('admin/');
        }

        return view('backend.tags.create');
    }


    public function store(TagRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_tags')) {
            return redirect('admin/');
        }

        Tag::create($request->validated());
        Alert::toast('تم إضافة المنتج بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.tags.index');

    }


    public function show($id)
    {
        if (!auth()->user()->ability('admin', 'display_tags')) {
            return redirect('admin/');
        }

   return view('backend.tags.show');
    }


    public function edit(Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'update_tags')) {
            return redirect('admin/');
        }
        return view('backend.tags.edit', compact('tag'));
    }

    public function update(TagRequest $request,Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'update_tags')) {
            return redirect('admin/');
        }
       $input['name'] = $request->name;
       $input['status'] = $request->status;
       $input['slug'] = null;
         $tag->update($input);
          Alert::toast('تم تحديث المنتج بنجاح', 'success')->position('top-end');
          return redirect()->route('admin.tags.index');
    }


    public function destroy(Tag $tag)
    {
        if (!auth()->user()->ability('admin', 'delete_tags')) {
            return redirect('admin/');
        }
        $tag->delete();
        Alert::toast('تم حذف المنتج بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.tags.index');

    }
}
