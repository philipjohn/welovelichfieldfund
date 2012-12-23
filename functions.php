<?php

/**
 * We Love Lichfield Class
 */
Class We_Love_Lichfield_Fund {
	
	/*
	 * Define textdomain for this theme
	 * @since 0.2
	 */
	const textdomain = 'welovelichfieldfund';
	
	/**
	 * Construct!
	 * @since 0.1
	 */
	function __construct(){
		self::actions();
		self::filters();
		flush_rewrite_rules(); // required after activation for CPTs
	}
	
	/**
	 * Run any actions we want to have
	 * @uses add_action
	 * @since 0.1
	 */
	function actions(){
		add_action('after_setup_theme', array($this, 'textdomain' ) );
	}
	
	/**
	 * Setup textdomain for translations
	 * @since 0.2
	 */
	function textdomain(){
		load_theme_textdomain( self::textdomain, get_template_directory() . '/languages' );
	}
	
	/**
	 * Run any filters we want to have
	 * @since 0.1
	 */
	function filters(){
		add_filter( 'comments_template', array($this, 'remove_comments_template_on_pages'), 11 );
	}
	
	/**
	 * Remove comments from pages completely
	 * @param string $file Path to the theme comments template
	 * @since 0.3
	 */
	function remove_comments_template_on_pages( $file ) {
		if ( is_page() )
			$file = STYLESHEETPATH . '/no-comments-please.php';
		return $file;
	}
	
	/**
	 * Register our new post types
	 * @uses register_post_type()
	 * @since 0.4
	 */
	function register_post_types() {
		$supporters_cpt = array(
			'label' => __('Supporters', self::textdomain),
			'labels' => array(
				'name' => __('Supporters', self::textdomain),
				'singular_name' => __('Supporter', self::textdomain),
				'add_new' => __('Add New Supporter', self::textdomain),
				'all_items' => __('All Supporters', self::textdomain),
				'add_new_item' => __('Add New Supporter', self::textdomain),
				'edit_item' => __('Edit Supporter', self::textdomain),
				'new_item' => __('New Supporter', self::textdomain),
				'view_item' => __('View Supporter', self::textdomain),
				'search_items' => __('Search Supporters', self::textdomain),
				'not_found' => __('No supporters found', self::textdomain),
				'not_found_in_trash' => __('No supporters found in trash', self::textdomain),
				'menu_name' => __('Supporters', self::textdomain)
				),
			'description' => __('A listing of supporters of the We Love Lichfield Fund', self::textdomain),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 20,
			'capability_type' => 'post',
			'map_meta_cap' => true,
			'hierarchical' => true,
			'supports' => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'revisions',
				'page-attributes'
				),
			'register_meta_box_cb' => array($this, 'supporters_cpt_metabox_cb'),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'supporters',
				'with_front' => false,
				'feeds' => true,
				'pages' => true
				),
			'can_export' => true
			);
		register_post_type('supporters', $supporters_cpt);
	}
}
new We_Love_Lichfield_Fund;