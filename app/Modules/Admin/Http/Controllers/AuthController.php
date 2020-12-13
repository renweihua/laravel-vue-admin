<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Requests\LoginRequest;
use App\Modules\Admin\Services\AuthService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends AdminController
{
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // 登录流程
        $token = $this->service->login($data);

        return $this->successJson($token);
    }

    public function me()
    {

    }

    public function logout()
    {

    }
}
