<?php

namespace App\Http\Controllers\Backend\CustomerAddress;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerAddressRequest;
use App\Models\Country;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerAddressController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_customer_addresses, show_customer_addresses')) {
            return redirect('admin/');
        }

        $customer_addresses = UserAddress::with(['user:id,first_name,last_name', 'country:id,name', 'state:id,name', 'city:id,name']) // Eager load related models
        ->when(request()->filled('keyword'), function ($query) {
            $query->search(request()->keyword);
        })
            ->when(request()->filled('status'), function ($query) {
                $query->whereDefaultAddress(request()->status);
            })
            ->orderBy(request()->get('sort_by', 'id'), request()->get('order_by', 'desc'))
            ->paginate(request()->get('limit_by', 10));

        return view('backend.customer_addresses.index', compact('customer_addresses'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_customer_addresses')) {
            return redirect('admin/');
        }

        $countries = Country::whereStatus(true)->get(['id', 'name']);
        return view('backend.customer_addresses.create', compact('countries'));
    }


    public function store(CustomerAddressRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_customer_addresses')) {
            return redirect('admin/');
        }

        UserAddress::create($request->validated());
        Alert::toast('تم إضافة العنوان بنجاح', 'success')->position('top-end');

        return redirect()->route('admin.customer_addresses.index');
    }


    public function show(UserAddress $customer_address)
    {
        if (!auth()->user()->ability('admin', 'display_customer_addresses')) {
            return redirect('admin/');
        }

        return view('backend.customer_addresses.show', compact('customer_address'));
    }


    public function edit(UserAddress $customer_address)
    {
        if (!auth()->user()->ability('admin', 'update_customer_addresses')) {
            return redirect('admin/');
        }

        $countries = Country::whereStatus(true)->get(['id', 'name']);
        return view('backend.customer_addresses.edit', compact('customer_address', 'countries'));
    }


    public function update(CustomerAddressRequest $request, UserAddress $customer_address)
    {
        if (!auth()->user()->ability('admin', 'update_customer_addresses')) {
            return redirect('admin/');
        }

        $customer_address->update($request->validated());
        Alert::toast('تم تحديث العنوان بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.customer_addresses.index');
    }


    public function destroy(UserAddress $customer_address)
    {
        if (!auth()->user()->ability('admin', 'delete_customer_addresses')) {
            return redirect('admin/');
        }
        $customer_address->delete();
        Alert::toast('تم حذف العنوان بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.customer_addresses.index');
    }
}
