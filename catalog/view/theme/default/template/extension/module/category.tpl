<?php

function getChildren($children = array(), $category_path) {
    $html = '';

    if ($children) {
        foreach ($children as $child) {
            $html .= '<a href="' . $child['href'] . '" class="list-group-item ' . (in_array($child['category_id'], $category_path) ? 'active' : '') . '">&nbsp;&nbsp;&nbsp;- ' . $child['name'] . '</a>';
            $html .= getChildren($child['children'], $category_path);
        }
        return $html;
    } else {
        return false;
    }
}
?>

<div class="list-group">
    <?php foreach ($categories as $category) { ?>
        <?php if (in_array($category['category_id'], $category_path)) { ?>
          <a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>
          <?php echo getChildren($category['children'], $category_path); ?>
      <?php } else { ?>
          <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
      <?php } ?>
  <?php } ?>
</div>
