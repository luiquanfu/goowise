<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Rate extends Controller
{
    public function list(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $page = $request->get('page');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $filter_name = $request->get('filter_name');
        $paginate = 10;
        
        \Log::info('Admin '.$api_token.' list rate page '.$page);

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
        $sorts[] = 'interest';
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

        // get rates
        $query = \DB::connection('mysql')->table('rates');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $select[] = 'interest';
        $query->select($select);
        $total_rates = $query->count();
        if(strlen($filter_name) != 0)
        {
            $query->where('name', 'like', '%'.$filter_name.'%');
        }
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $rates = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['rates'] = $rates;
        $response['total_rates'] = $total_rates;
        $response['total_pages'] = ceil($total_rates / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $name = $request->get('name');
        $interest = $request->get('interest');

        \Log::info('Admin '.$api_token.' add rate');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate name
        if(strlen($name) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Rate name is required';
            return $response;
        }

        // validate interest
        if(strlen($interest) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Interest is required';
            return $response;
        }

        // insert rate
        $data = array();
        $data['id'] = $this->unique_id();
        $data['name'] = $name;
        $data['interest'] = $interest;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('rates')->insert($data);

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
        $rate_id = $request->get('rate_id');

        \Log::info('Admin '.$api_token.' edit rate '.$rate_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get rate
        $query = \DB::connection('mysql')->table('rates');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $select[] = 'interest';
        $query->select($select);
        $query->where('id', $rate_id);
        $rate = $query->first();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['rate'] = $rate;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $rate_id = $request->get('rate_id');
        $name = $request->get('name');
        $interest = $request->get('interest');

        \Log::info('Admin '.$api_token.' update rate '.$rate_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // validate name
        if(strlen($name) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Rate name is required';
            return $response;
        }

        // validate interest
        if(strlen($interest) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Interest is required';
            return $response;
        }
        
        // update rate
        $data = array();
        $data['name'] = $name;
        $data['interest'] = $interest;
        \DB::table('rates')->where('id', $rate_id)->update($data);

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
        $rate_id = $request->get('rate_id');

        \Log::info('Admin '.$api_token.' destroy rate '.$rate_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete rate
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('rates')->where('id', $rate_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
