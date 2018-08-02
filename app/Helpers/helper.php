<?php

use Tymon\JWTAuth\Facades\JWTAuth;
use Faker\Factory as Faker;
use App\Models\Image;



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
    $path = 'public/' . date('Ymd');

    $imageDir = storage_path('app/') . $path;

    if (!file_exists($imageDir)) {
        mkdir($imageDir);
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

function getSrcImage($content) {
   try {
       $first_img = strpos($content->content, '<img ');
       $last_img = strpos($content->content, '/>');
       $img = substr($content->content, $first_img, $last_img - $first_img );
       $src = substr($img, strpos($img, 'src'), strpos($img, 'alt') - strpos($img,'src'));
       $src = stripslashes($src);
       $path_img = substr($src, strpos($src, 'http'), stripos($src, ' ') - strpos($src, 'http'));
       $path_img = str_replace('"','',$path_img);
       return $path_img;
   }catch (Exception $e){
   }
}

function getNameImage($img) {
    preg_match('/[a-zA-z].*\//',$img,$path);
    if (count($path[0])) {
        $name = str_replace($path[0],"",$img);
    }
    return $name;
}

function getUrlImageFromFolder() {
    $directory = storage_path('app/public/images_of_content');
    $urls = glob($directory . "/*.{jpg,png,gif,JPG,PNG}", GLOB_BRACE);
    return $urls;
}

function getIdPostFromNameImage($name) {
    $name_explode = explode("_",$name);
    $id_post = $name_explode[0];
//    dd($id_post);
    return $id_post;
}