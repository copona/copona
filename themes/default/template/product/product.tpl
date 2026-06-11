<?php echo $header; ?>
<style>
#product-gallery { margin-bottom: 20px; }
.gallery-main {
  background: #f5f5f7;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 10px;
  aspect-ratio: 4/3;
  display: flex;
  align-items: center;
  justify-content: center;
}
.gallery-main a { display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; }
.gallery-main img { width: 100%; height: 100%; object-fit: contain; }
.gallery-thumbs {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  padding-bottom: 4px;
  scrollbar-width: thin;
}
.gallery-thumb {
  position: relative;
  flex: 0 0 72px;
  height: 72px;
  border-radius: 6px;
  overflow: hidden;
  cursor: pointer;
  border: 2px solid transparent;
  transition: border-color .15s;
}
.gallery-thumb.active, .gallery-thumb:hover { border-color: #0071e3; }
.gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
.gallery-play {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  background: rgba(0,0,0,.35);
  color: #fff;
  font-size: 20px;
  pointer-events: none;
}
</style>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
          <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
          <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
          <?php $class = 'col-sm-12'; ?>
      <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row" id="printable">
          <?php if ($column_left || $column_right) { ?>
              <?php $class = 'col-sm-6'; ?>
          <?php } else { ?>
              <?php $class = 'col-sm-8'; ?>
          <?php } ?>

        <div class="<?php echo $class; ?>">
            <?php
            $gallery_items = array();
            if ($thumb) {
                $gallery_items[] = array('popup' => $popup, 'mid' => $image_mid, 'thumb' => $thumb, 'title' => $heading_title, 'type' => 'image');
            }
            if ($images) {
                foreach ($images as $gimg) {
                    $gallery_items[] = array('popup' => $gimg['popup'], 'mid' => $gimg['image_mid'], 'thumb' => $gimg['thumb'], 'title' => $gimg['description'], 'type' => 'image');
                }
            }
            if (!empty($product_videos)) {
                foreach ($product_videos as $video) {
                    $gallery_items[] = array('popup' => $video['video'], 'mid' => $video['video_src'], 'thumb' => $video['video_src'], 'title' => $heading_title, 'type' => 'video');
                }
            }
            ?>
            <?php if ($gallery_items) { ?>
            <div id="product-gallery">
              <div class="gallery-main">
                <a href="<?php echo htmlspecialchars($gallery_items[0]['popup']); ?>" class="<?php echo $gallery_items[0]['type'] === 'video' ? 'gallery-video' : 'gallery-image'; ?>">
                  <img id="gallery-main-img" src="<?php echo $gallery_items[0]['mid']; ?>" alt="<?php echo htmlspecialchars($gallery_items[0]['title']); ?>">
                </a>
              </div>
              <?php if (count($gallery_items) > 1) { ?>
              <div class="gallery-thumbs">
                <?php foreach ($gallery_items as $gi => $gitem) { ?>
                <div class="gallery-thumb<?php echo $gi === 0 ? ' active' : ''; ?>"
                     data-popup="<?php echo htmlspecialchars($gitem['popup']); ?>"
                     data-mid="<?php echo htmlspecialchars($gitem['mid']); ?>"
                     data-title="<?php echo htmlspecialchars($gitem['title']); ?>"
                     data-type="<?php echo $gitem['type']; ?>">
                  <img src="<?php echo $gitem['thumb']; ?>" alt="<?php echo htmlspecialchars($gitem['title']); ?>">
                  <?php if ($gitem['type'] === 'video') { ?><span class="gallery-play">&#9654;</span><?php } ?>
                </div>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
            <?php } ?>

          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link active" href="#tab-description" data-bs-toggle="tab" role="tab"><?php echo $tab_description; ?></a></li>
            <?php if ($attribute_groups) { ?>
                <li class="nav-item" role="presentation"><a class="nav-link" href="#tab-specification" data-bs-toggle="tab" role="tab"><?php echo $tab_attribute; ?></a></li>
            <?php } ?>
            <?php if ($review_status) { ?>
                <li class="nav-item" role="presentation"><a class="nav-link" href="#tab-review" data-bs-toggle="tab" role="tab"><?php echo $tab_review; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane show active" id="tab-description"><?php echo $description; ?></div>
            <?php if ($attribute_groups) { ?>
                <div class="tab-pane" id="tab-specification">
                  <table class="table table-bordered">
                      <?php foreach ($attribute_groups as $attribute_group) { ?>
                        <thead>
                          <tr>
                            <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                          </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                              <tr>
                                <td><?php echo $attribute['name']; ?></td>
                                <td><?php echo $attribute['text']; ?></td>
                              </tr>
                          <?php } ?>
                        </tbody>
                    <?php } ?>
                  </table>
                </div>
            <?php } ?>
            <?php if ($review_status) { ?>
                <div class="tab-pane" id="tab-review">
                  <form class="row" id="form-review">
                    <div id="review"></div>
                    <h2><?php echo $text_write; ?></h2>
                    <?php if ($review_guest) { ?>
                        <div class="form-group required">
                          <div class="col-sm-12">
                            <label class="form-label" for="input-name"><?php echo $entry_name; ?></label>
                            <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group required">
                          <div class="col-sm-12">
                            <label class="form-label" for="input-review"><?php echo $entry_review; ?></label>
                            <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                            <div class="form-text text-muted"><?php echo $text_note; ?></div>
                          </div>
                        </div>
                        <div class="form-group required">
                          <div class="col-sm-12">
                            <label class="form-label"><?php echo $entry_rating; ?></label>
                            &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                            <input type="radio" name="rating" value="1" />
                            &nbsp;
                            <input type="radio" name="rating" value="2" />
                            &nbsp;
                            <input type="radio" name="rating" value="3" />
                            &nbsp;
                            <input type="radio" name="rating" value="4" />
                            &nbsp;
                            <input type="radio" name="rating" value="5" />
                            &nbsp;<?php echo $entry_good; ?></div>
                        </div>
                        <?php echo $captcha; ?>
                        <div class="buttons clearfix">
                          <div class="float-end">
                            <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                          </div>
                        </div>
                    <?php } else { ?>
                        <?php echo $text_login; ?>
                    <?php } ?>
                  </form>
                </div>
            <?php } ?>
          </div>
        </div>
        <?php if ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <div class="btn-group">
            <button type="button" data-bs-toggle="tooltip" class="btn btn-secondary" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button>
            <button type="button" data-bs-toggle="tooltip" class="btn btn-secondary" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
            <button type="button" data-bs-toggle="tooltip" class="btn btn-secondary print" title="<?php echo $button_print; ?>"><i class="fa fa-print" aria-hidden="true"></i></button>
          </div>
          <h1><?php echo $heading_title; ?></h1>
          <ul class="list-unstyled">
              <?php if ($manufacturer) { ?>
                <li><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <li><?php echo $text_model; ?> <?php echo $model; ?></li>
            <?php if ($reward) { ?>
                <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
            <?php } ?>
            <li><?php echo $text_stock; ?> <?php echo $stock; ?></li>
          </ul>
          <?php if ($group_products) { ?>
              <ul class="list-unstyled product-group">
                  <?php foreach ($group_products as $group_product) { ?>
                    <li <?= ($group_product['product_id'] == $product_id ? 'class="active"' : '') ?>>
                      <a href="<?php echo $group_product['href'] ?>"><img src="<?php echo $group_product['image'] ?>" alt="<?php echo $group_product['name'] ?>" title="<?php echo $group_product['name'] ?>"></a>
                    </li>
                <?php } ?>
              </ul>
          <?php } ?>


          <?php if ($price) { ?>
              <ul class="list-unstyled">
                  <?php if (!$special) { ?>
                    <li>
                      <h2><?php echo $price; ?></h2>
                    </li>
                <?php } else { ?>
                    <li><span class="price-old"><?php echo $price; ?></span></li>
                    <li>
                      <h2><?php echo $special; ?></h2>
                    </li>
                <?php } ?>
                <?php if ($tax) { ?>
                    <li><?php echo $text_tax; ?> <?php echo $tax; ?></li>
                <?php } ?>
                <?php if ($points) { ?>
                    <li><?php echo $text_points; ?> <?php echo $points; ?></li>
                <?php } ?>
                <?php if ($discounts) { ?>
                    <li>
                      <hr>
                    </li>
                    <?php foreach ($discounts as $discount) { ?>
                        <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
                    <?php } ?>
                <?php } ?>
              </ul>
          <?php } ?>
          <div id="product">

            <?php if ($options) { ?>
                <hr>
                <h3><?php echo $text_option; ?></h3>
                <?php foreach ($options as $option) { ?>
                    <?php if ($option['type'] == 'select') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                    <?php if ($option_value['price']) { ?>
                                      (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                  <?php } ?>
                                </option>
                            <?php } ?>
                          </select>
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'radio') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label"><?php echo $option['name']; ?></label>
                          <div id="input-option<?php echo $option['product_option_id']; ?>">
                              <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                    <?php if ($option_value['image']) { ?>
                                        <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
                                    <?php } ?>
                                    <?php echo $option_value['name']; ?>
                                    <?php if ($option_value['price']) { ?>
                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                    <?php } ?>
                                  </label>
                                </div>
                            <?php } ?>
                          </div>
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'checkbox') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label"><?php echo $option['name']; ?></label>
                          <div id="input-option<?php echo $option['product_option_id']; ?>">
                              <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                    <?php if ($option_value['image']) { ?>
                                        <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
                                    <?php } ?>
                                    <?php echo $option_value['name']; ?>
                                    <?php if ($option_value['price']) { ?>
                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                    <?php } ?>
                                  </label>
                                </div>
                            <?php } ?>
                          </div>
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'text') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'textarea') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'file') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label"><?php echo $option['name']; ?></label>
                          <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-secondary d-block w-100"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                          <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'date') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <div class="input-group date">
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['product_option_value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                            <span class="input-group-text">
                              <button class="btn btn-secondary" type="button"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'datetime') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <div class="input-group datetime">
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                            <span class="input-group-text">
                              <button type="button" class="btn btn-secondary"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'time') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="form-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <div class="input-group time">
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                            <span class="input-group-text">
                              <button type="button" class="btn btn-secondary"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if ($recurrings) { ?>
                <hr>
                <h3><?php echo $text_payment_recurring; ?></h3>
                <div class="form-group required">
                  <select name="recurring_id" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($recurrings as $recurring) { ?>
                        <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                    <?php } ?>
                  </select>
                  <div class="form-text text-muted" id="recurring-description"></div>
                </div>
            <?php } ?>
            <div class="form-group">
              <label class="form-label" for="input-quantity"><?php echo $entry_qty; ?></label>
              <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <br />
              <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg d-block w-100"><?php echo $button_cart; ?></button>
            </div>
            <div class="row">
              <div class="col-md-12 social-icons">
                <ul>
                  <li class="facebook">
                    <a href="#" title="Facebook share">
                      <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60"><path fill="#000000" d="M31.8,40.3h-3.7v-10h-2.5v-3.4l2.5,0l0-2c0-2.8,0.8-4.5,4.1-4.5h2.8v3.4h-1.7c-1.3,0-1.4,0.5-1.4,1.4l0,1.7h3.1l-0.4,3.4l-2.7,0L31.8,40.3L31.8,40.3z"></path></svg>
                    </a>
                  </li>
                  <li class="twitter">
                    <a href="#" title="Share on X">
                      <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24"><path fill="#000000" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <?php if ($minimum > 1) { ?>
                <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
            <?php } ?>
          </div>
          <?php if ($review_status) { ?>
              <div class="rating">
                <p>
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if ($rating < $i) { ?>
                          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                      <?php } else { ?>
                          <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                      <?php } ?>
                  <?php } ?>
                  <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
                <hr>
              </div>
          <?php } ?>
        </div>
      </div>
      <?php if ($products) { ?>
          <h3><?php echo $text_related; ?></h3>
          <div class="row">
              <?php
              if ($column_left && $column_right) {
                  $product_col_class = 'col-8 col-sm-6';
              } elseif ($column_left || $column_right) {
                  $product_col_class = 'col-6 col-md-4';
              } else {
                  $product_col_class = 'col-6 col-sm-3';
              }
              ?>
              <?php foreach ($products as $product) { ?>
                <?php include(__DIR__ . '/../common/_product_card.tpl'); ?>
              <?php } ?>
          </div>
      <?php } ?>
      <?php if ($tags) { ?>
          <p><?php echo $text_tags; ?>
              <?php for ($i = 0; $i < count($tags); $i++) { ?>
                  <?php if ($i < (count($tags) - 1)) { ?>
                    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
                <?php } else { ?>
                    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
                <?php } ?>
            <?php } ?>
          </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
            dataType: 'json',
            beforeSend: function () {
                $('#recurring-description').html('');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['success']) {
                    $('#recurring-description').html(json['success']);
                }
            }
        });
    });
</script>
<script>
    $('#button-cart').on('click', function () {
        $("alert-success").remove();
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-cart').button('loading');
            },
            complete: function () {
                $('#button-cart').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('is-invalid');

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('is-invalid');
                }

                if (json['success']) {
                    $('.breadcrumb')
                            .after($('<div class="alert alert-success">' + json['success'] +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>')
                                    .hide()
                                    .fadeIn(200));
                    delay(function () {
                        $('.alert-success').fadeOut(500);
                    }, 3000);
                    $('#cart').load('index.php?route=common/cart/info');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

</script>
<script>
    $('.date').datetimepicker({
        pickTime: false
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });

    $('.time').datetimepicker({
        pickDate: false
    });

    $('button[id^=\'button-upload\']').on('click', function () {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $(node).button('loading');
                    },
                    complete: function () {
                        $(node).button('reset');
                    },
                    success: function (json) {
                        $('.text-danger').remove();

                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $(node).parent().find('input').val(json['code']);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
</script>
<script>
    $('#review').delegate('.pagination a', 'click', function (e) {
        e.preventDefault();

        $('#review').fadeOut('slow');

        $('#review').load(this.href);

        $('#review').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

    $('#button-review').on('click', function () {
        $.ajax({
            url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
            type: 'post',
            dataType: 'json',
            data: $("#form-review").serialize(),
            beforeSend: function () {
                $('#button-review').button('loading');
            },
            complete: function () {
                $('#button-review').button('reset');
            },
            success: function (json) {
                $('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                }
            }
        });
    });

    $(document).ready(function () {
        // Gallery thumbnail click
        $('.gallery-thumb').on('click', function () {
            var popup = $(this).data('popup');
            var mid   = $(this).data('mid');
            var title = $(this).data('title');
            var type  = $(this).data('type');
            $('.gallery-thumb').removeClass('active');
            $(this).addClass('active');
            $('#gallery-main-img').attr('src', mid).attr('alt', title);
            var $a = $('.gallery-main a');
            $a.attr('href', popup);
            $a.removeClass('gallery-image gallery-video').addClass(type === 'video' ? 'gallery-video' : 'gallery-image');
        });

        // Build items array from thumbnails for Magnific gallery
        var mfpItems = [];
        $('.gallery-thumb').each(function () {
            mfpItems.push({
                src:  $(this).data('popup'),
                type: $(this).data('type') === 'video' ? 'iframe' : 'image'
            });
        });

        $('.gallery-main a').on('click', function (e) {
            e.preventDefault();
            $.magnificPopup.open({
                items:   mfpItems,
                gallery: { enabled: true },
                type:    'image'
            }, $('.gallery-thumb.active').index());
        });
    });
</script>
<script>
    $('.facebook').on('click', function(){
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL), 'facebook-popup', 'height=400,width=600');
    })
    $('.twitter').on('click', function(){
        window.open('https://x.com/intent/post?url=http://<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?= urlencode($heading_title) ?>', 'x-popup', 'height=400,width=600');
    })
</script>
<?php echo $footer; ?>
