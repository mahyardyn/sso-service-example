<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Lib\Sms\Sms;
use App\Lib\Logger\Logger;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Send OTP code to mobile number
     *
     * @param AuthRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function otp(AuthRequest $request): JsonResponse
    {
        // Get mobile from input
        $mobile = (int)$request->input('mobile');

        // Get user IP
        $ip = $request->getClientIp();

        // Check mobile and IP exist
        if (app('redis')->get($mobile) or app('redis')->get($ip)) {

            $msg = app('redis')->get($mobile) ? 'Mobile number in redis exist!' : 'IP address in redis exist!';

            // Generate log
            Logger::set('error', 'otp', $msg);

            // Send 429 status code to Exception handler
            abort(429);
        }

        // Create code otp
        $code = rand(10000, 99999);

        // Create otp
        app('redis')->set($mobile, $code);
        app('redis')->set($ip, true);
        app('redis')->expire($mobile, 120);
        app('redis')->expire($ip, 120);

        // Send sms with OTP code
        $smsRes = (new Sms())->send($mobile, $code);

        // sms result message
        if ($smsRes) {
            $type = 'info';
            $smsMsg = 'The code was sent';
        } else {
            $type = 'error';
            $smsMsg = 'Failed to send code';
        }

        // Generate log
        Logger::set($type, 'otp', $smsMsg);

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'sms' => $smsRes, 'message' => __($smsMsg)]);
    }

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function login(AuthRequest $request): JsonResponse
    {
        // Get mobile from input
        $mobile = (int)$request->input('mobile');

        // Get code from input
        $code = $request->input('code');

        // Create new empty array for error messages
        $msg = array();

        // check if mobile number set in redis
        if (!app('redis')->get($mobile))
            $msg[] = 'Mobile number is not set';

        // check if entered code is correct
        if (app('redis')->get($mobile) !== $code)
            $msg[] = 'The entered code is invalid';

        // if any error message exist
        if ($msg) {
            // Generate log
            Logger::set('error', 'login', $msg);

            // Send 400 status code to Exception handler
            Abort(400, serialize($msg));
        }

        // Get updated user data
        $user = User::where('mobile', $mobile)->first();

        // Register new user
        if (!$user) {
            $user = User::create(['mobile' => $mobile]);

            // check first user for admin access permission
            if (User::all()->count() == 1) {
                $user->role = 'مدیر سیستم';
                $user->save();
            }

            // Generate log
            Logger::set('info', 'register', 'User successfully registered');
        }

        // Get JWT token
        $token = JWTAuth::fromUser($user);

        // Generate log
        Logger::set('info', 'login', 'User logged in successfully');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'message' => __('User logged in successfully.'), 'data' => $user, 'token' => $token]);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        // Generate log
        Logger::set('info', 'check-jwt', 'Authorized!');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'message' => __('Authorized'), 'data' => auth()->user()]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function update(AuthRequest $request): JsonResponse
    {
        // Get validated values
        $fields = $request->validated();

        // Update user
        auth()->user()->update($fields);

        // Get updated the user data
        $user = auth()->user();

        // Generate log
        Logger::set('info', 'update-profile', 'User updated successfully');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'message' => __('User updated successfully'), 'data' => $user]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // Invalidate the token (Logout user)
        auth()->logout();

        // Generate log
        Logger::set('info', 'logout', 'Successfully logged out!');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'message' => __('Successfully logged out')]);
    }

    /**
     * Change default locale to json locale
     *
     * @return void
     */
    protected function setDefaultLocale(): void
    {
        app('translator')->setLocale(config('app.json_locale'));
    }

//    /**
//     * Get the token array structure.
//     *
//     * @param string $token
//     *
//     * @return JsonResponse
//     */
//    protected function respondWithToken(string $token): JsonResponse
//    {
//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'bearer',
//            'expires_in' => auth()->factory()->getTTL() * 60
//        ]);
//    }
}
