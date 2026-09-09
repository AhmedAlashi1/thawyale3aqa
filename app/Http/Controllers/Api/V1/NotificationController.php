<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AppUser;
use App\Models\Clothes;
use App\Models\Country;
use App\Models\Notification;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\NotificationRepository;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;
class NotificationController extends ApiController
{

    use Functions;
    private $repo;

    public function __construct(Request $request, NotificationRepository $repo)
    {
        parent::__construct($request);
        $this->repo = $repo;
    }

    public function getData(Request $request)
    {

        $user = auth('api')->user();
//            return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        $id =  $user->id;
            $length = ($request->input('count')) ? $request->input('count') : 10;


            $notifications = Notifications::where(function ($q) use ($id) {
                $q->where('user_id', $id);
                $q->orWhere('user_id', 0);
            })->where('created_at', '>=',  $user->created_at)
                ->where('status',1)
                ->with('category')
                ->orderBy('id','desc')->paginate($length);

        $notifications_unread= Notifications::where(function ($q) use ($id) {
            $q->where('user_id', $id);
            $q->orWhere('user_id', 0);
        })->where('created_at', '>=',  $user->created_at)
            ->where('status',0)
            ->with('category')
            ->orderBy('id','desc')->get();
//        return $notifications;


            $data=[];
            //read
             $title= $request->header('lang') == 'ar' ? 'title_ar' : 'title_en';
            foreach($notifications->items() as $kk=>$item){
                $data[$kk]['id']=$item->id;
                $data[$kk]['created_at']=$item->created_at->diffForHumans();
                $data[$kk]['title']=$item->title;
                $data[$kk]['message']=$item->message;
                $data[$kk]['read']=(int)$item->status;
                $data[$kk]['ads_id']=$item->product_id;
                $data[$kk]['url']=$item->url;
                $data[$kk]['cat_id']=$item->cat_id;
                $data[$kk]['views']=$item->views;

                $data[$kk]['cat_title']=(string)$item->category ? $item->category->$title : null;
                $data[$kk]['profile_id']=$item->profile_id;


            }

        $data_unread=[];
        foreach($notifications_unread as $kk=>$item){
            $data_unread[$kk]['id']=$item->id;
            $data_unread[$kk]['created_at']=$item->created_at->diffForHumans();
            $data_unread[$kk]['title']=$item->title;
            $data_unread[$kk]['message']=$item->message;
            $data_unread[$kk]['read']=$item->status;
            $data_unread[$kk]['ads_id']=$item->product_id;
            $data_unread[$kk]['url']=$item->url;
            $data_unread[$kk]['cat_id']=$item->cat_id;
            $data_unread[$kk]['views']=$item->views;
            $data_unread[$kk]['cat_title']=$item->category ? $item->category->$title : null;
            $data_unread[$kk]['profile_id']=$item->profile_id;

        }

        $data_all=[
            'un_read' => $notifications_unread->count(),
            'count_total' => $notifications->total(),
            'nextPageUrl' => $notifications->nextPageUrl(),
            'pages'=>ceil($notifications->total()/$length),
            'data'=>[
                'unread'=>$data_unread,
                'read'=>$data,
            ]
        ];


            return $this->outApiJson(true,'success',$data_all);

    }


    public function read(Request $request,$id)
    {

        $user = auth('api')->user();
//            return $user;
        if (empty($user)) {

            return $this->outApiJson(false, 'user_not_found');
        }
            try {
            $item= Notifications::where('id',$id)->first();
            if(!$item){
                return $this->outApiJson(false,'not_found');
            }
            $item->status = 1;
            $item->save();
            return $this->outApiJson(true,'success');
        } catch (\Exception $ex) {
            return $this->outApiJson(false,'unexpected_error');
        } catch (\PDOException $ex){
            return $this->outApiJson(false,'pdo_exception');
        }

    }

    public function delete(Request $request,$id)
    {
        try {
            Notifications::where('id',$id)->delete();
            return $this->outApiJson(true,'success');
        } catch (\Exception $ex) {
            return $this->outApiJson(false,'unexpected_error');
        } catch (\PDOException $ex){
            return $this->outApiJson(false,'pdo_exception');
        }

    }

    public function chat(Request $request){

        $user = auth('api')->user();
        $sender_id=AppUser::find($request->sender_id);

        if (empty($user ) || empty($sender_id )) {

            return $this->outApiJson(false, 'user_not_found');
        }
        $device_token = AppUser::where('id', $request->sender_id)->pluck('device_token')->first();

        if ($request->type == 1){
            $title='تطبيق سوق';
            $body='يوجد رسالة جديدة';

            $notification=$this->notification($device_token,$title,$body,null,$user->id,$request->sender_id,$request->chat_id);
        }else{
            $title='تطبيق سوق';
            $body='يوجد متابعة جديدة';
            $notification=$this->notification($device_token,$title,$body,null,$user->id,$request->sender_id,$request->chat_id);
        }

        $notification = new Notifications();
        $notification->title = $title;
        $notification->user_id = $user->id;
        $notification->sender_id =$request->sender_id;
        $notification->message = $body;
        $notification->url = null;
        $notification->chat_id = $request->chat_id ?? "";
        $notification->type = $request->type;
        $notification->status = '0';
        $notification->save();
        return $this->outApiJson(true,'success');


    }
    public function views(Request $request){

        $notification = Notifications::where('id',$request->id)->first();
        if(!$notification){
            return $this->outApiJson(false,'not_found');
        }
        $notification->views = $notification->views + 1;
        $notification->save();
        return $this->outApiJson(true,'success');


    }

    public function notification($FcmToken = [], $title = "", $body = "", $url = "",$sender_id='',$user_id ='',$chat_id = '')
    {
        $data = [
            "to" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "url" => $url,
                "user_id" => $user_id,
                "sender_id" => $sender_id,
                "chat_id" => $chat_id,
                "sound" => "default"
            ],
            "data" => [
                "title" => $title,
                "body" => $body,
                "url" => $url,
                "sender_id" => $sender_id,
                "chat_id" => $chat_id,
                "sound" => "default"
            ],
            'priority' =>  "high",
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
            return response()
            ->json(['status' => 'success', 'errors' => 0,
            'data' => json_decode($response, true)])
            ->header('Content-type', 'application/json');
    }

}
