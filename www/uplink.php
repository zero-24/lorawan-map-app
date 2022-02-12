<?php
/**
 * Uplink entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../includes/uplink.php';

if ($input->getString('uplink_secret', false) !== UPLINK_SECRET)
{
	exit;
}

// Read current gps data
$currentGpsData = $fileHelper->readJsonFile('gps_data');

// Read data
$dataUplinkMessages = (array) $input->json->get('uplink_message', array());
$dataDevice = (array) $input->json->get('end_device_ids', array());

// Loop through the current data
foreach ($currentGpsData as $currentGpsPoint)
{
	if ($currentGpsPoint['device_id'] !== $dataDevice['device_id'])
	{
		$gpsData[] = $currentGpsPoint;
	}
}

// Write new data to an stdClass object
$tracker = new stdClass;

// Take the data from the payload
foreach ($dataUplinkMessages['decoded_payload'] as $key => $value)
{
	$tracker->$key = $value;
}

$tracker->date = date("d-m-Y");
$tracker->time = date("H:i:s");
$tracker->device_id = $dataDevice['device_id'];

// We do not have any latitude nor longitude values -> we can not use that update
if (!isset($tracker->latitude) || !isset($tracker->longitude))
{
	return;
}

// Append the new / updated tracker data to the array
$gpsData[] = $tracker;

// Encode the json
$json = json_encode((array) $gpsData);

// Write the JSON Data to the data folder
$fileHelper->writeJsonFile('gps_data', $json);

