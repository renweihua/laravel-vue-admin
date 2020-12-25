<?php

namespace App\Modules\Admin\Entities\System;

use App\Models\Model;
use Illuminate\Support\Facades\Storage;

class Friendlink extends Model
{
    protected $primaryKey = 'link_id';
    protected $is_delete = 0;

    public function getLinkCoverAttribute($key)
    {
        if (empty($key)) return $key;
        return Storage::url($key);
    }

    public function setLinkCoverAttribute($key)
    {
        if (!empty($key)) {
            $this->attributes['link_cover'] = str_replace(Storage::url('/'), '', $key);
        }
    }
}
