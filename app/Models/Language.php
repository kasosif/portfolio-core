<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function translations(): HasMany {
        return $this->hasMany(Translation::class);
    }

    public function candidates(): BelongsToMany {
        return $this->belongsToMany(Candidate::class,'candidate_language', 'language_id', 'candidate_id');
    }

    public function resumes(): HasMany {
        return $this->hasMany(CurriculumVitae::class);
    }
}
