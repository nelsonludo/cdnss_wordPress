<?php
/**
 * Custom search results template with styled header and post cards
 *
 * @package Astra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php
if ( astra_page_layout() === 'left-sidebar' ) {
	get_sidebar();
}
?>

<div id="primary" <?php astra_primary_class(); ?>>

	<?php astra_primary_content_top(); ?>

	<!-- Custom Header -->
	<section class="advanced-search-header">
		<h1 class="page-title">Recherche Avancée</h1>
		<p>Trouvez exactement ce que vous cherchez</p>
		<!-- Advanced Search Form -->
		<section class="advanced-search-form">
			<form role="search" method="get" action="/">
				
				<!-- Keyword -->
				<div class="form-field">
					<label for="s">Mot-clé</label>
					<input type="text" name="s" id="s" value="<?php echo get_search_query(); ?>" placeholder="Entrez un mot-clé">
				</div>

				<!-- Category -->
				<div class="form-field">
					<label for="category">Catégorie</label>
					<select name="cat" id="category">
						<option value="">Toutes les catégories</option>
						<?php 
						$categories = get_categories();
						foreach ( $categories as $category ) {
							echo '<option value="' . esc_attr($category->term_id) . '"' . selected( get_query_var('cat'), $category->term_id, false ) . '>' . esc_html($category->name) . '</option>';
						}
						?>
					</select>
				</div>

				<!-- Author -->
				<div class="form-field">
					<label for="author">Auteur</label>
					<select name="author" id="author">
						<option value="">Tous les auteurs</option>
						<?php 
						$authors = get_users(['who' => 'authors']);
						foreach ( $authors as $author ) {
							echo '<option value="' . esc_attr($author->ID) . '"' . selected( get_query_var('author'), $author->ID, false ) . '>' . esc_html($author->display_name) . '</option>';
						}
						?>
					</select>
				</div>

				<!-- Date Range -->
				<div class="form-field">
					<label for="date_from">Date de</label>
					<input type="date" name="date_from" id="date_from" value="<?php echo isset($_GET['date_from']) ? esc_attr($_GET['date_from']) : ''; ?>">
				</div>

				<div class="form-field">
					<label for="date_to">À</label>
					<input type="date" name="date_to" id="date_to" value="<?php echo isset($_GET['date_to']) ? esc_attr($_GET['date_to']) : ''; ?>">
				</div>

				<!-- Submit Button -->
				<div class="form-field">
					<button type="submit">Rechercher</button>
				</div>

			</form>
		</section>

	</section>

	<?php
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$search_query = get_search_query();

	$args = [
		'post_type' => 'post',
		's' => $search_query,
		'posts_per_page' => 6,
		'paged' => $paged,
	];

	$results = new WP_Query( $args );

	if ( $results->have_posts() ) :
		?>

		<div class="search-results-container">

			<?php while ( $results->have_posts() ) : $results->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('search-card'); ?>>
					
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="search-card-image">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('medium'); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="search-card-content">
						<h2 class="search-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="search-card-excerpt">
							<?php the_excerpt(); ?>
						</div>
					</div>

				</article>
			<?php endwhile; ?>

		</div>

		<!-- Pagination -->
		<div class="search-pagination">
			<?php
			echo paginate_links([
				'total'   => $results->max_num_pages,
				'current' => $paged,
				'prev_text' => __('« Précédent'),
				'next_text' => __('Suivant »'),
				'mid_size' => 2,
			]);
			?>
		</div>

	<?php
	else :
		echo '<p class="no-results">Aucun résultat trouvé pour votre recherche.</p>';
	endif;

	wp_reset_postdata();
	?>

	<?php astra_primary_content_bottom(); ?>

</div><!-- #primary -->

<?php
if ( astra_page_layout() === 'right-sidebar' ) {
	get_sidebar();
}

get_footer();
?>
