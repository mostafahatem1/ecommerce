<?php

namespace App\Http\Controllers\Backend\ShippingCompanies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ShippingCompanyRequest;
use App\Models\Country;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ShippingCompanyController extends Controller
{

    public function index()
    {
        if (!Auth::user()->ability('admin', 'manage_shipping_companies,show_shipping_companies')){
            return redirect('admin/');
        }

        $shipping_companies = ShippingCompany::withCount('countries')
            ->when(\request()->keyword != '', function ($q){
                $q->search(\request()->keyword);
            })
            ->when(\request()->status != '', function ($q){
                $q->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.shipping_companies.index', compact('shipping_companies'));
    }


    public function create()
    {
        if (!Auth::user()->ability('admin', 'create_shipping_companies')){
            return redirect('admin/');
        }
        $countries = Country::orderBy('id', 'asc')->get(['id', 'name']);
        return view('backend.shipping_companies.create', compact('countries'));
    }


    public function store(ShippingCompanyRequest $request)
    {
        if (!Auth::user()->ability('admin', 'create_shipping_companies')){
            return redirect('admin/');
        }

        if ($request->validated()) {
            $shipping_company = ShippingCompany::create($request->except('countries', '_token', 'submit'));
            $shipping_company->countries()->attach($request->countries);
            Alert::toast('تم إضافة شركات الشحن بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.shipping_companies.index');
        } else {
            return redirect()->route('admin.shipping_companies.index')->with([
                'message' => 'Something wrong',
                'alert-type' => 'danger'
            ]);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit(ShippingCompany $shipping_company)
    {
        if (!Auth::user()->ability('admin', 'update_shipping_companies')){
            return redirect('admin/');
        }

        $shipping_company->with('countries');
        $countries = Country::get(['id', 'name']);
        return view('backend.shipping_companies.edit', compact('shipping_company', 'countries'));
    }


    public function update(ShippingCompanyRequest $request, ShippingCompany $shipping_company)
    {
        if (!Auth::user()->ability('admin', 'update_shipping_companies')){
            return redirect('admin/');
        }


        if ($request->validated()) {

            $shipping_company->update($request->except('countries', '_token', 'submit'));
            $shipping_company->countries()->sync($request->countries);
            Alert::toast('تم تحديث شركات الشحن بنجاح', 'success')->position('top-end');
            return redirect()->route('admin.shipping_companies.index');
        } else {
            return redirect()->route('admin.shipping_companies.index');
        }
    }


    public function destroy(ShippingCompany $shipping_company)
    {
        if (!Auth::user()->ability('admin', 'delete_shipping_companies')){
            return redirect('admin/');
        }
        $shipping_company->delete();
        Alert::toast('تم حذف شركات الشحن بنجاح', 'warning')->position('top-end');
        return redirect()->route('admin.shipping_companies.index');
    }
}
