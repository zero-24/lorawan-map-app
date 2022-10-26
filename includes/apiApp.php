<?php
/**
 * Api include
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../etc/constants.php';

// Ensure we've initialized Composer
if (!file_exists(ROOT_PATH . '/vendor/autoload.php'))
{
    exit(1);
}

require ROOT_PATH . '/vendor/autoload.php';

use zero24\Helper\FileHelper;
use zero24\Helper\TrackerGpsDataHelper;
use zero24\Helper\TrackerMetadataHelper;
use zero24\Helper\PointDataHelper;

// Setup the FileHelper
$fileHelper = new FileHelper([
    'dataFolder' => ROOT_PATH . '/data',
]);

$trackerGpsDataHelper = new TrackerGpsDataHelper([
    'dataFolder' => ROOT_PATH . '/data',
    'fileName' => 'tracker_gpsdata',
]);

$trackerMetadataHelper = new TrackerMetadataHelper([
    'dataFolder' => ROOT_PATH . '/data',
    'fileName' => 'tracker_metadata',
]);


// Setup the PointDataHelper
$pointDataHelper = new PointDataHelper([
    'dataFolder' => ROOT_PATH . '/data',
    'fileName' => 'tracker_pointdata',
]);


