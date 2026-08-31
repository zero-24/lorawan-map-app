
<?php
/**
 * traccar entry point for the application
 *
 * @copyright  Copyright (C) 2026 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/traccarApp.php';

// Read current gps data
$currentGpsData = $fileHelper->readJsonFile('tracker_gpsdata');
$todaysGpsData  = $fileHelper->readJsonFile(date("Ymd") . '_tracker_gpsdata');

// Read data
$dataDeviceId = (int) $input->post->getInteger('id');

// Loop through the current data
foreach ($currentGpsData as $currentGpsPoint)
{
    if ($currentGpsPoint['device_id'] !== $dataDeviceId)
    {
        $gpsData[] = $currentGpsPoint;
    }
}

// Write new data to an stdClass object
$tracker = new stdClass;
$tracker->type = 'traccar';
$tracker->latitude = $input->post->getString('lat');
$tracker->longitude = $input->post->getString('lon');
$tracker->altitude = $input->post->getString('altitude');
$tracker->timestamp = $input->post->getString('timestamp');
$tracker->accuracy = $input->post->getString('accuracy');
$tracker->speed = $input->post->getString('speed');
$tracker->batt = $input->post->getString('batt');
$tracker->charge = $input->post->getString('charge');

$tracker->date = date("d-m-Y");
$tracker->time = date("H:i:s");
$tracker->device_id = (string) $dataDeviceId;

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
