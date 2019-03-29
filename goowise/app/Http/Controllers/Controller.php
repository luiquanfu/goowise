<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public function ip_address()
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip_forward = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ip_address = $ip_forward[0];
		}
		return $ip_address;
	}
	
	public function user_agent()
	{
		$user_agent = '';
		if(isset($_SERVER['HTTP_USER_AGENT']))
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
		}
		return $user_agent;
	}
	
	public function unique_id()
	{
		$random = str_pad(rand(1, 999999999), 9, 0, STR_PAD_LEFT);
		return time().$random;
	}

	public function unique_token()
	{
		$random = str_pad(rand(1, 999999999), 9, 0, STR_PAD_LEFT);
		return md5(time().$random);
	}

}
