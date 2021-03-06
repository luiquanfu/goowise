<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Home extends Controller
{
    public function index()
    {
        // get cookie
        $api_token = '';
        if(isset($_COOKIE['advisor_token']))
        {
            $api_token = $_COOKIE['advisor_token'];
        }
        \Log::info('advisor '.$api_token.' home');

        // get device_id
        $device_id = $this->unique_id();
        if(isset($_COOKIE['device_id']))
        {
            $device_id = $_COOKIE['device_id'];
        }
        setcookie('device_id', $device_id, time() + (60 * 60 * 24 * 365 * 1), '/');

        // response
        $data = array();
        $data['app_url'] = url();
        $data['api_token'] = $api_token;
        $data['device_id'] = $device_id;
        return view('advisor.index', $data);
    }

    public function initialize(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        \Log::info('advisor '.$api_token.' initialize');

        // validate api_token
        $response = $this->check_advisor($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $advisor = $response['advisor'];

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['advisor'] = $advisor;
        return $response;
    }

    public function login(Request $request)
    {
        // set variables
        $email = $request->get('email');
        $password = $request->get('password');
        $device_id = $request->get('device_id');
        $device_type = $request->get('device_type');
        \Log::info('advisor login '.$device_type.' '.$email);

        // validate device_id
        if(strlen($device_id) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Device ID is invalid';
            return $response;
        }

        // validate device_type
        if(strlen($device_type) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Device type is invalid';
            return $response;
        }

        // get advisor
        $query = \DB::table('advisors');
        $query->select('id', 'password');
        $query->where('email', $email);
        $query->where('deleted_at', 0);
        $advisor = $query->first();

        // if advisor not found
        if($advisor == null)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email or password is invalid';
            return $response;
        }

        // if password not match
        if(\Hash::check($password, $advisor->password) == false)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email or password is invalid';
            return $response;
        }

        // get advisor_token
        $query = \DB::table('advisor_tokens');
        $query->select('id', 'api_token');
        $query->where('advisor_id', $advisor->id);
        $query->where('device_id', $device_id);
        $query->where('deleted_at', 0);
        $advisor_token = $query->first();

        // if advisor_token not found
        if($advisor_token == null)
        {
            // create advisor_token
            $advisor_token = (object)[];
            $advisor_token->id = $this->unique_id();
            $advisor_token->advisor_id = $advisor->id;
            $advisor_token->device_id = $device_id;
            $advisor_token->device_type = $device_type;
            $advisor_token->ip_address = $this->ip_address();
            $advisor_token->user_agent = $this->user_agent();
            $advisor_token->api_token = $this->unique_token();
            $advisor_token->created_at = time();
            $advisor_token->updated_at = time();
            \DB::table('advisor_tokens')->insert($this->fields($advisor_token));
        }

        setcookie('advisor_token', $advisor_token->api_token, time() + (60 * 60 * 24 * 365 * 1), '/');

        // modify advisor
        $advisor->api_token = $advisor_token->api_token;

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['advisor'] = $advisor;
        return $response;
    }

    public function logout(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        \Log::info('advisor '.$api_token.' logout');

        // update advisor_token
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('advisor_tokens')->where('api_token', $api_token)->update($data);
        setcookie('advisor_token', null, time() - 1, '/');

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
