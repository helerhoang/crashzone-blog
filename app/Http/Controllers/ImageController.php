<?php

namespace App\Http\Controllers;

use App\Models\Post;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;
use App\Models\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function addImageFromFolder()
    {
        $count_content = Post::select('*')->count();
        for ($i = 0; $i <= 1; $i++) {
            $contents = Post::select('created_at')->skip($i)->take(1)->where('id',7311)->get();
            foreach($contents as $content) {
                $datetime = formatCreatedAt($content->created_at);
                $folder = $datetime['date'];
                $urls = getUrlImageFromFolder($folder);
                foreach ($urls as $key => $url) {
                    $image = str_replace("/", "", getImage($url));
                    $image = explode(".", $image);
                    $extension = $image[1];
                    $name = decodeNameImageFromFolder($image[0]);
                    $mime_type = mime_content_type($url);
                    $size = filesize($url);
                    $name_slug = slugify($name);
                    $existsImage = Image::where('slug', $name_slug)->first();

                    $slug = $existsImage ? $name_slug . '-duplicate-' . faker()->uuid : $name_slug;

                    $encoded_name = date('Ymd') . '-' . time() * 1000 . '-' . faker()->uuid() . '-' . $name_slug;

                    $imageSaved = Storage::disk('local')->putFileAs(imageDirectory(), new File($url), $encoded_name . '.' . $extension);

                    $imagePath = Storage::url($imageSaved);
                    $imageCreated[] = Image::create([
                        'name' => $name,
                        'alt' => $name_slug,
                        'slug' => $slug,
                        'path' => $imagePath,
                        'extension' => $extension,
                        'mime_type' => $mime_type,
                        'size' => $size
                    ]);

//            \Illuminate\Support\Facades\Storage::delete((storage_path('app/public/images_of_content/' . $image)));

                }
            }
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

    public function attachImagePost()
    {
        $count_content = Post::select('*')->count();
        $images = Image::select('id', 'name')->skip(1)->take($count_content)->get();
        foreach ($images as $key => $image) {
            $id_image = $image->id;
            $id_post = getIdPostFromNameImage($image->name);
            $posts = Post::find($id_post);
            $posts->images()->attach($id_image);
        }
    }


    /**
     * download image from table post on field content
     * api/v1/download-image | GET
     */
    public function downloadImageFormPost()
    {
        $count_content = Post::select('*')->count();
        for ($i = 0; $i <= 1; $i++) {
            $contents = Post::select('id', 'content','created_at','slug')
                ->skip($i)
                ->take(1)
                ->where('id', 7311)
                ->get();
            foreach ($contents as $key => $content) {
                $id_post = $content->id;
                $url = getSrcImage($content);
                $datetime = formatCreatedAt($content->created_at);
                $folder = $datetime['date'];
                try {
                    if ($url[$key] === "") {
                        unset($url[$key]);
                        continue;
                    }
                } catch (\ErrorException $e) {
                    continue;
                }
                try {
                    $image = getInformationImage($url);
                    $extension = $image['extension'];
                    $nameSaved = $folder . '_' . $id_post . "_"  . $content->slug . '.' . $extension ;

                    if (!file_exists(storage_path('app/public/posts/'. $folder))) {
                        mkdir(storage_path('app/public/posts/'. $folder), 0777, true);
                    }

                    $path = storage_path('app/public/posts/' .$folder . '/' . $nameSaved);
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

}
