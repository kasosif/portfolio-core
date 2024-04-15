<?php

namespace App\Models;

use App\Libraries\HasDeletingProcesses;
use App\Libraries\HasPictures;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SocialAccount extends Model
{
    use HasFactory, HasPictures, HasDeletingProcesses;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at', 'pictures', 'pivot'];
    protected $appends = ['picture_url', 'link'];

    public function candidates(): BelongsToMany {
        return  $this->belongsToMany(Candidate::class,'candidate_social_account', 'social_account_id', 'candidate_id');
    }

    public function getCandidateIdAttribute() {
        return 0;
    }

    public function getLinkAttribute() {
        return $this->pivot ? $this->pivot->link : null;
    }
}
