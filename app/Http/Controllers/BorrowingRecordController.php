<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowingRecord;
use App\Models\Patron;
use Illuminate\Http\Request;

class BorrowingRecordController extends Controller
{
    public function borrow($bookId, $patronId)
    {
        //Check the book
        $book = Book::find($bookId);
        if (!$book) {
            return response()->json(['message' => 'The book is not available'], 404);
        }

        // Check if the client exists
        $patron = Patron::find($patronId);
        if (!$patron) {
            return response()->json(['message' => 'Client not present'], 404);
        }

        // Check if the book is available for lending
        if ($book->is_available == false) {
            return response()->json(['message' => 'Books are not available for borrowing.'], 400);
        }

        // Added borrowing record
        $borrowingRecord = new BorrowingRecord();
        $borrowingRecord->book_id = $bookId;
        $borrowingRecord->patron_id = $patronId;
        $borrowingRecord->borrow_date = now();
        $borrowingRecord->save();

        // Update book state
        $book->is_available = false;
        $book->save();

        return response()->json(['message' => 'Borrowed successfully', 'record' => $borrowingRecord], 201);
    }

    public function return($bookId, $patronId)
    {
        // Check if the borrowing history exists
        $borrowingRecord = BorrowingRecord::where('book_id', $bookId)
            ->where('patron_id', $patronId)
            ->whereNull('return_date')
            ->first();

        if (!$borrowingRecord) {
            return response()->json(['message' => 'Borrowing record not found'], 404);
        }

        //Add return date
        $borrowingRecord->return_date = now();
        $borrowingRecord->save();

        //Update book state
        $book = Book::find($bookId);
        if ($book) {
            $book->is_available = true;
            $book->save();
        }

        return response()->json(['message' => 'The book has been successfully returned.', 'record' => $borrowingRecord], 200);
    }
}
