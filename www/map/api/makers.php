<?php
/**
 * Ajax entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/apiApp.php';

if (array_change_key_case(getallheaders(), CASE_LOWER)['api-token'] !== API_TOKEN)
{
    exit;
}

$gpsData  = $trackerGpsDataHelper->getGpsData();
$trackers = $trackerMetadataHelper->getTrackers();

foreach ($gpsData as $gpsPoint)
{
    $mappingFound = false;

    $popupText = MARKER_POPUP_TEXT_TEMPLATE;
    $popupText = str_replace('{date}', $gpsPoint['date'], $popupText);
    $popupText = str_replace('{time}', $gpsPoint['time'], $popupText);

    foreach ($trackers as $tracker)
    {
        if ($tracker['device_id'] === $gpsPoint['device_id'])
        {
            $mappingFound = true;

            $popupText = str_replace('{title}', $tracker['title'], $popupText);
            $popupText = str_replace('{longtext}', $tracker['longtext'], $popupText);
            $popupText = str_replace('{groupleader}', $tracker['groupleader'], $popupText);
            $popupText = str_replace('{callsign}', $tracker['callsign'], $popupText);
            $popupText = str_replace('{strength}', $tracker['strength_leader'] . ' / ' . $tracker['strength_groupleader'] . ' / ' . $tracker['strength_helper'] . ' // <b>' . $tracker['strength'] . '</b>', $popupText);

            continue;
        }
    }

    if (!$mappingFound)
    {
        $popupText = str_replace('{title}', 'No data in the tracker_metadata.json found for this device_id: "' . $gpsPoint['device_id'] . '"', $popupText);
        $popupText = str_replace('{longtext}', 'No data in the tracker_metadata.json found for this device_id: "' . $gpsPoint['device_id'] . '"', $popupText);
        $popupText = str_replace('{date}', $gpsPoint['date'], $popupText);
        $popupText = str_replace('{time}', $gpsPoint['time'], $popupText);
        $popupText = str_replace('{groupleader}', 'Unknown Group Leader', $popupText);
        $popupText = str_replace('{callsign}', 'Unknown Callsign', $popupText);
        $popupText = str_replace('{strength}', '? / ? / ? // <b>??</b>', $popupText);
    }

    // Icon mapping
    $icon = 'blue';

    $gpsTime = new DateTime($gpsPoint['date'] . ' ' . $gpsPoint['time']);
    $nowTime = new DateTime();

    // Substract from "now" the configured grace time
    $nowSubGracetime = $nowTime->sub(date_interval_create_from_date_string(MARKER_GRACE_TIME));

    // Compare the timestamps whether the gps time is within the grace time
    if ($gpsTime->getTimestamp() < $nowSubGracetime->getTimestamp())
    {
        // Make the icon red when the time has been exceeded
        $icon = 'red';
    }

    // Add markers to the return array
    $markers[] = [$gpsPoint['device_id'], $gpsPoint['latitude'], $gpsPoint['longitude'], $popupText, $icon];
}

$points = $pointDataHelper->getPoints();

foreach ($points as $point)
{
    if ($point['visibility'] === 0)
    {
        continue;
    }

    $popupText = MARKER_POPUP_POINT_TEXT_TEMPLATE;
    $popupText = str_replace('{title}', $point['title'], $popupText);
    $popupText = str_replace('{longtext}', $point['longtext'], $popupText);
    $popupText = str_replace('{callsign}', $point['callsign'], $popupText);
    $popupText = str_replace('{group}', $point['group'], $popupText);
    $popupText = str_replace('{pointleader}', $point['pointleader'], $popupText);
    $popupText = str_replace('{strength}', $point['strength_leader'] . ' / ' . $point['strength_groupleader'] . ' / ' . $point['strength_helper'] . ' // <b>' . $point['strength'] . '</b>', $popupText);

    // Make the icon red when the time has been exceeded
    $icon = 'red';

    // Add markers to the return array
    $markers[] = [$point['point_id'], $point['latitude'], $point['longitude'], $popupText, $icon];
}

// Output the json
header('content-type: application/json');
echo json_encode($markers);
