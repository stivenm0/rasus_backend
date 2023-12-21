<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    

    public function login(Request $credentials)
    {
        if(Auth::attempt($credentials->all())){
            return ApiResponse::success(
                data: [
                    'user' => new UserResource(Auth::user()),
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
                'user' => new UserResource(Auth::user()),
                'token' => $user->createToken('client')->plainTextToken
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nickname)
    {
        $user = User::where('nickname',$nickname)->first();

        if($user){
            return ApiResponse::success(data: $user);
        }
        return 'Not found';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user()->update([
            'name'=> $request->name,
            'nickname'=> $this->makeNick($request->name),
            'email'=> $request->email,
            'photo'=> $this->savePhoto($request->photo)
        ]);

        return ApiResponse::success(data: $user);
    }


    public function updatePassword(Request $request)
    {
        $validation = Validator::make($request, [
            'password' => 'required|string|min:8|max:30'
        ]);


        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);
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

        $nameImage = $user->nickname . now();

        Storage::disk('photos')->put($nameImage, $photo);

        return $nameImage;
    }

}
