<?php echo $header; ?><?php echo $column_left; ?>
  <div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>"
                                   class="btn btn-primary"><i class="fa fa-plus"></i></a>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"
                  onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-banner').submit() : false;"><i
              class="fa fa-trash-o"></i></button>
        </div>
        <h1><?php echo $heading_title; ?></h1>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
              <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
      </div>
    </div>
    <div class="container-fluid">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?> </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">

            <div id="modal-image"
                 href="<?=$this->url->link('design/media_manager/ajax', 'token=' . $this->session->data['token'], true)?>">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

<script>
  $(document).ready(function(){
      $('#modal-image').load($('#modal-image').attr('href'));
  })
</script>
<?php echo $footer; ?>