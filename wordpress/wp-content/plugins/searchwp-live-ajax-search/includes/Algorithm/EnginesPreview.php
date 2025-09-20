<?php

use SearchWP_Live_Search_Utils as Utils;

/**
 * SearchWP EnginesPreview.
 *
 * @since 1.8.0
 */
class SearchWP_Live_Search_EnginesPreview {

	/**
	 * Slug for this view.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	private static $slug = 'algorithm';

	/**
	 * EnginesPreview constructor.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {

		if ( ! Utils::is_swp_live_search_admin_page( self::$slug ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );
		add_filter( 'searchwp_live_search_settings_sub_header_items', [ __CLASS__, 'add_sub_header_items' ] );
	}

	/**
	 * Add the Algorithm sub header item.
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
			'label' => esc_html__( 'Engines', 'searchwp-live-ajax-search' ),
		];

		return $items;
	}

	/**
	 * Enqueue assets for the Engines preview screen.
	 *
	 * @since 1.8.0
	 */
	public static function assets() {

		wp_enqueue_style(
			'searchwp-live-search-styles',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/style.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		wp_enqueue_style(
			'searchwp-live-search-engines-styles',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/engines.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		Utils::enqueue_framework_styles(
			[
				'style',
				'buttons',
				'collapse-layout',
				'content-header',
				'layout',
			]
		);
	}

	/**
	 * Output the view for the Engines preview screen.
	 *
	 * @since 1.8.0
	 */
	public static function render() {

		$title      = __( 'Get SearchWP Pro and Unlock all the Powerful Features', 'searchwp-live-ajax-search' );
		$link_text  = __( 'Get SearchWP Pro', 'searchwp-live-ajax-search' );
		$link_url   = 'https://searchwp.com/?utm_source=WordPress&utm_medium=Engines+Upgrade+Buttom&utm_campaign=Live+Ajax+Search&utm_content=Get+SearchWP+Pro';
		$bonus_text = '';
		$target     = '_blank';

        self::render_preview();
		?>

		<div id="extension-preview-upsell">
			<div id="extension-preview-upsell-background">
                <h5><?php echo esc_html( $title ); ?></h5>
                <p><?php esc_html_e( 'Personalize your search results and discover what your visitors are searching for at the same time with SearchWP Pro!', 'searchwp-live-ajax-search' ); ?></p>

                <div class="list">
                    <ul>
                        <li><?php esc_html_e( 'Multiple custom search engines for different types of content', 'searchwp-live-ajax-search' ); ?></li>
                        <li><?php esc_html_e( 'Search categories, tags and even custom taxonomies', 'searchwp-live-ajax-search' ); ?></li>
						<li><?php esc_html_e( 'Search all custom field data', 'searchwp-live-ajax-search' ); ?></li>
						<li><?php esc_html_e( 'Make your media library (images, videos, etc.) searchable', 'searchwp-live-ajax-search' ); ?></li>
                    </ul>
                    <ul>
						<li><?php esc_html_e( 'Search PDF, .doc, .txt and other static documents', 'searchwp-live-ajax-search' ); ?></li>
						<li><?php esc_html_e( 'WooCommerce & Easy Digital Downloads support', 'searchwp-live-ajax-search' ); ?></li>
						<li><?php esc_html_e( 'Make ecommerce metadata discoverable in search results', 'searchwp-live-ajax-search' ); ?></li>
						<li><?php esc_html_e( 'Advanced search metrics and insights on visitor activity', 'searchwp-live-ajax-search' ); ?></li>
                    </ul>
                </div>

				<?php if ( ! empty( $bonus_text ) ) : ?>
                    <p>
						<?php
							echo wp_kses(
								$bonus_text,
								[
									'strong' => [],
									'span'   => [
										'class' => [],
									],
								]
							);
						?>
					</p>
				<?php endif; ?>

				<a class="swp-button swp-button--green" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $target ); ?>" rel="noopener noreferrer" title="<?php echo esc_html( $link_text ); ?>"><?php echo esc_html( $link_text ); ?></a>
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
		<div class="swp-content-container">
			<div id="searchwp-settings-engines">
				<div class="swp-page-header">
					<div class="swp-flex--row swp-justify-between swp-flex--align-c">
						<div class="swp-flex--row swp-flex--gap12 swp-flex--align-c">
							<h1 class="swp-h1 swp-page-header--h1">Engines</h1>
							<button class="swp-button swp-button--slim swp-button--green-text">Add New</button>
						</div>
						<button class="swp-button swp-button--green">Save</button>
					</div>
				</div>
				<div class="swp-flex--row sm:swp-flex--col swp-flex--gap20 sm:swp-items-stretch">
					<div class="swp-main-content">
						<div class="swp-collapse">
							<div class="searchwp-engine vc-collapse searchwp-meta-box" id="searchwp-engine-default">
								<div class="swp-collapse--header sm:swp-flex--col">
									<div class="swp-flex--item">
										<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c sm:swp-flex--wrap">
											<h2 class="swp-h2">Default</h2>
											<code class="swp-code">default</code>
											<span class="swp-tooltip">
											<span class="swp-tooltip--info has-tooltip"></span>
										</span>
										</div>
									</div>
									<div class="swp-flex--item">
										<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
											<button class="swp-button swp-button--slim swp-engine-settings">Sources &amp; Settings</button>
											<button class="swp-button swp-button--slim">
												<span class="swp-button--flex-content">
												Collapse All
												<svg width="10" height="6" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="swp-arrow swp-arrow--sm">
													<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"></path>
												</svg>
												</span>
											</button>
										</div>
									</div>
								</div>
								<div class="v-collapse-content v-collapse-content-end">
									<div class="swp-collapse--content">
										<div>
											<div class="swp-sub-collapse">
												<div class="vc-collapse searchwp-meta-box">
													<div class="swp-collapse--header sm:swp-flex--col">
														<div class="swp-flex--item">
															<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c sm:swp-flex--wrap">
																<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M11.25 3.33325H4.33337C3.78109 3.33325 3.33337 3.78097 3.33337 4.33325V15.6666C3.33337 16.2189 3.78109 16.6666 4.33337 16.6666H15.6667C16.219 16.6666 16.6667 16.2189 16.6667 15.6666V8.74992" stroke="#0E2121"></path>
																	<path d="M15 2.5V7.5" stroke="#0E2121"></path>
																	<path d="M12.5 5H17.5" stroke="#0E2121"></path>
																	<path d="M6.66663 8.33325H13.3333" stroke="#0E2121"></path>
																	<path d="M6.66663 10.8333H13.3333" stroke="#0E2121"></path>
																	<path d="M6.66663 13.3333H13.3333" stroke="#0E2121"></path>
																</svg>
																<h3 class="swp-h3">Posts</h3>
																<span class="swp-attributes"><span>6 Attributes</span></span>
																<span class="swp-tooltip">
																	<span class="swp-tooltip--info has-tooltip"></span>
																</span>
															</div>
														</div>
														<div class="swp-flex--item">
															<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																<button class="swp-button swp-button--trash-sm">
																	<svg width="12" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M1.77277 15.6668C1.77277 16.7144 2.57857 17.5716 3.56343 17.5716H10.7261C11.7109 17.5716 12.5167 16.7144 12.5167 15.6668V4.23823H1.77277V15.6668ZM3.56343 6.143H10.7261V15.6668H3.56343V6.143ZM10.2784 1.38109L9.38307 0.428711H4.90642L4.01109 1.38109H0.877441V3.28585H13.4121V1.38109H10.2784Z" fill="#0E2121" fill-opacity="0.7"></path>
																	</svg>
																</button>
																<button type="button" class="swp-arrow">

																	<svg width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"></path>
																	</svg>
																</button>
															</div>
														</div>
													</div>
													<div class="v-collapse-content v-collapse-content-end">
														<div class="inside swp-sub-collapse--content">
															<div class="swp-row swp-padding-b40">
																<div class="swp-flex--row l:swp-flex--col swp-flex--gap80 l:swp-flex--gap30">
																	<div class="swp-flex--item swp-w-1/2 l:swp-w-full">
																		<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c swp-margin-b25">
																			<h4 class="swp-h4">Applicable Attribute Relevance</h4>
																			<span class="swp-tooltip">
																				<span class="swp-tooltip--info has-tooltip"></span>
																			</span>
																		</div>
																		<div class="swp-flex--col swp-flex--gap30 swp-w-full">
																			<div class="swp-flex--col swp-flex--gap15">
																				<div>
																					<div class="swp-engine--source-attr">
																						<div class="swp-engine--source-attr-weight">
																							<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																								<label title="Title" class="swp-label swp-cut-text swp-w-1/2">Title</label>
																								<div class="swp-slider--container swp-w-1/2">
																									<div class="vue-slider vue-slider-ltr" position="right" style="padding: 3.5px; width: auto; height: 1px;">
																										<div class="vue-slider-rail">
																											<div class="vue-slider-process" style="height: 100%; top: 0px; left: 0%; width: 100%; transition-property: width, left; transition-duration: 0.5s; background-color: rgb(43, 102, 209);"></div>
																											<div class="vue-slider-marks">
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 0%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 50%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 100%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																											</div>
																											<div class="vue-slider-dot" role="slider" style="left: 100%;">
																												<div class="vue-slider-dot-handle" style="background-color: rgb(43, 102, 209); border-color: rgb(43, 102, 209);"></div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div>
																					<div class="swp-engine--source-attr">
																						<div class="swp-engine--source-attr-weight">
																							<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																								<label title="Content" class="swp-label swp-cut-text swp-w-1/2">Content</label>
																								<div class="swp-slider--container swp-w-1/2">
																									<div class="vue-slider vue-slider-ltr" position="right" style="padding: 3.5px; width: auto; height: 1px;">
																										<div class="vue-slider-rail">
																											<div class="vue-slider-process" style="height: 100%; top: 0px; left: 0%; width: 0%; transition-property: width, left; transition-duration: 0.5s; background-color: rgb(43, 102, 209);"></div>
																											<div class="vue-slider-marks">
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 0%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark" style="height: 100%; width: 1px; left: 50%;">
																													<div class="vue-slider-mark-step" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(232, 232, 235); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark" style="height: 100%; width: 1px; left: 100%;">
																													<div class="vue-slider-mark-step" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(232, 232, 235); box-shadow: none;"></div>
																												</div>
																											</div>
																											<div class="vue-slider-dot" style="left: 0%;">
																												<div class="vue-slider-dot-handle" style="background-color: rgb(43, 102, 209); border-color: rgb(43, 102, 209);"></div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div>
																					<div class="swp-engine--source-attr">
																						<div class="swp-engine--source-attr-weight">
																							<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																								<label title="Slug" class="swp-label swp-cut-text swp-w-1/2"> Slug </label>
																								<div class="swp-slider--container swp-w-1/2">
																									<div class="vue-slider vue-slider-ltr" position="right" style="padding: 3.5px; width: auto; height: 1px;">
																										<div class="vue-slider-rail">
																											<div class="vue-slider-process" style="height: 100%; top: 0px; left: 0%; width: 100%; transition-property: width, left; transition-duration: 0.5s; background-color: rgb(43, 102, 209);"></div>
																											<div class="vue-slider-marks">
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 0%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 50%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 100%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																											</div>
																											<div class="vue-slider-dot" style="left: 100%;">
																												<div class="vue-slider-dot-handle" style="background-color: rgb(43, 102, 209); border-color: rgb(43, 102, 209);"></div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div>
																					<div class="swp-engine--source-attr">
																						<div class="swp-engine--source-attr-weight">
																							<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																								<label title="Excerpt"class="swp-label swp-cut-text swp-w-1/2"> Excerpt </label>
																								<div class="swp-slider--container swp-w-1/2">
																									<div class="vue-slider vue-slider-ltr" position="right" style="padding: 3.5px; width: auto; height: 1px;">
																										<div class="vue-slider-rail">
																											<div class="vue-slider-process" style="height: 100%; top: 0px; left: 0%; width: 0%; transition-property: width, left; transition-duration: 0.5s; background-color: rgb(43, 102, 209);"></div>
																											<div class="vue-slider-marks">
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 0%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark" style="height: 100%; width: 1px; left: 50%;">
																													<div class="vue-slider-mark-step" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(232, 232, 235); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark" style="height: 100%; width: 1px; left: 100%;">
																													<div class="vue-slider-mark-step" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(232, 232, 235); box-shadow: none;"></div>
																												</div>
																											</div>
																											<div class="vue-slider-dot" style="left: 0%;">
																												<div class="vue-slider-dot-handle" style="background-color: rgb(43, 102, 209); border-color: rgb(43, 102, 209);"></div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div>
																					<div class="swp-engine--source-attr">
																						<div class="swp-engine--source-attr-weight">
																							<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																								<label title="Author" class="swp-label swp-cut-text swp-w-1/2"> Author </label>
																								<div class="swp-slider--container swp-w-1/2">
																									<div class="vue-slider vue-slider-ltr" position="right" style="padding: 3.5px; width: auto; height: 1px;">
																										<div class="vue-slider-rail">
																											<div class="vue-slider-process" style="height: 100%; top: 0px; left: 0%; width: 100%; transition-property: width, left; transition-duration: 0.5s; background-color: rgb(43, 102, 209);"></div> <div class="vue-slider-marks">
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 0%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 50%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																												<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 100%;">
																													<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																												</div>
																											</div>
																											<div class="vue-slider-dot" style="left: 100%;">
																												<div class="vue-slider-dot-handle" style="background-color: rgb(43, 102, 209); border-color: rgb(43, 102, 209);"></div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div>
																					<div class="swp-engine--source-attr">
																						<div class="swp-engine--source-attr-weight">
																							<h4 class="swp-h4 swp-margin-t15">Taxonomies</h4>
																							<div class="swp-flex--col swp-flex--gap15 swp-margin-t25">
																								<div>
																									<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																										<label title="Categories (category)" class="swp-label swp-cut-text swp-w-1/2"><span>Categories (category)</span></label>
																										<div class="swp-slider--container swp-w-1/2">
																											<div class="vue-slider vue-slider-ltr" position="right" style="padding: 3.5px; width: auto; height: 1px;">
																												<div class="vue-slider-rail">
																													<div class="vue-slider-process" style="width: 100%;"></div>
																													<div class="vue-slider-marks">
																														<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 0%;">
																															<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																														</div>
																														<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 50%;">
																															<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																														</div>
																														<div class="vue-slider-mark vue-slider-mark-active" style="height: 100%; width: 1px; left: 100%;">
																															<div class="vue-slider-mark-step vue-slider-mark-step-active" style="width: 7px; height: 7px; margin-top: -3px; margin-left: -3px; border: 1.5px solid rgb(43, 102, 209); box-shadow: none;"></div>
																														</div>
																													</div>
																													<div class="vue-slider-dot" style="left: 100%;">
																														<div class="vue-slider-dot-handle" style="background-color: rgb(43, 102, 209); border-color: rgb(43, 102, 209);"></div>
																													</div>
																												</div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div class="swp-flex--item">
																				<button class="swp-button swp-button--slim">Add/Remove Attributes</button>
																			</div>
																		</div>
																	</div>
																	<div class="swp-flex--col swp-flex--gap30 swp-w-1/2 l:swp-w-full">
																		<div class="swp-flex--item">
																			<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c swp-margin-b20 ">
																				<h4 class="swp-h4">Rules</h4>
																				<span class="swp-tooltip">
																					<span class="swp-tooltip--info has-tooltip"></span>
																				</span>
																			</div>
																			<div
																				class="swp-flex--col swp-flex--gap20 swp-w-full">
																				<div class="swp-flex--item">
																					<p class="swp-p swp-font-size13">There are currently no rules for Posts.</p>
																				</div>
																				<div class="swp-flex--item">
																					<button type="button" class="swp-button swp-button--slim">Edit Rules</button>
																				</div>
																			</div>
																		</div>
																		<div class="swp-flex--item">
																			<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c swp-margin-b20">
																				<h4 class="swp-h4">Options</h4>
																				<span class="swp-tooltip">
																					<span class="swp-tooltip--info has-tooltip"></span></span>
																			</div>
																			<div>
																				<div>
																					<div class="swp-flex--col swp-flex--gap20">
																						<div class="swp-flex--row swp-flex--grow0 swp-flex--align-c" value="weight_transfer">
																							<label class="swp-label">
																								<input type="checkbox" id="default-post.post-setting-weight_transfer" class="swp-checkbox">
																								<span><span>Transfer Weight to entry ID </span></span>
																								<span class="swp-tooltip">
																									<span class="swp-tooltip--info has-tooltip"></span>
																								</span>
																							</label>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="swp-sub-collapse">
												<div class="vc-collapse searchwp-meta-box closed">
													<div class="swp-collapse--header sm:swp-flex--col">
														<div class="swp-flex--item">
															<div
																class="swp-flex--row swp-flex--gap9 swp-flex--align-c sm:swp-flex--wrap">
																<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M11.25 3.33325H4.33337C3.78109 3.33325 3.33337 3.78097 3.33337 4.33325V15.6666C3.33337 16.2189 3.78109 16.6666 4.33337 16.6666H15.6667C16.219 16.6666 16.6667 16.2189 16.6667 15.6666V8.74992" stroke="#0E2121"></path>
																	<path d="M15 2.5V7.5" stroke="#0E2121"></path>
																	<path d="M12.5 5H17.5" stroke="#0E2121"></path>
																	<path d="M6.66663 8.33325H13.3333" stroke="#0E2121"></path>
																	<path d="M6.66663 10.8333H13.3333" stroke="#0E2121"></path>
																	<path d="M6.66663 13.3333H13.3333" stroke="#0E2121"></path>
																</svg>
																<h3 class="swp-h3">Pages</h3>
																<span class="swp-attributes"><span>5 Attributes</span></span>
																<span class="swp-tooltip">
																	<span class="swp-tooltip--info has-tooltip"></span>
																</span>
															</div>
														</div>
														<div class="swp-flex--item">
															<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																<button class="swp-button swp-button--trash-sm">
																	<svg width="12" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M1.77277 15.6668C1.77277 16.7144 2.57857 17.5716 3.56343 17.5716H10.7261C11.7109 17.5716 12.5167 16.7144 12.5167 15.6668V4.23823H1.77277V15.6668ZM3.56343 6.143H10.7261V15.6668H3.56343V6.143ZM10.2784 1.38109L9.38307 0.428711H4.90642L4.01109 1.38109H0.877441V3.28585H13.4121V1.38109H10.2784Z" fill="#0E2121" fill-opacity="0.7"></path>
																	</svg>
																</button>
																<button type="button" class="swp-arrow swp-arrow--right">
																	<svg width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"></path>
																	</svg>
																</button>
															</div>
														</div>
													</div>
													<div class="v-collapse-content"></div>
												</div>
											</div>
											<div class="swp-sub-collapse">
												<div class="vc-collapse searchwp-meta-box closed">
													<div class="swp-collapse--header sm:swp-flex--col">
														<div class="swp-flex--item">
															<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c sm:swp-flex--wrap">
																<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M11.25 3.33325H4.33337C3.78109 3.33325 3.33337 3.78097 3.33337 4.33325V15.6666C3.33337 16.2189 3.78109 16.6666 4.33337 16.6666H15.6667C16.219 16.6666 16.6667 16.2189 16.6667 15.6666V8.74992" stroke="#0E2121"></path>
																	<path d="M15 2.5V7.5" stroke="#0E2121"></path>
																	<path d="M12.5 5H17.5" stroke="#0E2121"></path>
																	<path d="M6.66663 8.33325H13.3333" stroke="#0E2121"></path>
																	<path d="M6.66663 10.8333H13.3333" stroke="#0E2121"></path>
																	<path d="M6.66663 13.3333H13.3333" stroke="#0E2121"></path>
																</svg>
																<h3 class="swp-h3">Products</h3>
																<span class="swp-attributes"><span>5 Attributes</span>
																</span>
																<span class="swp-tooltip">
																	<span class="swp-tooltip--info has-tooltip"></span>
																</span>
															</div>
														</div>
														<div class="swp-flex--item">
															<div class="swp-flex--row swp-flex--gap9 swp-flex--align-c">
																<button class="swp-button swp-button--trash-sm">
																	<svg width="12" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M1.77277 15.6668C1.77277 16.7144 2.57857 17.5716 3.56343 17.5716H10.7261C11.7109 17.5716 12.5167 16.7144 12.5167 15.6668V4.23823H1.77277V15.6668ZM3.56343 6.143H10.7261V15.6668H3.56343V6.143ZM10.2784 1.38109L9.38307 0.428711H4.90642L4.01109 1.38109H0.877441V3.28585H13.4121V1.38109H10.2784Z" fill="#0E2121" fill-opacity="0.7"></path>
																	</svg>
																</button>
																<button type="button" class="swp-arrow swp-arrow--right">
																	<svg width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"></path>
																	</svg>
																</button>
															</div>
														</div>
													</div>
													<div class="v-collapse-content"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="swp-sidebar">
						<div class="swp-sidebar-box swp-margin-b20">
							<div class="swp-sidebar-box--header">
								<div class="swp-sidebar-box--notices">
									<div class="swp-alternate-indexer"></div>
								</div>
								<div class="swp-sidebar--progress-header">
									<div class="swp-flex--row swp-justify-between swp-flex--align-c swp-flex--grow1">
										<h2 class="swp-h2">
											Index Status
										</h2>
										<div class="swp-index-status">100%</div>
									</div>
									<div class="swp-progress-bar--container">
										<div class="swp-progress-bar">
											<div class="swp-progress-bar--completed" style="width: 100%;"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="swp-sidebar-box--content">
								<div class="swp-index-stats">
									<div class="swp-flex--col swp-flex--gap20">
										<div class="swp-flex--row swp-justify-between">
											<span class="swp-b">Last Activity</span>
											<span>5 days ago</span>
										</div>
										<div class="swp-flex--row swp-justify-between">
											<span class="swp-b">Indexed</span><span>104</span>
										</div>
										<div class="swp-flex--row swp-justify-between">
											<span class="swp-b">Total</span><span>104</span>
										</div>
									</div>
								</div>
								<p class="swp-desc">Note: the index is automatically kept up to date and maintained for optimization</p>
							</div>
							<button class="swp-button swp-display-block swp-w-full" disabled="disabled">Rebuild Index</button>
						</div>
					</div>
				</div>
			</div>
		</div>
        <?php
    }
}
