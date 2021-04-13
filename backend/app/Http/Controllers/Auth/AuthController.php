<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Hash;

class AuthController extends Controller
{
    /**
     * Log in using Laravel Sanctum
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        try {
            $this->validateLogin($request);

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }
            return $this->sendFailedLoginResponse($request);
            
        } catch (\Throwable $th) {
            return $this->sendLoginResponse($request);
        }
    }

    /**
     * Get the user info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request){
        $user = User::find($request->user()->id);
        //$user->load(['cliente', 'proveedor']);
        return $user;
    }

    /**
     * refresh the token issued
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request){
        $user = $request->user();
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
          ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return Auth::attempt($this->credentials($request));
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = User::where('rut', $request->rut)->first();
        if (!Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
        }
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json([
            'status_code' => 500,
            'message' => 'Error in Login',
        ], 500);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|digit',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'rut';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
}
