<div class="accordion-item">
  <h2 class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-voucher" aria-expanded="false">
      <?php echo $heading_title; ?>
    </button>
  </h2>
  <div id="collapse-voucher" class="accordion-collapse collapse">
    <div class="accordion-body">
      <div class="input-group input-group-sm">
        <input type="text" name="voucher" value="<?php echo $voucher; ?>" placeholder="<?php echo $entry_voucher; ?>" id="input-voucher" class="form-control" />
        <button type="button" id="button-voucher" class="btn btn-primary"><?php echo $button_voucher; ?></button>
      </div>
    </div>
  </div>
</div>
<script>
$('#button-voucher').on('click', function () {
    $.ajax({
        url: 'index.php?route=extension/total/voucher/voucher',
        type: 'post',
        data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
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
