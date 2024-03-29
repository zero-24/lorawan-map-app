<?php
/**
 * Delete action for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/pointDataAction.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$pointIdExists = $input->exists('id');

if (!$pointIdExists)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

$pointId = $input->getString('id');
$point   = $pointDataHelper->getPointById($pointId);

if (!$point)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

$pointDataHelper->deletePoint($pointId);

header('Location: ../index.php?site_secret=' . SITE_SECRET);
