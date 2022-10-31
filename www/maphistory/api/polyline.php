<?php
/**
 * Ajax entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/apiHistoryApp.php';

if (array_change_key_case(getallheaders(), CASE_LOWER)['api-token'] !== API_TOKEN)
{
    exit;
}

$gpsData = $trackerGpsDataHelper->getGpsData();
$markers = [];

foreach ($gpsData as $gpsPoint)
{
    // Skip invalid data
    if ($gpsPoint['latitude'] === '-90' || $gpsPoint['longitude'] === '-100')
    {
        continue;
    }

    $popupText = MARKER_POPUP_HISTORY_TEXT_TEMPLATE;
    $popupText = str_replace('{deviceId}', $gpsPoint['device_id'], $popupText);
    $popupText = str_replace('{date}', $gpsPoint['date'], $popupText);
    $popupText = str_replace('{time}', $gpsPoint['time'], $popupText);

    $icon = 'location-pin';
    $updateColor = 'black';

    // Add markers to the return array
    $markers[] = [$gpsPoint['device_id'], $gpsPoint['latitude'], $gpsPoint['longitude']];
}

/**
 * Well what is happening here...
 * We do need a structure like this for the multiple polylines (https://leafletjs.com/reference.html#polyline)
 * array(
 *   array(
 *       [lat,lon],
 *       [lat,lon],
 *       [lat,lon]
 *   ),
 *   array(
 *       [lat,lon],
 *       [lat,lon],
 *       [lat,lon]
 *   )
 * );
 * We archive this by using dynamic variable names (https://www.php.net/manual/en/language.variables.variable.php)
 * So first we loop over the existing markers adding all lat/lon array's mapping into an array of the coresponging $device_id
 * The seccond foreach starts with the list of all device_id's we know form the data
 * From there we join the dynamic variables created before into the $polyline result array
 */

$polyline = [];

foreach ($markers as $key => $value)
{
    // $device_id[] = [lat, lon]
    ${$value[0]}[] = [$value[1], $value[2]];
}

$gpsDataIds = $trackerGpsDataHelper->getGpsDataIds();

foreach ($gpsDataIds as $key => $value)
{
    //$polyline[] = $device_id;
    $polyline[] = ${$key};
}

// Output the json
header('content-type: application/json');
echo json_encode($polyline);
