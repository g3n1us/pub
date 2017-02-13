<?php
	
namespace G3n1us\Pub\Controllers\Auth;	

use App\Http\Controllers\Auth\LoginController;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use SocialAccountService;
use UserPolicy;

/**
 * Provide login services for the Pub applications
 */
class PubLoginController extends LoginController {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
	
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function handleProviderCallback(SocialAccountService $service, $provider)
    {
// 	    dd(Socialite::driver($provider)->user());
		$providerUser = Socialite::driver($provider)->user();
/*
	    $validemail = ends_with($providerUser->getEmail(), ['mediadc.com', 'weeklystandard.com', 'washingtonexaminer.com', 'redalertpolitics.com']);
	    if(!$validemail) {
		    return redirect()->to("/login")->withErrors(['message' => 'Your email is not in an approved domain']);
	    }
*/
	    

        $user = $service->createOrGetUser($provider, $providerUser);

        auth()->login($user);
// 		return Redirect::intended($this->redirectTo);
        return redirect()->intended($this->redirectTo);
	    
/*
	    
        $user = Socialite::driver($provider)->user();
		dd($user);
        // $user->token;
        return redirect($this->redirectTo);
*/
    }
	
	
}
	