<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = "settings";

    protected $fillable = [
        'title_ar','title_en' ,'key_id', 'value','set_group', 'status'
    ];

    public static function whatsappLoginEnabled(): bool
    {
        $setting = static::where('key_id', 'whatsapp_login')->first();

        return !$setting || (string) $setting->value === '1';
    }

}