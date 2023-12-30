<?php
/**
 * mOne functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mOne
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'mone_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mone_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on mOne, use a find and replace
		 * to change 'mone' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mone', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'mone_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'mone_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mone_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mone_content_width', 640 );
}
add_action( 'after_setup_theme', 'mone_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mone_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mone' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mone_widgets_init' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}














/**
 * This theme uses wp_nav_menu() in one location.
 */
register_nav_menus(
	array(
		'menu-1' => esc_html__( 'Primary', 'mone' ),
		'menu-2' => esc_html__( 'Secondary', 'Secondary-Menu' ),
		'menu-3' => esc_html__( 'Mobile Menu', 'Mobile-Menu' ),
		'menu-4' => esc_html__( 'Secondary Two', 'Secondary-Menu-Two' )
	)
);


/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'mone_scripts' ) ) {

	function mone_scripts() {

		wp_enqueue_style( 'fontAwesome-min-css', get_template_directory_uri() . '/assets/fontAwesome/css/font-awesome.min.css',false,'4.7.1','all');

		wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300;400;600;700;800&display=swap', array() );
		wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap', array() );

		wp_enqueue_style( 'bootstrap-min-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', false, '4.3.1', 'all');
		wp_enqueue_style( 'owl-carousel-min-css', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', false, '4.3.1', 'all');
		wp_enqueue_style( 'animate-min-css', get_template_directory_uri() . '/assets/css/animate.min.css', false, '4.3.1', 'all');
		// wp_enqueue_style( 'custom.mega.menu.css', get_template_directory_uri() . '/assets/css/custom.mega.menu.css', array(), '4.3.1', 'all');
		wp_enqueue_style( 'mone-style', get_stylesheet_uri(), array(), _S_VERSION );
		wp_style_add_data( 'mone-style', 'rtl', 'replace' );

		wp_enqueue_script( 'jquery-min-js', get_template_directory_uri() . '/assets/js/jquery.min.js', false, 1.1, true);
		wp_enqueue_script( 'jquery-cookie-min-js', get_template_directory_uri() . '/assets/js/jquery.cookie.min.js',  array ( 'jquery-min-js' ), 1.4, true);
		wp_enqueue_script( 'popper-min-js', get_template_directory_uri() . '/assets/js/popper.min.js', array ( 'jquery-min-js' ), 1.14, true);
		wp_enqueue_script( 'bootstrap-min-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array ( 'jquery-min-js' ), 3.3, true);
		wp_enqueue_script( 'owl-carousel-min-js', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array ( 'jquery-min-js' ), 3.3, true);
		wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/main.js', array ( 'jquery-min-js' ), 1.0, true);

		//wp_enqueue_script( 'mone-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'mone_scripts' );
}
