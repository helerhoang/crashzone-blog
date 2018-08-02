<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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





    /**
     * download image from table post on field content
     * api/v1/download-image | GET
     */
    public function downloadImageFormPost()
    {
        $contents = Post::select('id', 'content')->skip(0)->take(80)->get();
        foreach ($contents as $key => $content) {
            $id_post = $content->id;
            $url = getSrcImage($content);
            try {
                if ($url[$key] === "") {
                    unset($url[$key]);
                    continue;
                }
            } catch (\ErrorException $e) {
                continue;
            }
            try {
                $nameSaved = $id_post . "_" . getNameImage($url);

                if (!file_exists(storage_path('app/public/images_of_content/'))) {
                    mkdir(storage_path('app/public/images_of_content/'));
                }

                $path = storage_path('app/public/images_of_content/' . $nameSaved);
                $file_path = fopen($path, 'w');

                $client = new Client();
                if ($client->head($url)) {
                    $client->get($url, ['save_to' => $file_path]);
                }

            } catch (ClientException $e) {
                continue;
            } catch (ConnectException $e) {
                continue;
            } catch (NotFoundHttpException $e) {
                continue;
            }

        }
    }



}
