<?php

use SearchWP_Live_Search_Storage as Storage;
use SearchWP_Live_Search_Utils as Utils;

/**
 * Display search forms on the frontend.
 *
 * @since 1.8.0
 */
class SearchWP_Live_Search_Frontend {

	/**
	 * Init.
	 *
	 * @since 1.8.0
	 */
	public function init() {

		add_shortcode( 'searchwp_form', [ __CLASS__, 'render' ] );

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.8.0
	 */
	public function hooks() {

		self::register_gutenberg_block();

		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_filter( 'block_categories_all', [ __CLASS__, 'register_gutenberg_block_category' ] );
		} else {
			add_filter( 'block_categories', [ __CLASS__, 'register_gutenberg_block_category' ] );
		}

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'assets' ] );
	}

	/**
	 * Register Search Forms Gutenberg block.
	 *
	 * @since 1.8.0
	 */
	private static function register_gutenberg_block() {

		register_block_type( SEARCHWP_LIVE_SEARCH_PLUGIN_DIR . 'assets/gutenberg/build/search-forms', [ 'render_callback' => [ __CLASS__, 'render' ] ] );

		wp_localize_script( 'searchwp-search-form-editor-script', 'searchwpForms', Storage::get_all() );
	}

	/**
	 * Add a block category for SearchWP if it doesn't exist already.
     *
     * @since 1.8.0
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array
	 */
	public static function register_gutenberg_block_category( $categories ) {

		$category_slugs = wp_list_pluck( $categories, 'slug' );

		return in_array( 'searchwp', $category_slugs, true ) ? $categories : array_merge(
			$categories,
			[
				[
					'slug'  => 'searchwp',
					'title' => 'SearchWP',
					'icon'  => null,
				],
			]
		);
	}

	/**
	 * Load frontend assets.
	 *
	 * @since 1.8.0
	 */
	public static function assets() {

		// If WP is in script debug, or we pass ?script_debug in a URL - set debug to true.
		$debug = Utils::get_debug_assets_suffix();

		wp_register_style(
			'searchwp-forms',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . "assets/styles/frontend/search-forms{$debug}.css",
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		global $post;

		if ( ! is_a( $post, 'WP_Post' ) ) {
			return;
		}

		if ( ! has_shortcode( $post->post_content, 'searchwp_form' ) ) {
			return;
		}

		wp_enqueue_style( 'searchwp-forms' );
	}

	/**
	 * Render form.
	 *
	 * @since 1.8.0
	 *
	 * @param array $args Args from a shortcode or a Gutenberg block.
	 */
	public static function render( $args ) {

		$form_id = isset( $args['id'] ) ? absint( $args['id'] ) : 0;

		if ( empty( $form_id ) ) {
			return '';
		}

		$form = Storage::get( $form_id );

		if ( empty( $form ) ) {
			return '';
		}

		self::enqueue_assets();

		ob_start();

		self::display_styles( $form );
		?>
		<form id="<?php echo esc_attr( self::get_form_element_id( $form ) ); ?>" role="search" method="get" class="searchwp-form" action="<?php echo esc_url( self::get_form_action( $form ) ); ?>">
			<input type="hidden" name="swp_form[form_id]" value="<?php echo absint( $form_id ); ?>">
			<div class="swp-flex--col swp-flex--wrap swp-flex--gap-md">
				<div class="swp-flex--row swp-items-stretch swp-flex--gap-md">
					<div class="searchwp-form-input-container swp-items-stretch">

						<?php $search_input_name = self::get_search_input_name( $form ); ?>
						<?php $search_query = ! empty( $_GET[ $search_input_name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $search_input_name ] ) ) : get_search_query(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
						<input type="search"
                            class="swp-input--search swp-input"
						    placeholder="<?php echo esc_attr( $form['field-label'] ); ?>"
						    value="<?php echo esc_attr( $search_query ); ?>"
                            name="<?php echo esc_attr( $search_input_name ); ?>"
						    title="<?php echo esc_attr( $form['field-label'] ); ?>"
							<?php echo ( function_exists( 'searchwp_live_search' ) && searchwp_live_search()->get( 'Settings_Api' )->get( 'enable-live-search' ) ) ? ' data-swplive="true"' : ''; ?>
                        />
					</div>

					<?php if ( ! empty( $form['search-button'] ) ) : ?>
						<input type="submit" class="search-submit swp-button" value="<?php echo esc_attr( self::get_button_value( $form ) ); ?>"/>
					<?php endif; ?>

				</div>
			</div>
		</form>
		<?php

		return ob_get_clean();
	}

	/**
	 * Gets the input name for the current form.
	 *
	 * @since 1.8.0
	 *
	 * @param array $form Form data.
	 *
	 * @return mixed|string
	 */
	private static function get_search_input_name( $form ) {

		return ! empty( $form['input_name'] ) ? $form['input_name'] : 's';
	}

	/**
	 * Get the form action for the current form.
	 *
	 * @since 1.8.0
	 *
	 * @param array $form Form data.
	 *
	 * @return string
	 */
	private static function get_form_action( $form ) {

		return home_url( ! empty( $form['target_url'] ) ? $form['target_url'] : '/' );
	}

	/**
	 * Get the button value for the current form.
	 *
	 * @since 1.8.0
	 *
	 * @param array $form Form data.
	 *
	 * @return string
	 */
	private static function get_button_value( $form ) {

		return ! empty( $form['button-label'] ) ? $form['button-label'] : __( 'Search', 'searchwp-live-ajax-search' );
	}

	/**
	 * Enqueue assets.
	 *
	 * @since 1.8.0
	 */
	private static function enqueue_assets() {

		if ( ! wp_style_is( 'searchwp-forms', 'enqueued' ) ) {
			wp_print_styles( [ 'searchwp-forms' ] );
		}

		if ( ! wp_script_is( 'searchwp-forms', 'enqueued' ) ) {
			wp_enqueue_script( 'searchwp-forms' );
		}
	}

	/**
	 * Display unique styles for a specific form.
	 *
	 * @since 1.8.0
	 *
	 * @param array $form Form data.
	 */
	private static function display_styles( $form ) {

		$el_id = '#' . self::get_form_element_id( $form );
		?>
		<style>
			<?php if ( isset( $form['swp-sfinput-shape'] ) ) : ?>
				<?php self::display_input_styles( $form, $el_id ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $form['search-form-font-size'] ) ) : ?>
                <?php echo esc_html( $el_id ); ?> * {
                    font-size: <?php echo absint( $form['search-form-font-size'] ); ?>px;
                }
			<?php endif; ?>

			<?php if ( ! empty( $form['button-background-color'] ) && isset( $form['swp-sfbutton-filled'] ) && $form['swp-sfbutton-filled'] === 'filled' ) : ?>
				<?php echo esc_html( $el_id ); ?> input[type=submit] {
                    background-color: <?php echo esc_html( $form['button-background-color'] ); ?>;
                }
			<?php endif; ?>

			<?php if ( isset( $form['swp-sfbutton-filled'] ) && $form['swp-sfbutton-filled'] === 'stroked' ) : ?>
				<?php echo esc_html( $el_id ); ?> input[type=submit] {
                    background-color: transparent;
                    border: 1px solid<?php echo ! empty( $form['search-form-color'] ) ? esc_html( $form['search-form-color'] ) : ''; ?>;
                }
			<?php endif; ?>

			<?php if ( ! empty( $form['button-font-color'] ) ) : ?>
				<?php echo esc_html( $el_id ); ?> input[type=submit] {
                    color: <?php echo esc_html( $form['button-font-color'] ); ?>;
                }
			<?php endif; ?>

			<?php if ( ! empty( $form['button-font-size'] ) ) : ?>
				<?php echo esc_html( $el_id ); ?> input[type=submit] {
                    font-size: <?php echo absint( $form['button-font-size'] ); ?>px;
                }
			<?php endif; ?>
		</style>
		<?php
	}

	/**
	 * Display unique input styles for a specific form.
	 *
	 * @since 1.8.0
	 *
	 * @param array  $form  Form data.
	 * @param string $el_id Form element id.
	 */
	private static function display_input_styles( $form, $el_id ) {

		if ( $form['swp-sfinput-shape'] === 'rectangle' ) :
			?>
			<?php echo esc_html( $el_id ); ?> .swp-input {
				border: 1px solid <?php echo esc_html( $form['search-form-color'] ); ?>;
				border-radius: 0;
			}
			<?php echo esc_html( $el_id ); ?> input[type=submit] {
				border-radius: 0;
			}
		<?php endif; ?>

		<?php if ( $form['swp-sfinput-shape'] === 'rounded' ) : ?>
			<?php echo esc_html( $el_id ); ?> .swp-input {
				border: 1px solid <?php echo esc_html( $form['search-form-color'] ); ?>;
				border-radius: 5px;
			}
			<?php echo esc_html( $el_id ); ?> input[type=submit] {
				border-radius: 5px;
			}
		<?php endif; ?>

		<?php if ( $form['swp-sfinput-shape'] === 'underlined' ) : ?>
			<?php echo esc_html( $el_id ); ?> .swp-input {
				border: 0;
				border-bottom: 1px solid <?php echo esc_html( $form['search-form-color'] ); ?>;
				border-radius: 0;
			}
		<?php endif; ?>

		<?php
	}

	/**
	 * Get form HTML element id.
	 *
	 * @since 1.8.0
	 *
	 * @param array $form Form data.
	 */
	private static function get_form_element_id( $form ) {

		$form_id = isset( $form['id'] ) ? absint( $form['id'] ) : 0;

		return 'searchwp-form-' . $form_id;
	}

	/**
	 * Disable Gutenberg block rendering.
	 *
	 * @param string|null $pre_render   The pre-rendered content. Default null.
	 * @param array       $parsed_block The block being rendered.
	 *
	 * @since 1.8.0
	 */
	public static function disable_block_render( $pre_render, $parsed_block ) {

		if ( ! isset( $parsed_block['blockName'] ) ) {
			return $pre_render;
		}

		return $parsed_block['blockName'] === 'searchwp/search-form' ? '' : $pre_render;
	}

	/**
	 * Disable Shortcode rendering.
	 *
	 * @param false|string $output Short-circuit return value. Either false or the value to replace the shortcode with.
	 * @param string       $tag    Shortcode name.
	 *
	 * @since 1.8.0
	 */
	public static function disable_shortcode_render( $output, $tag ) {

		return $tag === 'searchwp_form' ? '' : $output;
	}
}
