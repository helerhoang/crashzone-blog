<?php

use Tymon\JWTAuth\Facades\JWTAuth;
use Faker\Factory as Faker;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;


const ARRAY_IMAGE = ['jpg', 'png'];


function response_success($data = [], $msg = 'everything is ok', $status = 200, $note = 'custom response success')
{
    return response(
        [
            'status' => $status,
            'statusText' => 'OK',
            'data' => $data,
            'message' => $msg,
            'note' => $note
        ]


    );
}

function response_error($data = [], $msg = 'something went wrong', $status = 400, $note = 'custom response error')
{
    return response(
        [
            'status' => $status,
            'statusText' => 'ERROR',
            'data' => $data,
            'message' => $msg,
            'note' => $note
        ]
    );
}

function slugify($text)
{
  // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
    $text = trim($text, '-');

  // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

  // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function faker()
{
    $faker = Faker::create();
    return $faker;
}


function friendlyString($string)
{
    $newString = preg_replace('/[^\p{L}\p{N}\s]/u', '', trim($string));
    return $newString;
}

/* Image Helper */

function imageDirectory()
{
    $path = 'public/images/' . date('Ymd');

    $imageDir = storage_path('app/') . $path;

    if (!file_exists($imageDir)) {
        Storage::makeDirectory($imageDir, 0777, true);
    }

    return $path;
}

function imageInformation($image)
{
    $imageName = pathinfo($image->getClientOriginalName())['filename'];
    $name = pathinfo($imageName)['filename'];
    $extension = $image->getClientOriginalExtension();
    $mime_type = $image->getClientMimeType();
    $size = $image->getClientSize();

    $name_slug = slugify($name);

    $existsImage = Image::where('slug', $name_slug)->first();

    $slug = $existsImage ? $name_slug . '-duplicate-' . faker()->uuid : $name_slug;

    $encoded_name = date('Ymd') . '-' . time() * 1000 . '-' . faker()->uuid() . '-' . $name_slug;


    return
        collect([
        'name' => $name,
        'alt' => $name_slug,
        'extension' => $extension,
        'mime_type' => $mime_type,
        'size' => $size,
        'slug' => $slug,
        'encoded_name' => $encoded_name
    ]);
}

function getSrcImage($content)
{
    try {
        $first_img = strpos($content->content, '<img ');
        $last_img = strpos($content->content, '/>');
        $img = substr($content->content, $first_img, $last_img - $first_img);
        $src = substr($img, strpos($img, 'src'), strpos($img, 'alt') - strpos($img, 'src'));
        $src = stripslashes($src);
        $path_img = substr($src, strpos($src, 'http'), stripos($src, ' ') - strpos($src, 'http'));
        $path_img = str_replace('"', '', $path_img);
        return $path_img;
    } catch (Exception $e) {
    }
}

function getImage($img)
{
    preg_match('/[a-zA-z].*\//', $img, $path);
    if (count($path[0])) {
        $image = str_replace($path[0], "", $img);
    }
    return $image;
}

function formatCreatedAt($datetime) {
    $datetime = explode(' ', $datetime);
    try{
        $date = str_replace("-",'',$datetime[0]);
        $time = str_replace(":", '', $datetime[1]);
        return collect([
            'date' => $date,
            'time' => $time
        ]);
    }catch(Exception $e){}
}

function decodeNameImageFromFolder($nameImage) {
    $nameImage = explode("_", $nameImage);
    try{
        $name = $nameImage[2];
    }catch (Exception $e){}

    return $name;
}

function getInformationImage($img)
{
    preg_match('/[a-zA-z].*\//', $img, $path);
    if (count($path[0])) {
        $image = str_replace($path[0], "", $img);
        $image = explode(".", $image);
        try {
            $name = $image[0];
            $extension = $image[1];
        }catch (Exception $e) {}
    }
    return collect([
        'name' => $name,
        'extension' => $extension
    ]);
}

function getUrlImageFromFolder($folder)
{
    $directory = storage_path('app/public/posts/'. $folder);
    $urls = glob($directory . "/*.{jpg,png,gif,JPG,PNG}", GLOB_BRACE);
    return $urls;
}


function getIdPostFromNameImage($name)
{
    $name_explode = explode("_", $name);
    $id_post = $name_explode[0];
    return $id_post;
}

function saveImage($file)
{

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

    return $imageStored;

}