<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function list(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        \Log::info('Admin '.$api_token.' dashboard');

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // get rates
        $query = \DB::connection('mysql')->table('rates');
        $query->select('id', 'name', 'interest');
        $query->where('deleted_at', 0);
        $rates = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $banks = $query->get();
        
        // get bank_loans
        $query = \DB::connection('mysql')->table('bank_loans');
        $query->select('id', 'name', 'lock_period');
        $query->where('deleted_at', 0);
        $bank_loans = $query->get();

        // get bank_rates
        $query = \DB::connection('mysql')->table('bank_rates');
        $select = array();
        $select[] = 'id';
        $select[] = 'rate_id';
        $select[] = 'bank_id';
        $select[] = 'bank_loan_id';
        $select[] = 'year';
        $select[] = 'calculate';
        $select[] = 'interest';
        $query->select($select);
        $query->where('deleted_at', 0);
        $query->orderBy('bank_id');
        $query->orderBy('bank_loan_id');
        $query->orderBy('year');
        $bank_rates = $query->get();

        // modify bank_rates
        foreach($bank_rates as $bank_rate)
        {
            $bank_rate->rate_name = 'NA';
            foreach($rates as $rate)
            {
                if($rate->id == $bank_rate->rate_id)
                {
                    $bank_rate->rate_name = $rate->name;
                    $bank_rate->rate_interest = $rate->interest;
                }
            }

            $bank_rate->bank_name = 'NA';
            foreach($banks as $bank)
            {
                if($bank->id == $bank_rate->bank_id)
                {
                    $bank_rate->bank_name = $bank->name;
                }
            }

            $bank_rate->bank_loan_name = 'NA';
            foreach($bank_loans as $bank_loan)
            {
                if($bank_loan->id == $bank_rate->bank_loan_id)
                {
                    $bank_rate->bank_loan_name = $bank_loan->name;
                    $bank_rate->lock_period = $bank_loan->lock_period;
                }
            }
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['bank_rates'] = $bank_rates;
        return $response;
    }
}
