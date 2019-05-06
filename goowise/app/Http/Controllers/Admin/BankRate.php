<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BankRate extends Controller
{
    public function list(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $page = $request->get('page');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $paginate = 10;
        \Log::info('Admin '.$api_token.' list bank_rate page '.$page);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

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
        $sorts[] = 'name';
        $sorts[] = 'year';
        $sorts[] = 'interest_rate';
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

        // get bank_rates
        $query = \DB::connection('mysql')->table('bank_rates');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'name';
        $select[] = 'loan_type';
        $select[] = 'lock_period';
        $select[] = 'year';
        $select[] = 'rate';
        $select[] = 'interest_rate';
        $query->select($select);
        $total_bank_rates = $query->count();
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $bank_rates = $query->get();

        // modify bank_rates
        foreach($bank_rates as $bank_rate)
        {
            $query = \DB::connection('mysql')->table('banks');
            $query->select('name');
            $query->where('id', $bank_rate->bank_id);
            $bank = $query->first();
            $bank_rate->bank_name = 'NA';
            if($bank) $bank_rate->bank_name = $bank->name;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['bank_rates'] = $bank_rates;
        $response['total_bank_rates'] = $total_bank_rates;
        $response['total_pages'] = ceil($total_bank_rates / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function create(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');

        \Log::info('Admin '.$api_token.' create bank_rate');

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
        $name = $request->get('name');
        $year = $request->get('year');

        \Log::info('Admin '.$api_token.' add bank_rate');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate bank_id
        if(strlen($bank_id) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Bank is required';
            return $response;
        }

        // validate name
        if(strlen($name) <= 2)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Bank loan name is required at least 3 chars long';
            return $response;
        }

        // validate year
        if(strlen($year) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Year is required';
            return $response;
        }

        // insert bank_rate
        $data = array();
        $data['id'] = $this->unique_id();
        $data['bank_id'] = $bank_id;
        $data['name'] = $name;
        $data['year'] = $year;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('bank_rates')->insert($data);

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
        $bank_rate_id = $request->get('bank_rate_id');

        \Log::info('Admin '.$api_token.' edit bank_rate '.$bank_rate_id);

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

        // get bank_rate
        $query = \DB::connection('mysql')->table('bank_rates');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'name';
        $select[] = 'year';
        $query->select($select);
        $query->where('id', $bank_rate_id);
        $bank_rate = $query->first();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['banks'] = $banks;
        $response['bank_rate'] = $bank_rate;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_rate_id = $request->get('bank_rate_id');
        $bank_id = $request->get('bank_id');
        $name = $request->get('name');
        $year = $request->get('year');

        \Log::info('Admin '.$api_token.' update bank_rate '.$bank_rate_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate bank_id
        if(strlen($bank_id) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Bank is required';
            return $response;
        }

        // validate name
        if(strlen($name) <= 2)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Bank loan name is required at least 3 chars long';
            return $response;
        }

        // validate year
        if(strlen($year) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Year is required';
            return $response;
        }
        
        // update bank_rate
        $data = array();
        $data['bank_id'] = $bank_id;
        $data['name'] = $name;
        $data['year'] = $year;
        \DB::table('bank_rates')->where('id', $bank_rate_id)->update($data);

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
        $bank_rate_id = $request->get('bank_rate_id');

        \Log::info('Admin '.$api_token.' destroy bank_rate '.$bank_rate_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete bank_rate
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('bank_rates')->where('id', $bank_rate_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
