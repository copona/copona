<div class="accordion-item">
  <h2 class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-reward" aria-expanded="false">
      <?php echo $heading_title; ?>
    </button>
  </h2>
  <div id="collapse-reward" class="accordion-collapse collapse">
    <div class="accordion-body">
      <div class="input-group input-group-sm">
        <input type="text" name="reward" value="<?php echo $reward; ?>" placeholder="<?php echo $entry_reward; ?>" id="input-reward" class="form-control" />
        <button type="button" id="button-reward" class="btn btn-primary"><?php echo $button_reward; ?></button>
      </div>
    </div>
  </div>
</div>
<script>
$('#button-reward').on('click', function () {
    $.ajax({
        url: 'index.php?route=extension/total/reward/reward',
        type: 'post',
        data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
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
