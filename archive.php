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
$GLOBALS['is_archive'] = 1;

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

<div class="wrap archive-wrap">
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header" <?php if ( is_tag() ) : echo 'style="min-height: 30px;"'; endif; ?>>
				<?php
					if ( !is_tag() ) :
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					endif;
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); 

			$post_id      = get_the_ID();
			$post_content = get_post_filtered_content( $post_id );
			$excerpt      = crop_text( $post_content, 200 );
			// $old_excerpt  = wpden_excerpt('news_feed');

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				//get_template_part( 'content', get_post_format() );

				$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				?>

				<div class="latest-news-wrap clearfix">
	            <?php the_post_thumbnail( 'latest-news-thumb' ); ?>

							<?php if( ! is_category( 'insights' ) ) : ?>
	            <p class="front-latest-date"><?php echo get_the_date('j F, Y'); ?></p>
							<?php endif; ?>
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				<span class="front-latest-excerpt"> <?php echo $excerpt; ?></span>
			</div>
				<?php

			// End the loop.
			endwhile;

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
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->
</div>

<?php }

genesis();
