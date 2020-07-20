<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Clients\AlphaSmsClient;
use App\User;
use App\Models\UserResetPassword;

class ResetPasswordSmsController extends Controller
{

    public function sendCodeMessage(Request $request, AlphaSmsClient $alphaSmsClient)
    {
        $phone = preg_replace("/[^0-9]/", '', $request->phone);
        $user = User::where('phone', '=', $request->phone)->first();
        abort_if(!$user, 422, 'Користувача з таким номером не існує!');
        $verificationCode = random_int(10000, 99999);
        $alphaSmsClient = new AlphaSmsClient(env('ALPHA_SMS_API_LOGIN'), env('ALPHA_SMS_API_PASSWORD'), env('ALPHA_SMS_API_KEY'));
        $smsStatus = $alphaSmsClient->sendSMS('BESTLEASING', $phone, "Нікому не повідомляйте код для зміни паролю $verificationCode");
        $userResetPassword = new UserResetPassword;
        $userResetPassword->user_id = $user->id;
        $userResetPassword->verification_code = "$verificationCode";
        $userResetPassword->save();

        return response()->json([
            'userId' => $user->id
        ]);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->post();
        $userResetPassword = UserResetPassword::where('user_id', '=', $data['userId'])
                    ->where('is_active', '=', 1)
                    ->orderBy('created_at', 'desc')->first();

        if($userResetPassword->verification_code === $data['verificationCode'])
        {
            $user = User::find($data['userId']);
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
            $userResetPassword->update([
                'is_active' => false
            ]);
            $response = [
                'status' => true,
                'massage' => 'Пароль успішно змінено!'
            ];
        }else{
            $response = [
                'status' => false,
                'massage' => 'Невірний код!'
            ];
        }

        return response()->json($response);
    }
}
