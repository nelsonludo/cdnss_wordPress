<?php
/**
 * Metabox config
 *
 * @package    Logo_Carousel_Free
 * @subpackage Logo_Carousel_Free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

$prefix = 'sp_lcp_shortcode_options';

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPLC::createMetabox(
	'splcp_live_preview',
	array(
		'title'           => __( 'Live Preview', 'logo-carousel-free' ),
		'post_type'       => 'sp_lc_shortcodes',
		'show_restore'    => false,
		'splcp_shortcode' => false,
		'context'         => 'normal',
	)
);

SPLC::createSection(
	'splcp_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

/**
 * View shortcode show metabox.
 *
 * @param string 'sp_lcp_shortcode_show' The metabox main Key.
 * @return void
 */
SPLC::createMetabox(
	'sp_lcp_shortcode_show',
	array(
		'title'            => __( 'How To Use', 'logo-carousel-free' ),
		'post_type'        => 'sp_lc_shortcodes',
		'context'          => 'side',
		'show_restore'     => false,
		'sp_lcp_shortcode' => false,
	)
);

SPLC::createSection(
	'sp_lcp_shortcode_show',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => 'manage_view',
				'class'     => 'sp_tpro-admin-sidebar',
			),
		),
	)
);

/**
 * Page builder supported metabox.
 *
 * @param string 'sp_lcp_builder_option' The metabox main Key.
 * @return void
 */
SPLC::createMetabox(
	'sp_lcp_builder_option',
	array(
		'title'            => __( 'Page Builders', 'logo-carousel-free' ),
		'post_type'        => 'sp_lc_shortcodes',
		'context'          => 'side',
		'show_restore'     => false,
		'sp_lcp_shortcode' => false,
	)
);

SPLC::createSection(
	'sp_lcp_builder_option',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => false,
				'class'     => 'sp_tpro-admin-sidebar',
			),
		),
	)
);

SPLC::createMetabox(
	'sp_lcp_pro_notice',
	array(
		'title'            => __( 'Unlock Pro Feature', 'logo-carousel-free' ),
		'post_type'        => array( 'sp_lc_shortcodes', 'sp_logo_carousel' ),
		'context'          => 'side',
		'show_restore'     => false,
		'sp_lcp_shortcode' => false,
	)
);

SPLC::createSection(
	'sp_lcp_pro_notice',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => 'pro_notice',
				'class'     => 'sp_tpro-admin-sidebar',
			),
		),
	)
);

/**
 * Layout metabox.
 *
 * @param string 'sp_lcp_layout_options' The metabox main Key.
 * @return void
 */
SPLC::createMetabox(
	'sp_lcp_layout_options',
	array(
		'title'            => __( 'Layout', 'logo-carousel-free' ),
		'post_type'        => 'sp_lc_shortcodes',
		'show_restore'     => false,
		'sp_lcp_shortcode' => false,
		'context'          => 'normal',
	)
);

SPLC::createSection(
	'sp_lcp_layout_options',
	array(
		'fields' => array(
			array(
				'type'    => 'heading',
				'image'   => SP_LC_URL . 'admin/assets/images/lc-logo.svg',
				'after'   => '<i class="fa fa-life-ring"></i> Support',
				'link'    => 'https://shapedplugin.com/support/?user=lite',
				'class'   => 'splogocarousel-admin-header',
				'version' => SP_LC_VERSION,
			),
			array(
				'id'      => 'lcp_layout',
				'class'   => 'lcp_layout active-sign',
				'type'    => 'layout_preset',
				'title'   => __( 'Layout Preset', 'logo-carousel-free' ),
				'options' => array(
					'carousel'   => array(
						'image'           => SP_LC_URL . 'admin/assets/images/carousel.svg',
						'text'            => __( 'Carousel', 'logo-carousel-free' ),
						'option_demo_url' => 'https://logocarousel.com/carousel/',
					),
					'grid'       => array(
						'image'           => SP_LC_URL . 'admin/assets/images/grid.svg',
						'text'            => __( 'Grid', 'logo-carousel-free' ),
						'option_demo_url' => 'https://logocarousel.com/grid/',
					),
					'multi_rows' => array(
						'image'           => SP_LC_URL . 'admin/assets/images/multi_rows.svg',
						'text'            => __( 'Multi Rows', 'logo-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://logocarousel.com/multi-row-carousel/',
					),
					'masonry'    => array(
						'image'           => SP_LC_URL . 'admin/assets/images/masonry.svg',
						'text'            => __( 'Masonry', 'logo-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://logocarousel.com/masonry/',
					),
					'filter'     => array(
						'image'           => SP_LC_URL . 'admin/assets/images/isotope.svg',
						'text'            => __( 'Isotope', 'logo-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://logocarousel.com/isotope/',
					),
					'list'       => array(
						'image'           => SP_LC_URL . 'admin/assets/images/list.svg',
						'text'            => __( 'List', 'logo-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://logocarousel.com/list/',
					),
					'inline'     => array(
						'image'           => SP_LC_URL . 'admin/assets/images/inline.svg',
						'text'            => __( 'Inline', 'logo-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://logocarousel.com/inline/',
					),
					'table'      => array(
						'image'           => SP_LC_URL . 'admin/assets/images/table.svg',
						'text'            => __( 'Table', 'logo-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://logocarousel.com/table/',
					),
				),
				'default' => 'carousel',
			),
			array(
				'id'         => 'lcp_logo_carousel_mode',
				'type'       => 'layout_preset',
				'title'      => __( 'Carousel Style', 'logo-carousel-free' ),
				'options'    => array(
					'standard' => array(
						'image' => SP_LC_URL . 'admin/assets/images/carousel-mode/standard.svg',
						'text'  => __( 'Standard', 'logo-carousel-free' ),
					),
					'center'   => array(
						'image'    => SP_LC_URL . 'admin/assets/images/carousel-mode/center.svg',
						'text'     => __( 'Center', 'logo-carousel-free' ),
						'pro_only' => true,
					),
					'ticker'   => array(
						'image'    => SP_LC_URL . 'admin/assets/images/carousel-mode/ticker.svg',
						'text'     => __( 'Ticker', 'logo-carousel-free' ),
						'pro_only' => true,
					),
				),
				'only_pro'   => true,
				'default'    => 'standard',
				'dependency' => array( 'lcp_layout', '==', 'carousel', true ),
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/carousel-modes/" target="_blank">%s</a></div>',
					__( 'Carousel Style', 'logo-carousel-free' ),
					__( 'This feature allows you to select the most suitable carousel style between Standard, Ticker (continuous scrolling), or Center.', 'logo-carousel-free' ),
					__( 'Live Demo', 'logo-carousel-free' )
				),
			),
			array(
				'id'         => 'lcp_layout_justified_mode',
				'type'       => 'layout_preset',
				'class'      => 'lcp_layout_justified_mode',
				'title'      => __( 'Justify Logo', 'logo-carousel-free' ),
				'options'    => array(
					'left'   => array(
						'image' => SP_LC_URL . 'admin/assets/images/justify_content_left.svg',
						'text'  => __( 'Left', 'logo-carousel-free' ),
					),
					'right'  => array(
						'image' => SP_LC_URL . 'admin/assets/images/Justify_content_right.svg',
						'text'  => __( 'Right', 'logo-carousel-free' ),
					),
					'center' => array(
						'image'    => SP_LC_URL . 'admin/assets/images/justify_content_center.svg',
						'text'     => __( 'Center', 'logo-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'left',
				'dependency' => array( 'lcp_layout', 'any', 'grid,inline', true ),
			),
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'lcp-live-filter-notice',
				'content' => sprintf(
					/* translators: %1$s: strong tag starts , %2$s: link tag starts %3$s: link tag and strong tag end . %4$s: another link tag starts %5$s: link tag ends. */
					__(
						'Want to unleash your creativity with %1$s10+ exclusive %2$slayouts%3$s and design freedom? %4$sGet Pro Now!%5$s',
						'logo-carousel-free'
					),
					'<strong class="lcp-pro-text">',
					'<a href="https://logocarousel.com/carousel/" target="_blank">',
					'</a></strong>',
					'<b><a href="https://logocarousel.com/pricing/?ref=1" target="_blank">',
					'</a></b>'
				),
			),
		),
	)
);

// -----------------------------------------
// Shortcode Generator Options.
// -----------------------------------------
SPLC::createMetabox(
	$prefix,
	array(
		'title'     => __( 'Shortcode Options', 'logo-carousel-free' ),
		'post_type' => 'sp_lc_shortcodes',
		'class'     => 'sp_logo_carousel_shortcode',
		'context'   => 'normal',
		'priority'  => 'default',
		'preview'   => true,
	)
);

// General Settings.
SPLC::createSection(
	$prefix,
	array(
		'title'  => __( 'General Settings', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon fa fa-cog"></i>',
		'fields' => array(
			array(
				'id'         => 'lcp_number_of_columns',
				'type'       => 'column',
				'title'      => __( 'Logo Columns', 'logo-carousel-free' ),
				'subtitle'   => __( 'Set number of columns in different devices.', 'logo-carousel-free' ),
				'title_info' => sprintf(
					/* translators: %1$s: icon and strong tag start, %2$s: strong tag ends, %3$s: icon and strong tag start %4$s: icon and strong tag start %5$s: icon and strong tag start %6$s: icon and strong tag start */
					__( '%1$sLarge Desktop%2$s - is larger than 1200px,%3$sDesktop%2$s - size is larger than 1024px,%4$sTablet%2$s - size is larger than 768,%5$sMobile Landscape%2$s- size is larger than 575px,%6$sMobile%2$s - size is smaller than 576px.', 'logo-carousel-free' ),
					'<i class="fa fa-television"></i> <strong>',
					'</strong>',
					'<br><i class="fa fa-desktop"></i> <strong>',
					'<br> <i class="fa fa-laptop"></i> <strong>',
					'<br> <i class="fa fa-tablet"></i> <strong>',
					'<br> <i class="fa fa-mobile"></i> <strong>'
				),
				'sanitize'   => 'splogocarousel_sanitize_number_array_field',
				'default'    => array(
					'lg_desktop'       => '5',
					'desktop'          => '4',
					'tablet'           => '3',
					'mobile_landscape' => '2',
					'mobile'           => '1',
				),
				'min'        => '1',
				'dependency' => array( 'lcp_layout', 'any', 'carousel,grid', true ),
			),
			array(
				'id'            => 'lcp_logo_margin',
				'type'          => 'spacing',
				'title'         => __( 'Space', 'logo-carousel-free' ),
				'subtitle'      => __( 'Set a margin or space between the logos.', 'logo-carousel-free' ),
				'units'         => array(
					__( 'px', 'logo-carousel-free' ),
				),
				'show_title'    => true,
				'all'           => true,
				'vertical'      => true,
				'all_icon'      => '<i class="fa fa-arrows-h" aria-hidden="true"></i>',
				'vertical_icon' => '<i class="fa fa-arrows-v" aria-hidden="true"></i>',
				'default'       => array(
					'all'      => '8',
					'vertical' => '8',
				),
				'title_info'    => '<div class="splogocarousel-img-tag"><img src="' . SPLC::include_plugin_url( 'assets/images/gap.svg' ) . '" alt="' . __( 'Space Between Logos', 'logo-carousel-free' ) . '"></div><div class="splogocarousel-info-label img">' . __( 'Space Between Logos', 'logo-carousel-free' ) . '</div>',
			),
			array(
				'id'         => 'lcp_display_logos_from',
				'class'      => 'lcp_display_logos_from',
				'type'       => 'select',
				'title'      => __( 'Filter Logos', 'logo-carousel-free' ),
				'subtitle'   => __( 'Select an option to display by filtering logos.', 'logo-carousel-free' ),
				'options'    => array(
					'latest'         => __( 'All', 'logo-carousel-free' ),
					'category'       => array(
						'text'     => __( 'Category (Pro)', 'logo-carousel-free' ),
						'pro_only' => true,
					),
					'specific_logos' => array(
						'text'     => __( 'Specific (Pro)', 'logo-carousel-free' ),
						'pro_only' => true,
					),
					'exclude_logos'  => array(
						'text'     => __( 'Exclude (Pro)', 'logo-carousel-free' ),
						'pro_only' => true,
					),
				),
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-docs" href="https://docs.shapedplugin.com/docs/logo-carousel-free/configurations/how-to-filter-logos/" target="_blank">%s</a></div>',
					__( 'Filter Logos', 'logo-carousel-free' ),
					__( 'This option allows you to choose how many logos are displayed by applying filters. You can choose to show all logos, filter by categories, or select specific logos.', 'logo-carousel-free' ),
					__( 'Open Docs', 'logo-carousel-free' )
				),
				'default'    => 'latest',
			),
			array(
				'id'         => 'lcp_number_of_total_items',
				'type'       => 'spinner',
				'class'      => 'lcp_spinner',
				'title'      => __( 'Limit', 'logo-carousel-free' ),
				'subtitle'   => __( 'Number of total logos to show.', 'logo-carousel-free' ),
				'title_info' => __( 'Leave it empty to show all logos.', 'logo-carousel-free' ),
				'default'    => ' ',
				'sanitize'   => 'splogocarousel_sanitize_number_field',
				'min'        => 1,
			),
			array(
				'id'     => 'lcp_click_action_groups',
				'class'  => 'lcp-click-action-type',
				'type'   => 'fieldset',
				'fields' => array(
					array(
						'id'         => 'lcp_link_type',
						'type'       => 'button_set',
						'class'      => 'lcp_link_type sp-lc-link-pro--only',
						'title'      => __( 'Click Action Type', 'logo-carousel-free' ),
						'subtitle'   => __( 'Set logo detail link type.', 'logo-carousel-free' ),
						'options'    => array(
							'external_link' => __( 'Link', 'logo-carousel-free' ),
							'popup'         => __( 'Popup', 'logo-carousel-free' ),
							'none'          => __( 'Disabled', 'logo-carousel-free' ),
						),
						'default'    => 'none',
						'only_pro'   => true,
						'title_info' => sprintf(
							'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-docs" href="https://docs.shapedplugin.com/docs/logo-carousel-free/configurations/how-to-select-click-action-type/" target="_blank">%s</a><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/popup-with-logo-details/" target="_blank">%s</a></div>',
							__( 'Click Action Type', 'logo-carousel-free' ),
							__( 'This feature allows you to choose between directing to a link, opening a popup, or disabling the link entirely.', 'logo-carousel-free' ),
							__( 'Open Docs', 'logo-carousel-free' ),
							__( 'Live Demo', 'logo-carousel-free' )
						),
					),
					array(
						'id'         => 'lcp_link_open_target',
						'class'      => 'lcp_link_open_target',
						'type'       => 'checkbox',
						'title'      => __( 'Open a New Tab', 'logo-carousel-free' ),
						'default'    => true,
						'dependency' => array( 'lcp_link_type', '==', 'external_link', true ),
					),
					array(
						'id'         => 'modal_type',
						'type'       => 'checkbox',
						'title'      => __( 'Multiple Popup with Nav', 'logo-carousel-free' ),
						'class'      => 'page_link_type_option',
						'default'    => false,
						'dependency' => array( 'lcp_link_type', '==', 'popup', true ),
					),
				),
			),
			array(
				'id'         => 'lcp_random_order',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Random Order', 'logo-carousel-free' ),
				'subtitle'   => __( 'Enable/Disable logo random order.', 'logo-carousel-free' ),
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'default'    => false,
				'text_width' => 100,
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div>',
					__( 'Random Order', 'logo-carousel-free' ),
					__( 'If you enable this option, logos will be displayed randomly without following a particular order or sequence.', 'logo-carousel-free' )
				),

			),
			array(
				'id'       => 'lcp_item_order_by',
				'type'     => 'select',
				'class'    => 'order_by_pro',
				'title'    => __( 'Order By', 'logo-carousel-free' ),
				'subtitle' => __( 'Select an order by option.', 'logo-carousel-free' ),
				'options'  => array(
					'title'      => __( 'Title', 'logo-carousel-free' ),
					'date'       => __( 'Date', 'logo-carousel-free' ),
					'menu_order' => __( 'Drag & Drop (Pro)', 'logo-carousel-free' ),
				),
				'default'  => 'date',
			),
			array(
				'id'       => 'lcp_item_order',
				'type'     => 'select',
				'title'    => __( 'Order', 'logo-carousel-free' ),
				'subtitle' => __( 'Select an order option.', 'logo-carousel-free' ),
				'options'  => array(
					'ASC'  => __( 'Ascending', 'logo-carousel-free' ),
					'DESC' => __( 'Descending', 'logo-carousel-free' ),
				),
				'default'  => 'ASC',
			),
			array(
				'id'         => 'lcp_scheduler',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Scheduling', 'logo-carousel-free' ),
				'subtitle'   => __( 'Enable to schedule the display of logos at specific time intervals.', 'logo-carousel-free' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'text_width' => 100,
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/scheduling/" target="_blank">%s</a></div>',
					__( 'Scheduling', 'logo-carousel-free' ),
					__( 'This option allows you to schedule the display of logos at specific time intervals easily.', 'logo-carousel-free' ),
					__( 'Live Demo', 'logo-carousel-free' )
				),
			),
			array(
				'id'         => 'lcp_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'logo-carousel-free' ),
				'subtitle'   => __( 'Carousel will be hidden until page load completed.', 'logo-carousel-free' ),
				'default'    => true,
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'text_width' => 100,
			),
			array(
				'id'      => 'lcp_section_ajax_live_filter',
				'class'   => 'lcp_only_pro',
				'type'    => 'subheading',
				'content' => __( 'Ajax Logo Live Filters (Pro)', 'logo-carousel-free' ),
			),
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'lcp-live-filter-notice',
				'content' => sprintf(
					/* translators: %1$s: bold tag starts, %2$s: bold tag ends, %3$s: link tag starts %4$s: link tag ends %5$s: another link tag start %6$s: link tag ends */
					__( 'To allow your visitors to filter logos by %1$sCategories%2$s, %3$sA-Z filter%2$s, and Ajax Search on the frontend, %4$sUpgrade To Pro!%2$s', 'logo-carousel-free' ),
					'<a href="https://logocarousel.com/ajax-logo-live-filters/" target="_blank"><strong>',
					'</strong></a>',
					'<a href="https://logocarousel.com/ajax-logo-live-filters/#sp-lcpro-id-1645" target="_blank"><strong>',
					'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><strong>'
				),
			),
			array(
				'id'         => 'logo_live_filter',
				'class'      => 'logo_live_filter',
				'type'       => 'switcher',
				'only_pro'   => true,
				'title'      => __( 'Ajax Logo Live Filters', 'logo-carousel-free' ),
				'subtitle'   => __( 'Enable Ajax live filter to navigate through the logo categories and A-Z alphabetical filters.', 'logo-carousel-free' ),
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'default'    => false,
				'text_width' => 100,
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-docs" href="https://docs.shapedplugin.com/docs/logo-carousel-free/configurations/how-to-enable-ajax-logo-live-filters/" target="_blank">%s</a><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/ajax-logo-live-filters/" target="_blank">%s</a></div>',
					__( 'Ajax Logo Live Filters (Pro)', 'logo-carousel-free' ),
					__( 'Make your visitor\'s logo search easier by enabling the Ajax live filter. This powerful feature allows effortless navigation through logo categories, while the A-Z alphabetical filters ensure that they can easily find exactly what they\'re looking for.', 'logo-carousel-free' ),
					__( 'Open Docs', 'logo-carousel-free' ),
					__( 'Live Demo', 'logo-carousel-free' )
				),
				'dependency' => array( 'lcp_layout', '!=', 'filter', true ),
			),
			array(
				'id'         => 'live_filter_type',
				'class'      => 'lcp_pro_option',
				'type'       => 'button_set',
				'only_pro'   => true,
				'title'      => __( 'Live Filter Type', 'logo-carousel-free' ),
				'subtitle'   => __( 'Select a ajax live filter type.', 'logo-carousel-free' ),
				'options'    => array(
					'category_filter'        => __( 'Category', 'logo-carousel-free' ),
					'az_filter'              => __( 'A-Z Filter', 'logo-carousel-free' ),
					'category_and_az_filter' => __( 'Category & A-Z', 'logo-carousel-free' ),
				),
				'default'    => 'category_filter',
				'dependency' => array( 'logo_live_filter', '==', 'true', true ),
			),
			array(
				'id'         => 'lcp_filter_button_type',
				'class'      => 'lcp_pro_option',
				'type'       => 'layout_preset',
				'only_pro'   => true,
				'title'      => __( 'Filter Type', 'logo-carousel-free' ),
				'subtitle'   => __( 'Choose a filter button type.', 'logo-carousel-free' ),
				'options'    => array(
					'button'   => array(
						'image' => SP_LC_URL . 'admin/assets/images/filter-type/button.svg',
						'text'  => __( 'Button', 'logo-carousel-free' ),
					),
					'dropdown' => array(
						'image' => SP_LC_URL . 'admin/assets/images/filter-type/dropdown.svg',
						'text'  => __( 'Dropdown', 'logo-carousel-free' ),
					),
				),
				'default'    => 'button',
				'dependency' => array( 'logo_live_filter', '==', 'true', true ),
			),
			array(
				'id'         => 'lcp_all_tab',
				'type'       => 'switcher',
				'only_pro'   => true,
				'class'      => 'ajax-filter-options lcp_only_pro',
				'title'      => __( '"All" Tab', 'logo-carousel-free' ),
				'subtitle'   => __( 'Show/Hide "All" tab.', 'logo-carousel-free' ),
				'default'    => true,
				'text_on'    => __( 'Show', 'logo-carousel-free' ),
				'text_off'   => __( 'Hide', 'logo-carousel-free' ),
				'text_width' => 80,
				'dependency' => array( 'logo_live_filter', '==', 'true', true ),
			),
			array(
				'id'         => 'lcp_all_tab_text',
				'type'       => 'text',
				'only_pro'   => true,
				'class'      => 'lcp_all_tab_text ajax-filter-options lcp_pro_option',
				'title'      => __( '"All" Tab Label', 'logo-carousel-free' ),
				'subtitle'   => __( 'Set "All" tab label text.', 'logo-carousel-free' ),
				'default'    => 'All',
				'dependency' => array( 'logo_live_filter', '==', 'true', true ),
			),
			array(
				'id'         => 'filter_btn_align',
				'class'      => 'filter_align ajax-filter-options lcp_pro_option',
				'type'       => 'button_set',
				'only_pro'   => true,
				'title'      => __( 'Alignment', 'logo-carousel-free' ),
				'subtitle'   => __( 'Choose filter button alignment.', 'logo-carousel-free' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="Left"></i>',
					'center' => '<i class="fa fa-align-center" title="Center"></i>',
					'right'  => '<i class="fa fa-align-right" title="Right"></i>',
				),
				'default'    => 'center',
				'dependency' => array( 'logo_live_filter', '==', 'true', true ),
			),
			array(
				'id'         => 'lcp_filter_cat_color',
				'type'       => 'color_group',
				'only_pro'   => true,
				'title'      => __( 'Color', 'logo-carousel-free' ),
				'subtitle'   => __( 'Set isotope filter category color.', 'logo-carousel-free' ),
				'class'      => 'ajax-filter-options lcp_pro_option',
				'options'    => array(
					'color1' => __( 'Color', 'logo-carousel-free' ),
					'color2' => __( 'Hover Color', 'logo-carousel-free' ),
					'color3' => __( 'Background', 'logo-carousel-free' ),
					'color4' => __( 'Hover Background', 'logo-carousel-free' ),
				),
				'default'    => array(
					'color1' => '#444444',
					'color2' => '#ffffff',
					'color3' => '#e2e2e2',
					'color4' => '#16a08b',
				),
				'dependency' => array( 'logo_live_filter', '==', 'true', true ),
			),
			array(
				'id'          => 'lcp_filter_cat_border',
				'type'        => 'border',
				'only_pro'    => true,
				'class'       => 'ajax-filter-options lcp_pro_option',
				'title'       => __( 'Border', 'logo-carousel-free' ),
				'subtitle'    => __( 'Set isotope filter category border.', 'logo-carousel-free' ),
				'default'     => array(
					'all'         => '0',
					'style'       => 'solid',
					'color'       => '#444444',
					'hover_color' => '#16a08b',
					'radius'      => '2',
				),
				'all'         => true,
				'hover_color' => true,
				'radius'      => true,
				'dependency'  => array( 'logo_live_filter', '==', 'true', true ),
			),
		),
	)
);

// Style Settings.
SPLC::createSection(
	$prefix,
	array(
		'title'  => __( 'Display Settings', 'logo-carousel-free' ),
		'icon'   => '<span class="dashicons dashicons-screenoptions"></span>',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'lcp-display-tabs',
				'tabs'  => array(
					array(
						'title'  => __( 'Basic Styles', 'logo-carousel-free' ),
						'icon'   => '<span><i class="fa fa-paint-brush"></i></span>',
						'fields' => array(
							array(
								'id'         => 'lcp_section_title',
								'type'       => 'switcher',
								'title'      => __( 'Section Title', 'logo-carousel-free' ),
								'subtitle'   => __( 'Display logo section title.', 'logo-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'text_width' => 80,
							),
							array(
								'id'         => 'logo_search',
								'type'       => 'switcher',
								'class'      => 'lcp_only_pro',
								'title'      => __( 'Ajax Logo Search', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable ajax search for logo.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'default'    => false,
								'text_width' => 100,
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/ajax-logo-search/" target="_blank">%s</a></div>',
									__( 'Ajax Logo Search', 'logo-carousel-free' ),
									__( 'With this feature, your website\'s visitors can easily search for the exact logo they are looking for. It provides a convenient way for them to find what they need quickly.', 'logo-carousel-free' ),
									__( 'Live Demo', 'logo-carousel-free' )
								),
							),
							array(
								'id'         => 'lcp_content_position',
								'class'      => 'lcp_content_position active-sign',
								'type'       => 'layout_preset',
								'title'      => __( 'Logo Position', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose your logo position to display the logos.', 'logo-carousel-free' ),
								'options'    => array(
									'default'     => array(
										'image' => SP_LC_URL . 'admin/assets/images/logo-position/default.svg',
										'text'  => __( 'Default', 'logo-carousel-free' ),
									),
									'top-logo'    => array(
										'image'    => SP_LC_URL . 'admin/assets/images/logo-position/top.svg',
										'text'     => __( 'Top', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'bottom-logo' => array(
										'image'    => SP_LC_URL . 'admin/assets/images/logo-position/bottom.svg',
										'text'     => __( 'Bottom', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'left-logo'   => array(
										'image'    => SP_LC_URL . 'admin/assets/images/logo-position/left.svg',
										'text'     => __( 'Left', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'right-logo'  => array(
										'image'    => SP_LC_URL . 'admin/assets/images/logo-position/right.svg',
										'text'     => __( 'Right', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'overlay'     => array(
										'image'    => SP_LC_URL . 'admin/assets/images/logo-position/overlay.svg',
										'text'     => __( 'Overlay', 'logo-carousel-free' ),
										'pro_only' => true,
									),
								),
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/5-logo-caption-positions/" target="_blank">%s</a></div>',
									__( 'Logo Position', 'logo-carousel-free' ),
									__( 'This feature allows you to select the placement of logos.', 'logo-carousel-free' ),
									__( 'Live Demo', 'logo-carousel-free' )
								),
								'desc'       => sprintf(
									/* translators: %1$s: bold tag starts, %2$s: bold tag ends, %3$s: link tag starts %4$s: link tag ends %5$s: another link tag start %6$s: link tag ends */
									__( 'To create a %1$sprofessional-looking logo showcase%2$s with flexible %3$scontent positions%4$s and boost engagement, %5$sUpgrade To Pro!%6$s', 'logo-carousel-free' ),
									'<strong class="lcp-pro-text">',
									'</strong>',
									'<a href="https://logocarousel.com/5-logo-caption-positions/" target="_blank">',
									'</a>',
									'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
								'default'    => 'default',
							),
							array(
								'id'         => 'lcp_grid_inline_vertical_alignment',
								'type'       => 'layout_preset',
								'class'      => 'order_by_pro lcp-grid-inline-vertical-alignment',
								'title'      => __( 'Vertical Alignment', 'logo-carousel-free' ),
								'subtitle'   => __( 'Select vertical alignment type.', 'logo-carousel-free' ),
								'options'    => array(
									'middle' => array(
										'image' => SP_LC_URL . 'admin/assets/images/vertical-alignments/middle.svg',
										'text'  => __( 'Middle', 'logo-carousel-free' ),
									),
									'bottom' => array(
										'image' => SP_LC_URL . 'admin/assets/images/vertical-alignments/bottom.svg',
										'text'  => __( 'Bottom', 'logo-carousel-free' ),
									),
									'top'    => array(
										'image' => SP_LC_URL . 'admin/assets/images/vertical-alignments/top.svg',
										'text'  => __( 'Top', 'logo-carousel-free' ),
									),
								),
								'default'    => 'middle',
								'dependency' => array( 'lcp_logo_image', '==', 'true', true ),
							),
							array(
								'id'         => 'lcp_logo_title',
								'type'       => 'switcher',
								'title'      => __( 'Logo Title', 'logo-carousel-free' ),
								'subtitle'   => __( 'Show/Hide logo title.', 'logo-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'text_width' => 80,
								'class'      => 'lcp_only_pro',
							),
							array(
								'id'         => 'lcp_logo_description',
								'type'       => 'switcher',
								'title'      => __( 'Description', 'logo-carousel-free' ),
								'subtitle'   => __( 'Show/Hide the logo description.', 'logo-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'text_width' => 80,
								'class'      => 'lcp_only_pro',
							),
							array(
								'id'         => 'lcp_description_type',
								'type'       => 'button_set',
								'title'      => __( 'Content Display Type', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose the description display type.', 'logo-carousel-free' ),
								'radio'      => true,
								'options'    => array(
									'description'       => __( 'Full', 'logo-carousel-free' ),
									'description_limit' => __( 'Limit', 'logo-carousel-free' ),
								),
								'attributes' => array(
									'data-depend-id' => 'lcp_description_type',
								),
								'default'    => 'description_limit',
								'class'      => 'lcp_pro_option',
							),
							array(
								'id'       => 'lcp_description_words_limit',
								'type'     => 'spinner',
								'title'    => __( 'Words Limit', 'logo-carousel-free' ),
								'subtitle' => __( 'Set description words limit.', 'logo-carousel-free' ),
								'default'  => 30,
								'min'      => 0,
								'max'      => 1000,
								'class'    => 'lcp_pro_option',
							),
							array(
								'id'         => 'lcp_description_read_more',
								'type'       => 'switcher',
								'title'      => __( 'Read More Button', 'logo-carousel-free' ),
								'subtitle'   => __( 'Show/Hide description read more button.', 'logo-carousel-free' ),
								'default'    => true,
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'text_width' => 80,
								'class'      => 'lcp_only_pro',
							),
						),
					),
					array(
						'title'  => __( 'Tooltips', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M5 6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H5z"/><path fill-rule="evenodd" d="M6.867 14.8c.267.333.667.533 1.133.533.133 0 .333 0 .533-.067.067 0 .2-.067.267-.133.086-.043.144-.114.211-.195l.123-.139 1.333-1.667s.067 0 .067-.067L10.6 13a.95.95 0 0 1 .8-.4h2.333C15 12.6 16 11.6 16 10.333v-7.4c0-1.267-1-2.267-2.267-2.267h-11.8l-.01.003H1.5A1.5 1.5 0 0 0 0 2.17v8.097.783a1.5 1.5 0 0 0 1.5 1.5H3a1 1 0 0 0 .183-.017H4.6a.95.95 0 0 1 .8.4l.067.133.067.067L6.867 14.8zm-4.923-3.493c.052-.009.092.003.127.014a.22.22 0 0 0 .062.013H4.6a2.21 2.21 0 0 1 1.733.867L8 14.267 9.667 12.2a2.21 2.21 0 0 1 1.733-.867h2.333c.2 0 .4-.067.6-.2.267-.2.467-.533.467-.867V2.933a1.03 1.03 0 0 0-1-1H2.267l-.003-.003H2.2a1 1 0 0 0-.332.057c-.114.051-.216.104-.304.172a1 1 0 0 0-.363.735l-.001.039v7.403c0 .238.095.476.242.658a1 1 0 0 0 .502.315z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'content' => sprintf(
									/* translators: %1$s: strong tag starts, %2$s: link tag starts, %3$s: link tag  and bold tag ended %4$s: another link tag start %5$s: link tag ends */
									__( 'Want to enhance your users\' experience with %1$seye-catching %2$slogo tooltips%3$s? %4$sUpgrade To Pro!%5$s', 'logo-carousel-free' ),
									'<strong class="lcp-pro-text">',
									'<a href="https://logocarousel.com/active-tooltips/" target="_blank">',
									'</a></strong>',
									'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'lcp_logo_tooltip',
								'type'       => 'switcher',
								'class'      => 'lcp_only_pro',
								'title'      => __( 'Tooltip', 'logo-carousel-free' ),
								'subtitle'   => __( 'Show/Hide logo tooltip on hover.', 'logo-carousel-free' ),
								'default'    => true,
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'text_width' => 80,
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/active-tooltips/" target="_blank">%s</a></div>',
									__( 'Tooltip', 'logo-carousel-free' ),
									__( 'This feature enables your website visitors to receive information about logos by simply hovering over them.', 'logo-carousel-free' ),
									__( 'Live Demo', 'logo-carousel-free' )
								),
							),
							array(
								'id'         => 'lcp_logo_tooltip_position',
								'type'       => 'layout_preset',
								'class'      => 'lcp_pro_option active-sign',
								'title'      => __( 'Position', 'logo-carousel-free' ),
								'subtitle'   => __( 'Select the tooltip position.', 'logo-carousel-free' ),
								'options'    => array(
									'top'    => array(
										'image' => SP_LC_URL . 'admin/assets/images/tooltip-position/top.svg',
										'text'  => __( 'Top', 'logo-carousel-free' ),
									),
									'bottom' => array(
										'image' => SP_LC_URL . 'admin/assets/images/tooltip-position/bottom.svg',
										'text'  => __( 'Bottom', 'logo-carousel-free' ),
									),
									'left'   => array(
										'image' => SP_LC_URL . 'admin/assets/images/tooltip-position/left.svg',
										'text'  => __( 'Left', 'logo-carousel-free' ),
									),
									'right'  => array(
										'image' => SP_LC_URL . 'admin/assets/images/tooltip-position/right.svg',
										'text'  => __( 'Right', 'logo-carousel-free' ),
									),
								),
								'default'    => 'top',
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'         => 'lcp_logo_tooltip_width',
								'type'       => 'spinner',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Tooltip Width', 'logo-carousel-free' ),
								'subtitle'   => __( 'Maximum width of the tooltip.', 'logo-carousel-free' ),
								'default'    => '220',
								'unit'       => __( 'px', 'logo-carousel-free' ),
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
								'min'        => 0,
								'title_info' => '<div class="splogocarousel-img-tag"><img src="' . SPLC::include_plugin_url( 'assets/images/tooltip_max_width.svg' ) . '" alt="' . __( 'Tooltip Max Width', 'logo-carousel-free' ) . '"></div><div class="splogocarousel-info-label img">' . __( 'Tooltip Max Width', 'logo-carousel-free' ) . '</div>',
							),
							array(
								'id'         => 'lcp_logo_speech_bubble_arrow',
								'type'       => 'switcher',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Speech Bubble Arrow', 'logo-carousel-free' ),
								'default'    => true,
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'text_width' => 80,
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'         => 'lcp_logo_tooltip_distance',
								'type'       => 'slider',
								'class'      => 'sp-lc-opacity lcp_logo_ranger lcp_pro_option',
								'title'      => __( 'Distance', 'logo-carousel-free' ),
								'subtitle'   => __( 'The distance between the origin and tooltip.', 'logo-carousel-free' ),
								'step'       => 1,
								'min'        => 1,
								'max'        => 500,
								'default'    => 400,
								'only_pro'   => true,
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'         => 'lcp_tooltip_content_source',
								'type'       => 'select',
								'title'      => __( 'Tooltip Content Source', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose an content source for the tooltip.', 'logo-carousel-free' ),
								'options'    => array(
									'title'       => __( 'Logo Title', 'logo-carousel-free' ),
									'custom_text' => __( 'Custom Tooltip Text', 'logo-carousel-free' ),
									'desc'        => __( 'Description', 'logo-carousel-free' ),
								),
								'default'    => 'title',
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'         => 'lcp_logo_tooltip_effect',
								'type'       => 'select',
								'title'      => __( 'Tooltip Effect', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose an effect for the tooltip.', 'logo-carousel-free' ),
								'options'    => array(
									'grow'  => __( 'Grow', 'logo-carousel-free' ),
									'fade'  => __( 'Fade', 'logo-carousel-free' ),
									'swing' => __( 'Swing', 'logo-carousel-free' ),
									'slide' => __( 'Slide', 'logo-carousel-free' ),
									'fall'  => __( 'Fall', 'logo-carousel-free' ),
									'none'  => __( 'None', 'logo-carousel-free' ),
								),
								'default'    => 'grow',
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'       => 'lcp_logo_tooltip_visibility',
								'type'     => 'button_set',
								'class'    => 'lcp_pro_option lcp_link_type',
								'title'    => __( 'Tooltip Visibility', 'logo-carousel-free' ),
								'subtitle' => __( 'Choose tooltip visibility.', 'logo-carousel-free' ),
								'options'  => array(
									'hover' => __( 'Mouseover', 'logo-carousel-free' ),
									'click' => __( 'On Click', 'logo-carousel-free' ),
								),
								'default'  => 'hover',
							),
							array(
								'id'         => 'lcp_logo_tooltip_color',
								'type'       => 'color_group',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Tooltip Color', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set tooltip color.', 'logo-carousel-free' ),
								'options'    => array(
									'color1' => __( 'Color', 'logo-carousel-free' ),
									'color2' => __( 'Background', 'logo-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#ffffff',
									'color2' => '#000000',
								),
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'          => 'lcp_logo_tooltip_border',
								'type'        => 'border',
								'class'       => 'lcp_pro_option',
								'title'       => __( 'Border', 'logo-carousel-free' ),
								'subtitle'    => __( 'Set border for tooltip.', 'logo-carousel-free' ),
								'all'         => true,
								'default'     => array(
									'all'         => '1',
									'style'       => 'solid',
									'color'       => '#000000',
									'hover_color' => '#16a08b',
								),
								'hover_color' => false,
								'dependency'  => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'         => 'lcp_logo_tooltip_border_radius',
								'type'       => 'spacing',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Border Radius', 'logo-carousel-free' ),
								'all'        => true,
								'units'      => array(
									__( 'px', 'logo-carousel-free' ),
								),
								'subtitle'   => __( 'Set border radius for tooltip.', 'logo-carousel-free' ),
								'default'    => array(
									'all' => '2',
								),
								'min'        => 0,
								'dependency' => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
							array(
								'id'          => 'lcp_logo_tooltip_padding',
								'type'        => 'spacing',
								'class'       => 'lcp_pro_option',
								'title'       => __( 'Padding', 'logo-carousel-free' ),
								'subtitle'    => __( 'Set tooltip custom padding.', 'logo-carousel-free' ),
								'output_mode' => 'padding', // or margin, relative
								'default'     => array(
									'top'    => '6',
									'right'  => '14',
									'bottom' => '6',
									'left'   => '14',
									'unit'   => 'px',
								),
								'dependency'  => array( 'lcp_logo_tooltip', '==', 'true', true ),
							),
						),
					),
					array(
						'title'  => __( 'BG, Border & Shadow', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M1.2 1.3c0-.046.015-.067.024-.076s.03-.024.076-.024h1.108V0H1.3A1.28 1.28 0 0 0 0 1.3v1.117h1.2V1.3zm3.425-.1h2.217V0H4.625v1.2zm4.433 0h2.217V0H9.058v1.2zm4.434 0H14.6c.046 0 .067.015.076.024s.024.03.024.076v1.108h1.2V1.3A1.28 1.28 0 0 0 14.6 0h-1.108v1.2zM14.7 4.625v2.217h1.2V4.625h-1.2zM1.2 6.883V4.65H0v2.233h1.2zm13.5 2.175v2.217h1.2V9.058h-1.2zM1.2 11.35V9.117H0v2.233h1.2zm13.5 2.142v1.168l.012.058a.32.32 0 0 1 .007.081l-1.136.001V16H14.7c.35 0 .708-.134.952-.433.234-.286.306-.652.248-1.02v-1.056h-1.2zM1.2 14.7v-1.117H0V14.7A1.28 1.28 0 0 0 1.3 16h1.117v-1.2H1.3c-.046 0-.067-.015-.076-.024s-.024-.03-.024-.076zm5.683.1H4.65V16h2.233v-1.2zm4.467 0H9.117V16h2.233v-1.2zM4.38 12h7.283c.217 0 .38-.162.326-.432v-7.19a.37.37 0 0 0-.38-.378H4.38a.37.37 0 0 0-.38.378v7.243a.37.37 0 0 0 .38.378z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								/* translators: %1$s: bold and link tags start, %2$s: link and bold tags ended, %3$s: link tag starts %4$s: link tag ends */
								'content' => sprintf( __( 'Want to make your logo showcase %1$svisually impressive%2$s? %3$sUpgrade To Pro!%4$s', 'logo-carousel-free' ), '<strong class="lcp-pro-text"><a href="https://logocarousel.com/box-highlight-style/" target="_blank">', '</a></strong>', '<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>', '</b></a>' ),
							),
							array(
								'id'       => 'lcp_logo_bg_type',
								'type'     => 'button_set',
								'class'    => 'sp-lc-pro-only',
								'title'    => __( 'Logo Background Type', 'logo-carousel-free' ),
								'subtitle' => __( 'Choose a logo background type.', 'logo-carousel-free' ),
								'options'  => array(
									'none'     => __( 'None', 'logo-carousel-free' ),
									'solid'    => __( 'Solid', 'logo-carousel-free' ),
									'gradient' => __( 'Gradient', 'logo-carousel-free' ),
								),
								'default'  => 'none',
							),
							array(
								'id'          => 'lcp_logo_border',
								'type'        => 'border',
								'title'       => __( 'Logo Border', 'logo-carousel-free' ),
								'subtitle'    => __( 'Set border for logo image.', 'logo-carousel-free' ),
								'all'         => true,
								'default'     => array(
									'all'         => '1',
									'style'       => 'solid',
									'color'       => '#dddddd',
									'hover_color' => '#16a08b',
								),
								'hover_color' => true,
							),
							array(
								'id'         => 'lcp_logo_border_radius',
								'type'       => 'spacing',
								'title'      => __( 'Border Radius', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set logo border radius.', 'logo-carousel-free' ),
								'all'        => true,
								'sanitize'   => 'splogocarousel_sanitize_number_array_field',
								'units'      => array(
									'px',
									'%',
								),
								'default'    => array(
									'all'  => 0,
									'unit' => 'px',
								),
								'all_icon'   => '<i class="fa fa-arrows-alt"></i>',
								'dependency' => array( 'lcp_layout', '!=', 'inline', true ),
							),
							array(
								'id'         => 'lcp_logo_shadow_type',
								'type'       => 'layout_preset',
								'class'      => 'sp-lc-pro-only active-sign',
								'title'      => __( 'BoxShadow', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set boxshadow for the logo.', 'logo-carousel-free' ),
								'options'    => array(
									'shadow_inset'  => array(
										'image'    => SP_LC_URL . 'admin/assets/images/boxshadow-type/inset.svg',
										'text'     => __( 'Inset', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'shadow_outset' => array(
										'image'    => SP_LC_URL . 'admin/assets/images/boxshadow-type/outset.svg',
										'text'     => __( 'Outset', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'off'           => array(
										'image' => SP_LC_URL . 'admin/assets/images/boxshadow-type/none.svg',
										'text'  => __( 'None', 'logo-carousel-free' ),
									),
								),
								'default'    => 'off',
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/box-highlight-style/" target="_blank">%s</a></div>',
									__( 'BoxShadow', 'logo-carousel-free' ),
									__( 'The BoxShadow option allows you to apply a box shadow effect to logos, providing a way to enhance their aesthetics with shadow effects.', 'logo-carousel-free' ),
									__( 'Live Demo', 'logo-carousel-free' )
								),
							),
						),
					),
					array(
						'title'  => __( 'Ajax Pagination', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><path d="M3.997 1.664c0-.297-.159-.573-.416-.721a.83.83 0 0 0-.833 0c-.257.149-.416.424-.416.721v3.333c.017.12.053.237.107.347l.033.067a.71.71 0 0 0 .153.227c.039.037.081.071.127.1.02.003.04.003.06 0a.58.58 0 0 0 .16.06.44.44 0 0 0 .16.033h.06 3.14c.297 0 .573-.159.721-.416a.83.83 0 0 0 0-.833c-.149-.257-.424-.416-.721-.416H5.059c.867-.665 1.933-1.017 3.027-1a4.84 4.84 0 0 1 2.992 1.099c.844.693 1.429 1.652 1.66 2.721s.092 2.183-.392 3.163a4.84 4.84 0 0 1-2.273 2.233c-.988.467-2.105.585-3.169.336a4.84 4.84 0 0 1-3.736-4.719c0-.297-.159-.573-.416-.721a.83.83 0 0 0-.833 0c-.257.149-.416.424-.416.721-.001 1.468.496 2.893 1.408 4.044a6.5 6.5 0 0 0 3.617 2.291c1.431.333 2.931.172 4.257-.457a6.5 6.5 0 0 0 3.049-3.007c.648-1.317.831-2.816.517-4.251a6.5 6.5 0 0 0-2.239-3.651c-1.139-.929-2.557-1.445-4.025-1.465s-2.9.457-4.063 1.355V1.664h-.027z" fill="#343434"/></svg></span>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'content' => sprintf(
									/* translators: %1$s: strong and link tags start, %2$s: link and strong tags end, %3$s: strong tag starts %4$s: strong tag ends %5$s: another link tag start %6$s: link tag ends */
									__( 'Do you want to enhance your %1$slogo pagination%2$s experience with %3$s10+ advanced%4$s settings? %5$sUpgrade To Pro!%6$s', 'logo-carousel-free' ),
									'<strong><a href="https://logocarousel.com/multiple-ajax-paginations/" target="_blank">',
									'</a></strong>',
									'<strong class="lcp-pro-text">',
									'</strong>',
									'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'lcp_pagination',
								'type'       => 'switcher',
								'class'      => 'lcp_only_pro',
								'title'      => __( 'Pagination', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable pagination.', 'logo-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 96,
							),
							array(
								'id'         => 'lcp_pagination_type',
								'type'       => 'radio',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Pagination Type', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose a pagination type.', 'logo-carousel-free' ),
								'options'    => array(
									'ajax_number'     => __( 'Ajax Number Pagination', 'logo-carousel-free' ),
									'ajax_load_more'  => __( 'Load More Button (Ajax)', 'logo-carousel-free' ),
									'infinite_scroll' => __( 'Infinite Scroll (Ajax)', 'logo-carousel-free' ),
									'no_ajax'         => __( 'No Ajax (Normal Pagination)', 'logo-carousel-free' ),
								),
								'default'    => 'ajax_load_more',
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/multiple-ajax-paginations/" target="_blank">%s</a></div>',
									__( 'Pagination Type', 'logo-carousel-free' ),
									__( 'This option lets you choose the best pagination style for your website visitors.', 'logo-carousel-free' ),
									__( 'Live Demo', 'logo-carousel-free' )
								),
							),
							array(
								'id'       => 'lcp_number_of_pagination',
								'type'     => 'spinner',
								'title'    => __( 'Logo(s) To Show Per Page/Click', 'logo-carousel-free' ),
								'class'    => 'lcp_pro_option',
								'subtitle' => __( 'Set number of logo(s) to show per page or click.', 'logo-carousel-free' ),
								'default'  => '15',
								'min'      => 1,
								'max'      => 1000,
							),
							array(
								'id'       => 'lcp_show_per_click',
								'type'     => 'spinner',
								'title'    => __( 'Logo(s) To Show Per Click', 'logo-carousel-free' ),
								'class'    => 'lcp_pro_option',
								'subtitle' => __( 'Set number of logo(s) to show per click.', 'logo-carousel-free' ),
								'default'  => '5',
								'min'      => 1,
								'max'      => 1000,
							),
							array(
								'id'       => 'load_more_label',
								'type'     => 'text',
								'title'    => __( 'Load More Button Label', 'logo-carousel-free' ),
								'subtitle' => __( 'Change load more button label text.', 'logo-carousel-free' ),
								'default'  => 'Load More',
								'class'    => 'lcp_pro_option',
							),
							array(
								'id'         => 'lcp_pagination_alignment',
								'type'       => 'button_set',
								'title'      => __( 'Alignment', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose pagination alignment.', 'logo-carousel-free' ),
								'options'    => array(
									'left'   => '<i class="fa fa-align-left" title="Left"></i>',
									'center' => '<i class="fa fa-align-center" title="Center"></i>',
									'right'  => '<i class="fa fa-align-right" title="Right"></i>',
								),
								'class'      => 'lcp_pro_option',
								'default'    => 'center',
								'dependency' => array( 'lcp_layout|lcp_pagination', 'any|==', 'list,inline,grid,masonry,filter|true', true ),
							),
							array(
								'id'       => 'lcp_pagination_margin',
								'type'     => 'spacing',
								'title'    => __( 'Margin', 'logo-carousel-free' ),
								'subtitle' => __( 'Set pagination margin.', 'logo-carousel-free' ),
								'class'    => 'lcp_pro_option',
								'default'  => array(
									'top'    => '40',
									'bottom' => '20',
									'left'   => '0',
									'right'  => '0',
								),
							),
							array(
								'id'       => 'lcp_pagination_color',
								'type'     => 'color_group',
								'title'    => __( 'Color', 'logo-carousel-free' ),
								'class'    => 'lcp_pro_option',
								'subtitle' => __( 'Set pagination color.', 'logo-carousel-free' ),
								'options'  => array(
									'color1' => __( 'Color', 'logo-carousel-free' ),
									'color2' => __( 'Hover Color', 'logo-carousel-free' ),
									'color3' => __( 'Background', 'logo-carousel-free' ),
									'color4' => __( 'Hover BG', 'logo-carousel-free' ),
									'color5' => __( 'Border', 'logo-carousel-free' ),
									'color6' => __( 'Hover Border', 'logo-carousel-free' ),
								),
								'default'  => array(
									'color1' => '#5e5e5e',
									'color2' => '#ffffff',
									'color3' => 'transparent',
									'color4' => '#63a37b',
									'color5' => '#dddddd',
									'color6' => '#63a37b',
								),
							),
						),
					),
					array(
						'title'  => __( 'CTA Button', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="#343434" ><path d="M4 4a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H4z"/><path fill-rule="evenodd" d="M0 5a5 5 0 0 1 5-5h6a5 5 0 1 1 0 10H5a5 5 0 0 1-5-5zm5-3.8h6a3.8 3.8 0 1 1 0 7.6H5a3.8 3.8 0 1 1 0-7.6z"/></svg></span>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'content' => sprintf(
									/* translators: %1$s: strong and link tags start, %2$s: link and strong tags end, %3$s: another link tag start %4$s: link tag ends */
									__( 'Want to get a customized %1$sCTA button%2$s below your logo showcase to boost engagement? %3$sUpgrade To Pro!%4$s', 'logo-carousel-free' ),
									'<strong class="lcp-pro-text"><a href="https://logocarousel.com/cta-button/" target="_blank">',
									'</a></strong>',
									'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'lcp_contact_button',
								'type'       => 'switcher',
								'title'      => __( 'Contact Us Button', 'logo-carousel-free' ),
								'class'      => 'lcp_only_pro',
								'subtitle'   => __( 'Show/Hide call to action button below logo showcase.', 'logo-carousel-free' ),
								'text_on'    => __( 'Show', 'logo-carousel-free' ),
								'text_off'   => __( 'Hide', 'logo-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/cta-button/" target="_blank">%s</a></div>',
									__( 'Contact Us Button', 'logo-carousel-free' ),
									__( 'This feature allows you to display a "Contact Us" call-to-action button below the logo showcase so that interested individuals to easily can reach out to the website owner.', 'logo-carousel-free' ),
									__( 'Live Demo', 'logo-carousel-free' )
								),
							),
							array(
								'id'       => 'lcp_contact_button_style',
								'type'     => 'layout_preset',
								'title'    => __( 'Button Style', 'logo-carousel-free' ),
								'class'    => 'lcp_pro_option',
								'subtitle' => __( 'Choose a call to action button style.', 'logo-carousel-free' ),
								'options'  => array(
									'filled'  => array(
										'image' => SP_LC_URL . 'admin/assets/images/button-type/filled.svg',
										'text'  => __( 'Filled', 'logo-carousel-free' ),
									),
									'outline' => array(
										'image' => SP_LC_URL . 'admin/assets/images/button-type/outline.svg',
										'text'  => __( 'Outline', 'logo-carousel-free' ),
									),
								),
								'default'  => 'filled',
							),
							array(
								'id'          => 'lcp_contact_button_border',
								'type'        => 'border',
								'title'       => __( 'Border', 'logo-carousel-free' ),
								'class'       => 'lcp_pro_option',
								'subtitle'    => __( 'Set a call to action button border.', 'logo-carousel-free' ),
								'default'     => array(
									'all'         => '2',
									'style'       => 'solid',
									'color'       => '#444444',
									'hover_color' => '#16a08b',
								),
								'all'         => true,
								'hover_color' => true,
							),
							array(
								'id'       => 'lcp_contact_button_color',
								'type'     => 'color_group',
								'class'    => 'lcp_pro_option',
								'title'    => __( 'Color', 'logo-carousel-free' ),
								'subtitle' => __( 'Set a call to action button color.', 'logo-carousel-free' ),
								'options'  => array(
									'color1' => __( 'Color', 'logo-carousel-free' ),
									'color2' => __( 'Hover', 'logo-carousel-free' ),
									'color3' => __( 'Background', 'logo-carousel-free' ),
									'color4' => __( 'BG Hover', 'logo-carousel-free' ),
								),
								'default'  => array(
									'color1' => '#ffffff',
									'color2' => '#ffffff',
									'color3' => '#13835a',
									'color4' => '#126344',
								),
							),
							array(
								'id'       => 'lcp_contact_button_shape free', // Add "free" id to avoid pro key conflict.
								'type'     => 'slider',
								'class'    => 'lcp_pro_option',
								'title'    => __( 'Border Radius', 'logo-carousel-free' ),
								'subtitle' => __( 'Set a border radius for the button shape.', 'logo-carousel-free' ),
								'unit'     => __( 'px', 'logo-carousel-free' ),
								'step'     => 1,
								'min'      => 0,
								'max'      => 100,
								'default'  => 5,
							),
							array(
								'id'       => 'lcp_contact_button_font_size',
								'type'     => 'spinner',
								'class'    => 'lcp_spinner lcp_pro_option',
								'title'    => __( 'Font Size', 'logo-carousel-free' ),
								'subtitle' => __( 'Set font size for the call to action button.', 'logo-carousel-free' ),
								'default'  => '18',
								'unit'     => __( 'px', 'logo-carousel-free' ),
								'min'      => 0,
							),
							array(
								'id'       => 'lcp_contact_link_type',
								'type'     => 'button_set',
								'title'    => __( 'Link Type', 'logo-carousel-free' ),
								'class'    => 'lcp_pro_option',
								'subtitle' => __( 'Choose a link type.', 'logo-carousel-free' ),
								'options'  => array(
									'link'  => __( 'Link', 'logo-carousel-free' ),
									'email' => __( 'Email', 'logo-carousel-free' ),
									'phone' => __( 'Phone', 'logo-carousel-free' ),
								),
								'default'  => 'link',
							),
							array(
								'id'         => 'lcp_contact_button_link free', // Add "free" id to avoid pro key conflict.
								'type'       => 'text',
								'title'      => __( 'Link URL', 'logo-carousel-free' ),
								'class'      => 'lcp_pro_option',
								'subtitle'   => __( 'Type custom URL for the call to action button.', 'logo-carousel-free' ),
								'tab_blank'  => false,
								'link_title' => __( 'Open a New Tab ', 'logo-carousel-free' ),
								'default'    => '#',
								'dependency' => array( 'lcp_contact_link_type', '==', 'link', true ),
							),
							array(
								'id'         => 'lcp_contact_with_email free', // Add "free" id to avoid pro key conflict.
								'type'       => 'text',
								'title'      => __( 'Email', 'logo-carousel-free' ),
								'class'      => 'lcp_pro_option',
								'subtitle'   => __( 'Type custom email for the call to action button.', 'logo-carousel-free' ),
								'tab_blank'  => true,
								'link_title' => __( 'Open a New Tab ', 'logo-carousel-free' ),
								'default'    => '',
								'dependency' => array( 'lcp_contact_button|lcp_contact_link_type', '==|==', 'true|email', true ),
							),
							array(
								'id'         => 'lcp_contact_with_phone free', // Add "free" id to avoid pro key conflict.
								'type'       => 'text',
								'title'      => __( 'Phone Number', 'logo-carousel-free' ),
								'class'      => 'lcp_pro_option',
								'subtitle'   => __( 'Type your phone number for the call to action button.', 'logo-carousel-free' ),
								'tab_blank'  => true,
								'link_title' => __( 'Open a Popup ', 'logo-carousel-free' ),
								'default'    => '',
								'dependency' => array( 'lcp_contact_button|lcp_contact_link_type', '==|==', 'true|phone', true ),
							),
							array(
								'id'       => 'lcp_contact_button_label',
								'type'     => 'text',
								'class'    => 'lcp_all_tab_text lcp_pro_option',
								'title'    => __( 'Text Label', 'logo-carousel-free' ),
								'subtitle' => __( 'Change call to action button text.', 'logo-carousel-free' ),
								'default'  => 'Contact Us',
							),
						),

					),
				),
			),
		),
	)
);

// Logo Image Settings.
SPLC::createSection(
	$prefix,
	array(
		'title'  => __( 'Logo Image Settings', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon fa fa-image"></i>',
		'fields' => array(
			array(
				'id'         => 'lcp_logo_image',
				'type'       => 'switcher',
				'title'      => __( 'Logo Image', 'logo-carousel-free' ),
				'subtitle'   => __( 'Show/Hide logo image.', 'logo-carousel-free' ),
				'default'    => true,
				'text_on'    => __( 'Show', 'logo-carousel-free' ),
				'text_off'   => __( 'Hide', 'logo-carousel-free' ),
				'text_width' => 80,
			),
			array(
				'id'         => 'lcp_image_sizes',
				'type'       => 'image_sizes',
				'chosen'     => true,
				'title'      => __( 'Logo Image Dimensions', 'logo-carousel-free' ),
				'default'    => 'full',
				'subtitle'   => __( 'Set a size for logo image.', 'logo-carousel-free' ),
				'dependency' => array( 'lcp_logo_image', '==', 'true', true ),
			),
			array(
				'id'                => 'lcp_image_crop_size',
				'type'              => 'dimensions_advanced',
				'title'             => __( 'Custom Logo Size', 'logo-carousel-free' ),
				'subtitle'          => __( 'Set width and height of the logo image.', 'logo-carousel-free' ),
				'chosen'            => true,
				'bottom'            => false,
				'left'              => false,
				'color'             => false,
				'top_icon'          => '<i class="fa fa-arrows-h"></i>',
				'right_icon'        => '<i class="fa fa-arrows-v"></i>',
				'top_placeholder'   => 'width',
				'right_placeholder' => 'height',
				'styles'            => array(
					'Soft-crop',
					'Hard-crop',
				),
				'default'           => array(
					'top'   => '',
					'right' => '',
					'style' => 'Hard-crop',
					'unit'  => 'px',
				),
				'attributes'        => array(
					'min' => 0,
				),
				'dependency'        => array( 'lcp_logo_image|lcp_image_sizes', '==|==', 'true|custom', true ),
			),

			array(
				'id'         => 'load_2x_image',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Load 2x Resolution Image in Retina Display', 'logo-carousel-free' ),
				'subtitle'   => __(
					'You should upload 2x sized images for the retina display.
				',
					'logo-carousel-free'
				),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'text_width' => 100,
				'dependency' => array( 'lcp_logo_image|lcp_image_sizes', '==|==', 'true|custom', true ),
			),
			array(
				'id'         => 'lcp_logo_lazy_load',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Lazy Load', 'logo-carousel-free' ),
				'subtitle'   => __(
					'Enable to activate lazy loading for logo images.',
					'logo-carousel-free'
				),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'text_width' => 100,
			),
			array(
				'id'         => 'lcp_logo_zoom_effect_types',
				'type'       => 'select',
				'title'      => __( 'Hover Animation', 'logo-carousel-free' ),
				'subtitle'   => __( 'Select a hover animation effect for the logo image.', 'logo-carousel-free' ),
				'class'      => 'order_by_pro',
				'options'    => array(
					'off'               => __( 'None', 'logo-carousel-free' ),
					'zoom_in'           => __( 'Zoom In (Pro)', 'logo-carousel-free' ),
					'zoom_out'          => __( 'Zoom Out (Pro)', 'logo-carousel-free' ),
					'bounce'            => __( 'Bounce (Pro)', 'logo-carousel-free' ),
					'flash'             => __( 'Flash (Pro)', 'logo-carousel-free' ),
					'pulse'             => __( 'Pulse (Pro)', 'logo-carousel-free' ),
					'rubberBand'        => __( 'RubberBand (Pro)', 'logo-carousel-free' ),
					'shakeY'            => __( 'ShakeY (Pro)', 'logo-carousel-free' ),
					'swing'             => __( 'Swing (Pro)', 'logo-carousel-free' ),
					'tada'              => __( 'Tada (Pro)', 'logo-carousel-free' ),
					'heartBeat'         => __( 'HeartBeat (Pro)', 'logo-carousel-free' ),
					'backInDown'        => __( 'BackInDown (Pro)', 'logo-carousel-free' ),
					'backInLeft'        => __( 'BackInLeft (Pro)', 'logo-carousel-free' ),
					'backInRight'       => __( 'BackInRight (Pro)', 'logo-carousel-free' ),
					'bounceIn'          => __( 'BounceIn (Pro)', 'logo-carousel-free' ),
					'bounceInLeft'      => __( 'BounceInLeft (Pro)', 'logo-carousel-free' ),
					'bounceInRight'     => __( 'BounceInRight (Pro)', 'logo-carousel-free' ),
					'fadeIn'            => __( 'FadeIn (Pro)', 'logo-carousel-free' ),
					'fadeInDown'        => __( 'FadeInDown (Pro)', 'logo-carousel-free' ),
					'fadeInUp'          => __( 'FadeInUp (Pro)', 'logo-carousel-free' ),
					'fadeInTopLeft'     => __( 'FadeInTopLeft (Pro)', 'logo-carousel-free' ),
					'fadeInTopRight'    => __( 'FadeInTopRight (Pro)', 'logo-carousel-free' ),
					'fadeInBottomLeft'  => __( 'FadeInBottomLeft (Pro)', 'logo-carousel-free' ),
					'fadeInBottomRight' => __( 'FadeInBottomRight (Pro)', 'logo-carousel-free' ),
					'flip'              => __( 'Flip (Pro)', 'logo-carousel-free' ),
					'flipInX'           => __( 'FlipInX (Pro)', 'logo-carousel-free' ),
					'flipInY'           => __( 'FlipInY (Pro)', 'logo-carousel-free' ),
					'lightSpeedInRight' => __( 'LightSpeedInRight (Pro)', 'logo-carousel-free' ),
					'lightSpeedInLeft'  => __( 'LightSpeedInLeft (Pro)', 'logo-carousel-free' ),
					'rotateIn'          => __( 'RotateIn (Pro)', 'logo-carousel-free' ),
					'rotateInDownLeft'  => __( 'RotateInDownLeft (Pro)', 'logo-carousel-free' ),
					'rotateInDownRight' => __( 'RotateInDownRight (Pro)', 'logo-carousel-free' ),
					'rotateInUpRight'   => __( 'RotateInUpRight (Pro)', 'logo-carousel-free' ),
					'jackInTheBox'      => __( 'JackInTheBox (Pro)', 'logo-carousel-free' ),
					'slideInDown'       => __( 'SlideInDown (Pro)', 'logo-carousel-free' ),
					'slideInLeft'       => __( 'SlideInLeft (Pro)', 'logo-carousel-free' ),
					'slideInRight'      => __( 'SlideInRight (Pro)', 'logo-carousel-free' ),
					'slideInUp'         => __( 'SlideInUp (Pro)', 'logo-carousel-free' ),
				),
				'default'    => 'off',
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/logo-animations/" target="_blank">%s</a></div>',
					__( 'Hover Animation', 'logo-carousel-free' ),
					__( 'This feature lets you choose a specific animation effect when hovering over the logo image.', 'logo-carousel-free' ),
					__( 'Live Demo', 'logo-carousel-free' )
				),
				'dependency' => array( 'lcp_logo_image|lcp_logo_carousel_mode', '==|any', 'true|standard,ticker', true ),
			),
			array(
				'id'         => 'lcp_logo_blur_effect',
				'type'       => 'switcher',
				'class'      => 'lcp_only_pro',
				'title'      => __( 'Blur', 'logo-carousel-free' ),
				'subtitle'   => __( 'Enable/Disable loge image blur effect.', 'logo-carousel-free' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'text_width' => 100,
				'dependency' => array( 'lcp_logo_image', '==', 'true', true ),

			),
			array(
				'id'         => 'lcp_logo_opacity',
				'type'       => 'slider',
				'class'      => 'sp-lc-opacity',
				'title'      => __( 'Opacity', 'logo-carousel-free' ),
				'subtitle'   => __( 'Set opacity for the logo images.', 'logo-carousel-free' ),
				'step'       => 0.01,
				'min'        => 0.01,
				'max'        => 1,
				'default'    => 1,
				'dependency' => array( 'lcp_logo_image', '==', 'true', true ),
			),
			array(
				'id'         => 'lcp_logo_gray_scale',
				'class'      => 'lcp_logo_gray_scale sp-lc-pro-only',
				'type'       => 'button_set',
				'title'      => __( 'Logo Image Mode', 'logo-carousel-free' ),
				'subtitle'   => __( 'Set a mode for logo images.', 'logo-carousel-free' ),
				'options'    => array(
					'off'          => __( 'Original', 'logo-carousel-free' ),
					'always_gray'  => __( 'Grayscale', 'logo-carousel-free' ),
					'custom-color' => __( 'Custom color', 'logo-carousel-free' ),
				),
				'title_info' => sprintf(
					'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div><div class="info-button"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/grayscale-style/" target="_blank">%s</a></div>',
					__( 'Logo Image Mode', 'logo-carousel-free' ),
					__( 'This feature allows you to select the visual style or image mode that aligns with your design preferences for logo presentation.', 'logo-carousel-free' ),
					__( 'Live Demo', 'logo-carousel-free' )
				),
				'default'    => 'off',
				'dependency' => array( 'lcp_logo_image', '==', 'true', true ),
			),
			array(
				'id'         => 'lcp_image_grayscale_on_hover',
				'type'       => 'checkbox',
				'class'      => 'lcp_pro_option',
				'title'      => __( 'Grayscale on Hover', 'logo-carousel-free' ),
				'subtitle'   => __( 'Check to grayscale logo image on hover.', 'logo-carousel-free' ),
				'default'    => false,
				'dependency' => array( 'lcp_logo_image|lcp_logo_gray_scale', '==|==', 'true|off', true ),
			),
			array(
				'id'         => 'lcp_image_grayscale_normal_on_hover',
				'type'       => 'checkbox',
				'title'      => __( 'Normal on Hover', 'logo-carousel-free' ),
				'subtitle'   => __( 'Check to normal grayscale logo image on hover.', 'logo-carousel-free' ),
				'default'    => false,
				'dependency' => array( 'lcp_logo_image|lcp_logo_gray_scale', '==|==', 'true|always_gray', true ),
			),
			array(
				'id'         => 'lcp_mobile_tablet_gray_off',
				'type'       => 'switcher',
				'title'      => __( 'Grayscale on Tablet & Mobile', 'logo-carousel-free' ),
				'class'      => 'lcp_navigation',
				'subtitle'   => __( 'Enable/Disable grayscale effect on table and mobile screen. Screen smaller than 736px.', 'logo-carousel-free' ),
				'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
				'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
				'text_width' => 100,
				'default'    => false,
				'dependency' => array( 'lcp_logo_image|lcp_logo_gray_scale|lcp_image_grayscale_normal_on_hover', '==|==|==', 'true|always_gray|true', true ),
			),
			array(
				'id'         => 'lcp_image_title_attr',
				'type'       => 'checkbox',
				'title'      => __( 'Logo Title Attribute', 'logo-carousel-free' ),
				'subtitle'   => __( 'Check to add logo title attribute.', 'logo-carousel-free' ),
				'default'    => false,
				'dependency' => array( 'lcp_logo_image', '==', 'true', true ),
			),
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'typography-normal-notice',
				'content' => sprintf(
					/* translators: %1$s: strong tag starts, %2$s: link tag starts, %3$s: link tag ends %4$s: link tag starts %5$s: another link tag start %6$s: first strong tag ends  %7$s: strong tag start %8$s: link and bold tags start %9$s: bold and link tags end */
					__( 'Ready to fascinate your audience with %1$s30+ stunning %2$slogo hover effects%3$s, lazy load, blur, %4$sgrayscale%3$s, %5$scustom logo colors%3$s, dimensions,%6$s and %7$sretina readiness?%6$s %8$sUpgrade To Pro!%9$s', 'logo-carousel-free' ),
					'<strong class="lcp-pro-text">',
					'<a href="https://logocarousel.com/logo-animations/" target="_blank">',
					'</a>',
					'<a href="https://logocarousel.com/grayscale-style/" target="_blank">',
					'<a href="https://logocarousel.com/custom-logo-color/" target="_blank">',
					'</strong>',
					'<strong>',
					'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
					'</b></a>'
				),
			),
		),
	)
);

// Carousel Controls.
SPLC::createSection(
	$prefix,
	array(
		'title'  => __( 'Carousel Settings', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon fa fa-sliders"></i>',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'lcp-carousel-tabs',
				'tabs'  => array(
					array(
						'title'  => __( 'Carousel Basics', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)"><path fill-rule="evenodd" d="M1.224 1.224c-.009.009-.024.03-.024.076v13.4c0 .046.015.067.024.076s.03.024.076.024h13.4c.02-.017.019-.043.012-.082l-.012-.058V14.6 1.3c0-.046-.015-.067-.024-.076s-.03-.024-.076-.024H1.3c-.046 0-.067.015-.076.024zM0 1.3A1.28 1.28 0 0 1 1.3 0h13.3a1.28 1.28 0 0 1 1.3 1.3v13.247c.058.368-.014.734-.248 1.02-.244.299-.602.433-.952.433H1.3A1.28 1.28 0 0 1 0 14.7V1.3zm12.4 3h-.9c-.3-.7-1.1-1.2-1.9-1.2-.9 0-1.6.5-1.9 1.2H3.6c-.5 0-.9.4-.9.9s.4.9.9.9h4.1c.3.8 1 1.3 1.9 1.3s1.6-.5 1.9-1.2h.9c.5 0 .9-.4.9-.9s-.4-1-.9-1zm-7.9 7.4h-.9c-.5 0-.9-.4-.9-.9s.4-.9.9-.9h.9c.3-.8 1-1.3 1.9-1.3s1.6.5 1.9 1.3h4.1c.5 0 .9.4.9.9s-.4.9-.9.9H8.3c-.3.7-1 1.2-1.9 1.2-.8 0-1.6-.5-1.9-1.2z" fill="#000"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'id'         => 'lcp_vertical_horizontal',
								'type'       => 'button_set',
								'class'      => 'sp-lc-pro-only',
								'title'      => __( 'Carousel Orientation', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose a carousel orientation.', 'logo-carousel-free' ),
								'options'    => array(
									'horizontal' => __( 'Horizontal', 'logo-carousel-free' ),
									'vertical'   => __( 'Vertical', 'logo-carousel-free' ),
								),
								'default'    => 'horizontal',
								'dependency' => array( 'lcp_layout', '==', 'carousel', true ),
							),
							array(
								'type'       => 'switcher',
								'id'         => 'lcp_carousel_auto_play',
								'title'      => __( 'AutoPlay', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable autoplay for the carousel.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'lcp_carousel_auto_play_speed',
								'type'       => 'slider',
								'class'      => 'lcp_carousel_auto_play_ranger',
								'title'      => __( 'AutoPlay Delay Time', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set autoplay delay time in millisecond.', 'logo-carousel-free' ),
								'sanitize'   => 'splogocarousel_sanitize_number_field',
								'unit'       => __( 'ms', 'logo-carousel-free' ),
								'step'       => 100,
								'min'        => 100,
								'max'        => 50000,
								'default'    => 3000,
								'dependency' => array(
									'lcp_carousel_auto_play',
									'==',
									'true',
									true,
								),
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div>',
									__( 'AutoPlay Delay Time', 'logo-carousel-free' ),
									__( 'Set autoplay delay or interval time. The amount of time to delay between automatically cycling a weather item. e.g. 1000 milliseconds(ms) = 1 second.', 'logo-carousel-free' )
								),

							),
							array(
								'id'         => 'lcp_carousel_scroll_speed',
								'type'       => 'slider',
								'class'      => 'lcp_carousel_auto_play_ranger',
								'title'      => __( 'Carousel Speed', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set carousel scroll speed in millisecond.', 'logo-carousel-free' ),
								'unit'       => __( 'ms', 'logo-carousel-free' ),
								'step'       => 100,
								'min'        => 1,
								'max'        => 20000,
								'default'    => 600,
								'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode', '==|any', 'carousel|standard,center', true ),
								'title_info' => sprintf(
									'<div class="splogocarousel-info-label">%s</div> <div class="splogocarousel-short-content">%s</div>',
									__( 'Carousel Speed', 'logo-carousel-free' ),
									__( 'Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.', 'logo-carousel-free' )
								),
							),
							array(
								'id'         => 'lcp_carousel_pause_on_hover',
								'type'       => 'switcher',
								'title'      => __( 'Pause on Hover', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable pause on hover carousel.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array(
									'lcp_carousel_auto_play',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'lcp_carousel_starts_on_screen',
								'type'       => 'switcher',
								'title'      => __( 'Carousel Starts on Screen', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable the option to start the carousel when the carousel comes on screen.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array(
									'lcp_carousel_auto_play',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'       => 'lcp_slides_to_scroll',
								'type'     => 'column',
								'class'    => 'pro_only_field lcp-slide-to-scroll',
								'title'    => __( 'Slide To Scroll', 'logo-carousel-free' ),
								'subtitle' => __( 'Set number of slide to scroll on devices.', 'logo-carousel-free' ),
								'sanitize' => 'splogocarousel_sanitize_number_array_field',
								'default'  => array(
									'lg_desktop'       => '1',
									'desktop'          => '1',
									'tablet'           => '1',
									'mobile_landscape' => '1',
									'mobile'           => '1',
								),
								'min'      => '1',
							),
							array(
								'id'         => 'lcp_carousel_infinite',
								'type'       => 'switcher',
								'title'      => __( 'Infinite Loop', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable infinite looping for the carousel.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'       => 'lcp_rtl_mode',
								'type'     => 'button_set',
								'title'    => __( 'Carousel Direction', 'logo-carousel-free' ),
								'subtitle' => __( 'Set carousel direction as you need.', 'logo-carousel-free' ),
								'options'  => array(
									'false' => __( 'Right to Left', 'logo-carousel-free' ),
									'true'  => __( 'Left to Right', 'logo-carousel-free' ),
								),
								'default'  => 'false',
							),
							array(
								'id'         => 'lcp_rows',
								'type'       => 'column',
								'class'      => 'pro_only_field lcp-row-number',
								'title'      => __( 'Row', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set number of row on devices.', 'logo-carousel-free' ),
								'default'    => array(
									'lg_desktop'       => '1',
									'desktop'          => '1',
									'tablet'           => '1',
									'mobile_landscape' => '1',
									'mobile'           => '1',
								),
								'min'        => '1',
								'title_info' => '<div class="splogocarousel-img-tag"><img src="' . SPLC::include_plugin_url( 'assets/images/row.svg' ) . '" alt="' . __( 'Multi Row', 'logo-carousel-free' ) . '"></div><div class="splogocarousel-info-label img">' . __( 'Multi Row', 'logo-carousel-free' ) . '</div><div class="info-button img"><a class="splogocarousel-open-live-demo" href="https://logocarousel.com/multi-row-carousel/" target="_blank">' . __( 'Live Demo', 'logo-carousel-free' ) . '</a></div>',
							),
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'content' => sprintf(
									/* translators: %1$s: link tag starts %2$s: link tag ends %3$s: link tag starts %4$s: link tag starts %5$s: link tag starts %6$s: link and bold tags start %7$s: bold and link tags end */
									__( 'To create engaging logo slideshows with intuitive scrolling, %1$smulti-row%2$s, %3$sticker%2$s, %4$scenter mode%2$s, and %5$svertical%2$s carousels, %6$sUpgrade To Pro!%7$s', 'logo-carousel-free' ),
									'<a href="https://logocarousel.com/multi-row-carousel/" target="_blank">',
									'</a>',
									'<a href="https://logocarousel.com/ticker-carousel-with-infinite-loop/" target="_blank">',
									'<a href="https://logocarousel.com/center-mode-carousel/" target="_blank">',
									'<a href="https://logocarousel.com/widget-example/" target="_blank">',
									'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
						),
					),
					array(
						'title'  => __( 'Navigation', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#343434" ><path d="M2.2 8l4.1-4.1a.85.85 0 0 0 0-1.3c-.4-.3-1-.3-1.3.1L.3 7.4a.85.85 0 0 0 0 1.3L5 13.3c.3.3.9.3 1.2 0a.85.85 0 0 0 0-1.3l-4-4zM11 2.7l4.7 4.7c.4.3.4.9-.1 1.3l-4.7 4.7c-.4.4-1 .2-1.2 0a.85.85 0 0 1 0-1.3L13.8 8l-4-4.1c-.4-.3-.4-.9-.1-1.2a.85.85 0 0 1 1.3 0zM6.5 6a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-3z"/></svg></span>',
						'fields' => array(
							array(
								'id'     => 'lcp_carousel_navigation',
								'class'  => 'lcp-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'lcp_nav_show',
										'type'       => 'switcher',
										'title'      => __( 'Navigation', 'logo-carousel-free' ),
										'class'      => 'lcp_navigation',
										'subtitle'   => __( 'Show/hide navigation.', 'logo-carousel-free' ),
										'text_on'    => __( 'Show', 'logo-carousel-free' ),
										'text_off'   => __( 'Hide', 'logo-carousel-free' ),
										'text_width' => 80,
										'default'    => true,
										'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode', '==|any', 'carousel|standard,center', true ),
									),
									array(
										'id'         => 'lcp_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'lcp_hide_on_mobile',
										'title'      => __( 'Hide on Mobile', 'logo-carousel-free' ),
										'default'    => false,
										'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode|lcp_nav_show', '==|any|==', 'carousel|standard,center|true', true ),
									),
								),
							),
							array(
								'id'         => 'lcp_nav_position',
								'type'       => 'select',
								'preview'    => true,
								'class'      => 'lcp-carousel-nav-position',
								'title'      => __( 'Position', 'logo-carousel-free' ),
								'subtitle'   => __( 'Select a position of the navigation arrows.', 'logo-carousel-free' ),
								'options'    => array(
									'top_right'       => __( 'Top Right', 'logo-carousel-free' ),
									'top_center'      => __( 'Top Center', 'logo-carousel-free' ),
									'top_left'        => __( 'Top Left', 'logo-carousel-free' ),
									'bottom_left'     => __( 'Bottom Left', 'logo-carousel-free' ),
									'bottom_center'   => __( 'Bottom Center', 'logo-carousel-free' ),
									'bottom_right'    => __( 'Bottom Right', 'logo-carousel-free' ),
									'vertical_center_inner' => __( 'Vertical Center Inner', 'logo-carousel-free' ),
									'vertical_outer'  => __( 'Vertical Outer', 'logo-carousel-free' ),
									'vertical_center' => __( 'Vertical Center', 'logo-carousel-free' ),
								),
								'only_pro'   => true,
								'default'    => 'top_right',
								'dependency' => array(
									'lcp_nav_show',
									'!=',
									'hide',
									true,
								),
							),
							array(
								'id'         => 'lcp_nav_type',
								'type'       => 'button_set',
								'class'      => 'sp-lc-pro-only',
								'title'      => __( 'Navigation Type', 'logo-carousel-free' ),
								'subtitle'   => __( 'Select the carousel navigation type.', 'logo-carousel-free' ),
								'options'    => array(
									'nav_arrow' => __( 'Arrow', 'logo-carousel-free' ),
									'nav_text'  => __( 'Text', 'logo-carousel-free' ),
								),
								'default'    => 'nav_arrow',
								'dependency' => array(
									'lcp_nav_show|lcp_layout|lcp_logo_carousel_mode',
									'!=|==|any',
									'false|carousel|standard,center',
									true,
								),
							),
							array(
								'id'         => 'lcp_nav_arrow_type_horizontal',
								'type'       => 'button_set',
								'class'      => 'sp-lc-pro-only lcp-nav-arrow-type-horizontal',
								'title'      => __( 'Arrow Type', 'logo-carousel-free' ),
								'subtitle'   => __( 'Choose a navigation arrow icon for the carousel.', 'logo-carousel-free' ),
								'options'    => array(
									'angle_arrow_horizon'  => '<i class="lc-icon-angle-right"></i>',
									'chevron_arrow_horizon' => '<i class="lc-icon-right-open-big"></i>',
									'double_arrow_horizon' => '<i class="lc-icon-right-open"></i>',
									'bold_arrow_horizon'   => '<i class="lc-icon-right-open-1"></i>',
									'long_arrow_horizon'   => '<i class="lc-icon-right-open-3"></i>',
									'caret_arrow_horizon'  => '<i class="lc-icon-right-open-outline"></i>',
									'icon_right'           => '<i class="lc-icon-right"></i>',
									'arrow_triangle_right' => '<i class="lc-icon-arrow-triangle-right"></i>',
								),
								'default'    => 'angle_arrow_horizon',
								'dependency' => array(
									'lcp_nav_show|lcp_layout|lcp_nav_type|lcp_logo_carousel_mode',
									'!=|==|==|any',
									'false|carousel|nav_arrow|standard,center',
									true,
								),
							),
							array(
								'id'         => 'lcp_arrow_icon_font_size',
								'type'       => 'spinner',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Icon Size', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set font size for the arrow icon.', 'logo-carousel-free' ),
								'default'    => '20',
								'unit'       => __( 'px', 'logo-carousel-free' ),
								'min'        => 0,
								'dependency' => array(
									'lcp_nav_show|lcp_layout|lcp_nav_type|lcp_logo_carousel_mode',
									'!=|==|==|any',
									'false|carousel|nav_arrow|standard,center|',
									true,
								),
							),
							array(
								'id'         => 'lcp_nav_color',
								'type'       => 'color_group',
								'title'      => __( 'Color', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set navigation color.', 'logo-carousel-free' ),
								'options'    => array(
									'color1' => __( 'Color', 'logo-carousel-free' ),
									'color2' => __( 'Hover Color', 'logo-carousel-free' ),
									'color3' => __( 'Background', 'logo-carousel-free' ),
									'color4' => __( 'Hover Background', 'logo-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#aaaaaa',
									'color2' => '#ffffff',
									'color3' => 'transparent',
									'color4' => '#16a08b',
								),
								'dependency' => array(
									'lcp_nav_show',
									'!=',
									'hide',
									true,
								),
							),
							array(
								'id'          => 'lcp_nav_border',
								'type'        => 'border',
								'title'       => __( 'Border', 'logo-carousel-free' ),
								'subtitle'    => __( 'Set border for navigation.', 'logo-carousel-free' ),
								'all'         => true,
								'default'     => array(
									'all'         => '1',
									'style'       => 'solid',
									'color'       => '#aaaaaa',
									'hover_color' => '#16a08b',
								),
								'dependency'  => array(
									'lcp_nav_show',
									'!=',
									'hide',
									true,
								),
								'hover_color' => true,
							),
							array(
								'id'         => 'lcp_nav_border_radius',
								'type'       => 'spacing',
								'class'      => 'lcp_pro_option',
								'title'      => __( 'Border Radius', 'logo-carousel-free' ),
								'all'        => true,
								'units'      => array(
									__( 'px', 'logo-carousel-free' ),
									__( '%', 'logo-carousel-free' ),
								),
								'subtitle'   => __( 'Set navigation border radius.', 'logo-carousel-free' ),
								'default'    => array(
									'all' => '0',
								),
								'dependency' => array(
									'lcp_nav_show|lcp_layout|lcp_logo_carousel_mode',
									'!=|==|any',
									'false|carousel|standard,center',
									true,
								),
							),

						),
					),
					array(
						'title'  => __( 'Pagination', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" ><g clip-path="url(#A)" fill="#343434"><path d="M5.2 10.2a2.2 2.2 0 1 0 0-4.4 2.2 2.2 0 1 0 0 4.4zm6.2-.5a1.7 1.7 0 0 0 0-3.4 1.7 1.7 0 0 0 0 3.4z"/><path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-.5h12a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'id'     => 'lcp_carousel_pagination',
								'class'  => 'lcp-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'lcp_carousel_dots',
										'type'       => 'switcher',
										'title'      => __( 'Pagination', 'logo-carousel-free' ),
										'class'      => 'lcp_pagination',
										'subtitle'   => __( 'Show/hide navigation.', 'logo-carousel-free' ),
										'text_on'    => __( 'Show', 'logo-carousel-free' ),
										'text_off'   => __( 'Hide', 'logo-carousel-free' ),
										'text_width' => 80,
										'default'    => true,
										'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode', '==|any', 'carousel|standard,center', true ),
									),
									array(
										'id'         => 'lcp_pagination_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'lcp_hide_on_mobile',
										'title'      => __( 'Hide on Mobile', 'logo-carousel-free' ),
										'default'    => false,
										'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode|lcp_carousel_dots', '==|any|==', 'carousel|standard,center|true', true ),
									),
								),
							),
							array(
								'id'         => 'carousel_pagination_type',
								'type'       => 'layout_preset',
								'title'      => __( 'Pagination Style', 'logo-carousel-free' ),
								'subtitle'   => __( 'Select a style for pagination.', 'logo-carousel-free' ),
								'options'    => array(
									'dots'      => array(
										'image' => SP_LC_URL . 'admin/assets/images/pagination-type/bullets.svg',
										'text'  => __( 'Bullets', 'logo-carousel-free' ),
									),
									'dynamic'   => array(
										'image'    => SP_LC_URL . 'admin/assets/images/pagination-type/dynamic.svg',
										'text'     => __( 'Dynamic', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'strokes'   => array(
										'image'    => SP_LC_URL . 'admin/assets/images/pagination-type/stroke.svg',
										'text'     => __( 'Strokes', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'scrollbar' => array(
										'image'    => SP_LC_URL . 'admin/assets/images/pagination-type/scrollbar.svg',
										'text'     => __( 'Scrollbar', 'logo-carousel-free' ),
										'pro_only' => true,
									),
									'number'    => array(
										'image'    => SP_LC_URL . 'admin/assets/images/pagination-type/number.svg',
										'text'     => __( 'Numbers', 'logo-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => 'dots',
								'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode|lcp_carousel_dots', '==|any|!=', 'carousel|standard,center|false', true ),
							),
							array(
								'id'         => 'lcp_carousel_dots_color',
								'type'       => 'color_group',
								'title'      => __( 'Color', 'logo-carousel-free' ),
								'subtitle'   => __( 'Set pagination dots color.', 'logo-carousel-free' ),
								'options'    => array(
									'color1' => __( 'Color', 'logo-carousel-free' ),
									'color2' => __( 'Active Color', 'logo-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#dddddd',
									'color2' => '#16a08b',
								),
								'dependency' => array(
									'lcp_carousel_dots',
									'!=',
									'hide',
								),
							),
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'content' => sprintf(
									/* translators: %1$s: strong tag starts %2$s: strong tag ends %3$s: link and bold tags start %4$s: bold and link tags end */
									__( 'Want even more fine-tuned control over your %1$sCarousel Pagination%2$s display? %3$sUpgrade To Pro!%4$s', 'logo-carousel-free' ),
									'<strong class="lcp-pro-text">',
									'</strong>',
									'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),

						),
					),
					array(
						'title'  => __( 'Miscellaneous', 'logo-carousel-free' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M12.4 3.9h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm0 3.3h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm-6 3.2h6c.5 0 .8.4.8.8 0 .5-.4.8-.8.8h-6c-.4 0-.8-.4-.8-.8s.4-.8.8-.8zM4.9 4.8a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1zM3.9 9a.94.94 0 0 0 1-1 .94.94 0 0 0-1-1 .94.94 0 0 0-1 1c0 .6.5 1 1 1zm1 2.2a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1z"/><path fill-rule="evenodd" d="M13.2 0H2.9C1.3 0 0 1.3 0 2.9v10.2C0 14.7 1.3 16 2.9 16h10.2c1.6 0 2.9-1.3 2.9-2.8V2.9C16 1.3 14.7 0 13.2 0zm1.4 13.2c0 .8-.6 1.4-1.4 1.4H2.9c-.8 0-1.4-.6-1.4-1.4V2.9c0-.8.6-1.4 1.4-1.4h10.3c.8 0 1.4.6 1.4 1.4v10.3z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'id'         => 'lcp_carousel_adaptive_height',
								'type'       => 'switcher',
								'title'      => __( 'Adaptive Height', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable adaptive height to set fixed height for the carousel.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode', '==|any', 'carousel|standard,center', true ),
							),
							array(
								'id'         => 'lcp_carousel_tab_key_nav',
								'type'       => 'switcher',
								'title'      => __( 'Tab & Key Navigation', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable carousel scroll with tab and keyboard.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'lcp_layout|lcp_logo_carousel_mode', '==|any', 'carousel|standard,center', true ),
							),
							array(
								'id'         => 'lcp_carousel_swipe',
								'type'       => 'switcher',
								'title'      => __( 'Touch Swipe', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable touch swipe mode.', 'logo-carousel-free' ),
								'default'    => true,
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'dependency' => array( 'lcp_layout', '==', 'carousel', true ),
							),
							array(
								'id'         => 'lcp_carousel_draggable',
								'type'       => 'switcher',
								'title'      => __( 'Mouse Draggable', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'lcp_layout|lcp_carousel_swipe', '==|==', 'carousel|true', true ),
							),
							array(
								'id'         => 'lcp_free_mode',
								'type'       => 'switcher',
								'title'      => __( 'Free Mode', 'logo-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable carousel free mode.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'lcp_layout|lcp_carousel_draggable|lcp_carousel_swipe|lcp_logo_carousel_mode', '==|==|==|!=', 'carousel|true|true|ticker', true ),
							),
							array(
								'id'         => 'lcp_slide_to_swipe',
								'type'       => 'switcher',
								'title'      => __( 'Swipe To Slide', 'logo-carousel-free' ),
								'subtitle'   => __( 'Allow users to drag or swipe directly to a slide irrespective of slides to scroll.', 'logo-carousel-free' ),
								'text_on'    => __( 'Enabled', 'logo-carousel-free' ),
								'text_off'   => __( 'Disabled', 'logo-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'lcp_layout|lcp_carousel_draggable|lcp_carousel_swipe|lcp_logo_carousel_mode', '==|==|==|!=', 'carousel|true|true|ticker', true ),
							),

						),
					),
				),
			),
		),
	)
);

// Typography.
SPLC::createSection(
	$prefix,
	array(
		'title'  => __( 'Typography', 'logo-carousel-free' ),
		'icon'   => '<i class="splogocarousel-tab-icon fa fa-font"></i>',
		'fields' => array(
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'typography-normal-notice',
				'content' => sprintf(
					/* translators: %1$s: strong tag starts %2$s: strong tag ends %3$s: link and bold tags start %4$s: bold and link tags end */
					__( 'Want to customize everything %1$s(Colors and Typography)%2$s easily? %3$sUpgrade To Pro%4$s!', 'logo-carousel-free' ),
					'<strong class="lcp-pro-text">',
					'</strong>',
					'<b><a href="https://logocarousel.com/pricing/?ref=1" target="_blank">',
					'</a></b>'
				),
			),
			array(
				'id'            => 'lcp_section_title_typography',
				'type'          => 'typography',
				'class'         => 'lcp_section_title_typography',
				'title'         => __( 'Section Title Font', 'logo-carousel-free' ),
				'subtitle'      => __( 'Set section title font properties.', 'logo-carousel-free' ),
				'default'       => array(
					'font-family'    => 'Ubuntu',
					'font-weight'    => 'regular',
					'type'           => 'google',
					'font-size'      => '24',
					'line-height'    => '32',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'letter-spacing' => '',
					'color'          => '#222',
					'margin-bottom'  => '30',
				),
				'color'         => true, // Enable or disable preview box.
				'margin_bottom' => true,
				'preview'       => 'always', // Enable or disable preview box.
				'preview_text'  => 'The Section Title', // Replace preview text with any text you like.
			),
			array(
				'id'           => 'lcp_logo_title_typography',
				'type'         => 'typography',
				'title'        => __( 'Logo Title Font', 'logo-carousel-free' ),
				'subtitle'     => __( 'Set logo title font properties', 'logo-carousel-free' ),
				'default'      => array(
					'font-family'    => 'Ubuntu',
					'font-weight'    => 'regular',
					'type'           => 'google',
					'font-size'      => '14',
					'line-height'    => '21',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => '',
					'color'          => '#2f2f2f',
				),
				'color'        => true, // Enable or disable preview box.
				'preview'      => 'always', // Enable or disable preview box.
				'preview_text' => 'The Logo Title', // Replace preview text with any text you like.
			),
			array(
				'id'           => 'lcp_logo_description_typography',
				'type'         => 'typography',
				'title'        => __( 'Logo Body/Description Font', 'logo-carousel-free' ),
				'subtitle'     => __( 'Set logo description font properties', 'logo-carousel-free' ),
				'default'      => array(
					'font-family'    => 'Ubuntu',
					'font-weight'    => 'regular',
					'type'           => 'google',
					'font-size'      => '14',
					'line-height'    => '21',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'letter-spacing' => '',
					'color'          => '#555',
				),
				'color'        => true, // Enable or disable color field.
				'preview'      => 'always', // Enable or disable preview box.
				'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
				// Replace preview text with any text you like.
			),
			array(
				'id'           => 'lcp_read_more_typography',
				'type'         => 'typography',
				'title'        => __( 'Read More Font', 'logo-carousel-free' ),
				'subtitle'     => __( 'Set description read more font properties', 'logo-carousel-free' ),
				'default'      => array(
					'font-family'    => 'Ubuntu',
					'font-weight'    => 'regular',
					'type'           => 'google',
					'font-size'      => '14',
					'line-height'    => '20',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'letter-spacing' => '',
				),
				'preview'      => 'always', // Enable or disable preview box.
				'preview_text' => 'Learn More', // Replace preview text with any text you like.
			),
			array(
				'id'           => 'lcp_logo_popup_title_typography',
				'type'         => 'typography',
				'title'        => __( 'Popup Title Font', 'logo-carousel-free' ),
				'subtitle'     => __( 'Set logo popup title font properties', 'logo-carousel-free' ),
				'default'      => array(
					'font-family'    => 'Ubuntu',
					'font-weight'    => 'regular',
					'type'           => 'google',
					'font-size'      => '22',
					'line-height'    => '24',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'letter-spacing' => '',
					'color'          => '#2f2f2f',
				),
				'color'        => true, // Enable or disable preview box.
				'preview'      => 'always', // Enable or disable preview box.
				'preview_text' => 'The Logo Title', // Replace preview text with any text you like.
			),
			array(
				'id'           => 'lcp_logo_popup_description_typography',
				'type'         => 'typography',
				'title'        => __( 'Popup Description Font', 'logo-carousel-free' ),
				'subtitle'     => __( 'Set logo popup description font properties', 'logo-carousel-free' ),
				'default'      => array(
					'font-family'    => 'Ubuntu',
					'font-weight'    => 'regular',
					'type'           => 'google',
					'font-size'      => '14',
					'line-height'    => '23',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'letter-spacing' => '',
					'color'          => '#555',
				),
				'color'        => true, // Enable or disable color field.
				'preview'      => 'always', // Enable or disable preview box.
				'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
				// Replace preview text with any text you like.
			),

		),
	)
);

$prefix = 'sp_logo_carousel_link_option';

// -----------------------------------------
// Logo Link Metabox Options               -
// -----------------------------------------
SPLC::createMetabox(
	$prefix,
	array(
		'title'     => __( 'Logo Link URL', 'logo-carousel-free' ),
		'post_type' => 'sp_logo_carousel',
		'context'   => 'normal',
		'priority'  => 'default',
	)
);

// Logo link.
SPLC::createSection(
	$prefix,
	array(
		'fields' => array(

			array(
				'type'    => 'subheading',
				'content' => __( 'Link & Tooltip', 'logo-carousel-free' ),
			),

			array(
				'id'         => 'lcp_logo_link',
				'type'       => 'text',
				'class'      => 'lcp_logo_link',
				'title'      => __( 'Custom URL', 'logo-carousel-free' ),
				'desc'       => sprintf(
					/* translators: %1$s: strong tag starts %2$s: strong tag ends %3$s: link and bold tags start %4$s: bold and link tags end */
					__( 'To add a link URL for the logo, %1$sUpgrade to Pro!%2$s', 'logo-carousel-free' ),
					'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
					'</b></a>'
				),
				'attributes' => array(
					'placeholder' => 'http://example.com',
					'disabled'    => 'disabled',
				),
			),

			array(
				'id'     => 'lcp_logo_link_target',
				'type'   => 'checkbox',
				'class'  => 'lcp_logo_link_ref',
				'before' => __( 'Open a New Tab', 'logo-carousel-free' ),
			),

			array(
				'id'     => 'lcp_logo_link_ref',
				'type'   => 'checkbox',
				'class'  => 'lcp_logo_link_ref',
				'before' => __( 'Nofollow', 'logo-carousel-free' ),
			),

			array(
				'id'         => 'lcp_custom_tooltip_text',
				'type'       => 'text',
				'class'      => 'lcp_custom_tooltip_meta',
				'title'      => __( 'Custom Tooltip Text', 'logo-carousel-free' ),
				'desc'       => sprintf(
					'To add tooltip custom text for the logo, %sUpgrade to Pro!%s',
					'<a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>',
					'</b></a>'
				),
				'attributes' => array(
					'placeholder' => 'Tooltip Text',
					'disabled'    => 'disabled',
				),
			),
		),
	)
);
