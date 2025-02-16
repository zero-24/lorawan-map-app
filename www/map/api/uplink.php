<?php
/**
 * Uplink entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/uplinkApp.php';

if ($input->getString('uplink_secret', false) !== UPLINK_SECRET)
{
    exit;
}

// Read current gps data
$currentGpsData = $fileHelper->readJsonFile('tracker_gpsdata');
$todaysGpsData  = $fileHelper->readJsonFile(date("Ymd") . '_tracker_gpsdata');

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

// Append the new tracker data
$todaysGpsData[] = $tracker;

// Encode the json
$json = json_encode((array) $gpsData);
$todaysGpsJson = json_encode((array) $todaysGpsData);

// Write the JSON Data to the data folder
$fileHelper->writeJsonFile('tracker_gpsdata', $json);

// Check whether the GPS data should be stored
if (UPLINK_STORE_DATA === true)
{
    $fileHelper->writeJsonFile(date("Ymd") . '_tracker_gpsdata', $todaysGpsJson);
}

// Check whether there is a upstram server to send data to
if (defined('UPLINK_UPSTREAM_MAP_APP_API_URL'))
{
    // Send tracker data via curl to the upstream map
    $apiURL = str_replace(['<id>', '<lat>', '<long>'], [$tracker->device_id, $tracker->latitude, $tracker->longitude], UPLINK_UPSTREAM_MAP_APP_API_URL);

    try
    {
        $response = $http->get($apiURL);
    }
    catch (RuntimeException $e)
    {
        // Dont log any errors
    }
}

