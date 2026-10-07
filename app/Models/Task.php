<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'department_id', 'area_id', 'due_at', 'created_by'])]
#[Table('tasks')]
class Task extends Model
{
    protected function casts(): array
    {
        return [
            'due_at' => 'date',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Tugas yang sudah dikumpulkan dealer tidak boleh diubah atau dihapus.
     */
    public function isLocked(): bool
    {
        return $this->submissions()->exists();
    }

    /**
     * Deadline berupa tanggal: pengumpulan masih diterima sampai akhir hari deadline.
     */
    public function isPastDue(): bool
    {
        return now()->startOfDay()->greaterThan($this->due_at);
    }

    /**
     * Alasan dealer tidak boleh mengumpulkan, atau null jika boleh.
     */
    public function submissionBlockedReason(?Submission $existing): ?string
    {
        if ($this->isPastDue()) {
            return 'Batas waktu pengumpulan sudah lewat.';
        }

        if ($existing === null || $existing->status->allowsResubmission()) {
            return null;
        }

        return match ($existing->status->value) {
            'MENUNGGU' => 'Pengumpulan Anda sedang menunggu pemeriksaan supervisor.',
            'DISETUJUI' => 'Pengumpulan sudah disetujui, tidak dapat dikumpulkan lagi.',
            default => 'Pengumpulan sudah ditolak, tidak dapat dikumpulkan lagi.',
        };
    }
}
