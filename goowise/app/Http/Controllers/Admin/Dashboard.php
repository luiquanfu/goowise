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
        $rates = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $banks = $query->get();
        
        // get bank_loans
        $query = \DB::connection('mysql')->table('bank_loans');
        $query->select('id', 'bank_id', 'name', 'lock_period');
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

        // create results
        $result_banks = array();
        foreach($banks as $bank)
        {
            $result_bank = (object)[];
            $result_bank->name = $bank->name;
            $result_bank_loans = array();

            foreach($bank_loans as $bank_loan)
            {
                if($bank_loan->bank_id != $bank->id)
                {
                    continue;
                }

                $result_bank_loan = (object)[];
                $result_bank_loan->name = $bank_loan->name;
                $result_bank_loan->lock_period = $bank_loan->lock_period.' Years';
                $result_bank_rates = array();

                foreach($bank_rates as $bank_rate)
                {
                    if($bank_rate->bank_loan_id != $bank_loan->id)
                    {
                        continue;
                    }
                    
                    $rate_name = '';
                    $rate_interest = 0;
                    $formula = '';
                    $interest_rate = $bank_rate->interest;

                    foreach($rates as $rate)
                    {
                        if($rate->id == $bank_rate->rate_id)
                        {
                            $rate_name = $rate->name;
                            $rate_interest = $rate->interest;
                        }
                    }

                    if($bank_rate->rate_id != 0)
                    {
                        $formula = $rate_name;
                        $interest_rate = $rate_interest;
                        if($bank_rate->calculate == 'add')
                        {
                            $formula .= ' + ';
                            $interest_rate += $bank_rate->interest;
                        }
                        if($bank_rate->calculate == 'subtract')
                        {
                            $formula .= ' - ';
                            $interest_rate -= $bank_rate->interest;
                        }
                        $formula .= $bank_rate->interest.' %';
                    }

                    $result_bank_rate = (object)[];
                    $result_bank_rate->year = $bank_rate->year;
                    $result_bank_rate->formula = $formula;
                    $result_bank_rate->interest_rate = round($interest_rate, 3).' %';
                    $result_bank_rates[] = $result_bank_rate;
                }
                $result_bank_loan->bank_rates = $result_bank_rates;
                $result_bank_loans[] = $result_bank_loan;
            }
            $result_bank->bank_loans = $result_bank_loans;
            $result_banks[] = $result_bank;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['banks'] = $result_banks;
        return $response;
    }
}
