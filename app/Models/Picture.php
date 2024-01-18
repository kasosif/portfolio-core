<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Picture extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['galleriable_id', 'galleriable_type', 'created_at','updated_at','path'];
    protected $appends = ['url','public_url'];
    protected $casts = ['main' => 'boolean', 'public' => 'boolean'];

    /**
     * Get the parent commentable model (post or video).
     */
    public function galleriable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string {
        return url('api/v1/pictures/download/'. $this->id);
    }
    public function getPublicUrlAttribute(): string | null {
        if ($this->public) {
            return url('api/v1/cdn/pictures/'.$this->id);
        }
        return null;
    }
}
