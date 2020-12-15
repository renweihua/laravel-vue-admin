<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Friendlink;

class FriendlinkService extends BaseService
{
    public function __construct(Friendlink $friendlink)
    {
        $this->model = $friendlink;
    }
}
