<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->where('reset_token', $request->token)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The credential you entered are incorrect or token expired.']
            ]);
        }

        $user['reset_token'] = '';
        $user['password'] = Hash::make($request->password);
        $user->save();
        return response()->json(array("message" => 'Password reset successfully.', "data" => array()), 200);
    }
}
