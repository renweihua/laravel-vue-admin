<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Version;

class VersionService extends BaseService
{
    public function __construct(Version $version)
    {
        $this->model = $version;
    }
}
