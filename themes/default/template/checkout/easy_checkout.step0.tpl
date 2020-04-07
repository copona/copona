<script>
    $(window).bind("pageshow", function() {
        $('[name=checkout_type]').first().prop('checked', true);

        $('input[type=radio][name=customer_group_id]').trigger('change');

    });

</script>


  <div class="row">
    <div class="col-5">
        <div class="row">

              <div class="col-4">
                <div class="row">
                  <div class="col-12">
                    <label>
                      <input type="radio" name="checkout_type" value="1" checked="checked" />
                      Guest checkout
                    </label>
                  </div>
                </div>
              </div>

          <div class="col-4">
                <div class="row">
                  <div class="col-12">
                    <label>
                      <input type="radio" name="checkout_type" value="2" onclick="window.location = '/?route=account/login&amp;return=checkout';"/>
                      Login / register
                    </label>
                  </div>
                </div>
              </div>











      </div>

      <div class="login-form registerbox clearfix" style="display:none">
        <div class="row">
          <div class="col-md-12 message"></div>
          <form class="form-inline" role="form">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                <input type="text" name="email" value="" placeholder="<?php echo str_replace(':', '', $entry_email); ?>" id="input-email" class="form-control"/>
              </div>&nbsp;&nbsp;
              <div class="form-group">
                <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                <input type="password" name="password" value="" placeholder="<?php echo str_replace(':', '', $entry_password); ?>" id="input-password" class="form-control"/>
              </div>
            </div>
            <div class="form-group col-md-4">
              <input type="button" value="<?php echo $button_login; ?>" id="button-login" data-loading-text="<?php if (isset($text_loading)) {
                  echo $text_loading;
              } else echo 'loading ...' ?>" class="btn btn-login"/>
              <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



