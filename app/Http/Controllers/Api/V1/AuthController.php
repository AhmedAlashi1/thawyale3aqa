<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Models\AppUser;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\InvalidClaimException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\PayloadException;
use \Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Config;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException as TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException as TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException as TokenInvalidException;
use App\Helpers\Functions;
use Auth;
use App\Repositories\AppUsersRepository;
use Illuminate\Support\Facades\Http;

class AuthController extends ApiController
{
    use Functions;
    protected $repo;

    /**
     * AuthController constructor.
     * @param Request $request
     * @param AppUsersRepository $repo
     */
    public function __construct(Request $request, AppUsersRepository $repo)
    {
        parent::__construct($request);
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request)
    {
            if (empty($request->input('mobile_number'))) {
                return $this->outApiJson(false, 'data_required');
            }
            $user= AppUser::where('mobile_number',$request->input('mobile_number'))->first();
            if ($user){
                $user->password = bcrypt($request->input('mobile_number'));
                $user->save();
            }


        try {
            // verify the credentials and create a token for the user
                if (!$token = JWTAuth::attempt(['mobile_number' => $request->input('mobile_number'),
                    'password' => $request->input('mobile_number')])) {

                    return $this->outApiJson(false, 'number_not_registered');
                }
                $user = Auth::user();
        } catch (JWTException $e) {
            // something went wrong
            return $this->outApiJson(false,'could_not_create_token');
        }
        if ($request->input('mobile_number') == '0096512345678' or $request->input('mobile_number') == '0096555558718' ) {

            $activation_code = 1234;
        } else {
//            $activation_code = 1234;
            $activation_code = rand(1111, 9999);
        }

//        $activation_code = rand(1111, 9999);
       $user->activation_code = $activation_code;
       $deviceToken = $request->input('device_token')
           ?: $request->input('fcm_token')
           ?: $request->input('firebase_token');
       if ($deviceToken){
           $user->device_token = $deviceToken;
       }
       if ($request->filled('device_type')) {
           $user->device_type = $request->input('device_type');
       }

        $user->save();
        $user = AppUser::find($user->id) ?: $user;
        $sentVia = $this->sendLoginActivationCode($user, $activation_code, $request->input('mobile_number'));

        $userdata = [
            'user_id' => $user->id,
            'token' => $token,
            'mobile' => $user->mobile_number,
            'activation_code' => $activation_code,
            'sent_via' => $sentVia,
        ];
        return $this->outApiJson(true,'success',$userdata);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        if (isset($request->token)) {
            return $this->outApiJson(false,'invalid_token');
        }
        try {
            if (!$token = JWTAuth::parseToken()->refresh()) {
                return $this->outApiJson(false,'user_not_found');
            }
        } catch (TokenExpiredException $e) {
            return $this->outApiJson(false,'token_expired');
        } catch (TokenInvalidException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (JWTException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (InvalidClaimException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (PayloadException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (TokenBlacklistedException $e) {
            return $this->outApiJson(false,'invalid_token');
        }

        if (!$user = JWTAuth::setToken($token)->authenticate()) {
            return $this->outApiJson(false,'user_not_found');
        }
        $userdata = [
            'token' => $token,
        ];
        return $this->outApiJson(true,'success',$userdata);
        // the token is valid and we have found the user via the sub claim
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        if (empty($request->input('email'))) {
            return $this->outApiJson(false,'data_required');
        }
        // get user email id
        $user = $this->repo->findWhere(['email' => $request->input('email')])->first();
        if (!$user) {
            return $this->outApiJson(false,'user_not_found');
        }
        try {
            $code = rand(11111, 99999);
            $this->sendEmail('emails.forget_pass', ['name'=>$user->full_name,'email'=>$request->input('email'),'code'=>$code], 'forget password', [$user->email]);
            $user->password_code=$code;
            $user->save();
            return $this->outApiJson(true,'success');
        } catch (\PDOException $e) {
            // something went wrong
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        if (
        empty($request->input('password_code'))
        ) {
            return $this->outApiJson(false,'activation_code_missing');
        }
        $user = $this->repo->findWhere(['password_code' => $request->input('password_code')])->first();
        if(!$user){
            return $this->outApiJson(false,'activation_code_invalid');
        }
        $activationCode = $request->input('password_code');
        $code = intval($activationCode);
        if (!preg_match("/^[0-9]{5}$/", $code)) {
            return $this->outApiJson(false,'activation_code_invalid');
        }

        if ($user->password_code != $activationCode) {
            return $this->outApiJson(false,'activation_code_wrong');
        }
        $user->password_code = '';
        try {
            if ($user->save()) {
                $token=JWTAuth::fromUser($user);
                $userdata = [
                    'user_id' => $user->id,
                    'token' => $token,
                ];
                return $this->outApiJson(true,'success',$userdata);
            } else {
                return $this->outApiJson(false,'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePass(Request $request)
    {
        if (
        empty($request->input('password'))
        ) {
            return $this->outApiJson(false,'data_required');
        }

        $this->user->password = \Hash::make($request->input('password'));
        try {
            if ($this->user->save()) {
                return $this->outApiJson(true,'success');
            } else {
                return $this->outApiJson(false,'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function logout(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (empty($user)){

                return $this->outApiJson(false, 'user_not_found');
            }
            /*if ($this->user->send_notification == 1){
                $this->user->old_token = $this->user->device_token;
                $this->user->send_notification = 0;
                $this->user->device_token = 'logout';
                $this->user->save();
            }else{
                $this->user->send_notification = 1;
                $this->user->device_token = $this->user->old_token;
                $this->user->save();
            }*/
            $user->device_token = 'logout';
            $user->save();
            //JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            // something went wrong
            return $this->outApiJson(false,'invalid_token');
        }
        return $this->outApiJson(true,'success');
    }
    public function whatsappOld($phone , $bode){


        $params=array(
            'token' => 'lnmjhc6925uud3ek',
            'to' => $phone,
            'body' =>$bode,

        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance56092/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
//        else {
//            echo $response;
//        }

    }

    public function whatsapp($phone, $body ,$dedupKey = null)
    {
//        $instanceId   = $user->whatsapp_instance_id ?? config('services.wawp.instance_id');
//        $accessToken  = $user->whatsapp_token ?? config('services.wawp.access_token');     // access_token\

        $instanceId= '8D30ABB1E6DA';
        $accessToken = 'rhS3eDMYV7goCg';

        $chatId = $this->formatWawpChatId($phone, $dedupKey);

        //$url = "https://wawp.net/wp-json/awp/v1/send";
        $url = "https://api.wawp.net/v2/send/text";
        $response = Http::timeout(20)->post($url, [
            'instance_id'   => $instanceId,
            'access_token'  => $accessToken,
            'chatId'        => $chatId,
            'message'       => $body,
        ]);


        return $response;
    }

    protected function formatWawpChatId(string $phone, $dedupKey = null): string
    {
        $phone = trim($phone);

        // لو أصلاً جايك chatId جاهز من webhook
        if (str_contains($phone, '@c.us') || str_contains($phone, '@g.us') || str_contains($phone, '@lid')) {
            return $phone;
        }

        // شيل كل شيء غير أرقام
        $digits = preg_replace('/\D+/', '', $phone);

        // لو بدأ بـ 00 (مثل 00970...) احذفها
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits =  substr($digits, 1);
        }

        // الآن صار E.164 بدون +
        return $digits . '@c.us';
    }

}
