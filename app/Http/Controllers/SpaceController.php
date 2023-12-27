<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Http\Requests\StoreSpaceRequest;
use App\Http\Resources\LinkResource;
use App\Http\Resources\SpaceResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SpaceController extends Controller
{
    public function index()
    {
      $spaces = Auth::user()->spaces()->paginate(10);  
      return SpaceResource::collection($spaces);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpaceRequest $request)
    {
        Space::create([
            'user_id'=> Auth::user()->id,
            'name'=> $request->name,
            'description'=> $request->description,
            'slug'=> $this->makeSlug($request->name)
        ]);
        return ApiResponse::success(statusCode: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $space = Space::where('slug', $slug)->first();
        if($space){
            $links = $space->links()->paginate(10);
            return ApiResponse::success(data: [
                'space' =>$space, 
                'links' =>LinkResource::collection( $links)
            ]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSpaceRequest $request, Space $space)
    {
        $space->update([
            'name'=> $request->name,
            'description'=> $request->description,
            'slug'=> $this->makeSlug($request->name)
        ]);
        return ApiResponse::success(statusCode: 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Space $space)
    {
        $space->delete();
        return ApiResponse::success();
    }

    public function makeSlug(string $name): string{
        $slug = Str::slug(substr($name, 0, 20));

        while(Space::where('slug', $slug)->where('user_id', Auth::user()->id)->exists()){
            $slug .= random_int(100, 999);
        }
        return $slug;
    }
}
