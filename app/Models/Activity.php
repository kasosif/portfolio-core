<?php

namespace App\Models;

use App\Libraries\Draftable;
use App\Libraries\HasDeletingProcesses;
use App\Libraries\HasPictures;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory, HasPictures, HasTranslations, Draftable, HasDeletingProcesses;
    protected $with = ['pictures'];
    protected $appends = ['picture_url'];
    protected $guarded = [];
    protected $hidden = ['candidate_id'];
    protected $casts = ['draft' => 'boolean'];


    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
