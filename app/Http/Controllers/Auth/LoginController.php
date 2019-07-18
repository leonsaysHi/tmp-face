<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;

/**
 * @codeCoverageIgnore
 * Testing the trait though
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function validateLogin(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 'password' => 'required',
        ]);

        $credentials = $this->credentials($request);

        $can_login = $this->guard()->attempt($credentials, $request->has('remember'));

        if ($can_login) {
            if ($user = \App\User::where('email', $request->get('email'))->first()) {
                $session = DB::table("sessions")->where("user_id", $user->id);
                if ($session) {
                    $session->delete();
                }
            }
        }
    }

    /**
     * Only allow one concurrent session per user
     *
     * @param \Illuminate\Http\Request $request
     * @param  User $user
     * @return Response
     */
    protected function authenticated(\Illuminate\Http\Request $request, User $user)
    {
        Session::put('user_id', $user->id);

        $files = array_diff(scandir(storage_path('framework/sessions')), ['.', '..', '.gitignore']);
        $token = Session::get('_token');

        foreach ($files as $file) {
            $filepath = storage_path('framework/sessions/' . $file);
            $session = unserialize(file_get_contents($filepath));

            if (isset($session['user_id']) && $session['user_id'] === $user->id && $session['_token'] !== $token) {
                unlink($filepath);
            }
        }

        return redirect()->intended($this->redirectPath());
    }
}
