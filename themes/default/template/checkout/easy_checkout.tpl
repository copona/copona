<?php echo $header; ?>

<?php

// pr($address);
//TODO: GLOBAL SUCKS!
// MUST USEE SOMETHING BETTER!
global $current_address;
$current_address = $address;




?>



<div class="container">
  <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
  </ul>
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>


  <div id="success-messages"></div>

  <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) {
          $class = 'col-sm-6';
      } elseif ($column_left || $column_right) {
          $class = 'col-sm-9';
      } else {
          $class = 'col-sm-12';
      } ?>

      <?php $step_num = 1; ?>

    <div id="content" class="<?php echo $class; ?>">


      <?php /*><h2><?php echo $heading_title; ?></h2><*/ ?>


      <div class="row box checkout_form box-container">
        <div class="col-md-12 register_block">
          <div class="row">
            <div class="col-md-12">
              <div class="card-columns card-columns-1">


                <div class="card">
                  <div class="card-body">
                    <div class="row">
                        <?php /* Step 1: Account & Billing Details */ ?>

                      <div class="col-md-12 heading-row">
                        <div>
                          <div class="step-label">Step <?= $step_num++ ?></div>
                          <h3>Checkout type</h3>
                        </div>
                      </div>
                      <div class="col-md-12">
                          <?php require __DIR__ . "/easy_checkout.step0.tpl"; ?>
                      </div>
                    </div>
                  </div>
                </div>





                <div class="card">
                  <div class="card-body">
                    <div class="row">
                        <?php /* Step 1: Account & Billing Details */ ?>
                      <div class="col-md-12 heading-row">
                        <div>
                          <div class="step-label">Step <?= $step_num++ ?></div>
                          <h3><?php echo $text_checkout_account; ?></h3></div>
                      </div>
                      <div class="col-md-12">
                          <?php require __DIR__ . "/easy_checkout.step1.details.tpl"; ?>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12 col-md-4">

                    <div class="card">
                      <div class="card-body">
                        <div class="row">

                          <div class="col-12">
                            <div class="row">
                                <?php /* Step 5: Promo code */ ?>
                              <div class="col-md-12 heading-row">

                                <div>
                                  <div class="step-label">Step <?= $step_num++ ?></div>
                                  <h3><?php echo __C('text_checkout_coupon'); ?></h3>
                                </div>
                              </div>
                              <div class="col-md-12">
                                  <?php require __DIR__ . "/easy_checkout.coupon.tpl"; ?>
                              </div>
                            </div>
                          </div>


                          <div class="col-12">
                            <div class="row">
                                <?php /* Step 2: Billing Details */ ?>
                              <div class="col-md-12 heading-row">
                                <div>
                                  <div class="step-label">Step <?= $step_num++ ?></div>
                                  <h3><?php echo $text_checkout_shipping_method; ?></h3></div>
                              </div>
                              <div class="col-md-12" id="shipping_method_block">
                                  <?php // require __DIR__ . "/easy_checkout.step4.tpl"; ?>
                                 <?php require __DIR__ . "/easy_shipping_method.tpl"; ?>
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="row">
                                <?php /* Step 5: Payment Method */ ?>
                              <div class="col-md-12 heading-row">
                                <div>
                                  <div class="step-label">Step <?= $step_num++ ?></div>
                                  <h3><?php echo $text_checkout_payment_method; ?></h3></div>
                              </div>
                              <div class="col-md-12">
                                  <?php require __DIR__ . "/easy_checkout.step5.tpl"; ?>
                              </div>
                            </div>
                          </div>




                        </div>
                      </div>

                    </div>


                  </div>

                  <div class="col-12 col-md-8">
                    <div class="card">
                      <div class="card-body">

                        <div class="row">
                            <?php /* Cart */ ?>
                          <div class="col-12 heading-row">
                            <div>
                              <div class="step-label">Step <?= $step_num++ ?></div>
                              <h3><?php echo $text_cart; ?></h3></div>
                          </div>
                          <div class="col-12">
                              <?php require __DIR__ . "/easy_checkout.step6.cart.tpl"; ?>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>


              </div><!-- -->
            </div>
          </div>
        </div>









      </div>
        <?php echo $content_bottom; ?>
    </div>
      <?php echo $column_right; ?></div>
</div>


<script type="text/javascript">
    <?php include __DIR__ . "/easy_checkout_script.js" ?>
</script>

<?php echo $footer; ?>
