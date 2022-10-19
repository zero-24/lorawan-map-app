<?php
/**
 * Main entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../includes/mapApp.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$gpsData = $fileHelper->readJsonFile('tracker_gps_data');

foreach ($gpsData as $gpsPoint)
{
    $points[] = [$gpsPoint['latitude'], $gpsPoint['longitude']];
}

// Calculate the resfresh time for the markers
$markerRefresh = MARKER_REFRESH_SECONDS * 1000;

$cspnonce = base64_encode(bin2hex(random_bytes(64)));
header("content-security-policy: default-src 'self'; script-src 'self' 'nonce-" . $cspnonce . "'; img-src 'self' data: https://*.openstreetmap.org")

?>
<html>
    <head>
        <title><?php echo SITE_TITLE; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="<?php echo SITE_ROBOTS; ?>">
        <meta http-equiv="x-ua-compatible" content="IE=edge">
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="120x120" href="../apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
        <link rel="manifest" href="../site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <!-- CSS & JavaScript -->
        <link rel="stylesheet" href="../media/css/leaflet.css" />
        <link rel="stylesheet" href="../media/css/app.css" />
        <script type="text/javascript" src="../media/js/leaflet.js"></script>
        <!-- Map generation code -->
        <script language="javascript" nonce="<?php echo $cspnonce; ?>">
            window.addEventListener(
                'DOMContentLoaded',
                function () {
                    var editTrackerButton = L.Control.extend({
                    options: {
                        position: 'topleft'
                    },

                    onAdd: function (map) {
                        var container = L.DomUtil.create('input');
                        container.type = 'button';
                        container.title = 'Edit Tracker Data';
                        container.value = 'ET';
                        container.style.backgroundSize = '30px 30px';
                        container.style.width = '35px';
                        container.style.height = '35px';
                        container.style.textAlign = 'center';
                        container.className = 'leaflet-bar';

                        container.onclick = function() {
                            window.open('../tracker/index.php?site_secret=<?php echo SITE_SECRET ?>', '_self');
                        }

                        return container;
                    }
                    });

                    var viewGpsDataButton = L.Control.extend({
                    options: {
                        position: 'topleft'
                    },

                    onAdd: function (map) {
                        var container = L.DomUtil.create('input');
                        container.type = 'button';
                        container.title = 'View GPS Data';
                        container.value = 'GD';
                        container.style.backgroundSize = '30px 30px';
                        container.style.width = '35px';
                        container.style.height = '35px';
                        container.style.textAlign = 'center';
                        container.className = 'leaflet-bar';

                        container.onclick = function() {
                            window.open('../gpsdata/index.php?site_secret=<?php echo SITE_SECRET ?>', '_self');
                        }

                        return container;
                    }
                    });

                    var map = new L.Map('map');

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
                        maxZoom: 18
                    }).addTo(map);

                    // Add Edit Tracker Button to the Map
                    map.addControl(new editTrackerButton());
                    map.addControl(new viewGpsDataButton());

                    // Focus on the the inital set of points
                    map.fitBounds(<?php echo json_encode($points); ?>);

                    /**
                    * Calls the markers api endpoint and sets based on that result the markers on the page
                    *
                    * @return {void}
                    */
                    function updateMarkers()
                    {
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', 'api/makers.php', true);
                        xhr.setRequestHeader('content-type', 'application/json');
                        xhr.setRequestHeader('api-token', '<?php echo API_TOKEN ?>');

                        xhr.onload = function()
                        {
                            if (this.status >= 200 && this.status < 400)
                            {
                                // Success! -> Take the response as markers
                                var markers = JSON.parse(this.response);
                                var openDeviceId = '';

                                // Remove the existing device layers
                                map.eachLayer(function(layer)
                                {
                                    if (layer.hasOwnProperty('device_id'))
                                    {
                                        if (layer.isPopupOpen())
                                        {
                                            openDeviceId = layer.device_id;
                                        }
                                        map.removeLayer(layer);
                                    }
                                });

                                // Icons from https://github.com/pointhi/leaflet-color-markers
                                var blueIcon = new L.Icon({
                                    iconUrl: '../media/css/images/marker-icon-2x.png',
                                    shadowUrl: '../media/css/images/marker-shadow.png',
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41],
                                    popupAnchor: [1, -34],
                                    shadowSize: [41, 41]
                                });

                                var redIcon = new L.Icon({
                                    iconUrl: '../media/css/images/marker-icon-2x-red.png',
                                    shadowUrl: '../media/css/images/marker-shadow.png',
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41],
                                    popupAnchor: [1, -34],
                                    shadowSize: [41, 41]
                                });

                                // Loop through the markers array
                                for (var i = 0; i < markers.length; i++)
                                {
                                    var icon = blueIcon;
                                    var markersIcon = markers[i][4];

                                    // icon
                                    if (markersIcon == 'red')
                                    {
                                        icon = redIcon;
                                    }

                                    var deviceId = markers[i][0];
                                    var lat = markers[i][1];
                                    var lon = markers[i][2];
                                    var popupText = markers[i][3];
                                    var markerLocation = new L.LatLng(lat, lon);
                                    var marker = new L.Marker(markerLocation, {icon: icon});

                                    marker.device_id = deviceId;
                                    map.addLayer(marker);
                                    marker.bindPopup(popupText);

                                    // Open the marker when it was open before the refresh
                                    if (marker.device_id == openDeviceId)
                                    {
                                        marker.openPopup();
                                    }
                                }
                            }
                            else
                            {
                                console.log('Could not load the makers api endpoint');
                                return;
                            }
                        };

                        xhr.onerror = function()
                        {
                            console.log('Could not load the makers api endpoint');
                            return;
                        };

                        xhr.send();
                    }

                    // Set the inital markers
                    updateMarkers()

                    // Update the markers every said seconds
                    window.setInterval(updateMarkers, <?php echo $markerRefresh; ?>);
                },
                false
            );
        </script>
    </head>
    <body>
        <div id="map" class="map-height"></div>
    </body>
</html>
