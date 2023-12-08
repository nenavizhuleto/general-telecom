#!/usr/bin/php
<?php
$stdout = [];
exec("asterisk -rx \"sip show registry\"", $stdout);
$data = array_slice($stdout, 1, -2);
$registers = [];
foreach ($data as $row) {
	$columns = preg_split('/\s{2,}/', $row);
	$host = $columns[0];
	// Once more split 4th column because of asterisk formatting output
	$state = explode(" ", $columns[3])[1];
	$registers[] = [
		"host" => $host,
		"state" => $state
	];
}

echo json_encode($registers, JSON_PRETTY_PRINT) . PHP_EOL;
?>
