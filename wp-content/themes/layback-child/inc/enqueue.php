<?php 

	add_action( 'wp_enqueue_scripts', 'lb_enqueue_files' );
	function lb_enqueue_files() 
	{

		/* Normalize
		------------------------------------------------------------------ */

		wp_enqueue_style( 'normalizecss', 'https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css', false, '8.0.1', 'all' );
		
		/* Flexbox Grid
		------------------------------------------------------------------ */

		wp_enqueue_style( 'flexboxgrid', 'https://cdn.jsdelivr.net/npm/flexboxgrid@6.3.1/dist/flexboxgrid.min.css', false, '6.3.1', 'all' );
		
 		/* Slick slideshow - https://github.com/kenwheeler/slick/
		------------------------------------------------------------------ */

		// wp_enqueue_style( 'slickcss', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', false, '1.8.1', 'all' );
		// wp_enqueue_script( 'slickjs', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '1.8.1', true );

		/* jQuery cookies - https://github.com/js-cookie/js-cookie
		------------------------------------------------------------------ */

		// wp_enqueue_script( 'cookiesjs', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', array('jquery', 'site'), '1.0', true );
		
		/* Stylesheet for this site
		------------------------------------------------------------------ */

		// wp_enqueue_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:400,700', false, '1.0.0', 'all' );
		wp_enqueue_style( 'wordpress-stylesheet', get_stylesheet_directory_uri() . '/style.css', false, filemtime(get_stylesheet_directory() . '/style.css'), 'all' );
		wp_enqueue_style( 'layback-style', get_stylesheet_directory_uri() . '/lib/css/style.css', false, filemtime(get_stylesheet_directory() . '/lib/css/style.css'), 'all' );

		/* This sites default javascript file
		------------------------------------------------------------------ */

		// wp_enqueue_script( 'easing', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js', array('jquery'), '1.4.1', true );
		// wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/97ac913193.js', array(), null, true );
		wp_enqueue_script( 'layback-site', get_stylesheet_directory_uri().'/lib/js/site.js', array('jquery'), filemtime(get_stylesheet_directory() . '/lib/js/site.js'), true );
	}


	/* Customizer live preview 
	------------------------------------------------------------------ */
	
	add_action( 'customize_preview_init', 'customizer_live_preview' );
	function customizer_live_preview()
	{
		wp_enqueue_script('layback-customizer', get_stylesheet_directory_uri() . '/lib/js/theme-customizer.js', array( 'jquery', 'customize-preview' ), filemtime(get_stylesheet_directory() . '/lib/js/theme-customizer.js'), true);
	}

	/* Admin stylesheet
	------------------------------------------------------------------ */

	// add_action('admin_enqueue_scripts', 'lb_enqueueStyle_admin');
	function lb_enqueueStyle_admin()
	{
		wp_enqueue_style('layback-admin', get_stylesheet_directory_uri() . '/lib/css/admin.css', array(), filemtime(get_stylesheet_directory() . '/lib/css/admin.css'));
	}

	/* Custom editor styles
	------------------------------------------------------------------ */

	add_action('init', 'custom_editor_styles');
	function custom_editor_styles() {
		add_editor_style( get_stylesheet_directory_uri() . '/lib/css/editor.css' );
	}
