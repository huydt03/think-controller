<?php
declare (strict_types = 1);

namespace Huydt\ThinkController;

use Firebase\JWT\JWT as _JWT;
use Firebase\JWT\Key;

class JWT{

	private static $serect = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';

	private static $exp_minutes = 30;

	private static $alg = 'HS256';

	public static function encode($data = []){

		$serect_key  = env('jwt.serect', self::$serect);

		$minutes = env('jwt.exp_minutes', self::$exp_minutes);

		$exp_minutes = "$minutes minutes";

		$timestamp_now = date_create("now")->getTimestamp();
		
		$expire_at = date_modify(date_create("now"), $exp_minutes)->getTimestamp();
		
		$request_data = [
		    'iat'  => $timestamp_now,         	// Issued at: time when the token was generated
		    'iss'  => $_SERVER['SERVER_NAME'],  // Issuer
		    'nbf'  => $timestamp_now,         	// Not before
		    'exp'  => $expire_at,   			// Expire
		];

		// return json_encode([$exp_minutes, $serect_key]);

		return _JWT::encode(
	        array_merge($request_data, $data),
	        $serect_key,
	        self::$alg
	    );

	}

	public static function decode($jwt){

		$serect_key  = env('jwt.serect', self::$serect);

		// try{	
		// 	return _JWT::decode($jwt, new Key($serect_key, self::$alg));
		// }catch(\Exception $e){
		//      return $e->getMessage();
		// }

		return _JWT::decode($jwt, new Key($serect_key, self::$alg));

	}


}