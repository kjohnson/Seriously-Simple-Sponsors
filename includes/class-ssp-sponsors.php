<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Sponsors {

	/**
	 * The single instance of SSP_Sponsors.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The taxonomy slug.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $tax;

	/**
	 * The singular name for taxonomy terms.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $single;

	/**
	 * The plural name for taxonomy terms.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plural;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'ssp_sponsors';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );

		// Setup taxonomy details
		add_action( 'init', array( $this, 'setup_tax' ) );

		// Register functions to run on plugin activation
		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Register taxonomy
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		// Add sponsors to episode meta
		add_filter( 'ssp_episode_meta_details', array( $this, 'display_sponsors' ), 10, 3 );

		// Handle localisation
		add_action( 'plugins_loaded', array( $this, 'load_localisation' ) );
	} // End __construct ()

	public function setup_tax () {
		$this->tax = 'sponsor';
		$this->single = apply_filters( 'ssp_sponsors_single_label', __( 'Sponsor', 'seriously-simple-sponsors' ) );
		$this->plural = apply_filters( 'ssp_sponsors_plural_label', __( 'Sponsors', 'seriously-simple-sponsors' ) );
	}

	public function display_sponsors ( $meta = array(), $episode_id = 0, $context = '' ) {

		if( ! $episode_id ) {
			return $meta;
		}

        $sponsors = wp_get_post_terms( $episode_id, 'sponsor' );

		// Saving sponsor count in a variable as is it used a few times
		$count = count( $sponsors );

		if( is_wp_error( $sponsors ) || ( is_array( $sponsors ) && 0 == $count ) ) {
			return $meta;
		}

		// Get label for sponsor display
		if( 1 == $count ) {
			$label = $this->single;
		} else {
			$label = $this->plural;
		}

		// Allow dynamic filtering of label
		$label = apply_filters( 'ssp_sponsors_display_label', $label, $episode_id, $count );

        $sponsors_html = '';

		// Generate HTML for speaker display
		foreach( $sponsors as $sponsor ) {

			if( ! $sponsors_html ) {
                $sponsors_html .= $label . ': ';
			} else {
                $sponsors_html .= ', ';
			}

            $sponsors_html .= '<a href="' . get_term_link( $sponsor->term_id ) . '">' . $sponsor->name . '</a>';

		}

        $sponsors_html = apply_filters( 'ssp_sponsors_display', $sponsors_html, $episode_id );

		// Add speaker display to episode meta
		if( $sponsors_html ) {
			$meta['sponsors'] = $sponsors_html;
		}

		return $meta;
	}

	public function register_taxonomy() {

		// Create taxonomy labels
		$labels = array(
            'name' => $this->plural,
            'singular_name' => $this->single,
            'menu_name' => $this->plural,
            'all_items' => sprintf( __( 'All %s' , 'seriously-simple-sponsors' ), $this->plural ),
            'edit_item' => sprintf( __( 'Edit %s' , 'seriously-simple-sponsors' ), $this->single ),
            'view_item' => sprintf( __( 'View %s' , 'seriously-simple-sponsors' ), $this->single ),
            'update_item' => sprintf( __( 'Update %s' , 'seriously-simple-sponsors' ), $this->single ),
            'add_new_item' => sprintf( __( 'Add New %s' , 'seriously-simple-sponsors' ), $this->single ),
            'new_item_name' => sprintf( __( 'New %s Name' , 'seriously-simple-sponsors' ), $this->single ),
            'parent_item' => sprintf( __( 'Parent %s' , 'seriously-simple-sponsors' ), $this->single ),
            'parent_item_colon' => sprintf( __( 'Parent %s:' , 'seriously-simple-sponsors' ), $this->single ),
            'search_items' =>  sprintf( __( 'Search %s' , 'seriously-simple-sponsors' ), $this->plural ),
            'popular_items' =>  sprintf( __( 'Popular %s' , 'seriously-simple-sponsors' ), $this->plural ),
            'separate_items_with_commas' =>  sprintf( __( 'Separate %s with commas' , 'seriously-simple-sponsors' ), $this->plural ),
            'add_or_remove_items' =>  sprintf( __( 'Add or remove %s' , 'seriously-simple-sponsors' ), $this->plural ),
            'choose_from_most_used' =>  sprintf( __( 'Choose from the most used %s' , 'seriously-simple-sponsors' ), $this->plural ),
            'not_found' =>  sprintf( __( 'No %s found' , 'seriously-simple-sponsors' ), $this->plural ),
            'items_list_navigation' => sprintf( __( '%s list navigation' , 'seriously-simple-sponsors' ), $this->plural ),
            'items_list' => sprintf( __( '%s list' , 'seriously-simple-sponsors' ), $this->plural ),
        );

		// Build taxonomy arguments
        $args = array(
        	'label' => $this->plural,
        	'labels' => apply_filters( 'ssp_sponsors_taxonomy_labels', $labels ),
        	'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'meta_box_cb' => null,
            'show_admin_column' => true,
            'update_count_callback' => '',
            'query_var' => $this->tax,
            'rewrite' => array( 'slug' => apply_filters( 'ssp_sponsors_taxonomy_slug', $this->tax ) ),
            'sort' => '',
        );

        // Allow filtering of taxonomy arguments
        $args = apply_filters( 'ssp_register_taxonomy_args', $args, $this->tax );

        // Get all selected podcast post types
        $podcast_post_types = ssp_post_types( true );

        // Register taxonomy for all podcast post types
        register_taxonomy( $this->tax, $podcast_post_types, $args );
    }

    public function get_sponsors ( $episode_id = 0 ) {

    	$sponsors = array();

    	if( ! $episode_id ) {
			global $post;
			$episode_id = $post->ID;
		}

		if( ! $episode_id ) {
			return $sponsors;
		}

		$sponsor_terms = wp_get_post_terms( $episode_id, 'sponsor' );

		if( is_wp_error( $sponsors ) || ( is_array( $sponsor_terms ) && 0 == count( $sponsor_terms ) ) ) {
			return $sponsors;
		}

		foreach( $sponsor_terms as $sponsor ) {
            $sponsors[] = array(
				'id' => $sponsor->term_id,
				'name' => $sponsor->name,
				'url' => get_term_link( $sponsor->term_id ),
			);
		}

		return $sponsors;
    }

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'seriously-simple-sponsors', false, basename( $this->dir ) . '/languages/' );
	} // End load_localisation ()

	/**
	 * Main SSP_Sponsors Instance
	 *
	 * Ensures only one instance of SSP_Sponsors is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see SSP_Sponsors()
	 * @return Main SSP_Sponsors instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();

		// Register taxonomy and flush rewrite rules on plugin activation
		$this->register_taxonomy();
		flush_rewrite_rules( true );
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
