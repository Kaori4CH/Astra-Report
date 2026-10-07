<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
#[Table('dealers')]
class Dealer extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function isInUse(): bool
    {
        return $this->users()->exists() || $this->submissions()->exists();
    }
}
