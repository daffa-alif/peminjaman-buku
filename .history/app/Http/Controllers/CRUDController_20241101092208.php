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
        $items = CRUD::all();
        return view('CRUD.index', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nama_peminjam' => 'required|string|max:100',
            'nama_buku' => 'required|string|max:100',
            'jumlah_buku' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam', // Ensure return date is not before borrow date
        ]);

        // Create a new CRUD item
        $crud = CRUD::create($validatedData);
        $crud->calculateDenda(); // Call method to calculate fines if necessary

        // Return a JSON response
        return response()->json(['success' => 'Data added successfully!', 'item' => $crud]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CRUD $cRUD)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nama_peminjam' => 'required|string|max:100',
            'nama_buku' => 'required|string|max:100',
            'jumlah_buku' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam', // Ensure return date is not before borrow date
        ]);

        // Update the CRUD item
        $cRUD->update($validatedData);
        $cRUD->calculateDenda(); // Update fine calculation if necessary

        // Return a JSON response
        return response()->json(['success' => 'Data updated successfully!', 'item' => $cRUD]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CRUD $cRUD)
    {
        // Delete the CRUD item
        $cRUD->delete();

        // Return a JSON response
        return response()->json(['success' => 'Data deleted successfully!']);
    }
}
