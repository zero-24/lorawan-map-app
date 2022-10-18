<?php
/**
 * View action for the tracker text mapping edit application
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

include '../../../includes/trackerActions.php';

if ($input->getString('site_secret', false) !== SITE_SECRET)
{
    exit;
}

$deviceIdExists = $input->exists('id');

if (!$deviceIdExists)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

$deviceId = (int) $input->getString('id');
$tracker  = $textMappingHelper->getTrackerById($deviceId);

if (!$tracker)
{
    include '../sites/header.php';
    include '../sites/not_found.php';
    include '../sites/footer.php';
    exit;
}

?>
<?php include '../sites/header.php' ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>View Tracker: <b><?php echo $tracker['title'] ?></b></h3>
            </div>
            <div class="card-body">
                <a class="btn btn-secondary" href="edit.php?site_secret=<?php echo SITE_SECRET ?>&id=<?php echo $tracker['device_id'] ?>">Edit</a>
                <form style="display: inline-block" method="POST" action="delete.php">
                    <input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
                    <input type="hidden" name="id" value="<?php echo $tracker['device_id'] ?>">
                    <button class="btn btn-danger">Delete</button>
                </form>
                <a class="btn btn-info" href="../index.php?site_secret=<?php echo SITE_SECRET ?>">Cancel</a>
            </div>
            <table class="table">
                <tbody>
                <tr>
                    <th>Tracker ID:</th>
                    <td><?php echo $tracker['device_id'] ?></td>
                </tr>
                <tr>
                    <th>Title:</th>
                    <td><?php echo $tracker['title'] ?></td>
                </tr>
                <tr>
                    <th>Longtext:</th>
                    <td><?php echo $tracker['longtext'] ?></td>
                </tr>
                <tr>
                    <th>Callsign:</th>
                    <td><?php echo $tracker['callsign'] ?></td>
                </tr>
                <tr>
                    <th>Groupleader:</th>
                    <td><?php echo $tracker['groupleader'] ?></td>
                </tr>
                <tr>
                    <th>Strength:</th>
                    <td><?php echo $tracker['strength_leader'] . ' / ' . $tracker['strength_groupleader'] . ' / ' . $tracker['strength_helper'] . ' // <b>' . $tracker['strength'] . '</b>' ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php include '../sites/footer.php' ?>
