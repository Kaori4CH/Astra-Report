<?php

use App\Enums\SubmissionStatus;
use App\Models\Area;
use App\Models\Dealer;
use App\Models\Department;
use App\Models\Submission;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeSupervisor(): User
{
    return User::factory()->supervisor()->create();
}

function makeDealerUser(): User
{
    $dealer = Dealer::create(['code' => 'DLP-'.fake()->unique()->numerify('###'), 'name' => 'Dealer Uji']);

    return User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
}

function makeTask(User $creator, array $overrides = []): Task
{
    return Task::create(array_merge([
        'title' => 'Laporan Bulanan',
        'department_id' => Department::firstOrCreate(['code' => 'DEP-1'], ['name' => 'Sales'])->id,
        'area_id' => Area::firstOrCreate(['code' => 'ARA-1'], ['name' => 'Jakarta'])->id,
        'due_at' => now()->addDays(3)->toDateString(),
        'created_by' => $creator->id,
    ], $overrides));
}

function makeSubmission(Task $task, User $dealerUser, SubmissionStatus $status): Submission
{
    return Submission::create([
        'task_id' => $task->id,
        'dealer_id' => $dealerUser->dealer_id,
        'drive_link' => 'https://drive.google.com/file/d/abc',
        'status' => $status,
        'submitted_at' => now(),
    ]);
}

const DRIVE_LINK = 'https://drive.google.com/drive/folders/abc123';

test('tamu diarahkan ke login dan peran lain mendapat 403', function () {
    $this->get(route('tasks.index'))->assertRedirect('/login');

    $this->actingAs(makeDealerUser())->get(route('tasks.index'))->assertForbidden();
    $this->actingAs(makeDealerUser())->get(route('dealers.index'))->assertForbidden();
    $this->actingAs(makeSupervisor())->get(route('submissions.index'))->assertForbidden();
});

test('supervisor mengelola data dealer', function () {
    $supervisor = makeSupervisor();

    $this->actingAs($supervisor)
        ->post(route('dealers.store'), ['code' => 'DLP-031', 'name' => 'Dealer Baru'])
        ->assertRedirect(route('dealers.index'));

    $dealer = Dealer::where('code', 'DLP-031')->firstOrFail();

    $this->actingAs($supervisor)
        ->put(route('dealers.update', $dealer), ['code' => 'DLP-031', 'name' => 'Dealer Diubah'])
        ->assertRedirect(route('dealers.index'));
    expect($dealer->fresh()->name)->toBe('Dealer Diubah');

    $this->actingAs($supervisor)
        ->post(route('dealers.store'), ['code' => 'DLP-031', 'name' => 'Duplikat'])
        ->assertSessionHasErrors('code');

    $this->actingAs($supervisor)->delete(route('dealers.destroy', $dealer))->assertRedirect();
    expect(Dealer::count())->toBe(0);
});

test('tugas menyimpan pembuatnya dan tanggal default bisa dipakai', function () {
    $supervisor = makeSupervisor();
    $department = Department::create(['code' => 'D1', 'name' => 'Sales']);
    $area = Area::create(['code' => 'A1', 'name' => 'Jakarta']);

    $this->actingAs($supervisor)->post(route('tasks.store'), [
        'title' => 'Laporan Penjualan',
        'department_id' => $department->id,
        'area_id' => $area->id,
        'due_at' => now()->toDateString(),
    ])->assertRedirect(route('tasks.index'));

    expect(Task::first()->created_by)->toBe($supervisor->id);
});

test('tugas yang sudah dikumpulkan tidak bisa diubah atau dihapus', function () {
    $supervisor = makeSupervisor();
    $task = makeTask($supervisor);
    makeSubmission($task, makeDealerUser(), SubmissionStatus::Menunggu);

    $this->actingAs($supervisor)
        ->put(route('tasks.update', $task), [
            'title' => 'Judul Baru',
            'department_id' => $task->department_id,
            'area_id' => $task->area_id,
            'due_at' => now()->addDay()->toDateString(),
        ])
        ->assertSessionHas('error');
    expect($task->fresh()->title)->toBe('Laporan Bulanan');

    $this->actingAs($supervisor)->delete(route('tasks.destroy', $task))->assertSessionHas('error');
    expect(Task::count())->toBe(1);
});

test('dealer mengumpulkan tugas dan log tercatat', function () {
    $task = makeTask(makeSupervisor());
    $dealerUser = makeDealerUser();

    $this->actingAs($dealerUser)
        ->post(route('submissions.store', $task), ['drive_link' => DRIVE_LINK, 'note' => 'Terlampir'])
        ->assertRedirect(route('submissions.show', $task));

    $submission = Submission::firstOrFail();
    expect($submission->status)->toBe(SubmissionStatus::Menunggu);
    expect($submission->logs()->first()->activity)->toBe('Kumpul');
});

test('link pengumpulan harus dari google drive', function () {
    $task = makeTask(makeSupervisor());

    $this->actingAs(makeDealerUser())
        ->post(route('submissions.store', $task), ['drive_link' => 'https://example.com/file'])
        ->assertSessionHasErrors('drive_link');

    expect(Submission::count())->toBe(0);
});

test('dealer hanya bisa kumpul ulang saat status revisi', function () {
    $task = makeTask(makeSupervisor());
    $dealerUser = makeDealerUser();
    $submission = makeSubmission($task, $dealerUser, SubmissionStatus::Menunggu);

    $payload = ['drive_link' => DRIVE_LINK];

    // menunggu pemeriksaan: ditolak
    $this->actingAs($dealerUser)->post(route('submissions.store', $task), $payload)->assertSessionHas('error');
    expect($submission->logs()->count())->toBe(0);

    // revisi: diterima, status kembali menunggu, log "Pengumpulan Ulang"
    $submission->update(['status' => SubmissionStatus::Revisi]);
    $this->actingAs($dealerUser)->post(route('submissions.store', $task), $payload)->assertSessionHas('success');
    expect($submission->fresh()->status)->toBe(SubmissionStatus::Menunggu);
    expect($submission->logs()->first()->activity)->toBe('Pengumpulan Ulang');
    expect(Submission::count())->toBe(1);
});

test('dealer tidak bisa kumpul ulang setelah disetujui atau ditolak', function (SubmissionStatus $status) {
    $task = makeTask(makeSupervisor());
    $dealerUser = makeDealerUser();
    $submission = makeSubmission($task, $dealerUser, $status);

    $this->actingAs($dealerUser)
        ->post(route('submissions.store', $task), ['drive_link' => DRIVE_LINK])
        ->assertSessionHas('error');

    expect($submission->logs()->count())->toBe(0);
})->with([SubmissionStatus::Disetujui, SubmissionStatus::Ditolak]);

test('dealer tidak bisa mengumpulkan setelah deadline', function () {
    $task = makeTask(makeSupervisor(), ['due_at' => now()->subDay()->toDateString()]);

    $this->actingAs(makeDealerUser())
        ->post(route('submissions.store', $task), ['drive_link' => DRIVE_LINK])
        ->assertSessionHas('error');

    expect(Submission::count())->toBe(0);
});

test('deadline hari ini masih bisa dikumpulkan', function () {
    $task = makeTask(makeSupervisor(), ['due_at' => now()->toDateString()]);

    $this->actingAs(makeDealerUser())
        ->post(route('submissions.store', $task), ['drive_link' => DRIVE_LINK])
        ->assertSessionHas('success');
});

test('supervisor memeriksa tugas dan aktivitas dicatat', function (string $status, string $activity) {
    $supervisor = makeSupervisor();
    $submission = makeSubmission(makeTask($supervisor), makeDealerUser(), SubmissionStatus::Menunggu);

    $this->actingAs($supervisor)
        ->put(route('reviews.update', $submission), ['status' => $status, 'note' => 'Catatan'])
        ->assertRedirect(route('reviews.show', $submission));

    expect($submission->fresh()->status->value)->toBe($status);
    expect($submission->logs()->first()->activity)->toBe($activity);
    expect($submission->logs()->first()->note)->toBe('Catatan');
})->with([
    ['REVISI', 'Supervisor Minta Revisi'],
    ['DISETUJUI', 'Supervisor Menyetujui Pengumpulan'],
    ['DITOLAK', 'Supervisor Menolak Hasil Pekerjaan'],
]);

test('supervisor tidak bisa memilih status di luar tiga pilihan', function () {
    $supervisor = makeSupervisor();
    $submission = makeSubmission(makeTask($supervisor), makeDealerUser(), SubmissionStatus::Menunggu);

    $this->actingAs($supervisor)
        ->put(route('reviews.update', $submission), ['status' => 'MENUNGGU'])
        ->assertSessionHasErrors('status');
});
