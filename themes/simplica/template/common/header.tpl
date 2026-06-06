<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta property="og:type" content="website"/>
  <meta property="og:title" content="<?php echo $title; ?>">
  <meta property="og:description"
        content="<?= $description ? $description : '' ?>"><?php if ($class == 'common-home') { ?>
    <meta property="og:url" content="<?php echo $base; ?>">
    <?php }; ?>
  <meta property="og:site_name" content="<?php echo Config::get('config_name') ?>">
  <title><?php echo $title; ?></title>
  <base href="<?php echo $base; ?>"/>
    <?php if ($description) { ?>
      <meta name="description" content="<?php echo $description; ?>"/>
    <?php } ?>
    <?php if ($keywords) { ?>
      <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <?php } ?>

    <?php foreach ($styles as $style) { ?>
      <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>"
            media="<?php echo $style['media']; ?>, print"/>
    <?php } ?>

    <?php foreach ($links as $link) { ?>
      <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>"/>
    <?php } ?>
    <?php foreach ($scripts as $script) { ?>
      <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <?php foreach ($analytics as $analytic) { ?>
        <?php echo $analytic; ?>
    <?php } ?>
</head>
<body class="<?php echo $class; ?>">
<?php if (!empty($admin_bar)) { ?>
<div id="copona-admin-bar" style="position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#1d1d1f;color:#f5f5f7;padding:8px 16px;display:flex;align-items:center;gap:12px;font-family:sans-serif;font-size:13px;box-shadow:0 -2px 8px rgba(0,0,0,.4);">
  <a href="/admin/" style="opacity:.6;font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:#f5f5f7;text-decoration:none;">&#9881; Admin</a>
  <?php foreach ($admin_bar_links as $link) { ?>
  <a href="<?php echo $link['href']; ?>" style="background:#0071e3;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-weight:600;"><?php echo $link['text']; ?></a>
  <?php } ?>
</div>
<?php } ?>
<nav id="top">
  <div class="container">
      <?php echo $currency; ?>
      <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
        <?php if (!$cms_hide_top_eshop_links) { ?>
          <ul class="list-inline">
            <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span
                  class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>

            <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i
                    class="fa fa-heart"></i> <span
                    class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
            <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i
                    class="fa fa-shopping-cart"></i> <span
                    class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
            <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i>
                <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
            <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>"
                                    class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span
                    class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span
                    class="caret"></span></a>
              <ul class="dropdown-menu dropdown-menu-right">
                  <?php if ($logged) { ?>
                    <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                    <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                    <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
                    <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                    <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                  <?php } else { ?>
                    <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
                    <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
                  <?php } ?>
              </ul>
            </li>
          </ul>
        <?php } ?>
    </div>
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <div id="logo">
            <?php if ($logo) { ?>
              <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
                                                  alt="<?php echo $name; ?>" class="img-responsive"/></a>
            <?php } else { ?>
              <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
            <?php } ?>
        </div>
      </div>
        <?php if (!$cms_hide_top_search) { ?>
          <div class="col-md-6 col-sm-5"><?php echo $search; ?>
          </div>
        <?php } ?>
        <?php if (!$cms_hide_top_cart) { ?>
          <div id="cart" class="col-md-3 col-sm-4"><?php echo $cart; ?></div>
        <?php } ?>
    </div>
  </div>
</header>
<?php if ($categories || $informations) { ?>
  <div class="container">
    <nav id="menu" class="navbar">
      <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
        <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse"
                data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <?php if ($categories) { ?>
                <?php foreach ($categories as $category) { ?>
                    <?php if ($category['children']) { ?>
                  <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle"
                                          data-toggle="dropdown"><?php echo $category['name']; ?></a>
                    <div class="dropdown-menu">
                      <div class="dropdown-inner">
                          <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                            <ul class="list-unstyled">
                                <?php foreach ($children as $child) { ?>
                                  <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                                <?php } ?>
                            </ul>
                          <?php } ?>
                      </div>
                      <a href="<?php echo $category['href']; ?>"
                         class="see-all"><?php echo $text_all; ?><?php echo $category['name']; ?></a></div>
                  </li>
                    <?php } else { ?>
                  <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <?php if ($informations) { ?>
                <?php foreach ($informations as $information) { ?>
                <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                <?php } ?>
            <?php } ?>
        </ul>
      </div>
    </nav>
  </div>
<?php } ?>
