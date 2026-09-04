<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;

class RoleController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return view('central.role.index',['title'=>'Roles']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('central.role.create',['title'=>'Crear Rol']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
