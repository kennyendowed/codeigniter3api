<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use \Firebase\JWT\JWT;


if ( ! function_exists('validateAuth')){
      function validateAuth($token)
      {
          if(!$token) {
          		$output = array("Error" => "Access Denied");
          		return $this->response(['message' => $output], RestController::HTTP_UNAUTHORIZED);
          }
          try {
          		$payload = JWT::decode($token,env('PUBLICKEY'),array('HS256'));
          } catch (Exception $ex) {
          		$output = array("Error" => $ex->getMessage());
          		return $this->response(['message' => $output], RestController::HTTP_UNAUTHORIZED);
          }
      }
   }


if ( ! function_exists('random')){
   function random(){
         $number = rand(1111,9999);
         return $number;
       }
   }

   if ( ! function_exists('decode_jwt_token')){
      function decode_jwt_token($token){
        $key = env('PUBLICKEY');
        $jwt = JWT::decode($token, $key,array('RS256'));
            return $jwt;
          }
      }

if ( ! function_exists('current_utc_date_time')){
   function current_utc_date_time(){
         $dateTime = gmdate("Y-m-d\TH:i:s\Z");;
         return $dateTime;
       }
   }
   if ( ! function_exists('password_check')){
      function password_check($str)
     {
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
          return TRUE;
        }
        return FALSE;
     }
      }
   if ( ! function_exists('validate_mobile')){
      function validate_mobile($mobile)
      {
          return preg_match('/^[0-9]{11}+$/', $mobile);
      }
}
if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
