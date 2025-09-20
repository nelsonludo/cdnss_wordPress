<?php
/**
 * Preloader.
 *
 * @package    logo-carousel-free
 * @subpackage logo-carousel-free/public
 */

if ( $preloader ) {
	$preloader_class = ' lcp-preloader';
	$preloader_image = SP_LC_URL . 'admin/assets/images/spinner.svg';
	if ( ! empty( $preloader_image ) ) { ?>
		<div id="lcp-preloader-<?php echo esc_attr( $post_id ); ?>" class="sp-logo-carousel-preloader"><img src="<?php echo esc_url( $preloader_image ); ?>" class="skip-lazy" alt="<?php esc_attr_e( 'Loader Image', 'logo-carousel-free' ); ?>"/></div>
		<?php
	}
}
