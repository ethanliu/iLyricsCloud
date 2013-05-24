<?php
/**
 * CURL class
 *
 **/

 class CURL {
 	var $callback = false;
 	function setCallback($func_name) {
 		$this->callback = $func_name;
 	}
 	function doRequest($method, $url, $vars) {
 		$ch = curl_init();
 		curl_setopt($ch, CURLOPT_URL, $url);
 		curl_setopt($ch, CURLOPT_HEADER, 0);
 		//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
 		// force user agent
 		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1");
 		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
 		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
 		if ($method == 'POST') {
 			curl_setopt($ch, CURLOPT_POST, 1);
 			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
 		}
 		$data = curl_exec($ch);
 		curl_close($ch);
 		if ($data) {
 			if ($this->callback) {
 				$callback = $this->callback;
 				$this->callback = false;
 				return call_user_func($callback, $data);
 			}
 			else {
 				return $data;
 			}
 		}
 		else {
 			return curl_error($ch);
 		}
 	}
 	function get($url) {
 		 return $this->doRequest('GET', $url, 'NULL');
 	}

 	function post($url, $vars) {
 		 return $this->doRequest('POST', $url, $vars);
 	}
 }
