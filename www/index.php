<?php
/**
 * Main entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../includes/app.php';

$gpsData = $fileHelper->readJsonFile('gps_data');
$textMappings = $fileHelper->readJsonFile('text_mapping');

foreach ($gpsData as $gpsPoint)
{
	$points[] = [$gpsPoint['latitude'], $gpsPoint['longitude']];

	foreach ($textMappings as $textMapping)
	{
		if ($textMapping['device_id'] === $gpsPoint['device_id'])
		{
			$markers[] = [$gpsPoint['latitude'], $gpsPoint['longitude'], $textMapping['title']];
			continue;
		}
	}
}

/* Dummy Daten
$markers = [
    [50.807029, 7.134106, "Big Ben"],
    [50.808024, 7.134209, "London Eye"],
    [50.809024, 7.134509, "Nelson's Column<br><a href=\"https://en.wikipedia.org/wiki/Nelson's_Column\">wp</a>"]
];

$points = [
    [50.807029, 7.134106],
    [50.808024, 7.134209],
    [50.809024, 7.134509]
];

exit;
*/
$cspnonce = base64_encode(bin2hex(random_bytes(64)));
header("content-security-policy: default-src 'self'; script-src 'self' 'nonce-" . $cspnonce . "'; img-src 'self' https://*.openstreetmap.org")

?>

<html>
    <head>
        <title>DRK Troisdorf Einsatztracker Map</title>
        <!--<meta http-equiv="refresh" content="5">-->
        <link rel="stylesheet" href="media/css/leaflet.css" />
        <link rel="stylesheet" href="media/css/app.css" />
        <script src="media/js/leaflet.js"></script>
        <script language="javascript" nonce="<?php echo $cspnonce; ?>">
            window.addEventListener(
                'DOMContentLoaded',
                function () {
                    var map = new L.Map('map');
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
                        maxZoom: 18
                    }).addTo(map);

                    map.fitBounds(<?php echo json_encode($points); ?>);

                    // Define the markers
                    var markers = <?php echo json_encode($markers); ?>;

                    // Loop through the markers array
                    for (var i=0; i < markers.length; i++)
                    {
                        var lat = markers[i][0];
                        var lon = markers[i][1];
                        var popupText = markers[i][2];

                        var markerLocation = new L.LatLng(lat, lon);
                        var marker = new L.Marker(markerLocation);
                        map.addLayer(marker);
                        marker.bindPopup(popupText);
                    }
                },
                false
            );
        </script>
    </head>
    <body>
        <div id="map" class="map-height"></div>
    </body>
</html>
