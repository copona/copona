<div class="accordion-item">
  <h2 class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-coupon" aria-expanded="false">
      <?php echo $heading_title; ?>
    </button>
  </h2>
  <div id="collapse-coupon" class="accordion-collapse collapse">
    <div class="accordion-body">
      <div class="input-group input-group-sm">
        <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="<?php echo $entry_coupon; ?>" id="input-coupon" class="form-control" />
        <button type="button" id="button-coupon" class="btn btn-primary"><?php echo $button_coupon; ?></button>
      </div>
    </div>
  </div>
</div>
<script>
$('#button-coupon').on('click', function () {
    $.ajax({
        url: 'index.php?route=extension/total/coupon/coupon',
        type: 'post',
        data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
        dataType: 'json',
        success: function (json) {
            $('.alert').remove();
            if (json['error']) {
                $('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $('html, body').animate({scrollTop: 0}, 'slow');
            }
            if (json['redirect']) { location = json['redirect']; }
        }
    });
});
</script>
