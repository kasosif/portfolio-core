<?php

namespace App\Models;

use App\Libraries\HasPictures;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimony extends Model
{
    use HasFactory, HasPictures, HasTranslations;
    protected $with = ['pictures'];
    protected $guarded = [];
    protected $hidden = ['candidate_id'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
