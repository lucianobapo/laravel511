<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 22/08/15
 * Time: 10:51
 */

namespace app\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OAuth2Verify {
    public function verify($username, $password) {
        if(Auth::attempt([
            'email' => $username,
            'password' => $password], false, false)){

            if (!is_null($user = User::where('email', $username)->first()))
                return $user->id;
            else
                return false;
        } else return false;
    }
}