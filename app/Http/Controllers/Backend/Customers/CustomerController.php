<?php

namespace App\Http\Controllers\Backend\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerRequest;
use App\Models\Role;
use App\Models\User;
use App\Traits\ImageRemoveTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    use ImageUploadTrait, ImageRemoveTrait;

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_customers, show_customers')) {
            return redirect('admin/');
        }

        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);
        return view('backend.customers.index', compact('customers'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_customers')) {
            return redirect('admin/');
        }

        return view('backend.customers.create');
    }


    public function store(CustomerRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_customers')) {
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
                $file_name = $this->uploadSingleImage($image, $request->username, 'customers');

                $input['user_image'] = $file_name;
            }

            $customer = User::create($input);
            $customer->markEmailAsVerified();
            $customer->attachRole(Role::whereName('customer')->first()->id);

            Alert::toast('تم إضافة العميل بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.customers.index');

        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }
    }


    public function show(User $customer)
    {
        if (!auth()->user()->ability('admin', 'display_customers')) {
            return redirect('admin/');
        }

        return view('backend.customers.show', compact('customer'));
    }


    public function edit(User $customer)
    {
        if (!auth()->user()->ability('admin', 'update_customers')) {
            return redirect('admin/');
        }

        return view('backend.customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, User $customer)
    {
        if (!auth()->user()->ability('admin', 'update_customers')) {
            return redirect('admin/');
        }

        try {

            $input['first_name'] = $request->first_name;
            $input['last_name'] = $request->last_name;
            $input['username'] = $request->username;
            $input['email'] = $request->email;
            $input['mobile'] = $request->mobile;
            if (trim($request->password) != '') {
                $input['password'] = bcrypt($request->password);
            }
            $input['status'] = $request->status;


            // when update the category image
            if ($image = $request->file('user_image')) {

                // delete old image
                $this->deleteImages($customer, 'customers', 'user_image');

                // upload new image
                $file_name = $this->uploadSingleImage($image, $request->username, 'customers');
                $input['user_image'] = $file_name;
            }

            $customer->update($input);

            Alert::toast('تم تعديل العميل بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.customers.index');
        } catch (\Exception $e) {

            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'danger'
            ]);
        }
    }


    public function destroy(User $customer)
    {
        if (!auth()->user()->ability('admin', 'delete_customers')) {
            return redirect('admin/');
        }

        $this->deleteImages($customer, 'customers', 'user_image');
        $customer->delete();
        Alert::toast('تم الحزف العميل بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.customers.index');
    }

    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin', 'delete_product_categories')) {
            return redirect('admin/');
        }
        $customer = User::findOrFail($request->customer_id);
        $this->removeImage($customer, 'customers','user_image');
        return true;
    }

    public function get_customers()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when(\request()->input('query') != '', function ($query) {
                $query->search(\request()->input('query'));
            })
            ->get(['id', 'first_name', 'last_name', 'email'])->toArray();

        return response()->json($customers);
    }

}
