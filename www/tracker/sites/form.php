<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>
                <?php if ($tracker['device_id']): ?>
                    Edit Tracker <b><?php echo $tracker['title'] ?></b>
                <?php else : ?>
                    Create new Tracker
                <?php endif ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="">
                <div class="form-group">
                    <label>Tracker ID</label>
                    <?php if ($tracker['device_id']) : ?>
                        <input name="device_id" type="text" readonly value="<?php echo $tracker['device_id'] ?>" class="form-control <?php echo $errors['device_id'] ? 'is-invalid' : '' ?>">
                    <?php else : ?>
                        <input name="device_id" type="text" list="trackerGpsData" class="form-control <?php echo $errors['device_id'] ? 'is-invalid' : '' ?>">
                        <datalist id="trackerGpsData">
                            <?php $gpsData = $trackerGpsDataHelper->getGpsData() ?>
                            <?php foreach ($gpsData as $gpsPoint) : ?>
                                <?php echo '<option value="' . $gpsPoint['device_id'] . '"' . '>' . $gpsPoint['device_id'] . '</option>' ?>
                            <?php endforeach ?>
                        </datalist>
                    <?php endif ?>
                    <div class="invalid-feedback">
                        <?php echo $errors['device_id'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" type="text" value="<?php echo $tracker['title'] ?>" class="form-control <?php echo $errors['title'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['title'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Longtext</label>
                    <input name="longtext" type="text" value="<?php echo $tracker['longtext'] ?>" class="form-control <?php echo $errors['longtext'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['longtext'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Callsign</label>
                    <input name="callsign" type="text" value="<?php echo $tracker['callsign'] ?>" class="form-control <?php echo $errors['callsign'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['callsign'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Groupleader</label>
                    <input name="groupleader" type="text" value="<?php echo $tracker['groupleader'] ?>" class="form-control <?php echo $errors['groupleader'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['groupleader'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Strength</label>
                    <div class="form-group row">
                        <div class="col">
                            <label for="strength_leader" class="small">Number of association leaders and doctors</label>
                            <input id="strength_leader" name="strength_leader" type="number" step="1" value="<?php echo $tracker['strength_leader'] ?>" class="form-control <?php echo $errors['strength_leader'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['strength_leader'] ?>
                            </div>
                        </div>
                        <div class="col">
                            <label for="strength_groupleader" class="small">Number of group and squad leaders</label>
                            <input id="strength_groupleader" name="strength_groupleader" type="number" step="1" value="<?php echo $tracker['strength_groupleader'] ?>" class="form-control <?php echo $errors['strength_groupleader'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['strength_groupleader'] ?>
                            </div>
                        </div>
                        <div class="col">
                            <label for="strength_helper" class="small">Number of helpers</label>
                            <input id="strength_helper" name="strength_helper" type="number" step="1" value="<?php echo $tracker['strength_helper'] ?>" class="form-control <?php echo $errors['strength_helper'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['strength_helper'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
                <button type="submit" class="btn btn-success">Save</button>
                <a class="btn btn-info" href="../index.php?site_secret=<?php echo SITE_SECRET ?>">Cancel</a>
            </form>
        </div>
    </div>
</div>
