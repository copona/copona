<?php if (count($languages) > 1) { ?>
    <div class="language-select">
      <div class="btn-group">
        <button class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown">
            <?php foreach ($languages as $language) { ?>
                <?php if ($language['code'] == $code) { ?>
                  <img src="catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
                  <span class="d-none d-lg-inline"><?= $language['name']; ?></span></button>
            <?php } ?>
        <?php } ?>
        <ul class="dropdown-menu">
          <?php foreach ($languages as $language) { ?>
              <li>
                <a href="<?= $language['href'] ?>">
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
