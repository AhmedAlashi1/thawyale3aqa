<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddWhatsappLoginSetting extends Migration
{
    public function up()
    {
        $exists = Setting::where('key_id', 'whatsapp_login')->exists();
        if ($exists) {
            return;
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

        if (\Illuminate\Support\Facades\Schema::hasColumn('settings', 'status')) {
            $row['status'] = '1';
        }

        DB::table('settings')->insert($row);
    }

    public function down()
    {
        Setting::where('key_id', 'whatsapp_login')->delete();
    }
}
