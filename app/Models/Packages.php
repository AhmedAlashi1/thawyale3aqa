<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;

    protected $table = "packages";
    public $timestamps = true;

    protected $fillable = [
            'title_ar','title_en','image','price','days','sort_order','type','status','place_installation','repeat_duration','number_repetitions','cat_id'

    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Categories', 'cat_id','id');
    }

}
