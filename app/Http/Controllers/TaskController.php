<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Area;
use App\Models\Department;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $departmentId = $request->query('department_id');
        $areaId = $request->query('area_id');

        $tasks = Task::query()
            ->with(['department', 'area', 'creator'])
            ->withCount('submissions')
            ->when($search, fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($departmentId, fn ($query, $departmentId) => $query->where('department_id', $departmentId))
            ->when($areaId, fn ($query, $areaId) => $query->where('area_id', $areaId))
            ->latest('due_at')
            ->latest('id')
            ->simplePaginate(10)
            ->withQueryString();

        return view('tasks.index', [
            'title' => 'Astra Report - Daftar Tugas',
            'tasks' => $tasks,
            'departments' => Department::orderBy('name')->get(),
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('tasks.create', [
            'title' => 'Astra Report - Tambah Tugas',
            'departments' => Department::orderBy('name')->get(),
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Task::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Task $task): View
    {
        $task->load(['department', 'area', 'creator']);

        return view('tasks.show', [
            'title' => 'Astra Report - Detail Tugas',
            'task' => $task,
            'submissions' => $task->submissions()->with('dealer')->orderBy('submitted_at')->get(),
        ]);
    }

    public function edit(Task $task): View|RedirectResponse
    {
        if ($task->isLocked()) {
            return redirect()->route('tasks.show', $task)
                ->with('error', 'Tugas sudah dikumpulkan dealer, tidak dapat diubah.');
        }

        return view('tasks.edit', [
            'title' => 'Astra Report - Edit Tugas',
            'task' => $task,
            'departments' => Department::orderBy('name')->get(),
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateRequest $request, Task $task): RedirectResponse
    {
        if ($task->isLocked()) {
            return redirect()->route('tasks.show', $task)
                ->with('error', 'Tugas sudah dikumpulkan dealer, tidak dapat diubah.');
        }

        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        if ($task->isLocked()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Tugas sudah dikumpulkan dealer, tidak dapat dihapus.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
