<?php

namespace App\Modules\Admin\Entities\System;

use App\Models\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $primaryKey = 'banner_id';
    protected $is_delete = 0;

    public function getBannerCoverAttribute($key)
    {
        if (empty($key)) return $key;
        return Storage::url($key);
    }

    public function setBannerCoverAttribute($key)
    {
        if (!empty($key)) {
            $this->attributes['banner_cover'] = str_replace(Storage::url('/'), '', $key);
        }
    }
}
