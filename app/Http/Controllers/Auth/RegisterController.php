<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
            'password'  => $this->cryptPassword($password),
        ]);

        $text = 'Ваш пароль ' . $password;
        $text .= ' ссылка на подтверждение ' . request()->getHttpHost() . '/confirm/' . $user->id . '/' . md5($user->email . getenv('APP_KEY'));

        Mail::send('mailtest', ['text' => $text], function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Пожалуйста, подтвердите ваш аккаунт');
        });

        Auth::login($user, true);

        return $user;
    }


    public function resetPassword(Request $request)
    {
        $password = $this->generatePassword();

        $email = $request->input('email');
        if(!$email){
            return 'error';
        }

        $user = User::where('email', $email)->first();
        $user->password = $this->cryptPassword($password);
        $user->save();

        $text = 'Ваш новый пароль ' . $password;

        Mail::send('mailtest', ['text' => $text], function ($message) use($email) {
            $message->to($email);
            $message->subject('pass recovery');
        });
    }

    private static function generatePassword()
    {
        $alphabet    = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';
        $pass        = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 6; $i++) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    private static function cryptPassword($password) {
        return bcrypt($password);
    }

}
