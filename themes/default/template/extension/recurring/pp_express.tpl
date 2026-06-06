<div class="buttons clearfix">
    <?php if ($order_recurring_id) { ?>
      <div class="float-start">
        <button type="button" id="button-cancel" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><?php echo $button_cancel; ?></button>
      </div>
  <?php } ?>
  <div class="float-end"><a href="<?php echo $continue; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
</div>
<script type="text/javascript"><!--
$(document).delegate('#button-cancel', 'click', function () {
        $.ajax({
            url: 'index.php?route=extension/recurring/pp_express/cancel',
            dataType: 'json',
            beforeSend: function () {
                $('#button-cancel').button('loading');
            },
            complete: function () {
                $('#button-cancel').button('reset');
            },
            success: function (json) {
                $('.alert').remove();

                if (json['success']) {
                    $('#content').prepend('<div class="alert alert-danger">' + json['success'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }

                if (json['error']) {
                    $('#content').prepend('<div class="alert alert-danger">' + json['error'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
//--></script>