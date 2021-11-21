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

// Read data
$dataUplinkMessages = (array) $input->json->get('uplink_message', array());
$dataDevice = (array) $input->json->get('end_device_ids', array());

// Write data to an stdClass object
$tracker = new stdClass;

foreach ($dataUplinkMessages['decoded_payload'] as $key => $value)
{
    $tracker->$key = $value;
}

$tracker->time = date("d-m-Y H:i:s");
$tracker->device_id = $dataDevice['device_id'];

// In the end we need an array of tracker elements
$gpsData[] = $tracker;

// Encode the json
$json = json_encode((array) $gpsData);

// Write the JSON Data to the data folder
$fileHelper->writeJsonFile('gps_data', $json);

