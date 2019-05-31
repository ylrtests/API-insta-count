<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator, DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

/**
 * Tutorial: 
 * https://medium.com/mesan-digital/tutorial-5-how-to-build-a-laravel-5-4-jwt-authentication-api-with-e-mail-verification-61d3f356f823
 */


class AuthController extends Controller
{

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);

        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
        
        // $credentials['is_verified'] = 1;
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'message' => 'El nombre de usuario y la contraseña que ingresaste no coinciden con nuestros registros. Por favor, revisa e inténtalo de nuevo.']);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'message' => 'Error iniciando sesión, intente de nuevo']);
        }
        // all good so return the token
        return response()->json([
            'success' => true, 
            'token'=>  $token,
            'message'  => 'Se ha iniciado sesión correctamente.'
            ]);
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        //return response()->json(['success' => $request, 'message'=> "You have successfully logged out."]);
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true, 'message'=> "Has cerrado sesión correctamente."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'message' => 'Ups! Algo salio mal, vuelve a intentarlo.'], 500);
        }
    }

    

    /**
     * API Get the user by token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request){

        try{
            $user = JWTAuth::parseToken()->authenticate();
        }
        catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'success'=> false, 
                'message'=> 'fallo en el token: '+$e
                ]);
        }
        if($user){
            return response()->json([
                'success'=> true, 
                'user'=> $user,
                'message' => "Usuario autenticado."
                ]);
        }
        else{
            return response()->json([
                'success'=> false, 
                'message'=> 'no se encontró el usuario'
                ]);
        }
        
    }

}