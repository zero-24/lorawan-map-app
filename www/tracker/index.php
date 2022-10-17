<?php
/**
 * Main entry point for the json edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../includes/trackerApp.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
	exit;
}

$trackers = $textMappingHelper->getTrackers();
header("content-security-policy: default-src 'self';");
?>
<html>
	<head>
		<title><?php echo SITE_TITLE_TRACKER_APP ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="robots" content="<?php echo SITE_ROBOTS ?>">
		<meta http-equiv="x-ua-compatible" content="IE=edge">
		<!-- Favicon -->
		<link rel="apple-touch-icon" sizes="120x120" href="../apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
		<link rel="manifest" href="../site.webmanifest">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../media/css/bootstrap.min.css" integrity="sha384-T8BvL2pDN59Kgod7e7p4kesUb+oyQPt3tFt8S+sIa0jUenn1byQ97GBKHUN8ZPk0" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
			<p>
				<a class="btn btn-success" href="actions/create.php?site_secret=<?php echo SITE_SECRET ?>">Create new Tracker</a>
			</p>
			<table class="table">
				<thead>
					<tr>
						<th>Tracker ID</th>
						<th>Title</th>
						<th class="d-none d-md-block d-lg-block d-xl-block">Callsign</th>
						<th>Groupleader</th>
						<th class="d-none d-md-block d-lg-block d-xl-block">Strength</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($trackers as $tracker) : ?>
						<tr>
							<td><?php echo $tracker['device_id'] ?></td>
							<td><?php echo $tracker['title'] ?></td>
							<td class="d-none d-md-block d-lg-block d-xl-block"><?php echo $tracker['callsign'] ?></td>
							<td><?php echo $tracker['groupleader'] ?></td>
							<td class="d-none d-md-block d-lg-block d-xl-block"><?php echo $tracker['strength_leader'] . ' / ' . (int) $tracker['strength_groupleader'] . ' / ' . (int) $tracker['strength_helper'] . ' // <b>' . $tracker['strength'] . '</b>' ?></td>
							<td>
								<a href="actions/view.php?site_secret=<?php echo SITE_SECRET ?>&id=<?php echo $tracker['device_id'] ?>" class="btn btn-sm btn-outline-info">View</a>
								<a href="actions/edit.php?site_secret=<?php echo SITE_SECRET ?>&id=<?php echo $tracker['device_id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
								<form method="POST" action="actions/delete.php">
									<input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
									<input type="hidden" name="id" value="<?php echo $tracker['device_id'] ?>">
									<button class="btn btn-sm btn-outline-danger">Delete</button>
								</form>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
