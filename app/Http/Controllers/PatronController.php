<?php

namespace App\Http\Controllers;

use App\Models\Patron;
use Illuminate\Http\Request;

class PatronController extends Controller
{
    function index(){
        return Patron::all();
    }

    function show($id){
        return Patron::findOrFail($id);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publication_year' => 'required|integer',
            'isbn' => 'required|string|unique:books',
        ]);
        return Patron::create($validated);
    }

    public function update(Request $request, $id) {
        $book = Patron::findOrFail($id);
        $validated = $request->validate([
            'title' => 'string',
            'author' => 'string',
            'publication_year' => 'integer',
            'isbn' => 'string|unique:books,isbn,' . $id,
        ]);
        $book->update($validated);
        return $book;
    }

    function destroy($id){
        return Patron::destroy($id);
    }
}
