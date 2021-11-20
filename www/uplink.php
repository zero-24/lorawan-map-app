<?php
/**
 * Uplink entry point for the application
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../includes/uplink.php';

if ($input->getString('uplink_secret', false) !== UPLINK_SECRET)
{
	exit;
}

$data = (array) $input->json->get('uplink_message', array());

var_dump($data);

// Write the JSON Data to the data folder
