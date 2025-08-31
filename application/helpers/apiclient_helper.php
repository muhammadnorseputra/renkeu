<?php

/**
 * Function Name
 *
 * Function Description
 *
 * @access	public
 * @param	type	name
 * @return	type	
 */

if (! function_exists('api_client')) {
	function api_client($url)
	{
		$api_url = $url;
		$json_data = file_get_contents($api_url);
		return json_decode($json_data, TRUE);
	}
}
if (! function_exists('file_get_contents_curl')) {

	function file_get_contents_curl($url)
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
}

if (! function_exists('api_curl')) {
	function api_curl($url, $arr)
	{
		// set post fields
		$post = $arr;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);

		// do anything you want with your response
		return $response;
	}
}

if (! function_exists('api_curl_get')) {
	function api_curl_get($url = '')
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);

		if ($response === false) {
			$response = '-';
		}
		// do anything you want with your response
		return $response;
	}
}

if (! function_exists('api_qr_code')) {
	function api_qr_code($url = 'http://localhost:5001/session/start', $params = [])
	{

		$curl = curl_init();

		$posts = json_encode($params);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $posts,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
}
