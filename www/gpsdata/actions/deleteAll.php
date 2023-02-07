<?php
/**
 * Delete action for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2023 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/gpsDataAction.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

// Delete all GPS Points
$trackerGpsDataHelper->deleteAllGpsPoints();

header('Location: ../index.php?site_secret=' . SITE_SECRET);
