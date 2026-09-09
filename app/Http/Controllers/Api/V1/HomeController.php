<?php

namespace App\Http\Controllers\api\V1;

use App\Helpers\Functions;
use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Categories;
use App\Models\Fav;
use App\Models\PackageOrder;
use App\Models\Advertisements;
use App\Models\AppUser;
use App\Models\Charge;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Governorates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class HomeController extends Controller
{
    use Functions;
    private $shuffledResults = [];

    public function home(Request $request){


        $categories_business = Categories::where('status', '1')->where(['business'=> 1])->orderBy('sort_order', 'asc')->get();
        $categories_individuals = Categories::where('status', '1')->where(['business'=> null,'individuals'=> 1])->orderBy('sort_order', 'asc')->get();


        $ads_first=Ads::where('status', '1')->where('layout', '1')->with('categories')->limit(3)->get();
        $ads_second=Ads::where('status', '1')->where('layout', '2')->with('categories')->limit(3)->get();

        $title = 'title_' . $request->header('lang');

        $d= [];
        foreach ($categories_business as $k => $row) {
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->$title;
            $d[$k]['business'] = $row->business == 1 ? true : false;
            $d[$k]['individuals'] = $row->individuals == 1 ? true : false;
            $d[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;

        }
        $dd= [];
        foreach ($categories_individuals as $k => $row) {
            $dd[$k]['id'] = $row->id;
            $dd[$k]['title'] = $row->$title;
            $dd[$k]['business'] = $row->business == 1 ? true : false;
            $dd[$k]['individuals'] = $row->individuals == 1 ? true : false;
            $dd[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;

        }
        $ddd = [];
        foreach ($ads_first as $k => $row) {
            $ddd[$k]['id'] = $row->id;
            $ddd[$k]['lauout_title'] = $row->lauout_title;
            $ddd[$k]['status'] = $row->status;
            $ddd[$k]['url'] = $row->url;
            $ddd[$k]['user_id'] = $row->user_id;
            $ddd[$k]['cat_id'] = $row->cat_id;
            $ddd[$k]['cat_title'] = $row->categories ? $row->categories->$title : null;

            $ddd[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
        }
        $dddd = [];
        foreach ($ads_second as $k => $row) {
            $dddd[$k]['id'] = $row->id;
            $dddd[$k]['lauout_title'] = $row->lauout_title;
            $dddd[$k]['status'] = $row->status;
            $dddd[$k]['url'] = $row->url;
            $dddd[$k]['user_id'] = $row->user_id;
            $dddd[$k]['cat_id'] = $row->cat_id;
            $dddd[$k]['cat_title'] = $row->categories ? $row->categories->$title : null;
            $dddd[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
        }

        $data=[
            'ads_first'=>$ddd,
            'categories_business'=>$d,
            'ads_second'=>$dddd,
            'categories_individuals'=>$dd,
        ];

        return $this->outApiJson(true, 'success', $data);
    }
    public function doctors(Request $request)
    {

        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        $now=Carbon::now();
        $country_id=$request->header('country');
        $cat_id = null;
        if ($request->input('cat_id')){
            $cat_id=Categories::find($request->input('cat_id'));
            if (!$cat_id){
                return $this->outApiJson(false, 'not_found');
            }
        }


        $title = 'title_' . $request->header('lang');

        if (@$cat_id->business == 1 and @$cat_id->individuals == null) {
            $doctors = AppUser::where(['type' => '2','cat_id'=>$request->cat_id,'country_id'=>$country_id])
                ->where('automatic_acceptance','1')
                ->inRandomOrder()->paginate($length);

            $d = [];
            foreach ($doctors->items() as $k => $row) {

                $d[$k]['id'] = $row->id;
                $d[$k]['name'] = $row->commercial_name;
                $d[$k]['image'] = url('/') . '/assets/tmp/' . $row->avatar;
                $d[$k]['commercial_name'] = $row->commercial_name;
                $d[$k]['type_name'] = 'شركات';
                $d[$k]['type'] = 2;
            }
        }elseif (@$cat_id->business == null and @$cat_id->individuals == 1){

            $advertisements=Advertisements::where(['status'=>1,'cat_id'=>$request->cat_id])
                ->whereHas('user', function ($query) use ($country_id) {
                    $query->where('country_id', $country_id);
                })
                ->with('charityImages')
                ->where('type_account', '1')
                ->inRandomOrder()
                ->paginate($length);

            $dddd = [];
            foreach ($advertisements ->items() as $k => $row) {
                $dddd[$k]['id'] = $row->id;
                $dddd[$k]['name'] = $row->$title;
//            $dddd[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
                $dddd[$k]['type_name'] = 'اعلان';
                $dddd[$k]['type'] = 1;
                $dddd[$k]['type_account'] = $row->type_account;

                foreach ($row->charityImages as $kk=>$image){

                    if (!empty($image->image)){
                        $dddd[$k]['image']=url('/').'/assets/tmp/'.$image->image;
                    }elseif (!empty($image->cover)){
                        $dddd[$k]['image']=url('/').'/assets/tmp/'.$image->cover;
                    }else{
                        $dddd[$k]['image']=null;
                    }

                }

            }

        }elseif (@$cat_id->business == 1 and @$cat_id->individuals == 1) {

            $userString = $request->user_string ;
            $advertisementString = $request->advertisements_string ;
            $UserArray = explode(',', $userString);
            $AdvertisementArray = explode(',', $advertisementString);

            $model1s = AppUser::where(['type' => '2','cat_id'=>$request->cat_id])
                ->where('country_id', $country_id)
                ->where('automatic_acceptance','1')
                ->whereNotIn('id', $UserArray)
                ->select('id','first_name','commercial_name','type','created_at','avatar')
//            ->inRandomOrder()
                ->orderBy('id', 'DESC')
                ->get()->toArray();

            $model2s = Advertisements::where(['cat_id'=>$request->cat_id,'status'=>1])
                ->where('type_account', '1')
                ->where('country_id', $country_id)->select('id','title_ar','type','created_at','image')
                ->whereNotIn('id', $AdvertisementArray)
//            ->inRandomOrder()
                ->orderBy('id', 'DESC')
                ->get()->toArray();


            $results = array_merge($model1s, $model2s);

            shuffle($results);

            $data = $this->paginate($results, $length);

            $ddddd=[];
            foreach ($data->items() as $k => $row) {
                $ddddd[$k]['id'] = $row['id'];
                $ddddd[$k]['name'] = $row['title_ar'];
                $ddddd[$k]['image'] = url('/') . '/assets/tmp/' . $row['image'];
                $ddddd[$k]['type'] = (int)$row['type'];
            }
        }



        $fixa_doctor=PackageOrder::where(['payment_status'=>'1','status'=>'1','type'=>'2',])
            ->whereDate('end_at','>',$now)
            ->whereHas('user', function ($query) use ($country_id ,$request){
                $query->where('country_id', $country_id);
                $query->where('cat_id', $request->cat_id);
            })
            ->with('user')->orderBy('created_at', 'DESC')->limit(4)->get();

        $adss=Ads::query();

        $adss->where(['status'=>1])->where('layout', '3');
        if ($request->cat_id){
            $adss->whereIn('cat_id', [$request->cat_id,0]);
        }
        $adss->with('categories');
        $ads=$adss->get();


        $ddd=[];
        foreach ($fixa_doctor as $k => $row) {

            $ddd[$k]['id_ads'] = $row->id;
            $ddd[$k]['id'] = $row->user->id;
            $ddd[$k]['name'] = $row->user->commercial_name;
            $ddd[$k]['image'] = url('/') . '/assets/tmp/' . $row->user->avatar;

        }

        $dd = [];
        foreach ($ads as $k => $row) {
            $dd[$k]['id'] = $row->id;
            $dd[$k]['title'] = $row->title;
            $dd[$k]['lauout_title'] = $row->lauout_title;
            $dd[$k]['status'] = $row->status;
            $dd[$k]['url'] = $row->url;
//            $dd[$k]['cat_id'] = $row->cat_id;
            $dd[$k]['cat_id'] = null;
            $dd[$k]['cat_title'] = $row->categories ? $row->categories->$title : null;

            $dd[$k]['user_id'] = $row->user_id;

            $dd[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
        };



        if (@$cat_id->business == 1 and @$cat_id->individuals == null){

            $data=[
                'proven_doctor'=>$ddd,
                'ads'=>$dd,
                'data' =>[
                    'data' => $d,
                    'count_total' => $doctors->total(),
                    'nextPageUrl' => $doctors->nextPageUrl(),
                    'pages'=>ceil($doctors->total()/$length),
                ]
            ];
        }elseif (@$cat_id->business == null and @$cat_id->individuals == 1){
            $data=[
//                'type'=>2,
                'proven_doctor'=>[],
                'ads'=>$dd,

                'data' =>[
                    'data' => $dddd,
                    'count_total' => $advertisements->total(),
//                    'nextPageUrl' => url('/api/v1/doctors') . '?page=' . ($data->currentPage() + 1),
//                    'nextPageUrl' => url('/api/v1/doctors') . $data->nextPageUrl(),
                    'nextPageUrl' => $data->nextPageUrl() ? url('/api/v1/doctors') . '?page=' . ($data->currentPage() + 1) : $data->nextPageUrl(),

                    'pages'=>ceil($advertisements->total()/$length),
                ]
            ];

          }elseif (@$cat_id->business == 1 and @$cat_id->individuals == 1) {

            if ($request->cat_id == 221){
                $data = [
                    'proven_doctor' => $ddd,
                    'ads' => $dd,
                    'data' =>[
                        'data' => $ddddd,
                        'paginate' => false,
                        'count_total' => $data->total(),
//                        'nextPageUrl' => url('/api/v1/doctors') . '?page=' . ($data->currentPage() + 1),
                    'nextPageUrl' => $data->nextPageUrl() ? url('/api/v1/doctors') . '?page=' . ($data->currentPage() + 1) : $data->nextPageUrl(),
//                        'nextPageUrl' => $data->nextPageUrl() ? url('/api/v1/doctors') . '?page=' . (1) : $data->nextPageUrl(),
                        'pages'=>ceil($data->total()/$length),
                    ]
                ];

            }else{
                $data = [
                    'proven_doctor' => $ddd,
                    'ads' => $dd,
                    'data' =>[
                        'data' => $ddddd,
                        'paginate' => false,
                        'count_total' => $data->total(),
//                        'nextPageUrl' => url('/api/v1/doctors') . '?page=' . ($data->currentPage() + 1),
//                    'nextPageUrl' => $data->nextPageUrl() ? url('/api/v1/doctors') . '?page=' . ($data->currentPage() + 1) : $data->nextPageUrl(),
                        'nextPageUrl' => $data->nextPageUrl() ? url('/api/v1/doctors') . '?page=' . (1) : $data->nextPageUrl(),
                        'pages'=>ceil($data->total()/$length),
                    ]
                ];
            }

        }

        return  $this->outApiJson(true, 'success', $data);

    }
    public static function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage - 1) * $perPage;
        $itemstoshow = array_slice($items, $offset, $perPage);

        return new LengthAwarePaginator($itemstoshow, $total, $perPage, $currentpage, $options);
    }

    public function search(Request $request){

        $search=$request->input('key');
        $country_id=$request->header('country');
        $title=$request->header('lang')=='ar'?'title_ar':'title_en';
//        return $country_id;
        $length = ($request->input('count')) ? $request->input('count') : 20;
//        $search = implode('',str_split($searchs));
        $model1s = AppUser::query();
        $model1s->where('status','active');
        $model1s->where('automatic_acceptance','1');
        $model1s->where(['type' => '2'])->where('country_id', $country_id)
            ->select('id','first_name','commercial_name','type','created_at','avatar')->inRandomOrder();
        if ($request->key){
            $model1s->where('first_name', 'LIKE', "%{$search}%");
            $model1s->orwhere('commercial_name', 'LIKE', "%{$search}%");
        }
        $model1=$model1s->get()->toArray();
//return $model1;
        $model2s = Advertisements::query();

        $model2s->where(['status'=>1])
            ->where('type_account', '1')
            ->where('country_id', $country_id)->select('id','title_ar','title_en','type','created_at','image')->inRandomOrder();
        if ($request->key){
            $model2s->where($title, 'LIKE', "%{$search}%");
        }
//        $model2s->with('charityImages');

        $model2=$model2s->get()->toArray();

        $model3s=Categories::query();
        $model3s->where(['status'=>1]);
        $model3s->select('id','title_ar','title_en','created_at','image')->inRandomOrder();
        if ($request->key){
            $model3s->where($title, 'LIKE', "%{$search}%");
        }
        $model3=$model3s->get()->toArray();


        $results = array_merge($model1, $model2,$model3);
        shuffle($results);

        $data = $this->paginate($results,$length);

        $d = [];

        foreach ($data->items() as $k => $row) {

            $d[$k]['id'] = $row['id'];
            $d[$k]['name'] = $row[$title];
            $d[$k]['image'] = url('/') . '/assets/tmp/' . $row['image'];
            $d[$k]['type'] = (int)$row['type'];
            $d[$k]['cover'] = $row['cover'] ? url('/') . '/assets/tmp/' . $row['cover'] : null;




        }
        $data=[

                'data' => $d,
                'count_total' => $data->total(),
                'nextPageUrl' => $data->nextPageUrl(),
                'pages'=>ceil($data->total()/$length),

        ];

        return  $this->outApiJson(true, 'success', $data);


    }
    public function doctorsProfile(Request $request){
        //profile

        $users=null;
        try{
            $users = auth('api')->user();
        }catch (JWTException $e) {

        }
        $user = AppUser::where('id',$request->id)->with('categories')->first();
        $length = ($request->input('count')) ? $request->input('count') : 10;

        if (empty($user)){

            return $this->outApiJson(false, 'not_found');
        }


        if(empty($user->views)){
            $user->views = 1;
        }else{
            $user->views  += 1;
        }
        $user->save();
        $title = 'title_'.$request->header('lang');

        $address=Charge::where('user_id',$user->id)->with('cityData','regionData')->get();
        $region=[];
        foreach($address as $kk=>$addres){
            $region[$kk]['id'] = $addres->id;
            $region[$kk]['governorates_title'] = ($addres->cityData) ? $addres->cityData->$title : null ;
            $region[$kk]['cities_title'] = ($addres->regionData) ? $addres->regionData->$title : null ;
            $region[$kk]['lat'] = (float)$addres->lat  ;
            $region[$kk]['lng'] = (float) $addres->lng  ;
            $region[$kk]['street'] = $addres->street;

        }

        $country=Country::where('id',$user->country_id)->where('status','1')->first();

        $clothes=Advertisements::where('user_id',$user->id)->count();
        $clothes_user= Advertisements::query();
        $clothes_user->where('status','1')
            ->where('user_id',$user->id)
            ->with('user','country','charityImages')->orderBy('id','desc');

        $clothes_users = $clothes_user->paginate(10);
        $ddd = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');

        foreach($clothes_users->items() as $k=>$row){
            $ddd[$k]['id'] = $row->id;
            $ddd[$k]['title'] = $row->$title;
            $ddd[$k]['note'] = $row->$note;
            $ddd[$k]['price'] = $row->price;
            $ddd[$k]['cat_id'] = (string)$row->cat_id;


            // $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
//            $ddd[$k]['views'] = $row->views;
            $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
//            foreach ($row->charityImages as $kk=>$image){
//
//                if (!empty($image->image)){
//                    $ddd[$k]['image']=url('/').'/assets/tmp/'.$image->image;
//                }elseif (!empty($image->cover)){
//                    $ddd[$k]['image']=url('/').'/assets/tmp/'.$image->cover;
//                }else{
//                    $ddd[$k]['image']=null;
//                }
//
//            }


        }


        if($users) {
            $favs=Fav::where('user_id',$users->id)->where('advertiser_id',$user->id)->count();


           $fav = ($favs > 0) ? true: false;
            $user_data = [
                'user_id' =>  $user->id,
                'mobile' =>  $user->mobile_number,
                'email' =>  $user->email,
                'name' =>  $user->first_name,
                'commercial_name' =>  $user->commercial_name,
                'type' =>  $user->type,
                'country_id' =>  $user->country_id,
                'cat_title' => ($user->categories)?  $user->categories->$title : null,
                'avatar' => asset("assets/tmp/" .  $user->avatar),
//                'region' => $region,
                'whats_number' => $user->whats_number,
                'description' => $user->description,
                'work_hours' => $user->work_hours,
                'work_hours2' => $user->work_hours2,
                'holiday' => $user->holiday,
                'land_number' => $user->land_number,
                'views' => $user->views,
                'fav' => $fav,
                'advertisements' => $clothes,

            ];
        }else{
            $user_data = [
                'user_id' =>  $user->id,
                'mobile' =>  $user->mobile_number,
                'email' =>  $user->email,
                'name' =>  $user->first_name,
                'commercial_name' =>  $user->commercial_name,
                'type' =>  $user->type,
                'country_id' =>  $user->country_id,
                'cat_title' => ($user->categories)?  $user->categories->$title : null,
                'avatar' => asset("assets/tmp/" .  $user->avatar),
//                'region' => $region,
                'whats_number' => $user->whats_number,
                'description' => $user->description,
                'work_hours' => $user->work_hours,
                'advertisements' => $clothes,
                'work_hours2' => $user->work_hours2,
                'holiday' => $user->holiday,
                'land_number' => $user->land_number,

            ];
        }


        $data=[
            'user_data' => $user_data,
            'address' => $region,
            'advertisements'=>[
                'data'=>$ddd,
                'count_total' => $clothes_users->total(),
                'nextPageUrl' => $clothes_users->nextPageUrl(),
                'pages'=>ceil($clothes_users->total()/$length),
            ],


        ];
        return $this->outApiJson(true, 'success', $data);

    }

}
