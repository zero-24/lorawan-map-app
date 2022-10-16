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

$gpsData = $fileHelper->readJsonFile('gps_data');
$textMappings = $fileHelper->readJsonFile('text_mapping');

foreach ($gpsData as $gpsPoint)
{
	$mappingFound = false;

	$popupText = MARKER_POPUP_TEXT_TEMPLATE;
	$popupText = str_replace('{date}', $gpsPoint['date'], $popupText);
	$popupText = str_replace('{time}', $gpsPoint['time'], $popupText);

	foreach ($textMappings as $textMapping)
	{
		if ($textMapping['device_id'] === $gpsPoint['device_id'])
		{
			$mappingFound = true;

			$popupText = str_replace('{title}', $textMapping['title'], $popupText);
			$popupText = str_replace('{longtext}', $textMapping['longtext'], $popupText);
			$popupText = str_replace('{groupleader}', $textMapping['groupleader'], $popupText);
			$popupText = str_replace('{callsign}', $textMapping['callsign'], $popupText);
			$popupText = str_replace('{strength}', $textMapping['strength_leader'] . ' / ' . $textMapping['strength_groupleader'] . ' / ' . $textMapping['strength_helper'] . ' // <b>' . $textMapping['strength'] . '</b>', $popupText);

			continue;
		}
	}

	if (!$mappingFound)
	{
		$popupText = str_replace('{title}', 'No data in the text_mapping.json found for this device_id: "' . $gpsPoint['device_id'] . '"', $popupText);
		$popupText = str_replace('{longtext}', 'No data in the text_mapping.json found for this device_id: "' . $gpsPoint['device_id'] . '"', $popupText);
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

// Output the json
header('content-type: application/json');
echo json_encode($markers);
