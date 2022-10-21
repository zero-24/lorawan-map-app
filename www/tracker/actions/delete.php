<?php
/**
 * Delete action for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/trackerAction.php';

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
$tracker  = $trackerMetadataHelper->getTrackerById($deviceId);

if (!$tracker)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

$trackerMetadataHelper->deleteTracker($deviceId);

header('Location: ../index.php?site_secret=' . SITE_SECRET);
