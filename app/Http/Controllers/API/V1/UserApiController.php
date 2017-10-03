<?php

namespace App\Http\Controllers\API\V1;
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 26-9-17
 * Time: 13:08
 */

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserApiController extends Controller
{
    /**
     * Userlogin and returns basic details of the user
     * @param $email
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */

    public function getLogin(Request $request){

        $userNumber = $request->userNumber;
        $password = $request->password;
        if(Auth::attempt(['user_number' => $userNumber, 'password' => $password])) {

            $user = DB::table('users')->select('name', 'email', 'street', 'home_number', 'phone_number')
                ->where('user_number', $userNumber)->first(); //user number has unique constraint in db, so first is safe

            return response()->json($user);

        } else {
            return response()->json("{'message': 'Incorrect user and password'}");
        }
    }

    public function editUser(Request $request)
    {
        $result = DB::table('users')->where('user_number', $request->userNumber)
            ->update(['street' => $request->street, 'home_number' => $request->homeNumber,
            'email' => $request->email, 'phone_number' => $request->phoneNumber]);
        return response()->json($result);
    }

    public function login(Request $request){
        $userNumber = $request->userNumber;
        $password = $request->password;
        if(Auth::attempt(['user_number' => $userNumber, 'password' => $password])) {
            return redirect()->intended('/');
        }
        else {
            Return 'incorrect password';
        }
    }

    /**
     * Get the user details
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($email) {
        $user = DB::table('users as u')
            ->select('u.name as Username', 'u.email as Email', 'u.street as Street', 'u.home_number as HomeNumber',
                'u.phone_number as PhoneNumber', 'u.bsn as BSN', 'u.date_of_birth as DateOfBirth',
                'u.policy_number as PolicyNumber', 'u.insurance_type as InsuranceType',
                'u.insurance_start as InsuranceStart', 'u.insurance_end as InsuranceEnd', 'u.excess as Excess',
                'h.name as HealthInsuranceCompanyName')
            ->leftJoin('health_insurance_companies as h', 'u.health_insurance_id', '=', 'h.id')
            ->where('email', $email)->get();
        return response()->json($user);
    }
}