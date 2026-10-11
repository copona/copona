<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $og_has_type = false; foreach ($ogmeta as $og) { if (strpos($og['name'], '"og:type"') !== false) { $og_has_type = true; } } ?>
  <?php if (!$og_has_type) { ?>
  <meta property="og:type" content="website"/>
  <?php } ?>
  <meta property="og:title" content="<?php echo $title; ?>">
  <meta property="og:description"
        content="<?= $description ? $description : '' ?>"><?php if ($class == 'common-home') { ?>
    <meta property="og:url" content="<?php echo $base; ?>">
    <?php }; ?>
  <meta property="og:site_name" content="<?php echo Config::get('config_name') ?>">
    <?php foreach ($ogmeta as $og) { ?>
      <meta <?php echo $og['name']; ?> content="<?php echo htmlspecialchars((string)$og['content'], ENT_QUOTES, 'UTF-8', false); ?>">
    <?php } ?>
    <?php foreach ($json_ld as $schema) { ?>
      <script type="application/ld+json"><?php echo $schema; ?></script>
    <?php } ?>
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
<?php if (!empty($demo_text)) { ?>
<div id="copona-demo-bar"><?php echo $demo_text; ?></div>
<?php } ?>
<?php if (!empty($admin_bar)) { ?>
<div id="copona-admin-bar">
  <a href="/admin/" class="copona-admin-bar__home">&#9881; Admin</a>
  <?php foreach ($admin_bar_links as $link) { ?>
  <a href="<?php echo $link['href']; ?>" class="copona-admin-bar__edit"><?php echo $link['text']; ?></a>
  <?php } ?>
</div>
<?php } ?>
<div class="cp-toast-wrap">
  <div id="cart-toast" class="toast" role="alert" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
    <div class="toast-header">
      <i class="fa fa-check-circle text-success me-2"></i>
      <strong class="me-auto"><?php echo isset($text_added_to_cart) ? $text_added_to_cart : 'Added to cart'; ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      <span id="cart-toast-product"></span>
      <a id="cart-toast-link" href="#" class="btn btn-dark btn-sm"><?php echo $text_shopping_cart; ?></a>
    </div>
  </div>
</div>

<div id="top">
  <div class="container">
    <div class="cp-top__left">
      <?php if ($telephone) { ?>
        <a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i> <?php echo $telephone; ?></a>
      <?php } ?>
    </div>
    <div class="cp-top__right">
      <?php echo $currency; ?>
      <?php echo $language; ?>
      <?php if (!$cms_hide_top_eshop_links) { ?>
        <a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a>
      <?php } ?>
    </div>
  </div>
</div>
<header class="cp-header">
  <div class="container cp-header__bar">
    <div id="logo">
      <?php if ($logo) { ?>
        <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>"/></a>
      <?php } else { ?>
        <a href="<?php echo $home; ?>" class="cp-wordmark"><?php echo $name; ?></a>
      <?php } ?>
    </div>
    <?php if (!$cms_hide_top_search) { ?>
      <?php echo $search; ?>
    <?php } ?>
    <div class="cp-header__actions">
      <?php if (!$cms_hide_top_eshop_links) { ?>
        <div class="dropdown">
          <a href="<?php echo $account; ?>" class="cp-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo $text_account; ?>"><i class="fa fa-user-o"></i><span class="visually-hidden"><?php echo $text_account; ?></span></a>
          <ul class="dropdown-menu dropdown-menu-end">
            <?php if ($logged) { ?>
              <li><a class="dropdown-item" href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
              <li><a class="dropdown-item" href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
              <li><a class="dropdown-item" href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
              <li><a class="dropdown-item" href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
              <li><a class="dropdown-item" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
              <li><a class="dropdown-item" href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
              <li><a class="dropdown-item" href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <a href="<?php echo $wishlist; ?>" id="wishlist-total" class="cp-icon-btn" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart-o"></i><span class="visually-hidden"><?php echo $text_wishlist; ?></span></a>
      <?php } ?>
      <?php if (!$cms_hide_top_cart) { ?>
        <div id="cart" class="dropdown"><?php echo $cart; ?></div>
      <?php } ?>
    </div>
  </div>
  <?php if ($categories || $informations) { ?>
    <nav id="menu" class="navbar navbar-expand-lg">
      <div class="container">
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar-main" aria-controls="navbar-main" aria-expanded="false">
          <i class="fa fa-bars"></i> <?php echo $text_category; ?>
        </button>
        <div class="collapse navbar-collapse" id="navbar-main">
          <ul class="navbar-nav">
            <?php foreach ($categories as $category) { ?>
              <?php if ($category['children']) { ?>
                <li class="nav-item dropdown">
                  <a href="<?php echo $category['href']; ?>" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $category['name']; ?></a>
                  <div class="dropdown-menu">
                    <?php foreach ($category['children'] as $child) { ?>
                      <a class="dropdown-item" href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                    <?php } ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item cp-see-all" href="<?php echo $category['href']; ?>"><?php echo $text_all; ?> <?php echo $category['name']; ?></a>
                  </div>
                </li>
              <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
              <?php } ?>
            <?php } ?>
            <?php foreach ($informations as $information) { ?>
              <li class="nav-item"><a class="nav-link" href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
  <?php } ?>
</header>
