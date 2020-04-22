<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
    	$comments = Comment::all();

    	return view('admin.comments.index', compact('comments'));
    }

    public function status($id)
    {
    	Comment::find($id)->toggleStatus();

    	return redirect()->route('comments.index');
    }

    public function destroy($id)
    {
    	Comment::find($id)->remove();

    	return redirect()->route('comments.index');
    }
}
