<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Image;
class userController extends Controller
{
    /**
     * create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
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
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users',
            'password'=>'required|min:6'
        ]);
       return user::create([
           'name'=>$request['name'],
           'email'=>$request['email'],
           'type'=>$request['type'],
           'bio'=>$request['bio'],
           'photo'=>$request['photo'],
           'password'=>Hash::make($request['password'])
       ]);
    }
      
    public function updateProfile(Request $request)
    {
     $user= auth('api')->user();

     $this->validate($request,[
        'name'=>'required|string|max:191',
        'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
        'password'=>'sometimes|required|min:6'
    ]);

     $currentPhoto=$user->photo;
    if($request->photo != $currentPhoto)
        { 
         $name= time().'.'. explode('/', explode(':',substr($request->photo,0,strpos ($request->photo, ';')))[1])[1];
//return ($name);
   \Image::make($request->photo)->save(public_path('img/profile/').$name);
   $request->merge(['photo'=>$name]);
// user photo directory
   $userPhoto=public_path('img/profile/').$currentPhoto;
   if(file_exists($userPhoto)){

      @unlink($userPhoto);

   }

        }

    if(!empty($request->password)){
        $request->merge(['password'=>Hash::make($request['password'])]);
    }

     $user->update($request->all());

    }


    public function profile()
    {
     return auth('api')->user();
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    
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
        //get the user we want
        $user=User::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'=>'sometimes|min:6'
        ]);
        $user->update($request->all());


       // return ['message'=>'updated the user info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete the user
        $user=User::findOrFail($id);

        $user->delete();
        
        return ['message'=>'userDeleted'];
    }
}
