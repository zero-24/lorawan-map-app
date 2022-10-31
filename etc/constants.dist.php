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

// The site title for the tracker app to be shown on the page
define('SITE_TITLE_TRACKER_APP', '');

// The site title for the GPS Data app to be shown on the page
define('SITE_TITLE_GPSDATA_APP', '');

// The site title for the Point Data app to be shown on the page
define('SITE_TITLE_POINT_APP', '');

// Change the robots options
define('SITE_ROBOTS', 'noindex, nofollow');

// The seconds how often the markers should be updated
define('MARKER_REFRESH_SECONDS', 5);

// The grace time when the marker should get red (usage https://www.php.net/manual/en/dateinterval.createfromdatestring.php)
define('MARKER_GRACE_TIME', '90 seconds');

// The text template to be displayed in the popup for trackers
define('MARKER_POPUP_TEXT_TEMPLATE', '<b>{title}</b><br>{groupleader}<br>{strength}<br>{callsign}<br>{longtext}<br><small>Last Updated: {date} {time}</small>');

// The text template to be displayed in the popup for fixed points
define('MARKER_POPUP_POINT_TEXT_TEMPLATE', '<b>{title}</b><br>{pointleader}<br>{strength}<br>{callsign}<br>{longtext}<br><small>Group: {group}</small>');

// The text template to be displayed in the popup for fixed points
define('MARKER_POPUP_HISTORY_TEXT_TEMPLATE', '<b>{deviceId}</b><br>Updated: {date} {time}');

// The Marker Icons Suggestion
define('MARKER_ICON_ARRAY_SUGGESTION', [
    'tower-control' => 'Führungstelle',
    'car-side' => 'ELW',
    'tent' => 'UHS',
    'hospital' => 'Krankenhaus',
    'truck-medical' => 'KTW / RTW',
    'truck-ramp-box' => 'MZF',
    'van-shuttle' => 'MTF',
    'people-roof' => 'Notunterkunft',
    'location-crosshairs' => 'Einsatzstelle',
    'house-fire' => 'Feuerwache',
    'house-medical-flag' => 'DRK Unterkunft',
    'location-pin' => 'Sonderpin',
    'house' => 'Wache',
    'gas-pump' => 'Tankstelle',
    '0' => 'EVT 0',
    '1' => 'EVT 1',
    '2' => 'EVT 2',
    '3' => 'EVT 3',
    '4' => 'EVT 4',
    '5' => 'EVT 5',
    '6' => 'EVT 6',
    '7' => 'EVT 7',
    '8' => 'EVT 8',
    '9' => 'EVT 9',
]);
