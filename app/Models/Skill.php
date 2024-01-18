<?php

namespace App\Models;

use App\Libraries\HasPictures;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory, HasPictures , HasTranslations;
    protected $with = ['pictures'];
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function candidates(): BelongsToMany {
        return  $this->belongsToMany(Candidate::class,'candidate_skill', 'skill_id', 'candidate_id');
    }

    public function getCandidateIdAttribute() {
        return 0;
    }
}
