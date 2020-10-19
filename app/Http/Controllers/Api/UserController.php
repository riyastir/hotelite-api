<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\User as UserResource;
use Exception;
use Illuminate\Auth\AuthenticationException;

class UserController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $user->roles;
            return response()->json(['success' => $success, 'user' => $user], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        /* Check role exists */
        $roleExist = Role::where('name', 'Hotel')->first();
        if (@$roleExist->id) {
            $user->assignRole([$roleExist->id]);
        } else {
            $role = Role::create(['name' => 'Hotel']);
            $permissions = Permission::whereRaw('name like "hotel-%" or name like "package-%"')->pluck('id', 'id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
        }
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success' => $success], $this->successStatus);
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details()
    {
        $user = Auth::user();
        //$user = auth()->user()->can('role-list');
        return response()->json(['success' => new UserResource($user)], $this->successStatus);
    }

    /**
     * logout api
     * 
     * @return \Illuminate\Http\Response 
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();

            return response()->json(['status' => 'success', 'message'=>'Logged out successfully']);
        }
    }

    /**
     * Check AuthToken Valid
     * 
     * @return \Illuminate\Http\Response
     */
    public function authCheck(){
        try{
            if (Auth::check()) {
                return response()->json(['status' => 'success', 'message'=>'Token Valid']);
            } else {
                return response()->json(['status' => 'error', 'message'=>'Token Invalid']);
            }
        } catch(Exception $e){
            return response()->json(['status' => 'error', 'message'=>'Something went wrong']);
        }
        
    }
}
