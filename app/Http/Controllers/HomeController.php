<?php

namespace App\Http\Controllers;

use App\Models\Advertisements;
use App\Models\AppUser;
use App\Models\PackageOrder;
use App\Models\DailyVisits;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {



        $datas['total']=[];
        $datas['country']=[];
        $country=[];
        $month=[];
        for ($j = 11; $j >= 0; $j--) {
//            $count=PackageOrder::where('status','1')->whereYear('created_at', '=', Carbon::now()->subMonth($j)->format('Y'))
//                ->whereMonth('created_at', '=', Carbon::now()->subMonth($j)->format('m'))->sum('total_cost');
            $count=1;
//            $count=$this->order->getcountReportMonth(Carbon::now()->subMonth($j)->format('Y'), Carbon::now()->subMonth($j)->format('m'));
            $month[]=Carbon::now()->subMonth($j)->format('M/y');
            $country[]=($count) ? $count : 0;
        };


        // $customers=Customer::where('type',1)->count();
        // $contestants=Customer::where('type',2)->count();
        $events=Advertisements::count();
        $orders_count=PackageOrder::count();

        $date=[];
        $products=[];
        $orders=[];
        for ($i = 0; $i < 7; $i++){
            $range = \Carbon\Carbon::now()->subDays($i)->format('20y-m-d');
            $product=Advertisements::whereDate('created_at',$range)->get();
            $order=PackageOrder::whereDate('created_at',$range)->orderBy('id', 'DESC')->get();
            $date[]=$range;
            $products[]=$product->count();
            $orders[]=$order->count();
        }
        $now=Carbon::now()->format('Y-m-d');
        $number_daily = DailyVisits::where('date',$now)->first();
        $number_daily = ($number_daily) ? $number_daily->total : 0;

        $number_weekly = DailyVisits::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        $number_monthly = DailyVisits::whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('total');
        $number_yearly = DailyVisits::whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->sum('total');
        $number_total = DailyVisits::sum('total');

        $number_weekly = ($number_weekly) ? $number_weekly : 0;
        $number_monthly = ($number_monthly) ? $number_monthly : 0;
        $number_yearly = ($number_yearly) ? $number_yearly : 0;
        $number_total = ($number_total) ? $number_total : 0;

        $countries = \App\Models\Country::all()->map(function ($country) {
            return [
                'business'=> AppUser::where('type',2)->whereCountryId($country->id)->count(),
                'individual'=> AppUser::where('type',1)->whereCountryId($country->id)->count(),
                'title'=>$country->{'title_'.app()->getLocale()}
            ];
        });

        // $numOfBusAccount = AppUser::where('type',2)->count();
        // $numOfIndAccount = AppUser::where('type',1)->count();



        return view('index',compact('events','orders_count','date','countries','products','orders','country','month','number_daily','number_weekly','number_monthly','number_yearly','number_total' ));
    }
}
