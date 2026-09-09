<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Advertisements;
use App\Models\AppUser;
use App\Models\Charge;
use App\Models\Country;
use App\Models\Categories;
use App\Models\Fav;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\Governorates;
use App\Models\Cities;
use App\Models\DailyVisits;

use App\Repositories\TimesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\ClothesRepository;
use App\Repositories\SliderRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\ContactRepository;
use App\Repositories\FavRepository;
use App\Repositories\AdsRepository;
use App\Repositories\AppUsersChargeRepository;
use App\Repositories\DeliveryRepository;
use App\Repositories\PaymentRepository;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use App\Models\Setting;



class SettingController extends Controller
{
    use Functions;

    public function country(Request $request)
    {

        $Country = Country::query();
        $Country->where('status', '1');
        $title = 'title_' . $request->header('lang');

        if ($request->input('key')) {

            $Country->where($title, 'like', '%' . $request->input('key') . '%');
        }
        $paginate = $Country->get();
        $d = [];
        foreach ($paginate as $k => $row) {
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->$title;
            $d[$k]['status'] = (int)$row->status;
            $d[$k]['coin_price'] = $row->coin_price;
            $d[$k]['coin_name'] = $row->coin_name;
            $d[$k]['coin_name_en'] = $row->coin_name_en;
            $d[$k]['phone_code'] = $row->phone_code;
            $d[$k]['regus'] = $row->regus;
            $d[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
        }
        $data = [

            'Country' => $d,
        ];
        return $this->outApiJson(true, 'success', $d);

    }

    public function setting(Request $request)
    {

        $visit = DailyVisits::where('date',Carbon::now()->format('Y-m-d'))->first();
        if (!$visit){
            $visit = new DailyVisits();
            $visit->date = Carbon::now()->format('Y-m-d');
        }
        $visit->total +=1;
        $visit->save();
        $title = 'title_' . $request->header('lang');
        $first_interface_title = 'first_interface_title_' . $request->header('lang');
        $second_interface_title = 'second_interface_title_' . $request->header('lang');
        $description_first_interface = 'description_first_interface_' . $request->header('lang');
        $description_second_interface = 'description_second_interface_' . $request->header('lang');
        $ads =Ads::where('layout','4')->where('status',1)->with('categories')->first();
//                    return $ads;
        if ($ads){
            $data_ads=[
                'url' => $ads->url,
                'cat_id' => $ads->cat_id,
                'cat_title' => $ads->categories ? $ads->categories->$title : null,
                'user_id' => $ads->user_id,
                'image' =>url('/').'/assets/tmp/'.$ads->image,

            ];
        }
        try {

            $data = [
                'android_version' => Setting::where('key_id', 'version')->first()->value,
                'ios_version' => Setting::where('key_id', 'version_ios')->first()->value,
                'force_update' => Setting::where('key_id', 'force_update')->first()->value == 1 ? true : false,
                'force_close' => Setting::where('key_id', 'force_close')->first()->value == 1 ? true : false,
                'whats' => Setting::where('key_id', 'whats')->first()->value,
                'activation_url' => Setting::where('key_id', 'picasa')->first()->value,
                'snap' => Setting::where('key_id', 'Snapchat')->first()->value,
                'instagram' => Setting::where('key_id', 'Instagram')->first()->value,
//                'TikTok'=> Setting::where('key_id','TikTok')->first()->value,
                'twitter' => Setting::where('key_id', 'twitter')->first()->value,
                'ads_status'=> Setting::where('key_id','ads_status')->first()->value,
                'ads'=>$ads ? $data_ads : null,
                'installation'=> Setting::where('key_id','installation')->first()->value == 1 ? true : false,


            ];
            return $this->outApiJson(true, 'success', $data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function about(Request $request)
    {

        try {

            $data = [

                'about'=>Setting::where('key_id','about_'.$request->header('lang'))->first()->value,
//                'help'=>Setting::where('key_id','help_'.$request->header('lang'))->first()->value,
//                'privacy'=>Setting::where('key_id','p rivacy_'.$request->header('lang'))->first()->value,
                'conditions' => Setting::where('key_id', 'conditions_' . $request->header('lang'))->first()->value,

            ];
            return $this->outApiJson(true, 'success', $data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function getAreas(Request $request, $id)
    {

        $areas = Cities::where(['status' => 1, 'governorat_id' => $id])->get();
        $data = [];
        $title = 'title_' . $request->header('lang');
        foreach ($areas as $row) {
            $data[] = [
                'id' => $row->id,
                'region_id' => $row->id,
                'title' => $row->$title,
            ];
        }
        return $this->outApiJson(true, 'success', $data);
    }

    public function getCities(Request $request)
    {

        $country_id = $request->header('country');

        $governorates = Governorates::query();
        $governorates->where(['status' => 1]);
        if ($request->input('country_id')) {
            $governorates->where('country_id', $request->input('country_id'));
        }
        $areas = $governorates->get();
        $data = [];
        $title = 'title_' . $request->header('lang');
        foreach ($areas as $row) {
            $zones = Cities::where(['status' => 1, 'governorat_id' => $row->id])->get();
            $data_zone = [];
            foreach ($zones as $item) {
                $data_zone[] = [
                    'id' => $item->id,
                    'region_id' => $item->id,
                    'title' => $item->$title,
                ];
            }
            $data[] = [
                'id' => $row->id,
//                'city_id'=>$row->id,
                'country_id' => $row->country_id,
                'title' => $row->$title,
                'regions' => $data_zone,

            ];
            unset($data_zone);
        }
        return $this->outApiJson(true, 'success', $data);
    }

    public function getData(Request $request)
    {


        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        $parent_id = ($request->input('parent_id')) ? $request->input('parent_id') : 0;

        $where_obj = Categories::query();
        $where_obj->where('status', '1');
//        $where_obj->where('parent_id',$parent_id);
        if ($request->cat_id){
            $where_obj->where('id',$request->cat_id);
        }
        if ($request->input('keyword')) {
            $where_obj->where('title_' . $request->header('lang'), 'like', '%' . $request->input('keyword') . '%');
        }
        if ($request->type == 1) {
            $where_obj->where('individuals', 1)->where('business', null);
        } elseif ($request->type == 2) {
            $where_obj->where('business', 1)->where('individuals', null);

        } elseif ($request->type == 3) { //both
            $where_obj->where('individuals', 1)->where('business', 1)
                ->orWhere('business', 1)->orWhere('individuals', null);
            ;

        }elseif ($request->type == 4) { //both
            $where_obj->where('individuals', 1);
        }
        $where_obj->orderBy('sort_order', 'asc');
        $where_obj->with('packages');
        $paginate = $where_obj->paginate($length);


        $d['data'] = [];

        $title = 'title_' . $request->header('lang');
//         return $paginate;
        foreach ($paginate->items() as $k => $row) {
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['color'] = $row->color;
            $d['data'][$k]['image'] = url('/') . '/assets/tmp/thumb/' . $row->image;
            if (!empty($row->packages)) {
                foreach ($row->packages as  $kk=> $item) {
                    $d['data'][$k]['packages'][$kk]['packages_id'] = $item->id;
                    $d['data'][$k]['packages'][$kk]['packages_name'] = $item->$title;
                    $d['data'][$k]['packages'][$kk]['packages_price'] = $item->price;
                }

            }
//            $d['data'][$k]['products']=$row->products->count();
//            $d['data'][$k]['subCategory']=$row->sub->count();

        }
//        $dd=['categories'=>['data'=>$d['data']]];
        //$d['data'] = $paginate->items();
        //        $d['recordsTotal'] = $paginate->total();
        //        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true, 'success', $d['data']);
    }

    public function packages(Request $request)
    {


        $title = 'title_' . $request->header('lang');

        $questions = Packages::query();
        $questions->where('status', '1');
        if ($request->type) {
            $questions->where('type', $request->type);

        }
        $questions->with('category');
//        return $questions->get();
        $d = [];
        foreach ($questions->get() as $k => $row) {
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->$title;
            $d[$k]['type'] = $row->type;

            $d[$k]['price'] = $row->price;
            $d[$k]['days'] = $row->days;
            $d[$k]['status'] = $row->status;

        }
        return $this->outApiJson(true, 'success', $d);

    }

    public function payment(Request $request)
    {

        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;

        $paginate = Payment::where('status', '1')->orderby('id', 'desc')->paginate($length);


        $d['data'] = [];
        $title = 'title_' . $request->header('lang');
        foreach ($paginate->items() as $k => $row) {
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
        }
        //$d['data'] = $paginate->items();
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true, 'success', ['count_total' => $paginate->total(), 'nextPageUrl' => $paginate->nextPageUrl(), 'pages' => ceil($paginate->total() / $length), 'data' => $d['data']]);
    }

    public function categories(Request $request)
    {

        $title = 'title_' . $request->header('lang');
        $categories = Categories::where('status', '1')->get();
        $d = [];
        foreach ($categories as $k => $row) {
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->$title;
            $d[$k]['status'] = $row->status;
            $d[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;


        }
        return $this->outApiJson(true, 'success', $d);

    }

    public function ads(Request $request)
    {

        $title = 'title_' . $request->header('lang');
        $ads = Ads::query();
           $ads->where('status', '1');
        if ($request->type) {
            $ads->where('layout', $request->type);
        };
             $ads = $ads->get();
        $d = [];
        foreach ($ads as $k => $row) {
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->title;
            $d[$k]['lauout_title'] = $row->lauout_title;
            $d[$k]['status'] = $row->status;
            $d[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
        };
        return $this->outApiJson(true, 'success', $d);

    }

    public function addFav(Request $request)
    {
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        if ($request->type == 1){
            $advertiser_id=AppUser::where('id',$request->input('id'))->first();
            if(empty($advertiser_id)){
                return $this->outApiJson(false,'user_not_found');
            }
            $add=Fav::where(['user_id'=> $user->id,'advertiser_id'=>$request->input('id')])->first();
            if($add){
                return $this->outApiJson(false,'fav_exists');
            }
            $data=[
                'advertiser_id'=>$request->input('id'),
                'user_id'=> $user->id,
            ];
        }else{
            $charity_id=Advertisements::where('id',$request->input('id'))->first();
            if(empty($charity_id)){
                return $this->outApiJson(false,'not_found');
            }
            $add=Fav::where(['user_id'=> $user->id,'charity_id'=>$request->input('id')])->first();
            if($add){
                return $this->outApiJson(false,'fav_exists');
            }
            $data=[
                'charity_id'=>$request->input('id'),
                'user_id'=> $user->id,
            ];
        }

        try{

            $repose=Fav::create($data);

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function deleteFav(Request $request)
    {

        $user=auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        if(
            empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
//        return 'a';
        if ($request->type == 1){
            $advertiser_id=AppUser::where('id',$request->input('id'))->first();
            if(empty($advertiser_id)){
                return $this->outApiJson(false,'user_not_found');
            }
            $add=Fav::where(['user_id'=> $user->id,'advertiser_id'=>$request->input('id')])->first();

            if(!$add){
                return $this->outApiJson(false,'fav_id_not_exists');
            }
        }else{
            $charity_id=Advertisements::where('id',$request->input('id'))->first();
            if(empty($charity_id)){
                return $this->outApiJson(false,'not_found');
            }
            $add=Fav::where(['user_id'=> $user->id,'charity_id'=>$request->input('id')])->first();

            if(!$add){
                return $this->outApiJson(false,'fav_id_not_exists');
            }
        }


        try{
            if ($request->type==1){
                $repose=Fav::where('advertiser_id', $request->input('id'))->where('user_id', $user->id)->delete();
            }else{
                $repose=Fav::where('charity_id', $request->input('id'))->where('user_id', $user->id)->delete();
            }

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function getFav(Request $request)
    {
        $user=auth('api')->user();
        $country_id=$request->header('country');
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }


        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;

        if ($request->type == 1){
            $paginate = Fav::where('user_id',$user->id)
                ->whereHas('advertiser', function ($q) use ($country_id) {
                    $q->where('country_id', $country_id);
                })
                ->whereNotNull('advertiser_id')
                ->with('charity','user','advertiser')->orderBy('id', 'DESC')->paginate($length);
//            return $paginate;
        }else{
            $paginate = Fav::where('user_id',$user->id)
                ->whereHas('charity', function ($q) use ($country_id) {
                    $q->where('country_id', $country_id);
                })
                ->whereNotNull('charity_id')
                ->with('charity.user','user','advertiser')->orderBy('id', 'DESC')->paginate($length);
//            return $paginate;
        }


        $d['data'] = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');
//        return $paginate;

            foreach($paginate->items() as $k=>$row){
                if ($request->type == 2) {
                    if ($row->charity) {
                        if ($row->charity->status == 1) {
                            $d['data'][$k]['id'] = $row->charity->id;
                            $d['data'][$k]['title'] = $row->charity->$title;
                            $d['data'][$k]['note'] = $row->charity->$note;
                            $d['data'][$k]['end_date'] = $row->charity->end_date;
                            $d['data'][$k]['price'] = $row->charity->price;
                            $d['data'][$k]['cat_id'] =(string) $row->charity->cat_id;
//                            if ($user) {
//                                $d['data'][$k]['fav'] = ($row->charity->favorites->where('user_id', $user->id)->count() > 0) ? true : false;
//                            }
                            $d['data'][$k]['image'] = url('/') . '/assets/tmp/' . $row->charity->image;

                        }
                    }
                }else {
                    if ($row->advertiser){
                        $d['data'][$k]['id'] = $row->advertiser->id;
                        $d['data'][$k]['name'] = $row->advertiser->first_name;

                        $d['data'][$k]['image']=url('/').'/assets/tmp/'.$row->advertiser->avatar;
                    }

                }

            }





        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    public function addAdd(Request $request)
    {

        $user=auth('api')->user();
//        return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }


        $data=[
            'lat'=>$request->input('lat'),
            'lng'=>$request->input('lng'),
            'street'=>$request->input('street'),
            'block'=>$request->input('block'),
            'city'=>$request->input('city'),
            'governate'=>$request->input('governate'),
            'building'=>$request->input('building'),
            'avenue'=>$request->input('avenue'),
            'city_id'=>$request->input('city_id'),
            'region_id'=>$request->input('region_id'),
            'user_id'=> $user->id,
        ];
        try{
//            return $data;
            $repose=Charge::create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function editAdd(Request $request)
    {
        $user=auth('api')->user();

        if (!$user) {
            return $this->outApiJson(false,'user_not_found');
        }
        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        //check user inactive
//        if ($user->status != 'active') {
//            return $this->outApiJson(false,'user_inactive');
//        }
//
//        if(empty($request->input('title'))){
//            return $this->outApiJson(false,'data_required');
//        }


        $data=[
            'lat'=>$request->input('lat'),
            'lng'=>$request->input('lng'),
            'street'=>$request->input('street'),
            'block'=>$request->input('block'),
            'city'=>$request->input('city'),
            'governate'=>$request->input('governate'),
            'building'=>$request->input('building'),
            'avenue'=>$request->input('avenue'),
            'city_id'=>$request->input('city_id'),
            'region_id'=>$request->input('region_id'),
            'user_id'=>$user->id,
        ];
        try{
            $address=Charge::find($request->input('id'));
            $repose=$address->update($data);

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function deleteAdd(Request $request)
    {
        //check user inactive
        $user=auth('api')->user();

        if (!$user) {
            return $this->outApiJson(false,'user_not_found');
        }
//        if ($user->status != 'active') {
//            return $this->outApiJson(false,'user_inactive');
//        }

        if(
            empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $add=Charge::where(['user_id'=>$user->id,'id'=>$request->input('id')])->first();
//        $add=$this->address->findWhere(['user_id'=>$this->user->id,'id'=>$request->input('id')])->first();

        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }
        try{
            $repose=$add->delete();
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function getAdd(Request $request)
    {
        $user=auth('api')->user();

        if (!$user) {
            return $this->outApiJson(false,'user_not_found');
        }
        ini_set('precision', 10);
        ini_set('serialize_precision', 10);
        //check user inactive
        $user=auth('api')->user();


        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;

        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });

        $paginate=Charge::orderBy('id','desc')->where('user_id',$user->id)->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            if($user->address == $row->id){
                $d['data'][$k]['default']=true;
            }
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['lat'] =(float) $row->lat;
            $d['data'][$k]['lng'] = (float) $row->lng;
            $d['data'][$k]['street'] = $row->street;
            $d['data'][$k]['building'] = $row->building;
            $d['data'][$k]['block'] = $row->block;

            $d['data'][$k]['city'] = $row->cityData;
            $d['data'][$k]['governate'] = $row->regionData;
//
//            $d['data'][$k]['floor'] = $row->floor;
//            $d['data'][$k]['flat'] = $row->flat;
//            $d['data'][$k]['avenue'] = $row->avenue;
//            $d['data'][$k]['mobile'] = $row->mobile;
//            $d['data'][$k]['post_code'] = $row->post_code;
//            $d['data'][$k]['additional_direction'] = $row->additional_direction;
//            $d['data'][$k]['house'] = $row->building;


        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }



}
