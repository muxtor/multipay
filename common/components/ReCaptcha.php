<?php

namespace common\components;

/**
 * Description of ReCaptcha
 *
 * @author ilya <2377635@gmail.com>
 */
class ReCaptcha {
	
	private static $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
	
	public static function verify($secret, $response, $remoteIP = null)
	{
		$params = http_build_query(['secret' => $secret, 'response' => $response]);
		if ($remoteIP) {
			$params['remoteip'] = $remoteIP;
		}
		
		$curl = curl_init();
		if($curl) {
			curl_setopt($curl, CURLOPT_URL, self::$verifyUrl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			$out = json_decode(curl_exec($curl));
			curl_close($curl);
			return $out->success;
		}

	}
}
