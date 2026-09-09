<?php

namespace App\Http\Controllers\Api\V3;

use App\Models\Cities;
use App\Models\Country;
use App\Models\Governorates;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Helpers\Functions;
use App\Repositories\CountriesRepository;
use App\Repositories\CityRepository;
use App\Repositories\Criteria\AdvancedSearchCriteria;
class CountriesController extends ApiController
{
    use Functions;
    private $repo;
    private $city;

    public function __construct(Request $request,CountriesRepository $repo,CityRepository $city)
    {
        parent::__construct($request);
        $this->repo = $repo;
        $this->city = $city;
    }

    public function index()
    {
        try {
            $all=$this->repo->all(['*']);
            $data=[];
            foreach($all as $row){
                $data[]=[
                    'id'=>$row->id,
                    'name'=>$row->name_ar,
                ];

            }
            return $this->outApiJson(true,'success',['count'=>count($data),'data'=>$data]);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAreas(Request $request,$id){

        $areas = Cities::where(['status'=>1,'governorat_id'=>$id])->get();
        $data=[];
        $title = 'title_'.$request->header('lang');
        foreach ($areas as $row){
            $data[]=[
                'id'=>$row->id,
                'region_id'=>$row->id,
                'title'=>$row->$title,
            ];
        }
        return $this->outApiJson(true,'success',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAreasFar(Request $request){
        $areas = Cities::where(['status'=>1,'far_zone'=>1])->get();
        $data=[];
        $title = 'title_'.app()->getLocale();
        foreach ($areas as $row){
            $data[]=[
                'id'=>$row->id,
                'title'=>$row->$title,
            ];
        }
        return $this->outApiJson(true,'success',$data);
    }

    public function ordersError(Request $request){

        echo 'error';
    }
    public function ordersSuccess(Request $request){

        echo 'success';
    }
}
