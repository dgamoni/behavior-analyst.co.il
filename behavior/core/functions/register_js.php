<?php
function custom_child_scripts() {


	wp_enqueue_style(
		'custom_core_style', 
		CORE_URL . '/css/custom_core_style.css',
		array(),
		rand()
	);

	wp_enqueue_style(
		'adaptive', 
		CORE_URL .  '/css/adaptive.css',
		array('custom_core_style'),
		rand()
	);

	wp_enqueue_script(
	    'custom_core',
	    CORE_URL . '/js/custom_core.js',
        array('jquery'), 
        rand(),
        true  
	);

	wp_localize_script( 'custom_script', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
}
//add_action( 'wp_enqueue_scripts', 'custom_child_scripts' ); 

function custom_admin_theme_style() {
    wp_enqueue_style('custom-admin-style', CORE_URL .'/css/custom_admin_style.css', array(), rand());

	wp_enqueue_script(
	    'custom_admin_js',
	    CORE_URL . '/js/custom_admin.js',
        array('jquery'), 
        rand(),
        true  
	);
	wp_localize_script( 'custom_admin_js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}
add_action('admin_enqueue_scripts', 'custom_admin_theme_style');