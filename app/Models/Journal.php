<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $fillable = [
        'branch_id', 'reference_type', 'reference_id', 'date', 'description', 'created_by'
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'journal_id');
    }
}
