<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $articles = Article::with('author', 'images')->take(5)->get();

        return response_success(['articles' => $articles]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $articleInfo = $request->only(['title', 'description', 'content']);
        $validator = Validator::make($articleInfo, [
            'title' => 'required|min:3|unique:articles',
            'description' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response_error(['errors' => $validator->errors()]);
        };

        $title = trim($articleInfo['title']);
        $title_seo = str_replace(' ', '-', strtolower(friendlyString($title)));
        $description = trim($articleInfo['description']);
        $content = trim($articleInfo['content']);

        $newArticle = Article::create(['title' => $title, 'title_seo' => $title_seo, 'description' => $description, 'content' => $content]);

        return response_success(['article' => $newArticle]);
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
        $article = Article::where('slug', $slug)->first();

        return response_success(['article' => $article]);
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
    public function destroy(Article $article)
    {
        return $article->delete()
            ? response_success(['article' => $article], 'deleted article id ' . $article->id)
            : response_error([], 'can not find article id ' . $article->id, 401);
    }
}
