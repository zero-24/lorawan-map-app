<?php
/**
 * Edit action for the tracker text mapping edit application
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

$pointData = [
    'point_id'             => '',
    'title'                => '',
    'longtext'             => '',
    'callsign'             => '',
    'latitude'             => '',
    'longitude'            => '',
    'location'             => '',
    'visibility'           => '',
    'group'                => '',
    'pointleader'          => '',
    'strength_leader'      => '',
    'strength_groupleader' => '',
    'strength_helper'      => '',
    'icon'                 => '',
];

$errors = [
    'point_id'             => '',
    'title'                => '',
    'longtext'             => '',
    'callsign'             => '',
    'latitude'             => '',
    'longitude'            => '',
    'visibility'           => '',
    'group'                => '',
    'pointleader'          => '',
    'strength_leader'      => '',
    'strength_groupleader' => '',
    'strength_helper'      => '',
    'icon'                 => '',
];

$isValid = true;

if ($input->getMethod() === 'POST')
{
    $point = array_merge($pointData, $point);

    foreach ($point as $key => $value)
    {
        $point[$key] = $input->getString($key);
    }

    // Check whether the data is valid $point and $errors are passed by reference
    $isValid = $pointDataHelper->validatePoint($point, $errors, 'edit');

    if ($isValid)
    {
        $point = $pointDataHelper->editPoint($point, $pointId);

        if ($input->getString('return', false) === 'map')
        {
            header('Location: ../../map/index.php?site_secret=' . SITE_SECRET);
            exit;
        }


        header('Location: ../index.php?site_secret=' . SITE_SECRET);
    }
}

?>
<?php include '../sites/header.php' ?>
<?php include '../sites/form.php' ?>
<?php include '../sites/footer.php' ?>

