<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreRequest;
use App\Http\Requests\Department\UpdateRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $departments = Department::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('code')
            ->simplePaginate(10)
            ->withQueryString();

        return view('departments.index', [
            'title' => 'Astra Report - Daftar Departemen',
            'departments' => $departments,
        ]);
    }

    public function create(): View
    {
        return view('departments.create', [
            'title' => 'Astra Report - Tambah Departemen',
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()->route('departments.index')->with('success', 'Data departemen berhasil ditambahkan.');
    }

    public function show(Department $department): View
    {
        return view('departments.show', [
            'title' => 'Astra Report - Detail Departemen',
            'department' => $department,
        ]);
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', [
            'title' => 'Astra Report - Edit Departemen',
            'department' => $department,
        ]);
    }

    public function update(UpdateRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('departments.index')->with('success', 'Data departemen berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->isInUse()) {
            return redirect()->route('departments.index')
                ->with('error', 'Data departemen tidak dapat dihapus karena masih dipakai oleh tugas.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Data departemen berhasil dihapus.');
    }
}
