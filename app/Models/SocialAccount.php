<?php

namespace App\Models;

use App\Libraries\HasPictures;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    use HasFactory, HasPictures;

    protected $guarded = [];
    protected $hidden = ['candidate_id','created_at','updated_at', 'pictures'];
    protected $appends = ['picture_url'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
