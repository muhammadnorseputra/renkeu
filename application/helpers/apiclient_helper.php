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
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // this should be set to
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
	function api_qr_code($url, $params = [])
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
			// >>> Disable SSL verification (INSECURE — for testing only)
			CURLOPT_SSL_VERIFYPEER => false, // do not verify the peer's certificate
			CURLOPT_SSL_VERIFYHOST => 0,     // do not check the certificate's name against host
			// <<< end insecure options
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
}

/**
 * GET data dari API eksternal dengan custom headers dan request body.
 *
 * Digunakan untuk endpoint yang menerima GET + JSON body (non-standar tapi
 * dimiliki beberapa API seperti SILKA).
 *
 * @param  string $url     Endpoint URL
 * @param  array  $headers Assoc array header kustom, e.g. ['apiKey' => 'xxx']
 * @param  array  $body    Data yang dikirim sebagai JSON body (walaupun GET)
 * @return array           ['success' => bool, 'data' => mixed, 'error' => string|null]
 */
function api_get_with_headers($url, $headers = [], $body = [])
{
    $ch = curl_init();

    // Encode body menjadi JSON
    $jsonBody = json_encode($body);

    // Set header default + header kustom
    $httpHeaders = ['Content-Type: application/json'];
    foreach ($headers as $key => $value) {
        $httpHeaders[] = "$key: $value";
    }

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => 'POST', // Gunakan POST untuk mengirim body, walaupun GET
        CURLOPT_POSTFIELDS     => $jsonBody,
        CURLOPT_HTTPHEADER     => $httpHeaders,
        CURLOPT_SSL_VERIFYPEER => false, // sesuaikan di production
        CURLOPT_SSL_VERIFYHOST => 0,     // sesuaikan di production
        CURLOPT_TIMEOUT        => 30,
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['success' => false, 'data' => null, 'error' => $error];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true);

    return [
        'success'  => $httpCode >= 200 && $httpCode < 300,
        'data'     => $decoded,
        'error'    => $httpCode >= 300 ? "HTTP $httpCode" : null,
    ];
}

/**
 * Ambil data pegawai berdasarkan Unit Organisasi (UNOR) dari API SILKA.
 *
 * Endpoint: http://silka.balangankab.go.id/services/pegawaiWithBasicAuth/getPegawaiByUnor
 * Method  : GET + JSON body + custom header apiKey
 *
 * @param  string $unor_id  ID unit organisasi (default: 1120)
 * @return array            Data pegawai atau array kosong jika gagal
 */
function silka_get_pegawai_by_unor($unor_id = '1120')
{
    $url  = 'http://silka.balangankab.go.id/services/pegawaiWithBasicAuth/getPegawaiByUnor';
    $args = [
        'headers' => ['apiKey' => 'bkpsdm6811'],
        'body'    => ['unor_id' => $unor_id],
    ];

    $result = api_get_with_headers($url, $args['headers'], $args['body']);

    if (!$result['success']) {
        log_message('error', '[SILKA] Gagal ambil pegawai UNOR ' . $unor_id . ': ' . $result['error']);
        return [];
    }

    return $result['data'];
}

/**
 * Ambil data pegawai PPPK dari API SILKA.
 *
 * Endpoint: http://silka.balangankab.go.id/services/pppk
 * Method  : GET + JSON body + custom header apiKey
 *
 * @return array            Data pegawai PPPK atau array kosong jika gagal
 */
function silka_get_pppk($unor_id = '1120')
{
    $url  = 'http://silka.balangankab.go.id/services/pppk';
    $args = [
        'headers' => ['apiKey' => 'bkpsdm6811'],
        'body'    => ['unor_id' => $unor_id],
    ];

    $result = api_get_with_headers($url, $args['headers'], $args['body']);

    if (!$result['success']) {
        log_message('error', '[SILKA] Gagal ambil data PPPK UNOR ' . $unor_id . ': ' . $result['error']);
        return [];
    }

    return $result['data'];
}

function sendWaMessage($url, $session, $to, $text, $is_group = false)
{
	$curl = curl_init();

	$payload = json_encode([
		"session"  => $session,
		"to"       => $to,
		"text"     => $text,
		"is_group" => $is_group
	]);

	curl_setopt_array($curl, [
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING       => '',
		CURLOPT_MAXREDIRS      => 10,
		CURLOPT_TIMEOUT        => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST  => 'POST',
		CURLOPT_POSTFIELDS     => $payload,
		CURLOPT_HTTPHEADER     => [
			'Content-Type: application/json'
		],
		// Kalau server SSL bermasalah dan butuh skip check (TESTING only):
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0,
	]);

	$response = curl_exec($curl);

	if ($response === false) {
		$error = curl_error($curl);
		curl_close($curl);
		return [
			"success" => false,
			"error"   => $error
		];
	}

	curl_close($curl);

	return [
		"success"  => true,
		"response" => $response
	];
}