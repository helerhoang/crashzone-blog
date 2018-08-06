<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('subCategories', 'posts')->withCount('posts')->parentIdIsNull()->get();

        return response_success(['categories' => $categories]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validateCategory($request);

        $name = $request->name;

        $category = Category::create([
            'name' => $name
        ]);

        return response_success([
            'category' => $category
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
        $this->validateCategory($request);

        $category->name = $request->name;
        $category->save();
        return response_success(['category' => $category]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        return $category->delete()
            ? response_success(['category' => $category], 'deleted category id ' . $category->id)
            : response_error([], 'can not find category id ' . $category->id, 401);
    }

    /*
     *  SOFT DELETE
     */

    public function indexDeleted()
    {
        $categories = Category::onlyTrashed()->get();
        return response_success(['categories' => $categories]);
    }

    public function destroyDeleted($id)
    {
        $category = Category::withTrashed()->find($id);
        return $category->forceDelete() ?
            response_success(['category' => $category], 'deleted permanently category id ' . $category->id) : response_error([], 'can not find category id ' . $category->id, 401);
    }


    public function restoreDeleted($id)
    {
        $category = Category::withTrashed()->find($id);
        return $category->restore() ?
            response_success(['category' => $category], 'retore deleted category id ' . $category->id) : response_error([], 'can not find category id ' . $category->id, 401);
    }


    public function validateCategory($request)
    {
        $validator = Validator::make($request->only(['name']), [
            'name' => 'required|min:3|unique:categories'
        ]);

        if ($validator->fails()) {
            return response_error([
                'errors' => $validator->errors()
            ]);
        }

        return $request;
    }



}


