<?php

use SearchWP_Live_Search_Utils as Utils;

/**
 * SearchWP ModalFormPreview.
 *
 * @since 1.8.0
 */
class SearchWP_Live_Search_ModalFormPreview {

	/**
	 * Slug for this view.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	private static $slug = 'search-modal';

	/**
	 * Slug for this view.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	private static $parent_slug = 'live-search';

	/**
	 * ModalFormPreview constructor.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {

		add_filter( 'searchwp_live_search_settings_sub_header_items', [ $this, 'add_sub_header_items' ], 20 );

		if ( ! Utils::is_swp_live_search_admin_page( self::$parent_slug, self::$slug ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );
	}

	/**
	 * Add the Search Modal sub header item.
	 *
	 * @since 1.8.0
	 *
	 * @param array $items The sub header items.
	 *
	 * @return mixed
	 */
	public static function add_sub_header_items( $items ) {

		$items[ 'searchwp-' . self::$parent_slug ][] = [
			'page'  => 'searchwp-' . self::$parent_slug,
			'tab'   => self::$slug,
			'label' => esc_html__( 'Search Modal', 'searchwp-live-ajax-search' ),
		];

		return $items;
	}

	/**
	 * Enqueue assets for the Search Modal preview screen.
	 *
	 * @since 1.8.0
	 */
	public static function assets() {

		$handle = 'searchwp_live_search_' . self::$slug . '_preview';

		wp_enqueue_style(
			$handle,
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/search-modal-preview.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);
	}

	/**
	 * Output the view for the Search Modal preview screen.
	 * The render method is called from the SearchWP_Live_Search_Settings::page_searchwp_disabled() method.
	 *
	 * @since 1.8.0
	 */
	public static function render() {

		$link_text = 'Install Plugin';
		$link_url  = 'https://searchwp.com/extensions/modal-form/?utm_source=WordPress&utm_medium=Search+Modal+Upsell+Button&utm_campaign=Live+Ajax+Search&utm_content=Install+Plugin';

		self::render_preview();

		?>

		<div id="extension-preview-upsell">
			<div id="extension-preview-upsell-background">
                <h5><?php esc_html_e( 'SearchWP Modal Search Form', 'searchwp-live-ajax-search' ); ?></h5>
                <p>
					<?php
						echo wp_kses(
							sprintf(
								/* translators: %s is the URL to the Modal Search Form documentation. */
								__( 'Easily integrate an accessible, lightweight modal search form into your WordPress website! <a href="%s" target="_blank">View&nbsp;Docs</a>', 'searchwp-live-ajax-search' ),
								'https://searchwp.com/extensions/modal-form/'
							),
							[
								'a' => [
									'href'   => [],
									'target' => [],
								],
							]
						);
					?>
                </p>

                <div class="list">
                    <ul>
                        <li><?php esc_html_e( 'Easily add a search modal', 'searchwp-live-ajax-search' ); ?></li>
                        <li><?php esc_html_e( 'Increase Conversion Rates', 'searchwp-live-ajax-search' ); ?></li>
                    </ul>
                    <ul>
                        <li><?php esc_html_e( 'Improve User Experience', 'searchwp-live-ajax-search' ); ?></li>
                    </ul>
                </div>

				<a class="swp-button swp-button--green" href="<?php echo esc_url( $link_url ); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_html( $link_text ); ?>"><?php echo esc_html( $link_text ); ?></a>

			</div>
		</div>
		<?php
	}

    /**
     * Output the content for the Engines preview screen.
     *
     * @since 1.8.0
     */
    public static function render_preview() {
        ?>
		<div id="swp-search-modal-preview-wrapper" class="swp-content-container">
			<div class="swp-collapse swp-opened">
				<div class="swp-collapse--header">
					<h2 class="swp-h2">
						<?php esc_html_e( 'Search Modal Settings', 'searchwp-live-ajax-search' ); ?>
					</h2>

					<button class="swp-expand--button">
						<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"></path>
						</svg>
					</button>
				</div>

				<div class="swp-collapse--content">
					<div class="searchwp-modal-form-settings">
						<form id="searchwp-modal-form-admin-settings-form" class="searchwp-admin-settings-form" method="post">
							<div class="searchwp-settings-row searchwp-settings-row-content section-heading" id="searchwp-setting-row-general-heading">
								<span class="searchwp-setting-field">
									<h3><?php esc_html_e( 'General', 'searchwp-live-ajax-search' ); ?></h3>
								</span>
							</div>
							<div class="searchwp-settings-row searchwp-settings-row-checkbox" id="searchwp-setting-row-enable-modal-form">
								<span class="searchwp-setting-label">
									<label for="searchwp-setting-enable-modal-form"><?php esc_html_e( 'Enable Modal Form', 'searchwp-live-ajax-search' ); ?></label>
								</span>
								<span class="searchwp-setting-field">
									<label class="swp-toggle">
										<input type="checkbox" class="swp-toggle-checkbox" id="searchwp-setting-enable-modal-form" name="enable-modal-form" checked="checked">
										<div class="swp-toggle-switch"></div>
										<span class="swp-label"><?php esc_html_e( 'Check this option to enable the Modal Search Form.', 'searchwp-live-ajax-search' ); ?></span>
									</label>
								</span>
							</div>
							<div class="searchwp-settings-row searchwp-settings-row-content section-heading" id="searchwp-setting-row-modal-heading">
								<span class="searchwp-setting-field">
									<h3><?php esc_html_e( 'Modal', 'searchwp-live-ajax-search' ); ?></h3>
								</span>
							</div>
							<div class="searchwp-settings-row searchwp-settings-row-select" id="searchwp-setting-row-include-frontend-css">
								<span class="searchwp-setting-label">
									<label for="searchwp-setting-include-frontend-css"><?php esc_html_e( 'Include Styling', 'searchwp-live-ajax-search' ); ?></label>
								</span>
								<span class="searchwp-setting-field">
									<select id="searchwp-modal-form-setting-include-frontend-css" name="include-frontend-css">
										<option value="all" selected="selected">
											<?php esc_html_e( 'Positioning and visual styling', 'searchwp-live-ajax-search' ); ?>
										</option>
									</select>
									<p class="desc"><?php esc_html_e( 'Determines which CSS files to load and use for the site. "Positioning and visual styling" is recommended, unless you are experienced with CSS or instructed by support to change settings.', 'searchwp-live-ajax-search' ); ?></p>
								</span>
							</div>
							<div class="searchwp-settings-row searchwp-settings-row-checkbox " id="searchwp-setting-row-modal-fullscreen">
								<span class="searchwp-setting-label">
									<label for="searchwp-setting-modal-fullscreen"><?php esc_html_e( 'Full Screen Mode', 'searchwp-live-ajax-search' ); ?></label>
								</span>
								<span class="searchwp-setting-field">
								<label class="swp-toggle">
									<input type="checkbox" class="swp-toggle-checkbox" id="searchwp-setting-modal-fullscreen" name="modal-fullscreen" checked="checked">
									<div class="swp-toggle-switch"></div>
									<span class="swp-label"><?php esc_html_e( 'Check this option to make the modal cover the entire screen when open. This option is great to provide a distraction free search experience for your users.', 'searchwp-live-ajax-search' ); ?></span>
								</label>
								</span>
							</div>
							<div class="searchwp-settings-row searchwp-settings-row-checkbox " id="searchwp-setting-row-modal-disable-scroll">
								<span class="searchwp-setting-label">
									<label for="searchwp-setting-modal-disable-scroll"><?php esc_html_e( 'Disable Scroll', 'searchwp-live-ajax-search' ); ?></label>
								</span>
								<span class="searchwp-setting-field">
									<label class="swp-toggle">
										<input type="checkbox" class="swp-toggle-checkbox" id="searchwp-setting-modal-disable-scroll" name="modal-disable-scroll" checked="checked">
										<div class="swp-toggle-switch"></div>
										<span class="swp-label"><?php esc_html_e( 'Check this option to disable background scrolling of the page when the modal is open.', 'searchwp-live-ajax-search' ); ?></span>
									</label>
								</span>
							</div>
						</form>
					</div>
				</div>

			</div>

			<p class="submit">
				<button type="submit" form="searchwp-modal-form-admin-settings-form" class="searchwp-btn searchwp-btn-md searchwp-btn-accent" name="searchwp-modal-form-settings-submit">
					<?php esc_html_e( 'Save Settings', 'searchwp-live-ajax-search' ); ?>
				</button>
			</p>
		</div>
        <?php
    }
}
