<?php

namespace App\Http\Controllers;

use App\Events\ClickEvent;
use App\Models\Link;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Responses\ApiResponse;
use App\Notifications\ClicksNotification;
use Illuminate\Support\Str;

class LinkController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinkRequest $request)
    {
        Link::create([
            'space_id'=> $request->space_id,
            'name'=> $request->name,
            'link'=> $request->link,
            'short'=> $this->makeShort()
        ]);
        return ApiResponse::success(statusCode:201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $short)
    {
        $link = Link::where('short',$short)->first();
        if($link){
            $link->clicks += 1;
            $link->save();

            event(new ClickEvent($link));
            return ApiResponse::success(data: $link->link);
        }
        return ApiResponse::error(statusCode: 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLinkRequest $request, Link $link)
    {
        $this->authorize('author', $link);
        $link->update($request->all());
        return ApiResponse::success();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $this->authorize('author', $link);
        $link->delete();
        return ApiResponse::success();
    }

    public function makeShort()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $shortUrl = '';

        for ($i = 0; $i < 3; $i++) {
            $shortUrl .= $characters[rand(0, strlen($characters) - 1)];
        }

        while(Link::where('short',$shortUrl)->exists()){
            $shortUrl .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $shortUrl;
    }
}
