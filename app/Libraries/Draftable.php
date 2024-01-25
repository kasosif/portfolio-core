<?php
namespace App\Libraries;

trait Draftable {

    public function scopePublished($query){
        return $query->where('draft' , false);
    }

    public function scopeDraft($query){
        return $query->where('draft' , true);
    }
}
