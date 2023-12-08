#!/usr/bin/php
<?php
if (count($argv) < 2 || !is_numeric($argv[1])) {
	exit(1);
}
require_once('common.php');

$dbGolos = new mysqli(
	$configGolos->dbhost,
	$configGolos->dbuser,
	$configGolos->dbpass,
	$configGolos->dbtable,
	$configGolos->dbport
);
function transliterate($textcyr = null, $textlat = null) {
	$cyr = array(
	'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
	'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
	$lat = array(
	'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
	'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q');
	if($textcyr) return str_replace($cyr, $lat, $textcyr);
	else if($textlat) return str_replace($lat, $cyr, $textlat);
	else return null;
}

$result = $dbGolos->query("
SELECT 
	`device`.`phone`, `device`.`sipusername`, `device`.`block_id`,
	`device`.`porch_id`, `device`.`room_id`, `device`.`comment`,
	`device`.`type`,
	`room`.`num` AS `room_num`,
	`porch`.`num` AS `porch_num`,
	`building`.`code` AS `building_code`,
	`building`.`num` AS `building_num`,
	`street`.`code` AS `street_code`, `street`.`title` AS `street_title`,
	`block`.`num` AS `block_num`, `block`.`name` AS `block_name`
FROM `device`
RIGHT JOIN `room` on `room`.`id` = `device`.`room_id`
RIGHT JOIN `porch` on `porch`.`id` = `room`.`porch_id`
RIGHT JOIN `building` on `building`.`id` = `porch`.`building_id`
RIGHT JOIN `street` on `street`.`id` = `building`.`street_id`
RIGHT JOIN `block_building` on `block_building`.`building_id` = `building`.`id`
RIGHT JOIN `block` on `block`.`id` = `block_building`.`block_id`
WHERE `device`.`sipusername` = {$argv[1]}
UNION ALL
SELECT 
	`device`.`phone`, `device`.`sipusername`, `device`.`block_id`,
	`device`.`porch_id`, `device`.`room_id`, `device`.`comment`,
	`device`.`type`,
	NULL AS `room_num`,
	`porch`.`num` AS `porch_num`,
	`building`.`code` AS `building_code`,
	`building`.`num` AS `building_num`,
	`street`.`code` AS `street_code`, `street`.`title` AS `street_title`,
	`block`.`num` AS `block_num`, `block`.`name` AS `block_name`
FROM `device`
RIGHT JOIN `porch` on `porch`.`id` = `device`.`porch_id`
RIGHT JOIN `building` on `building`.`id` = `porch`.`building_id`
RIGHT JOIN `street` on `street`.`id` = `building`.`street_id`
RIGHT JOIN `block_building` on `block_building`.`building_id` = `building`.`id`
RIGHT JOIN `block` on `block`.`id` = `block_building`.`block_id`
WHERE `device`.`sipusername` = {$argv[1]}
UNION ALL
SELECT 
	`device`.`phone`, `device`.`sipusername`, `device`.`block_id`,
	`device`.`porch_id`, `device`.`room_id`, `device`.`comment`,
	`device`.`type`,
	NULL AS `room_num`,
	NULL AS `porch_num`,
	`building`.`code` AS `building_code`,
	`building`.`num` AS `building_num`,
	`street`.`code` AS `street_code`, `street`.`title` AS `street_title`,
	`block`.`num` AS `block_num`, `block`.`name` AS `block_name`
FROM `device`
RIGHT JOIN `block` on `block`.`id` = `device`.`block_id`
RIGHT JOIN `block_building` on `block_building`.`block_id` = `block`.`id`
RIGHT JOIN `building` on `building`.`id` = `block_building`.`building_id`
RIGHT JOIN `street` on `street`.`id` = `building`.`street_id`
WHERE `device`.`sipusername` = {$argv[1]};");

if ($result->num_rows <= 0) {
	exit(1);
}
$device = $result->fetch_object();

if(in_array("-v", $argv)) {
print("SIP: " . $device->sipusername . 
	"\nblock num/name: " . $device->block_num . "/" . ($device->block_name ?: "none") . 
	"\nstreet: " . transliterate($device->street_title) .
	"\nbuilding num/code: " . $device->building_num . "/" . $device->building_code . 
	"\nporch: " . ($device->porch_num ?: "none") . 
	"\nroom: " . ($device->room_num ?: "none") . "\n"); 
}


$callerid = "";
$callerid = $callerid . implode("_", array_map(fn($str): string => substr($str, 0, 3), explode(" ", strtolower(transliterate($device->street_title)))));
$callerid = $callerid . $device->building_num;
$dev_type = "";
$dev_num = "";

switch($device->type) {
	case '1':
		$dev_type = 'pod';
		$dev_num = $device->porch_num;
		break;
	case '2': 
	case '3':
		$dev_type = 'dvor';
		$dev_num = implode("_", array_map(fn($str): string => substr($str, 0, 3), explode(" ", strtolower(transliterate($device->block_name)))));
		break;
	case '4':
		$dev_type = 'pxm';
		$dev_num = $device->porch_num;
		break;
	case '5':
		$dev_type = 'kon';
		$dev_num = $device->porch_num;
		break;
	case '6':
	case '7':
	case '8':
	case '9':
	case '0':
		$dev_type = 'k';
		$dev_num = $device->room_num;
		break;
	default:
		$dev_type = 'undf';
		break;
}



$callerid = $callerid . '-' . $dev_type . "_" . $dev_num;

echo $callerid . "\n";

exit(0);



