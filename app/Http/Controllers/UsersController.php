<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsersController extends Controller
{
    public function create(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users,email', //
            'password'=> 'required|min:8'
        ]);

        try{
            $user = User::create([
                'name'=>$request['name'],
                'email'=>$request['email'],
                'password'=>bcrypt($request['password'])
            ]);
            return response()->json(['user'=>$user],200);
        }catch (\Exception $e){
            return response()->json(['error'=>'Couldn\'t create user'], 500);
        }
    }
    public function login(Request $request){
        $this->validate($request, [
            'email'=> 'required|email|exists:users,email',
            'password'=>'required|min:8'
            ]);
        if(Auth::attempt(['email'=> $request['email'],'password'=>$request['password']])){
            $user = Auth::user();

            $accessToken = $user->createToken('Token')->accessToken;
            $user->access_token = $accessToken;
            return response()->json(['user'=>$user], 200);
        }else{
            return response()->json(['error'=>'Couldn\'t find user'], 404);
        }
    }

    public function show(){
        $user = Auth::user();

        return response()->json(['user'=>$user], 200);
    }
    public function idUser($idUser){
       try{
           $user = User::findOrFail($idUser);
           return response()->json(['user'=>$user], 200);
       }catch(\Exception $exception ){
           return response()->json(['error'=>'Couldn\'t find user'], 404);
       }
    }
    public function booksUser(){
        $user = Auth::user();

        $books = $user->books;

        return response()->json(['books'=>$books], 200);
    }
    public function updateUser(Request $request){
        $user = Auth::user();

        $this->validate($request,[
            'name'=>'max:100|string',
            'email'=>'email|unique:users,email', //
            'password'=> 'min:8'
        ]);

       try{
           $user=$user->update($request->only(['name','email','password']));
           return response()->json(['user'=>$user], 200);
       }catch(\Exception $exception){
           return response()->json(['error'=>'Couldn\'t update user'], 500);
       }
    }
    public function deleteUser(){
        $user = Auth::user();

        $user->delete();

        return response()->json(['success'=>'User deleted with success'], 200);
    }
}
