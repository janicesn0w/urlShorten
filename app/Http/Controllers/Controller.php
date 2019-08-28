<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Log;
use App\Model\Link;
use Illuminate\Support\Facades\Redirect;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function shortenUrl() {
    	$validator = Validator::make(Input::all(),[
    		'url' => 'required|url|max:225'
    	]);
 		
 		if($validator->fails()){	
 			return redirect('/')->withInput()->withErrors($validator);
 		}else{
 			$url = Input::get('url');
 			$code = null;
 			$exist = Link::where('url',$url);

 			if($exist->count() ===1){
 				$code = $exist->first()->code;
 				return redirect('/')->with('status','Your shorten url is: <a href="'.route('toUrl',$code).'">'.route('toUrl',$code).'</a>');
 			} else {
 				$code = base_convert(time().$url, 10, 36);
 				$create = Link::create([
 					'url'=>$url,
 					'code'=>$code
 				]);

	 			if($create){
 				return redirect('/')->with('status','Your shorten url is: <a href="'.route('toUrl',$code).'">'.route('toUrl',$code).'</a>');
	 			}
 			}

 		}
			return redirect('/')->with('status','Opss! Something went wrong.');
    }

    public function toUrl($code) {
    	$link = Link::where('code',$code);

    	if(!$link->count()){

    		return redirect('/');
    	} else {
    		return redirect($link->first()->url); 
    	} 
    }
}
