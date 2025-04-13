<?php

namespace App\Http\Controllers\Backend\Worlds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StateRequest;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StateController extends Controller
{

    public function index()
    {
        if (!auth()->user()->ability('admin', 'manage_states, show_states')) {
            return redirect('admin/');
        }
        $states = State::with('cities','country')->withCount('cities')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.worlds.states.index', compact('states'));
    }


    public function create()
    {
        if (!auth()->user()->ability('admin', 'create_states')) {
            return redirect('admin/');
        }
        $countries = Country::get(['id', 'name']);
        return view('backend.worlds.states.create', compact('countries'));
    }


    public function store(StateRequest $request)
    {
        if (!auth()->user()->ability('admin', 'create_states')) {
            return redirect('admin/');
        }

        State::create($request->validated());
        Alert::toast('تم إضافة المحافظه بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.states.index');
    }


    public function show(State $state)
    {
        if (!auth()->user()->ability('admin', 'display_states')) {
            return redirect('admin/');
        }

        return view('backend.worlds.states.show', compact('state'));
    }


    public function edit(State $state)
    {
        if (!auth()->user()->ability('admin', 'update_states')) {
            return redirect('admin/');
        }
        $countries = Country::get(['id', 'name']);
        return view('backend.worlds.states.edit', compact('countries', 'state'));
    }


    public function update(StateRequest $request, State $state)
    {
        if (!auth()->user()->ability('admin', 'update_states')) {
            return redirect('admin/index');
        }

        $state->update($request->validated());
        Alert::toast('تم تعديل المحافظه بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.states.index');
    }


    public function destroy(State $state)
    {
        if (!auth()->user()->ability('admin', 'delete_states')) {
            return redirect('admin/');
        }
        $state->delete();
        Alert::toast('تم حذف المحافظه بنجاح', 'success')->position('top-end');
        return redirect()->route('admin.states.index');
    }
    public function get_states(Request $request)
    {
        $states = State::whereCountryId($request->country_id)->whereStatus(true)->get(['id', 'name'])->toArray();
        return response()->json($states);
    }
}
