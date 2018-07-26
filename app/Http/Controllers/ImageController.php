<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;
use App\Models\Image;
use Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->only(['image']), [
            'image' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response_error([
                'errors' => $validator->errors()
            ]);
        };

        $file = $request->file('image');
        $image = imageInformation($file);

        $imageSaved = Storage::disk('local')->putFileAs(imageDirectory(), new File($file), $image['encoded_name'] . '.' . $image['extension']);

        $imagePath = Storage::url($imageSaved);

        $imageStored = Image::create([
            'name' => $image['name'],
            'alt' => $image['alt'],
            'slug' => $image['slug'],
            'path' => $imagePath,
            'extension' => $image['extension'],
            'mime_type' => $image['mime_type'],
            'size' => $image['size']
        ]);

        return response_success([
            'image' => $imageStored,
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
    public function destroy($id)
    {
        //
    }
}
