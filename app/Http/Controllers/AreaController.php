<?php

namespace App\Http\Controllers;

use App\Http\Requests\Area\StoreRequest;
use App\Http\Requests\Area\UpdateRequest;
use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $areas = Area::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('code')
            ->simplePaginate(10)
            ->withQueryString();

        return view('areas.index', [
            'title' => 'Astra Report - Daftar Area',
            'areas' => $areas,
        ]);
    }

    public function create(): View
    {
        return view('areas.create', [
            'title' => 'Astra Report - Tambah Area',
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Area::create($request->validated());

        return redirect()->route('areas.index')->with('success', 'Data area berhasil ditambahkan.');
    }

    public function show(Area $area): View
    {
        return view('areas.show', [
            'title' => 'Astra Report - Detail Area',
            'area' => $area,
        ]);
    }

    public function edit(Area $area): View
    {
        return view('areas.edit', [
            'title' => 'Astra Report - Edit Area',
            'area' => $area,
        ]);
    }

    public function update(UpdateRequest $request, Area $area): RedirectResponse
    {
        $area->update($request->validated());

        return redirect()->route('areas.index')->with('success', 'Data area berhasil diperbarui.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        if ($area->isInUse()) {
            return redirect()->route('areas.index')
                ->with('error', 'Data area tidak dapat dihapus karena masih dipakai oleh tugas.');
        }

        $area->delete();

        return redirect()->route('areas.index')->with('success', 'Data area berhasil dihapus.');
    }
}
