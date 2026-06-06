<div class="panel card">
  <div class="card-header">
    <h4 class="card-title"><a href="#collapse-voucher" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $heading_title; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="collapse-voucher" class=" collapse">
    <div class="card-body">
      <label class="col-sm-2 form-label" for="input-voucher"><?php echo $entry_voucher; ?></label>
      <div class="input-group">
        <input type="text" name="voucher" value="<?php echo $voucher; ?>" placeholder="<?php echo $entry_voucher; ?>" id="input-voucher" class="form-control" />
        <span class="input-group-text">
          <input type="submit" value="<?php echo $button_voucher; ?>" id="button-voucher" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
        </span> </div>
      <script>
$('#button-voucher').on('click', function () {
              $.ajax({
                  url: 'index.php?route=extension/total/voucher/voucher',
                  type: 'post',
                  data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
                  dataType: 'json',
                  beforeSend: function () {
                      $('#button-voucher').button('loading');
                  },
                  complete: function () {
                      $('#button-voucher').button('reset');
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
