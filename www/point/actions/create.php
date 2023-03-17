<?php
/**
 * Create action for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/pointDataAction.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$point = [
    'point_id'             => $pointDataHelper->getNextPointId(),
    'title'                => '',
    'longtext'             => '',
    'callsign'             => '',
    'latitude'             => $input->getString('lat', '50.8070725023327'),
    'longitude'            => $input->getString('lng', '7.133824179895859'),
    'location'             => '',
    'visibility'           => '',
    'group'                => '',
    'pointleader'          => '',
    'strength_leader'      => '0',
    'strength_groupleader' => '0',
    'strength_helper'      => '0',
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
    foreach ($point as $key => $value)
    {
        $point[$key] = $input->getString($key);
    }

    // Check whether the data is valid $point and $errors are passed by reference
    $isValid = $pointDataHelper->validatePoint($point, $errors, 'create');

    if ($isValid)
    {
        $point = $pointDataHelper->createPoint($point);

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
