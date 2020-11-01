<?php

namespace App\Http\Controllers;

use App\Models\Post;

class MainController extends Controller
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at','desc')->get();
        return view('pages.welcome')->withPosts($posts);
    }
}
