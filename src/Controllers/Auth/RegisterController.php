<?php
namespace G3n1us\Pub\Controllers\Auth;	

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
	
class RegisterController extends Controller{
	
    use RegistersUsers;
    
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