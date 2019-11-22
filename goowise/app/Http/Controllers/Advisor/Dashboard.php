<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function listing(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $filter_package_id = $request->get('filter_package_id');
        $filter_bank_id = $request->get('filter_bank_id');
        \Log::info('advisor '.$api_token.' list dashboard');

        // validate api_token
        $response = $this->check_advisor($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        $advisor = $response['advisor'];

        // update advisor
        $last_visit = array();
        $last_visit['page'] = 'dashboard_listing';
        $data = array();
        $data['last_visit'] = json_encode($last_visit);
        \DB::table('advisors')->where('id', $advisor->id)->update($data);

        // get rates
        $query = \DB::connection('mysql')->table('rates');
        $query->select('id', 'name', 'interest');
        $rates = $query->get();

        // get packages
        $query = \DB::connection('mysql')->table('packages');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $packages = $query->get();

        // get banks
        $query = \DB::connection('mysql')->table('banks');
        $query->select('id', 'name');
        $query->where('deleted_at', 0);
        $banks = $query->get();
        
        // get bank_loans
        $query = \DB::connection('mysql')->table('bank_loans');
        $select = array();
        $select[] = 'id';
        $select[] = 'bank_id';
        $select[] = 'package_id';
        $select[] = 'name';
        $select[] = 'lock_period';
        $select[] = 'minimum_loan';
        $query->select($select);
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

        // get headlines
        $query = \DB::connection('mysql')->table('headlines');
        $select = array();
        $select[] = 'id';
        $select[] = 'admin_id';
        $select[] = 'title';
        $select[] = 'message';
        $select[] = 'updated_at';
        $query->where('updated_at', '>', strtotime('-7 days'));
        $query->where('deleted_at', 0);
        $query->orderBy('updated_at', 'desc');
        $headlines = $query->get();

        // modify headlines
        foreach($headlines as $headline)
        {
            $headline->message = nl2br($headline->message);
            $headline->updated_at = $this->wrap_timezone($headline->updated_at, 8);
            $headline->updated_at = date('d M Y g:i A', $headline->updated_at);
        }

        // create results
        $result_dashboards = array();
        foreach($packages as $package)
        {
            $result_dashboard = (object)[];
            $result_dashboard->name = $package->name;
            $result_bank_loans = array();

            foreach($bank_loans as $bank_loan)
            {
                if($bank_loan->package_id != $package->id)
                {
                    continue;
                }

                if(strlen($filter_bank_id) != 0)
                {
                    if($bank_loan->bank_id != $filter_bank_id) continue;
                }
                if(strlen($filter_package_id) != 0)
                {
                    if($bank_loan->package_id != $filter_package_id) continue;
                }

                $bank_name = '';
                foreach($banks as $bank)
                {
                    if($bank->id == $bank_loan->bank_id)
                    {
                        $bank_name = $bank->name;
                    }
                }

                $result_bank_loan = (object)[];
                $result_bank_loan->bank_name = $bank_name;
                $result_bank_loan->name = $bank_loan->name;
                $result_bank_loan->lock_period = $bank_loan->lock_period.' years';
                if($bank_loan->lock_period == 0) $result_bank_loan->lock_period = 'Nil';
                $result_bank_loan->minimum_loan = $this->wrap_currency($bank_loan->minimum_loan, '$', 'left', 0, 'minus');
                if($bank_loan->minimum_loan == 0) $result_bank_loan->minimum_loan = 'NA';
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

                    if($bank_rate->rate_id != '')
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
            $result_dashboard->bank_loans = $result_bank_loans;
            $result_dashboards[] = $result_dashboard;
        }

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        $response['dashboards'] = $result_dashboards;
        $response['packages'] = $packages;
        $response['banks'] = $banks;
        $response['headlines'] = $headlines;
        return $response;
    }
}
