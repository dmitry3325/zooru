<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
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
    protected $redirectTo = '/';

    public function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:mysql.auth.users,email',
            'password'  => 'required|string|min:6',
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     */
    protected function create(Request $request)
    {
        $data = $this->validate($request, $this->rules());

        $password = $data['password'];

        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'password'  => bcrypt($password),
        ]);

        Mail::send('mailtest', ['text' => 'Ваш пароль ' . $password], function($message) use ($data){
            $message->to($data['email']);
            $message->subject('Testing');
        });

        Auth::login($user, true);

        return $user;
    }

}
