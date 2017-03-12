<?php

namespace G3n1us\Pub\Controllers\Auth;	

use App\Http\Controllers\Auth\ResetPasswordController as BaseResetPasswordController;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends BaseResetPasswordController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


}
