<?php

namespace DoubleC\LaravelShopify\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DoubleC\LaravelShopify\Http\Requests\InstallAppRequest;
use DoubleC\LaravelShopify\Traits\Authenticator;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    use Authenticator;

    /**
     * Login with sudo
     * @param InstallAppRequest $request
     * @return RedirectResponse
     */
    public function sudoLogin(InstallAppRequest $request): RedirectResponse
    {
        $this->logout();
        return $this->login($request->get('shop'), true);
    }
}
