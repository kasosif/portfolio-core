<?php

namespace App\Libraries;
use App\Models\Picture;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
trait HasPictures {

    public function pictures():MorphMany
    {
        return $this->morphMany(Picture::class, 'galleriable');
    }

    public function picture(): object | null
    {
        return $this->pictures()->where('main', true)->first();
    }

    public function setMainPicture(int $pictureId): void {
        $this->pictures()->update(['main' => false]);
        $this->pictures()->where('id', $pictureId)->update(['main' => true]);
    }

    public function addPicture(UploadedFile $picture, bool $isMain): void {
        $filename = Str::random(12). '.' . $picture->getClientOriginalExtension();
        $path = storage_path('app/pictures/'.Str::lower(Str::plural(get_class($this))).'/' . $this->getKey() .'/'. $filename );
        Image::make($picture)->save($path);
        $mainexists = $this->pictures()
            ->where('main',true)
            ->exists();
        $picture = new Picture([
            'main' => !$mainexists,
            'path' => $path,
            'name' => $filename
        ]);
        $picture = $this->pictures()->save($picture);
        if ($isMain) {
            $this->setMainPicture($picture);
        }
    }
    public function addPictures(array $pictures): void {
        foreach ($pictures as $key => $picture) {
            $this->addPicture($picture, $key === 0);
        }
    }

    public function deletePicture(int $pictureId): void {
        $picture = $this->pictures()->where('id', $pictureId)->first();
        if ($picture) {
            $path = storage_path('app/pictures/'.Str::lower(Str::plural(get_class($this))).'/' . $this->getKey() .'/'. $picture->name );
            if ( file_exists($path) ) {
                Storage::delete($path);
            }
            $picture->delete();
        }

    }
    public function deleteAllPictures(): void {
        foreach ($this->pictures()->get() as $picture) {
            $path = storage_path('app/pictures/'.Str::lower(Str::plural(get_class($this))).'/' . $this->getKey() .'/'. $picture->name );
            if ( file_exists($path) ) {
                Storage::delete($path);
            }
            $picture->delete();
        }
    }
}
