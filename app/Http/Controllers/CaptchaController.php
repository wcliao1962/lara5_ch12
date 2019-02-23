<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Validator;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;

class CaptchaController extends Controller
{
    public function index(){
        return View::make('captcha');
    }
    public function captcha(Request $request){
        $rules = [
            'g-recaptcha-response' => ['required', new CaptchaRule],
        ];
        $messages = [
            'g-recaptcha-response.required' => '尚未進行驗證',
            'g-recaptcha-response.captcha'  => '驗證失敗',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return View::make('captcha')->withErrors($validator->messages());
        }else{
            return View::make('captcha')->with([
                'msg' => '驗證成功'
            ]);
        }
    }
}
