<?php
/**
 * Main entry point for the lorawan-map-app application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../includes/indexApp.php';

// Check whether the correct secret has been set
if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

// Redirect to the map app
header('Location: map/index.php?site_secret=' . SITE_SECRET);
