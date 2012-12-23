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
	 * @uses flush_rewrite_rules
	 * @since 0.1
	 */
	function __construct(){
		self::actions();
		self::filters();
		flush_rewrite_rules(); // required after activation for CPTs
	}
	
	/**
	 * Setup textdomain for translations
	 * @uses load_theme_textdomain
	 * @since 0.2
	 */
	function textdomain(){
		load_theme_textdomain( self::textdomain, get_template_directory() . '/languages' );
	}
	
	/**
	 * Run any actions we want to have
	 * @uses add_action
	 * @since 0.1
	 */
	function actions(){
		add_action('after_setup_theme', array($this, 'textdomain' ) );
		add_action( 'init', array($this, 'register_post_types') );
		add_action( 'load-post.php', array($this, 'supporters_meta_boxes_setup') );
		add_action( 'load-post-new.php', array($this, 'supporters_meta_boxes_setup') );
	}
	
	/**
	 * Run any filters we want to have
	 * @uses add_filter
	 * @since 0.1
	 */
	function filters(){
		add_filter( 'comments_template', array($this, 'remove_comments_template_on_pages'), 11 );
		add_filter('default_hidden_meta_boxes', array($this, 'hide_meta_lock'), 10, 2);
		add_filter('gettext', array($this, 'custom_enter_title'));
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
				'excerpt',
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
	
	/**
	 * Metabox callback for the Supporters CPT
	 * @uses remove_metabox()
	 * @uses add_meta_box()
	 * @since 0.4
	 */
	function supporters_cpt_metabox_cb() {
		remove_meta_box( 'postimagediv', 'supporters', 'side' );
		add_meta_box('postimagediv', __('Supporter Logo', self::textdomain), 'post_thumbnail_meta_box', 'supporters', 'normal', 'default');
	}
	
	/**
	 * Hide authors metabox from Supporters custom post type
	 * @since 0.4
	 */
	function hide_meta_lock($hidden, $screen) {
		if ( 'supporters' == $screen->post_type )
			$hidden = array('slugdiv','postcustom','trackbacksdiv', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv', 'pageparentdiv');
		return $hidden;
	}
	
	/**
	 * Change title helper text on Supporter edit page
	 * @since 0.4
	 */
	function custom_enter_title( $input ) {
	
		global $post_type;
	
		if( is_admin() && 'Enter title here' == $input && 'supporters' == $post_type )
			return 'Enter supporter name here';
	
		return $input;
	}
	
	/**
	 * Build our custom meta boxes for the Supporters CPT
	 * @uses add_action
	 * @since 0.4
	 */
	function supporters_meta_boxes_setup() {
		add_action( 'add_meta_boxes', array($this, 'add_post_meta_boxes') );
		add_action( 'save_post', array($this, 'save_post_meta'), 10, 2 );
	}
	
	/**
	 * Add the post meta boxes for Supporters CPT
	 * @uses add_meta_box()
	 * @since 0.4
	 */
	function add_post_meta_boxes() {
		add_meta_box(
			'supporters-url',			// Unique ID
			esc_html__( 'Web Address', self::textdomain ),		// Title
			array($this, 'supporters_class_meta_box'),		// Callback function
			'supporters',					// Admin page (or post type)
			'normal',					// Context
			'default'					// Priority
		);
	}
	
	/**
	 * Our metabox content for the Supporters URL
	 * @uses wp_nonce_field()
	 * @since 0.4
	 */
	function supporters_class_meta_box($object, $box) { ?>
	
		<?php wp_nonce_field( basename( __FILE__ ), 'supporters_url_nonce' ); ?>
	
		<p>
			<label for="supporters-url"><?php _e( "Add the web address to the Supporter's website", self::textdomain ); ?></label>
			<br />
			<input class="widefat" type="text" name="supporters-url" id="supporters-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'supporters_url', true ) ); ?>" size="30" />
		</p>
	<?php }
	
	/**
	 * Save the meta box data
	 * @uses wp_verify_nonce()
	 * @uses basename()
	 * @uses get_post_type_object()
	 * @uses current_user_can()
	 * @uses sanitize_html_class()
	 * @uses get_post_meta()
	 * @uses add_post_meta()
	 * @uses update_post_meta()
	 * @uses delete_post_meta()
	 * @uses esc_url()
	 * @since 0.4
	 */
	function save_post_meta( $post_id, $post ) {
	
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['supporters_url_nonce'] ) || !wp_verify_nonce( $_POST['supporters_url_nonce'], basename( __FILE__ ) ) )
			return $post_id;
	
		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );
	
		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;
	
		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST['supporters-url'] ) ? esc_url( $_POST['supporters-url'] ) : '' );
	
		/* Get the meta key. */
		$meta_key = 'supporters_url';
	
		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );
	
		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	
		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	
		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}
new We_Love_Lichfield_Fund;