<?php
/**
 * Main entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../includes/mapHistoryApp.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$gpsData = $trackerGpsDataHelper->getGpsData();

foreach ($gpsData as $gpsPoint)
{
    if ($gpsPoint['latitude'] === '-90' || $gpsPoint['longitude'] === '-100')
    {
        continue;
    }

    $points[] = [$gpsPoint['latitude'], $gpsPoint['longitude']];
}

if (empty($points))
{
    $points[] = ['50.8070725023327','7.133824179895859'];
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
        <link rel="stylesheet" href="../media/css/bootstrap.min.css" integrity="sha384-T8BvL2pDN59Kgod7e7p4kesUb+oyQPt3tFt8S+sIa0jUenn1byQ97GBKHUN8ZPk0" crossorigin="anonymous">
        <link rel="stylesheet" href="../media/css/fontawesome.css" />
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

                    var editPointButton = L.Control.extend({
                    options: {
                        position: 'topleft'
                    },

                    onAdd: function (map) {
                        var container = L.DomUtil.create('input');
                        container.type = 'button';
                        container.title = 'Edit Point Data';
                        container.value = 'EP';
                        container.style.backgroundSize = '30px 30px';
                        container.style.width = '35px';
                        container.style.height = '35px';
                        container.style.textAlign = 'center';
                        container.className = 'leaflet-bar';

                        container.onclick = function() {
                            window.open('../point/index.php?site_secret=<?php echo SITE_SECRET ?>', '_self');
                        }

                        return container;
                    }
                    });

                    var regularMapButton = L.Control.extend({
                    options: {
                        position: 'topleft'
                    },

                    onAdd: function (map) {
                        var container = L.DomUtil.create('input');
                        container.type = 'button';
                        container.title = 'Regular Map';
                        container.value = 'RM';
                        container.style.backgroundSize = '30px 30px';
                        container.style.width = '35px';
                        container.style.height = '35px';
                        container.style.textAlign = 'center';
                        container.className = 'leaflet-bar';

                        container.onclick = function() {
                            window.open('../map/index.php?site_secret=<?php echo SITE_SECRET ?>', '_self');
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
                    map.addControl(new editPointButton());
                    map.addControl(new regularMapButton());

                    // Focus on the the inital set of points
                    map.fitBounds(<?php echo json_encode($points); ?>);

                    /**
                    * Calls the markers api endpoint and sets based on that result the markers on the page
                    *
                    * @return {void}
                    */
                    function updateMarkers(updateFitMap = false)
                    {
                        const apiDate = document.getElementById('mapDateSelect').value;

                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', 'api/makers.php', true);
                        xhr.setRequestHeader('content-type', 'application/json');
                        xhr.setRequestHeader('api-date', apiDate);
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

                                var latlngs = Array();

                                if (markers.length === 0)
                                {
                                    console.log('No Data for: ' + apiDate);
                                    return;
                                }

                                // Loop through the markers array
                                for (var i = 0; i < markers.length; i++)
                                {
                                    var markerUpdateColor = markers[i][4];
                                    var markerIconClass = markers[i][5];

                                    var deviceId = markers[i][0] + i;
                                    var lat = markers[i][1];
                                    var lon = markers[i][2];
                                    var popupText = markers[i][3];
                                    var markerLocation = new L.LatLng(lat, lon);

                                    var marker = new L.Marker(markerLocation, {
                                        icon: L.divIcon({
                                            html: '<i class="fa-solid fa-' + markerIconClass + ' fa-2xl ' + markerUpdateColor + '"></i>',
                                            iconSize: [20, 20],
                                            className: 'markerDivIcon'
                                        })
                                    });

                                    marker.device_id = deviceId;
                                    map.addLayer(marker);
                                    marker.bindPopup(popupText);

                                    // Get latlng from marker and add it to the array
                                    latlngs.push(marker.getLatLng());

                                    // Open the marker when it was open before the refresh
                                    if (marker.device_id == openDeviceId)
                                    {
                                        marker.openPopup();
                                    }
                                }

                                // From documentation https://leafletjs.com/reference.html#polyline
                                // Create a black polyline from an arrays of LatLng points
                                var polyline = L.polyline(latlngs, {color: markerUpdateColor});
                                polyline.device_id = deviceId;

                                map.addLayer(polyline);

                                if (updateFitMap == true)
                                {
                                    // Zoom the map to the polyline
                                    map.fitBounds(polyline.getBounds());
                                }

                                polyline.apiDate = apiDate;
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

                    document.getElementById('mapDateSelect').addEventListener(
                        'change',
                        function() {
                            updateMarkers(true)
                        }
                    );
                },
                false
            );
        </script>
    </head>
    <body>
        <select class="custom-select" max-width="30%" id="mapDateSelect">
            <?php $date = $input->getString('date', date("Ymd")); ?>
            <option selected value="<?php echo $date ?>">Choose date to analyse</option>
            <?php $fileNames = $trackerGpsDataHelper->getStoredGpsPointFileNames(); ?>
            <?php foreach ($fileNames as $filename) : ?>
                <option value="<?php echo $filename ?>"><?php echo $filename ?></option>
            <?php endforeach ?>
        </select>
        <div id="map" class="map-height-analyse"></div>
    </body>
</html>
