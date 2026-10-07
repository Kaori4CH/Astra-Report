<?php

namespace App\Http\Controllers;

use App\Enums\SubmissionStatus;
use App\Http\Requests\Submission\StoreRequest;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Pengumpulan tugas oleh dealer.
 */
class SubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $dealerId = $this->dealerId($request);

        $tasks = Task::query()
            ->with(['department', 'area'])
            ->latest('due_at')
            ->latest('id')
            ->simplePaginate(10);

        $submissions = Submission::query()
            ->where('dealer_id', $dealerId)
            ->whereIn('task_id', $tasks->pluck('id'))
            ->get()
            ->keyBy('task_id');

        return view('submissions.index', [
            'title' => 'Astra Report - Tugas Saya',
            'tasks' => $tasks,
            'submissions' => $submissions,
        ]);
    }

    public function show(Request $request, Task $task): View
    {
        $submission = $this->findSubmission($task, $this->dealerId($request));

        return view('submissions.show', [
            'title' => 'Astra Report - Kumpulkan Tugas',
            'task' => $task->load(['department', 'area']),
            'submission' => $submission,
            'blockedReason' => $task->submissionBlockedReason($submission),
            'logs' => $submission?->logs()->orderByDesc('created_at')->orderByDesc('id')->get() ?? collect(),
        ]);
    }

    public function store(StoreRequest $request, Task $task): RedirectResponse
    {
        $dealerId = $this->dealerId($request);
        $existing = $this->findSubmission($task, $dealerId);

        if ($reason = $task->submissionBlockedReason($existing)) {
            return redirect()->route('submissions.show', $task)->with('error', $reason);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($request, $task, $dealerId, $existing, $validated) {
            $submission = $existing ?? new Submission(['task_id' => $task->id, 'dealer_id' => $dealerId]);

            $submission->fill([
                'drive_link' => $validated['drive_link'],
                'note' => $validated['note'] ?? null,
                'status' => SubmissionStatus::Menunggu,
                'submitted_at' => now(),
            ])->save();

            $submission->addLog(
                $request->user(),
                $existing ? 'Pengumpulan Ulang' : 'Kumpul',
                $validated['note'] ?? null,
            );
        });

        return redirect()->route('submissions.show', $task)->with('success', 'Tugas berhasil dikumpulkan.');
    }

    private function dealerId(Request $request): int
    {
        $dealerId = $request->user()->dealer_id;

        abort_unless($dealerId, 403, 'Akun Anda belum terhubung dengan dealer.');

        return $dealerId;
    }

    private function findSubmission(Task $task, int $dealerId): ?Submission
    {
        return $task->submissions()->where('dealer_id', $dealerId)->first();
    }
}
