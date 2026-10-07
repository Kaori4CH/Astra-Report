<?php

namespace App\Http\Controllers;

use App\Enums\SubmissionStatus;
use App\Http\Requests\Review\UpdateRequest;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pemeriksaan hasil tugas dealer oleh supervisor.
 */
class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $submissions = Submission::query()
            ->with(['task', 'dealer'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('task', fn ($q) => $q->where('title', 'like', "%{$search}%"))
                        ->orWhereHas('dealer', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest('submitted_at')
            ->simplePaginate(10)
            ->withQueryString();

        return view('reviews.index', [
            'title' => 'Astra Report - Pemeriksaan Tugas',
            'submissions' => $submissions,
            'statuses' => SubmissionStatus::cases(),
        ]);
    }

    public function show(Submission $submission): View
    {
        $submission->load(['task.department', 'task.area', 'dealer']);

        return view('reviews.show', [
            'title' => 'Astra Report - Periksa Tugas',
            'submission' => $submission,
            'options' => SubmissionStatus::reviewOptions(),
            'logs' => $submission->logs()->orderByDesc('created_at')->orderByDesc('id')->get(),
        ]);
    }

    public function update(UpdateRequest $request, Submission $submission): RedirectResponse
    {
        $status = SubmissionStatus::from($request->validated('status'));
        $note = $request->validated('note');

        $submission->update(['status' => $status]);
        $submission->addLog($request->user(), $status->reviewActivity(), $note);

        return redirect()->route('reviews.show', $submission)->with('success', 'Hasil pemeriksaan tersimpan.');
    }
}
