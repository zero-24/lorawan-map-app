<?php
/**
 * Main entry point for the json edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/trackerActions.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
	exit;
}

$deviceID = $input->getString('id');

if (!isset($deviceID) || empty($deviceID))
{
    include '../sites/not_found.php';
    exit;
}

$textMappingHelper->deleteTracker($deviceID);

header('Location: ../index.php?site_secret=' . SITE_SECRET);
