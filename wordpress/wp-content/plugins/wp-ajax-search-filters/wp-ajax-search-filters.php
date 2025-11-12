<?php
/**
 * Plugin Name: WP AJAX Search Filters
 * Description: Sidebar author + date filters and AJAX-posts results for search pages.
 * Version: 1.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts & styles
 */
function wajsf_enqueue_assets() {
	wp_register_style( 'wajsf-style', plugins_url( 'assets/style.css', __FILE__ ) );
	wp_register_script( 'wajsf-script', plugins_url( 'assets/script.js', __FILE__ ), [ 'jquery' ], null, true );

	wp_localize_script( 'wajsf-script', 'wajsfData', [
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'wajsf_nonce' ),
	] );

	wp_enqueue_style( 'wajsf-style' );
	wp_enqueue_script( 'wajsf-script' );
}
add_action( 'wp_enqueue_scripts', 'wajsf_enqueue_assets' );

/**
 * Helper: get author list (authors with published posts)
 */
function wajsf_get_authors() {
	$args = [
		'who' => 'authors',
		'has_published_posts' => true,
		'orderby' => 'display_name',
		'order' => 'ASC',
		'number' => 0,
	];
	$users = get_users( $args );
	return $users;
}

/**
 * Shortcode: Filters UI
 * Usage: [wajsf_filters]
 */
function wajsf_filters_shortcode( $atts ) {
	$atts = shortcode_atts( [], $atts );
	$authors = wajsf_get_authors();

	ob_start(); ?>
	<div class="wajsf-filters-wrap">
		<!-- Author container -->
		<aside class="wajsf-filter-box wajsf-author-box" data-filter="author">
			<div class="wajsf-filter-header">
				<h4>Author</h4>
				<button class="wajsf-toggle-btn" aria-expanded="true">–</button>
			</div>

			<div class="wajsf-filter-body">
				<div class="wajsf-author-list">
					<?php
					$counter = 0;
					foreach ( $authors as $author ) {
						$counter++;
						$checked = '';
						$show_class = ( $counter > 4 ) ? 'wajsf-hidden-by-default' : '';
						printf(
							'<label class="wajsf-author-item %1$s"><input type="checkbox" name="wajsf_authors[]" value="%2$d" %3$s /> %4$s <span class="wajsf-author-count">(%5$d)</span></label>',
							esc_attr( $show_class ),
							intval( $author->ID ),
							$checked,
							esc_html( $author->display_name ),
							intval( count_user_posts( $author->ID, 'post' ) )
						);
					}
					?>
				</div>

				<?php if ( count( $authors ) > 4 ) : ?>
					<button class="wajsf-show-more">Show more</button>
				<?php endif; ?>

				<div class="wajsf-author-search">
					<label for="wajsf_author_search">Search author name</label>
					<input id="wajsf_author_search" placeholder="Author name" type="text">
				</div>

				<div class="wajsf-controls">
					<button class="wajsf-apply-btn" data-scope="author">Apply</button>
					<button class="wajsf-reset-btn" data-scope="author">Reset</button>
				</div>
			</div>
		</aside>

		<!-- Date container -->
		<aside class="wajsf-filter-box wajsf-date-box" data-filter="date">
			<div class="wajsf-filter-header">
				<h4>Date</h4>
				<button class="wajsf-toggle-btn" aria-expanded="true">–</button>
			</div>

			<div class="wajsf-filter-body">
				<div class="wajsf-date-range">
					<label for="wajsf_date_from">From</label>
					<input id="wajsf_date_from" type="date" />
					<label for="wajsf_date_to">To</label>
					<input id="wajsf_date_to" type="date" />
				</div>

				<div class="wajsf-controls">
					<button class="wajsf-apply-btn" data-scope="date">Apply</button>
					<button class="wajsf-reset-btn" data-scope="date">Reset</button>
				</div>
			</div>
		</aside>

	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'wajsf_filters', 'wajsf_filters_shortcode' );

/**
 * Shortcode: AJAX Results container (initial render + target for AJAX)
 * Usage: [wajsf_results posts_per_page="5"]
 */
function wajsf_results_shortcode( $atts ) {
	$atts = shortcode_atts( [
		'posts_per_page' => 5,
	], $atts );

	// Use current GET values to render initial results for progressive enhancement
	$paged = isset( $_GET['wajsf_paged'] ) ? max( 1, intval( $_GET['wajsf_paged'] ) ) : 1;
	$authors = isset( $_GET['wajsf_authors'] ) ? array_map( 'intval', (array) $_GET['wajsf_authors'] ) : [];
	$date_from = isset( $_GET['wajsf_date_from'] ) ? sanitize_text_field( $_GET['wajsf_date_from'] ) : '';
	$date_to   = isset( $_GET['wajsf_date_to'] ) ? sanitize_text_field( $_GET['wajsf_date_to'] ) : '';
	$s         = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

	$args = [
		'post_type' => 'post',
		'posts_per_page' => intval( $atts['posts_per_page'] ),
		'paged' => $paged,
	];

	if ( $s ) {
		$args['s'] = $s;
	}

	if ( ! empty( $authors ) ) {
		$args['author__in'] = $authors;
	}

	if ( $date_from || $date_to ) {
		$date_query = [];
		if ( $date_from ) {
			$date_query['after'] = $date_from;
		}
		if ( $date_to ) {
			$date_query['before'] = $date_to;
		}
		$args['date_query'] = [ $date_query ];
	}

	$query = new WP_Query( $args );

	// Render same markup used by AJAX response
	ob_start();
	?>
	<div id="wajsf-results" class="wajsf-results" data-posts-per-page="<?php echo intval( $atts['posts_per_page'] ); ?>">
		<?php wajsf_render_posts_list( $query ); ?>
		<?php wajsf_render_pagination( $query, $paged ); ?>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'wajsf_results', 'wajsf_results_shortcode' );

/**
 * Render posts loop (extract so AJAX and initial render match)
 */
function wajsf_render_posts_list( $query ) {
	if ( ! $query->have_posts() ) {
		echo '<p class="wajsf-no-results">No posts found.</p>';
		return;
	}

	echo '<div class="wajsf-posts-list">';

	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<article class="wajsf-post-card">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="wajsf-thumb">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
				</div>
			<?php endif; ?>
			<div class="wajsf-post-content">
				<div class="wajsf-post-meta">
					<span class="wajsf-cat"><?php the_category( ', ' ); ?></span>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</div>
				<h3 class="wajsf-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="wajsf-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 28 ); ?></div>
				<div class="wajsf-author">By <?php the_author_posts_link(); ?></div>
			</div>
		</article>
		<?php
	}
	echo '</div>';
	wp_reset_postdata();
}

/**
 * Render pagination markup (with data attributes for AJAX)
 */
function wajsf_render_pagination( $query, $current_paged = 1 ) {
	$max_pages = $query->max_num_pages;
	if ( $max_pages <= 1 ) {
		return;
	}

	echo '<nav class="wajsf-pagination" aria-label="Posts navigation">';
	// Prev
	if ( $current_paged > 1 ) {
		printf( '<a href="#" class="wajsf-page-link" data-page="%1$d">« Prev</a>', $current_paged - 1 );
	}

	// Numeric pages (simple)
	for ( $i = 1; $i <= $max_pages; $i++ ) {
		$active = ( $i == $current_paged ) ? 'wajsf-current' : '';
		printf( '<a href="#" class="wajsf-page-link %2$s" data-page="%1$d">%1$d</a>', $i, $active );
	}

	// Next
	if ( $current_paged < $max_pages ) {
		printf( '<a href="#" class="wajsf-page-link" data-page="%1$d">Next »</a>', $current_paged + 1 );
	}
	echo '</nav>';
}

/**
 * AJAX Handler: return posts html
 */
function wajsf_ajax_fetch_posts() {
	check_ajax_referer( 'wajsf_nonce', 'nonce' );

	$posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 5;
	$paged = isset( $_POST['paged'] ) ? max( 1, intval( $_POST['paged'] ) ) : 1;
	$authors = isset( $_POST['authors'] ) ? array_map( 'intval', (array) $_POST['authors'] ) : [];
	$date_from = isset( $_POST['date_from'] ) ? sanitize_text_field( $_POST['date_from'] ) : '';
	$date_to = isset( $_POST['date_to'] ) ? sanitize_text_field( $_POST['date_to'] ) : '';
	$s = isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '';

	$args = [
		'post_type' => 'post',
		'posts_per_page' => $posts_per_page,
		'paged' => $paged,
	];

	if ( $s ) {
		$args['s'] = $s;
	}

	if ( ! empty( $authors ) ) {
		$args['author__in'] = $authors;
	}

	if ( $date_from || $date_to ) {
		$date_query = [];
		if ( $date_from ) {
			$date_query['after'] = $date_from;
		}
		if ( $date_to ) {
			$date_query['before'] = $date_to;
		}
		$args['date_query'] = [ $date_query ];
	}

	$query = new WP_Query( $args );

	ob_start();
	wajsf_render_posts_list( $query );
	wajsf_render_pagination( $query, $paged );
	$html = ob_get_clean();

	wp_send_json_success( [
		'html' => $html,
	] );
}
add_action( 'wp_ajax_wajsf_fetch_posts', 'wajsf_ajax_fetch_posts' );
add_action( 'wp_ajax_nopriv_wajsf_fetch_posts', 'wajsf_ajax_fetch_posts' );
