<?php if (count($languages) > 1) { ?>
    <div class="pull-left language-select">
      <div class="btn-group">
        <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
            <?php foreach ($languages as $language) { ?>
                <?php if ($language['code'] == $code) { ?>
                  <img src="catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
                  <span class="hidden-xs hidden-sm hidden-md"><?= $language['name']; ?></span> <i class="fa fa-caret-down"></i></button>
            <?php } ?>
        <?php } ?>
        <ul class="dropdown-menu">
          <?php foreach ($languages as $language) { ?>
              <li>
                <a href="<?= $language['code'] ?>">
                  <img src="catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png"
                       alt="<?php echo $language['name']; ?>"
                       title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
                </a>
              </li>
          <?php } ?>
        </ul>
      </div>
    </div>
<?php } ?>
