<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BankLoan extends Controller
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

        \Log::info('Admin '.$api_token.' list bank_loan page '.$page);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $admin = $response['admin'];

        // update admin
        $last_visit = array();
        $last_visit['page'] = 'bank_loan_listing';
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

        // get bank_loans
        $query = \DB::connection('mysql')->table('bank_loans');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'package_id';
        $select[] = 'name';
        $select[] = 'lock_period';
        $query->select($select);
        $total_bank_loans = $query->count();
        if(strlen($filter_name) != 0)
        {
            $query->where('name', 'like', '%'.$filter_name.'%');
        }
        $query->where('deleted_at', 0);
        $query->orderBy($sort, $direction);
        $query->paginate($paginate);
        $bank_loans = $query->get();

        // modify bank_loans
        foreach($bank_loans as $bank_loan)
        {
            $query = \DB::connection('mysql')->table('banks');
            $query->select('name');
            $query->where('id', $bank_loan->bank_id);
            $bank = $query->first();
            $bank_loan->bank_name = 'NA';
            if($bank) $bank_loan->bank_name = $bank->name;

            $query = \DB::connection('mysql')->table('packages');
            $query->select('name');
            $query->where('id', $bank_loan->package_id);
            $package = $query->first();
            $bank_loan->package_name = 'NA';
            if($package) $bank_loan->package_name = $package->name;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['bank_loans'] = $bank_loans;
        $response['total_bank_loans'] = $total_bank_loans;
        $response['total_pages'] = ceil($total_bank_loans / $paginate);
        $response['current_page'] = $page;
        return $response;
    }

    public function create(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');

        \Log::info('Admin '.$api_token.' create bank_loan');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get rates
        $query = \DB::connection('mysql')->table('rates');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $rates = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $banks = $query->get();

        // get packages
        $query = \DB::connection('mysql')->table('packages');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $packages = $query->get();

        // get building_types
        $query = \DB::connection('mysql')->table('building_types');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $building_types = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['rates'] = $rates;
        $response['banks'] = $banks;
        $response['packages'] = $packages;
        $response['building_types'] = $building_types;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_id = $request->get('bank_id');
        $package_id = $request->get('package_id');
        $name = $request->get('name');
        $lock_period = $request->get('lock_period');
        $minimum_loan = $request->get('minimum_loan');
        $building_types = $request->get('building_types');
        $new_bank_rates = $request->get('new_bank_rates');

        \Log::info('Admin '.$api_token.' add bank_loan');

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

        // validate package_id
        if(strlen($package_id) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Package is required';
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

        // validate lock_period
        if(strlen($lock_period) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Lock period required';
            return $response;
        }

        // validate minimum_loan
        if(strlen($minimum_loan) == 0)
        {
            $minimum_loan = 0;
        }

        // insert bank_loan
        $bank_loan_id = $this->unique_id();
        $data = array();
        $data['id'] = $bank_loan_id;
        $data['bank_id'] = $bank_id;
        $data['package_id'] = $package_id;
        $data['name'] = $name;
        $data['lock_period'] = $lock_period;
        $data['minimum_loan'] = $minimum_loan;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('bank_loans')->insert($data);

        // insert bank_loan_buildings
        foreach($building_types as $building_type)
        {
            $data = array();
            $data['id'] = $this->unique_id();
            $data['bank_loan_id'] = $bank_loan_id;
            $data['building_type_id'] = $building_type['id'];
            $data['created_at'] = time();
            $data['updated_at'] = time();
            \DB::table('bank_loan_buildings')->insert($data);
        }

        // insert bank_rates
        foreach($new_bank_rates as $new_bank_rate)
        {
            $data = array();
            $data['id'] = $this->unique_id();
            $data['rate_id'] = $new_bank_rate['rate_id'];
            $data['bank_id'] = $bank_id;
            $data['bank_loan_id'] = $bank_loan_id;
            $data['year'] = $new_bank_rate['year'];
            $data['calculate'] = $new_bank_rate['calculate'];
            $data['interest'] = $new_bank_rate['interest'];
            $data['created_at'] = time();
            $data['updated_at'] = time();
            \DB::table('bank_rates')->insert($data);
        }

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
        $bank_loan_id = $request->get('bank_loan_id');

        \Log::info('Admin '.$api_token.' edit bank_loan '.$bank_loan_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get packages
        $query = \DB::connection('mysql')->table('packages');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $packages = $query->get();

        // get rates
        $query = \DB::connection('mysql')->table('rates');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $rates = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $banks = $query->get();

        // get building_types
        $query = \DB::connection('mysql')->table('building_types');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $query->orderBy('name');
        $building_types = $query->get();

        // get bank_loan
        $query = \DB::connection('mysql')->table('bank_loans');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'package_id';
        $select[] = 'name';
        $select[] = 'lock_period';
        $select[] = 'minimum_loan';
        $query->select($select);
        $query->where('id', $bank_loan_id);
        $bank_loan = $query->first();

        // get bank_loan_buildings
        $query = \DB::connection('mysql')->table('bank_loan_buildings');
        $query->select('id', 'building_type_id');
        $query->where('bank_loan_id', $bank_loan->id);
        $query->where('deleted_at', 0);
        $bank_loan_buildings = $query->get();

        // get bank_rates
        $query = \DB::connection('mysql')->table('bank_rates');
        $select = array();
        $select[] = 'id';
        $select[] = 'rate_id';
        $select[] = 'year';
        $select[] = 'calculate';
        $select[] = 'interest';
        $query->select($select);
        $query->where('bank_loan_id', $bank_loan->id);
        $query->where('deleted_at', 0);
        $query->orderBy('year');
        $bank_rates = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['rates'] = $rates;
        $response['banks'] = $banks;
        $response['building_types'] = $building_types;
        $response['packages'] = $packages;
        $response['bank_loan'] = $bank_loan;
        $response['bank_loan_buildings'] = $bank_loan_buildings;
        $response['bank_rates'] = $bank_rates;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_loan_id = $request->get('bank_loan_id');
        $bank_id = $request->get('bank_id');
        $package_id = $request->get('package_id');
        $name = $request->get('name');
        $lock_period = $request->get('lock_period');
        $minimum_loan = $request->get('minimum_loan');
        $building_types = $request->get('building_types');
        $edit_bank_rates = $request->get('edit_bank_rates');
        $new_bank_rates = $request->get('new_bank_rates');

        \Log::info('Admin '.$api_token.' update bank_loan '.$bank_loan_id);

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

        // validate package_id
        if(strlen($package_id) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Package is required';
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

        // validate lock_period
        if(strlen($lock_period) == 0)
        {
            $response = array();
            $response['error'] = 1;
            $response['message'] = 'Lock period required';
            return $response;
        }

        // validate minimum_loan
        if(strlen($minimum_loan) == 0)
        {
            $minimum_loan = 0;
        }

        // get bank_loan_buildings
        $query = \DB::connection('mysql')->table('bank_loan_buildings');
        $query->select('id', 'building_type_id');
        $query->where('bank_loan_id', $bank_loan_id);
        $query->where('deleted_at', 0);
        $bank_loan_buildings = $query->get();

        // create delete_ids and insert_ids
        $selected_ids = array_column($building_types, 'id');
        $database_ids = array_column($bank_loan_buildings, 'building_type_id');
        $insert_ids = array();
        $delete_ids = array();
        foreach($selected_ids as $selected_id)
        {
            if(in_array($selected_id, $database_ids) == false)
            {
                $insert_ids[] = $selected_id;
            }
        }
        foreach($database_ids as $database_id)
        {
            if(in_array($database_id, $selected_ids) == false)
            {
                $delete_ids[] = $database_id;
            }
        }

        // update bank_loan_buildings
        $data = array();
        $data['deleted_at'] = time();
        $query = \DB::table('bank_loan_buildings');
        $query->where('bank_loan_id', $bank_loan_id);
        $query->whereIn('building_type_id', $delete_ids);
        $query->where('deleted_at', 0);
        $query->update($data);
        foreach($insert_ids as $insert_id)
        {
            $data = array();
            $data['id'] = $this->unique_id();
            $data['bank_loan_id'] = $bank_loan_id;
            $data['building_type_id'] = $insert_id;
            $data['created_at'] = time();
            $data['updated_at'] = time();
            \DB::table('bank_loan_buildings')->insert($data);
        }
        
        // update bank_loan
        $data = array();
        $data['bank_id'] = $bank_id;
        $data['package_id'] = $package_id;
        $data['name'] = $name;
        $data['lock_period'] = $lock_period;
        $data['minimum_loan'] = $minimum_loan;
        $data['updated_at'] = time();
        \DB::table('bank_loans')->where('id', $bank_loan_id)->update($data);

        // update bank_rates
        foreach($edit_bank_rates as $edit_bank_rate)
        {
            $data = array();
            $data['rate_id'] = $edit_bank_rate['rate_id'];
            $data['bank_id'] = $bank_id;
            $data['bank_loan_id'] = $bank_loan_id;
            $data['year'] = $edit_bank_rate['year'];
            $data['calculate'] = $edit_bank_rate['calculate'];
            $data['interest'] = $edit_bank_rate['interest'];
            $data['updated_at'] = time();
            \DB::table('bank_rates')->where('id', $edit_bank_rate['id'])->update($data);
        }

        // insert bank_rates
        foreach($new_bank_rates as $new_bank_rate)
        {
            $data = array();
            $data['id'] = $this->unique_id();
            $data['rate_id'] = $new_bank_rate['rate_id'];
            $data['bank_id'] = $bank_id;
            $data['bank_loan_id'] = $bank_loan_id;
            $data['year'] = $new_bank_rate['year'];
            $data['calculate'] = $new_bank_rate['calculate'];
            $data['interest'] = $new_bank_rate['interest'];
            $data['created_at'] = time();
            $data['updated_at'] = time();
            \DB::table('bank_rates')->insert($data);
        }

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
        $bank_loan_id = $request->get('bank_loan_id');

        \Log::info('Admin '.$api_token.' destroy bank_loan '.$bank_loan_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete bank_loan
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('bank_loans')->where('id', $bank_loan_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
