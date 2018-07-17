<?php

use Tymon\JWTAuth\Facades\JWTAuth;

function friendlyString($string)
{
    $newString = preg_replace('/[^\p{L}\p{N}\s]/u', '', trim($string));
    return $newString;
}

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

function auth_user()
{
    return JWTAuth::parseToken()->authenticate();
}