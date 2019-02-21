<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use View;

class CarbonController extends Controller
{
    public function index(){
    	Carbon::setLocale('zh-tw');
    	$day_str = ['日', '一', '二', '三', '四', '五', '六'];
    	$data=[
    		'tw_now' => Carbon::now(),
    		'ja_now' => Carbon::now('Asia/Tokyo'),
    		'next_year' => Carbon::create((Carbon::now()->year+1), 1, 1, 0),
    		'next_year_day' => ''
    	];
    	$data['next_year_day'] = $day_str[$data['next_year']->dayOfWeek];
    	return View::make('carbon', $data);
    }

}
