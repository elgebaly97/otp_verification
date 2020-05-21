<?php

namespace App\Http\Controllers\Api;

use App\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    private function apiResponse($status, $message, $data=null){
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data

        ];
        return response()->json($response);
    }

    public function otps(){
        $otp = new Otp();
        $otp->otp = rand(100000, 999999);
        $otp->save();

        return $this->apiResponse(1, '', $otp);
    }

    public function verification(Request $request){
        $validator = validator()->make($request->all(), [
            'otp' => 'required'
        ]);
        if($validator->fails()){
            return $this->apiResponse(0, $validator->errors()->first(), $validator->errors());
        }
        $otp = Otp::all()->last();
        if($request->otp == $otp->otp){
            return $this->apiResponse(1, 'Success');
        } else {
            return $this->apiResponse(2, 'Failed');
        }
    }
}
