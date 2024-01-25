<?php

namespace App\Models;

use App\Libraries\Draftable;
use App\Libraries\HasDeletingProcesses;
use App\Libraries\HasPictures;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory, HasPictures , HasTranslations, Draftable, HasDeletingProcesses;
    protected $with = ['tasks'];
    protected $guarded = [];
    protected $casts = ['draft' => 'boolean'];
    protected $appends = ['picture_url'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function tasks():MorphMany
    {
        return $this->morphMany(Tache::class, 'taskable');
    }
}
