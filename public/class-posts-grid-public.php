<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://webcodesigner.com/
 * @since      1.0.0
 *
 * @package    Posts_Grid
 * @subpackage Posts_Grid/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Posts_Grid
 * @subpackage Posts_Grid/public
 * @author     Cristian Ionel <cristian.ionel@gmail.com>
 */
class Posts_Grid_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $overlay;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Posts_Grid_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Posts_Grid_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/posts-grid-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Posts_Grid_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Posts_Grid_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/posts-grid-public.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Registers [posts-grid] shortcode
	 *
	 * @return [type] [description]
	 */
	public function register_shortcode() {
		add_shortcode( 'posts-grid', array( $this, 'postsGrid' ) );
		
	} // register_shortcode()

	/*-----------------------------------------------------------------------------------*/
	/*	Posts Shortcode
	/*
	/* [posts featured_type="page" featured_id="1" featured_text="My New Headline"  featured_image="http://new.feature.image.jpg" ids="31,42,56"]
	/*-----------------------------------------------------------------------------------*/

	public function postsGrid( $atts, $content = null ) {
	    extract(shortcode_atts(
	    	array(
			    'featured_id'	=> 0,
			    'featured_headline'	=> '',
			    'featured_text'	=> '',
			    'featured_image' => '',
			    'featured_color' => '',
			    'ids' => array(),
			    'overlay' => false,
		    ),
	    $atts));

	    // Posts IDs to array of integers
	    $ids = array_map('intval', explode(',', $ids));
	    
	    // Create HTML
	    $out = '<div class="posts_grid'.( ($overlay) ? ' overlay_text':'' ).'">';
	    
	    // Featured Post/Page area
	    if($featured_id){
	    	$out .= $this->showFeatured($featured_id,$featured_headline,$featured_text,$featured_image,$featured_color);
	    }
	    
	    if(!empty($ids)){
	    	$out .= '<section>';
	    	foreach($ids as $id){
	    		$out .= $this->showPost($id);
	    	}
	    	// Close containers
			$out .= '</section>';
	    }
	    
	    
	    

		// Return html string
	    return $out;
		
		
	}

	public function showPost($id) {
		$out = '<div class="column posts_grid_post">';

		$grid_post = get_post($id);

		// Post Featured Image
		if ( has_post_thumbnail( $id ) ){
			$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
			$post_featured_image = $post_thumbnail[0];
			$post_css = 'background: url("'.$post_featured_image.'") no-repeat center;background-size:cover;';
			$out .= '<a href="' . get_permalink( $id ) . '" title="' . esc_attr( $grid_post->post_title ) . '"><div class="featured_image_container" style=\''.$post_css.'\'></div></a>';
    	}

    	// Featured Title
    	$out .= '<h4><a href="' . get_permalink( $id ) . '" title="' . esc_attr( $grid_post->post_title ) . '">' . $grid_post->post_title . '</a></h4>';

		$out .= '</div>';

		return $out;
	}

	public function showFeatured($featured_id,$featured_headline,$featured_text,$featured_image,$featured_color) {
		$out = '<section><div class="column posts_grid_featured">';
    		
		$featured = get_post($featured_id);

		// Featured Title
    	$featured_headline = '<h3><a href="' . get_permalink( $featured_id ) . '" title="' . esc_attr( $featured->post_title ) . '">' . (($featured_headline != '') ? $featured_headline : $featured->post_title) . '</a></h3>';
    	
    	// Featured Description
    	$featured_text = '<p>' . ($featured_text != '') ? $featured_text : get_the_excerpt($featured_id) . '</p>';
    	
    	// Featured Image			
	    if($featured_image == ''){
	    	if ( has_post_thumbnail( $featured_id ) ){
				$featured_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $featured_id ), 'full' );
				$featured_image = $featured_thumbnail[0];
	    	} else {
	    		if ($featured_color == ''){
	    			die('Please set a featured image or color.');
	    			exit();
		    	}
    		}
	    }

	    $featured_css = 'background: url("'.$featured_image.'") no-repeat center;background-size:cover;';
    	if($featured_color){
    		$featured_css = 'background-color:"'.$featured_color.'";';
    	}
    	$featured_image_section = '<a href="' . get_permalink( $featured_id ) . '" title="' . esc_attr( $featured->post_title ) . '"><div class="featured_image_container" style=\''.$featured_css.'\'></div></a>';

    	$out .= $featured_image_section . $featured_headline . $featured_text . '</div></section>';

    	return $out;
	}


}
