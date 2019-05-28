<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = array();
        $data['app_url'] = url();
        return view('website.index', $data);
    }

    public function register(Request $request)
    {
        //validate name
        if(strlen($request->name) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Name is required';
            return $response;
        }
        if(strlen($request->name) < 3)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Name should be more than 3 chars';
            return $response;
        }

        //validate email
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL) == false)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Please use a valid email address';
            return $response;
        }

        //validate mobile
        if(strlen($request->mobile) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Mobile is required';
            return $response;
        }
        if(preg_match('/^[0-9]+$/', $request->mobile) == false)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Mobile number is invalid';
            return $response;
        }
        if(strlen($request->mobile) < 8)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Mobile should be 8 numbers';
            return $response;
        }

        //get user_email
        $query = \DB::table('users');
        $query->select('id');
        $query->where('email', $request->email);
        $query->where('deleted_at', 0);
        $user_email = $query->first();

        //get user_mobile
        $query = \DB::table('users');
        $query->select('id');
        $query->where('mobile', $request->mobile);
        $query->where('deleted_at', 0);
        $user_mobile = $query->first();

        //if user exists
        if($user_email != false && $user_mobile != false)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Thank you, please wait for our confirmation';
            return $response;
        }

        $ip_address = $this->ip_address();
        $api_key = '6LdGaZIUAAAAAG849niK8lqPfysnDdFbNOlYAeSa';
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        //prepare data
        $data = array();
        $data['secret'] = $api_key;
        $data['response'] = $request->google;
        $data['remoteip'] = $ip_address;

        //send to google
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);

        //if google reject
        if($result->success == false)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Please check that you are not a robot';
            return $response;
        }

        //create user
        $user = (object)[];
        $user->id = $this->unique_id();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->reason = $request->reason;
        $user->ip_address = $ip_address;
        $user->user_agent = $this->user_agent();

        //insert user
        $data = array();
        $data['id'] = $user->id;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['mobile'] = $user->mobile;
        $data['reason'] = $user->reason;
        $data['ip_address'] = $user->ip_address;
        $data['user_agent'] = $user->user_agent;
        $data['created_at'] = time();
        \DB::table('users')->insert($data);

        //send email
        $data = array();
        $data['user'] = $user;
        \Mail::send('emails.website.register', $data, function ($mail) use ($user)
        {
            $mail->from('enquiry@goowise.com', 'Goowise');
            $mail->to('enquiry@goowise.com')->subject('Register - '.$user->name);
        });

        //success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Thank you for the interest. We will send a confirmation email to you!';
        return $response;
    }

    public function test()
    {
        $last_visit = array();
        $last_visit['page'] = 'bank_listing';
        return json_encode($last_visit);
    }
}
