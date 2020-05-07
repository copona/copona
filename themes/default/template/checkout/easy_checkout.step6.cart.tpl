<div class="your_order">


    <div id="cart_table">
        <?php echo $cart_table ?>
    </div>

    <div class="comment">
        <p><strong><?php echo $text_comments; ?></strong></p>
        <p><textarea name="comment" rows="2" class="form-control"><?php echo $comment; ?></textarea></p>
    </div>


    <?php if ($text_agree) { ?>
      <div class="buttons clearfix" id="terms-id">
        <div class="">
          <label>
            <?php if ($agree) { ?>
              <input type="checkbox" name="agree" value="1" checked="checked"/>
            <?php } else { ?>
              <input type="checkbox" name="agree" value="1"/>
            <?php } ?>

              <?php echo $text_agree; ?>

          </label>
        </div>
      </div>
    <?php } ?>


  <div class="payment clearfix">
      <?php if ($payment) { ?>
        <p><?php $payment; ?></p>
      <?php } ?>
  </div>





  <div class="confirm_button">

    <button class="btn btn-primary" id="button-checkout-loading" type="button" disabled style="display: none" >
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      <span class="sr-only"></span> Loading...
    </button>


    <button type="button" class="btn btn-success" id="button-checkout" style="display: <?=( $products ? 'inline' : 'none' )?>">
        <?php echo $text_checkout_confirm; ?>
    </button>

    <button type="button" class="btn btn-continue" id="button-continue" style="display: <?=( $products ? 'none' : 'inline' )?>">
        <?php echo $button_shopping; ?>
    </button>




  </div>




</div>
