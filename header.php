 <!doctype html>
	<!--[if !IE]>
	<html class="no-js non-ie" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 7 ]>
	<html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 8 ]>
	<html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 9 ]>
	<html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
	<!--[if gt IE 9]><!-->
<html> <!--<![endif]-->
	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		
		<title><?php bloginfo( 'name' ); ?><?php wp_title( '—', true, 'left' ); ?></title>

		<link rel="profile" href="http://gmpg.org/xfn/11"/>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

		<!-- <link rel="stylesheet" href="<?php echo home_url( '/assets/css/style.css' ); ?>" type="text/css">	 -->
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div class="container full-height d-flex d-column">
			<header class="d-flex flex-row">
				<div id="site-url" class="d-3-twelfth m-half">
					<a href="<?= home_url() ?>"><?php bloginfo( 'name' ); ?></a>
				</div>
				<div id="menu-btn" class="m-half m-visible">
					<a href="#" data-open="Close" data-close="Menu">Menu</a>
				</div>
				<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
			</header>
