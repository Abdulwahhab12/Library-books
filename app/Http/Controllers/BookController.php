<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    function index(){
        return Book::all();
    }

    function show($id){
        return Book::findOrFail($id);
        return view('books.show',['books'=>$book]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publication_year' => 'required|integer',
            'isbn' => 'required|string|unique:books',
        ]);
        return Book::create($validated);
    }

    public function update(Request $request, $id) {
        $book = Book::findOrFail($id);
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
        return Book::destroy($id);
    }
}
