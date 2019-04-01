<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Home extends Controller
{
    public function index()
    {
        // get cookie
        $api_token = '';
        if(isset($_COOKIE['admin_token']))
        {
            $api_token = $_COOKIE['admin_token'];
        }

        // response
        $data = array();
        $data['app_url'] = url();
        $data['api_token'] = $api_token;
        return view('admin.index', $data);
    }

    public function initialize(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');

        \Log::info('Admin '.$api_token.' initialize');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }

    public function login(Request $request)
    {
        // set variables
        $email = $request->get('email');
        $password = $request->get('password');

        \Log::info('Admin login '.$email);

        // get admin
        $query = \DB::table('admins');
        $query->select('id', 'password');
        $query->where('email', $email);
        $query->where('deleted_at', 0);
        $admin = $query->first();

        // if admin not found
        if($admin == null)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email or password is invalid';
            return $response;
        }

        // if password not match
        if(\Hash::check($password, $admin->password) == false)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email or password is invalid';
            return $response;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
