<?php
/**
 * Update version.
 */
update_option( 'logo_carousel_free_version', '3.6.0' );
update_option( 'logo_carousel_free_db_version', '3.6.0' );

/**
 * Update old to new typography.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'sp_lc_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '500',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );

if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {

		/**
		 * Collect metadata
		 */
		$shortcode_data = get_post_meta( $shortcode_id, 'sp_lcp_shortcode_options', true );

		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}

		/**
		 * Multi rows option updater
		 */
		$lcp_layout                = isset( $shortcode_data['lcp_layout'] ) ? $shortcode_data['lcp_layout'] : '';

		// Update main layouts.
		if ( $lcp_layout ) {
			$layout_data['lcp_layout'] = $lcp_layout;
		}

		/**
		 * Update metadata
		 */
		update_post_meta( $shortcode_id, 'sp_lcp_layout_options', $layout_data );
	}
}
