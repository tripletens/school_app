<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use App\Validations\AuthValidators;
use App\Validations\ErrorValidation;

use Illuminate\Support\Facades\Auth;

use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['login', 'register'],
        ]);
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidators::validate_rules($request, 'register');

            if (!$validate->fails() && $validate->validated()) {
                try {
                    $register = User::create([
                        'full_name' => $request->full_name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                    ]);

                    if ($register) {
                        return ResponseHelper::success_response(
                            'Registration was successful',
                            null
                        );
                    } else {
                        return ResponseHelper::error_response(
                            'Registration failed, Database insertion issues',
                            $validate->errors(),
                            401
                        );
                    }
                } catch (Exception $e) {
                    return ResponseHelper::error_response(
                        'Server Error',
                        $e->getMessage(),
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['full_name', 'username', 'email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
        }
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidators::validate_rules($request, 'login');

            if (!$validate->fails() && $validate->validated()) {
                if (
                    $token = Auth::guard('api')->attempt([
                        'email' => $request->email,
                        'password' => $request->password,
                    ])
                ) {
                    $token = $this->respondWithToken($token);
                    $user = $this->me();

                    return ResponseHelper::success_response(
                        'Login Successfull',
                        $user,
                        $token
                    );
                } else {
                    return ResponseHelper::error_response(
                        'Invalid login credentials',
                        null,
                        401
                    );
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);
                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        }
    }

    public function me()
    {
        $user = auth()->user();

        $role_data = Role::where('id', $user->role)->exists()
            ? Role::where('id', $user->role)
                ->get()
                ->first()
            : null;
        if ($role_data != null) {
            $user->role_name = $role_data->name;
            $user->role_slug = $role_data->slug;
        }

        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>
                auth()
                    ->factory()
                    ->getTTL() * 60,
        ]);
    }
}
