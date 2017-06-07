<div class="list-group">
    <?php foreach ($categories as $category) { ?>
        <div class="category-item">
            <?php if ($category['children']) { ?>
                <a href="#" class="dropdown-toggle list-group-item" data-toggle="dropdown"
                   aria-expanded="true"><?php echo $category['name'] ?></a>
                <div class="dropdown-menu mega-dropdown-menu">
                    <?php foreach (array_chunk($category['children'],
                        ceil(count($category['children']) / 4)) as $child_2_row) { ?>
                        <div class="row">
                            <?php foreach ($child_2_row as $child_2) { ?>
                                <div class="col-sm-3">
                                    <div class="dropdown-header"><?= $child_2['name'] ?></div>
                                    <?php if ($child_2['children']) { ?>
                                        <ul class="child_3">
                                            <?php
                                            $i2 = 0;
                                            foreach ($child_2['children'] as $child_3) { ?>
                                                <li><a href="<?= $child_3['href'] ?>"><?php echo $child_3['name'] ?></a>
                                                </li>
                                                <?php if (count($child_2['children']) > $i2 && $i2++ > 4) { ?>
                                                    <li><a href="<?= $child_2['href'] ?>"><strong> visas kategorijas </strong></a></li>
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
