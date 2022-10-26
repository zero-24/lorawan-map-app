<?php
/**
 * View action for the tracker text mapping edit application
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

?>
<?php include '../sites/header.php' ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>View Point: <b><?php echo $point['title'] ?></b></h3>
            </div>
            <div class="card-body">
                <a class="btn btn-secondary" href="edit.php?site_secret=<?php echo SITE_SECRET ?>&id=<?php echo $point['point_id'] ?>">Edit</a>
                <form style="display: inline-block" method="POST" action="delete.php">
                    <input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
                    <input type="hidden" name="id" value="<?php echo $point['point_id'] ?>">
                    <button class="btn btn-danger">Delete</button>
                </form>
                <a class="btn btn-info" href="../index.php?site_secret=<?php echo SITE_SECRET ?>">Cancel</a>
            </div>
            <table class="table">
                <tbody>
                <tr>
                    <th>Tracker ID:</th>
                    <td><?php echo $point['point_id'] ?></td>
                </tr>
                <tr>
                    <th>Title:</th>
                    <td><?php echo $point['title'] ?></td>
                </tr>
                <tr>
                    <th>Longtext:</th>
                    <td><?php echo $point['longtext'] ?></td>
                </tr>
                <tr>
                    <th>Callsign:</th>
                    <td><?php echo $point['callsign'] ?></td>
                </tr>
                <tr>
                    <th>Contact/Leader:</th>
                    <td><?php echo $point['pointleader'] ?></td>
                </tr>
                <tr>
                    <th>Strength:</th>
                    <td><?php echo $point['strength_leader'] . ' / ' . $point['strength_groupleader'] . ' / ' . $point['strength_helper'] . ' // <b>' . $point['strength'] . '</b>' ?></td>
                </tr>
                <tr>
                    <th>Latitude:</th>
                    <td><?php echo $point['latitude'] ?></td>
                </tr>
                <tr>
                    <th>Longitude:</th>
                    <td><?php echo $point['longitude'] ?></td>
                </tr>
                <tr>
                    <th>Visibility:</th>
                    <td><?php echo $point['visibility'] ? 'Visible' : 'Hidden' ?></td>
                </tr>
                <tr>
                    <th>Group:</th>
                    <td><?php echo $point['group'] ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php include '../sites/footer.php' ?>
