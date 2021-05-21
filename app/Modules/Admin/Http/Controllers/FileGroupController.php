<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Services\FileGroupService;

class FileGroupController extends BaseController
{
    public function __construct(FileGroupService $fileGroupService)
    {
        $this->service = $fileGroupService;
    }
}
