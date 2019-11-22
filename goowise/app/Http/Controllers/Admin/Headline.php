<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Headline extends Controller
{
    public function listing(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $page = $request->get('page');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $filter_message = $request->get('filter_message');
        $paginate = 10;

        \Log::info('admin '.$api_token.' list headline page '.$page);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // update admin
        $last_visit = array();
        $last_visit['page'] = 'headline_listing';
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
        $sorts[] = 'updated_at';
        $sorts[] = 'title';
        $sorts[] = 'message';
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

        // get headlines
        $query = \DB::connection('mysql')->table('headlines');
        $select = array();
        $select[] = 'id';
        $select[] = 'admin_id';
        $select[] = 'title';
        $select[] = 'message';
        $select[] = 'updated_at';
        $query->select($select);
        if(strlen($filter_message) != 0)
        {
            $query->where(function($query) use($filter_message)
            {
                $query->where('title', 'like', '%'.$filter_message.'%');
                $query->orWhere('message', 'like', '%'.$filter_message.'%');
            });
        }
        $total_headlines = $query->count();
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $headlines = $query->get();

        // get admins
        $query = \DB::connection('mysql')->table('admins');
        $select = array();
        $select[] = 'id';
        $select[] = 'firstname';
        $select[] = 'lastname';
        $query->where('deleted_at', 0);
        $admins = $query->get();

        // modify headlines
        foreach($headlines as $headline)
        {
            $headline->admin_name = '';
            foreach($admins as $admin)
            {
                if($admin->id == $headline->admin_id)
                {
                    $headline->admin_name = $admin->firstname.' '.$admin->lastname;
                }
            }
            $headline->message = nl2br($headline->message);
            $headline->updated_at = $this->wrap_timezone($headline->updated_at, 8);
            $headline->updated_at = date('d M Y g:i A', $headline->updated_at);
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['headlines'] = $headlines;
        $response['total_headlines'] = $total_headlines;
        $response['total_pages'] = ceil($total_headlines / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $title = $request->get('title');
        $message = $request->get('message');

        \Log::info('admin '.$api_token.' add headline');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // validate title
        if(strlen($title) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Title is required';
            return $response;
        }

        // validate message
        if(strlen($message) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Message is required';
            return $response;
        }

        // insert headline
        $data = array();
        $data['id'] = $this->unique_id();
        $data['admin_id'] = $admin->id;
        $data['title'] = $title;
        $data['message'] = $message;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('headlines')->insert($data);

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
        $headline_id = $request->get('headline_id');

        \Log::info('admin '.$api_token.' edit headline '.$headline_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get headline
        $query = \DB::connection('mysql')->table('headlines');
        $select = array();
        $select[] = 'id';
        $select[] = 'admin_id';
        $select[] = 'title';
        $select[] = 'message';
        $query->select($select);
        $query->where('id', $headline_id);
        $headline = $query->first();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['headline'] = $headline;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $headline_id = $request->get('headline_id');
        $title = $request->get('title');
        $message = $request->get('message');

        \Log::info('admin '.$api_token.' update headline '.$headline_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // validate title
        if(strlen($title) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Title is required';
            return $response;
        }

        // validate message
        if(strlen($message) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Message is required';
            return $response;
        }

        // update headline
        $data = array();
        $data['admin_id'] = $admin->id;
        $data['title'] = $title;
        $data['message'] = $message;
        $data['updated_at'] = time();
        \DB::table('headlines')->where('id', $headline_id)->update($data);

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
        $headline_id = $request->get('headline_id');

        \Log::info('admin '.$api_token.' destroy headline '.$headline_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete headline
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('headlines')->where('id', $headline_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
