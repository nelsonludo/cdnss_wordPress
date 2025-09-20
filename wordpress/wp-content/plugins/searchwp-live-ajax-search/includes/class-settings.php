<?php

use SearchWP_Live_Search_Utils as Utils;
use SearchWP_Live_Search_License as License;
use SearchWP\Admin\NavTab;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Settings.
 *
 * The SearchWP Live Ajax Search settings.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Settings {

	/**
	 * The slug of this view.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	public static $slug = 'live-search';

	/**
	 * Hooks.
	 *
	 * @since 1.7.0
	 */
	public static function hooks() {

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );

        if ( Utils::is_searchwp_active() ) {
            self::hooks_searchwp_enabled();
        } else {
	        self::hooks_searchwp_disabled();
        }
	}

	/**
	 * Outputs the assets needed for the Settings page.
	 *
	 * @since 1.7.0
	 *
	 * @return void
	 */
	public static function assets() {

		if ( ! Utils::is_settings_page() ) {
			return;
		}

		$handle = Utils::SEARCHWP_LIVE_SEARCH_PREFIX . self::$slug;

		wp_enqueue_script( 'iris' );

		wp_enqueue_script(
			Utils::SEARCHWP_LIVE_SEARCH_PREFIX . 'choicesjs',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/vendor/choicesjs/js/choices-10.2.0.min.js',
			[],
			'10.2.0',
			true
		);

		wp_enqueue_style(
			Utils::SEARCHWP_LIVE_SEARCH_PREFIX . 'choicesjs',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/vendor/choicesjs/css/choices-10.2.0.min.css',
			[],
			'10.2.0'
		);

		wp_enqueue_script(
			$handle,
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/js/admin/settings.js',
			[
				'underscore',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'choices',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'collapse',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'color-picker',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'copy-input-text',
			],
			SEARCHWP_LIVE_SEARCH_VERSION,
			true
		);

		Utils::localize_script( $handle );

		wp_enqueue_style(
			'searchwp-live-search-styles',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/style.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		wp_enqueue_style(
			$handle,
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/settings.css',
			[
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'choicesjs',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'collapse-layout',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'color-picker',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'input',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'toggle-switch',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'radio-img',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'style',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'buttons',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'content-header',
				Utils::SEARCHWP_LIVE_SEARCH_SLUG . 'layout',
			],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		if ( ! Utils::is_searchwp_active() ) {

			// FontAwesome.
			wp_enqueue_style(
				'searchwp-font-awesome',
				SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/vendor/fontawesome/css/font-awesome.min.css',
				null,
				'4.7.0'
			);
		}
	}

	/**
	 * Hooks if SearchWP is enabled.
	 *
	 * @since 1.7.0
	 */
	private static function hooks_searchwp_enabled() {

		add_action(
			'searchwp\settings\nav\after',
			function () {
				if ( ! class_exists( '\\SearchWP\\Admin\\NavTab' ) ) {
					return;
				}
				if ( ! Utils::is_parent_settings_page() ) {
					return;
				}
				new NavTab(
					[
						'page'  => 'forms',
						'tab'   => 'live-search',
						'label' => esc_html__( 'Live Search', 'searchwp-live-ajax-search' ),
					]
				);
			}
		);

		if ( Utils::is_settings_page() ) {
			add_action( 'searchwp\settings\view', [ __CLASS__, 'render' ] );
		}
	}

	/**
	 * Hooks if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 */
	private static function hooks_searchwp_disabled() {

		add_filter( 'searchwp_live_search_settings_sub_header_items', [ __CLASS__, 'add_sub_header_items' ], 10 );

		add_filter( 'admin_footer_text', [ __CLASS__, 'admin_footer_rate_us_searchwp_disabled' ], 1, 2 );

		add_filter( 'update_footer', [ __CLASS__, 'admin_footer_hide_wp_version_searchwp_disabled' ], PHP_INT_MAX );

		add_action( 'admin_print_scripts', [ __CLASS__, 'admin_hide_unrelated_notices' ] );

		add_action( 'wp_ajax_' . utils::SEARCHWP_LIVE_SEARCH_PREFIX . 'license_activate',  [ __CLASS__, 'activate_license_ajax' ] );
	}

	/**
	 * Add the Live Search Settings sub header items.
	 *
	 * @since 1.8.0
	 *
	 * @param array $items The sub header items.
	 *
	 * @return mixed
	 */
	public static function add_sub_header_items( $items ) {

		$items[ 'searchwp-' . self::$slug ][] = [
			'page'  => 'searchwp-' . self::$slug,
			'tab'   => '',
			'label' => esc_html__( 'Live Search', 'searchwp-live-ajax-search' ),
		];

		return $items;
	}

	/**
	 * Renders the Settings page.
	 *
	 * @since 1.8.0
	 */
	public static function render() {

		$settings = searchwp_live_search()
					->get( 'Settings_Api' )
					->get();

		?>
		<div class="swp-content-container">

			<?php self::render_settings_header(); ?>

			<?php self::render_settings_general( $settings ); ?>

			<?php self::render_settings_theme( $settings ); ?>

			<?php self::render_settings_styling( $settings ); ?>

			<?php self::render_settings_results( $settings ); ?>

			<?php self::render_settings_misc( $settings ); ?>

			<?php self::render_after_settings(); ?>

        </div>
		<?php
	}

	/**
	 * Output the settings header.
	 *
	 * @since 1.8.0
	 */
	private static function render_settings_header() {
		?>
		<div class="swp-page-header">

			<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30 swp-justify-between swp-flex--align-c sm:swp-flex--align-start">

				<div class="swp-flex--row swp-flex--gap15 swp-flex--align-c">

					<h1 class="swp-h1 swp-page-header--h1">
						<?php esc_html_e( 'Settings', 'searchwp-live-ajax-search' ); ?>
					</h1>

				</div>

				<div class="swp-flex--row swp-flex--gap15 swp-flex--grow0">

					<button type="button" id="swp-settings-save" class="swp-button swp-button--green">
						<?php esc_html_e( 'Save', 'searchwp-live-ajax-search' ); ?>
					</button>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings general section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_general( $settings ) {
		?>
		<div class="swp-collapse swp-opened">

			<div class="swp-collapse--header">

				<h2 class="swp-h2">
					<?php esc_html_e( 'General', 'searchwp-live-ajax-search' ); ?>
				</h2>

				<button class="swp-expand--button">

					<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"/>
					</svg>

				</button>

			</div>

			<div class="swp-collapse--content">

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'Enable Live Search', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>

							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<label class="swp-toggle">

										<input class="swp-toggle-checkbox" type="checkbox" name="enable-live-search"<?php checked( $settings['enable-live-search'] ); ?>>

										<div class="swp-toggle-switch"></div>

									</label>

									<p class="swp-desc">
										<?php esc_html_e( 'Check this option to automatically enhance your search forms with Live Ajax Search.' , 'searchwp-live-ajax-search' ); ?>
									</p>

								</div>

								<?php
								echo self::get_dyk_block_output(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>

							</div>

						</div>

						<?php self::render_license_field(); ?>

					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Output License field.
	 *
	 * @since 1.8.0
	 */
	private static function render_license_field() {

		if ( ! Utils::is_searchwp_active() ) :

			$license_key = License::get_license_key();

			$action = Utils::is_searchwp_installed() && License::is_active() ?
				__( 'Activate SearchWP Pro', 'searchwp-live-ajax-search' ) :
				__( 'Verify Key', 'searchwp-live-ajax-search' );
			?>

			<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

				<div class="swp-col swp-col--title-width--sm">

					<h3 class="swp-h3">
						<?php esc_html_e( 'Upgrade to Pro', 'searchwp-live-ajax-search' ); ?>
					</h3>

				</div>

				<div class="swp-col">

					<div class="swp-flex--row sm:swp-flex--wrap swp-flex--gap12">

						<input id="swp-license" class="swp-input swp-w-2/5" type="<?php echo License::is_active() ? 'password' : 'text'; ?>" value="<?php echo esc_attr( $license_key ); ?>">

						<button id="swp-license-activate" class="swp-button swp-button--green"><?php echo esc_html( $action ); ?></button>

					</div>

					<p id="swp-license-error-msg" class="swp-desc--btm swp-text-red" style="display:none"></p>

					<p class="swp-desc--btm">Your SearchWP Pro license key can be found in your <a href="https://searchwp.com/account/?utm_campaign=plugin&utm_source=WordPress&utm_medium=settings-license&utm_content=Account%20Dashboard" target="_blank" rel="noopener noreferrer">SearchWP Account Dashboard</a>. Don’t have a license? <a href="https://searchwp.com/buy/?utm_campaign=plugin&utm_source=WordPress&utm_medium=settings-license&utm_content=License%20Key%20Sign%20Up" target="_blank" rel="noopener noreferrer">Sign up today!</a></p>

				</div>

			</div>
		<?php
		endif;
	}

	/**
	 * Renders the settings theme section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_theme( $settings ) {
		?>
		<div class="swp-collapse swp-opened">

			<div class="swp-collapse--header">

				<h2 class="swp-h2">
					<?php esc_html_e( 'Choose a theme', 'searchwp-live-ajax-search' ); ?>
				</h2>

				<button class="swp-expand--button">

					<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"/>
					</svg>

				</button>

			</div>

			<div class="swp-collapse--content">

				<div class="swp-row">

					<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

						<div class="swp-col swp-col--title-width--sm">

							<h3 class="swp-h3">
								<?php esc_html_e( 'Layout Theme', 'searchwp-live-ajax-search' ); ?>
							</h3>

						</div>

						<div class="swp-col">

							<div class="swp-flex--row swp-flex--gap20 swp-las--layout-themes">

								<div class="swp-flex--grow1 swp-input--radio-img">

									<input type="radio" id="swp-minimal-theme" name="swp-layout-theme" value="minimal"<?php checked( $settings['swp-layout-theme'], 'minimal' ); ?> />

									<label for="swp-minimal-theme">

										<img src="<?php echo esc_url( SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/images/admin/pages/live-search/swp--minimal.svg' ); ?>" alt="" />

										<?php esc_html_e( 'Minimal', 'searchwp-live-ajax-search' ); ?>

									</label>

								</div>

								<div class="swp-flex--grow1 swp-input--radio-img">

									<input type="radio" id="swp-medium-theme" name="swp-layout-theme" value="medium"<?php checked( $settings['swp-layout-theme'], 'medium' ); ?> />

									<label for="swp-medium-theme">

										<img src="<?php echo esc_url( SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/images/admin/pages/live-search/swp--medium.svg' ); ?>" alt="" />

										<?php esc_html_e( 'Medium', 'searchwp-live-ajax-search' ); ?>

									</label>

								</div>

								<div class="swp-flex--grow1 swp-input--radio-img">

									<input type="radio" id="swp-rich-theme" name="swp-layout-theme" value="rich"<?php checked( $settings['swp-layout-theme'], 'rich' ); ?> />

									<label for="swp-rich-theme">

										<img src="<?php echo esc_url( SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/images/admin/pages/live-search/swp--rich.svg' ); ?>" alt="" />

										<?php esc_html_e( 'Rich', 'searchwp-live-ajax-search' ); ?>

									</label>

								</div>

								<div class="swp-flex--grow1 swp-input--radio-img">

									<input type="radio" id="swp-custom-theme" name="swp-layout-theme" value="custom"<?php checked( $settings['swp-layout-theme'], 'custom' ); ?> />

									<label for="swp-custom-theme">

										<img src="<?php echo esc_url( SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/images/admin/pages/live-search/swp--custom.svg' ); ?>" alt="" />

										<?php esc_html_e( 'Custom', 'searchwp-live-ajax-search' ); ?>

									</label>

								</div>

							</div>

							<h4 class="swp-h4 swp-margin-t30">
								<?php esc_html_e( 'Theme Preview', 'searchwp-live-ajax-search' ); ?>
							</h4>

							<div class="swp-las--theme-preview">

								<?php
								$results = [
									[
										'title'   => 'New crackers recipe available!',
										'content' => 'Crispy, crunchy multigrain crackers, loaded with seeds. Add delicious crunch to your day!',
										'price'   => '$18.00',
									],
									[
										'title'   => 'Fresh avocado crackers recipe!',
										'content' => 'Classic multigrain crackers, featuring a fresh avocado twist. Elevate your snacking experience with this delicious combination!',
										'price'   => '$16.00',
									],
									[
										'title'   => 'Multigrain crackers with banana topping!',
										'content' => 'Perfect blend of crunchy multigrain crackers, enriched with seeds and topped with sweet bananas.',
										'price'   => '$21.00',
									],
								];
								?>

								<div class="<?php echo esc_attr( self::get_container_classes( $settings ) ); ?>">

								<?php foreach ( $results as $index => $result ) { ?>

									<div class="searchwp-live-search-result">

										<div class="searchwp-live-search-result--img"<?php echo empty( $settings['swp-description-enabled'] ) ? ' style="display: none;"' : ''; ?>>

											<img src="<?php echo esc_url( SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/images/admin/pages/live-search/cracker00' . ( $index + 1 ) . '.jpg' ); ?>" alt="">

										</div>

										<div class="searchwp-live-search-result--info">

											<h4 class="searchwp-live-search-result--title">

												<a class="swp-a" role="link" aria-disabled="true">
													<?php echo esc_html( $result['title'] ); ?>
												</a>

											</h4>

											<p class="searchwp-live-search-result--desc"<?php echo empty( $settings['swp-description-enabled'] ) ? ' style="display: none;"' : ''; ?>>
												<?php echo esc_html( $result['content'] ); ?>
											</p>

										</div>

										<?php if ( Utils::is_ecommerce_plugin_active() ) { ?>

										<div class="searchwp-live-search-result--ecommerce">

											<div class="searchwp-live-search-result--price"<?php echo empty( $settings['swp-price-enabled'] ) ? ' style="display: none;"' : ''; ?>>
												<?php echo esc_html( $result['price'] ); ?>
											</div>

											<div class="searchwp-live-search-result--add-to-cart"<?php echo empty( $settings['swp-add-to-cart-enabled'] ) ? ' style="display: none;"' : ''; ?>>
												<a href="#" class="button" aria-disabled="true">Add to cart</a>
											</div>

										</div>

										<?php } ?>

									</div>

								<?php } ?>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings styling section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_styling( $settings ) {
		?>
		<div class="swp-collapse swp-opened">

			<div class="swp-collapse--header">

				<h2 class="swp-h2">
					<?php esc_html_e( 'Custom Styling', 'searchwp-live-ajax-search' ); ?>
				</h2>

				<button class="swp-expand--button">

					<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"/>
					</svg>

				</button>

			</div>

			<div class="swp-collapse--content">

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<?php self::render_settings_styling_description( $settings ); ?>

					</div>

				</div>

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<?php self::render_settings_styling_image( $settings ); ?>

					</div>

				</div>

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<?php self::render_settings_styling_title( $settings ); ?>

					</div>

				</div>

				<?php if ( Utils::is_ecommerce_plugin_active() ) : ?>

					<?php self::render_settings_styling_ecommerce( $settings ); ?>

				<?php endif; ?>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings styling image section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_styling_image( $settings ) {
		?>
		<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

			<div class="swp-col swp-col--title-width--sm">

				<h3 class="swp-h3">
					<?php esc_html_e( 'Image', 'searchwp-live-ajax-search' ); ?>
				</h3>

			</div>

			<div class="swp-col">

				<div class="swp-w-1/4">

					<select class="swp-choicesjs-single" name="swp-image-size">

						<option value="">
							<?php esc_html_e( 'None', 'searchwp-live-ajax-search' ); ?>
						</option>

						<option value="small"<?php selected( $settings['swp-image-size'], 'small' ); ?>>
							<?php esc_html_e( 'Small', 'searchwp-live-ajax-search' ); ?>
						</option>

						<option value="medium"<?php selected( $settings['swp-image-size'], 'medium' ); ?>>
							<?php esc_html_e( 'Medium', 'searchwp-live-ajax-search' ); ?>
						</option>

						<option value="large"<?php selected( $settings['swp-image-size'], 'large' ); ?>>
							<?php esc_html_e( 'Large', 'searchwp-live-ajax-search' ); ?>
						</option>

					</select>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings styling description section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_styling_description( $settings ) {
		?>
		<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

			<div class="swp-col swp-col--title-width--sm">

				<h3 class="swp-h3">
					<?php esc_html_e( 'Description', 'searchwp-live-ajax-search' ); ?>
				</h3>

			</div>

			<div class="swp-col">

				<label class="swp-toggle">

					<input class="swp-toggle-checkbox" type="checkbox" name="swp-description-enabled"<?php checked( $settings['swp-description-enabled'] ); ?>>

					<div class="swp-toggle-switch"></div>

				</label>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings title styling section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_styling_title( $settings ) {
		?>
		<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

			<div class="swp-col swp-col--title-width--sm">

				<h3 class="swp-h3">
					<?php esc_html_e( 'Title', 'searchwp-live-ajax-search' ); ?>
				</h3>

			</div>


			<div class="swp-col">

				<div class="swp-flex--row swp-flex--gap17">

					<div class="swp-inputbox-vertical">

						<label for="" class="swp-label">
							<?php esc_html_e( 'Color', 'searchwp-live-ajax-search' ); ?>
						</label>

						<span class="swp-input--colorpicker">
							<input type="text" class="swp-input" name="swp-title-color" value="<?php echo esc_attr( $settings['swp-title-color'] ); ?>" placeholder="default" maxlength="7">
							<svg fill="none" height="18" viewBox="0 0 18 18" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g mask="url(#a)"><path d="m9.74075 15.25c-1.53556 0-2.82666-.5274-3.90225-1.5897-1.07639-1.0631-1.60779-2.3322-1.60779-3.8353 0-.76007.1438-1.45237.42598-2.08339.28739-.64268.68359-1.21661 1.19118-1.72371l3.89288-3.8176 3.89285 3.81755c.5076.50712.9038 1.08106 1.1912 1.72376.2822.63102.426 1.32332.426 2.08339 0 1.5031-.5312 2.7723-1.6071 3.8353-1.0761 1.0623-2.3675 1.5897-3.90295 1.5897z" fill="#fff" stroke="#e1e1e1"/></g></svg>
						</span>

					</div>

					<div class="swp-inputbox-vertical">

						<label for="" class="swp-label">
							<?php esc_html_e( 'Font size', 'searchwp-live-ajax-search' ); ?>
						</label>

						<span class="swp-input--font-input">
							<input type="number" min="0" class="swp-input" name="swp-title-font-size"<?php echo ! empty( $settings['swp-title-font-size'] ) ? ' value="' . absint( $settings['swp-title-font-size'] ) . '"' : ''; ?> placeholder="-">
						</span>

					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings ecommerce styling section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_styling_ecommerce( $settings ) {
		?>
		<div class="swp-row">

			<div class="swp-flex--col swp-flex--gap30">

				<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

					<div class="swp-col swp-col--title-width--sm">

						<h3 class="swp-h3">
							<?php esc_html_e( 'Price', 'searchwp-live-ajax-search' ); ?>
						</h3>

					</div>

					<div class="swp-col">

						<div class="swp-flex--col swp-flex--gap30">

							<label class="swp-toggle">
								<input class="swp-toggle-checkbox" type="checkbox" name="swp-price-enabled"<?php checked( $settings['swp-price-enabled'] ); ?>>
								<div class="swp-toggle-switch"></div>
							</label>

							<div class="swp-flex--row swp-flex--gap17">

								<div class="swp-inputbox-vertical">

									<label for="" class="swp-label">
										<?php esc_html_e( 'Color', 'searchwp-live-ajax-search' ); ?>
									</label>

									<span class="swp-input--colorpicker">
										<input type="text" class="swp-input" name="swp-price-color" value="<?php echo esc_attr( $settings['swp-price-color'] ); ?>" placeholder="default" maxlength="7">
										<svg fill="none" height="18" viewBox="0 0 18 18" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g mask="url(#a)"><path d="m9.74075 15.25c-1.53556 0-2.82666-.5274-3.90225-1.5897-1.07639-1.0631-1.60779-2.3322-1.60779-3.8353 0-.76007.1438-1.45237.42598-2.08339.28739-.64268.68359-1.21661 1.19118-1.72371l3.89288-3.8176 3.89285 3.81755c.5076.50712.9038 1.08106 1.1912 1.72376.2822.63102.426 1.32332.426 2.08339 0 1.5031-.5312 2.7723-1.6071 3.8353-1.0761 1.0623-2.3675 1.5897-3.90295 1.5897z" fill="#fff" stroke="#e1e1e1"/></g></svg>
									</span>

								</div>

								<div class="swp-inputbox-vertical">

									<label for="" class="swp-label">
										<?php esc_html_e( 'Font size', 'searchwp-live-ajax-search' ); ?>
									</label>

									<span class="swp-input--font-input">
										<input type="number" min="0" class="swp-input" name="swp-price-font-size"<?php echo ! empty( $settings['swp-price-font-size'] ) ? ' value="' . absint( $settings['swp-price-font-size'] ) . '"' : ''; ?> placeholder="-">
									</span>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="swp-row">

			<div class="swp-flex--col swp-flex--gap30">

				<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

					<div class="swp-col swp-col--title-width--sm">

						<h3 class="swp-h3">
							<?php esc_html_e( 'Add to cart', 'searchwp-live-ajax-search' ); ?>
						</h3>

					</div>

					<div class="swp-col">

						<div class="swp-flex--col swp-flex--gap30">

							<label class="swp-toggle">
								<input class="swp-toggle-checkbox" type="checkbox" name="swp-add-to-cart-enabled"<?php checked( $settings['swp-add-to-cart-enabled'] ); ?>>
								<div class="swp-toggle-switch"></div>
							</label>

							<div class="swp-flex--row swp-flex--gap17">

								<div class="swp-inputbox-vertical">

									<label for="" class="swp-label">
										<?php esc_html_e( 'Background Color', 'searchwp-live-ajax-search' ); ?>
									</label>

									<span class="swp-input--colorpicker">
										<input type="text" class="swp-input" name="swp-add-to-cart-background-color" value="<?php echo esc_attr( $settings['swp-add-to-cart-background-color'] ); ?>" placeholder="default" maxlength="7">
										<svg fill="none" height="18" viewBox="0 0 18 18" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g mask="url(#a)"><path d="m9.74075 15.25c-1.53556 0-2.82666-.5274-3.90225-1.5897-1.07639-1.0631-1.60779-2.3322-1.60779-3.8353 0-.76007.1438-1.45237.42598-2.08339.28739-.64268.68359-1.21661 1.19118-1.72371l3.89288-3.8176 3.89285 3.81755c.5076.50712.9038 1.08106 1.1912 1.72376.2822.63102.426 1.32332.426 2.08339 0 1.5031-.5312 2.7723-1.6071 3.8353-1.0761 1.0623-2.3675 1.5897-3.90295 1.5897z" fill="#fff" stroke="#e1e1e1"/></g></svg>
									</span>

								</div>

								<div class="swp-inputbox-vertical">

									<label for="" class="swp-label">
										<?php esc_html_e( 'Font Color', 'searchwp-live-ajax-search' ); ?>
									</label>

									<span class="swp-input--colorpicker">
										<input type="text" class="swp-input" name="swp-add-to-cart-font-color" value="<?php echo esc_attr( $settings['swp-add-to-cart-font-color'] ); ?>" placeholder="default" maxlength="7">
										<svg fill="none" height="18" viewBox="0 0 18 18" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g mask="url(#a)"><path d="m9.74075 15.25c-1.53556 0-2.82666-.5274-3.90225-1.5897-1.07639-1.0631-1.60779-2.3322-1.60779-3.8353 0-.76007.1438-1.45237.42598-2.08339.28739-.64268.68359-1.21661 1.19118-1.72371l3.89288-3.8176 3.89285 3.81755c.5076.50712.9038 1.08106 1.1912 1.72376.2822.63102.426 1.32332.426 2.08339 0 1.5031-.5312 2.7723-1.6071 3.8353-1.0761 1.0623-2.3675 1.5897-3.90295 1.5897z" fill="#fff" stroke="#e1e1e1"/></g></svg>
									</span>

								</div>

								<div class="swp-inputbox-vertical">

									<label for="" class="swp-label">
										<?php esc_html_e( 'Font', 'searchwp-live-ajax-search' ); ?>
									</label>

									<span class="swp-input--font-input">
										<input type="number" min="0" class="swp-input" name="swp-add-to-cart-font-size"<?php echo ! empty( $settings['swp-add-to-cart-font-size'] ) ? ' value="' . absint( $settings['swp-add-to-cart-font-size'] ) . '"' : ''; ?> placeholder="-">
									</span>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings results section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_results( $settings ) {
		?>
		<div class="swp-collapse swp-opened">

			<div class="swp-collapse--header">

				<h2 class="swp-h2">
					<?php esc_html_e( 'Results', 'searchwp-live-ajax-search' ); ?>
				</h2>

				<button class="swp-expand--button">

					<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"/>
					</svg>

				</button>

			</div>

			<div class="swp-collapse--content">

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'Include Styling', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>

							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<div class="swp-w-1/4">

										<select class="swp-choicesjs-single" name="include-frontend-css">

											<option value="all">
												<?php esc_html_e( 'Positioning and visual styling', 'searchwp-live-ajax-search' ); ?>
											</option>

											<option value="position"<?php selected( $settings['include-frontend-css'], 'position' ); ?>>
												<?php esc_html_e( 'Positioning styling only', 'searchwp-live-ajax-search' ); ?>
											</option>

											<option value="none"<?php selected( $settings['include-frontend-css'], 'none' ); ?>>
												<?php esc_html_e( 'No styling', 'searchwp-live-ajax-search' ); ?>
											</option>

										</select>

									</div>

									<p class="swp-desc"><?php esc_html_e( 'Determines which CSS files to load and use for the site. "Positioning and visual styling" is recommended, unless you are experienced with CSS or instructed by support to change settings.', 'searchwp-live-ajax-search' ); ?></p>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'Positioning', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>


							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<div class="swp-w-1/4">

										<select class="swp-choicesjs-single" name="results-pane-position">

											<option value="bottom">
												<?php esc_html_e( 'Below the search form', 'searchwp-live-ajax-search' ); ?>
											</option>

											<option value="top"<?php selected( $settings['results-pane-position'], 'top' ); ?>>
												<?php esc_html_e( 'Above the search form', 'searchwp-live-ajax-search' ); ?>
											</option>

										</select>

									</div>

									<p class="swp-desc"><?php esc_html_e( 'Selects where to position the results pane relative to the search form.', 'searchwp-live-ajax-search' ); ?></p>

								</div>

							</div>

						</div>
					</div>
				</div>

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'Auto Width', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>

							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<label class="swp-toggle">

										<input class="swp-toggle-checkbox" type="checkbox" name="results-pane-auto-width"<?php checked( $settings['results-pane-auto-width'] ); ?>>

										<div class="swp-toggle-switch"></div>

									</label>

									<p class="swp-desc"><?php esc_html_e( 'Check this option to align the results pane width with the search form width.', 'searchwp-live-ajax-search' ); ?></p>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'Max results', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>

							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<div class="swp-inputbox-horizontal">

										<input type="number" min="-1" class="swp-input" name="swp-results-per-page"<?php echo ! empty( $settings['swp-results-per-page'] ) ? ' value="' . intval( $settings['swp-results-per-page'] ) . '"' : ''; ?> placeholder="7">

										<label for="swp-results-per-page" class="swp-label">
											<?php esc_html_e( 'Results', 'searchwp-live-ajax-search' ); ?>
										</label>

									</div>

									<p class="swp-desc">
										<?php esc_html_e( 'Choose how many results you want to show at maximum', 'searchwp-live-ajax-search' ); ?>
									</p>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'No Results Message', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>

							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<input type="text" class="swp-input swp-w-1/2" name="swp-no-results-message"<?php echo ! empty( $settings['swp-no-results-message'] ) ? ' value="' . esc_attr( $settings['swp-no-results-message'] ) . '"' : ''; ?>>

									<p class="swp-desc"><?php esc_html_e( 'Enter the text to display when no results are found.', 'searchwp-live-ajax-search' ); ?></p>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Renders the settings misc section.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	private static function render_settings_misc( $settings ) {
		?>
		<div class="swp-collapse swp-opened">

			<div class="swp-collapse--header">

				<h2 class="swp-h2">
					<?php esc_html_e( 'Misc', 'searchwp-live-ajax-search' ); ?>
				</h2>

				<button class="swp-expand--button">

					<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"/>
					</svg>

				</button>

			</div>

			<div class="swp-collapse--content">

				<div class="swp-row">

					<div class="swp-flex--col swp-flex--gap30">

						<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

							<div class="swp-col swp-col--title-width--sm">

								<h3 class="swp-h3">
									<?php esc_html_e( 'Minimum characters length', 'searchwp-live-ajax-search' ); ?>
								</h3>

							</div>

							<div class="swp-col">

								<div class="swp-flex--col swp-flex--gap25">

									<div class="swp-inputbox-horizontal">

										<input type="number" min="1" class="swp-input" name="swp-min-chars"<?php echo ! empty( $settings['swp-min-chars'] ) ? ' value="' . absint( $settings['swp-min-chars'] ) . '"' : ''; ?> placeholder="3">

										<label for="swp-swp-min-chars" class="swp-label">
											<?php esc_html_e( 'Characters', 'searchwp-live-ajax-search' ); ?>
										</label>

									</div>

									<p class="swp-desc">
										<?php esc_html_e( 'Choose how many characters must be typed before starting to search', 'searchwp-live-ajax-search' ); ?>
									</p>

								</div>

							</div>

						</div>

						<?php if ( ! Utils::is_searchwp_active() ) : ?>

							<div class="swp-flex--row sm:swp-flex--col sm:swp-flex--gap30">

								<div class="swp-col swp-col--title-width--sm">

									<h3 class="swp-h3">
										<?php esc_html_e( 'Hide Announcements', 'searchwp-live-ajax-search' ); ?>
									</h3>

								</div>

								<div class="swp-col">

									<div class="swp-flex--col swp-flex--gap25">

										<label class="swp-toggle">

											<input class="swp-toggle-checkbox" type="checkbox" name="hide-announcements"<?php checked( $settings['hide-announcements'] ); ?>>

											<div class="swp-toggle-switch"></div>

										</label>

										<p class="swp-desc"><?php esc_html_e( 'Check this option to hide plugin announcements and update details.', 'searchwp-live-ajax-search' ); ?></p>

									</div>

								</div>

							</div>

						<?php endif; ?>
					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Get the preview container classes.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings The settings.
	 *
	 * @return string
	 */
	private static function get_container_classes( $settings ) {

		$classes = [
			'searchwp-live-search-results-container',
		];

		switch ( $settings['swp-image-size'] ) {
			case 'small':
				$classes[] = 'swp-ls--img-sm';
				break;

			case 'medium':
				$classes[] = 'swp-ls--img-m';
				break;

			case 'large':
				$classes[] = 'swp-ls--img-l';
				break;
		}

		return implode( ' ', $classes );
	}

	/**
	 * Renders the page content if SearchWP is disabled.
	 *
	 * @since 1.8.0
	 */
	public static function render_searchwp_disabled() {

		if ( utils::is_swp_live_search_admin_page( self::$slug, 'search-modal' ) ) {

			if ( Utils::is_modal_form_active() ) {
				/**
				 * Action to render the modal form settings.
				 *
				 * @since 1.8.0
				 */
				do_action( 'searchwp_live_search_modal_form_render' );
			} else {
				searchwp_live_search()->get( 'ModalFormPreview' )->render();
			}

			return;
		}

		if ( ! utils::is_swp_live_search_admin_page( self::$slug, '' ) ) {
			return;
		}

		self::render();
	}

	/**
	 * After settings content render.
	 *
	 * @since 1.7.0
	 */
	private static function render_after_settings() {

		if ( Utils::is_searchwp_active() ) {
			return;
		}

		?>
		<div class="searchwp-settings-cta">
			<h5><?php esc_html_e( 'Get SearchWP Pro and Unlock all the Powerful Features', 'searchwp-live-ajax-search' ); ?></h5>
			<p><?php esc_html_e( 'Thank you for being a loyal SearchWP Live Ajax Search user. Upgrade to SearchWP Pro to unlock all the powerful features and experience why SearchWP is the best WordPress search plugin.', 'searchwp-live-ajax-search' ); ?></p>
			<p>
				<?php
				printf(
					wp_kses( /* translators: %s - star icons. */
						esc_html__( 'We know that you will truly love SearchWP Pro. It’s used on over 30,000 smart WordPress websites and is consistently rated 5-stars (%s) by our customers.', 'searchwp-live-ajax-search' ),
						[
							'i' => [
								'class'       => [],
								'aria-hidden' => [],
							],
						]
					),
					str_repeat( '<i class="fa fa-star" aria-hidden="true"></i>', 5 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
			</p>
			<h6><?php esc_html_e( 'Pro Features:', 'searchwp-live-ajax-search' ); ?></h6>
			<div class="list">
				<ul>
					<li><?php esc_html_e( 'Search all custom field data', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Make ecommerce metadata discoverable in search results', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Search PDF, .doc, .txt and other static documents', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Search custom database tables and other custom content', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Make your media library (images, videos, etc.) searchable', 'searchwp-live-ajax-search' ); ?></li>
				</ul>
				<ul>
					<li><?php esc_html_e( 'Search categories, tags and even custom taxonomies', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Easy integration with all WordPress themes and page builders', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Advanced search metrics and insights on visitor activity', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'Multiple custom search engines for different types of content', 'searchwp-live-ajax-search' ); ?></li>
					<li><?php esc_html_e( 'WooCommerce & Easy Digital Downloads support', 'searchwp-live-ajax-search' ); ?></li>
				</ul>
			</div>
			<p><a href="https://searchwp.com/?utm_source=WordPress&utm_medium=Settings+Upgrade+Bottom+Link&utm_campaign=Live+Ajax+Search&utm_content=Get+SearchWP+Pro+Today+and+Unlock+all+the+Powerful+Features" target="_blank" rel="noopener noreferrer" title="<?php esc_html_e( 'Get SearchWP Pro Today', 'searchwp-live-ajax-search' ); ?>"><?php esc_html_e( 'Get SearchWP Pro Today and Unlock all the Powerful Features', 'searchwp-live-ajax-search' ); ?> &raquo;</a></p>
			<p>
				<?php
				echo wp_kses(
					__( '<strong>Bonus:</strong> SearchWP Live Ajax Search users get <span class="green">50% off the regular price</span>, automatically applied at checkout!', 'searchwp-live-ajax-search' ),
					[
						'strong' => [],
						'span'   => [
							'class' => [],
						],
					]
				);
				?>
			</p>
		</div>
		<?php
	}


	/**
	 * Callback for the "Activate License" button.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public static function activate_license_ajax() {

		SearchWP_Live_Search()
			->incl( 'class-license.php' )
			->register( 'License' )
			->maybe_activate_license();
	}

	/**
	 * When user is on a SearchWP related admin page, display footer text
	 * that graciously asks them to rate us.
	 *
	 * @since 1.7.0
	 *
	 * @param string $text Footer text.
	 *
	 * @return string
	 */
	public static function admin_footer_rate_us_searchwp_disabled( $text ) {

		global $current_screen;

		if ( empty( $current_screen->id ) || strpos( $current_screen->id, 'searchwp-live-search' ) === false ) {
			return $text;
		}

		$url = 'https://wordpress.org/support/plugin/searchwp-live-ajax-search/reviews/?filter=5#new-post';

		return sprintf(
			wp_kses( /* translators: $1$s - SearchWP plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
				__( 'Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the SearchWP team!', 'searchwp-live-ajax-search' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			'<strong>SearchWP Live Ajax Search</strong>',
			$url,
			$url
		);
	}

	/**
	 * Hide the wp-admin area "Version x.x" in footer on SearchWP pages.
	 *
	 * @since 1.7.0
	 *
	 * @param string $text Default "Version x.x" or "Get Version x.x" text.
	 *
	 * @return string
	 */
	public static function admin_footer_hide_wp_version_searchwp_disabled( $text ) {

		// Reset text if we're not on a SearchWP screen or page.
		if ( Utils::is_settings_page() ) {
			return '';
		}

		return $text;
	}

	/**
	 * Output "Did you know" block.
	 *
	 * @since 1.7.0
	 *
	 * @return string
	 */
	public static function get_dyk_block_output() {

		if ( Utils::is_searchwp_active() ) {
			return '';
		}

		ob_start();

		?>
		<div class="searchwp-settings-dyk">
			<h5><?php esc_html_e( 'Did You Know?', 'searchwp-live-ajax-search' ); ?></h5>
			<p>
				<?php
				echo wp_kses(
					__( 'By default, WordPress doesn’t make all your content searchable. <strong><em>That’s frustrating</em></strong>, because it leaves your visitors unable to find what they are looking for!', 'searchwp-live-ajax-search' ),
					[
						'strong' => [],
						'em'     => [],
					]
				);
				?>
			</p>
			<p><?php esc_html_e( 'With SearchWP Pro, you can overcome this obstacle and deliver the best, most relevant search results based on all your content, such as custom fields, ecommerce data, categories, PDF documents, rich media and more!', 'searchwp-live-ajax-search' ); ?></p>
			<p><a href="https://searchwp.com/?utm_source=WordPress&utm_medium=Settings+Did+You+Know+Upgrade+Link&utm_campaign=Live+Ajax+Search&utm_content=Get+SearchWP+Pro+Today" target="_blank" rel="noopener noreferrer" title="<?php esc_html_e( 'Get SearchWP Pro Today', 'searchwp-live-ajax-search' ); ?>"><?php esc_html_e( 'Get SearchWP Pro Today', 'searchwp-live-ajax-search' ); ?> &raquo;</a></p>
			<p>
				<?php
				echo wp_kses(
					__( '<strong>Bonus:</strong> SearchWP Live Ajax Search users get <span class="green">50% off the regular price</span>, automatically applied at checkout!', 'searchwp-live-ajax-search' ),
					[
						'strong' => [],
						'span'   => [
							'class' => [],
						],
					]
				);
				?>
			</p>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Remove non-SearchWP notices from SearchWP pages.
	 *
	 * @since 1.8.0
	 */
	public static function admin_hide_unrelated_notices() {

		if ( ! Utils::is_swp_live_search_admin_page() ) {
			return;
		}

		global $wp_filter;

		// Define rules to remove callbacks.
		$rules = [
			'user_admin_notices' => [], // remove all callbacks.
			'admin_notices'      => [],
			'all_admin_notices'  => [],
			'admin_footer'       => [
				'render_delayed_admin_notices', // remove this particular callback.
			],
		];

		$notice_types = array_keys( $rules );

		foreach ( $notice_types as $notice_type ) {
			if ( empty( $wp_filter[ $notice_type ]->callbacks ) || ! is_array( $wp_filter[ $notice_type ]->callbacks ) ) {
				continue;
			}

			$remove_all_filters = empty( $rules[ $notice_type ] );

			foreach ( $wp_filter[ $notice_type ]->callbacks as $priority => $hooks ) {
				self::remove_notice_callbacks( $priority, $hooks, $notice_type, $rules, $remove_all_filters );
			}
		}
	}

	/**
	 * Remove all callbacks from a specific notice type.
	 *
	 * @since 1.8.0
	 *
	 * @param int    $priority           Priority.
	 * @param array  $hooks              Hooks.
	 * @param string $notice_type        Notice type.
	 * @param array  $rules              Rules to remove callbacks.
	 * @param bool   $remove_all_filters Remove all filters.
	 */
	private static function remove_notice_callbacks( $priority, $hooks, $notice_type, $rules, $remove_all_filters ) {

		global $wp_filter;

		foreach ( $hooks as $name => $arr ) {
			if ( is_object( $arr['function'] ) && is_callable( $arr['function'] ) ) {
				if ( $remove_all_filters ) {
					unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
				}
				continue;
			}

			$class = '';
			if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) ) {
				$class = strtolower( get_class( $arr['function'][0] ) );
			}
			if ( ! empty( $arr['function'][0] ) && is_string( $arr['function'][0] ) ) {
				$class = strtolower( $arr['function'][0] );
			}

			// Remove all callbacks except SearchWP notices.
			if ( $remove_all_filters && strpos( $class, 'searchwp' ) === false ) {
				unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
				continue;
			}

			$cb = is_array( $arr['function'] ) ? $arr['function'][1] : $arr['function'];

			// Remove a specific callback.
			if ( ! $remove_all_filters && in_array( $cb, $rules[ $notice_type ], true ) ) {
				unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
			}
		}
	}
}
