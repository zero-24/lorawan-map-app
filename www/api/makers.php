<?php
/**
 * Ajax entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../includes/api.php';

/*if (array_change_key_case(getallheaders(), CASE_LOWER)['api-token'] !== API_TOKEN)
{
	exit;
}
*/
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

			continue;
		}
	}

	if (!$mappingFound)
	{
		$popupText = str_replace('{title}', 'No data in the text_mapping.json found for this device_id: "' . $gpsPoint['device_id'] . '"', $popupText);
		$popupText = str_replace('{longtext}', 'No data in the text_mapping.json found for this device_id: "' . $gpsPoint['device_id'] . '"', $popupText);
		$popupText = str_replace('{date}', $gpsPoint['date'], $popupText);
		$popupText = str_replace('{time}', $gpsPoint['time'], $popupText);
	}

	// Icon mapping
	$icon = 'blue';

	$gpsTime = new DateTime($gpsPoint['date'] . ' ' . $gpsPoint['time']);
	$nowTime = new DateTime();

	// Set substract from "now" 60 seconds to allow 60 seconds of grace time
	$nowBefore60seconds = $nowTime->sub(date_interval_create_from_date_string('60 seconds'));

	// Compare the timestamps whether the gps time is within the grace time
	if ($gpsTime->getTimestamp() < $nowBefore60seconds->getTimestamp())
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
