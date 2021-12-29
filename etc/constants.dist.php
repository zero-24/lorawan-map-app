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

// The site title to be shown on the page
define('SITE_TITLE', '');

// The site secret to be passed to be allowed to access the page
define('SITE_SECRET', '');

// Change the robots options
define('SITE_ROBOTS', 'noindex, nofollow');

// The seconds how often the markers should be updated
define('MARKER_REFRESH_SECONDS', 5);

// The text template to be displayed in the popup
define('MARKER_POPUP_TEXT_TEMPLATE', '<b>{title}</b><br>{longtext}<br><small>Last Update:{date} {time}</small>');
