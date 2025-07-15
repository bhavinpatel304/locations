<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Addresses;
use Validator;

class AddressController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | get all the addresses 
    | get single address as per given ID
    |--------------------------------------------------------------------------
    | URL - /get/{id?}
    | @param id - numeric
    |
    | function gets all/single the address with/without pagination
    */

    public function getAddress(Request $request,$id=""){

        $rules = [
            "id" => "sometimes|numeric",
        ];
        
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json(["error" =>$validate->errors(), 'code' => 400])->header("Content-Type", "application/json");
        }
            
        $query = Addresses::select("*")
                ->with(["country"=>function($q){
                    $q->select("*");
                }]);
        
        
        if($id){
            $returnData =  $query->where("id",$id)->first();
        }
        else{

            if($request->search) {
                $query->where(function($q) use ($request) {

                    $q->where("name", "LIKE", "%{$request->search}%")
                    ->orWhere("address","LIKE", "%{$request->search}%")
                    ->orWhere("address2","LIKE", "%{$request->search}%")
                    ->orWhere("postal_code","LIKE", "%{$request->search}%")
                    ->orWhere("city","LIKE", "%{$request->search}%")
                    ->orWhere("province","LIKE", "%{$request->search}%")
                    ->orWhere("phone","LIKE", "%{$request->search}%")

                    ->orWhereHas("country", function($q) use ($request) {
                        $q->where("name","LIKE", "%{$request->search}%")
                        ->orWhere("alpha2_code","LIKE", "%{$request->search}%")
                        ->orWhere("alpha3_code","LIKE", "%{$request->search}%");
                    });

                });
            }
            $returnData = $query->orderBy("deleted","ASC")->paginate(env("PAGINATION"));
        }
        
        
        if(empty($returnData)) {
            return response()->json(["msg" => trans("messages.data_not_found"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        
        return response()->json(["data" => $returnData, "code" => 200])->header("Content-Type", "application/json");
       

    }


    /*
    |--------------------------------------------------------------------------
    | Create the address
    |--------------------------------------------------------------------------
    | URL - /create
    | @param name  - required and string
    | @param address  - required and string
    | @param address2 - required and string
    | @param postal_code - required and string
    | @param city - required and string
    | @param province - required and string
    | @param phone - required and numeric
    | @param id_country - required and numeric
    |
    | function create the address if not same match in DB table     
    */

    public function createAddress(Request $request){

        

        $rules = [            
            "name" => "required|min:6|max:255",           
            "address"  => "required|min:6|max:255",
            "address2"  => "max:255",
            "postal_code"  => "required|max:6",
            "city"  => "required|min:3|max:25",
            "province"  => "required|min:2|max:25",
            "phone"  => "required|numeric|digits:10",
            "id_country" => "required|numeric|digits_between:1,3|exists:countries,id"
        ];
        
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json(["error" =>$validate->errors(), 'code' => 400])->header("Content-Type", "application/json");
        }

        $address = Addresses::firstOrCreate([
            "name" => $request->name,
            "address" => $request->address,
            "address2" => $request->address2,
            "postal_code" => $request->postal_code,
            "city" => $request->city,
            "province" => $request->province,
            "phone" => $request->phone,
            "id_country" => $request->id_country
        ]);

        if($address->wasRecentlyCreated){
            return response()->json(["msg" => trans("messages.data_inserted"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        else{
            return response()->json(["msg" => trans("messages.data_exists"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        
    }

    /*
    |--------------------------------------------------------------------------
    | Update the address
    |--------------------------------------------------------------------------
    | URL - /update
    | @param id  - required and numeric
    | @param name  - required and string
    | @param address  - required and string
    | @param address2 - required and string
    | @param postal_code - required and string
    | @param city - required and string
    | @param province - required and string
    | @param phone - required and numeric
    | @param id_country - required and numeric
    |
    | function update the address as per given ID
    */

    public function updateAddress(Request $request){

        $rules = [   
            "id" => "required|numeric",       
            "name" => "required|min:6|max:255",           
            "address"  => "required|min:6|max:255",
            "address2"  => "max:255",
            "postal_code"  => "required|max:6",
            "city"  => "required|min:3|max:25",
            "province"  => "required|min:2|max:25",
            "phone"  => "required|numeric|digits:10",
            "id_country" => "required|numeric|digits_between:1,3|exists:countries,id"
        ];
        
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json(["error" =>$validate->errors(), 'code' => 400])->header("Content-Type", "application/json");
        }

        $address = Addresses::find($request->id);
        if ($address === null ) {
            return response()->json(["msg" => trans("messages.data_not_found"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        
        $address->name = $request->name;
        $address->address = $request->address;
        $address->address2 = $request->address2;
        $address->postal_code = $request->postal_code;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->phone = $request->phone;
        $address->id_country = $request->id_country;   
        $address->save();

        if($address->wasChanged()){
            return response()->json(["msg" => trans("messages.data_updated"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        else{
            return response()->json(["msg" => trans("messages.data_no_change"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        
    }


    /*
    |--------------------------------------------------------------------------
    | Delete the address
    |--------------------------------------------------------------------------
    | URL - /delete
    | @param id - required and numeric
    |
    | function update deleted column to 0 [ Soft-delete ]  
    */

    public function deleteAddress(Request $request){

        $rules = [   
            "id" => "required|numeric"
        ];
        
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json(["error" =>$validate->errors(), 'code' => 400])->header("Content-Type", "application/json");
        }

        $address = Addresses::find($request->id);
       
        if ($address === null ) {
            return response()->json(["msg" => trans("messages.data_not_found"), "code" => 204 ])->header("Content-Type", "application/json");
        }
        elseif($address->deleted){
            return response()->json(["msg" => trans("messages.data_previously_deleted"), "code" => 200 ])->header("Content-Type", "application/json");
        }
        else{
            $address->deleted = 1;
            $address->save();
            if($address->wasChanged()){
                return response()->json(["msg" => trans("messages.data_deleted"), "code" => 200 ])->header("Content-Type", "application/json");
            }
        }
        
    }
}


            
