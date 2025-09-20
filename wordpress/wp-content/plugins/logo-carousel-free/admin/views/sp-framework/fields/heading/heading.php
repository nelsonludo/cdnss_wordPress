<?php
/**
 * Framework heading field file.
 *
 * @link https://shapedplugin.com
 *
 * @package logo-carousel-free
 * @subpackage logo-carousel-free/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPLC_FREE_Field_heading' ) ) {
	/**
	 * SPF_WPSP_Fields_heading
	 */
	class SPLC_FREE_Field_heading extends SPLC_FREE_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * The render method.
		 *
		 * @return void
		 */
		public function render() {
			$version = ! empty( $this->field['version'] ) ? $this->field['version'] : '';
			echo ( ! empty( $this->field['content'] ) ) ? wp_kses_post( $this->field['content'] ) : '';
			echo ( ! empty( $this->field['image'] ) ) ? '<div class="heading-wrapper"><img src="' . esc_url( $this->field['image'] ) . '"><span class="splogocarousel-version">' . esc_html( $version ) . '</span></div>' : '';

			echo ( ! empty( $this->field['after'] ) && ! empty( $this->field['link'] ) ) ? '
			<div class="sp_lcp_shortcode_header_support">
				<span class="support-area">
					<i class="fa fa-support"></i> ' . esc_html__( 'Support', 'logo-carousel-free' ) . '
				</span>
				<div class="splogocarousel-help-text splogocarousel-support">
					<div class="splogocarousel-info-label">' . esc_html__( 'Documentation', 'logo-carousel-free' ) . '</div>
					' . esc_html__( 'Check out our documentation and more information about what you can do with the Logo Carousel.', 'logo-carousel-free' ) . '
					<a class="splogocarousel-open-docs browser-docs" href="https://docs.shapedplugin.com/docs/logo-carousel-free/introduction/" target="_blank">' . esc_html__( 'Browse Docs', 'logo-carousel-free' ) . '</a>
						
					<div class="splogocarousel-info-label">' . esc_html__( 'Need Help or Missing a Feature?', 'logo-carousel-free' ) . '</div>
					' . esc_html__( 'Feel free to get help from our friendly support team or request a new feature if needed. We appreciate your suggestions to make the plugin better.', 'logo-carousel-free' ) . '
					
					<a class="splogocarousel-open-docs support" href="https://shapedplugin.com/create-new-ticket/" target="_blank">' . esc_html__( 'Get Help', 'logo-carousel-free' ) . '</a>
					<a class="splogocarousel-open-docs feature-request" href="https://shapedplugin.com/contact-us/" target="_blank">' . esc_html__( 'Request a Feature', 'logo-carousel-free' ) . '</a>
				</div>
			</div>	
			' : '';//phpcs:ignore
		}
	}
}
