<?php

namespace App\Models;

use App\Libraries\HasPictures;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory, HasPictures;
    protected $appends = ['picture_url'];
    protected $guarded = [];
    protected $hidden = ['pivot','created_at','updated_at'];

    public function translations(): HasMany {
        return $this->hasMany(Translation::class);
    }

    public function candidates(): BelongsToMany {
        return $this->belongsToMany(Candidate::class,'candidate_language', 'language_id', 'candidate_id');
    }

    public function resumes(): HasMany {
        return $this->hasMany(CurriculumVitae::class);
    }

    public function getCandidateIdAttribute() {
        return 0;
    }
}
