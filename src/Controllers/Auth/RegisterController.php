<?php
namespace G3n1us\Pub\Controllers\Auth;	

use App\Http\Controllers\Auth\RegisterController as BaseRegisterController;

	
class RegisterController extends BaseRegisterController{
	    
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('pub::auth.pub_register');
    }    
    
    protected $redirectTo = '/dashboard';
    

}