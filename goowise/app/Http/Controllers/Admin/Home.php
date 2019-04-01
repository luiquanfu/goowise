<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = array();
        $data['app_url'] = url();
        return view('admin.index', $data);
    }
}
