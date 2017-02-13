<?php

namespace G3n1us\Pub\Services;


use Laravel\Socialite\Contracts\User as ProviderUser;

use SocialAccount;

use User;

class SocialAccountService
{
		
    public function createOrGetUser($providerHandle, ProviderUser $providerUser){
	    
        $account = SocialAccount::whereProvider($providerHandle)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
	        $account->metadata = $providerUser;
	        $account->email = $providerUser->getEmail();
	        $account->name = $providerUser->getName();
	        $account->nickname = $providerUser->getNickname();
	        $account->avatar = $providerUser->getAvatar();
	        $account->save();
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
                'nickname' => $providerUser->getNickname(),
                'provider' => $providerHandle,
                'metadata' => $providerUser
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();
            
            

            if (!$user) {
				$username = snake_case($providerUser->getName());
				$existingusernames = User::where('username', $username)->count();
				$i = 1;
				while($existingusernames > 0){
					$username = $username.$i;
					$i++;
					$existingusernames = User::where('username', $username)->count();	
		   
				}
	            
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'username' => $username,
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}