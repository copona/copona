<div class="panel card">
  <div class="card-header">
    <h4 class="card-title"><a href="#collapse-reward" class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion"><?php echo $heading_title; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="collapse-reward" class=" collapse">
    <div class="card-body">
      <label class="col-sm-2 form-label" for="input-reward"><?php echo $entry_reward; ?></label>
      <div class="input-group">
        <input type="text" name="reward" value="<?php echo $reward; ?>" placeholder="<?php echo $entry_reward; ?>" id="input-reward" class="form-control" />
        <span class="input-group-text">
          <input type="submit" value="<?php echo $button_reward; ?>" id="button-reward" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
        </span></div>
      <script type="text/javascript"><!--
$('#button-reward').on('click', function () {
              $.ajax({
                  url: 'index.php?route=extension/total/reward/reward',
                  type: 'post',
                  data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
                  dataType: 'json',
                  beforeSend: function () {
                      $('#button-reward').button('loading');
                  },
                  complete: function () {
                      $('#button-reward').button('reset');
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
//--></script>
    </div>
  </div>
</div>