<?php
/**
 * Starter Theme functions and definitions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STARTER_THEME_VERSION', '0.1.0' );

/**
 * Theme setup: supports, nav menus, thumbnails.
 */
function starter_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'custom-logo' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'starter-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'starter_theme_setup' );

/**
 * Enqueue theme stylesheet.
 */
function starter_theme_scripts() {
	wp_enqueue_style( 'starter-theme-style', get_stylesheet_uri(), array(), STARTER_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'starter_theme_scripts' );

/**
 * Register a sidebar widget area.
 */
function starter_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'starter-theme' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<section class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'starter_theme_widgets_init' );
