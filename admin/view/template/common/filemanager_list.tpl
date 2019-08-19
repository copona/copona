<?php foreach (array_chunk($images, 6) as $image) { ?>
  <div class="row">
      <?php foreach ($image as $image) {
          if ($image['type'] != 'directory') {
              break 2;
          }
          ?>
        <div class="col-sm-2 col-xs-4 text-center">
          <div class="text-center"><a href="<?php echo $image['href']; ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-3x"></i></a></div>
          <label>
            <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>"/>
              <?php echo $image['name']; ?>
          </label>
        </div>
      <?php } ?>
  </div>

<?php } ?>


<div class="col-xs-12">
    <?php foreach (array_chunk($images, 6) as $image) { ?>
      <div class="row">
          <?php foreach ($image as $image) { ?>

              <?php if ($image['type'] == 'directory') {
                  continue;
              }
              ?>
            <div class="col-sm-2 col-xs-4 text-center">
                <?php if ($image['type'] == 'image') { ?>
                  <div class="thumbnail">
                    <a style="display:block;" href="<?php echo $image['href']; ?>" class="thumbnail"><img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>"/>
                    </a>
                    <label>
                      <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>"/>
                        <?php echo $image['name']; ?></label>
                  </div>
                <?php } ?>
            </div>
          <?php } ?>
      </div>
    <?php } ?>
</div>
