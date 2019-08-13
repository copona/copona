<style>
  #filemanager label {
    word-break: break-all;
  }

  .thumbnail img {
    max-width: 100%;
  }
</style>
<div id="filemanager" class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo $heading_title; ?></h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-5"><a href="<?php echo $parent; ?>" data-toggle="tooltip" title="<?php echo $button_parent; ?>" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a> <a
            href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_upload; ?>" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i></button>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_folder; ?>" id="button-folder" class="btn btn-default"><i class="fa fa-folder"></i></button>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
        </div>
        <div class="col-sm-7">
          <div class="input-group">
            <input type="text" name="search" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_search; ?>" class="form-control">
            <span class="input-group-btn">
            <button type="button" data-toggle="tooltip" title="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </span></div>
        </div>
      </div>
      <hr/>

      <div class="filemanager alert alert-success" style="display:none">
        <i class="fa fa-check-circle"></i><span class="message"></span>
        <button type="button" class="close" data-dismiss="alert">×</button>
      </div>

      <div class="view_list">
          <?php require_once('filemanager_list.tpl'); ?>
      </div>


    </div>
    <div class="modal-footer"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
    <?php if ($ckedialog) { ?>
  $(document).on('click', 'a.thumbnail', function (e) {


    e.preventDefault();
    dialog = CKEDITOR.dialog.getCurrent();
    var targetElement = '<?php echo $ckedialog; ?>' || null;
    var target = targetElement.split(':');
    dialog.setValueOf(target[0], target[1], this.getAttribute('href'));
    $('#modal-image').modal('hide');
  });
    <?php } ?>


    <?php if ($target) { ?>
  $('#filemanager .view_list').on('click', 'a.thumbnail', function (e) {
    e.preventDefault();
      <?php if ($thumb) { ?>
    $('#<?php echo $thumb; ?>').find('img').attr('src', $(this).find('img').attr('src'));
      <?php } ?>

    $('#<?php echo $target; ?>').val($(this).parent().find('input').val());

    $('#modal-image').modal('hide');
  });
    <?php } ?>

  $('#filemanager .view_list').on('click', 'a.directory', function (e) {
    e.preventDefault();
    $('#modal-image').load($(this).attr('href'));
  });

  $('.pagination a').on('click', function (e) {
    e.preventDefault();

    $('#modal-image').load($(this).attr('href'));
  });

  $('#button-parent').on('click', function (e) {
    e.preventDefault();

    $('#modal-image').load($(this).attr('href'));
  });

  $('#button-refresh').on('click', function (e) {
    e.preventDefault();

    $('#modal-image .view_list').load($(this).attr('href') + "&view=list");
  });

  $('input[name=\'search\']').on('keydown', function (e) {
    if (e.which == 13) {
      $('#button-search').trigger('click');
    }
  });

  $('#button-search').on('click', function (e) {
    var url = '?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>';

    var filter_name = $('input[name=\'search\']').val();

    if (filter_name) {
      url += '&filter_name=' + encodeURIComponent(filter_name);
    }

      <?php if ($thumb) { ?>
    url += '&thumb=' + '<?php echo $thumb; ?>';
      <?php } ?>

      <?php if ($target) { ?>
    url += '&target=' + '<?php echo $target; ?>';
      <?php } ?>

    $('#modal-image').load(url);
  });
  //--></script>
<script type="text/javascript"><!--
  $('#button-upload').on('click', function () {
    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file[]" value="" multiple="multiple" /></form>');

    $('#form-upload input[name=\'file[]\']').trigger('click');

    if (typeof timer != 'undefined') {
      clearInterval(timer);
    }

    timer = setInterval(function () {
      if ($('#form-upload input[name=\'file[]\']').val() != '') {
        clearInterval(timer);

        $.ajax({
          url: '?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#form-upload')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
            $('#button-upload').prop('disabled', true);
          },
          complete: function () {
            $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
            $('#button-upload').prop('disabled', false);
          },
          success: function (json) {
            if (json['error']) {
              $(".filemanager.alert-success").addClass('alert-danger');
              $(".filemanager.alert-success .message").text( json['error'] );
              $(".filemanager.alert-success").show();
              $('#button-refresh').trigger('click');
            }

            if (json['success']) {
              $(".filemanager.alert-success").removeClass('alert-danger');
              $(".filemanager.alert-success .message").text( json['success'] );
              $(".filemanager.alert-success").show();

              $('#button-refresh').trigger('click');
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    }, 500);
  });

  $('#button-folder').popover({
    html: true,
    placement: 'bottom',
    trigger: 'click',
    title: '<?php echo $entry_folder; ?>',
    content: function () {
      html = '<div class="input-group">';
      html += '  <input type="text" name="folder" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
      html += '  <span class="input-group-btn"><button type="button" title="<?php echo $button_folder; ?>" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
      html += '</div>';

      return html;
    }
  });

  $('#button-folder').on('shown.bs.popover', function () {
    $('#button-create').on('click', function () {
      $.ajax({
        url: '?route=common/filemanager/folder&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
        type: 'post',
        dataType: 'json',
        data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
        beforeSend: function () {
          $('#button-create').prop('disabled', true);
        },
        complete: function () {
          $('#button-create').prop('disabled', false);
        },
        success: function (json) {

          if (json['error']) {
            $(".filemanager.alert-success").addClass('alert-danger');
            $(".filemanager.alert-success .message").text( json['error'] );
            $(".filemanager.alert-success").show();
            $('#button-refresh').trigger('click');
          }

          if (json['success']) {
            $(".filemanager.alert-success").removeClass('alert-danger');
            $(".filemanager.alert-success .message").text( json['success'] );
            $(".filemanager.alert-success").show();

            $('#button-refresh').trigger('click');
          }

        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    });
  });

  $('#modal-image #button-delete').on('click', function (e) {
    if (confirm('<?php echo $text_confirm; ?>')) {
      $.ajax({
        url: '?route=common/filemanager/delete&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        data: $('input[name^=\'path\']:checked'),
        beforeSend: function () {
          $('#button-delete').prop('disabled', true);
        },
        complete: function () {
          $('#button-delete').prop('disabled', false);
        },
        success: function (json) {
          if (json['error']) {
            $(".filemanager.alert-success").addClass('alert-danger');
            $(".filemanager.alert-success .message").text( json['error'] );
            $(".filemanager.alert-success").show();
            $('#button-refresh').trigger('click');
          }

          if (json['success']) {
            $(".filemanager.alert-success").removeClass('alert-danger');
            $(".filemanager.alert-success .message").text( json['success'] );
            $(".filemanager.alert-success").show();

            $('#button-refresh').trigger('click');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });
  //--></script>
