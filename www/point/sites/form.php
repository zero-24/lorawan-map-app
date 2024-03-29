<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>
                <?php if ($pointDataHelper->getPointById($point['point_id'])): ?>
                    Edit Point <b><?php echo $point['title'] ?></b>
                <?php else : ?>
                    Create new Point
                <?php endif ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="">
                <div class="form-group">
                    <label>Point ID</label>
                    <?php if ($point['point_id']) : ?>
                        <input name="point_id" type="text" readonly value="<?php echo $point['point_id'] ?>" class="form-control <?php echo $errors['point_id'] ? 'is-invalid' : '' ?>">
                    <?php else : ?>
                        <input name="point_id" type="text" class="form-control <?php echo $errors['point_id'] ? 'is-invalid' : '' ?>">
                    <?php endif ?>
                    <div class="invalid-feedback">
                        <?php echo $errors['point_id'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" type="text" value="<?php echo $point['title'] ?>" class="form-control <?php echo $errors['title'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['title'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Longtext</label>
                    <input name="longtext" type="text" value="<?php echo $point['longtext'] ?>" class="form-control <?php echo $errors['longtext'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['longtext'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Callsign</label>
                    <input name="callsign" type="text" value="<?php echo $point['callsign'] ?>" class="form-control <?php echo $errors['callsign'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['callsign'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact/Leader</label>
                    <input name="pointleader" type="text" value="<?php echo $point['pointleader'] ?>" class="form-control <?php echo $errors['pointleader'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['pointleader'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Strength</label>
                    <div class="form-group row">
                        <div class="col">
                            <label for="strength_leader" class="small">Number of association leaders and doctors</label>
                            <input id="strength_leader" name="strength_leader" type="number" step="1" value="<?php echo $point['strength_leader'] ?>" class="form-control <?php echo $errors['strength_leader'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['strength_leader'] ?>
                            </div>
                        </div>
                        <div class="col">
                            <label for="strength_groupleader" class="small">Number of group and squad leaders</label>
                            <input id="strength_groupleader" name="strength_groupleader" type="number" step="1" value="<?php echo $point['strength_groupleader'] ?>" class="form-control <?php echo $errors['strength_groupleader'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['strength_groupleader'] ?>
                            </div>
                        </div>
                        <div class="col">
                            <label for="strength_helper" class="small">Number of helpers</label>
                            <input id="strength_helper" name="strength_helper" type="number" step="1" value="<?php echo $point['strength_helper'] ?>" class="form-control <?php echo $errors['strength_helper'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['strength_helper'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group row">
                        <div class="col">
                            <label>Latitude</label>
                            <input id="latitude" name="latitude" type="text" readonly value="<?php echo $point['latitude'] ?>" class="form-control <?php echo $errors['latitude'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['latitude'] ?>
                            </div>
                        </div>
                        <div class="col">
                            <label>Longitude</label>
                            <input id="longitude" name="longitude" type="text" readonly value="<?php echo $point['longitude'] ?>" class="form-control <?php echo $errors['longitude'] ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback">
                                <?php echo $errors['longitude'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Latitude / Longitude Picker</label>
                    <div id="location-map" class="map-picker"></div>
                    <input id="location" name="location" type="text" value="<?php echo $point['latitude'] . ',' . $point['longitude'] ?>" class="form-control hidden">
                </div>
                <div class="form-group">
                    <label>Visibility</label>
                    <div class="form-control <?php echo $errors['visibility'] ? 'is-invalid' : '' ?>">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="visibility-visible" name="visibility" class="custom-control-input" value="visible" <?php echo $point['visibility'] == 'visible' || $point['visibility'] == '' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="visibility-visible">Visible</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="visibility-hidden" name="visibility" class="custom-control-input" value="hidden" <?php echo $point['visibility'] == 'hidden' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="visibility-hidden">Hidden</label>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        <?php echo $errors['visibility'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Group</label>
                    <input name="group" type="text" value="<?php echo $point['group'] ?>" list="groupDataList" class="form-control <?php echo $errors['group'] ? 'is-invalid' : '' ?>" />
                    <datalist id="groupDataList">
                        <?php $pointGroups = $pointDataHelper->getPointGroups() ?>
                        <?php foreach ($pointGroups as $pointGroup) : ?>
                            <?php echo '<option value="' . $pointGroup . '"' . '>' . $pointGroup . '</option>' ?>
                        <?php endforeach ?>
                    </datalist>
                    <div class="invalid-feedback">
                        <?php echo $errors['group'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <?php $pointIcons = $pointDataHelper->getPointIcons() ?>
                    <div class="form-group row">
                        <div class="col">
                            <input name="icon" type="text" value="<?php echo $point['icon'] ?>" list="iconDataList" class="form-control <?php echo $errors['icon'] ? 'is-invalid' : '' ?>" />
                            <datalist id="iconDataList">
                                <?php foreach ($pointIcons as $pointIcon => $pointIconTitle) : ?>
                                    <?php echo '<option value="' . $pointIcon . '"' . '>' . $pointIconTitle . '</option>' ?>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                        <div class="col">
                            <input name="icon_title" type="text" readonly value="<?php echo isset($pointIcons[$point['icon']]) ? $pointIcons[$point['icon']] : '' ?>" class="form-control <?php echo $errors['icon'] ? 'is-invalid' : '' ?>" />
                        </div>
                        <div class="invalid-feedback">
                            <?php echo $errors['icon'] ?>
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
<script language="javascript" nonce="<?php echo $cspnonce; ?>">
    new OsmPicker({
        inputElement: document.getElementById('location'),
        mapElement: document.getElementById('location-map'),
        defaultView: {
        latLng: ['50.8070725023327', '7.133824179895859'],
        zoom: 18,
        }
    });
</script>
