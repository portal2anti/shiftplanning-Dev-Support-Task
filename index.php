<html>
<head><meta charset="UTF-8"></head>
<body>
<?php
	require_once("TableGenerator.php");
	require './shiftplanning.php';
	
	// access to API
	$shiftplanning = new shiftplanning(
		array(
			'key' => '6f0ee92499e528bc30968a7af7f401f68209afb9' // enter your developer key
		)
	);

	// Check session
	$session = $shiftplanning->getSession();

	if(!$session) {
		$response = $shiftplanning->doLogin(
			array(// these fields are required to login
				'username' => 'shu.xuan.shu@gmail.com',
				'password' => 'RandomNumber',
			)
		);
	}

	// Get shifts
	$shifts = $shiftplanning->setRequest(
		array(
			'token' => $shiftplanning->getAppToken(),
			'module' => 'schedule.shifts',
			'start_date' => 'today',
			'end_date' => 'today',
			'mode' => 'overview'
		)
	);

	// Will hold table data
	$tableData = array();

	foreach($shifts['data'] as $shift) {
		// Prepare data for pushing to $tableData
		$obj = new stdClass;
		$obj->name = $shift['employees'][0]['name'];
		$obj->time = $shift['start_date']['time'] . '-' .$shift['end_date']['time'];
		// Location is optional when making shift, so check
		if ($shift['location']) {
			$obj->location = $shift['location']['name'];
		} else {
			$obj->location = "n/a";
		}
		array_push($tableData, $obj);
	}

	$tg = new TableGenerator($tableData);
	$tg->getTable();
?>
</body>
</html>