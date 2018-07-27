<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $take = 5;
        $offset = 0;
        $posts = Post::with('author', 'images')->latest()->take(20)->offset($offset)->get();

        return response_success(['posts' => $posts]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postInfo = $request->only(['title', 'description', 'content']);
        $validator = Validator::make($postInfo, [
            'title' => 'required|min:3|unique:posts',
            'description' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response_error(['errors' => $validator->errors()]);
        };

        $title = trim($postInfo['title']);
        $title_seo = str_replace(' ', '-', strtolower(friendlyString($title)));
        $description = trim($postInfo['description']);
        $content = trim($postInfo['content']);

        $newPost = Post::create(['title' => $title, 'title_seo' => $title_seo, 'description' => $description, 'content' => $content]);

        return response_success(['post' => $newPost]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $post = Post::where('slug', $slug)->first();

        return response_success(['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        return $post->delete()
            ? response_success(['post' => $post], 'deleted post id ' . $post->id)
            : response_error([], 'can not find post id ' . $post->id, 401);
    }
}
