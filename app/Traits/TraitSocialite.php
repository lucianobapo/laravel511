<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 21/06/16
 * Time: 22:24
 */

namespace App\Traits;

use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

trait TraitSocialite
{
    protected function callSocialiteDriver($provider)
    {
        $driver = Socialite::driver($provider);
        if ($provider=='google') {
            $driver->scopes([
                'https://www.googleapis.com/auth/userinfo.email',
                'https://www.googleapis.com/auth/plus.me',
                'https://www.googleapis.com/auth/userinfo.profile',
            ]);
        }
        if ($provider=='facebook') {
            $driver->fields([
                //public_profile
                'id',
                'name',
                'first_name',
                'last_name',
                'age_range',
                'link',
                'gender',
                'locale',
                'picture',
                'timezone',
                'updated_time',
                'verified',
                //email
                'email',
                'birthday',
                'friends',
            ]);
            $driver->scopes(['email','user_birthday','user_friends']);
        }
        return $driver;
    }
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return $this->callSocialiteDriver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = $this->callSocialiteDriver($provider)->user();

        return $this->processSocialUser($provider, $user);

        // OAuth Two Providers
//        $token = $user->token;
//        $refreshToken = $user->refreshToken; // not always provided
//        $expiresIn = $user->expiresIn;

        // OAuth One Providers
//        $token = $user->token;
//        $tokenSecret = $user->tokenSecret;

        // All Providers
//        $user->getId();
//        $user->getNickname();
//        $user->getName();
//        $user->getEmail();
//        $user->getAvatar();
    }

    /**
     * processSocialUser.
     *
     * @return Response
     */
    abstract protected function processSocialUser($provider, $user);
}