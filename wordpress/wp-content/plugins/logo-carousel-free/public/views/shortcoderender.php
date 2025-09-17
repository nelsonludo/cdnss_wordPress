<?php
/**
 * This file render the shortcode to the frontend
 *
 * @package logo-carousel-free
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SPLC_Shortcode_Render' ) ) {
	/**
	 * Logo Carousel - Shortcode Render class
	 *
	 * @since 3.0
	 */
	class SPLC_Shortcode_Render {
		/**
		 * Single instance of the class
		 *
		 * @var mixed SPLC_Shortcode_Render single instance of the class
		 *
		 * @since 3.0
		 */
		protected static $_instance = null;

		/**
		 * Main SPLC Instance
		 *
		 * @since 3.0
		 * @static
		 * @return self Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * SPLC_Shortcode_Render constructor.
		 */
		public function __construct() {
			add_shortcode( 'logocarousel', array( $this, 'sp_logo_carousel_render' ) );
		}

		/**
		 * Minify output
		 *
		 * @param  statement $html output.
		 * @return statement
		 */
		public static function minify_output( $html ) {
			$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
			$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
			while ( stristr( $html, '  ' ) ) {
				$html = str_replace( '  ', ' ', $html );
			}
			return $html;
		}

		/**
		 * Gets the existing shortcode-id, page-id and option-key from the current page.
		 *
		 * @return array
		 */
		public static function get_page_data() {
			$current_page_id    = get_queried_object_id();
			$option_key         = 'sp_lcp_page_id' . $current_page_id;
			$found_generator_id = get_option( $option_key );
			if ( is_multisite() ) {
				$option_key         = 'sp_lcp_page_id' . get_current_blog_id() . $current_page_id;
				$found_generator_id = get_site_option( $option_key );
			}
			$get_page_data = array(
				'page_id'      => $current_page_id,
				'generator_id' => $found_generator_id,
				'option_key'   => $option_key,
			);
			return $get_page_data;
		}
		/**
		 * Load dynamic style of the existing shortcode id.
		 *
		 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
		 * @param  mixed $logo_data to push all options.
		 * @return array dynamic style use in the existing shortcodes in the current page.
		 */
		public static function load_dynamic_style( $found_generator_id, $logo_data = '', $layout_data = '' ) {
			$setting_data = get_option( '_sp_lcpro_options' );
			$dynamic_css  = '';
			// If multiple shortcode found in the current page.
			if ( is_array( $found_generator_id ) ) {
				foreach ( $found_generator_id as $post_id ) {
					if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
						$logo_data   = get_post_meta( $post_id, 'sp_lcp_shortcode_options', true );
						$layout_data = get_post_meta( $post_id, 'sp_lcp_layout_options', true );
						require SP_LC_PATH . 'public/views/dynamic-style.php';
					}
				}
			} else {
				// If single shortcode found in the current page.
				$post_id = $found_generator_id;
				require SP_LC_PATH . 'public/views/dynamic-style.php';
			}
			// Custom css merge with dynamic style.
			$custom_css = isset( $setting_data['lcpro_custom_css'] ) ? trim( html_entity_decode( $setting_data['lcpro_custom_css'] ) ) : '';
			if ( ! empty( $custom_css ) ) {
				$dynamic_css .= $custom_css;
			}
			$dynamic_style = array(
				'dynamic_css' => self::minify_output( $dynamic_css ),
			);
			return $dynamic_style;
		}

		/**
		 * If the option does not exist, it will be created.
		 *
		 * It will be serialized before it is inserted into the database.
		 *
		 * @param  string $post_id existing shortcode id.
		 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the the current page.
		 * @return void
		 */
		public static function lcp_db_options_update( $post_id, $get_page_data ) {
			$found_generator_id = $get_page_data['generator_id'];
			$option_key         = $get_page_data['option_key'];
			$current_page_id    = $get_page_data['page_id'];
			if ( $found_generator_id ) {
				$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
				if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
					// If not found the shortcode id in the page options.
					array_push( $found_generator_id, $post_id );
					if ( is_multisite() ) {
						update_site_option( $option_key, $found_generator_id );
					} else {
						update_option( $option_key, $found_generator_id );
					}
				}
			} else {
				// If option not set in current page add option.
				if ( $current_page_id ) {
					if ( is_multisite() ) {
						add_site_option( $option_key, array( $post_id ) );
					} else {
						add_option( $option_key, array( $post_id ) );
					}
				}
			}
		}

		/**
		 * Full html show.
		 *
		 * @param array $post_id Shortcode ID.
		 * @param array $logo_data get all meta options.
		 * @param array $main_section_title shows section title.
		 */
		public static function splcp_html_show( $post_id, $logo_data, $layout_data, $main_section_title ) {

			/**
			 * Common controls.
			 */
			$layout                = isset( $layout_data['lcp_layout'] ) ? $layout_data['lcp_layout'] : 'carousel';
			$layout_justified_mode = isset( $layout_data['lcp_layout_justified_mode'] ) ? $layout_data['lcp_layout_justified_mode'] : 'left';
			$total_items           = isset( $logo_data['lcp_number_of_total_items'] ) && $logo_data['lcp_number_of_total_items'] ? $logo_data['lcp_number_of_total_items'] : 10000;
			$lcp_pagination        = ! empty( $logo_data['lcp_pagination'] ) ? 'true' : 'false';

			/**
			 * Section title and query parameters.
			 */
			$section_title         = isset( $logo_data['lcp_section_title'] ) ? $logo_data['lcp_section_title'] : 'false';
			$order_by              = isset( $logo_data['lcp_item_order_by'] ) ? $logo_data['lcp_item_order_by'] : 'date';
			$order                 = isset( $logo_data['lcp_item_order'] ) ? $logo_data['lcp_item_order'] : 'ASC';
			$preloader             = isset( $logo_data['lcp_preloader'] ) ? $logo_data['lcp_preloader'] : false;
			$show_image            = isset( $logo_data['lcp_logo_image'] ) ? $logo_data['lcp_logo_image'] : true;
			$image_sizes           = isset( $logo_data['lcp_image_sizes'] ) ? $logo_data['lcp_image_sizes'] : '';
			$show_image_title_attr = isset( $logo_data['lcp_image_title_attr'] ) ? $logo_data['lcp_image_title_attr'] : false;
			$logo_margin           = isset( $logo_data['lcp_logo_margin']['all'] ) && $logo_data['lcp_logo_margin']['all'] >= -50 ? (int) $logo_data['lcp_logo_margin']['all'] : '8';
			$logo_margin_vertical  = isset( $logo_data['lcp_logo_margin']['vertical'] ) && $logo_data['lcp_logo_margin']['vertical'] >= -50 ? (int) $logo_data['lcp_logo_margin']['vertical'] : '8';

			$args = new WP_Query(
				array(
					'post_type'      => 'sp_logo_carousel',
					'orderby'        => $order_by,
					'order'          => $order,
					'posts_per_page' => $total_items,
				)
			);

			/**
			 * Carousel controls.
			 */
			$columns             = isset( $logo_data['lcp_number_of_columns'] ) ? $logo_data['lcp_number_of_columns'] : '';
			$items               = isset( $columns['lg_desktop'] ) ? $columns['lg_desktop'] : 5;
			$items_desktop       = isset( $columns['desktop'] ) ? $columns['desktop'] : 4;
			$items_desktop_small = isset( $columns['tablet'] ) ? $columns['tablet'] : 3;
			$items_tablet        = isset( $columns['mobile_landscape'] ) ? $columns['mobile_landscape'] : 2;
			$items_mobile        = isset( $columns['mobile'] ) ? $columns['mobile'] : 1;

			// Navigation data.
			$carousel_navigation_group = isset( $logo_data['lcp_carousel_navigation'] ) ? $logo_data['lcp_carousel_navigation'] : array();
			$hide_on_mobile            = isset( $carousel_navigation_group['lcp_hide_on_mobile'] ) ? $carousel_navigation_group['lcp_hide_on_mobile'] : '';
			$nav_data                  = isset( $carousel_navigation_group['lcp_nav_show'] ) ? $carousel_navigation_group['lcp_nav_show'] : '';
			if ( $nav_data ) {
				$nav        = 'true';
				$nav_mobile = 'true';
			} elseif ( $nav_data && $hide_on_mobile ) {
				$nav        = 'true';
				$nav_mobile = 'false';
			} else {
				$nav        = 'false';
				$nav_mobile = 'false';
			}
			$carousel_pagination_group = isset( $logo_data['lcp_carousel_pagination'] ) ? $logo_data['lcp_carousel_pagination'] : array();
			$pagination_hide_on_mobile = isset( $carousel_pagination_group['lcp_pagination_hide_on_mobile'] ) ? $carousel_pagination_group['lcp_pagination_hide_on_mobile'] : '';
			$dots_data                 = isset( $carousel_pagination_group['lcp_carousel_dots'] ) ? $carousel_pagination_group['lcp_carousel_dots'] : '';
			if ( $dots_data ) {
				$dots        = 'true';
				$dots_mobile = 'true';
			} elseif ( $dots_data && $pagination_hide_on_mobile ) {
				$dots        = 'true';
				$dots_mobile = 'false';
			} else {
				$dots        = 'false';
				$dots_mobile = 'false';
			}
			$auto_play        = isset( $logo_data['lcp_carousel_auto_play'] ) && $logo_data['lcp_carousel_auto_play'] ? 'true' : 'false';
			$pause_on_hover   = isset( $logo_data['lcp_carousel_pause_on_hover'] ) && $logo_data['lcp_carousel_pause_on_hover'] ? 'true' : 'false';
			$swipe            = isset( $logo_data['lcp_carousel_swipe'] ) && $logo_data['lcp_carousel_swipe'] ? 'true' : 'false';
			$draggable        = isset( $logo_data['lcp_carousel_draggable'] ) && $logo_data['lcp_carousel_draggable'] ? 'true' : 'false';
			$free_mode        = isset( $logo_data['lcp_free_mode'] ) && $logo_data['lcp_free_mode'] ? 'true' : 'false';
			$adaptive_height  = isset( $logo_data['lcp_carousel_adaptive_height'] ) && $logo_data['lcp_carousel_adaptive_height'] ? 'true' : 'false';
			$tab_key_nav      = isset( $logo_data['lcp_carousel_tab_key_nav'] ) && ! $logo_data['lcp_carousel_tab_key_nav'] ? 'false' : 'true';
			$slide_to_swipe   = isset( $logo_data['lcp_slide_to_swipe'] ) && $logo_data['lcp_slide_to_swipe'] ? 'true' : 'false';
			$starts_on_screen = isset( $logo_data['lcp_carousel_starts_on_screen'] ) && $logo_data['lcp_carousel_starts_on_screen'] ? 'true' : 'false';
			$infinite         = isset( $logo_data['lcp_carousel_infinite'] ) && $logo_data['lcp_carousel_infinite'] ? 'true' : 'false';

			$rtl_mode = isset( $logo_data['lcp_rtl_mode'] ) ? $logo_data['lcp_rtl_mode'] : 'false';
			$rtl      = ( 'true' == $rtl_mode ) ? 'rtl' : 'ltr';

			$autoplay_speed   = isset( $logo_data['lcp_carousel_auto_play_speed'] ) ? $logo_data['lcp_carousel_auto_play_speed'] : '3000';
			$pagination_speed = isset( $logo_data['lcp_carousel_scroll_speed'] ) ? $logo_data['lcp_carousel_scroll_speed'] : '600';

			/**
			 * Grid controls.
			 */

			/**
			 * Template for output.
			 */
			$output          = '';
			$preloader_class = '';

			ob_start();

			if ( 'carousel' === $layout ) {
				// swiper data attributes.
				$swiper_data_attr = 'data-carousel=\'{ "speed":' . esc_attr( $pagination_speed ) . ',"spaceBetween": ' . esc_attr( $logo_margin ) . ', "autoplay": ' . esc_attr( $auto_play ) . ', "infinite":' . esc_attr( $infinite ) . ', "autoplay_speed": ' . esc_attr( $autoplay_speed ) . ', "stop_onHover": ' . esc_attr( $pause_on_hover ) . ', "pagination": ' . esc_attr( $dots ) . ', "navigation": ' . esc_attr( $nav ) . ', "MobileNav": ' . esc_attr( $nav_mobile ) . ', "MobilePagi": ' . esc_attr( $dots_mobile ) . ', "simulateTouch": ' . esc_attr( $draggable ) . ',"freeMode": ' . esc_attr( $free_mode ) . ',"swipeToSlide": ' . esc_attr( $slide_to_swipe ) . ', "carousel_accessibility": ' . esc_attr( $tab_key_nav ) . ',"adaptiveHeight": ' . esc_attr( $adaptive_height ) . ',"allowTouchMove": ' . esc_attr( $swipe ) . ', "slidesPerView": { "lg_desktop": ' . esc_attr( $items ) . ', "desktop": ' . esc_attr( $items_desktop ) . ', "tablet": ' . esc_attr( $items_desktop_small ) . ', "mobile": ' . esc_attr( $items_mobile ) . ', "mobile_landscape": ' . esc_attr( $items_tablet ) . ' } }\' data-carousel-starts-onscreen="' . esc_attr( $starts_on_screen ) . '"';

				// Carousel items.
				require SP_LC_PATH . 'public/views/templates/carousel.php';

			} elseif ( 'grid' === $layout ) {
				require SP_LC_PATH . 'public/views/templates/grid.php';
			}

			$output .= ob_get_clean();
			echo $output; //phpcs:ignore
		}

		/**
		 * Shortcode render
		 *
		 * @param  mixed $attribute attributes.
		 * @return mixed
		 */
		public function sp_logo_carousel_render( $attribute ) {
			if ( empty( $attribute['id'] ) || 'sp_lc_shortcodes' !== get_post_type( $attribute['id'] ) || ( get_post_status( $attribute['id'] ) === 'trash' ) || ( get_post_status( $attribute['id'] ) === 'draft' ) ) {
				return;
			}
			$post_id = esc_attr( intval( $attribute['id'] ) );
			// All Options of Shortcode.
			$layout_data = get_post_meta( $post_id, 'sp_lcp_layout_options', true );
			$logo_data   = get_post_meta( $post_id, 'sp_lcp_shortcode_options', true );
			ob_start();
			// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
			// Get the existing shortcode ids from the current page.
			$get_page_data      = self::get_page_data();
			$found_generator_id = $get_page_data['generator_id'];
			if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) ) {
				wp_enqueue_style( 'sp-lc-swiper' );
				wp_enqueue_style( 'sp-lc-font-awesome' );
				wp_enqueue_style( 'sp-lc-style' );
				$dynamic_style = self::load_dynamic_style( $post_id, $logo_data, $layout_data );
				// Load dynamic style.
				echo '<style id="sp_lcp_dynamic_css' . esc_attr( $post_id ) . '">' . $dynamic_style['dynamic_css'] . '</style>';
			}
			// Update options if the existing shortcode id option not found.
			self::lcp_db_options_update( $post_id, $get_page_data );

			$main_section_title = get_the_title( $post_id );
			self::splcp_html_show( $post_id, $logo_data, $layout_data, $main_section_title );

			wp_enqueue_script( 'sp-lc-swiper-js' );
			wp_enqueue_script( 'sp-lc-script' );
			return ob_get_clean();
		}
	}

	new SPLC_Shortcode_Render();
}
