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

    	//check the format of the text to make sure it's in url format
    	$validator = Validator::make(Input::all(),[
    		'url' => 'required|url|max:225'
    	]);
 		
 		if($validator->fails()){	
 			//if validator fails then return error to page
 			return redirect('/')->withInput()->withErrors($validator);
 		} else {
 			//initialize value
 			$url = Input::get('url');
 			$code = null;
 			//check if url entered exist in database
 			$exist = Link::where('url',$url);

 			if($exist->count() ===1){
 				//if exist then return shorten url
 				$code = $exist->first()->code;
 				return redirect('/')->with('status','Your shorten url is: <a href="'.route('toUrl',$code).'">'.route('toUrl',$code).'</a>');
 			} else {
 				/* if not exist then save into database and convert the long url into shorter code using base convert adding timestamp and url inserted, there are probably many other better ways to shorten it.
 				*/
 				$code = base_convert(time().$url, 10, 36);
 				$create = Link::create([
 					'url'=>$url,
 					'code'=>$code
 				]);

	 			if($create){
	 				//once created then return shorten url
 					return redirect('/')->with('status','Your shorten url is: <a href="'.route('toUrl',$code).'">'.route('toUrl',$code).'</a>');
	 			}
 			}
 		}
			return redirect('/')->with('status','Opss! Something went wrong.');
    }

    public function toUrl($code) {
    	//check if the shorten link exist in database
    	$link = Link::where('code',$code);

    	if(!$link->count()){
    		//if not exist then redirect back to home page
    		return redirect('/');
    	} else {
    		//redirect to the original link
    		return redirect($link->first()->url); 
    	} 
    }
}
