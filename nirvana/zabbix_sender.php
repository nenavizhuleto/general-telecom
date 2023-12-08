#!/usr/bin/php
<?php

function zbx_create_header($plain_data_size, $compressed_data_size = null)
{
	$protocol = "ZBXD";
	$flags = 0x01;
	if (is_null($compressed_data_size)) {
		$datalen = $plain_data_size;
		$reserved = 0;
	} else {
		$flags |= 0x02;
		$datalen = $compressed_data_size;
		$reserved = $plain_data_size;
	}
	return $protocol . chr($flags) . pack("VV", $datalen, $reserved);
}

$data = [
	[
		"host" => "Nirvana",
		"key" => "reg",
		"value" => "test",
	]
];

$json = json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;

$packet = zbx_create_header(strlen($json)) . $data;

$url = 'http://193.150.102.91';
$options = [
	'http' => [
		'header' => zbx_create_header(strlen($json)),
		'method' => 'POST',
		'content' => http_build_query($data)
	],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === false) {
	/* Handle error */
}

var_dump($result);
