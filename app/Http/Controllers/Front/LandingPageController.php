<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;


class LandingPageController extends Controller
{

    public function index(Request $request)
    {

        $categoryId = $request->input('categoryId');
        if ($categoryId) {

        } else {
            $user = null;
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {

            }

//        try{

            $up_banner = Ads::where(['layout' => '1', 'status' => '1'])->orderBy('id', 'desc')->limit(3)->get();

            $up_banner_commercial = Ads::where(['layout' => '2', 'status' => '1'])->orderBy('id', 'desc')->limit(3)->get();

            $categories = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
            $now = Carbon::now()->format('Y-m-d H:i:s');

            $fixed_ads = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                ->where('end_at', '>', $now)
                ->whereHas('packages', function ($q) {
                    $q->where('type', 1);
                })
                ->where('status', '1')

                ->whereIn('home', ['1','3'])
                ->orderBy('created_at', 'desc')->limit(9)->get(['*']);

            //return $fixed_ads;

            $featured = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                ->where('end_at', '>', $now)
                ->whereHas('packages', function ($q) {
                    $q->where('type', 2);
                })
                ->where('status', '1')
                ->whereIn('home', ['2','3'])
                ->orderBy('created_at', 'desc')->limit(9)->get(['*']);


            $most_watched = Clothes::where('type', '1')->where('status', '1')->where('confirm', '1')->with('user', 'country', 'governorates')->orderBy('views', 'desc')->limit(9)->get(['*']);
//            return $most_watched;
            $latest_ads = Clothes::where('type', '1')->where('status', '1')->where('confirm', '1')->with('user', 'country', 'governorates')->orderBy('id', 'desc')->limit(9)->get(['*']);


            $data = [
                'up_banner' => $up_banner,
                'up_banner_commercial' => $up_banner_commercial,
                'categories' => $categories,
                'fixed_ads' => $fixed_ads,
                'featured' => $featured,
                'most_watched' => $most_watched,
                'latest_ads' => $latest_ads,


            ];

            return view("Front.LandingPage.main", compact('data'));
        }

    }


    public function privacy()
    {
        return view("Front.LandingPage.PrivacyPolicy");
    }
    public function fav(Request $request){
        $user=auth('user')->user();
        $fav=Fav::where('user_id', $user->id)->where('charity_id',$request->id)->first();
        if (!$fav){
            $data=[
                'charity_id'=>$request->input('id'),
                'user_id'=> $user->id,
            ];
            $repose=Fav::create($data);

            if ($repose) {
                return response()->json([
                    'message' => 'add',
                    'status' => 200,
                    'data' => $repose,
                    'clothes_id' => $request->id
                ]);
            }
        }else{
            $repose=$fav->delete();
            return response()->json([
                'message' => 'delete',
                'status' => 201,
                'data' => $repose,
                'clothes_id' => $request->id
            ]);


        }


    }


}
