<?php

namespace App\Http\Controllers;

use App\Models\Category;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\User;
use Mews\Purifier\Facades\Purifier;



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
        $posts = Post::with('author', 'images', 'categories')->latest()->take(20)->offset($offset)->get();

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

        $images = $request->file('images');
        $post = collect(json_decode($request->post));

        $postInfo = $post->only(['title', 'description', 'content'])->toArray();
        $validator = Validator::make($postInfo, [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response_error(['errors' => $validator->errors()]);
        };

        $postTitle = trim($postInfo['title']);
        $postTitleSlug = slugify($postTitle);

        $existsPost = Post::where('slug', $postTitleSlug)->first();
        $postSlug = $existsPost ? $postTitleSlug . '-duplicate-' . faker()->uuid : $postTitleSlug;

        $postDescription = trim($postInfo['description']);
        $postContent = trim($postInfo['content']);
        $postUserId = auth()->user()->id;

        $newPost = Post::create(['title' => $postTitle, 'slug' => $postSlug, 'description' => $postDescription, 'content' => $postContent, 'user_id' => $postUserId]);
        $imageSaved = [];

        foreach ($images as $image) {
            array_push($imageSaved, saveImage($image));
        }

        foreach ($imageSaved as $image) {
            $image->posts()->attach($newPost->id);
        }

        if (count($post['categories'])) {
            $categories = collect($post['categories']);
            $categories->each(function ($category) use ($newPost) {
                Category::find($category)->posts()->attach($newPost->id);
            });
        } else {

            Category::where('name', 'uncategorized')->first()->posts()->attach($newPost->id);
        }




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

        $post = Post::with('images', 'author', 'categories')->where('slug', $slug)->first();

        $cate_id = $post->categories->modelKeys();
        $related_posts = Post::with('images', 'author', 'categories')
            ->whereHas('categories', function ($query) use ($cate_id) {
                $query->whereIn('categories.id', $cate_id);
            })
            ->orderByDesc('reputation')
            ->where('id', '<>', $post->id)
            ->take(4)
            ->get();
        return response_success([
            'post' => $post,
            'related_posts' => $related_posts
        ]);
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

    /*
     *  SOFT DELETE
     */

    public function indexDeleted()
    {
        $posts = Post::onlyTrashed()->get();
        return response_success(['posts' => $posts]);
    }

    public function destroyDeleted($id)
    {
        $post = Post::withTrashed()->find($id);
        return $post->forceDelete() ?
            response_success(['post' => $post], 'deleted permanently post id ' . $post->id) : response_error([], 'can not find post id ' . $post->id, 401);
    }


    public function restoreDeleted($id)
    {
        $post = Post::withTrashed()->find($id);
        return $post->restore() ?
            response_success(['post' => $post], 'retore deleted post id ' . $post->id) : response_error([], 'can not find post id ' . $post->id, 401);
    }

}
