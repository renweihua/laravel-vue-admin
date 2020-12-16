<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        return $this->successJson([]);
    }
}
