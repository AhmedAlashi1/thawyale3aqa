<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class PackageOrder extends Model
{
    use HasFactory;

    protected $table = "package_order";
    public $timestamps = true;

    protected $fillable = [
        'payment_status','end_at','start_at','user_id','packages_id','status','type','advertisements_id'

    ];

    public function user()
    {
        return $this->belongsTo(AppUser::class , 'user_id' , 'id');
    }

    public function packages()
    {
        return $this->belongsTo(Packages::class , 'packages_id' , 'id');
    }


}
