<?php

namespace App\Http\Controllers;

use App\Models\CRUD;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(CRUD.index);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(CRUD $cRUD)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CRUD $cRUD)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CRUD $cRUD)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CRUD $cRUD)
    {
        //
    }
}
