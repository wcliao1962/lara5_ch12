<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as UserEloquent;
use App\SocialUser as SocialUserEloquent;
use App;
use Auth;
use Config;
use Redirect;
use Socialite;

class SocialController extends Controller
{
    public function getSocialRedirect($provider){
	    $providerKey = Config::get('services.' . $provider);
	    if(empty($providerKey)){
	        return App::abort(404);
	    }
	    return Socialite::driver($provider)->redirect();
	}
	
	public function getSocialCallback($provider, Request $request){
	    //判斷第三方平台回傳有沒有出錯
	    if($request->exists('error_code')){
            return Redirect::action('Auth\LoginController@showLoginForm')
                ->withErrors([
	                'msg' => $provider . '登入或綁定失敗，請重新再試'
                ]);
	    }

	    //從第三方平台抓取資料
	    $socialite_user = Socialite::with($provider)->user();
	
	    //因為middleware設定為guest，所以如果登入的情況下是不會進入此路由
	    //所以能進來執行此函式代表一定是未登入的使用者
	    //會使用第三方登入不外乎就是要 登入 或是 註冊+登入
		$login_user = null;
	
	    //先從資料庫 看看此第三方登入的ID是否已經被其他使用者綁定了
		$s_u = SocialUserEloquent::where('provider_user_id', $socialite_user->id)->where('provider', $provider)->first();
	    if(!empty($s_u)){
	        //第三方登入的ID使用了，代表已經有註冊了
	        //我們只需要讓其對應的使用者帳號，登入即可
	        $login_user = $s_u->user;
	    }else{
	        //代表此第三方登入沒有被紀錄在資料表 代表沒被用過 有兩個情況
            //一個是他沒有註冊過會員 同時也沒有使用第三方帳號 => 註冊+綁定+登入
	        //一個是他有註冊過會員 但沒綁定過此第三方帳號(綁定+登入)
	        //注意沒綁定過此第三方帳號 但他"可能"已經綁定其他第三方帳號！
		    //我們首先要先判斷 該第三方平台 所提供的信箱 是有已經使用過
	        //但有些人可能第三方平台沒有綁定信箱(Facebook會發生此情形) 所以信箱會回傳null 所以需要先判斷以免發生程式錯誤
	        if (empty($socialite_user->email)) {
	            return Redirect::action('Auth\LoginController@showLoginForm')
	                ->withErrors([
	                    'msg' => '很抱歉，我們無法從您的' . $provider . '帳號抓到信箱，請用其他方式註冊帳號謝謝!'
	                ]);
	        }
	
	        //從user資料表看看有沒有已經註冊過的email
			$user = UserEloquent::where('email', $socialite_user->email)->first();
	        if(!empty($user)){
	            //代表此email被使用了 已經註冊了 所以我們要做的只是把此帳號跟此第三方做(綁定)
				$login_user = $user;
	
	            //但我們要確認此使用者帳號是否有綁定第三方
				$s_user = $login_user->socialUser;
				if (!empty($s_user)) {
	                //因為先前已經先用第三方提供的ID來搜尋資料庫其對應的user帳號
	                //會走到這一步代表沒有其對應的user帳號
	                //但如果此user帳號是有綁定其他的第三方平台帳號
	                //一定不可能是目前所使用的第三方平台
	                return Redirect::action('Auth\LoginController@showLoginForm')
	                        ->withErrors([
	                            'msg' => '此email已被其他帳號綁定了，請使用其他登入方式'
	                        ]);
				}else{
	                //代表這個帳號沒有任何綁定第三方平台 所以接下來就是進行(綁定+登入)
	                //建立第三方登入資料並綁定
	                $login_user->socialUser = SocialUserEloquent::create([
	                    'provider_user_id' => $socialite_user->id,
	                    'provider' => $provider,
	                    'user_id' => $login_user->id
	                ]);
				}
			}else{
				//代表連網站的會員都沒有註冊 所以就是自動幫忙(註冊+綁定+登入)
	
				//建立user資料
				$login_user = UserEloquent::create([
					'email' => $socialite_user->email,
					'password' => bcrypt(str_random(8)),
					'name' => $socialite_user->name,
				]);
	
	            //建立第三方登入資料
				$login_user->socialUser = SocialUserEloquent::create([
				    'provider_user_id' => $socialite_user->id,
	                'provider' => $provider,
	                'user_id' => $login_user->id
				]);
			}
	    }
	           
	    if(!is_null($login_user)){
	        Auth::login($login_user);
            return Redirect::action('HomeController@index');
        }
        return App::abort(500);
    }
}
