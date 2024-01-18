<?php

namespace App\Models;

use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tache extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];
    protected $hidden = ['taskable_id', 'taskable_type'];


    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}
