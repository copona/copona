<div class="list-group">
    <?php foreach ($categories as $category) { ?>
      <div class="category-item">
          <?php if ($category['children']) { ?>
            <a href="<?php echo $category['href'] ?>" class="list-group-item"
              aria-expanded="true"><?php echo $category['name'] ?> <i class="fa fa-caret-right" aria-hidden="true"></i></a>
            <div class="dropdown-menu mega-dropdown-menu">
                <?php $column_count = 4; ?>
                <?php $column_childs = 4; ?>
                <?php for ($i = 0; $i < count($category['children']); $i = $i + $column_count) { ?>
                  <div class="row">
                      <?php foreach (array_slice($category['children'], $i, $column_count) as $child_2) { ?>
                        <div class="col-sm-<?= floor(12 / $column_count) ?>">
                          <div class="dropdown-header"><a href="<?= $child_2['href'] ?>"><?= $child_2['name'] ?></a>
                          </div>
                            <?php if ($child_2['children']) { ?>
                              <ul class="child_3">
                                  <?php
                                  $i2 = 0;
                                  foreach ($child_2['children'] as $child_3) { ?>
                                    <li>
                                      <a href="<?= $child_3['href'] ?>"><?php echo $child_3['name']
                                            . ($child_3['total'] ? " (" . $child_3['total'] . ")" : '') ?></a>
                                    </li>
                                      <?php if (count($child_2['children']) > $i2 && $i2++ > $column_childs) { ?>
                                      <li><a href="<?= $child_2['href'] ?>"><strong class="all-categories">
                                            <small><?php echo $text_all_categories ?></small>
                                          </strong></a></li>
                                          <?php break;
                                      }
                                  } ?>
                              </ul>
                            <?php } ?>
                        </div>
                      <?php } ?>
                  </div>
                <?php } ?>
            </div>
          <?php } else { ?>
            <a href="<?= $category['href'] ?>" class="list-group-item"><?php echo $category['name'] ?></a>
          <?php } ?>
      </div>
    <?php } ?>
</div>
