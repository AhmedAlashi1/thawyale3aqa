<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyVisits extends Model
{
    use HasFactory;

    protected $table = "daily_vistis";

    protected $fillable = ['total' , 'date'];



}
