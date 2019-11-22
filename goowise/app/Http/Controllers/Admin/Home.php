<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

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
        \Log::info('admin '.$api_token.' home');

        // get device_id
        $device_id = $this->unique_id();
        if(isset($_COOKIE['device_id']))
        {
            $device_id = $_COOKIE['device_id'];
        }
        setcookie('device_id', $device_id, time() + (60 * 60 * 24 * 365 * 1), '/');

        // response
        $data = array();
        $data['app_url'] = url();
        $data['api_token'] = $api_token;
        $data['device_id'] = $device_id;
        return view('admin.index', $data);
    }

    public function initialize(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        \Log::info('admin '.$api_token.' initialize');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['admin'] = $admin;
        return $response;
    }

    public function login(Request $request)
    {
        // set variables
        $email = $request->get('email');
        $password = $request->get('password');
        $device_id = $request->get('device_id');
        $device_type = $request->get('device_type');
        \Log::info('admin login '.$device_type.' '.$email);

        // validate device_id
        if(strlen($device_id) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Device ID is invalid';
            return $response;
        }

        // validate device_type
        if(strlen($device_type) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Device type is invalid';
            return $response;
        }

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

        // get admin_token
        $query = \DB::table('admin_tokens');
        $query->select('id', 'api_token');
        $query->where('admin_id', $admin->id);
        $query->where('device_id', $device_id);
        $query->where('deleted_at', 0);
        $admin_token = $query->first();

        // if admin_token not found
        if($admin_token == null)
        {
            // create admin_token
            $admin_token = (object)[];
            $admin_token->id = $this->unique_id();
            $admin_token->admin_id = $admin->id;
            $admin_token->device_id = $device_id;
            $admin_token->device_type = $device_type;
            $admin_token->ip_address = $this->ip_address();
            $admin_token->user_agent = $this->user_agent();
            $admin_token->api_token = $this->unique_token();
            $admin_token->created_at = time();
            $admin_token->updated_at = time();
            \DB::table('admin_tokens')->insert($this->fields($admin_token));
        }

        setcookie('admin_token', $admin_token->api_token, time() + (60 * 60 * 24 * 365 * 1), '/');

        // modify admin
        $admin->api_token = $admin_token->api_token;

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['admin'] = $admin;
        return $response;
    }

    public function logout(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        \Log::info('admin '.$api_token.' logout');

        // update admin_token
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('admin_tokens')->where('api_token', $api_token)->update($data);
        setcookie('admin_token', null, time() - 1, '/');

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }

    public function migration()
    {
        Schema::create('headlines', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('admin_id', 20);
            $table->string('title', 50);
            $table->string('message');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
            
            $table->primary('id');
            $table->index('deleted_at');
        });

        return 'migration done';
    }

    public function rollback()
    {
        Schema::drop('headlines');

        return 'rollback done';
    }

    public function old_codes()
    {
        $data = array();
        $data['--database'] = 'mysql';
        $data['--path'] = 'database/migrations';
        $data['--force'] = true;
        \Artisan::call('migrate', $data);
        return 'migrate done';

        $data = array();
        $data['--database'] = 'mysql';
        \Artisan::call('migrate:rollback', $data);
        return 'rollback done';
    }

    public function old_migrations()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email', 70);
            $table->string('mobile', 15);
            $table->string('reason');
            $table->string('ip_address', 25);
            $table->string('user_agent');
            $table->integer('email_at');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('firstname');
            $table->index('lastname');
            $table->index('email');
            $table->index('mobile');
            $table->index('deleted_at');
        });

        Schema::create('admins', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email', 70);
            $table->string('mobile', 15);
            $table->string('password');
            $table->string('last_visit');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('firstname');
            $table->index('lastname');
            $table->index('email');
            $table->index('mobile');
            $table->index('deleted_at');
        });

        Schema::create('admin_tokens', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('admin_id', 20);
            $table->string('device_id');
            $table->string('device_type', 10);
            $table->string('ip_address', 25);
            $table->string('user_agent');
            $table->string('api_token', 32);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('admin_id');
            $table->index('device_id');
            $table->index('api_token');
            $table->index('deleted_at');
        });

        Schema::create('rates', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('name', 30);
            $table->double('interest');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
            
            $table->primary('id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('packages', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('name', 30);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
            
            $table->primary('id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('building_types', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('name', 30);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
            
            $table->primary('id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('banks', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('name', 30);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('bank_loans', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('bank_id', 20);
            $table->string('package_id', 20);
            $table->string('name', 30);
            $table->integer('lock_period');
            $table->integer('minimum_loan');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('bank_id');
            $table->index('package_id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('bank_loan_buildings', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('bank_loan_id', 20);
            $table->string('building_type_id', 20);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('bank_loan_id');
            $table->index('building_type_id');
            $table->index('deleted_at');
        });

        Schema::create('bank_rates', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('rate_id', 20);
            $table->string('bank_id', 20);
            $table->string('bank_loan_id', 20);
            $table->integer('year');
            $table->enum('calculate', ['add', 'subtract']);
            $table->double('interest');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('rate_id');
            $table->index('bank_id');
            $table->index('bank_loan_id');
            $table->index('deleted_at');
        });

        $data = array();
        $data['id'] = $this->unique_id();
        $data['firstname'] = 'Quanfu';
        $data['lastname'] = 'Lui';
        $data['mobile'] = '90174663';
        $data['email'] = 'quanfu@hotmail.sg';
        $data['password'] = bcrypt('alienwarem14x');
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['deleted_at'] = 0;
        DB::table('admins')->insert($data);

        $data = array();
        $data['id'] = $this->unique_id();
        $data['firstname'] = 'Alson';
        $data['lastname'] = 'Kong';
        $data['mobile'] = '97486595';
        $data['email'] = 'alsonkong@goowise.com';
        $data['password'] = bcrypt('alsonkong123');
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['deleted_at'] = 0;
        DB::table('admins')->insert($data);

        Schema::create('advisors', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('bank_id', 20);
            $table->string('nric', 15);
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('mobile', 15);
            $table->string('email', 70);
            $table->string('password');
            $table->string('bank_account', 20);
            $table->string('address');
            $table->string('postal_code', 10);
            $table->string('last_visit');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('firstname');
            $table->index('lastname');
            $table->index('mobile');
            $table->index('email');
            $table->index('deleted_at');
        });

        Schema::create('advisor_tokens', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('advisor_id', 20);
            $table->string('device_id');
            $table->string('device_type', 10);
            $table->string('ip_address', 25);
            $table->string('user_agent');
            $table->string('api_token', 32);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('advisor_id');
            $table->index('device_id');
            $table->index('api_token');
            $table->index('deleted_at');
        });

        Schema::create('clients', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('advisor_id', 20);
            $table->string('bank_id', 20);
            $table->string('owner_name', 50);
            $table->string('owner_nric', 15);
            $table->string('owner_mobile', 15);
            $table->string('owner_email', 70);
            $table->string('joint_name', 50);
            $table->string('joint_nric', 15);
            $table->string('joint_mobile', 15);
            $table->string('joint_email', 70);
            $table->string('property_address');
            $table->string('postal_code', 10);
            $table->integer('loan_amount');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
            
            $table->primary('id');
            $table->index('advisor_id');
            $table->index('bank_id');
            $table->index('owner_name');
            $table->index('owner_nric');
            $table->index('owner_mobile');
            $table->index('owner_email');
            $table->index('joint_name');
            $table->index('joint_nric');
            $table->index('joint_mobile');
            $table->index('joint_email');
            $table->index('deleted_at');
        });
    }

    public function old_rollbacks()
    {
        Schema::drop('packages');
        Schema::drop('banks');
        Schema::drop('building_types');
        Schema::drop('rates');
        Schema::drop('bank_loans');
        Schema::drop('bank_loan_buildings');
        Schema::drop('bank_rates');
        Schema::drop('admins');
        Schema::drop('admin_tokens');
        Schema::drop('users');

        Schema::drop('advisors');
        Schema::drop('advisor_tokens');
        Schema::drop('clients');
    }
}
