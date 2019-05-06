<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Bank extends Controller
{
    // * @param float $apr   Interest rate.
    // * @param integer $term  Loan length in years. 
    // * @param float $loan   The loan amount.
    // $term = $term * 12;
    // $apr = $apr / 1200;
    // $amount = $apr * -$loan * pow((1 + $apr), $term) / (1 - pow((1 + $apr), $term));
    // return round($amount);

    public function list(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $page = $request->get('page');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $filter_name = $request->get('filter_name');
        $paginate = 10;
        \Log::info('Admin '.$api_token.' list bank page '.$page);

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

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $total_banks = $query->count();
        if(strlen($filter_name) != 0)
        {
            $query->where('name', 'like', '%'.$filter_name.'%');
        }
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $banks = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['banks'] = $banks;
        $response['total_banks'] = $total_banks;
        $response['total_pages'] = ceil($total_banks / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $name = $request->get('name');

        \Log::info('Admin '.$api_token.' add bank');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate name
        if(strlen($name) <= 2)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Bank name is required at least 3 chars long';
            return $response;
        }

        // insert bank
        $data = array();
        $data['id'] = $this->unique_id();
        $data['name'] = $name;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('banks')->insert($data);

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
        $bank_id = $request->get('bank_id');

        \Log::info('Admin '.$api_token.' edit bank '.$bank_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get bank
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('id', $bank_id);
        $bank = $query->first();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['bank'] = $bank;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_id = $request->get('bank_id');
        $name = $request->get('name');

        \Log::info('Admin '.$api_token.' update bank '.$bank_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate name
        if(strlen($name) <= 2)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Bank name is required at least 3 chars long';
            return $response;
        }
        
        // update bank
        $data = array();
        $data['name'] = $name;
        \DB::table('banks')->where('id', $bank_id)->update($data);

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
        $bank_id = $request->get('bank_id');

        \Log::info('Admin '.$api_token.' destroy bank '.$bank_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete bank
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('banks')->where('id', $bank_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
