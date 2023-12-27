<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    

    public function login(Request $credentials)
    {
        if(Auth::attempt($credentials->all())){
            return ApiResponse::success(
                data: [
                    'token'=> Auth::user()->createToken('client')->plainTextToken
                ]
            );
        }
        return ApiResponse::error('Credenciales Incorrectas', 402);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return ApiResponse::success();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'=> $request->name,
            'nickname'=> $this->makeNick($request->name),
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        return ApiResponse::success(
            statusCode: 201, 
            data: [
                'token' => $user->createToken('client')->plainTextToken
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return ApiResponse::success(data: new UserResource(Auth::user()) );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        Auth::user()->update([
            'name'=> $request->name,
            'nickname'=> $this->makeNick($request->nickname),
            'email'=> $request->email,
            'photo'=> $this->savePhoto($request->photo)
        ]);
        return ApiResponse::success();
    }

    public function photo(string $name){
        return Storage::disk('photos')->get($name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        Auth::user()->delete();
        return ApiResponse::success();
    }

    public function makeNick(string $name)
    {
        $nickname = Str::slug(substr($name, 0, 8));

        while(User::where('nickname', $nickname)->exists()){
            $nickname .= random_int(100, 999);
        }
        return $nickname;
    }

    public function savePhoto($photo)
    {
        $user = Auth::user();

        if(!$photo){ return $user->photo; }
        
        if($user->photo){
            Storage::disk('photos')->delete($user->photo);
        }
        
        $nameImage =  time(). $photo->getClientOriginalName();
        
        Storage::disk('photos')->put($nameImage, File::get($photo));

        return $nameImage;
    }

}
