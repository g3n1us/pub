<?php
namespace G3n1us\Pub\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController as BaseForgotPasswordController;

class ForgotPasswordController extends BaseForgotPasswordController{
	
    public function showResetForm(Request $request, $token = null)
    {
        return view('pub::auth.passwords.pub_reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }	
    
    public function showLinkRequestForm()
    {
        return view('pub::auth.passwords.pub_email');
    }
    
}