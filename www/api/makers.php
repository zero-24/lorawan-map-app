<?php
/**
 * Ajax entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../includes/api.php';

$gpsData = $fileHelper->readJsonFile('gps_data');
$textMappings = $fileHelper->readJsonFile('text_mapping');

foreach ($gpsData as $gpsPoint)
{
	foreach ($textMappings as $textMapping)
	{
		if ($textMapping['device_id'] === $gpsPoint['device_id'])
		{
			$popupText = MARKER_POPUP_TEXT_TEMPLATE;
			$popupText = str_replace('{title}', $textMapping['title'], $popupText);
			$popupText = str_replace('{longtext}', $textMapping['longtext'], $popupText);
			$popupText = str_replace('{date}', $gpsPoint['date'], $popupText);
			$popupText = str_replace('{time}', $gpsPoint['time'], $popupText);

			$markers[] = [$gpsPoint['device_id'], $gpsPoint['latitude'], $gpsPoint['longitude'], $popupText];
			continue;
		}
	}
}

// Output the json
header('Content-Type: application/json');
echo json_encode($markers);
