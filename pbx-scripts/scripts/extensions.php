<?php
error_reporting(0);

/****************
 * s - street   *
 * b - building *
 * p - porch    *
 * B - block    *
 ****************/

require_once('common.php');

$dbGolos = new mysqli(
	$configGolos->dbhost,
	$configGolos->dbuser,
	$configGolos->dbpass,
	$configGolos->dbtable,
	$configGolos->dbport
);

$dbAsterisk = new mysqli(
	$configAsterisk->dbhost,
	$configAsterisk->dbuser,
	$configAsterisk->dbpass,
	$configAsterisk->dbtable,
	$configAsterisk->dbport
);

#system("pwd");
/* get gates  */
system("\$PWD/get_gates {$configGolos->dbpass}");

// [rooms-sssbbbb]
$buildingsResult = $dbGolos->query("
	SELECT
		`building`.`id`, `building`.`code`,
		`street`.`code` AS `street_code`
	FROM `building`
	INNER JOIN `street` ON `street`.`id` = `building`.`street_id`
	ORDER BY `street`.`code` DESC, `building`.`code`
");
while ($building = $buildingsResult->fetch_object()) {
	echo "[rooms-{$building->street_code}{$building->code}]\n";

	$roomsResult = $dbGolos->query("
		SELECT
			`room`.`id`, `room`.`num`
		FROM `room`
		INNER JOIN `porch` ON `porch`.`id` = `room`.`porch_id`
		WHERE `porch`.`building_id` = {$building->id}
	");
	while ($room = $roomsResult->fetch_object()) {
		$room->room_code = sprintf('%03d', $room->num);

		$dials = [];
		$devicesResult = $dbGolos->query("
			SELECT
				`device`.`sippeer_id`,
				`device`.`ps_endpoint_id`,
				`device`.`sipusername`
			FROM `device`
			WHERE `room_id` = {$room->id}
			ORDER BY `num`
		");
		while ($device = $devicesResult->fetch_object()) {
			if (null !== $device->sippeer_id)
				$dials[] = "SIP/{$device->sipusername}";

			if (null !== $device->ps_endpoint_id)
				$dials[] = "SIP/topbx/{$device->sipusername}";
		}

		if (count($dials)) {
			$dials = implode('&', $dials);

			echo "exten => {$room->room_code},1,Dial($dials,60)\n";

			if ('0' == $room->room_code[0])
				echo "exten => {$room->num},1,Dial($dials,60)\n";
		}
	}

	echo "exten => 999,1,Dial(SIP/123456)\n";
	echo "\n";
}
// [from-persdom]
$buildingsResult = $dbGolos->query("
	SELECT
		`building`.`id`, `building`.`code`,
		`street`.`code` AS `street_code`
	FROM `building`
	INNER JOIN `street` ON `street`.`id` = `building`.`street_id`
	ORDER BY `street`.`code` DESC, `building`.`code`
");
echo "[from-persdom]\n";
while ($building = $buildingsResult->fetch_object()) {

	$roomsResult = $dbGolos->query("
		SELECT
			`room`.`id`, `room`.`num`
		FROM `room`
		INNER JOIN `porch` ON `porch`.`id` = `room`.`porch_id`
		WHERE `porch`.`building_id` = {$building->id}
	");
	while ($room = $roomsResult->fetch_object()) {
		$room->room_code = sprintf('%03d', $room->num);

		$dials = [];
		$devicesResult = $dbGolos->query("
			SELECT
				`device`.`sippeer_id`,
				`device`.`ps_endpoint_id`,
				`device`.`sipusername`
			FROM `device`
			WHERE `room_id` = {$room->id} AND `type` = 8
			ORDER BY `num`
		");
		while ($device = $devicesResult->fetch_object()) {
			if (null !== $device->sippeer_id)
				echo "exten => _x./{$device->sipusername},1,Goto(rooms-{$building->street_code}{$building->code},{$room->num},1)\n";
		}
	}

	echo "\n";
}

// [room-out-sssbbbbpp]
$porchesResult = $dbGolos->query("
	SELECT
		`porch`.`num`, `porch`.`concierge_device_id`,
		`building`.`code` AS `building_code`,
		`street`.`code` AS `street_code`
	FROM `porch`
	INNER JOIN `building` ON `building`.`id` = `porch`.`building_id`
	INNER JOIN `street` ON `street`.`id` = `building`.`street_id`
	ORDER BY `street`.`code` DESC, `building`.`code`, `porch`.`num`
");
while ($porch = $porchesResult->fetch_object()) {
	$porch->code = sprintf('%02d', $porch->num);

	echo "[room-out-{$porch->street_code}{$porch->building_code}{$porch->code}]\n";
	
	// Concierge
	if ($porch->concierge_device_id) {
		$conciergeDeviceResult = $dbGolos->query("SELECT * FROM `device` WHERE `id` = {$porch->concierge_device_id}");
		$conciergeDevice = $conciergeDeviceResult->fetch_object();

		// TODO: CHECK AUTOSAVING $porch->concierge_device_id!
		echo "exten =>         000,1,Dial(SIP/{$conciergeDevice->sipusername})\n";
		echo "exten =>         999,1,Dial(SIP/{$conciergeDevice->sipusername})\n";
		echo "exten =>         _x./{$conciergeDevice->sipusername},1,Goto(blockout_custom,\${EXTEN},1)\n";
	}

	echo "exten =>        _xxx,1,NoOp(Rule #2)\n";
	echo " same =>             n,Goto(rooms-{$porch->street_code}{$porch->building_code},\${EXTEN},1)\n";
	echo "exten =>         _xx,1,NoOp(Rule #2)\n";
	echo " same =>             n,Goto(rooms-{$porch->street_code}{$porch->building_code},0\${EXTEN},1)\n";
	echo "exten =>          _x,1,NoOp(Rule #2)\n";
	echo " same =>             n,Goto(rooms-{$porch->street_code}{$porch->building_code},00\${EXTEN},1)\n";
	echo "exten => _xxxxxxxxxx,1,NoOp(Rule #3)\n";
	echo " same =>             n,Goto(rooms,\${EXTEN},1)\n";
	echo "\n";
}

// [rooms]
echo "[rooms]\n";
$buildingsResult = $dbGolos->query("
	SELECT
		`building`.`code`,
		`street`.`code` AS `street_code`
	FROM `building`
	INNER JOIN `street` ON `street`.`id` = `building`.`street_id`
	ORDER BY `street`.`code` DESC, `building`.`code`
");
while ($building = $buildingsResult->fetch_object()) {
	echo "exten => _{$building->street_code}{$building->code}xxx,1,Goto(rooms-{$building->street_code}{$building->code},\${EXTEN:7},1)\n";
}

echo "exten => _xxxxxxxxxx,1,NoOp(Unknown building!)\n";
echo "\n";

// [block-out-BB]
$blocksResult = $dbGolos->query("
	SELECT
		`id`, `num`
	FROM `block`
	ORDER BY `num`
");
while ($block = $blocksResult->fetch_object()) {
	$block->code = sprintf('%02d', $block->num);

	echo "[block-out-{$block->code}]\n";

	$blockBuildingsResult = $dbGolos->query("
		SELECT
			`building`.`id` AS `building_id`,
			`building`.`code` AS `building_code`,
			`street`.`code` AS `street_code`
		FROM `block_building`
		INNER JOIN `building` ON `building`.`id` = `block_building`.`building_id`
		INNER JOIN `street` ON `street`.`id` = `building`.`street_id`
		WHERE `block_building`.`block_id` = {$block->id}
		ORDER BY `street`.`code` DESC, `building`.`code`
	");
	while ($blockBuilding = $blockBuildingsResult->fetch_object()) {
		// To some room of building
    echo "exten => _{$blockBuilding->building_code}xxx,1,Goto(rooms-{$blockBuilding->street_code}{$blockBuilding->building_code},\${EXTEN:4},1)\n";

    // To some room of building (short building_code)
    $short_building_code = trim($blockBuilding->building_code, '0');
    $short_start_at = strlen($short_building_code);
    echo "exten => _{$short_building_code}xxx,1,Goto(rooms-{$blockBuilding->street_code}{$blockBuilding->building_code},\${EXTEN:$short_start_at},1)\n";

    // To concierge of some porch
    $porchResult = $dbGolos->query("
    	SELECT 
    		`porch`.`num` AS `porch_num`,
    		`device`.`sipusername` AS `device_sipusername`
    	FROM `porch`
    	INNER JOIN `device` ON `device`.`id` = `porch`.`concierge_device_id`
    	WHERE `porch`.`building_id` = {$blockBuilding->building_id}
    	ORDER BY `porch`.`num`
    ");

    while ($porch = $porchResult->fetch_object()) {
    	$porch_num = sprintf('%02d', $porch->porch_num);
    	echo "exten => _{$blockBuilding->building_code}9$porch_num,1,Dial(SIP/{$porch->device_sipusername},60)\n";
	    echo "exten => _{$short_building_code}9$porch_num,1,Dial(SIP/{$porch->device_sipusername},60)\n";
    }

		// (13)(9)(01) - consierge of porch #1 of building #13
	}

	echo "exten => _x.,1,Goto(blockout_custom,\${EXTEN},1)\n";
	echo "\n";
}

$dbAsterisk->close();
$dbGolos->close();



