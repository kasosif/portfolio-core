<?php

namespace App\Models;

use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    use HasFactory , HasTranslations;

    protected $guarded = [];
    protected $casts = ['current' => 'boolean'];
    protected $hidden = ['candidate_id'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function tasks(): HasMany {
        return $this->hasMany(ExperienceTask::class);
    }
}
