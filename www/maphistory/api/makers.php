<?php
/**
 * Ajax entry point for the markers
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
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
    $markers[] = [$gpsPoint['device_id'], $gpsPoint['latitude'], $gpsPoint['longitude'], $popupText, $updateColor, $icon];
}

// Output the json
header('content-type: application/json');
echo json_encode($markers);
