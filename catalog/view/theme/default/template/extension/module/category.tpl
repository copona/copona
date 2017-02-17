<?php

function getChildren($children = array()) {
    $html = '';

    if ($children) {
        foreach ($children as $child) {
            $html .= '<a href="' . $child['href'] . '" class="list-group-item active">&nbsp;&nbsp;&nbsp;- ' . $child['name'] . '</a>';
            $html .= getChildren($child['children']);
        }
        return $html;
    } else {
        return false;
    }
}
?>


<div class="list-group">
    <?php foreach ($categories as $category) { ?>
        <?php if ($category['category_id'] == $category_id) { ?>
          <a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>

          <?php echo getChildren($category['children']); ?>
      <?php } else { ?>
          <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
      <?php } ?>
  <?php } ?>
</div>
