<?php

namespace App\Http\Controllers\Backend\Worlds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CityRequest;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CityController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_cities, show_cities')) {
            return redirect('admin/');
        }

        $cities = City::with('state')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.worlds.cities.index', compact('cities'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_cities')) {
            return redirect('admin/');
        }
        $states = State::get(['id', 'name']);
        return view('backend.worlds.cities.create', compact('states'));
    }


    public function store(CityRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_cities')) {
            return redirect('admin/');
        }

        City::create($request->validated());
        Alert::toast('تم إضافة المدينه بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.cities.index');
    }


    public function show(City $city)
    {
        if (!auth()->user()->ability('admin', 'display_cities')) {
            return redirect('admin/');
        }

        return view('backend.worlds.cities.show', compact('city'));
    }


    public function edit(City $city)
    {
        if (!auth()->user()->ability('admin', 'update_cities')) {
            return redirect('admin/');
        }
        $states = State::get(['id', 'name']);
        return view('backend.worlds.cities.edit', compact('states', 'city'));
    }


    public function update(CityRequest $request, City $city)
    {
        if (!auth()->user()->ability('admin', 'update_cities')) {
            return redirect('admin/');
        }

        $city->update($request->validated());
        Alert::toast('تم تعديل المدينه بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.cities.index');
    }


    public function destroy(City $city)
    {
        if (!auth()->user()->ability('admin', 'delete_cities')) {
            return redirect('admin/');
        }
        $city->delete();
        Alert::toast('تم حذف المدينه بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.cities.index');
    }

    public function get_cities(Request $request)
    {
        $cities = City::whereStateId($request->state_id)->whereStatus(true)->get(['id', 'name'])->toArray();
        return response()->json($cities);
    }
}
