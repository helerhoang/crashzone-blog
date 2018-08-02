<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
            'image' => 'required'
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

    /**
     * add image from folder
     * api/v1/add-image | Post
     */

    public function addImageFromFolder() {
        $urls = getUrlImageFromFolder();
        foreach($urls as $key => $url) {
            $image = str_replace("/","",getNameImage($url));
            $image_explode = explode(".",$image);
            $nameImage = $image_explode[0];
            $extension = $image_explode[1];
            $mime_type = mime_content_type($url);
            $name_slug = slugify($nameImage);
            $existsImage = Image::where('slug', $name_slug)->first();

            $slug = $existsImage ? $name_slug . '-duplicate-' . faker()->uuid : $name_slug;

            $encoded_name = date('Ymd') . '-' . time() * 1000 . '-' . faker()->uuid() . '-' . $name_slug;

            $imageSaved = Storage::disk('local')->putFileAs(imageDirectory(), new File($url), $encoded_name. '.'. $extension);

            $imagePath = Storage::url($imageSaved);
            $imageCreated[] = Image::create([
                'name' => $nameImage,
                'alt' => $name_slug,
                'slug' => $slug,
                'path' => $imagePath,
                'extension' => $extension,
                'mime_type' => $mime_type,
                'size' => 1043
            ]);

//            \Illuminate\Support\Facades\Storage::delete((storage_path('app/public/images_of_content/' . $image)));

        }

//        dd($image);
        return response_success([
            'images' => $imageCreated
        ]);

    }

    /**
     * attach image post into table image_post
     * api/v1/attach-image-post | Post
     */

    public function attachImagePost() {
        $images = Image::select('id','name')->skip(1)->take(1389)->get();
        foreach($images as $key => $image) {
            $id_image = $image->id;
            $id_post = getIdPostFromNameImage($image->name);
            $posts =Post::find($id_post);
            $posts->images()->attach($id_image);
        }
    }
}
