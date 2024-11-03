<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRUD extends Model
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = CRUD::all();
        return view('CRUD.index', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_peminjam' => 'required|string|max:100',
            'nama_buku' => 'required|string|max:100',
            'jumlah_buku' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
        ]);

        $crud = CRUD::create($validatedData);
        $crud->calculateDenda();

        return response()->json(['success' => 'Data added successfully!', 'item' => $crud]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CRUD $cRUD)
    {
        $validatedData = $request->validate([
            'nama_peminjam' => 'required|string|max:100',
            'nama_buku' => 'required|string|max:100',
            'jumlah_buku' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
        ]);

        $cRUD->update($validatedData);
        $cRUD->calculateDenda();

        return response()->json(['success' => 'Data updated successfully!', 'item' => $cRUD]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CRUD $cRUD)
    {
        $cRUD->delete();
        return response()->json(['success' => 'Data deleted successfully!']);
    }
}
