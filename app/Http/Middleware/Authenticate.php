<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class Authenticate
{

  public function handle($request, Closure $next)
  {
    try {
      if (!$user = JWTAuth::parseToken()->authenticate()) {
        return response_error([], 'User not found', 404);
      }
    } catch (TokenBlacklistedException $e) {

      return response_error([], 'The token has been blacklisted');

    } catch (TokenExpiredException $e) {
      return response_error([], 'The token expired');

    } catch (TokenInvalidException $e) {

      return response_error([], 'The token Invalid');

    } catch (JWTException $e) {

      return response_error([], 'The token absent');

    }
    return $next($request);
  }


}