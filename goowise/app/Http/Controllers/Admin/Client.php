<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Client extends Controller
{
    public function listing(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $page = $request->get('page');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $filter_name = $request->get('filter_name');
        $filter_nric = $request->get('filter_nric');
        $filter_email = $request->get('filter_email');
        $filter_mobile = $request->get('filter_mobile');
        $paginate = 10;

        \Log::info('admin '.$api_token.' list client page '.$page);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // update admin
        $last_visit = array();
        $last_visit['page'] = 'client_listing';
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
        $sorts[] = 'owner_name';
        $sorts[] = 'owner_nric';
        $sorts[] = 'owner_mobile';
        $sorts[] = 'owner_email';
        $sorts[] = 'postal_code';
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

        // get clients
        $query = \DB::connection('mysql')->table('clients');
        $select = array();
        $select[] = 'id';
        $select[] = 'advisor_id';
        $select[] = 'bank_id';
        $select[] = 'owner_name';
        $select[] = 'owner_nric';
        $select[] = 'owner_mobile';
        $select[] = 'owner_email';
        $select[] = 'joint_name';
        $select[] = 'joint_nric';
        $select[] = 'joint_mobile';
        $select[] = 'joint_email';
        $select[] = 'property_address';
        $select[] = 'postal_code';
        $select[] = 'loan_amount';
        $query->select($select);
        $total_clients = $query->count();
        if(strlen($filter_name) != 0)
        {
            $query->where(function($query) use($filter_name)
            {
                $query->where('owner_name', 'like', '%'.$filter_name.'%');
                $query->orWhere('joint_name', 'like', '%'.$filter_name.'%');
            });
        }
        if(strlen($filter_nric) != 0)
        {
            $query->where(function($query) use($filter_nric)
            {
                $query->where('owner_nric', 'like', '%'.$filter_nric.'%');
                $query->orWhere('joint_nric', 'like', '%'.$filter_nric.'%');
            });
        }
        if(strlen($filter_email) != 0)
        {
            $query->where(function($query) use($filter_email)
            {
                $query->where('owner_email', 'like', '%'.$filter_email.'%');
                $query->orWhere('joint_email', 'like', '%'.$filter_email.'%');
            });
        }
        if(strlen($filter_mobile) != 0)
        {
            $query->where(function($query) use($filter_mobile)
            {
                $query->where('owner_mobile', 'like', '%'.$filter_mobile.'%');
                $query->orWhere('joint_mobile', 'like', '%'.$filter_mobile.'%');
            });
        }
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $clients = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $query->where('deleted_at', 0);
        $banks = $query->get();

        // get advisors
        $query = \DB::connection('mysql')->table('advisors');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $query->where('deleted_at', 0);
        $advisors = $query->get();

        // modify clients
        foreach($clients as $client)
        {
            $client->bank_name = '';
            foreach($banks as $bank)
            {
                if($bank->id == $client->bank_id)
                {
                    $client->bank_name = $bank->name;
                }
            }

            $client->advisor_name = '';
            foreach($advisors as $advisor)
            {
                if($advisor->id == $client->advisor_id)
                {
                    $client->advisor_name = $advisor->firstname.' '.$advisor->lastname;
                }
            }
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['clients'] = $clients;
        $response['total_clients'] = $total_clients;
        $response['total_pages'] = ceil($total_clients / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function create(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');

        \Log::info('admin '.$api_token.' create client');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get advisors
        $query = \DB::connection('mysql')->table('advisors');
        $select = array();
        $select[] = 'id';
        $select[] = 'firstname';
        $select[] = 'lastname';
        $query->select($select);
        $query->where('deleted_at', 0);
        $advisors = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $query->select($select);
        $query->where('deleted_at', 0);
        $banks = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['banks'] = $banks;
        $response['advisors'] = $advisors;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $advisor_id = $request->get('advisor_id');
        $bank_id = $request->get('bank_id');
        $owner_name = $request->get('owner_name');
        $owner_nric = $request->get('owner_nric');
        $owner_mobile = $request->get('owner_mobile');
        $owner_email = $request->get('owner_email');
        $joint_name = $request->get('joint_name');
        $joint_nric = $request->get('joint_nric');
        $joint_mobile = $request->get('joint_mobile');
        $joint_email = $request->get('joint_email');
        $property_address = $request->get('property_address');
        $postal_code = $request->get('postal_code');
        $loan_amount = $request->get('loan_amount');

        \Log::info('admin '.$api_token.' add client');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate owner_name
        if(strlen($owner_name) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Owner name is required';
            return $response;
        }

        // validate loan_amount
        if(strlen($loan_amount) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Loan amount is required';
            return $response;
        }

        // insert client
        $data = array();
        $data['id'] = $this->unique_id();
        $data['advisor_id'] = $advisor_id;
        $data['bank_id'] = $bank_id;
        $data['owner_name'] = $owner_name;
        $data['owner_nric'] = $owner_nric;
        $data['owner_mobile'] = $owner_mobile;
        $data['owner_email'] = $owner_email;
        $data['joint_name'] = $joint_name;
        $data['joint_nric'] = $joint_nric;
        $data['joint_mobile'] = $joint_mobile;
        $data['joint_email'] = $joint_email;
        $data['property_address'] = $property_address;
        $data['postal_code'] = $postal_code;
        $data['loan_amount'] = $loan_amount;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('clients')->insert($data);

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
        $client_id = $request->get('client_id');

        \Log::info('admin '.$api_token.' edit client '.$client_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get client
        $query = \DB::connection('mysql')->table('clients');
        $select = array();
        $select[] = 'id';
        $select[] = 'advisor_id';
        $select[] = 'bank_id';
        $select[] = 'owner_name';
        $select[] = 'owner_nric';
        $select[] = 'owner_mobile';
        $select[] = 'owner_email';
        $select[] = 'joint_name';
        $select[] = 'joint_nric';
        $select[] = 'joint_mobile';
        $select[] = 'joint_email';
        $select[] = 'property_address';
        $select[] = 'postal_code';
        $select[] = 'loan_amount';
        $query->select($select);
        $query->where('id', $client_id);
        $client = $query->first();

        // get advisors
        $query = \DB::connection('mysql')->table('advisors');
        $select = array();
        $select[] = 'id';
        $select[] = 'firstname';
        $select[] = 'lastname';
        $query->select($select);
        $query->where('deleted_at', 0);
        $advisors = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $query->select($select);
        $query->where('deleted_at', 0);
        $banks = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['advisors'] = $advisors;
        $response['client'] = $client;
        $response['banks'] = $banks;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $client_id = $request->get('client_id');
        $advisor_id = $request->get('advisor_id');
        $bank_id = $request->get('bank_id');
        $owner_name = $request->get('owner_name');
        $owner_nric = $request->get('owner_nric');
        $owner_mobile = $request->get('owner_mobile');
        $owner_email = $request->get('owner_email');
        $joint_name = $request->get('joint_name');
        $joint_nric = $request->get('joint_nric');
        $joint_mobile = $request->get('joint_mobile');
        $joint_email = $request->get('joint_email');
        $property_address = $request->get('property_address');
        $postal_code = $request->get('postal_code');
        $loan_amount = $request->get('loan_amount');

        \Log::info('admin '.$api_token.' update client '.$client_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate owner_name
        if(strlen($owner_name) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Owner name is required';
            return $response;
        }

        // validate loan_amount
        if(strlen($loan_amount) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Loan amount is required';
            return $response;
        }

        // update client
        $data = array();
        $data['advisor_id'] = $advisor_id;
        $data['bank_id'] = $bank_id;
        $data['owner_name'] = $owner_name;
        $data['owner_nric'] = $owner_nric;
        $data['owner_mobile'] = $owner_mobile;
        $data['owner_email'] = $owner_email;
        $data['joint_name'] = $joint_name;
        $data['joint_nric'] = $joint_nric;
        $data['joint_mobile'] = $joint_mobile;
        $data['joint_email'] = $joint_email;
        $data['property_address'] = $property_address;
        $data['postal_code'] = $postal_code;
        $data['loan_amount'] = $loan_amount;
        $data['updated_at'] = time();
        \DB::table('clients')->where('id', $client_id)->update($data);

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
        $client_id = $request->get('client_id');

        \Log::info('admin '.$api_token.' destroy client '.$client_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete client
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('clients')->where('id', $client_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}