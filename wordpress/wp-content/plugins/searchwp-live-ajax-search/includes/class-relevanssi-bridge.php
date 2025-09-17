<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Relevanssi_Bridge.
 *
 * @since 1.8.0
 */
class SearchWP_Live_Search_Relevanssi_Bridge {

	/**
	 * Hooks.
	 *
	 * @since 1.8.0
	 */
	public function hooks() {

		add_filter( 'searchwp_live_search_query_args', [ __CLASS__, 'alter_results' ] );
	}

	/**
	 * Alter Live Ajax Search results.
	 *
	 * @param array $args Arguments.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	public static function alter_results( $args ) {

		if ( function_exists( 'relevanssi_do_query' ) ) {
			$args['relevanssi'] = true;
		}

		return $args;
	}
}
