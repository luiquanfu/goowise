<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BankLoan extends Controller
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
        \Log::info('Admin '.$api_token.' list bank_loan page '.$page);

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
        $rates = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $banks = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['rates'] = $rates;
        $response['banks'] = $banks;
        return $response;
    }

    public function add(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_id = $request->get('bank_id');
        $name = $request->get('name');
        $lock_period = $request->get('lock_period');
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

        // insert bank_loan
        $bank_loan_id = $this->unique_id();
        $data = array();
        $data['id'] = $bank_loan_id;
        $data['bank_id'] = $bank_id;
        $data['name'] = $name;
        $data['lock_period'] = $lock_period;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        \DB::table('bank_loans')->insert($data);

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

        // get rates
        $query = \DB::connection('mysql')->table('rates');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $rates = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $banks = $query->get();

        // get bank_loan
        $query = \DB::connection('mysql')->table('bank_loans');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'name';
        $select[] = 'lock_period';
        $query->select($select);
        $query->where('id', $bank_loan_id);
        $bank_loan = $query->first();

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
        $bank_rates = $query->get();

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['rates'] = $rates;
        $response['banks'] = $banks;
        $response['bank_loan'] = $bank_loan;
        $response['bank_rates'] = $bank_rates;
        return $response;
    }

    public function update(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_loan_id = $request->get('bank_loan_id');
        $bank_id = $request->get('bank_id');
        $name = $request->get('name');
        $lock_period = $request->get('lock_period');
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
        
        // update bank_loan
        $data = array();
        $data['bank_id'] = $bank_id;
        $data['name'] = $name;
        $data['lock_period'] = $lock_period;
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
