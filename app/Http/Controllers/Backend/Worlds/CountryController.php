<?php

namespace App\Http\Controllers\Backend\Worlds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CountryController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_countries, show_countries')) {
            return redirect('admin/');
        }

        $countries = Country::with('states')->withCount('states')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.worlds.countries.index', compact('countries'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_countries')) {
            return redirect('admin/');
        }

        return view('backend.worlds.countries.create');
    }


    public function store(CountryRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_countries')) {
            return redirect('admin/');
        }

        Country::create($request->validated());
        Alert::toast('تم إضافة البلد بنجاح', 'success')->position('top-end');

        return redirect()->route('admin.countries.index');
    }


    public function show(Country $country)
    {
        if (!auth()->user()->ability('admin', 'display_countries')) {
            return redirect('admin/');
        }

        return view('backend.worlds.countries.show');
    }


    public function edit(Country $country)
    {
        if (!auth()->user()->ability('admin', 'update_countries')) {
            return redirect('admin/');
        }

        return view('backend.worlds.countries.edit', compact('country'));
    }


    public function update(CountryRequest $request, Country $country)
    {
        if (!auth()->user()->ability('admin', 'update_countries')) {
            return redirect('admin/');
        }

        $country->update($request->validated());
        Alert::toast('تم تحديث البلد بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.countries.index');
    }


    public function destroy(Country $country)
    {
        if (!auth()->user()->ability('admin', 'delete_countries')) {
            return redirect('admin/');
        }
        $country->delete();
        Alert::toast('تم حذف البلد بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.countries.index');
    }
}
