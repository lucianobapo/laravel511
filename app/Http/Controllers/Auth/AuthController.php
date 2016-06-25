<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Traits\TraitSocialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;
    use TraitSocialite;

//    protected $loginPath = '/laravel/orders';
    protected $redirectTo = '/laravel/orders';
    protected $redirectAfterLogout = '/laravel/orders';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->loginPath = route('auth.getLogin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Get a validator for an incoming social user request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorSocialUser(array $data)
    {
        $socialProviders = implode(',', config('auth.socialLogin'));
        $providerRequired = empty($socialProviders)?'laravel':'laravel,'.$socialProviders;
        return Validator::make($data, [
            'provider_name' => 'required|in:'.$providerRequired,
            'provider_id' => 'required|numeric',
//            'name' => 'required_without:provider|max:255',
            'email' => 'required|email|max:255|unique:users',
//            'password' => 'required_without:provider|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ];
        if (isset($data['mandante'])) $attributes['mandante'] = $data['mandante'];
        if (isset($data['role_id'])) $attributes['role_id'] = $data['role_id'];
        return User::create($attributes);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin($host=null)
    {
        return view('auth.login',is_null($host)?[]:compact('host'));
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister($host=null)
    {
        return view('auth.register',is_null($host)?[]:compact('host'))->with([
            'roles'=> Role::lists('name','id'),
        ]);
    }

    /**
     * @param string $provider
     * @param $socialUser
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function processSocialUser($provider, $socialUser)
    {
        $userFromDatabase = User::where([
            'provider_name' => $provider,
            'provider_id' => $socialUser->getId(),
        ])->first();

        if (is_null($userFromDatabase)) {
            $validatorSocialUser = $this->validatorSocialUser([
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'email' => $socialUser->getEmail(),
            ]);

            if ($validatorSocialUser->fails()) {
                return redirect()->to('/')
                    ->withErrors($validatorSocialUser->getMessageBag()->get('email'));
            }

            $attributes = [
                'mandante' => 'ilhanet',
//                'role_id' => null,
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(config('services.' . $provider . '.client_id')),
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ];
//            if (isset($data['mandante'])) $attributes['mandante'] = $data['mandante'];
//            if (isset($data['role_id'])) $attributes['role_id'] = $data['role_id'];
            $userFromDatabase = User::create($attributes);
        }

        Auth::guard($this->getGuard())->login($userFromDatabase);

        return redirect($this->redirectPath());
    }
}
