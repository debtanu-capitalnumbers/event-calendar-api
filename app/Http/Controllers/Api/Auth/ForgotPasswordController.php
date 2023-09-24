<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The email you entered are incorrect.']
            ]);
        }

        $reset_token = Str::random(60);

        $user['reset_token'] = $reset_token;
        $user->save();

        try {
            Mail::to($request->email)->send(new ResetPassword($user->username, $reset_token));
            return response()->json(array("message" => 'Password reset link has been sent to your email', "data" => array()), 200);
        } catch (TransportException $ex) {
            return response()->json(array("status" => 400, "message" => $ex->getMessage(), "data" => []), 400);
        } catch (Exception $ex) {
            return response()->json(array("status" => 400, "message" => $ex->getMessage(), "data" => []), 400);
        }
    }
}
