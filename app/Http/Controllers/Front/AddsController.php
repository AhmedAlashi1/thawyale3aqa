<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Country;
use App\Models\Governorates;
use App\Models\Clothes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;


class AddsController extends Controller
{
    public function create()
    {
        $this->data['countries'] = Country::get();
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
//return $this->data['categories'];

        return view("Front.LandingPage.AddAdminstermine")->with($this->data);
    }

    public function store(Request $request)
    {


        //$user = auth('api')->user();
        /*if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }*/

        if (
            empty($request->input('title')) || empty($request->input('Entitle')) ||
            empty($request->input('DescribeAd')) || empty($request->input('EnDescribeAd')) ||
            empty($request->input('price')) ||
            empty($request->input('country_id'))||
            empty($request->input('governorates_id')) ||
            empty($request->input('Pcat_id')) ||
            empty($request->input('cat_id'))

        ) {
            //dd("Here 1!");
            return redirect()->route('adds')->with('error', 'يجب تعبئة كافة الحقول المطلوبة');
        }

            $langs = ['ar', 'en'];
            $data = $request->except(['_token', 'id', 'note', 'type','use_credit','title']);
            $cat = Categories::find($request->input('cat_id'));
            $Parentcat = Categories::find($request->input('Pcat_id'));

            if (!$Parentcat) {
                //dd("Here 2!");
                return redirect()->route('adds')->with('error', 'يجب التصنيف الفرعي بشكل صحيح');
            }
            if (!$cat) {
                //dd("Here 3!");
                return redirect()->route('adds')->with('error', 'يجب التصنيف الفرعي بشكل صحيح');
            }

            if ($request->has('RoomCount'))
                $data['number_rooms'] = $request->RoomCount;
            if ($request->has('CountSwimmingPool'))
                $data['swimming_pool'] = $request->CountSwimmingPool;
            if ($request->has('CountJim'))
                $data['Jim'] = $request->CountJim;
            if ($request->has('working_condition'))
                $data['working_condition'] = $request->working_condition;
            if ($request->has('year'))
                $data['year'] = $request->year;
            if ($request->has('cere'))
                $data['cere'] = $request->cere;
            if ($request->has('number_cylinders'))
                $data['number_cylinders'] = $request->number_cylinders;
            if ($request->has('brand'))
                $data['brand_id'] = $request->brand;
            if ($request->has('salary'))
                $data['salary'] = $request->salary;
            if ($request->has('educational_level'))
                $data['educational_level_id'] = $request->educational_level;
            if ($request->has('specialization'))
                $data['specialization_id'] = $request->specialization;
            if ($request->has('biography'))
                $data['biography'] = $request->biography;
            if ($request->has('animal_type'))
                $data['animal_type_id'] = $request->animal_type;
            if ($request->has('fashion_type'))
                $data['fashion_type_id'] = $request->fashion_type;
            if ($request->has('subjects'))
                $data['subjects_id`'] = $request->subjects;


            $data['note_ar'] = $request->input('DescribeAd');
            $data['note_en'] = $request->input('EnDescribeAd');

            if(!empty($request->input('title'))){
                $data['title_ar'] = $request->title;
                $data['title_en'] = $request->Entitle;
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
//                $this->createThumb($destinationPath, $fileName);
                    $images_data[] = ['image' => $fileName];
                }
            }else{
                $images_data[] = ['image' => ''];
            }


            if (count($images_data) > 0) {
                $data['image'] = $images_data[0]['image'];
            }

            $data['user_id'] = Auth::guard()->user()->id;

            unset($data['images']);


            $add = Clothes::create($data);
            if (!$add) {
                //dd("Here 4!");
                redirect()->route('adds')->with('error', 'فشلت عملية إضافة الإعلان !');
            }
            $add->charityImages()->createMany($images_data);


            // add payment link
            $userdata['advertisement_id']=$add->id;
            //dd("Here 5!");
            return redirect()->route('adds')->with('success', '! تم إضافة الإعلان بنجاح');


    }

    public function getCity(Request $request)
    {
        $country_id = $request->input('country');
        $gov = Governorates::where('country_id',$country_id)->get();
        return ['value' => view('Front.LandingPage.AjaxCity',compact('gov'))->render()];

    }

    public function getSubCat(Request $request)
    {
        $category_id = $request->input('category');
        $categories = Categories::where(['status' => '1', 'parent_id' => $category_id])->get();
        $category = Categories::where(['status' => '1', 'parent_id' => $category_id])->first();
//        $category = Categories::where(['id' => $category_id])->first();

        return ['value' => view('Front.LandingPage.AjaxSubCat',compact('categories'))->render(),'data'=>$category];
    }

    public function getSubCatEdit(Request $request)
    {
        $category_id = $request->input('category');
        $categories = Categories::where(['status' => '1', 'parent_id' => $category_id])->get();
        $category = Categories::where(['status' => '1', 'parent_id' => $category_id])->first();
//        $category = Categories::where(['id' => $category_id])->first();

        return ['value' => view('Front.LandingPage.AjaxSubCatEdit',compact('categories'))->render(),'data'=>$category];
    }


    public function getInputs(Request $request)
    {
        $category_id = $request->input('sub_category');
        $category = Categories::where(['id' => $category_id])->first();
        return [
            'value' => view('Front.LandingPage.AjaxInputs',compact('category'))->render(),
            'data'=>$category
        ];
    }


    public function Edit($id,$idUser)
    {
        $AuthId = Auth::guard('user')->user()->id;
        $product = Clothes::with(['charityImages'])->find($id);
        if ($idUser != $AuthId || !$product) {
            return redirect()->route('user_Profile');
        }
        $this->data['countries'] = Country::get();
        $this->data['product'] = $product;
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
        //dd("id" . $id . 'idUser' . $idUser);
        return view('Front.LandingPage.EditProduct')->with($this->data);
    }
    public function update($id,$idUser)
    {

    }
}
