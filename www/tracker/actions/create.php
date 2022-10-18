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

$tracker = [
    'device_id'            => '',
    'title'                => '',
    'longtext'             => '',
    'callsign'             => '',
    'groupleader'          => '',
    'strength_leader'      => '',
    'strength_groupleader' => '',
    'strength_helper'      => '',
];

$errors = [
    'device_id'            => '',
    'title'                => '',
    'longtext'             => '',
    'callsign'             => '',
    'groupleader'          => '',
    'strength_leader'      => '',
    'strength_groupleader' => '',
    'strength_helper'      => '',
];

$isValid = true;

if ($input->getMethod() === 'POST')
{
    foreach ($tracker as $key => $value)
    {
        $tracker[$key] = $input->getString($key);
    }

    // Check whether the data is valid $tracker and $errors are passed by reference
    $isValid = $textMappingHelper->validateTracker($tracker, $errors);

    if ($isValid)
    {
        $tracker = $textMappingHelper->createTracker($tracker);

        header('Location: ../index.php?site_secret=' . SITE_SECRET);
    }
}

?>
<?php include '../sites/header.php' ?>
<?php include '../sites/form.php' ?>
<?php include '../sites/footer.php' ?>
