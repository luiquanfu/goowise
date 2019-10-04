<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Profile extends Controller
{
    public function edit(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');

        \Log::info('advisor '.$api_token.' edit profile');

        // validate api_token
        $response = $this->check_advisor($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $advisor = $response['advisor'];

        // get advisor
        $query = \DB::connection('mysql')->table('advisors');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'nric';
        $select[] = 'firstname';
        $select[] = 'lastname';
        $select[] = 'mobile';
        $select[] = 'email';
        $select[] = 'bank_account';
        $select[] = 'address';
        $select[] = 'postal_code';
        $query->select($select);
        $query->where('id', $advisor->id);
        $advisor = $query->first();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $banks = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['banks'] = $banks;
        $response['advisor'] = $advisor;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_id = $request->get('bank_id');
        $nric = $request->get('nric');
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $mobile = $request->get('mobile');
        $email = $request->get('email');
        $password = $request->get('password');
        $bank_account = $request->get('bank_account');
        $address = $request->get('address');
        $postal_code = $request->get('postal_code');

        \Log::info('advisor '.$api_token.' update profile');

        // validate api_token
        $response = $this->check_advisor($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $advisor = $response['advisor'];

        // validate firstname
        if(strlen($firstname) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Advisor name is required';
            return $response;
        }

        // validate email
        if(strlen($email) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email is required';
            return $response;
        }

        // check email
        $query = \DB::connection('mysql')->table('advisors');
        $query->where('id', '!=', $advisor->id);
        $query->where('email', $email);
        $query->where('deleted_at', 0);
        $result = $query->count();
        if($result != null)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email is taken';
            return $response;
        }
        
        // update advisor
        $data = array();
        $data['bank_id'] = $bank_id;
        $data['nric'] = $nric;
        $data['firstname'] = $firstname;
        $data['lastname'] = $lastname;
        $data['mobile'] = $mobile;
        $data['email'] = $email;
        if(strlen($password) != 0)
        {
            $data['password'] = bcrypt($password);
        }
        $data['bank_account'] = $bank_account;
        $data['address'] = $address;
        $data['postal_code'] = $postal_code;
        $data['updated_at'] = time();
        \DB::table('advisors')->where('id', $advisor->id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
