<?php

namespace App\Modules\Public\Auth\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectedTo = "/admin/dashboard";

    public function __construct()
    {
        $this->middleware("guest")->except("logout");
    }

    /**
     * Show the application's login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        $title = __("Login");

        return view("Public::Auth.login");
    }
}
