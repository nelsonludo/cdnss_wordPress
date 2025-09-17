<?php

use SearchWP_Live_Search_Utils as Utils;

/**
 * Manage Search Forms DB storage.
 *
 * @since 1.8.0
 */
class SearchWP_Live_Search_Storage {

	/**
	 * Add a new form with default data.
	 *
	 * @since 1.8.0
	 *
	 * @return int Form id.
	 */
	public static function add() {

		$option = self::get_option();

		$id = isset( $option['next_id'] ) ? absint( $option['next_id'] ) : 0;
		$id = ! empty( $id ) ? $id : 1;

		$form = [
			'id'                      => $id,
			'title'                   => sprintf(
			/* translators: %d: Form id. */
				__( 'Search Form %d', 'searchwp-live-ajax-search' ),
				$id
			),
			'engine'                  => 'default',
			'target_url'              => '/',
			'input_name'              => 's',
			'swp-layout-theme'        => 'basic',
			'category-search'         => false,
			'quick-search'            => false,
			'advanced-search'         => false,
			'post-type'               => [],
			'category'                => [],
			'field-label'             => '',
			'search-button'           => true,
			'quick-search-items'      => [],
			'advanced-search-filters' => [ 'authors', 'post_types', 'tags' ],
			'swp-sfinput-shape'       => '',
			'swp-sfbutton-filled'     => '',
			'search-form-color'       => '',
			'search-form-font-size'   => '',
			'button-label'            => '',
			'button-background-color' => '',
			'button-font-color'       => '',
			'button-font-size'        => '',
		];

		$option['forms'][ $id ] = $form;
		$option['next_id']      = $id + 1;

		self::update_option( $option );

		return $id;
	}

	/**
	 * Get a single form data.
	 *
	 * @since 1.8.0
	 *
	 * @param int $id Form id.
	 *
	 * @return array
	 */
	public static function get( $id ) {

		$form_id = absint( $id );

		if ( empty( $form_id ) ) {
			return [];
		}

		$forms = self::get_all();

		$form = isset( $forms[ $form_id ] ) ? $forms[ $form_id ] : [];

		/**
		 * Filter the Forms Settings.
		 *
		 * @param array $form    The Form settings array.
		 * @param int   $form_id The Form id.
		 */
		return apply_filters( 'searchwp\forms\settings', $form, $form_id );
	}

	/**
	 * Update a single form data.
	 *
	 * @since 1.8.0
	 *
	 * @param int   $id   Form id.
	 * @param array $data Form data.
	 *
	 * @return void
	 */
	public static function update( $id, $data ) {

		$form_id = absint( $id );
		if ( empty( $form_id ) ) {
			return;
		}

		$option = self::get_option();

		$option['forms'][ $form_id ] = $data;

		self::update_option( $option );
	}

	/**
	 * Delete a single form.
	 *
	 * @since 1.8.0
	 *
	 * @param int $id Form id.
	 *
	 * @return void
	 */
	public static function delete( $id ) {

		$option = self::get_option();

		$form_id = absint( $id );
		if ( empty( $form_id ) ) {
			return;
		}

		if ( ! isset( $option['forms'][ $form_id ] ) ) {
			return;
		}

		unset( $option['forms'][ $form_id ] );

		self::update_option( $option );
	}

	/**
	 * Get all forms data.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	public static function get_all() {

		$option = self::get_option();

		return ! empty( $option['forms'] ) ? $option['forms'] : [];
	}

	/**
	 * Get forms DB option.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	private static function get_option() {

		return json_decode( get_option( 'searchwp_forms' ), true );
	}

	/**
	 * Update forms DB option.
	 *
	 * @since 1.8.0
	 *
	 * @param array $data Option data.
	 *
	 * @return mixed
	 */
	private static function update_option( $data ) {

		$data = wp_json_encode( $data );

		wp_cache_delete( Utils::SEARCHWP_PREFIX . 'settings_forms' );

		update_option( Utils::SEARCHWP_PREFIX . 'forms', $data, false );

		// By default, WP_Cache will return `false` if the key doesn't exist, but sometimes
		// our intended value is `false` so we're going to wrap this in an array.
		wp_cache_set( Utils::SEARCHWP_PREFIX . 'settings_forms', [ 'forms' => $data ], '', 1 );

		return $data;
	}
}
