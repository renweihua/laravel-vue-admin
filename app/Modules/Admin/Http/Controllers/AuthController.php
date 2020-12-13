<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Requests\LoginRequest;
use App\Modules\Admin\Services\AuthService;

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
        if (\request()->getMethod() == 'OPTIONS'){
            return $this->successJson();
        }

        return $this->successJson($this->service->me());
    }

    public function logout()
    {

    }
}
