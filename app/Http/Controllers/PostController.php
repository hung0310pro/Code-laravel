<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Posts;

class PostController extends Controller
{
    public function search(Request $request)
    {
        if ($request->has('search')) {
            $posts = Posts::search($request->get('search'))->get();
            return response()->json($posts);
        } else {
            $posts = Posts::get();
            return response()->json($posts);
        }
    }
}
