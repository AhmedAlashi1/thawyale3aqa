<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'key_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title_ar', 'title_en', 'key_id', 'value', 'set_group', 'status', 'is_object'
    ];

    public static function whatsappLoginEnabled(): bool
    {
        $setting = static::ensureWhatsappLoginSetting();

        return $setting && (string) $setting->value === '1';
    }

    public static function ensureWhatsappLoginSetting()
    {
        $setting = static::where('key_id', 'whatsapp_login')->first();
        if ($setting) {
            return $setting;
        }

        $row = [
            'key_id' => 'whatsapp_login',
            'title_ar' => 'تفعيل إرسال كود الدخول عبر واتساب',
            'title_en' => 'Enable WhatsApp login code',
            'value' => '1',
            'set_group' => 'general',
            'is_object' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('settings', 'status')) {
            $row['status'] = '1';
        }

        DB::table('settings')->insert($row);

        return static::where('key_id', 'whatsapp_login')->first();
    }
}
