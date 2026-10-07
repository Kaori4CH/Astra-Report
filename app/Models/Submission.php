<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['task_id', 'dealer_id', 'drive_link', 'note', 'status', 'submitted_at'])]
#[Table('submissions')]
class Submission extends Model
{
    protected function casts(): array
    {
        return [
            'status' => SubmissionStatus::class,
            'submitted_at' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SubmissionLog::class);
    }

    public function addLog(User $user, string $activity, ?string $note): SubmissionLog
    {
        return $this->logs()->create([
            'user_id' => $user->id,
            'activity' => $activity,
            'note' => $note,
        ]);
    }
}
