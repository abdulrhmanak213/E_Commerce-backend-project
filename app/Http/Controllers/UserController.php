<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use illuminate\Validation\Rule;
use symfony\component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\AccessToken;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends Controller
{
    public function login(Request $request){
        $validator =Validator::make($request ->all(),[
            'email'=>['required','string','email','max:255'],
            'password'=>['required','string','min:8'],
        ]);

        if($validator->fails()) //Determine if the data fails the validation rules.
          return response()->json($validator->errors()->all(),400);
        $credentials =request(['email','password']);
        if(!Auth::attempt($credentials)){
            throw new AuthenticationException();
        }
        $user=$request->user();
        $tokenResult=$user->createToken('Personal Access Token');
        $data["user"]=$user;
        $data["token_type"]='Bearer';
        $data['access_token']=$tokenResult->accessToken;
        return response()->json ($data,200);
    }




    public function creatAccount(Request $request){
        $validator = Validator ::make ($request-> all(),[
            'name'=>['required','string','max:255'],
            'email'=>['required','string','email', 'max:255'],
            'password'=>['required','string','min:8'],
            'phone'=>['required','string'],
        ]);
        if($validator->fails()) //Determine if the data fails the validation rules.
            return response()->json($validator->errors()->all(),Response::HTTP_UNPROCESSABLE_ENTITY);
        $request['password']=Hash::make($request['password']);
        $user=User::query()->create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'phone'=>$request->phone,
        ]);
        $tokenResult=$user->createToken('Personal Access Token');
        $data["user"]=$user;
        $data["token_type"]='Bearer';
        $data['access_token']=$tokenResult->accessToken;
        return response()->json($data,200);
    }
    public function myProducts(Request $request){
        $ProductQuery = Product::query();
        $auth=Auth::id();

        return $ProductQuery->where('user_id' ,$auth)->get();



    }


}
