<?php
/**
 * Options config
 *
 * @package    Logo_Carousel_Free
 * @subpackage Logo_Carousel_Free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

// Setting prefix.
$prefix = '_sp_lcpro_options';

// Create options.
SPLC::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Settings', 'logo-carousel-free' ),
		'menu_parent'      => 'edit.php?post_type=sp_logo_carousel',
		'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
		'menu_slug'        => 'lc_settings',
		'class'            => 'lcpro_setting_options',
		'ajax_save'        => true,
		'show_reset_all'   => false,
		'show_search'      => false,
		'show_all_options' => false,
		'show_footer'      => false,
		'show_bar_menu'    => false,
		'framework_title'  => __( 'Settings', 'logo-carousel-free' ),
	)
);


// Advanced Settings.
SPLC::createSection(
	$prefix,
	array(
		'id'     => 'advanced_settings',
		'title'  => __( 'Advanced', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon lc-icon-advanced"></i>',
		'fields' => array(
			array(
				'id'         => 'lcpro_data_remove',
				'type'       => 'checkbox',
				'title'      => __( 'Clean-up Data on Deletion', 'logo-carousel-free' ),
				'title_info' => __( 'Check this box if you would like Logo Carousel to completely remove all of its data when the plugin is deleted.', 'logo-carousel-free' ),
				'default'    => false,
			),
			array(
				'id'         => 'lcpro_google_fonts',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Google Fonts', 'logo-carousel-free' ),
				'text_on'    => __( 'Enqueued', 'logo-carousel-free' ),
				'text_off'   => __( 'Dequeued', 'logo-carousel-free' ),
				'text_width' => 110,
				'default'    => false,
			),
			array(
				'id'         => 'enable_logo_stats',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Logo Analytics', 'logo-carousel-free' ),
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div>',
					__( 'Logo Analytics', 'logo-carousel-free' ),
					__( 'If you turned off this Logo Analytics (performance tracking) option, neither tracking data will appear in Analytics nor be saved in the database.', 'logo-carousel-free' )
				),
				'text_width' => 74,
				'default'    => false,
			),
		),
	)
);

// Custom CSS.
SPLC::createSection(
	$prefix,
	array(
		'id'     => 'load_css_and_js',
		'title'  => __( 'Control Assets', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon fa fa-tasks"></i>',
		'fields' => array(
			array(
				'id'         => 'lcpro_fontawesome_css',
				'type'       => 'switcher',
				'title'      => __( 'FontAwesome CSS', 'logo-carousel-free' ),
				'default'    => true,
				'text_on'    => __( 'Enqueued', 'logo-carousel-free' ),
				'text_off'   => __( 'Enqueued', 'logo-carousel-free' ),
				'text_width' => 110,
			),
			array(
				'id'         => 'lcpro_swiper_css',
				'type'       => 'switcher',
				'title'      => __( 'Swiper CSS', 'logo-carousel-free' ),
				'default'    => true,
				'text_on'    => __( 'Enqueued', 'logo-carousel-free' ),
				'text_off'   => __( 'Enqueued', 'logo-carousel-free' ),
				'text_width' => 110,
			),
			array(
				'id'         => 'lcpro_swiper_js',
				'type'       => 'switcher',
				'title'      => __( 'Swiper JS', 'logo-carousel-free' ),
				'default'    => true,
				'text_on'    => __( 'Enqueued', 'logo-carousel-free' ),
				'text_off'   => __( 'Enqueued', 'logo-carousel-free' ),
				'text_width' => 110,
			),
		),
	)
);
// Custom CSS.
SPLC::createSection(
	$prefix,
	array(
		'id'     => 'custom_css_section',
		'title'  => __( 'Additional CSS', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon lc-icon-code"></i>',
		'fields' => array(
			array(
				'id'       => 'lcpro_custom_css',
				'type'     => 'code_editor',
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'theme' => 'mbo',
					'mode'  => 'css',
				),
				'title'    => __( 'Custom CSS', 'logo-carousel-free' ),
			),
		),
	)
);
// Custom CSS.
SPLC::createSection(
	$prefix,
	array(
		'title'  => __( 'License Key', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon lc-icon-key-01"></i>',
		'fields' => array(
			array(
				'id'   => 'license_key',
				'type' => 'license',
			),
		),
	)
);
