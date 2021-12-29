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
			$markers[] = [$gpsPoint['device_id'], $gpsPoint['latitude'], $gpsPoint['longitude'], '<b>' . $textMapping['title'] . '</b><br>' . $textMapping['longtext']];
			continue;
		}
	}
}

// Output the json
header('Content-Type: application/json');
echo json_encode($markers);
