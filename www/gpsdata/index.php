<?php
/**
 * Main entry point for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../includes/gpsDataApp.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$gpsData = $gpsDataHelper->getGpsData();
header("content-security-policy: default-src 'self';");
?>
<html>
    <head>
        <title><?php echo SITE_TITLE_GPSDATA_APP ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="<?php echo SITE_ROBOTS ?>">
        <meta http-equiv="x-ua-compatible" content="IE=edge">
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="120x120" href="../apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
        <link rel="manifest" href="../site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../media/css/bootstrap.min.css" integrity="sha384-T8BvL2pDN59Kgod7e7p4kesUb+oyQPt3tFt8S+sIa0jUenn1byQ97GBKHUN8ZPk0" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <p>
                <a class="btn btn-dark" href="../map/index.php?site_secret=<?php echo SITE_SECRET ?>">Go to Map</a>
                <a class="btn btn-dark" href="../tracker/index.php?site_secret=<?php echo SITE_SECRET ?>">Go to Tracker App</a>
            </p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tracker ID</th>
                        <th>Title</th>
                        <th class="d-none d-md-block d-lg-block d-xl-block">Last Updated Date</th>
                        <th class="d-none d-md-block d-lg-block d-xl-block">Last Updated Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gpsData as $gpsPoint) : ?>
                        <tr>
                            <td><?php echo $gpsPoint['device_id'] ?></td>
                            <td><?php echo $textMappingHelper->getTrackerById($gpsPoint['device_id'])['title'] ?></td>
                            <td class="d-none d-md-block d-lg-block d-xl-block"><?php echo $gpsPoint['date'] ?></td>
                            <td class="d-none d-md-block d-lg-block d-xl-block"><?php echo $gpsPoint['time'] ?></td>
                            <td>
                                <a href="actions/view.php?site_secret=<?php echo SITE_SECRET ?>&id=<?php echo $gpsPoint['device_id'] ?>" class="btn btn-sm btn-outline-info">View</a>
                                <form method="POST" action="actions/delete.php">
                                    <input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
                                    <input type="hidden" name="id" value="<?php echo $gpsPoint['device_id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
