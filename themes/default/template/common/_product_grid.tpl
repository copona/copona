<h3><?php echo $heading_title; ?></h3>
<div class="row featured-module">
<?php if(isset($products)){ ?>
    <?php foreach ($products as $product) { 
        if ($product['product_id'] != '') { ?>
            <div id="tyre<?=$product['product_id']?>" class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product-short-info-block">
                <div class="image">
                    <a href="<?= $product['href']; ?>">
                        <img src="<?= $product['thumb']; ?>" alt="<?= $product['name']; ?>">
                    </a>
                </div>

                <div style="border: 2px solid #989898; border-top: none;">
                    <div class="info">
                        <div class="name">
                            <a href="<?= $product['href']; ?>"><?= $product['name'] ?></a>

                            <div class="details"><span><?=$product['content_meta']['width'] ?>/<?=$product['content_meta']['height'] ?>R<?=$product['content_meta']['radial'] ?>
                                    <?=$product['content_meta']['loadindex'] ?><?=$product['content_meta']['speedindex'] ?>

                    </span><span class="pull-right"><?= $text_stock . ': ' . ($product['content_meta']['quant'] > 4 ? '>4' : $product['content_meta']['quant']) ?></span></div>
                        </div>

                        <div class="price">
                            <span class="price-new"> <?php echo $product['special']; ?></span>
                            <span class="price-old"> <?php echo $product['price']; ?></span>
                        </div>
                    </div>
                    <div class="buttons">
                        <?php if($product['content_meta']['quant']) { ?>
                            <button type="button" class="add-to-cart small"
                                    onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['quantity'] < 4 ? $product['quantity'] : 4 ?>');">
                                <i class="fa fa-shopping-cart"></i>
                            </button>
                        <?php } ?>
                        <a class="btn btn-primary" href="<?= $product['href']; ?>"><span><i
                                    class="fa fa-chevron-right"></i><?= $text_know_more ?></span></a>
                        <button type="button" class="btn btn-primary compare " data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');">
                            <i class="fa fa-exchange"></i></button>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php } else { ?>
  <div class="col-sm-12">
    <h4><?= $text_empty ?></h4>
  </div>
<?php } ?>
	
</div>
