<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Use "name" as username field.
     */
    public function username()
    {
        return 'name';
    }

    /**
     * Validate login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate(
            [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ],
            [
                $this->username().'.required' => 'El usuario es obligatorio.',
                'password.required' => 'La contrasena es obligatoria.',
            ]
        );
    }

    /**
     * Return JSON response for popup login flow.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'mess' => 'Inicio de sesion exitoso.',
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Return clear Spanish error for failed login.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $message = 'Usuario o contrasena incorrectos.';
        $user = User::where($this->username(), $request->input($this->username()))->first();
        if ($user && empty($user->password)) {
            $message = 'Esta cuenta no tiene contrasena configurada. Contacta al administrador.';
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'mess' => $message,
            ], 422);
        }

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }
}
