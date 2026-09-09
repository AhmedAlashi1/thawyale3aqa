<?php

namespace App\Http\Controllers;

use App\Exports\ExportAppUser;
use App\Models\Advertisements;
use App\Models\Categories;
use App\Models\AppUser;
use App\Http\Repositories\AppUserRepositories;
use App\Models\Categories as Category;
use App\Models\Charge;
use App\Models\Cities;
use App\Models\Clothes;
use App\Models\Country;
use App\Models\Governorates;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AppUsersController extends Controller
{
    public function app_user(Request $request)
    {
        $query = AppUser::paginate();

        $type=$request->type;
        $categories= Category::get();
        $countries = Country::get();

        return view('App_User.index',compact('type','categories','countries'));
    }

    public function edit($id)
    {
        try {
//            return AppUser::get();
             $categories = Categories::orderBy('sort_order')->where('status', '1')->get();
            $appUser = AppUser::findorfail($id);
            $countries = Country::get();
            return view('App_User.edit',compact('appUser','categories','countries'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'user not found']);
        }
    }

    public function update($id,Request $request)
    {
        try {
//            return $request;
            $user = AppUser::findorfail($id);
            $type = $user->type;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_number = $request->mobile_number;
            $user->whats_number = $request->whats_number;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->type = $request->type;
            $user->country_id = $request->country_id;

            if($request->type == 1){
                $user->cat_id = NULL;
            }
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['gif', 'jpg', 'jpeg', 'png'])) {
                    return back()->withErrors(['error'=>'Image Must Be gif, jpg, jpeg, png']);
                }
                $destinationPath = 'assets/tmp';
                $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                    '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                 $user->avatar = $fileName;
            }
//            $user->work_hours = $request->work_hours;
            $start_time = Carbon::createFromFormat('H:i', $request->start_time)->format('h:i A');
            $end_time = Carbon::createFromFormat('H:i', $request->end_time)->format('h:i A');
            $work_hours = $start_time . ',' . $end_time;
//            return $work_hours;
            $user->work_hours = $work_hours;


            $user->work_hours2 = $request->work_hours2;
            $user->holiday = implode(',',$request->holiday??[]);
            $user->address = $request->address;
            $user->description = $request->description;
            if ($user->type == 2){
                $user->commercial_name = $request->commercial_name;
                $user->land_number = $request->land_number;
                $user->cat_id = $request->category;

            }

            $user->update();
            return redirect()->route('app_user', ['type' => $type]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function get_appUser(Request $request, AppUserRepositories $appuserRepo)
    {
        $dataTable = $appuserRepo->getDataTableClasses($request->all());
        $dataTable->addIndexColumn();
        $dataTable->escapeColumns(['*']);
        return $dataTable->make(true);

    }
    public function profile($id)
    {
        $shops =AppUser::where('id',$id)->with('categories')->first();

        $date=[];
        $products=[];
        $orders=[];

        $product=Advertisements::where('user_id',$shops->id)->with('user')->get();
//        return $shops;


        return view('App_User.profile',compact('shops','product','date','products','orders','id'));

    }
    public function app_user_address_index($id)
    {
//        $Charge = Charge::where('user_id' , $id)->with('cityData' , 'regionData')->get();
//        return $Charge;
        $Governorates = Governorates::get();
        $Cities = Cities::get();
        return view('App_User.addresses', compact('Governorates', 'Cities'));
    }

    public function get_app_user_address($id)
    {
        $Charge = Charge::where('user_id', $id)->with('cityData', 'regionData')->get();
        return response()->json([
            'message' => 'Update Success',
            'status' => 200,
            'data' => $Charge
        ]);
    }

    public function add_app_user_address(Request $request, $id, $type)
    {
        if ($type == '1') {
            $Charge = Charge::find($id);
            return response()->json([
                'status' => 200,
                'data' => $Charge
            ]);
        }
        elseif ($type == '2') {
            $Charges = Charge::find($request->id_categoryd);
//            $Charges->user_id = $id;
            $Charges->type = $request->type;
            $Charges->city_id = $request->governate;
            $Charges->region_id = $request->city;
            $Charges->block = $request->block;
            $Charges->avenue = $request->avenue;
            $Charges->street = $request->street;
            $Charges->building = $request->building;
            $Charges->floor = $request->floor;
            $Charges->flat = $request->flat;
            $Charges->notes = $request->notes;
            $Charges->address = $request->street;
            $Charges->save();
        } elseif ($type == '3') {
//            return $request->all();
            $user = AppUser::find($id);
            $user->mobile_number = $request->mobile_number;
            $user->first_name = $request->name;
            if ($request->cat_id == 'null'){
                $user->cat_id = null;
            }else{
                $user->cat_id = $request->cat_id;

            }
            $user->save();
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
            ]);
        } else {
            $Chargew = new Charge();
            $Chargew->user_id = $id;
            $Chargew->type = $request->type;
            $Chargew->city_id = $request->governate;
            $Chargew->region_id = $request->city;
            $Chargew->block = $request->block;
            $Chargew->avenue = $request->avenue;
            $Chargew->street = $request->street;
            $Chargew->building = $request->building;
            $Chargew->floor = $request->floor;
            $Chargew->flat = $request->flat;
            $Chargew->notes = $request->notes;
            $Chargew->address = $request->street;
            $Chargew->save();
            return response()->json([
                'message' => trans('category.success_add_property'),
                'status' => 200,
            ]);
        }

    }


    public function delete_app_user_address($id)
    {
        $Charge = Charge::find($id);
        if ($Charge) {
            $Charge->delete();
            return response()->json([
                'message' => trans('category.property_delete_success'),
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function delete($id)
    {
        $app_user = AppUser::find($id);
        if ($app_user) {
            $app_user->delete();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $categories = AppUser::find($id);
        $categories->status = request('status');
        $categories->update();
        return response()->json([
            'message' => trans('category.success_update_property'),
            'status' => 200,
        ]);
    }
    public function active_automatic_acceptance(Request $request){
                $id = $request->id;
                $categories = AppUser::find($id);
                $categories->automatic_acceptance = request('active_automatic_acceptance');
                $categories->update();
                return response()->json([
                    'message' => trans('category.success_update_property'),
                    'status' => 200,
                ]);

            }

    public function add100(Request $request)
    {
        $id = $request->id;
        $user = AppUser::find($id);
//        return $user->country_id;
        $country=Country::find( $user->country_id);
//        $country=Country::where('id',$country_id)->where('status','1')->first();

        $note=$request->note;
        if ($request->credit < 0){
            $request->credit = 0;
        }
        if (!empty($country)){

            if (!empty($user->credit)){
                $credit=  (string)(round($request->credit / $country->coin_price,3));
                $messages = 'تم اضافة رصيد الى حسابك بقيمه ' . $credit . ' '.$country->coin_name .' '.$note;

            }else{
                $credit=$request->credit;
                $messages = 'تم اضافة رصيد الى حسابك بقيمه ' . $credit . 'دينار'.' '.$note;

            }
        }else{
            $credit=$request->credit;
            $messages = 'تم اضافة رصيد الى حسابك بقيمه ' . $credit . 'دينار'.' '.$note;

        }
//        $credit = $request->credit;
        $credit_user = $user->credit;
        $sum = $credit + $credit_user;
        $user->credit = $sum;
        $user->update();
        $message = $messages;
        $this->notification($user->device_token , 'حلاو ككاو' , $message);

        return response()->json([
            'message' => 'تم إضافة رصيد بنجاح',
            'status' => 404,
        ]);
    }

    public function export(Request $request)
    {
//        return new ExportAppUser($request);
        return Excel::download(new ExportAppUser($request), 'AppUsers.xlsx');
    }

    public function usersAll (Request $request){
        if ($request->type == '1') {
            $users = AppUser::whereIn('id', $request->ids);
            if ($users) {
                $users->delete();
                return response()->json([
                    'message' => trans('category.property_delete_success'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }elseif ($request->type == '2'){
            $users = AppUser::whereIn('id', $request->ids);
            if ($users) {
                $users->update(['status' => "active",'automatic_acceptance'=>1]);
                return response()->json([
                    'message' => trans('category.property_delete_success'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }
    }

    public function account_transfer($id){

        $cat=Category::all();

        return view('App_User.create',compact('id','cat'));

    }
    public function account_transfer_store(Request $request){

        $user = AppUser::find($request->user_id);
//        return $user;
        $user->commercial_name = $request->commercial_name;

        $user->email = $request->email;
        $user->cat_id = $request->cat_id;
        $user->type = 2;
        $user->update();
        return redirect()->route('app_user')->with('success', 'تم تحويل الحساب بنجاح');

    }
    public function notification($FcmToken = [], $title = "", $body = "")
    {
        $data = [
            "to" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default"
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
//            return response()
//            ->json(['status' => 'success', 'errors' => 0,
//            'data' => json_decode($response, true)])
//            ->header('Content-type', 'application/json');
    }
}
