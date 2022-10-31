<?php
/**
 * Application include
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../etc/constants.php';

// Ensure we've initialized Composer
if (!file_exists(ROOT_PATH . '/vendor/autoload.php'))
{
    exit(1);
}

require ROOT_PATH . '/vendor/autoload.php';

use Joomla\Input;
use zero24\Helper\TrackerGpsDataHelper;

$input = new Input\Input;

$trackerGpsDataHelper = new TrackerGpsDataHelper([
    'dataFolder' => ROOT_PATH . '/data',
    'fileName' => $input->getString('date', date("Ymd")) . '_tracker_gpsdata',
]);
