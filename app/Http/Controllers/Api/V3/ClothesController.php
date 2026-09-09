<?php

namespace App\Http\Controllers\Api\V3;

use App\Models\Ads;
use App\Models\Country;
use App\Models\Categories;
use App\Models\Clothes;
use App\Models\DeliveryTypes;
use App\Models\Fav;
use App\Models\FixedAds;
use App\Models\Item;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\Times;
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

class   ClothesController extends ApiController
{

    use Functions;
    private $repo;
    private $cat;
    private $contact;
    private $fav;
    private $ads;
    private $address;
    private $dev;
    private $pay;
    private $slider;
    private $times;
    public function __construct(Request $request, ClothesRepository $repo,CategoriesRepository $cat,ContactRepository $contact, FavRepository $fav,AdsRepository $ads,AppUsersChargeRepository $address,DeliveryRepository $dev,PaymentRepository $pay,SliderRepository $slider, TimesRepository $times)
    {
        parent::__construct($request);
        $this->repo = $repo;
        $this->cat = $cat;
        $this->contact = $contact;
        $this->fav = $fav;
        $this->ads = $ads;
        $this->address = $address;
        $this->dev = $dev;
        $this->pay = $pay;
        $this->slider = $slider;
        $this->times = $times;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        if($request->input('cat_id')) {
            $where_obj->pushWhere('cat_id', $request->input('cat_id'), 'eq');
        }
        //$where_obj->pushWhere('end_date',Carbon::now(),'gte');
        $where_obj->pushWhere('status',1,'eq');
        $where_obj->pushWhere('confirm',1,'eq');
        if($request->input('keyword')){
            $where_obj->pushWhere('title_'.$request->header('lang'),$request->input('keyword'),'contain');
        }
        if($request->input('order')){
           if($request->input('order')==1){
                $where_obj->pushOrder('id','desc');
            }elseif($request->input('order')==2){
                $where_obj->pushOrder('id','asc');
            }elseif($request->input('order')==3){
                $where_obj->pushOrder('price','desc');
            }elseif($request->input('order')==4){
                $where_obj->pushOrder('price','asc');
            }
        }else{
           $where_obj->pushOrder('id','desc');
        }
        $where_obj_cat = new \App\Repositories\Criteria\WhereObject();
        $where_obj_cat->pushWhere('status',1,'eq');
        $where_obj->pushHas('cat',$where_obj_cat);

        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->repo->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->repo->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['end_date'] = $row->end_date;
            $d['data'][$k]['price_before'] = $row->price;
            $d['data'][$k]['price_after'] = $row->price_after;
            $d['data'][$k]['quntaty'] = $row->quntaty;
            $d['data'][$k]['order_limit'] = $row->order_limit;
            $d['data'][$k]['end_offer'] = $row->end_offer;

            $d['data'][$k]['image']=url('/').'/assets/tmp/thumb/'.$row->image;
            if($user) {
                $d['data'][$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }


    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataType(Request $request,$type)
    {

        if($type=='hot'){
            $deal=1;
        }elseif($type=='flash'){
            $deal=2;
        }else{
            $deal=3;
        }
        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('type',$deal,'eq');
        //$where_obj->pushWhere('end_date',Carbon::now(),'gte');
        $where_obj->pushWhere('status',1,'eq');
        $where_obj->pushWhere('confirm',1,'eq');
        $where_obj->pushOrder('id','desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->repo->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->repo->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['end_date'] = $row->end_date;
            $d['data'][$k]['price_before'] = $row->price;
            $d['data'][$k]['price_after'] = $row->price_after;
            $d['data'][$k]['quntaty'] = $row->quntaty;
            $d['data'][$k]['order_limit'] = $row->order_limit;
            $d['data'][$k]['end_offer'] = $row->end_offer;
            $d['data'][$k]['image']=url('/').'/assets/tmp/thumb/'.$row->image;
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRow(Request $request)
    {
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        try{
            $repose = $this->repo->find($request->input('id'));

            $data=[];
            if ($repose) {
                $title='title_'.$request->header('lang');
                $note='note_'.$request->header('lang');
                $data['id']=$repose->id;
                $data['title']=$repose->$title;
                $data['image']=url('/').'/assets/tmp/'.$repose->image;
                $data['note']=$repose->$note;
                $data['price_before']=$repose->price;
                $data['price_after']=$repose->price_after;
                $data['category']=($repose->cat)?$repose->cat->$title:'';
                $data['end_date']=$repose->end_date;
                $data['quntaty']=$repose->quntaty;
                $data['end_offer']=$repose->end_offer;
                $data['order_limit']=$repose->order_limit;
                $data['lat']=$repose->lat;
                $data['lng']=$repose->lng;
                if($user) {
                $data['fav'] = ($repose->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $data['images']=[];
                $kk=0;
                foreach($repose->charityImages as $kk=>$item){
                    //$data['images'][$kk]['id']=$item->id;
                    $data['images'][$kk]['image']=url('/').'/assets/tmp/'.$item->image;
                    $kk++;
                }
                $data['images'][$kk]=['image'=>url('/').'/assets/tmp/'.$repose->image];
                return $this->outApiJson(true,'success',$data);
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */



    public function times(Request $request)
    {

        $paginate = Times::where('status',1)->get();
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
        }
        return $this->outApiJson(true,'success',$d['data']);
    }
    public function deliveryTypes(Request $request)
    {

        $paginate = DeliveryTypes::where('status',1)->get();
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['time_from'] = $row->time_from;
            $d['data'][$k]['time_to'] = $row->time_to;
            $d['data'][$k]['sat'] = $row->sat;
            $d['data'][$k]['sun'] = $row->sun;
            $d['data'][$k]['mon'] = $row->mon;
            $d['data'][$k]['tue'] = $row->tue;
            $d['data'][$k]['wed'] = $row->wed;
            $d['data'][$k]['thu'] = $row->thu;
            $d['data'][$k]['fri'] = $row->fri;
        }
        return $this->outApiJson(true,'success',$d['data']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactUs(Request $request)
    {
        if(
            empty($request->input('name'))||
            empty($request->input('email'))||
            empty($request->input('subject'))||
            empty($request->input('message'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $data=[
            'name'=>$request->input('name'),
            'mobile'=>$request->input('subject'),
            'email'=>$request->input('email'),
            'message'=>$request->input('message'),
        ];
        try{
            $repose=$this->contact->create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */



    public function home(Request $request)
    {


//        return $request->all();
    $user = null;
    try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        try{





            $up_banner=Ads::where(['layout'=>'1','status'=>'1'])->orderBy('id','desc')->limit(3)->get();

            $up_banner_commercial=Ads::where(['layout'=>'2','status'=>'1'])->orderBy('id','desc')->limit(3)->get();


//        return $country;
            $categories=Categories::where(['status'=>'1','parent_id' => '0'])->orderBy('sort_order','asc')->get();
//            return $categories;
//            $fixed_ads= Clothes::where('type','1')->where('status','1')->where('confirm','1')->orderBy('id','asc')->limit(6)->get(['*']);
            $now=Carbon::now()->format('Y-m-d H:i:s');
//            $now2=Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
            $fixed_ads=FixedAds::with('clothes.favorites','clothes.user','clothes.country','clothes.governorates','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('packages' ,function ($q){
                    $q->where('type',1);
                })
                ->where('status','1')
//                ->where('home', '1')
                ->whereIn('home', ['1','3'])
                ->orderBy('created_at','desc')->limit(9)->get(['*']);

//            return $fixed_ads;

            $featured=FixedAds::with('clothes.favorites','clothes.user','clothes.country','clothes.governorates','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('packages' ,function ($q){
                    $q->where('type',2);
                })
                ->where('status','1')
                ->where('home', '1')
                ->orderBy('created_at','desc')->limit(9)->get(['*']);

//            $featured= Clothes::where('type','3')->where('status','1')->where('confirm','1')->orderBy('id','asc')->limit(6)->get(['*']);

            $most_watched= Clothes::where('type','1')->where('status','1')->where('confirm','1')

                ->with('user.block','country','governorates')
                ->orderBy('views','desc')->limit(6)->get(['*']);

            $latest_ads= Clothes::where('type','83')->where('status','1')->where('confirm','1')->with('user','country','governorates')->orderBy('id','desc')->limit(6)->get(['*']);


            $country_id= $request->header('country');
            $country=Country::where('id',$country_id)->where('status','1')->first();


            $d = [];
            $title='title_'.$request->header('lang');
            $note='note_'.$request->header('lang');
            foreach($fixed_ads as $k=>$row){
                $d[$k]['id'] = $row->clothes->id;
                $d[$k]['title'] = $row->clothes->$title;
                $d[$k]['note'] = $row->clothes->$note;
                $d[$k]['end_date'] = $row->end_date;
                $d[$k]['cat_id'] = $row->clothes->cat_id;
                $d[$k]['price'] = $row->clothes->price;

                $d[$k]['country'] = ($row->clothes->country) ? $row->clothes->country->$title : null;
                $d[$k]['coin_name'] =($row->clothes->country) ? $row->clothes->country->coin_name : null;

                $d[$k]['user_id'] = $row->clothes->user->id;
                $d[$k]['user_email'] = $row->clothes->user->email;
                $d[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
                $d[$k]['user_whats_number'] = $row->clothes->user->whats_number;

                $d[$k]['ishidden'] = $row->clothes->block_user >= 1 ?true : false;

                $d[$k]['chat_icon'] = $row->clothes->chat == 1 ?true : false;
                $d[$k]['email_icon'] = $row->clothes->email == 1 ?true : false;
                $d[$k]['sms_icon'] = $row->clothes->sms == 1 ?true : false;
                $d[$k]['whatsApp_icon'] = $row->clothes->whatsApp == 1 ?true : false;
                $d[$k]['call_icon'] = $row->clothes->call == 1 ?true : false;



                if($user) {
                $d[$k]['fav'] = ($row->clothes->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
                $d[$k]['image']=url('/').'/assets/tmp/'.$row->clothes->image;
            }
            $dd = [];
            foreach($featured as $k=>$row){
                $dd[$k]['id'] = $row->clothes->id;
                $dd[$k]['title'] = $row->clothes->$title;
                $dd[$k]['note'] = $row->clothes->$note;
                $dd[$k]['end_date'] = $row->end_date;
                $dd[$k]['cat_id'] = $row->clothes->cat_id;
                $dd[$k]['price'] = $row->clothes->price;
                $dd[$k]['country'] = ($row->clothes->country) ? $row->clothes->country->$title : null;
                $dd[$k]['coin_name'] =($row->clothes->country) ? $row->clothes->country->coin_name : null;
                $dd[$k]['user_id'] = $row->clothes->user->id;
                $dd[$k]['user_email'] = $row->clothes->user->email;
                $dd[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
                $dd[$k]['user_whats_number'] = $row->clothes->user->whats_number;
                $dd[$k]['ishidden'] = $row->clothes->block_user >= 1 ?true : false;
                $dd[$k]['chat_icon'] = $row->clothes->chat == 1 ?true : false;
                $dd[$k]['email_icon'] = $row->clothes->email == 1 ?true : false;
                $dd[$k]['sms_icon'] = $row->clothes->sms == 1 ?true : false;
                $dd[$k]['whatsApp_icon'] = $row->clothes->whatsApp == 1 ?true : false;
                $dd[$k]['call_icon'] = $row->clothes->call == 1 ?true : false;

                if($user) {
                    $dd[$k]['fav'] = ($row->clothes->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $dd[$k]['image']=url('/').'/assets/tmp/'.$row->clothes->image;
            }
            $ddd = [];
            foreach($most_watched as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['note'] = $row->$note;
                $ddd[$k]['end_date'] = $row->end_date;
                $ddd[$k]['price'] = $row->price;

                $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
                $ddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

                $ddd[$k]['cat_id'] = $row->cat_id;

                $ddd[$k]['views'] = $row->views;
                $ddd[$k]['user_id'] = $row->user->id;
                $ddd[$k]['user_email'] = $row->user->email;
                $ddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $ddd[$k]['user_whats_number'] = $row->user->whats_number;
                $ddd[$k]['ishidden'] = $row->block_user >= 1 ?true : false;

                $ddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
                $ddd[$k]['email_icon'] = $row->email == 1 ?true : false;
                $ddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
                $ddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
                $ddd[$k]['call_icon'] = $row->call == 1 ?true : false;

                if($user) {
                    $ddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }


            $dddd = [];
            foreach($latest_ads as $k=>$row){
                $dddd[$k]['id'] = $row->id;
                $dddd[$k]['title'] = $row->$title;
                $dddd[$k]['note'] = $row->$note;
                $dddd[$k]['end_date'] = $row->end_date;
                $dddd[$k]['price'] = $row->price;
                $dddd[$k]['cat_id'] = $row->cat_id;

                $dddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
                $dddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

                $dddd[$k]['user_id'] = $row->user->id;
                $dddd[$k]['user_email'] = $row->user->email;
                $dddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $dddd[$k]['user_whats_number'] = $row->user->whats_number;
                $dddd[$k]['ishidden'] = $row->block_user >= 1 ?true : false;
                $dddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
                $dddd[$k]['email_icon'] = $row->email == 1 ?true : false;
                $dddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
                $dddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
                $dddd[$k]['call_icon'] = $row->call == 1 ?true : false;

                if($user) {
                    $dddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $dddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }


            $categoriesData = [];
            foreach($categories as $k=>$row){
                $categoriesData[$k]['id'] = $row->id;
                $categoriesData[$k]['title'] = $row->$title;
                $categoriesData[$k]['color'] = $row->color  ;
                $categoriesData[$k]['image'] = url('/').'/assets/tmp/'.$row->image;
            }
            $ban=[];

            foreach($up_banner as $k=>$up){
                $ban[$k]['url'] = $up->url;
//                $ban[$k]['cat_id'] = $up->cat_id;
//                $ban[$k]['product_id'] = $up->product_id;
                $ban[$k]['image'] = url('/').'/assets/tmp/'.$up->image;
            }
            $ban2=[];
            foreach($up_banner_commercial as $up){
                $ban2[]=['url'=>$up->url,
//                    'cat_id'=>$up->cat_id,
//                    'product_id'=>$up->product_id,
                    'image'=>url('/').'/assets/tmp/'.$up->image,

                ];
            }

            $data=[
                'up_banner'=>$ban,
                'up_banner_commercial'=>$ban2,

                'categories'=>$categoriesData,
                'fixed_ads'=>$d,
                'featured'=>$dd,
                'most_watched'=>$ddd,
                'latest_ads'=>$dddd,


            ];
            return $this->outApiJson(true,'success',$data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function interested(Request $request)
    {
//        return 'a';
        try{
//            $newest=$this->repo->getHomeAds(4,500);

            $country_id= $request->header('country');
            $country=Country::where('id',$country_id)->where('status','1')->first();


            if (empty($country) or $country->id == 3 ){
                $newest=Clothes::where('type',4)->where('status',1)->where('confirm',1)->orderBy('sort_order','asc')->limit(500)->get(['*']);

            }else{

//                return 'a';
                $newest=Clothes::where('type',4)->where('international','1')->where('status',1)->where('confirm',1)->orderBy('sort_order','asc')->limit(500)->get(['*']);

            }
            $ddd = [];
            $title='title_'.$request->header('lang');
            foreach($newest as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['end_date'] = $row->end_date;

                if (empty($country)){
                    $ddd[$k]['price_before'] = $row->price;
                    $ddd[$k]['price_after'] = $row->price_after;
                }else{
                    $ddd[$k]['price_before'] = (string)(round( $row->price  / $country->coin_price,3));
                    if ($row->price_after != ''){
                        $ddd[$k]['price_after'] =(string)(round( $row->price_after  / $country->coin_price,3));
                    }else{
                        $ddd[$k]['price_after'] = (string)$row->price_after;
                    }

                }
//
//                $ddd[$k]['price_before'] = $row->price;
//                $ddd[$k]['price_after'] = $row->price_after;

                $ddd[$k]['quntaty'] = $row->quntaty;
                $ddd[$k]['order_limit'] = $row->order_limit;
                $ddd[$k]['end_offer'] = $row->end_offer;
                $ddd[$k]['weight'] = $row->weight;
                $ddd[$k]['international'] = $row->international;
                $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
//            return $ddd;
//            if (!empty($ddd)){
                return $this->outApiJson(true,'success',$ddd);

//            }else{
//                return $this->outApiJson(true,'success', 'null');
//            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFav(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }

        $add=$this->fav->findWhere(['user_id'=>$this->user->id,'charity_id'=>$request->input('id')])->first();
        if($add){
            return $this->outApiJson(false,'fav_exists');
        }
        $data=[
            'charity_id'=>$request->input('id'),
            'user_id'=>$this->user->id,
        ];
        try{
            $repose=$this->fav->create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFav(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(
        empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $add=$this->fav->findWhere(['user_id'=>$this->user->id,'charity_id'=>$request->input('id')])->first();
        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }
        try{
            $repose=$this->fav->deleteFav($request->input('id'),$this->user->id);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFav(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushOrder('id','desc');
        $where_obj->pushOrWhere('user_id',$this->user->id,'eq');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->fav->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->fav->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][]=[
                'id'=>($row->charity)?$row->charity->id:'',
                'title'=>($row->charity)?$row->charity->$title:'',
                'end_date'=>($row->charity)?$row->charity->end_date:'',
                'price_before'=>($row->charity)?$row->charity->price:'',
                'price_after'=>($row->charity)?$row->charity->price_after:'',
                'end_offer'=>($row->charity)?$row->charity->end_offer:'',
                'order_limit'=>($row->charity)?$row->charity->order_limit:'',
                'fav'=>true,
                'image'=>$row->charity?url('/').'/assets/tmp/'.$row->charity->image:'',
            ];
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    public function addAdd(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(empty($request->input('title'))){
            return $this->outApiJson(false,'data_required');
        }


        $data=[
            'lat'=>$request->input('lat'),
            'lng'=>$request->input('lng'),
            'address'=>$request->input('address'),
            'title'=>$request->input('title'),
            'street'=>$request->input('street'),
            'block'=>$request->input('block'),
            'city'=>$request->input('city'),
            'governate'=>$request->input('governate'),
            'floor'=>$request->input('floor'),
            'flat'=>$request->input('flat'),
            'building'=>$request->input('building'),
            'avenue'=>$request->input('avenue'),
            'user_id'=>$this->user->id,
        ];
        try{
            $repose=$this->address->create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function getAdd(Request $request)
    {
        ini_set('precision', 10);
ini_set('serialize_precision', 10);
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushOrder('id','desc');
        $where_obj->pushOrWhere('user_id',$this->user->id,'eq');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->address->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->address->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            if($this->user->address==$row->id){
                $d['data'][$k]['default']=true;
            }
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['address'] = $row->address;
            $d['data'][$k]['lat'] = $row->lat;
            $d['data'][$k]['lng'] = $row->lng;
            $d['data'][$k]['title'] = $row->title;
            $d['data'][$k]['street'] = $row->street;
            $d['data'][$k]['block'] = $row->block;
            $d['data'][$k]['city'] = $row->city;
            $d['data'][$k]['governate'] = $row->governate;
            $d['data'][$k]['floor'] = $row->floor;
            $d['data'][$k]['flat'] = $row->flat;
            $d['data'][$k]['building'] = $row->building;
            $d['data'][$k]['avenue'] = $row->avenue;
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }
    public function deleteAdd(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(
        empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $add=$this->address->findWhere(['user_id'=>$this->user->id,'id'=>$request->input('id')])->first();
        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }
        try{
            $repose=$this->address->delete($request->input('id'));
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function editAdd(Request $request)
    {
        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(empty($request->input('title'))){
            return $this->outApiJson(false,'data_required');
        }


        $data=[
            'lat'=>$request->input('lat'),
            'lng'=>$request->input('lng'),
            'address'=>$request->input('address'),
            'title'=>$request->input('title'),
            'street'=>$request->input('street'),
            'block'=>$request->input('block'),
            'city'=>$request->input('city'),
            'governate'=>$request->input('governate'),
            'floor'=>$request->input('floor'),
            'flat'=>$request->input('flat'),
            'building'=>$request->input('building'),
            'avenue'=>$request->input('avenue'),
            'user_id'=>$this->user->id,
        ];
        try{
            $repose=$this->address->update($data,$request->input('id'));
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

}
