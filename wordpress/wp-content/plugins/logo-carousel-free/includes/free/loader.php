<?php
/**
 * The Free Loader Class
 *
 * @package logo-carousel-free
 * @since 3.0
 */

/**
 * The Free Loader Class
 *
 * @package logo-carousel-free
 * @since 3.0
 */
class SPLC_Free_Loader {

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'lc_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'logo-carousel-free', 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Free Loader constructor
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'analytics_admin_menu' ), 70 );
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 90 );
		require_once SP_LC_PATH . 'admin/views/scripts.php';
		require_once SP_LC_PATH . 'public/views/shortcoderender.php';
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );

		add_action( 'admin_menu', array( $this, 'recommended_admin_menu' ), 80 );
		add_action( 'admin_menu', array( $this, 'lite_to_pro_admin_menu' ), 80 );
	}

	/**
	 * Admin Menu
	 *
	 * @return void
	 */
	public function analytics_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_logo_carousel',
			__( 'Logo Carousel Analytics', 'logo-carousel-free' ),
			__( 'Analytics', 'logo-carousel-free' ) . '<span class="lcp-menu-new-indicator" style="color: #f18200;font-size: 9px;padding-left: 3px;">' . __( '  NEW!', 'logo-carousel-free' ) . '</span>',
			'manage_options',
			'lc_analytics',
			array( $this, 'display_logo_analytics' )
		);
	}
	/**
	 * Admin Menu
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_logo_carousel',
			__( 'Logo Carousel Help', 'logo-carousel-free' ),
			__( 'Get Help', 'logo-carousel-free' ),
			'manage_options',
			'lc_help',
			array( $this, 'help_page_callback' )
		);
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function recommended_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_logo_carousel',
			__( 'Logo Carousel', 'logo-carousel-free' ),
			__( 'Recommended', 'logo-carousel-free' ),
			'manage_options',
			'edit.php?post_type=sp_logo_carousel&page=lc_help#recommended'
		);
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function lite_to_pro_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_logo_carousel',
			__( 'Logo Carousel', 'logo-carousel-free' ),
			__( 'Lite vs Pro', 'logo-carousel-free' ),
			'manage_options',
			'edit.php?post_type=sp_logo_carousel&page=lc_help#lite-to-pro'
		);
	}

	/**
	 * Splc_plugins_info_api_help_page function.
	 *
	 * @return void
	 */
	public function splc_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'sp-logo-carousel_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
					'icons',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
								'icons'             => $pl->icons['2x'],
							);
						}
					}
				}

				set_transient( 'sp-logo-carousel_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$plugin_icon = $plugin['icons'];
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}
				// Skip the plugin if it is already installed.
				if ( 'logo-carousel-free' === $plugin_slug ) {
					continue;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=772&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
									<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( $plugin_icon ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
								<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'logo-carousel-free' ); ?>
								</a>
								<?php
							}
						} else {
							?>
								<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'logo-carousel-free' ); ?>
									</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( sprintf( esc_html__( 'More information about %s', 'logo-carousel-free' ), $plugin['name'] ) ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'logo-carousel-free' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';
					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'logo-carousel-free' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}
					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo number_format_i18n( $plugin['active_installs'] ) . esc_html__( '+ Active Installations', 'logo-carousel-free' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'logo-carousel-free' ); ?></strong>
							<span><?php printf( esc_html__( '%s ago', 'logo-carousel-free' ), esc_html( human_time_diff( $plugin['last_updated'] ) ) ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_logo_carousel&page=lc_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_logo_carousel' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * Help Page Callback
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-logo-carousel-help">
			<!-- Header section start -->
			<section class="sp-logo-carousel__help header">
				<div class="sp-logo-carousel-header-area-top">
					<p>You’re currently using <b>Logo Carousel Lite</b>. To access additional features, consider <a target="_blank" href="https://logocarousel.com/pricing/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="sp-logo-carousel-header-area">
					<div class="sp-logo-carousel-container">
						<div class="sp-logo-carousel-header-logo">
							<img src="<?php echo esc_url( SP_LC_URL . 'admin/assets/images/lc-help-logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SP_LC_VERSION ); ?></span>
						</div>
					</div>
					<div class="sp-logo-carousel-header-logo-shape">
						<img src="<?php echo esc_url( SP_LC_URL . 'admin/assets/images/lc-help-logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="sp-logo-carousel-header-nav">
					<div class="sp-logo-carousel-container">
						<div class="sp-logo-carousel-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_logo_carousel&page=lc_help#get-start' ); ?>"><i class="sp-logo-carousel-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_logo_carousel&page=lc_help#recommended' ); ?>" data-id="recommended-tab"><i class="sp-logo-carousel-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_logo_carousel&page=lc_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="sp-logo-carousel-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_logo_carousel&page=lc_help#about-us' ); ?>" data-id="about-us-tab"><i class="sp-logo-carousel-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="sp-logo-carousel__help start-page" id="get-start-tab">
				<div class="sp-logo-carousel-container">
					<div class="sp-logo-carousel-start-page-wrap">
						<div class="sp-logo-carousel-video-area">
							<h2 class='sp-logo-carousel-section-title'>Welcome to Logo Carousel!</h2>
							<span class='sp-logo-carousel-normal-paragraph'>Thank you for installing Logo Carousel! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/Gf1EbH4T1bg?si=Kbf7ORto78GUCpdf" title="YouTube video player" allowfullscreen></iframe>
							<ul>
								<li><a class='sp-logo-carousel-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sp_lc_shortcodes' ); ?>">Create a Logo View</a></li>
								<li><a target="_blank" class='sp-logo-carousel-medium-btn' href="https://logocarousel.com/logo-carousel-lite-version-demos/">Live Demo</a></li>
								<li><a target="_blank" class='sp-logo-carousel-medium-btn arrow-btn' href="https://logocarousel.com/">Explore Logo Carousel <i class="sp-logo-carousel-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="sp-logo-carousel-start-page-sidebar">
							<div class="sp-logo-carousel-start-page-sidebar-info-box">
								<div class="sp-logo-carousel-info-box-title">
									<h4><i class="sp-logo-carousel-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='sp-logo-carousel-normal-paragraph'>Explore Logo Carousel plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='sp-logo-carousel-small-btn' href="https://docs.shapedplugin.com/docs/logo-carousel/introduction/">Browse Now</a>
							</div>
							<div class="sp-logo-carousel-start-page-sidebar-info-box">
								<div class="sp-logo-carousel-info-box-title">
									<h4><i class="sp-logo-carousel-icon-support"></i> Technical Support</h4>
								</div>
								<span class='sp-logo-carousel-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='sp-logo-carousel-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="sp-logo-carousel-start-page-sidebar-info-box">
								<div class="sp-logo-carousel-info-box-title">
									<h4><i class="sp-logo-carousel-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='sp-logo-carousel-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='sp-logo-carousel-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="sp-logo-carousel__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="sp-logo-carousel-container">
					<div class="sp-logo-carousel-call-to-action-top">
						<h2 class="sp-logo-carousel-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://logocarousel.com/pricing/?ref=1" class='sp-logo-carousel-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="sp-logo-carousel-lite-to-pro-wrap">
						<div class="sp-logo-carousel-features">
							<ul>
								<li class='sp-logo-carousel-header'>
									<span class='sp-logo-carousel-title'>FEATURES</span>
									<span class='sp-logo-carousel-free'>Lite</span>
									<span class='sp-logo-carousel-pro'><i class='sp-logo-carousel-icon-pro'></i> PRO</span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>All Free Version Features</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-check-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Layout Presets (Carousel, Grid, Masonry, Isotope, List, Inline, etc.)</span>
									<span class='sp-logo-carousel-free'><b>1</b></span>
									<span class='sp-logo-carousel-pro'><b>13+</b></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Live Isotope Filter with Opacity </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Carousel Styles (Ticker and Center) <i class="sp-logo-carousel-hot">Hot</i> </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Filtering Logos by Category, Specific & Exclude</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Click Action Types (Link or Popup) <i class='sp-logo-carousel-hot'>hot</i> </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Display Logos Randomly</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Scheduling Logos at Specific Time Intervals <i class='sp-logo-carousel-hot'>hot</i></span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Ajax Logo Search and Live Filters (A-Z Alphabetical and Categories)</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Display Justified Logos <i class='sp-logo-carousel-new'>New</i></span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>8+ Logo Content Positions </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Show Logo Title and Description (Full, Word Limit, Read More, etc.)</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Overlay Content Styles (Background Color, Visibility, etc.)</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Display Logo Tooltips (4 Positions, Max Width, 6 Effects, Color, etc.) <i class='sp-logo-carousel-hot'>Hot</i></span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Background Types (Solid and Gradient) </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Box Highlight Styles (Inset and Outset BoxShadow) </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Multiple Ajax Pagination Types (Number, Load More, Infinite, etc.) </span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo(s) to Show Per Page and Click</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Display Call To Action (CTA) Button (Styles, Color, Radius, Link, etc.)</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Image Custom Dimensions and Retina Ready Supported</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Lazy Load for Logo Images and 40+ On Hover Logo Animations</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Logo Custom Color and Effects (Blur, Opacity, Grayscale) <i class="sp-logo-carousel-new">New</i><i class='sp-logo-carousel-hot'>hot</i></span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Vertical Logo Carousel Orientation</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Powerful Carousel Settings (Slide to Scroll, Fade, Navigation, Pagination, etc.)</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Multi-Row logo Carousels <i class='sp-logo-carousel-hot'>hot</i></span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Import/Export Logos and Logo Views (Shortcodes)</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-check-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Stylize your Logo Showcase Typography with 1500+ Google Fonts</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
								<li class='sp-logo-carousel-body'>
									<span class='sp-logo-carousel-title'>Priority Top-notch Support</span>
									<span class='sp-logo-carousel-free sp-logo-carousel-close-icon'></span>
									<span class='sp-logo-carousel-pro sp-logo-carousel-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="sp-logo-carousel-upgrade-to-pro">
							<h2 class='sp-logo-carousel-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='sp-logo-carousel-section-subtitle'>Already, <b>25000+</b> people are using Logo Carousel on their websites to create beautiful Logo showcase, why won’t you!</span>
							<div class="sp-logo-carousel-upgrade-to-pro-btn">
								<div class="sp-logo-carousel-action-btn">
									<a target="_blank" href="https://logocarousel.com/pricing?ref=1" class='sp-logo-carousel-big-btn'>Upgrade to Pro Now!</a>
									<span class='sp-logo-carousel-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://logocarousel.com" class='sp-logo-carousel-big-btn-border'>See All Features</a>
								<a target="_blank" href="https://logocarousel.com/carousel/" class='sp-logo-carousel-big-btn-border sp-logo-carousel-pro-live-demo-btn'>Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="sp-logo-carousel-testimonial">
						<div class="sp-logo-carousel-testimonial-title-section">
							<span class='sp-logo-carousel-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="sp-logo-carousel-section-title">Our Users Love Logo Carousel Pro!</h2>
						</div>
						<div class="sp-logo-carousel-testimonial-wrap">
							<div class="sp-logo-carousel-testimonial-area">
								<div class="sp-logo-carousel-testimonial-content">
									<p>I’m developing a new WP site and testing the free version of Logo Carousel plugin. Easy to use, lightweiht and straight forward. Had a problem due to Divi template CSS updates, asked for support...</p>
								</div>
								<div class="sp-logo-carousel-testimonial-info">
									<div class="sp-logo-carousel-img">
										<img src="<?php echo esc_url( SP_LC_URL . 'admin/assets/help-page/img/hbergold.png' ); ?>" alt="">
									</div>
									<div class="sp-logo-carousel-info">
										<h3>Hbergold</h3>
										<div class="sp-logo-carousel-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="sp-logo-carousel-testimonial-area">
								<div class="sp-logo-carousel-testimonial-content">
									<p>I know a lot of themes have their own logo carousel options, but none of them perform as good as this plugin! Hands down the best! On top of the custom settings and easy-of-use, their support..</p>
								</div>
								<div class="sp-logo-carousel-testimonial-info">
									<div class="sp-logo-carousel-img">
										<img src="<?php echo esc_url( SP_LC_URL . 'admin/assets/help-page/img/impelr.png' ); ?>" alt="">
									</div>
									<div class="sp-logo-carousel-info">
										<h3>Impelr</h3>
										<div class="sp-logo-carousel-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="sp-logo-carousel-testimonial-area">
								<div class="sp-logo-carousel-testimonial-content">
									<p>Plugin work really well on a Astra template / Elementor install, we subscribed on the lifetime agency package after testing the plugin during 2 months and we received a great discount...</p>
								</div>
								<div class="sp-logo-carousel-testimonial-info">
									<div class="sp-logo-carousel-img">
										<img src="<?php echo esc_url( SP_LC_URL . 'admin/assets/help-page/img/butter.png' ); ?>" alt="">
									</div>
									<div class="sp-logo-carousel-info">
										<h3>Butterfly Pixel</h3>
										<div class="sp-logo-carousel-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="sp-logo-carousel-recommended-page">
				<div class="sp-logo-carousel-container">
					<h2 class="sp-logo-carousel-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="sp-logo-carousel-wp-list-table plugin-install-php">
						<div class="sp-logo-carousel-recommended-plugins" id="the-list">
							<?php
								$this->splc_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="sp-logo-carousel__help about-page">
				<div class="sp-logo-carousel-container">
					<div class="sp-logo-carousel-about-box">
						<div class="sp-logo-carousel-about-info">
							<h3>The Most Powerful Logo Showcase Plugin for WordPress from the Logo Carousel Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we have searched for the best way to display a group of logo images with a Title, Description, Tooltips, Links, and Popup or Lightbox as a grid or in a carousel on WordPress sites. Unfortunately, we couldn't find any suitable plugin that met our needs. Therefore, we decided to develop a powerful WordPress logo carousel plugin that is both user-friendly and efficient.</p>
							<p>We provide the easiest and most convenient way to create visually appealing unlimited logo carousels for WordPress websites. We are confident you will love it!</p>
							<div class="sp-logo-carousel-about-btn">
								<a target="_blank" href="https://logocarousel.com/" class='sp-logo-carousel-medium-btn'>Explore Logo Carousel</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='sp-logo-carousel-medium-btn sp-logo-carousel-arrow-btn'>More About Us <i class="sp-logo-carousel-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="sp-logo-carousel-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<?php
					$plugins_arr = get_transient( 'sp-logo-carousel_plugins' );
					$plugin_icon = array();
					if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
						foreach ( $plugins_arr as $plugin ) {
							$plugin_icon[ $plugin['slug'] ] = $plugin['icons'];
						}
					}
					?>
					<div class="sp-logo-carousel-our-plugin-list">
						<h3 class="sp-logo-carousel-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="sp-logo-carousel-our-plugin-list-wrap">
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://wpcarousel.io/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['wp-carousel-free'] ); ?>" alt="WP Carousel">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://realtestimonials.io/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['testimonial-free'] ); ?>" alt="Real Testimonials">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://smartpostshow.com/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['post-carousel'] ); ?>" alt="Smart Post Show">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://wooproductslider.io/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['woo-product-slider'] ); ?>" alt="Product Slider for WooCommerce">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://woogallery.io/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['gallery-slider-for-woocommerce'] ); ?>" alt="WooGallery">
								<h4>WooGallery</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://getwpteam.com/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['team-free'] ); ?>" alt="WP Team">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://shapedplugin.com/smart-swatches-for-woocommerce/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['smart-swatches'] ); ?>" alt="Smart Swatches for WooCommerce">
								<h4>Smart Swatches for WooCommerce</h4>
								<p>Appealing color, image, and button variation swatches on your WooCommerce Shop and Product pages in minutes to increase sales.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://easyaccordion.io/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['easy-accordion-free'] ); ?>" alt="Easy Accordion">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://shapedplugin.com/woocategory/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['woo-category-slider-grid'] ); ?>" alt="WooCategory">
								<h4>WooCategory</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://wptabs.com/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['wp-expand-tabs-free'] ); ?>" alt="WP Tabs">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://shapedplugin.com/quick-view-for-woocommerce/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['woo-quickview'] ); ?>" alt="Quick View for WooCommerce">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="sp-logo-carousel-our-plugin-list-box" href="https://shapedplugin.com/smart-brands/?ref=1">
								<i class="sp-logo-carousel-icon-button-arrow-icon"></i>
								<img src="<?php echo esc_url( $plugin_icon['smart-brands-for-woocommerce'] ); ?>" alt="Smart Brands for WooCommerce">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="sp-logo-carousel-footer">
				<div class="sp-logo-carousel-footer-top">
					<p><span>Made With <i class="sp-logo-carousel-icon-heart"></i> </span> By the <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a> Team</p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="sp-logo-carousel-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="sp-logo-carousel-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="sp-logo-carousel-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="sp-logo-carousel-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}

	/**
	 * Stats Page Callback function.
	 *
	 * @return void
	 */
	public function display_logo_analytics() {
		?>
		<div class="lcp-logo-indicator-notice">Want to know valuable insights or analytics into logos performance? To track impressions, clicks, and more to optimize engagement, <a href="https://logocarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a></div>
		<div class="lcp-logo-indicator">
		<img src="<?php echo esc_url( SP_LC_URL ) . 'admin/assets/css/images/analytic.webp'; ?>" alt="logo-indicator">
		</div>
		<?php
	}
}

new SPLC_Free_Loader();
