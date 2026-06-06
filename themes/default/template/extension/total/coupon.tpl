<div class="panel card">
  <div class="card-header">
    <h4 class="card-title"><a href="#collapse-coupon" class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion"><?php echo $heading_title; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="collapse-coupon" class=" collapse">
    <div class="card-body">
      <label class="col-sm-2 form-label" for="input-coupon"><?php echo $entry_coupon; ?></label>
      <div class="input-group">
        <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="<?php echo $entry_coupon; ?>" id="input-coupon" class="form-control" />
        <span class="input-group-text">
          <input type="button" value="<?php echo $button_coupon; ?>" id="button-coupon" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
        </span></div>
      <script>
$('#button-coupon').on('click', function () {
              $.ajax({
                  url: 'index.php?route=extension/total/coupon/coupon',
                  type: 'post',
                  data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
                  dataType: 'json',
                  beforeSend: function () {
                      $('#button-coupon').button('loading');
                  },
                  complete: function () {
                      $('#button-coupon').button('reset');
                  },
                  success: function (json) {
                      $('.alert').remove();

                      if (json['error']) {
                          $('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                          $('html, body').animate({scrollTop: 0}, 'slow');
                      }

                      if (json['redirect']) {
                          location = json['redirect'];
                      }
                  }
              });
          });
</script>
    </div>
  </div>
</div>
