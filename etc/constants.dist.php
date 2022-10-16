<?php
/**
 * Constants
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

define('ROOT_PATH', dirname(__DIR__));

// The secret to be passed by the remote server
define('UPLINK_SECRET', '');

// The site secret to be passed to be allowed to access the page
define('SITE_SECRET', '');

// The api token to be passed to the api endpoints
define('API_TOKEN', '');

// The site title to be shown on the page
define('SITE_TITLE', '');

// Change the robots options
define('SITE_ROBOTS', 'noindex, nofollow');

// The seconds how often the markers should be updated
define('MARKER_REFRESH_SECONDS', 5);

// The grace time when the marker should get red (usage https://www.php.net/manual/en/dateinterval.createfromdatestring.php)
define('MARKER_GRACE_TIME', '90 seconds');

// The text template to be displayed in the popup
define('MARKER_POPUP_TEXT_TEMPLATE', '<b>{title}</b><br>{groupleader}<br>{strength}<br>{callsign}<br>{longtext}<br><small>Last Updated: {date} {time}</small>');
