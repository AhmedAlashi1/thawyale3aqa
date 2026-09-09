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
        $setting = static::ensureWhatsappLoginSetting();

        return (string) $setting->value === '1';
    }

    public static function ensureWhatsappLoginSetting()
    {
        return static::firstOrCreate(
            ['key_id' => 'whatsapp_login'],
            [
                'title_ar' => 'تفعيل إرسال كود الدخول عبر واتساب',
                'title_en' => 'Enable WhatsApp login code',
                'value' => '1',
                'set_group' => 'general',
                'is_object' => 0,
            ]
        );
    }

}