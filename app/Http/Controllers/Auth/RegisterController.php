<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\UserCustom;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'firstname' => ['required', 'string', 'max:255'],
                    'lastname' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'address1' => ['required', 'string'],
                    'address2' => ['string'],
                    'city' => ['required', 'string'],
                    'province' => ['required', 'string'],
                    'postal' => ['required', 'string'],
                    'phone' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
        return User::create([
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'address1' => $data['address1'],
                    'address2' => $data['address2'],
                    'city' => $data['city'],
                    'province' => $data['province'],
                    'postal' => $data['postal'],
                    'phone' => $data['phone'],
        ]);
    }

    protected function registered(\Illuminate\Http\Request $request, $user) {
        UserCustom::create([
            'user_id' => $user->id,
            'customfield1' => 'Custom Field 1', 'customfield2' => 'Custom Field 2', 'customfield3' => 'Custom Field 3',
            'customfield4' => 'Custom Field 4', 'customfield5' => 'Custom Field 5', 'customfield6' => 'Custom Field 6',
            'customfield7' => 'Custom Field 7', 'customfield8' => 'Custom Field 8', 'customfield9' => 'Custom Field 9',
            'customfield10' => 'Custom Field 10',
            'notelabel1' => 'General Notes',
            'notelabel2' => 'Prospecting Notes',
            'notelabel3' => 'Personal Notes',
        ]);
    }

}
