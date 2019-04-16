<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_archive' );


}


// get_header();

function board_agenda_archive() {
	
	?>

<div class="wrap">
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			/*
			 * I unsuccessfully tried to figure out how to filter the main query
			 * by category, if set in the url.
			 * So, I'm gonna to override the query
			 */
			 //Filter by category
			if( isset( $_GET[ 'category' ] ) && ! empty( $_GET[ 'category' ] ) ) {
				global $wp_query;
				$cat_ID = intval( $_GET[ 'category' ] );

				$args = array(
					'post_type' => 'partners',
					'tax_query' => array(
						array(
							'taxonomy' => 'partner-categories',
							'field' => 'term_id',
							'terms' => array( $cat_ID ),
						),
					),
				);

				$wp_query = new WP_Query( $args );
			}
			?>

			<?php 
			global $wp_query;
			var_dump( $wp_query );
			die( 'herethere' );
			if ( have_posts() ) : 

			?>

			<div class="partners-list">
			<?php while ( have_posts() ) : the_post(); 
				//thumbnail
				$post_id = get_the_ID();
				$post_image_url = get_field('the_featured_post_image', $post_id);
			?>
				<article <?php post_class(); ?>>
					<div class="image">
						<img src="<?php echo $post_image_url['sizes']['medium']; ?>">
					</div>

					<div class="content">
						<?php if( get_field( 'featured' ) ) echo '<small class="featured">FEATURED</small>'; ?>
						<h2><a href="<?php the_permalink(); ?>" class="new-link" target="_blank"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>

						<a href="<?php the_permalink(); ?>" target="_blank">Read more <span class="fa fa-arrow-circle-right"></span></a>
					</div>
				</article>
			<?php endwhile;
			// Previous/next page navigation.
			// the_posts_pagination( array(
			// 	'prev_text'          => __( 'Previous page', 'board-agenda' ),
			// 	'next_text'          => __( 'Next page', 'board-agenda' ),
			// 	'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( '', 'board-agenda' ) . ' </span>',
			// ) );

			genesis_posts_nav();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		wp_reset_postdata();
		?>
		</div>

		</main><!-- .site-main -->
	</section><!-- .content-area -->
</div>

<?php }

genesis();
