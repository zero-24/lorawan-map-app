<?php
/**
 * View action for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/gpsDataAction.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$deviceIdExists = $input->exists('id');

if (!$deviceIdExists)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

$deviceId = $input->getString('id');
$gpsPoint = $trackerGpsDataHelper->getGpsPointById($deviceId);
$tracker  = $trackerMetadataHelper->getTrackerById($deviceId);

if (!$gpsPoint || !$tracker)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

?>
<?php include '../sites/header.php' ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>View GPS Point: <b><?php echo $tracker['title'] ?></b></h3>
            </div>
            <div class="card-body">
                <form class="inline-block" method="POST" action="delete.php">
                    <input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
                    <input type="hidden" name="id" value="<?php echo $gpsPoint['device_id'] ?>">
                    <button class="btn btn-danger">Delete</button>
                </form>
                <a class="btn btn-info" href="../index.php?site_secret=<?php echo SITE_SECRET ?>">Cancel</a>
            </div>
            <table class="table">
                <tbody>
                <tr>
                    <th>Tracker ID:</th>
                    <td><?php echo $gpsPoint['device_id'] ?></td>
                </tr>
                <tr>
                    <th>Tracker Name:</th>
                    <td><?php echo $tracker['title'] ?></td>
                </tr>
                <tr>
                    <th>Latitude:</th>
                    <td><?php echo $gpsPoint['latitude'] ?></td>
                </tr>
                <tr>
                    <th>Longitude:</th>
                    <td><?php echo $gpsPoint['longitude'] ?></td>
                </tr>
                <tr>
                    <th>Map:</th>
                    <td width="70%"><div id="location-map" class="map-readonly"></div></td>
                </tr>
                <tr>
                    <th>Altitude:</th>
                    <td><?php echo $gpsPoint['altitude'] ?></td>
                </tr>
                <tr>
                    <th>Satellites:</th>
                    <td><?php echo $gpsPoint['sat'] ?></td>
                </tr>
                <tr>
                    <th>Last Updated Date:</th>
                    <td><?php echo $gpsPoint['date'] ?></td>
                </tr>
                <tr>
                    <th>Last Updated Time:</th>
                    <td><?php echo $gpsPoint['time'] ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script language="javascript" nonce="<?php echo $cspnonce ?>">
        window.addEventListener(
        'DOMContentLoaded',
        function () {
            var map = new L.Map('location-map');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map);

            map.fitBounds(<?php echo json_encode([[$gpsPoint['latitude'], $gpsPoint['longitude']]]) ?>);
            map.addLayer(
                new L.Marker(
                    new L.LatLng(
                        <?php echo $gpsPoint['latitude'] ?>,
                        <?php echo $gpsPoint['longitude'] ?>
                    )
                )
            );
        });
    </script>
<?php include '../sites/footer.php' ?>
