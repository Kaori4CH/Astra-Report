<?php

namespace App\Http\Controllers;

use App\Models\dealer;
use Illuminate\Http\Request;

class DealerController extends Controller
{

public function index()
{
    $title = 'Halaman-Dealers';

    $dealers = dealer::select('code', 'name')->get();

    
    return view('Dealers.index', compact('title', 'dealers'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $title = 'Halaman-Dealer';

        return view('Dealers.create', [
            'title' => $title,
            ]);
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
