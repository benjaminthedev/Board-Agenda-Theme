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

function board_agenda_archive() { 
	?>
<div class="wrap">
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php 
			if ( have_posts() ) : ?>

			<div class="resources-list">
			<?php

			while ( have_posts() ) { the_post();
				get_template_part( 'template-parts/resource', 'list' );
			}

		endif; ?>
		</div>

		<?php genesis_posts_nav(); ?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->
</div>


<?php }

genesis();
