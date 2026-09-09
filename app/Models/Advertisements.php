<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

//use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Advertisements extends Model
{
    use HasFactory;

    protected $table = "advertisements";

    protected $fillable = ['title_ar' , 'title_en' , 'note_en' ,
    'note_ar' , 'image' , 'price' ,'quntaty' , 'cat_id' , 'user_id',
        'status' , 'type' , 'price_after','international','keywords' , 'order_limit',
        'end_date' , 'confirm','sort_order','work_hours','country_id','views','type_account'
    ];


//    protected $spatialFields = [
//        'from_location'
//    ];
    protected $casts = [
        'type' => 'integer',
        'cat_id' => 'integer',
    ];
    protected $appends = ['cover'];

    public static $rules = [
        'title_ar' => 'required|min:3',
        'title_en' => 'required|min:3',
        'note_ar' => 'required|min:3',
        'note_en' => 'required|min:3',
        'price' => 'required|numeric',
        'image' => 'required',
    ];
    public function getCoverAttribute()
    {
         $images = CharityImage::where('charity_id' , $this->id)->whereNotNull('cover')->first();
         return $images->cover ?? null;
    }


    public function categories()
    {
        return $this->belongsTo('\App\Models\Categories' , 'cat_id'  , 'id');
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class , 'user_id'  , 'id');
    }

    public function charityImages()
    {
        return $this->hasMany('\App\Models\CharityImage','charity_id','id');
    }

    public function ads()
    {
        return $this->hasMany(Ads::class , 'product_id ' , 'id');
    }

    public function favorites()
    {
        return $this->hasMany('\App\Models\Fav', 'charity_id', 'id');
    }
    public function pieces()
    {
        return $this->hasMany(Pieces::class, 'clothe_id', 'id');
    }
    public function fixedAds()
    {
        return $this->hasMany('\App\Models\FixedAds','clothes_id','id');
    }
    public function country()
    {
        return $this->belongsTo('\App\Models\Country','country_id','id');
    }
    public function governorates()
    {
        return $this->belongsTo('\App\Models\Governorates','governorates_id','id');
    }

}
