<?php

$validPage = array('gctl_subscribe_newsletter_page');
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
if(in_array($page, $validPage)) {
	wp_enqueue_style( 'bootstrap_min_css_plugin', plugins_url( '/assets/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'jquery_dataTables_min_css_plugin', plugins_url( 'assets/css/jquery.dataTables.min.css', __FILE__ ) );
	wp_enqueue_style( 'buttons_dataTables_min_plugin', plugins_url( 'assets/css/buttons.dataTables.min.css', __FILE__ ) );
	wp_enqueue_style( 'style_plugin_admin_panel_css_plugin', plugins_url( '/assets/css/style.plugin.admin.panel.css', __FILE__ ) );

	wp_enqueue_script( 'jquery_min_js_plugin', plugins_url( 'assets/js/jquery.min.js', __FILE__ ) );
	wp_enqueue_script( 'dataTables_min_js_plugin', plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__ ) );
	wp_enqueue_script( 'dataTables_buttons_min_js_plugin', plugins_url( 'assets/js/dataTables.buttons.min.js', __FILE__ ) );
	wp_enqueue_script( 'buttons_flash_min_js_plugin', plugins_url( 'assets/js/buttons.flash.min.js', __FILE__ ) );
	wp_enqueue_script( 'jszip_min_js_plugin', plugins_url( 'assets/js/jszip.min.js', __FILE__ ) );
	wp_enqueue_script( 'pdfmake_min_js_plugin', plugins_url( 'assets/js/pdfmake.min.js', __FILE__ ) );
	wp_enqueue_script( 'vfs_fonts_js_plugin', plugins_url( 'assets/js/vfs_fonts.js', __FILE__ ) );
	wp_enqueue_script( 'buttons_html5_min_js_plugin', plugins_url( 'assets/js/buttons.html5.min.js', __FILE__ ) );
	wp_enqueue_script( 'buttons_print_min_js_plugin', plugins_url( 'assets/js/buttons.print.min.js', __FILE__ ) );

	wp_enqueue_script( 'main_plugin_js_plugin', plugins_url( 'assets/js/main.plugin.js', __FILE__ ) );
}
