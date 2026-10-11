<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($products) { ?>
        <?php
        $compare_specs = [
            [$text_model, 'model'],
            [$text_manufacturer, 'manufacturer'],
            [$text_availability, 'availability'],
            [$text_weight, 'weight'],
        ];
        $compare_cols = count($products) + 1;
        ?>
        <div class="cp-table-wrap cp-compare">
          <table class="table cp-table cp-compare__table">
            <thead>
              <tr>
                <th scope="row" class="cp-compare__label"><?php echo $text_product; ?></th>
                <?php foreach ($products as $product) { ?>
                  <td class="cp-compare__product">
                    <a href="<?php echo $product['remove']; ?>" class="cp-compare__remove" title="<?php echo $button_remove; ?>" aria-label="<?php echo $button_remove; ?>"><i class="fa fa-times"></i></a>
                    <a href="<?php echo $product['href']; ?>" class="cp-compare__media">
                      <?php if ($product['thumb']) { ?>
                        <img loading="lazy" decoding="async" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                      <?php } ?>
                    </a>
                    <a href="<?php echo $product['href']; ?>" class="cp-compare__name"><?php echo $product['name']; ?></a>
                    <?php if ($product['price']) { ?>
                      <div class="cp-price">
                        <?php if (!$product['special']) { ?>
                          <span class="cp-price__now"><?php echo $product['price']; ?></span>
                        <?php } else { ?>
                          <span class="cp-price__now is-special"><?php echo $product['special']; ?></span>
                          <s class="cp-price__old"><?php echo $product['price']; ?></s>
                        <?php } ?>
                      </div>
                    <?php } ?>
                    <button type="button" class="btn btn-primary btn-sm w-100" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><?php echo $button_cart; ?></button>
                  </td>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($compare_specs as $spec) { ?>
                <tr>
                  <th scope="row" class="cp-compare__label"><?php echo $spec[0]; ?></th>
                  <?php foreach ($products as $product) { ?>
                    <td><?php echo $product[$spec[1]]; ?></td>
                  <?php } ?>
                </tr>
              <?php } ?>
              <tr>
                <th scope="row" class="cp-compare__label"><?php echo $text_dimension; ?></th>
                <?php foreach ($products as $product) { ?>
                  <td><?php echo $product['length']; ?> × <?php echo $product['width']; ?> × <?php echo $product['height']; ?></td>
                <?php } ?>
              </tr>
              <?php if ($review_status) { ?>
                <tr>
                  <th scope="row" class="cp-compare__label"><?php echo $text_rating; ?></th>
                  <?php foreach ($products as $product) { ?>
                    <td>
                      <div class="cp-rating" aria-label="<?php echo $product['rating']; ?>/5">
                        <?php for ($i = 1; $i <= 5; $i++) { ?><i class="fa <?php echo $product['rating'] < $i ? 'fa-star-o' : 'fa-star'; ?>"></i><?php } ?>
                      </div>
                      <span class="cp-table__note"><?php echo $product['reviews']; ?></span>
                    </td>
                  <?php } ?>
                </tr>
              <?php } ?>
              <tr>
                <th scope="row" class="cp-compare__label"><?php echo $text_summary; ?></th>
                <?php foreach ($products as $product) { ?>
                  <td class="cp-compare__desc"><?php echo $product['description']; ?></td>
                <?php } ?>
              </tr>
            </tbody>
            <?php foreach ($attribute_groups as $attribute_group) { ?>
              <tbody>
                <tr class="cp-compare__group">
                  <th colspan="<?php echo $compare_cols; ?>"><?php echo $attribute_group['name']; ?></th>
                </tr>
                <?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
                  <tr>
                    <th scope="row" class="cp-compare__label"><?php echo $attribute['name']; ?></th>
                    <?php foreach ($products as $product) { ?>
                      <td><?php echo $product['attribute'][$key] ?? ''; ?></td>
                    <?php } ?>
                  </tr>
                <?php } ?>
              </tbody>
            <?php } ?>
          </table>
        </div>
      <?php } else { ?>
        <p class="cp-empty"><?php echo $text_empty; ?></p>
        <div class="cp-actions">
          <a href="<?php echo $continue; ?>" class="btn btn-outline-secondary"><?php echo $button_continue; ?></a>
        </div>
      <?php } ?>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
