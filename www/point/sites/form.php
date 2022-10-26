<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>
                <?php if ($point['point_id']): ?>
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
                    <label>Latitude</label>
                    <input name="latitude" type="text" value="<?php echo $point['latitude'] ?>" class="form-control <?php echo $errors['latitude'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['latitude'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <input name="longitude" type="text" value="<?php echo $point['longitude'] ?>" class="form-control <?php echo $errors['longitude'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?php echo $errors['longitude'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Visibility</label>
                    <div class="form-control <?php echo $errors['visibility'] ? 'is-invalid' : '' ?>">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="visibility-visible" name="visibility" class="custom-control-input" value="1" <?php echo $point['visibility'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="visibility-visible">Visible</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="visibility-hidden" name="visibility" class="custom-control-input" value="0" <?php echo $point['visibility'] == 0 ? 'checked' : '' ?>>
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
                <input type="hidden" name="site_secret" value="<?php echo SITE_SECRET ?>">
                <button type="submit" class="btn btn-success">Save</button>
                <a class="btn btn-info" href="../index.php?site_secret=<?php echo SITE_SECRET ?>">Cancel</a>
            </form>
        </div>
    </div>
</div>
