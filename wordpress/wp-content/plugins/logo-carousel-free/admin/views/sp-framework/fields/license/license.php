<?php
/**
 * Framework license fields file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Logo_Carousel_Free
 * @subpackage Logo_Carousel_Free/sp-framework
 */


if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPLC_FREE_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.3.16
	 * @version 3.3.16
	 */
	class SPLC_FREE_Field_license extends SPLC_FREE_Fields {

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
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			?>
			<div class="logo-carousel-lite-license text-center">
				<h3><?php esc_html_e( 'You\'re using Logo Carousel Lite - No License Needed. Enjoy', 'logo-carousel-free' ); ?>! 🙂</h3>
				<p><?php esc_html_e( 'Upgrade to Logo Carousel Pro and unlock all the features.', 'logo-carousel-free' ); ?></p>
				<div class="logo-carousel-lite-license-area">
					<div class="lcp-upgrade-button">
					<b><a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><?php esc_html_e( 'Upgrade To Pro Now', 'logo-carousel-free' ); ?></a></b>
					</div>
				</div>
			</div>
			<?php
			echo wp_kses_post( $this->field_after() );
		}

	}
}
