<?php
	//kill gutenberg
	add_filter( 'use_block_editor_for_post', '__return_false' );

	//remove admin bar
	show_admin_bar(false);

	function register_my_menu() {
		register_nav_menu('header-menu',__( 'Header Menu' ));
	}
	add_action( 'init', 'register_my_menu' );


	// enqueue scripts
	function js_scripts() {
		wp_enqueue_script( 'embla', 'https://unpkg.com/embla-carousel/embla-carousel.umd.js', array(), '', true );
		wp_enqueue_script( 'embla-autoplay', 'https://unpkg.com/embla-carousel-autoplay/embla-carousel-autoplay.umd.js', array(), '', true );
		wp_enqueue_script( 'embla-fade', 'https://unpkg.com/embla-carousel-fade/embla-carousel-fade.umd.js', array(), '', true );

		wp_enqueue_script( 'simple-lightbox', get_stylesheet_directory_uri() . '/assets/js/simplelightbox/simple-lightbox.js', array(), '', true );
	  
		wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array(), '', true );
	}

	add_action( 'wp_enqueue_scripts', 'js_scripts' );

	//enqueue css
	function register_theme_styles() {
	  wp_register_style( 'simple-lightbox-style', get_template_directory_uri() . '/assets/js/simplelightbox/simplelightbox.css' );
	  wp_register_style( 'style', get_template_directory_uri() . '/assets/css/style.css' );
	  wp_enqueue_style( 'simple-lightbox-style' );
	  wp_enqueue_style( 'style' );
	}
	add_action( 'wp_enqueue_scripts', 'register_theme_styles' );
?>