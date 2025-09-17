<?php
/**
 * Carousel output wrapper.
 *
 * @package    logo-carousel-free
 * @subpackage logo-carousel-free/public
 */

?>

<div id='logo-carousel-free-<?php echo esc_attr( $post_id ); ?>' class="logo-carousel-free logo-carousel-free-area sp-lc-container">
	<?php

	// Preloader.
	require SP_LC_PATH . 'public/views/templates/preloader.php';
	// Section title.
	require SP_LC_PATH . 'public/views/templates/section-title.php';
	?>
	<div id="sp-logo-carousel-id-<?php echo esc_attr( $post_id ); ?>" class="swiper-container sp-logo-carousel <?php echo esc_attr( $preloader_class ); ?>" dir="<?php echo esc_attr( $rtl ); ?>" <?php echo wp_kses_post( $swiper_data_attr ); ?>>
		<div class="swiper-wrapper">
			<?php require SP_LC_PATH . 'public/views/loop/carousel-item.php'; ?>
		</div>

		<?php
		if ( 'true' === $dots || 'true' === $dots_mobile ) {
			?>
			<div class="sp-lc-pagination swiper-pagination dots"></div>
			<?php
		}
		if ( 'true' === $nav || 'true' === $nav_mobile ) {
			?>
			<div class="sp-lc-button-next"><i class="fa fa-angle-right"></i></div>
			<div class="sp-lc-button-prev"><i class="fa fa-angle-left"></i></div>
			<?php
		}
		?>
	</div>
</div>
