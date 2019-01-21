<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'type' => 'required'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function profile(){

        return auth('api')->user();

    }

    public function updateProfile(Request $request){

        $user = auth('api')->user();

        $currentPhoto = $user->photo;

        if($request->photo != $currentPhoto){//if a photo was uploaded and is different from current user photo
            //save the photo name uniquely as the current time of photo upload
            //also get the file extension e.g. png,jpg etc
            $name = time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            //now use image intervention to save the image
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            //set photo in request object to the new photo name
            $request->merge(['photo' => $name]);
            //dalate previous stored user photo if any
            $userPhoto = public_path('img/profile/').$currentPhoto;
            
            if(file_exists($userPhoto)){
                //delete the photo
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            //if user is updating password then ensure it is hashed
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ['message' => 'user info updated'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return ['message' => 'User Info Updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return ['message' => 'User successfully deleted'];
    }
}
