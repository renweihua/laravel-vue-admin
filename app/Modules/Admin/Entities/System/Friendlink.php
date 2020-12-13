<?php

namespace App\Modules\Admin\Entities\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendlink extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \App\Modules\Admin\Database\factories\System/FriendlinkFactory::new();
    }
}
