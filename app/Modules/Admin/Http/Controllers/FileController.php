<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Services\FileService;

class FileController extends BaseController
{
    public function __construct(FileService $fileService)
    {
        $this->service = $fileService;
    }
}
