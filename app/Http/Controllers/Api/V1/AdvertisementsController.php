<?php

namespace App\Http\Controllers\api\V1;

use App\Helpers\Functions;
use App\Http\Controllers\Controller;
use App\Models\Advertisements;
use App\Models\Categories;
use App\Models\CharityImage;
use App\Models\Clothes;
use App\Models\Fav;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class AdvertisementsController extends Controller
{
    use Functions;

    public function createProperty(Request $request)
    {


        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

//        try {
            $langs = ['ar', 'en'];
            $data = $request->except(['_token', 'id', 'note', 'type','use_credit','title']);
            if ($request->cat_id){
                $cat = Categories::find($request->input('cat_id'));

                if (!$cat) {
                    return $this->outApiJson(false, 'cat_not_found');
                }
            }
            if ($user->type == 1){
                $data['type_account'] = 1;
                $data['cat_id'] = $request->input('cat_id');
                $data['work_hours'] = $request->input('work_hours');

                $automatic_acceptance=Setting::where('key_id','automatic_acceptance')->first()->value;

                if (($request->is_filled == 1 and $automatic_acceptance==1) or $user->automatic_acceptance == 1){
                    $data['status'] = 1;
                }else{
                    $data['status'] = 0;
                }

//                automatic_acceptance
            }else{
                $data['type_account'] = 2;
                $data['cat_id'] =$user->cat_id;

                $automatic_acceptance=Setting::where('key_id','automatic_acceptance')->first()->value;

                if ($automatic_acceptance==1  or $user->automatic_acceptance == 1){
                    $data['status'] = 1;
                }else{
                    $data['status'] = 0;
                }


            }

             $data['type'] = 1;
            $data['note_ar'] = $request->input('note');
            $data['note_en'] = $request->input('note');

            if(!empty($request->input('title'))){
                $data['title_ar'] = $request->title;
                $data['title_en'] = $request->title;
            }

            $images_data = [];
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $file->getClientOriginalName() . '-' . rand(9999, 9999999) .
                        '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = 'assets/tmp';
                    $file->move($destinationPath, $fileName);
                    // create image thumb
//                $this->createThumb($destinationPath, $fileName);
                    $images_data[] = ['image' => $fileName];
                }
            }
        if ($request->hasFile('video')) {
            $file = $request->file('video');

            $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/tmp';
            $file->move($destinationPath, $fileName);

            $images_data[] = ['video' => $fileName];

        }


        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $extension = $file->getClientOriginalExtension();
            $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/tmp';
            $file->move($destinationPath, $fileName);

            $images_data[] = ['cover' => $fileName];

        }





            if (count($images_data) > 0) {
                $data['image'] = $images_data[0][key($images_data[0])];
            }

            $data['user_id'] = $user->id;
            $country = $request->header('country');
            $data['country_id'] = $country;
            unset($data['images']);

            $add = Advertisements::create($data);
            $watsApp_setting=Setting::where('key_id','whats_notification_number')->first()->value;
            if ($add->status==1){
                $massage='تم اضافة اعلان جديد بواسطة '.$user->first_name;
            }else{
                $massage='تم اضافة اعلان جديد بواسطة '.$user->first_name.' وبانتظار الموافقة';
            }
            $watsApp = $this->whatsapp($watsApp_setting,$massage);
            if (!$add) {
                return $this->outApiJson(false, 'create_error');
            }
            $add->charityImages()->createMany($images_data);
            // add payment link
            $userdata['advertisement_id']=$add->id;

            return $this->outApiJson(true, 'success',$userdata);
//        } catch (\PDOException $ex) {
//            return $this->outApiJson(false, 'pdo_exception');
//        }
    }

    public function updateProperty(Request $request)
    {
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if (
            empty($request->input('id'))
//            empty($request->input('title')) ||
//            empty($request->input('note')) ||
//            empty($request->input('price'))
//            empty($request->input('country_id'))||
//            empty($request->input('governorates_id')) ||
//            empty($request->input('cat_id'))

        ) {
            return $this->outApiJson(false, 'data_required');
        }

        try {
            $prop = Advertisements::where('id',$request->input('id'))->first();
            if (!$prop) {
                return $this->outApiJson(false, 'not_found');
            }
            $langs = ['ar', 'en'];
            $data = $request->except(['_token', 'id', 'note', 'type','use_credit','title']);
            if ($request->cat_id) {
                $cat = Categories::find($request->input('cat_id'));

                if (!$cat) {
                    return $this->outApiJson(false, 'cat_not_found');
                }
            }

            if ($user->type == 1){
                $data['type'] = 1;
                $data['cat_id'] = $request->input('cat_id');
                $data['work_hours'] = $request->input('work_hours');
                if ($request->is_filled == 1){
                    $data['status'] = 1;
                }else{
                    $data['status'] = 0;
                }

            }else{
                $data['type'] = 2;
                $data['cat_id'] =$user->cat_id;

            }
            $data['note_ar'] = $request->input('note');
            $data['note_en'] = $request->input('note');

            if(!empty($request->input('title'))){
                $data['title_ar'] = $request->title;
                $data['title_en'] = $request->title;
            }

            $images_data = [];
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                        '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = 'assets/tmp';
                    $file->move($destinationPath, $fileName);
                    // create image thumb
                    $this->createThumb($destinationPath, $fileName);
                    $images_data[] = ['image' => $fileName];
                }
            }
            if ($request->hasFile('video')) {
                $file = $request->file('video');

                $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                    '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'assets/tmp';
                $file->move($destinationPath, $fileName);

                $images_data[] = ['video' => $fileName];

            }


            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $extension = $file->getClientOriginalExtension();
                $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                    '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'assets/tmp';
                $file->move($destinationPath, $fileName);

                $images_data[] = ['cover' => $fileName];

            }

            if (count($images_data) > 0) {
                $data['image'] = $images_data[0][key($images_data[0])];
            }

            $country = $request->header('country');
            $data['country_id'] = $country;
            $data['user_id'] = $user->id;

            unset($data['images']);

            $add = $prop->update($data);
            $prop->refresh();
//            return $add;
            if (!$add) {
                return $this->outApiJson(false, 'create_error');
            }
            $prop->charityImages()->createMany($images_data);

//             $prop->charityImages()->createMany($images_data);
            // add payment link
            $userdata['advertisement_id']=$prop;

            return $this->outApiJson(true, 'success',$userdata);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function deleteProperty(Request $request, $id)
    {
        //check user inactive
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        $add =Advertisements::Where(['user_id' => $user->id, 'id' => $id])->first();
        if (!$add) {
            return $this->outApiJson(false, 'not_found');
        }
        try {
            $add->charityImages()->delete();
//            $add->fixedAds()->delete();
            $repose = $add->delete();
            if ($repose) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function deleteImage(Request $request, $id)
    {
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        $add = CharityImage::where('id', $id)->first();

        if (!$add) {
            return $this->outApiJson(false, 'not_found');
        }
        try {
            if (!empty($add->video)){
                $cover = CharityImage::whereNotNull('cover')->where('charity_id',$add->charity_id)->delete();
            }

            $repose = CharityImage::where('id', $id)->delete();
            if ($repose) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }


    public function getRow(Request $request)
    {
        $user=null;
        try{
            $user = auth('api')->user();
        }catch (JWTException $e) {

        }


        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        try{
            $repose = Advertisements::where('id',$request->input('id'))->with('user','categories')->first();
            if(empty($repose)){
                return $this->outApiJson(false,'not_found');
            }

            if(empty($repose->views)){
                $repose->views = 1;
            }else{
                $repose->views  += 1;
            }
            $repose->save();

            if ($user){

                $fav = Fav::where(['user_id'=>$user->id,'charity_id'=>$repose->id])->first();

            }

            $data=[];

            if ($repose) {
                $title='title_'.$request->header('lang');
                $note='note_'.$request->header('lang');

                $data['id'] = $repose->id;
                $data['title'] = $repose->$title;
                $data['note'] = $repose->$note;
                $data['end_date'] = $repose->end_date;
                $data['created_at'] = $repose->created_at;
                $data['price'] = $repose->price;
                $data['cat_id'] = (string)$repose->cat_id;
                $data['cat_title'] = $repose->categories ? $repose->categories->$title : null;
                $data['views'] = $repose->views;
                $data['type_account'] = $repose->type_account;
                if ($repose->type_account == 2){
                    $data['work_hours'] =$repose->user ?  $repose->user->work_hours : null;
                    $data['work_hours2'] =$repose->user ?  $repose->user->work_hours2 : null;
                    $data['holiday'] =$repose->user ?  $repose->user->holiday : null;
                }else{
                    $data['work_hours'] = $repose->work_hours;
                }

                $data['user_id'] = $repose->user ? $repose->user->id : null;
                $data['user_name'] = $repose->user ?  $repose->user->first_name : null;
                $data['user_email'] = $repose->user ? $repose->user->email : null;
                $data['user_land_number'] = $repose->user ? $repose->user->land_number : null;

                $data['user_mobile_number'] =$repose->user ? $repose->user->mobile_number : null;
                $data['user_whats_number'] =$repose->user ?  $repose->user->whats_number : null;
                $data['user_type'] = $repose->user ? $repose->user->type : null;
                if ($user) {
                    $data['fav'] = $fav ? true : false;
                }

                try{
                    file_get_contents(url('/').'/assets/tmp/'.$repose->image);

                    $data['image']=url('/').'/assets/tmp/'.$repose->image;
                }catch(Exception $e){
                    $data['image'] = "";
                }

                $data['images']=[];
                $data['country']=@$repose->country;

                $reorderedArray = [];
                $kk=0;
                foreach($repose->charityImages as $kk=>$item){
                    if ($item->image){
                        try{
                            file_get_contents(url('/').'/assets/tmp/'.$item->image);
        
                            $reorderedArray[$kk]['id']=$item->id;

                            $reorderedArray[$kk]['image']=url('/').'/assets/tmp/'.$item->image;
                        }catch(Exception $e){
                            unset($reorderedArray[$kk]);
                            continue;
                        }

                    }
                    if ($item->video){
                        try{
                            file_get_contents(url('/').'/assets/tmp/'.$item->video);

                            // $reorderedArray[$kk]['id']=$item->id;

                            $data['video']['id']=$item->id;
                            $data['video']['video']=url('/').'/assets/tmp/'.$item->video;
                        }catch(Exception $e){
                            unset($reorderedArray[$kk]);
                            continue;
                        }

                    }
                    if ($item->cover){
                        try{
                            file_get_contents(url('/').'/assets/tmp/'.$item->cover);
        
                            // $reorderedArray[$kk]['id']=$item->id;

                            $data['cover']['id']=$item->id;
                            $data['cover']['cover']=url('/').'/assets/tmp/'.$item->cover;
                        }catch(Exception $e){
                            unset($reorderedArray[$kk]);
                            continue;
                        }
                    }

//                    $data['images'][$kk]['cover']=url('/').'/assets/tmp/'.$item->cover;
                    $kk++;
                }

                foreach ($reorderedArray as $key => $value) {
                    $data['images'][] = $value;
                }
//                return   $data['images'];


                $datas=[
                    'data'=> $data,

                ];
                return $this->outApiJson(true,'success',$datas);
            }

            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function whatsapp($phone , $bode){


        $params=array(
            'token' => 'lnmjhc6925uud3ek',
            'to' => $phone,
            'body' =>$bode,

        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance56092/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
//        else {
//            echo $response;
//        }

    }

}
