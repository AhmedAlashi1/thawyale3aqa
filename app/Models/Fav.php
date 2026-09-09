<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fav extends Model
{
    protected $table = 'fav';
        protected $fillable = ['charity_id','user_id','advertiser_id'];
    protected $dates = ['created_at','updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charity()
    {
        return $this->belongsTo('\App\Models\Advertisements');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\AppUser');
    }
    public function advertiser(){
        return $this->belongsTo('\App\Models\AppUser');
    }
}
