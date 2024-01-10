<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumVitae extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
