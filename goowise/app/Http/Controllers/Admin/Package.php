<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Package extends Controller
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

        \Log::info('admin '.$api_token.' list package page '.$page);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // update admin
        $last_visit = array();
        $last_visit['page'] = 'package_listing';
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
        $sorts[] = 'name';
        $sorts[] = 'lock_period';
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

        // get packages
        $query = \DB::connection('mysql')->table('packages');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $query->select($select);
        $total_packages = $query->count();
        if(strlen($filter_name) != 0)
        {
            $query->where('name', 'like', '%'.$filter_name.'%');
        }
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $packages = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['packages'] = $packages;
        $response['total_packages'] = $total_packages;
        $response['total_pages'] = ceil($total_packages / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $name = $request->get('name');

        \Log::info('admin '.$api_token.' add package');

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
            $response['message'] = 'Package name is required';
            return $response;
        }

        // insert package
        $package_id = $this->unique_id();
        $data = array();
        $data['id'] = $package_id;
        $data['name'] = $name;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('packages')->insert($data);

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
        $package_id = $request->get('package_id');

        \Log::info('admin '.$api_token.' edit package '.$package_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get package
        $query = \DB::connection('mysql')->table('packages');
        $select = array();
        $select[] = 'id';
        $select[] = 'name';
        $query->select($select);
        $query->where('id', $package_id);
        $package = $query->first();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['package'] = $package;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $package_id = $request->get('package_id');
        $name = $request->get('name');

        \Log::info('admin '.$api_token.' update package '.$package_id);

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
            $response['message'] = 'Package name is required';
            return $response;
        }

        // update package
        $data = array();
        $data['name'] = $name;
        $data['updated_at'] = time();
        \DB::table('packages')->where('id', $package_id)->update($data);

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
        $package_id = $request->get('package_id');

        \Log::info('admin '.$api_token.' destroy package '.$package_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete package
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('packages')->where('id', $package_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
