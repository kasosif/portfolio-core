<?php

namespace App\Models;

use App\Libraries\HasDeletingProcesses;
use App\Libraries\HasPictures;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Candidate extends Model
{
    use HasFactory, HasPictures, HasTranslations, HasDeletingProcesses;
    protected $with = ['pictures', 'socialAccounts'];
    protected $appends = ['picture_url'];

    protected $guarded = [];

    protected $hidden = ['activated'];

    public function socialAccounts(): BelongsToMany {
        return $this->belongsToMany(SocialAccount::class,'candidate_social_account', 'candidate_id', 'social_account_id')->withPivot(['link']);
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
        return $this->belongsToMany(Skill::class,'candidate_skill', 'candidate_id', 'skill_id')
            ->withPivot(['percentage','icon_only'])
            ->using(SkillPivot::class);
    }

    public function projects(): HasMany {
        return  $this->hasMany(Project::class);
    }

    public function contactRequests(): HasMany {
        return  $this->hasMany(Project::class);
    }

    public function languages(): BelongsToMany {
        return $this->belongsToMany(Language::class,'candidate_language', 'candidate_id', 'language_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getCandidateIdAttribute() {
        return $this->attributes['id'];
    }

}
