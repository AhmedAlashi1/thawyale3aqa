<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $table = "categories";


    protected $fillable = [
        'title_en' , 'title_ar' ,  'image' , 'status','parent_id','type','individuals','business','individuals_and_business'

    ];

    public static $rules = [
        'title_ar' => 'required|min:3',
        'title_en' => 'required|min:3',

    ];
    protected $appends = ['type','cover'];

    public function getTypeAttribute()
    {
        return 3;

    }
    public function getCoverAttribute()
    {

        return null;
    }

//    public function products()
//    {
//        return $this->hasMany(Clothes::class , 'cat_id' , 'id');
//    }

    public function ads()
    {
        return $this->hasMany(Ads::class , 'cat_id' , 'id');
    }
        public function sub()
    {
        return $this->hasMany('\App\Models\Categories','parent_id','id');
    }
    public function packages()
    {
        return $this->hasMany('\App\Models\Packages','cat_id','id');
    }

}
