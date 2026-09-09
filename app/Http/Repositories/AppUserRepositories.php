<?php

namespace App\Http\Repositories;

use App\Models\AppUser;
use Yajra\DataTables\Facades\DataTables;

class AppUserRepositories
{

    public function getDataTableClasses(array $data)
    {
        $query = AppUser::query();
        $skip = $data['start'] ?? 0;
        $take = $data['length'] ?? 25;
        $name = $data['name'] ?? null;
        $commercial_name = $data['commercial_name'] ?? null;
//        $automatic_acceptance = $data['automatic_acceptance'] ?? null;
        $phone = $data['phone'] ?? null;
        $credit = $data['credit'] ?? null;
        $status = $data['status'] ?? null;
        $country = $data['country'] ?? null;
        $type = $data['type'] ?? null;
        $ids = $data['ids'] ?? null;
        $from = $data['from'] ?? null;
        $to = $data['to'] ?? null;
        $credit_operation = $data['credit_operation'] ?? null;
        $lang = $data['lang'] ?? 'ar';

        if ($name){
            $query->where('first_name' , 'like' , '%'. $name . '%');
        }
        if ($commercial_name){
            $query->where('commercial_name' , 'like' , '%'. $commercial_name . '%');
        }
        if ($phone){
            $query->where('mobile_number' , 'like' , '%'. $phone . '%');
        }
        if ($ids){
            $query->where('id' , 'like' , '%'. $ids . '%');
        }
        if ($credit){
            if ($credit_operation == '1'){
                $query->where('credit' , $credit);
            }elseif($credit_operation == '2'){
                $query->where('credit' ,'>', $credit);
            }
            elseif($credit_operation == '3'){
                $query->where('credit' ,'<', $credit);
            }

        }
        if ($status){
            $query->where('status' , $status);
        }
        if ($type == 1){
            $query->where('type' , $type);
        }elseif($type == 2){
            $query->where('type' , $type)
                ->where('automatic_acceptance' , 1);
        }elseif($type == 3){
            $query->where('type' , 2)
                ->where('automatic_acceptance','!=' , 1);
        }

//        if ($country){
//            $query->whereHas('Country', function ($query) use ($country , $lang) {
//                $query->where('title_'.$lang, 'like', '%' . $country . '%');
//            });
//        }
        if ($from && $to){
            $query->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }

        $count = $query->count();
        $info = $query->orderBy('id', 'desc')
                ->with('Country','categories')
            ->withCount('orders')
            ->withSum(
                ['orders'], 'total_cost')
            ->skip($skip)->take($take);
        return DataTables::of($info)->setTotalRecords($count);
    }

    public function countDataTableClasses(array $data)
    {
        $query = AppUser::query();

        return $query->count('id');
    }
}
