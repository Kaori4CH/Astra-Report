<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
#[Table('areas')]
class Area extends Model
{
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function isInUse(): bool
    {
        return $this->tasks()->exists();
    }
}
