<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Protocol;

class ProtocolService extends BaseService
{
    public function __construct(Protocol $protocol)
    {
        $this->model = $protocol;
    }
}
