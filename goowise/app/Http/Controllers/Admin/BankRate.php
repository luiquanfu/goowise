<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BankRate extends Controller
{
    public function destroy(Request $request)
    {
        // set variables
        $api_token = $request->get('api_token');
        $bank_rate_id = $request->get('bank_rate_id');

        \Log::info('admin '.$api_token.' destroy bank_rate '.$bank_rate_id);

        // validate api_token
        $response = $this->check_admin($api_token);
        if($response['error'] != 0)
        {
            return $response;
        }

        // delete bank_rate
        $data = array();
        $data['deleted_at'] = time();
        \DB::table('bank_rates')->where('id', $bank_rate_id)->update($data);

        // success
        $response = array();
        $response['error'] = 0;
        $response['message'] = 'Success';
        return $response;
    }
}
