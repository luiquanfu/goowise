<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Advisor extends Controller
{
    public function listing(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $page = $request->get('page');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $filter_name = $request->get('filter_name');
        $paginate = 10;

        \Log::info('admin '.$api_token.' list advisor page '.$page);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // update admin
        $last_visit = array();
        $last_visit['page'] = 'advisor_listing';
        $data = array();
        $data['last_visit'] = json_encode($last_visit);
        \DB::table('admins')->where('id', $admin->id)->update($data);

        // validate page
        if($page < 1)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Page is invalid';
            return $response;
        }

        // validate sort
        $sorts = array();
        $sorts[] = 'id';
        $sorts[] = 'firstname';
        $sorts[] = 'lastname';
        $sorts[] = 'mobile';
        $sorts[] = 'email';
        if(in_array($sort, $sorts) == null)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Sort is invalid';
            return $response;
        }

        // validate direction
        $directions = array();
        $directions[] = 'asc';
        $directions[] = 'desc';
        if(in_array($direction, $directions) == null)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Direction is invalid';
            return $response;
        }

        // get advisors
        $query = \DB::connection('mysql')->table('advisors');
        $select = array();
        $select[] = 'id';
        $select[] = 'firstname';
        $select[] = 'lastname';
        $select[] = 'mobile';
        $select[] = 'email';
        $query->select($select);
        $total_advisors = $query->count();
        if(strlen($filter_name) != 0)
        {
            $query->where(function($query) use($filter_name)
            {
                $query->where('firstname', 'like', '%'.$filter_name.'%');
                $query->orWhere('lastname', 'like', '%'.$filter_name.'%');
            });
        }
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $advisors = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['advisors'] = $advisors;
        $response['total_advisors'] = $total_advisors;
        $response['total_pages'] = ceil($total_advisors / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function create(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');

        \Log::info('admin '.$api_token.' create advisor');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

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
        return $response;
    }

    public function add(Request $request)
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

        \Log::info('admin '.$api_token.' add advisor');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

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

        // validate password
        if(strlen($password) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Password is required';
            return $response;
        }

        // check email
        $query = \DB::connection('mysql')->table('advisors');
        $query->where('email', $email);
        $query->where('deleted_at', 0);
        $advisor = $query->count();
        if($advisor != null)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Email is taken';
            return $response;
        }

        // insert advisor
        $data = array();
        $data['id'] = $this->unique_id();
        $data['bank_id'] = $bank_id;
        $data['nric'] = $nric;
        $data['firstname'] = $firstname;
        $data['lastname'] = $lastname;
        $data['mobile'] = $mobile;
        $data['email'] = $email;
        $data['password'] = bcrypt($password);
        $data['bank_account'] = $bank_account;
        $data['address'] = $address;
        $data['postal_code'] = $postal_code;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('advisors')->insert($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }

    public function edit(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $advisor_id = $request->get('advisor_id');

        \Log::info('admin '.$api_token.' edit advisor '.$advisor_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

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
        $query->where('id', $advisor_id);
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
        $advisor_id = $request->get('advisor_id');
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

        \Log::info('admin '.$api_token.' update advisor '.$advisor_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

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
        $query->where('id', '!=', $advisor_id);
        $query->where('email', $email);
        $query->where('deleted_at', 0);
        $advisor = $query->count();
        if($advisor != null)
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
        \DB::table('advisors')->where('id', $advisor_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }

    public function destroy(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $advisor_id = $request->get('advisor_id');

        \Log::info('admin '.$api_token.' destroy advisor '.$advisor_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete advisor
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('advisors')->where('id', $advisor_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
