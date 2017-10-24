<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>

      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <div class="container-fluid">

    <div id="elfinder" data-connector-url="<?php echo $connector_url;?>"></div>

  </div>
</div>

<script type="text/javascript" charset="utf-8">
  // Documentation for client options:
  // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
  $(document).ready(function() {
    var elfinder = $('#elfinder');
    var elfinderConnectorUrl = elfinder.data('connector-url');

    if (!elfinderConnectorUrl) {
      var message = 'Please set connector url for elFinder!';
      alert(message);
      throw Error(message);
    }

    elfinder.elfinder({
      url: elfinderConnectorUrl,
    });
  });
</script>

<?php echo $footer; ?>
