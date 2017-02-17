<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 7 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie7 lt-ie10 lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8 lt-ie10 lt-ie9"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9 lt-ie10"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/theme/atr374opc2101/stylesheet/bootstrap/atra.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href='//fonts.googleapis.com/css?family=Doppio+One' rel='stylesheet' type='text/css' />
<link href='//fonts.googleapis.com/css?family=Passion+One:400,700' rel='stylesheet' type='text/css' />
<link href="catalog/view/theme/atr374opc2101/stylesheet/stylesheet.css" rel="stylesheet" type="text/css" />
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/theme/atr374opc2101/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<!-- STICKY MENU HEADER -->
<script type="text/javascript" src="catalog/view/theme/atr374opc2101/javascript/jquery.sticky.js"></script>
<script type="text/javascript" src="catalog/view/theme/atr374opc2101/javascript/script.js"></script>
<?php foreach ($analytics as $analytic) { ?>
  <?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">
<?php if ($currency && $language) { ?>
<?php $class = 'mobile-sm-6'; ?>
<?php } elseif ($currency || $language) { ?>
<?php $class = 'mobile-sm-5'; ?>
<?php } else { ?>
<?php $class = 'mobile-sm-3'; ?>
<?php } ?>
<nav id="top" class="<?php echo $class; ?>">
  <div class="container top-container">
    <?php echo $currency; ?>
    <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li class="dropdown dropdown-account"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
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
        <?php if ($logged) { ?>
            <li>
                <span><?php echo $customer_name; ?></span>
            </li>
        <?php } ?>
        <li class="hide_tablet"><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li class="hide_tablet"><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li class="hide_tablet"><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
        <li class="hide_tablet"><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
      </ul>
    </div>
  </div>
</nav>
<div id="sticky_head">
  <header id="header">
    <div class="container logo-container">
      <div class="row">
        <div class="col-sm-4">
          <div id="logo">
            <?php if ($logo) { ?>
            <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
            <?php } else { ?>
            <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
            <?php } ?>
          </div>
        </div>
        <div class="col-sm-5">
          <i class="fa fa-search search-toggle"></i><?php echo $search; ?>
        </div>
        <div class="col-sm-3"><?php echo $cart; ?></div>
      </div>
      <div class="row">
        <div class="slogan">
          <?php echo $text_slogan; ?>
        </div>
      </div>
    </div>
  </header>
  <?php if ($categories) { ?><!-- 
--><div class="menu-container-wrapper">
    <div id="sticky_logo">
      <?php if ($logo) { ?>
      <a href="<?php echo $home; ?>"><img src="<?php echo str_replace(".png", "_stickyLogo.png", $logo); ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
      <?php } else { ?>
      <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
      <?php } ?>
    </div>
    <div class="container menu-container">
      <nav id="menu" class="navbar">
      <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
        <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
        <li class="menu_home"><a href="<?php echo $home; ?>"><img width="18" height="18" src="catalog/view/theme/atr374opc2101/image/home_hover.png" title="<?php echo $text_home; ?>" alt="<?php echo $text_home; ?>" class="img-responsive home_hover" /><img width="18" height="18" src="catalog/view/theme/atr374opc2101/image/home.png" title="<?php echo $text_home; ?>" alt="<?php echo $text_home; ?>" class="img-responsive home_icon" /></a></li><!--
        --><li class="mobile_home"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
        <li><a href="index.php?route=product/allproduct">All Products</a></li>
        <!--
        --><?php foreach ($categories as $category) { ?><?php if ($category['children']) { ?><li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><span><?php echo $category['name']; ?></span>&nbsp;&nbsp;<img width="8" height="8" class="menu-close" src="catalog/view/theme/atr374opc2101/image/menu_close.gif" /><img width="8" height="8" class="menu-open" src="catalog/view/theme/atr374opc2101/image/menu_open.gif" /></a>
          <div class="dropdown-menu">
          <div class="dropdown-inner">
            <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
            <ul class="list-unstyled">
            <?php foreach ($children as $child) { ?><!--
            --><li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li><!--
            --><?php } ?>
            </ul>
            <?php } ?>
          </div>
          <a href="<?php echo $category['href']; ?>" class="see-all"><span><?php echo $text_all; ?>&nbsp;<?php echo $category['name']; ?></span></a></div>
        </li><?php } else { ?><!--
        --><li><a href="<?php echo $category['href']; ?>"><span><?php echo $category['name']; ?></span></a></li><?php } ?><?php } ?>
        </ul>
      </div>
      </nav>
    </div>
  </div>
  <?php } ?>
</div>
<div id="logo_mobile">
	<?php if ($logo) { ?>
	<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
	<?php } else { ?>
	<h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
	<?php } ?>
</div>