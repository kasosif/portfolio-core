<?php

namespace App\Models;

use App\Libraries\HasPictures;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory, HasPictures, HasTranslations;

    protected $guarded = [];

    public function socialAccounts(): HasMany {
        return $this->hasMany(SocialAccount::class);
    }

    public function resumes(): HasMany {
        return $this->hasMany(CurriculumVitae::class);
    }

    public function activities(): HasMany {
        return $this->hasMany(Activity::class);
    }

    public function testimonies(): HasMany {
        return $this->hasMany(Testimony::class);
    }

    public function educations(): HasMany {
        return $this->hasMany(Education::class);
    }

    public function experiences(): HasMany {
        return $this->hasMany(Experience::class);
    }

    public function certificates(): HasMany {
        return $this->hasMany(Certificate::class);
    }

    public function skills(): BelongsToMany {
        return  $this->belongsToMany(Skill::class,'candidate_skill', 'candidate_id', 'skill_id');
    }

    public function projects(): HasMany {
        return  $this->hasMany(Project::class);
    }

    public function contactRequests(): HasMany {
        return  $this->hasMany(Project::class);
    }


}