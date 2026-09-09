<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AppUser extends Authenticatable implements JWTSubject
{

   use  Notifiable;

    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'app_users';
    protected $appends = ['orders_count','clothes_count','block_count','title_ar','image','cover','title_en'];
    protected $casts = [
        'type' => 'integer',
        'country_id' => 'integer',
        'cat_id' => 'integer',
    ];
    public function getCoverAttribute()
    {

        return null;
    }

    function getHolidayArrAttribute() {
        return explode(',',$this->attributes['holiday']);
    }

    public function getTitleArAttribute()
    {
        return $this->commercial_name;

    }
    public function getTitleEnAttribute()
    {
        return $this->commercial_name;

    }
    public function getImageAttribute()
    {
        return $this->avatar;

    }
    public function getOrdersCountAttribute()
    {
        $order=Order::where('user_id',$this->id)->where('status','!=','shipping')->count();
        return $order;

    }
    public function getBlockCountAttribute()
    {
        $block=BlockUser::where('user_id',$this->id)->count();
        return $block;

    }
    public function getClothesCountAttribute()
    {
        $order=Advertisements::where('user_id',$this->id)->count();
        return $order;

    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function categories()
    {
        return $this->belongsTo(Categories::class , 'cat_id' , 'id');
    }
    public function cities()
    {
        return $this->belongsTo(Cities::class , 'city_id' , 'id');
    }

    public function governorates()
    {
        return $this->belongsTo(Governorates::class , 'region_id ' , 'id');
    }

    public function charges()
    {
        return $this->hasMany('\App\Models\Charge','user_id','id');
    }

    public function addres()
    {
        return $this->belongsTo('\App\Models\Charge','address','id');
    }

    public function promo()
    {
        return $this->hasMany('\App\Models\Promo','user_id','id');
    }

    public function city()
    {
        return $this->belongsTo(Governorates::class,'city_id','id');
    }


    public function region()
    {
        return $this->belongsTo(Cities::class,'region_id','id');
    }

    public function Country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class , 'user_id' , 'id');
    }
    public function advertisements()
    {
        return $this->hasMany(Advertisements::class , 'user_id' , 'id');
    }

    public function block()
    {
        return $this->hasOne(BlockUser::class , 'user_id' , 'id');

    }
    public function favorites()
    {
        return $this->hasMany('\App\Models\Fav', 'charity_id', 'id');
    }
    public function address(){
        $this->belongsTo('\App\Models\Charge');
    }


}
