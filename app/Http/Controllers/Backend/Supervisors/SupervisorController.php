<?php

namespace App\Http\Controllers\Backend\Supervisors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SupervisorRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermissions;
use App\Traits\ImageRemoveTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupervisorController extends Controller
{
    use ImageUploadTrait, ImageRemoveTrait;

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_supervisors, show_supervisors')) {
            return redirect('admin/');
        }

        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backend.supervisors.index', compact('supervisors'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_supervisors')) {
            return redirect('admin/');
        }

        $permissions = Permission::get(['id', 'display_name']);
        return view('backend.supervisors.create', compact('permissions'));
    }


    public function store(SupervisorRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_supervisors')) {
            return redirect('admin/');
        }
        try {
        $input['first_name'] = $request->first_name;
        $input['last_name'] = $request->last_name;
        $input['username'] = $request->username;
        $input['email'] = $request->email;
        $input['mobile'] = $request->mobile;
        $input['password'] = bcrypt($request->password);
        $input['status'] = $request->status;

        if ($image = $request->file('user_image')) {
            // upload new image
            $file_name = $this->uploadSingleImage($image, $request->username, 'supervisors');
            $input['user_image'] = $file_name;
        }

        $supervisor = User::create($input);
        $supervisor->markEmailAsVerified();
        $supervisor->attachRole(Role::whereName('supervisor')->first()->id);
        // add permissions
        if (isset($request->permissions) && count($request->permissions) > 0) {
            $supervisor->permissions()->sync($request->permissions);
        }

            Alert::toast('تم إضافة مشرف بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.supervisors.index');

        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }
    }


    public function show(User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'display_supervisors')) {
            return redirect('admin/');
        }

        return view('backend.supervisors.show', compact('supervisor'));
    }


    public function edit(User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
            return redirect('admin/');
        }

        $permissions = Permission::get(['id', 'display_name']);
        $supervisorPermissions = UserPermissions::whereUserId($supervisor->id)->pluck('permission_id')->toArray();
        return view('backend.supervisors.edit', compact('supervisor', 'permissions', 'supervisorPermissions'));
    }


    public function update(SupervisorRequest $request, User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
            return redirect('admin/');
        }

        try {

        $input['first_name'] = $request->first_name;
        $input['last_name'] = $request->last_name;
        $input['username'] = $request->username;
        $input['email'] = $request->email;
        $input['mobile'] = $request->mobile;
        if (trim($request->password) != ''){
            $input['password'] = bcrypt($request->password);
        }
        $input['status'] = $request->status;

        if ($image = $request->file('user_image')) {
            // delete old image
            $this->deleteImages($supervisor, 'supervisors', 'user_image');

            // upload new image
            $file_name = $this->uploadSingleImage($image, $request->username, 'supervisors');
            $input['user_image'] = $file_name;
        }

        $supervisor->update($input);
        // add permissions
        if (isset($request->permissions) && count($request->permissions) > 0) {
            $supervisor->permissions()->sync($request->permissions);
        }


        Alert::toast('تم تعديل مشرف بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.supervisors.index');
        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }


    }

    public function destroy(User $supervisor)
    {
        if (!auth()->user()->ability('admin', 'delete_supervisors')) {
            return redirect('admin/');
        }

        $this->deleteImages($supervisor, 'supervisors', 'user_image');
        $supervisor->delete();
        Alert::toast('تم الحزف مشرف بنجاح', 'warning')->position('top-end');

        return redirect()->route('admin.supervisors.index');
    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_product_categories')) {
            return redirect('admin/');
        }
        $supervisor = User::findOrFail($request->supervisor_id);
        $this->removeImage($supervisor, 'supervisors','user_image');
        return true;
    }


}
