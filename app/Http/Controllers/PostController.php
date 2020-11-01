<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Notification;
use App\Notifications\NewPost;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostsExport;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $posts = Post::all();
        return view('welcome')
            ->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        return view('pages.welcome');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $post = new Post;
        $post->text = $request->input('post_text');

        if (Auth::user() !== null) {
            $post->user_id = Auth::user()->id;
        }
        $post->save();

        $user = User::getAdmin();
        $when = now()->addMinutes(1);
        $user->notify((new NewPost($post))->delay($when));

        $postResponse = array(
            "user" => Auth::user() !== null ? Auth::user()->name : "Аноним",
            "date" => date('M j, Y H:i', strtotime($post->created_at)),
            "text" => $post->text,
            "post_id" => $post->id
        );

        return response($postResponse)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): Response
    {
        $post = Post::find($id);
        $postResponse = array(
            "text" => $post->text,
            "post_id" => $post->id
        );

        return response($postResponse)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, int $id): void
    {
        $post = Post::find($id);
        $post->text = $request->input('post_text');
        $post->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        if (Auth::user() !== null && Post::find($id)->exists()) {
            Post::find($id)->delete();
            $postResponse = array("id" => $id);

            return response($postResponse)
                ->header('Content-Type', 'application/json');
        }

        return response("post not found");
    }

    public function export()
    {
        return Excel::download(new PostsExport, 'posts.xlsx');
    }
}
