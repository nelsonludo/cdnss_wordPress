<?php
/**
 * This file is to update options of Logo Carousel Free.
 *
 * @package logo-carousel-free
 */

/**
 * Update version.
 */
update_option( 'logo_carousel_free_version', '3.6.5' );
update_option( 'logo_carousel_free_db_version', '3.6.5' );

// If the transient exists, delete it.
if ( get_transient( 'sp-logo-carousel_plugins' ) ) {
	delete_transient( 'sp-logo-carousel_plugins' );
}
