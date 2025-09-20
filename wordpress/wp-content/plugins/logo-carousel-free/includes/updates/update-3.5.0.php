<?php
/**
 * Update version.
 */
update_option( 'logo_carousel_free_version', '3.5.0' );
update_option( 'logo_carousel_free_db_version', '3.5.0' );

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
		$logo_shortcode_data = get_post_meta( $shortcode_id, 'sp_lcp_shortcode_options', true );

		if ( ! is_array( $logo_shortcode_data ) ) {
			continue;
		}

		// Carousel navigation enable and position.
		$old_nav_data = isset( $logo_shortcode_data['lcp_nav_show'] ) ? $logo_shortcode_data['lcp_nav_show'] : '';
		switch ( $old_nav_data ) {
			case 'show':
				$logo_shortcode_data['lcp_carousel_navigation']['lcp_nav_show'] = true;
				break;
			case 'hide':
				$logo_shortcode_data['lcp_carousel_navigation']['lcp_nav_show'] = false;
				break;
			case 'hide_on_mobile':
				$logo_shortcode_data['lcp_carousel_navigation']['lcp_nav_show']       = true;
				$logo_shortcode_data['lcp_carousel_navigation']['lcp_hide_on_mobile'] = true;
				break;
		}
		if ( isset( $logo_shortcode_data['lcp_logo_shadow_type'] ) && 'none' === $logo_shortcode_data['lcp_logo_shadow_type'] ) {
			$logo_shortcode_data['lcp_logo_shadow_type'] = 'off';
		}

		// Carousel pagination.
		$old_show_pagination = isset( $logo_shortcode_data['lcp_carousel_dots'] ) ? $logo_shortcode_data['lcp_carousel_dots'] : '';
		switch ( $old_show_pagination ) {
			case 'show':
				$logo_shortcode_data['lcp_carousel_pagination']['lcp_carousel_dots'] = true;
				break;
			case 'hide':
				$logo_shortcode_data['lcp_carousel_pagination']['lcp_carousel_dots'] = false;
				break;
			case 'hide_on_mobile':
				$logo_shortcode_data['lcp_carousel_pagination']['lcp_carousel_dots']             = true;
				$logo_shortcode_data['lcp_carousel_pagination']['lcp_pagination_hide_on_mobile'] = true;
				break;
		}
		update_post_meta( $shortcode_id, 'sp_lcp_shortcode_options', $logo_shortcode_data );
	}
}
