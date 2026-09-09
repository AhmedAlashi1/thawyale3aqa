<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        Setting::ensureWhatsappLoginSetting();
        $settings=Setting::where('set_group','general')->get();
        return view('setting.index',compact('settings'));
    }


    public function privacy()
    {
        Setting::ensureWhatsappLoginSetting();
        $settings=Setting::where('set_group','general')->whereIN('key_id' , ['about_ar','about_en','conditions_ar','conditions_en','privacy_ar','privacy_en','installation','automatic_acceptance','whats_notification_number','whatsapp_login'])->get();
//        return  $settings;
        return view('setting.privacy_settings',compact('settings'));
    }

    public function social()
    {
        $settings=Setting::where('set_group','social')->get();
        return view('setting.social',compact('settings'));
    }

    public function update(Request $request)
    {
  //        return $request->all();
        foreach ($request->except('_token') as $k => $v) {
            $this->update_setting([
                'key_id' => $k,
                'value' => $v
            ], $k);
        }
        // session()->flash('success', 'تم التعديل بنجاح ');
        return redirect()->back()->with('success', 'تم التعديل بنجاح ');
    }

    public function update_setting($data,$key){
        return Setting::updateOrCreate(['key_id' => $key], $data);
    }
}
