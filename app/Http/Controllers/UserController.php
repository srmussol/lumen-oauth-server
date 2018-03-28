<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 15/02/18
 * Time: 10:55
 */

namespace App\Http\Controllers;

use App\User;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $users = User::all();
        return response()->json(['data' => $users], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $this->validateRequest($request);
        $userExists = $this->userExists($request->all());
        if( $userExists == false ) {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password'=> Hash::make($request->get('password'))
            ]);

            return response()->json(['data' => ['type' => "user", "id" => $user->id, "attributes" => ["name" => $user->name,"email" =>$user->email, "roles" => $user->role]]], 201);
        }

        return response()->json(['message' => $userExists], 202);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
        return response()->json(['data' => ['type' => "user", "id" => $user->id, "attributes" => ["name" => $user->name,"email" =>$user->email, "roles" => $user->role]]], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
        $this->validateRequest($request);
        $user->email        = $request->get('email');
        $user->password     = Hash::make($request->get('password'));
        $user->save();
        return response()->json(['data' => ['type' => "user", "id" => $user->id, "attributes" => ["email" =>$user->email, "roles" => $user->roles]]], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
        $user->delete();
        return response()->json(['data' => "The user with with id {$id} has been deleted"], 200);
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
    private function userExists($data)
    {
        $userExists = User::find($data['id']);
        if($userExists) {
            return "The customer with {$data['id']} exist in database";
        }
        $user = new User();
        $userEmailExists = $user->where('email',$data['email'])->first();
        if(!empty($userEmailExists)) {
            return "The customer with email {$data['email']} exist in database";
        }

        return false;

    }
}