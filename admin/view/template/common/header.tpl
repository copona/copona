<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <script>window.CATALOG_URL = '<?php echo addslashes(HTTP_CATALOG); ?>';</script>
    <script src="view/javascript/jquery/jquery-3.7.1.min.js"></script>
    <script src="view/javascript/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
    <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="view/javascript/dist/copona-admin.css?v=<?php echo $asset_v; ?>" type="text/css" rel="stylesheet" />
    <script src="view/javascript/dist/editor.bundle.js?v=<?php echo $asset_v; ?>"></script>
    <link type="text/css" href="view/stylesheet/stylesheet.css?v=<?php echo $asset_v; ?>" rel="stylesheet" media="screen" />
    <link type="text/css" href="view/stylesheet/bs3-compat.css?v=<?php echo $asset_v; ?>" rel="stylesheet" media="screen" />
    <link type="text/css" href="view/stylesheet/flatpickr.min.css" rel="stylesheet" media="screen" />
    <script src="view/javascript/flatpickr/flatpickr.min.js"></script>
    <script src="view/javascript/flatpickr/datetimepicker-shim.js"></script>

    <?php foreach ($styles as $style) { ?>
        <link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
        <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <script src="view/javascript/common.js?v=<?php echo $asset_v; ?>" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
        <script type="text/javascript" src="<?php echo $script; ?>"></script>
    <?php } ?>
  </head>
  <body>
  <?php if (!empty($demo_text)) { ?>
  <div id="copona-demo-bar" style="background:#fff3cd;color:#664d03;border-bottom:1px solid #ffe69c;padding:6px 16px;font-size:13px;text-align:center;"><?php echo $demo_text; ?></div>
  <?php } ?>
  <?php if (!empty($demo_text)) { ?>
  <!-- Demo mode: refused saves get a popup nobody can miss (page reloads and AJAX alike). -->
  <div id="copona-demo-popup" role="alertdialog" aria-live="assertive" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
    <div style="background:#198754;color:#fff;max-width:440px;margin:16px;padding:28px 32px;border-radius:10px;box-shadow:0 10px 40px rgba(0,0,0,.35);text-align:center;font-size:18px;">
      <i class="fa fa-lock" style="font-size:36px;display:block;margin-bottom:12px;"></i>
      <span id="copona-demo-popup-text"><?php echo $demo_warning ? $demo_warning : $demo_text; ?></span>
      <div style="margin-top:20px;"><button type="button" class="btn btn-light" onclick="document.getElementById('copona-demo-popup').style.display='none';">OK</button></div>
    </div>
  </div>
  <script>
    function coponaDemoPopup(message) {
      var popup = document.getElementById('copona-demo-popup');
      if (message) document.getElementById('copona-demo-popup-text').textContent = message;
      popup.style.display = 'flex';
      popup.querySelector('button').focus();
    }
    <?php if (!empty($demo_warning)) { ?>coponaDemoPopup();<?php } ?>
    $(document).ajaxComplete(function (event, xhr) {
      if (xhr.responseJSON && xhr.responseJSON.demo) coponaDemoPopup(xhr.responseJSON.demo);
    });
  </script>
  <?php } ?>

  <div id="container">
      <header id="header" class="navbar">
        <div class="navbar-header">
            <?php if ($logged) { ?>
              <a type="button" id="button-menu" class="float-start"><i class="fa fa-indent fa-lg"></i></a>
          <?php } ?>
          <a href="<?php echo $home; ?>" class="navbar-brand"><span class="hidden-xs"><img src="view/image/logo.png?v=<?php echo $asset_v; ?>" height="30" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></span><span class="hidden-lg hidden-md hidden-sm"><i class="fa fa-shopping-cart"></i></span></a></div>
        <?php if ($logged) { ?>
            <ul class="nav float-end">
                <li>
                    <a href="<?=$clear_all_cache;?>"
                       class="label label-warning float-end"
                       onclick="return confirm('<?=$text_clear_all_cache_confirm;?>');"><?=$text_clear_all_cache;?></a>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-bs-toggle="dropdown"><?php if ($alerts > 0) { ?><span class="label label-danger float-start"><?php echo $alerts; ?></span><?php } ?> <i class="fa fa-bell fa-lg"></i></a>
               <ul class="dropdown-menu dropdown-menu-end alerts-dropdown">
                  <li class="dropdown-header"><?php echo $text_order; ?></li>
                  <li><a href="<?php echo $processing_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning float-end"><?php echo $processing_status_total; ?></span><?php echo $text_processing_status; ?></a></li>
                  <li><a href="<?php echo $complete_status; ?>"><span class="label label-success float-end"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
                  <li><a href="<?php echo $return; ?>"><span class="label label-danger float-end"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
                  <li class="dropdown-divider"></li>
                  <li class="dropdown-header"><?php echo $text_customer; ?></li>
                  <li><a href="<?php echo $online; ?>"><span class="label label-success float-end"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
                  <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger float-end"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
                  <li class="dropdown-divider"></li>
                  <li class="dropdown-header"><?php echo $text_product; ?></li>
                  <li><a href="<?php echo $product; ?>"><span class="label label-danger float-end"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
                  <li><a href="<?php echo $review; ?>"><span class="label label-danger float-end"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
                  <li class="dropdown-divider"></li>
                  <li class="dropdown-header"><?php echo $text_affiliate; ?></li>
                  <li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger float-end"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
                </ul>
              </li>
              <li class="dropdown"><a class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-home fa-lg"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li class="dropdown-header"><?php echo $text_store; ?></li>
                  <?php foreach ($stores as $store) { ?>
                      <li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
                  <?php } ?>
                </ul>
              </li>
              <li class="dropdown"><a class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-life-ring fa-lg"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li class="dropdown-header"><?php echo $text_help; ?></li>
                  <li><a href="https://copona.org" target="_blank"><?php echo $text_homepage; ?></a></li>
                  <li><a href="https://github.com/copona/copona#readme" target="_blank"><?php echo $text_documentation; ?></a></li>
                  <li><a href="https://github.com/copona/copona/issues" target="_blank"><?php echo $text_support; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
            </ul>
        <?php } ?>
      </header>
