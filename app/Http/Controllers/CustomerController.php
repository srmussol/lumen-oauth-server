<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 15/02/18
 * Time: 10:55
 */

namespace App\Http\Controllers;

use App\Customer;
use App\SecondaryPassword;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Log;

/**
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $customers = Customer::all();
        return response()->json(['data' => $customers], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $this->validateRequest($request);

        $customer = new Customer();
        $userExists = $this->userExists($customer,$request->all());

        if(!$userExists) {
            $customer->id = $request->get('id');
            $customer->email = $request->get('email');

            $customer->save();

            $customer->secondaryPassword()->create(['secondary_password' => $request->get('password')]);

            return response()->json(['data' => ['type' => "customer", "id" => $customer->id, "attributes" => ["email" =>$customer->email]]], 201);
        }

        return response()->json(['message' => $userExists], 202);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $customer = Customer::find($id);
        if(!$customer){
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
        return response()->json(['data' => ['type' => "customer", "id" => $customer->id, "attributes" => ["name" => $customer->name,"email" =>$customer->email, "roles" => $customer->role]]], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        $customer = Customer::find($id);
        if(!$customer){
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
        $this->validateRequest($request);

        $customer->email        = $request->get('email');
        $customer->save();
        $customer->secondaryPassword()->create(['secondary_password' => $request->get('password')]);
        return response()->json(['data' => ['type' => "customer", "id" => $customer->id, "attributes" => ["email" =>$customer->email, "roles" => $customer->roles]]], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id){
        $customer = Customer::find($id);
        if(!$customer){
            return response()->json(['message' => "The customer with {$id} doesn't exist"], 404);
        }
        $customer->delete();
        $customer->secondaryPassword()->delete();
        return response()->json(['data' => "The customer with with id {$id} has been deleted"], 200);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateRequest(Request $request){
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ];
        $this->validate($request, $rules);
    }

    /**
     * @param Customer $customer
     * @param $data
     * @return bool|string
     */
    private function userExists(Customer $customer, $data)
    {
        $customerExists = Customer::find($data['id']);
        if($customerExists) {
            return "The customer with {$data['id']} exist in database";
        }
        $customerEmailExists = $customer->where('email',$data['email'])->first();
        if(!empty($customerEmailExists)) {
            return "The customer with email {$data['email']} exist in database";
        }
        $secondaryPassword = new SecondaryPassword();
        $orderExists = $secondaryPassword->where('secondary_password',$data['password'])->first();
        if ($data['password']) {
            if(!empty($orderExists)) {
                return "The order {$data['password']} can't be associated to the user {$data['id']}";
            }
        }

        return false;

    }
}