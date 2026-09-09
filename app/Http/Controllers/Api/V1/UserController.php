<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PusherApp;
use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Advertisements;
use App\Models\AppUser;
use App\Models\BlockUser;
use App\Models\Clothes;
use App\Models\Notification;
use App\Models\PackageOrder;
use App\Models\FixedAds;
use App\Models\Order;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\ReportUser;
use App\Models\Follow;
use App\Models\Governorates;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Setting;
use App\Models\SmsGate;
use App\Models\SmsLog;
use App\Models\TokenFirebase;
use App\Repositories\AppUsersRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Helpers\SmsGateways;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpseclib3\Crypt\Hash;
use App\Http\Controllers\ApiController;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Twilio;
use Laravel\Socialite\Facades\Socialite;
//use Laravel\Socialite\Facades\
//use Socialite;

class UserController extends ApiController
{
    use Functions;

    private $repo;
    private $gates;

    /**
     * UserController constructor.
     * @param Request $request
     * @param AppUsersRepository $repo
     * @param \App\Repositories\SmsGatesRepository $gates
     */
    public function __construct(Request $request, AppUsersRepository $repo, \App\Repositories\SmsGatesRepository $gates)
    {
//        parent::__construct($request);

        $this->repo = $repo;
        $this->gates = $gates;
    }

    public function register(Request $request)
    {

//            $request->validate([
//                'mobile_number'=>'required|phone:sa,kw,qa,ae,om,bh'
//                ]);

            if (
                empty($request->input('mobile_number'))
            ) {
                return $this->outApiJson(false, 'data_required');
            }
//            return $request->all();

            if (!ctype_digit($request->input('mobile_number'))) {
                return $this->outApiJson(false, 'mobile_invalid');
            }
            if ($request->input('mobile_number') == '0096512345678' or $request->input('mobile_number') == '0096555558718' ) {

                $activation_code = 1234;
            } else {
//            $activation_code = 1234;
                $activation_code = rand(1111, 9999);
            }

            if ($request->input('mobile_number')[0] == '0' and $request->input('mobile_number')[1] == '0'){
                $str = ltrim($request->input('mobile_number'),$request->input('mobile_number')[1]);
            }else{
                $str=$request->input('mobile_number');
            }

        $data = [];


            $data['mobile_number'] =$request->input('mobile_number');
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('mobile_number'));
            $data['activation_code'] = $activation_code;
            $data['status'] = 'pending_activation';
            $data['device_token'] = $request->input('device_token');
            $data['device_type'] = $request->input('device_type');
            $data['first_name'] = $request->input('name');
            $data['last_name'] = $request->input('last_name');
            $data['country_id'] = $request->input('country_id');
            $data['commercial_name'] = $request->input('commercial_name');
            $data['email'] = $request->input('email');
            $data['address'] = $request->input('address');
            $data['cat_id'] = $request->input('cat_id');
            $data['region_id'] = $request->input('region_id');
            $data['type'] = $request->input('type');
            $data['ip_address'] = request()->ip();

        try {

                $user = AppUser::where(['mobile_number' => $request->input('mobile_number')])->first();


            if (!$user) {

                $user = AppUser::create($data);
            } else {
                return $this->outApiJson(false, 'mobile_exist');
                /*if ($user->type == $request->type){
                    $user->update($data);
                }else{
                    return $this->outApiJson(false, 'mobile_exist');
                }*/

            }

            $credentials=['mobile_number' => $request->input('mobile_number'), 'password' => $request->input('mobile_number'),'disabled'=>0];

                $token = auth('api')->attempt($credentials);

                $this->sendLoginActivationCode($user, $activation_code, $str);

            $userdata = [
                'user_id' => $user->id,
                'token' => $token,
            ];

            return $this->outApiJson(true, 'success', $userdata);

        } catch (JWTException $e) {
            return $this->outApiJson(false, 'could_not_create_token');
        } catch (\PDOException $ex) {
//            dd($ex);
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function tokenFcm(Request $request){
        $user = auth('api')->user();

        if ($user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        $data['device_token'] = $request->input('device_token');
        $user->update($data);
        $userdata = [
            'user_id' =>$user->id,
            'device_token' => $data['device_token'],
        ];
        return $this->outApiJson(true, 'success', $userdata);

    }
    public function activateAccount(Request $request)
    {
        $user =  Auth('api')->user();
//        return  $user;

        if (empty($user)){
            return $this->outApiJson(false, 'user_not_found');
        }
        if (
            empty($request->input('activation_code'))
        ) {
            return $this->outApiJson(false, 'activation_code_missing');
        }


        //check user inactive
//        if ($user->status == 'inactive') {
//            return $this->outApiJson(false, 'user_inactive');
//        }

        // check device serial

//        if (empty($user->activation_code) || $user->status == 'active') {
//            return $this->outApiJson(false, 'user_already_activated');
//        }

        $activationCode = $request->input('activation_code');
        $code = intval($activationCode);
        if (!preg_match("/^[0-9]{4}$/", $code)) {
            return $this->outApiJson(false, 'activation_code_invalid');
        }

        $activation_code=Setting::where('key_id','activation_code')->first();

        if ($activationCode == $activation_code->value){

//            $user->activation_code = '';
            $user->status = 'active';
            $user->save();
            $userdata = [
                'user_id' =>  $user->id,
                'mobile' =>  $user->mobile_number,
                'first_name' =>  $user->first_name,
                'last_name' =>  $user->last_name,
                'address' =>  $user->address,
                'avatar' => asset("assets/tmp/" .  $user->avatar),
            ];
            return $this->outApiJson(true, 'success', $userdata);
        }

//        return $user;
        if ($user->activation_code != $activationCode) {
            return $this->outApiJson(false, 'activation_code_wrong');
        }

//        $user->activation_code = '';
        $user->status = 'active';
        try {
            if ( $user->save()) {
                $userdata = [
                    'user_id' =>  $user->id,
                    'mobile' =>  $user->mobile_number,
                    'first_name' =>  $user->first_name,
                    'last_name' =>  $user->last_name,
                    'address' =>  $user->address,
                    'avatar' => asset("assets/tmp/" .  $user->avatar),
                ];
                return $this->outApiJson(true, 'success', $userdata);
            } else {
                return $this->outApiJson(false, 'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function resendActivation(Request $request)
    {
        $user = auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

//        $mesage=Twilio::message('+201090730088', 'test');
//        dd($mesage);
//        check user inactive
//        if ( $user->status == 'inactive') {
//            return $this->outApiJson(false, 'user_inactive');
//        }

//        if (empty( $user->activation_code) ||  $user->status == 'active') {
//            return $this->outApiJson(false, 'user_already_activated');
//        }
        $activation_code=Setting::where('key_id','activation_code')->first();
        // check user max resend count
        if ( $user->resend_code_count >= $activation_code->value) {
            return $this->outApiJson(false, 'exceed_activition_code');
        }
         $user->status = 'pending_activation';
         $user->resend_code_count =  $user->resend_code_count + 1;
        try {
            if ( $user->save()) {
                $this->sendLoginActivationCode($user, $user->activation_code, $user->mobile_number);
//                $gate = $this->gates->getNextGate(0);
//                $gate = SmsGate::where('sort_order', '>', 0)
//                    ->orderBy('sort_order', 'asc')
//                    ->first();
//                if (!$gate) {
//                    $gate = SmsGate::where('sort_order', '>', 0)
//                        ->orderBy('sort_order', 'asc')
//                        ->first();
//                }
//                SmsGateways::send($gate, $message,  $user->mobile_number);
                $userdata = [
                    'resend_code_count' =>  $user->resend_code_count,
                ];
                return $this->outApiJson(true, 'success', $userdata);
            } else {
                return $this->outApiJson(false, 'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }
    public function updateInfo(Request $request)
    {

        $user = auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        if($user->type == 2){

            $rules =
            [
                'email' => "required|email|unique:app_users,email,".$user->id,
            ];

            $validation = Validator::make($request->all(),$rules);

            if($validation->fails()){

                return $this->outApiJson(false, 'email_exists',$validation->errors()->first());

            }
        }

        $user->commercial_name = $request->input('commercial_name');
        $user->first_name = $request->input('name');
         $user->email = $request->input('email');
         $user->mobile_number = $request->input('mobile_number');
         $user->whats_number = $request->input('whats_number');
        $user->description = $request->input('description');
        $user->work_hours = $request->input('work_hours');
        $user->work_hours2 = $request->input('work_hours2');
        $user->holiday = $request->input('holiday');
        $user->land_number = $request->input('land_number');


        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['gif', 'jpg', 'jpeg', 'png'])) {
                return $this->outApiJson(false, 'allow_extention_error');
            }
            $destinationPath = 'assets/tmp';
            $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
             $user->avatar = $fileName;
        }
//        try {
            if ( $user->save()) {
//                $user;
                $userdata = [
                    'user_id' =>  $user->id,
                    'mobile_number' =>  $user->mobile_number,
                    'commercial_name' =>  $user->commercial_name,
                    'name' =>  $user->first_name,
                    'email' =>  $user->email,
                    'description' =>  $user->description,
                    'whats_number' =>  $user->whats_number,
                    'work_hours' =>  $user->work_hours,
                    'work_hours2' => $user->work_hours2,
                    'holiday' => $user->holiday,
                    'land_number' => $user->land_number,
                    'avatar' => asset("assets/tmp/" .  $user->avatar),

                ];
                return $this->outApiJson(true, 'success', $userdata);
            } else {
                return $this->outApiJson(false, 'update_error');
            }
//        } catch (\PDOException $ex) {
//            return $this->outApiJson(false, 'pdo_exception');
//        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $users=null;
        try{
            $users = auth('api')->user();
        }catch (JWTException $e) {

        }
        if(!$users){
            abort(401);
        }
        $user = AppUser::where('id',$users->id)->with('categories')->first();
        $length = ($request->input('count')) ? $request->input('count') : 10;

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        $title = 'title_'.$request->header('lang');
        $region = null;

//        if ( $user->addres) {
//            if ( $user->addres->regionData) {
//                $country_id=Governorates::where('id',$user->addres->city_id)->first();
//                $region = [
//                    'id' =>  $user->addres->regionData->id,
//                    'title' =>  $user->addres->regionData->$title,
//                    'delivery_cost' =>  $user->addres->regionData->delivery_cost,
//                    'order_limit' =>  $user->addres->regionData->order_limit,
//                    'country_id' => $country_id->country_id,
//
//                ];
//            }
//        }
        $country=Country::where('id',$user->country_id)->where('status','1')->first();

        $clothes=Advertisements::where('user_id',$user->id)->count();
        $clothes_user= Advertisements::query();
        $clothes_user->where('status','1')
            ->where('user_id',$user->id)
            ->with('user','country')->orderBy('id','desc');

        $clothes_users = $clothes_user->paginate(10);
        $ddd = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');
//        return $clothes_users;
        foreach($clothes_users->items() as $k=>$row){
            $ddd[$k]['id'] = $row->id;
            $ddd[$k]['title'] = $row->$title;
            $ddd[$k]['note'] = $row->$note;
            $ddd[$k]['price'] = $row->price;
            $ddd[$k]['cat_id'] = (string)$row->cat_id;


            // $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;


            $ddd[$k]['views'] = (int)$row->views;


//            if($users) {
//                $ddd[$k]['fav'] = ($row->favorites->where('user_id', $users->id) ->count() > 0)?true: false;
//            }
//            $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
              $extension = pathinfo($row->image, PATHINFO_EXTENSION);
              $ddd[$k]['type_image']=$extension;

              if ($extension == 'mp4'){
                  $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->cover;
              }else{
                    $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
              }


        }

        //order
        $now=Carbon::now();

        $repose = PackageOrder::where('user_id',$user->id)
            ->where(['payment_status'=>'1','status'=>'1','type'=>'2',])
            ->whereDate('end_at','>',$now)
            ->orderBy('id', 'DESC')->first();
//        return $repose;


        $user_data = [
            'user_id' =>  $user->id,
            'mobile' =>  $user->mobile_number,
            'email' =>  $user->email,
            'name' =>  $user->first_name,
            'commercial_name' =>  $user->commercial_name,
            'country_id' =>  (int)$user->country_id,
            'cat_name' => ($user->categories)?  $user->categories->$title : null,
            'avatar' => asset("assets/tmp/" .  $user->avatar),
            'region' => $region,
            'whats_number' => $user->whats_number,
            'description' => $user->description,
            'work_hours' => $user->work_hours,
            'work_hours2' => $user->work_hours2,
            'holiday' => $user->holiday,
            'land_number' => $user->land_number,
            'exp_at' => $user->exp_at,
            'type' => (int)$user->type,
            'fix_user' => $repose ? true : false,
            'advertisements' => $clothes,

        ];
        $data=[
            'user_data' => $user_data,
            'advertisements'=>[
                'data'=>$ddd,
                'count_total' => $clothes_users->total(),
                'nextPageUrl' => $clothes_users->nextPageUrl(),
                'pages'=>ceil($clothes_users->total()/$length),
            ],


        ];
        return $this->outApiJson(true, 'success', $data);

    }

    public function getUser(){

        $user = AppUser::where('type',2)
        ->whereNotNull('work_hours')

        ->get();
        $data = [];
        foreach ($user as $users){
            $data[] = [
                'id' => $users->id,
                'work_hours' => $users->work_hours,
            ];
        }
        return $data;
    }


        public function reportUser(Request $request){
            $user = auth('api')->user();

            if (empty($user)){

                return $this->outApiJson(false, 'user_not_found');
            }

            if(empty($request->input('user_id'))){
                return $this->outApiJson(false,'data_required');
            }

//            $add=Fav::where(['user_id'=> $user->id,'charity_id'=>$request->input('id')])->first();
//            if($add){
//                return $this->outApiJson(false,'fav_exists');
//            }
            $data=[
                'user_id'=>$request->input('user_id'),
                'customer_id'=> $user->id,
                'message'=> $request->message,
            ];

            try{

                $repose=ReportUser::create($data);

                if ($repose) {
                    return $this->outApiJson(true,'success');
                }
                return $this->outApiJson(false,'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false,'pdo_exception');
            }
        }

    public function BlockUser(Request $request)
    {
        $user = auth('api')->user();

        if (empty($user)) {

            return $this->outApiJson(false, 'user_not_found');
        }

        if (empty($request->input('user_id'))) {
            return $this->outApiJson(false, 'data_required');
        }

        $add = BlockUser::where(['customer_id' => $user->id, 'user_id' => $request->input('user_id')])->first();
        if ($add) {
            return $this->outApiJson(false, 'fav_exists');
        }
            $data = [
                'user_id' => $request->input('user_id'),
                'customer_id' => $user->id,

            ];

            try {

                $repose = BlockUser::create($data);

                if ($repose) {
                    return $this->outApiJson(true, 'success');
                }
                return $this->outApiJson(false, 'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false, 'pdo_exception');
            }
        }
        public function deleteUser(){
            $user = auth('api')->user();

            if (empty($user)) {

                return $this->outApiJson(false, 'user_not_found');
            }

            try {

                $repose = AppUser::where('id', $user->id)->delete();
                $order= Order::where('user_id', $user->id)->delete();
//                $repose->disabled = 1;
//                $repose->deleted_at = date('Y-m-d H:i:s');
//                $repose->save();
                if ($repose) {
                    return $this->outApiJson(true, 'success');
                }
                return $this->outApiJson(false, 'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false, 'pdo_exception');
            }

        }

        public function connection_count(Request $request){


            $repose = AppUser::where('id', $request->user_id)->first();
            if (empty($repose)) {

                return $this->outApiJson(false, 'user_not_found');
            }

            try {

                $repose->connection += 1;
                $repose->save();
                if ($repose) {
                    return $this->outApiJson(true, 'success');
                }
                return $this->outApiJson(false, 'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false, 'pdo_exception');
            }

        }
    public function OrderPackage(Request $request){
        $user=auth('api')->user();
//        return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if ($request->input('package_id')) {

            $Packages = Packages::find($request->input('package_id'));
            if (!$Packages) {
                return $this->outApiJson(false, 'not_found');
            }

        }

        if ($request->input('type') == '2') {
            $now=Carbon::now();
            $country_id=$request->header('country');
            $fixa_doctor=PackageOrder::where(['payment_status'=>'1','status'=>'1','type'=>'2',])
                ->whereDate('end_at','>',$now)
                ->whereHas('user', function ($query) use ($country_id ,$request,$user){
////                    $query->where('country_id', $country_id);
//                    //category_id
                    $query->where('cat_id', $user->cat_id);
                })
                ->with('user')
//            ->inRandomOrder()
                ->orderBy('created_at', 'DESC')
                ->get();
//            return count($fixa_doctor);
            if ($fixa_doctor->count() > 4) {

                return $this->outApiJson(false, 'package_order');
            }

        }

        $total= $Packages->price;


        $now=Carbon::now()->format('Y-m-d H:i:s');
        $now2=Carbon::now()->addDays($Packages->days)->format('Y-m-d H:i:s');

        $order =new PackageOrder();

        $order->user_id=$user->id;
        $order->packages_id=$request->package_id;
        $order->start_at=$now;
        $order->end_at=$now2;
        $order->status='0';
        $order->payment_status='0';
        $order->type=$request->type;
        $order->advertisements_id=$request->advertisements_id;

        $order->save();
        $userdata = [
            'order_id' => $order->id,
        ];


        if ($total> 0)   {

            //upayment
            $sub = substr( $user->mobile_number, 0, 5);
            $number = substr( $user->mobile_number, 5);
            if ($sub == '00965') {
                $numbers = $number;
            } else {
                $numbers =  $user->mobile_number;
            }

            $headers = [
                'Authorization' => 'Bearer 96b88dfc6101d7b5983d6777b2db6193fd215156',
//                    'Authorization' => 'Bearer jtest123',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ];
            $client = new Client([
                'base_uri' => 'https://apiv2api.upayments.com/api/v1/',
                'headers' => $headers
            ]);

//            $pay = Payment::find($request->input('payment_id'));
//            if (!$pay) {
//                return $this->outApiJson(false, 'not_found');
//            }

//            if ($pay->id == 4){
//                        return request()->getHost();
                $response = $client->post('charge', [

                    'form_params' =>  [

                        'order' => [
                            "id" => $order->id,
                            "reference" => $order->id,
//                                    "description" => "Purchase order received for Logitech K380 Keyboard",
                            "currency" => "KWD",
                            "amount" => $total
                        ],

                        'paymentGateway' => [
//                            "src"=> $pay->payment_gateway,
                                "src"=> "knet"
//                                "src"=> "cc"
                        ],
                        "notificationType"=>"email",
                        "customerExtraData" => "",
                        "language"=> "en",
                        "isSaveCard"=> false,
                        "sessionId"=> "",
                        "is_whitelabled"=> true,

                        'tokens' => [
                            "creditCard"=> "",
                            "customerUniqueToken" => "8866268287"

                        ],
                        'reference' => [
                            "id"=> '202210101202210101'
                        ],
                        'customer' => [
                            "unique_id"=> $user->id,
                            "name"=>$user->first_name,
                            "email"=> $user->email ? $user->email : $numbers.'@admin.com',
                            "mobile"=> $numbers

                        ],
                        "returnUrl"=> route('ordersSuccess').'?order='. $order->id ,
//                        "returnUrl"=> 'https://www.thawielhimam.work/checkout/success?order='. $order->id ,
                        "cancelUrl"=>  route('ordersError').'?order='. $order->id ,
//                        "cancelUrl"=>  'https://www.thawielhimam.work/checkout/payment/error?order='. $order->id ,
                        "notificationUrl"=> "https://webhook.site/92eb6888-362b-4874-840f-3fff620f7cf4",
//                        'extraMerchantData' => [
//                            'amounts' => [
//                                $total,
//                            ],
//                            'commissions' => [
//                                0.25,
//                            ],
//                            'types' => [
//                                'fixed'
//                            ],
//                            'ibanNumbers' => [
//                                'KW28BBYN0000000000000625021003',
//                            ],
//                        ],
                        'plugin' => [
                            "src"=> null
                        ],
                        "ecwidData"=> null,
                        'device' => [
                            "browser"=> "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/93.0.0.0",
                            'browserDetails' => [
                                "screenWidth"=> "1920",
                                "screenHeight"=> "1080",
                                "colorDepth"=> "24",
                                "javaEnabled"=> "false",
                                "language"=> "en",
                                "timeZone"=> "-180",
                                "3DSecureChallengeWindowSize"=> "500_X_600"
                            ],

                        ],


                    ]

                ]);
//            }


            $jsonResponse = json_decode($response->getBody());
//                     return $jsonResponse;
//                    dd($jsonResponse);
            if ($jsonResponse->status == true) {
                $userdata['url'] = $jsonResponse->data->link;
            }
        }else{
            $type = $Packages->type;
            $orders=PackageOrder::where('id',$order->id)->first();

            $orders->payment_status = '1';
            $orders->status = '1';
            if ($type == 1){
                $user=AppUser::where('id',$order->user_id)->first();
                $user->exp_at = $order->end_at;
                $user->update();
            }
            $orders->update();
        }

        if ($order) {

            return $this->outApiJson(true,'success',$userdata);
        }
        return  $request->input('packege');

    }
    public function paymentStatus(Request $request)
    {


        if (empty($request->input('order_id'))) {
            return $this->outApiJson(false, 'data_required');
        }
        $repose = PackageOrder::find($request->input('order_id'));
//        return $repose;
        $type = $repose->type;
        $data = [];
        //type
        // 1 اشتراك
        // 2 تميز
        //3 اعلان

        if ($repose) {
//            return $repose->type;
            $repose->payment_status = $request->input('status');
            $repose->status = $request->input('status');

            if ($request->input('status') == 1) {
                if ($type == 1){
                    $user=AppUser::where('id',$repose->user_id)->first();
                    $user->exp_at = $repose->end_at;
                    $user->update();
                }elseif ($type == 3){
                    $advertisements=Advertisements::where('id',$repose->advertisements_id)->first();

                    $automatic_acceptance=Setting::where('key_id','automatic_acceptance')->first()->value;



                    $advertisements->end_date = $repose->end_at;
                    $advertisements->update();

                }

//                $emails1 = Setting::where('key_id', 'emails')->first()->value;
//                $emails3 = Setting::where('key_id', 'emails2')->first()->value;
//                $message = 'هناك طلب جديد في الطلبات الجديده';
//                $new_orders=Order::where('status','new')->where('payment_status','1')->whereNotNull('payment_status')->whereNull('deleted_at')->count();
//                $token = TokenFirebase::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                //                $this->notification($token, 'طلب جديد', $message, $repose->id,$new_orders);
//                Notification::create([
//                    'title'=>' طلب جديد',
//                    'days'=>$repose->id,
//                    'send_at'=>Carbon::now(),
//                    'message'=>$message,
//                ]);
//                if ($repose->lang == 'en'){
//                    \Mail::to($emails1)->send(new OrderMail($repose));
//                    \Mail::to($emails3)->send(new OrderMail($repose));
//                    \Mail::to($repose->user->email)->send(new OrderMail($repose));
//                }else{
//                    \Mail::to($emails1)->send(new OrderArMail($repose));
//                    \Mail::to($emails3)->send(new OrderArMail($repose));
//                    \Mail::to($repose->user->email)->send(new OrderArMail($repose));
//                }




//                \Mail::to('aalshy00@gmail.com')->send(new OrderMail($repose));
//                PusherApp::pushNotifications([
//                    'message' => 'هناك طلب جديد في الطلبات الجديده',
//                    'type' => 'cart-submitted',
//                    'id' => $repose->id,
//                    'user' => $repose->user->first_name,
//                    'total_cost' => $repose->total_cost,
//                    'notes' => $repose->notes,
//                    'city' => ($repose->address) ? $repose->address->city->title : '',
//                    'region' => ($repose->address) ? $repose->address->region->title : '',
//                ]);
            }else{
                if ($type == 3){
                    $advertisements=Advertisements::where('id',$repose->advertisements_id)->first();
                    $advertisements->status = '0';
//                    $advertisements->end_at = $repose->end_at;
                    $advertisements->update();

                }
            }
            $repose->save();
            return $this->outApiJson(true, 'success');
        }
        return $this->outApiJson(false, 'pdo_exception');
    }





    public function sms($mobile,$message){

        $username = env('SMS_USER_NAME');
        $password = env('SMS_PASSWORD');
        $sender = env('SMS_SENDER');
        $url = 'https://www.kwtsms.com/API/send/?username='.$username.'&password='.$password.'&sender='.$sender.'&mobile='.$mobile.'&lang=1&message='.$message;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ]);

        $result['content'] = curl_exec($ch);
        return $result;


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
