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
        // $random = str_pad(rand(1, 999999999), 9, 0, STR_PAD_LEFT);
        $random = strtoupper(str_random(10));
        return time().$random;
    }

    public function unique_token()
    {
        // $random = str_pad(rand(1, 999999999), 9, 0, STR_PAD_LEFT);
        $random = strtoupper(str_random(10));
        return time().$random;
    }

    public function check_admin($api_token)
    {
        // get admin_token
        $query = \DB::table('admin_tokens');
        $query->select('admin_id');
        $query->where('api_token', $api_token);
        $query->where('deleted_at', 0);
        $admin_token = $query->first();

        // if admin_token not found
        if($admin_token == null)
        {
            \Log::info('admin '.$api_token.' token not found');

            $response = array();
            $response['error'] = 99;
            $response['message'] = 'Admin token not found';
            return $response;
        }

        // get admin
        $query = \DB::table('admins');
        $query->select('id', 'firstname', 'email', 'last_visit');
        $query->where('id', $admin_token->admin_id);
        $query->where('deleted_at', 0);
        $admin = $query->first();

        // if admin not found
        if($admin == null)
        {
            \Log::info('admin '.$api_token.' not found');

            $response = array();
            $response['error'] = 99;
            $response['message'] = 'Admin not found';
            return $response;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['admin_token'] = $admin_token;
        $response['admin'] = $admin;
        return $response;
    }

    public function check_advisor($api_token)
    {
        // get advisor_token
        $query = \DB::table('advisor_tokens');
        $query->select('advisor_id');
        $query->where('api_token', $api_token);
        $query->where('deleted_at', 0);
        $advisor_token = $query->first();

        // if advisor_token not found
        if($advisor_token == null)
        {
            \Log::info('advisor '.$api_token.' token not found');

            $response = array();
            $response['error'] = 99;
            $response['message'] = 'Advisor token not found';
            return $response;
        }

        // get advisor
        $query = \DB::table('advisors');
        $query->select('id', 'firstname', 'email', 'last_visit');
        $query->where('id', $advisor_token->advisor_id);
        $query->where('deleted_at', 0);
        $advisor = $query->first();

        // if advisor not found
        if($advisor == null)
        {
            \Log::info('advisor '.$api_token.' not found');

            $response = array();
            $response['error'] = 99;
            $response['message'] = 'Advisor not found';
            return $response;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['advisor_token'] = $advisor_token;
        $response['advisor'] = $advisor;
        return $response;
    }

    function fields($object)
    {
        $fields = array();
        foreach($object as $key => $value)
        {
            $fields[$key] = $value;
        }
        return $fields;
    }

    function wrap_currency($amount, $symbol, $direction, $decimal, $negative)
    {
        // convert integer to decimal amount
        $amount = $amount / pow(10, $decimal);
        
        // convert number to string
        $string = number_format($amount, $decimal);

        // handle negative number
        if($amount < 0)
        {
            if($negative == 'none')
            {
                $string = number_format($amount * -1, $decimal);
            }

            if($negative == 'minus')
            {
                $string = number_format($amount * -1, $decimal);
            }

            if($negative == 'bracket')
            {
                $string = '('.number_format($amount * -1, $decimal).')';
            }
        }
        
        // add currency symbol to string
        if($direction == 'left')
        {
            $string = $symbol.$string;
        }
        if($direction == 'right')
        {
            $string = $string.$symbol;
        }
        
        // if negative is minus
        if($amount < 0)
        {
            if($negative == 'minus')
            {
                $string = '-'.$string;
            }
        }
        
        // return
        return $string;
    }
}
