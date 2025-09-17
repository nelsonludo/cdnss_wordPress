<?php
/**
 * Grid output wrapper.
 *
 * @package    logo-carousel-free
 * @subpackage logo-carousel-free/public
 */

?>

<div id="logo-carousel-free-<?php echo esc_attr( $post_id ); ?>" class="logo-carousel-free logo-carousel-free-area layout-<?php echo esc_attr( $layout . ' sp-lc-id-' . $post_id . ' sp-logo-section-id-' . $post_id ); ?>">

	<?php
	// Preloader.
	require SP_LC_PATH . 'public/views/templates/preloader.php';
	// Section title.
	require SP_LC_PATH . 'public/views/templates/section-title.php';
	?>

	<div id="sp-logo-carousel-id-<?php echo esc_attr( $post_id ); ?>" class="sp-logo-carousel <?php echo esc_attr( $preloader_class ); ?> sp-lc-grid-container" data-layout="<?php echo esc_attr( $layout ); ?>">
		<?php require SP_LC_PATH . 'public/views/loop/grid-item.php'; ?>
	</div>
	
</div>
