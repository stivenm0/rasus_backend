<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Http\Requests\StoreSpaceRequest;
use App\Http\Requests\UpdateSpaceRequest;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpaceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Space $space)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpaceRequest $request, Space $space)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Space $space)
    {
        //
    }
}
