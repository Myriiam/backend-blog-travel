<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getAllComments()
    {
       /*  $comments = Comment::all();
        return response()->json([
            'comments' =>  $comments,
        ]); */
    }

    public function addComment()
    {
        //
    }
}
