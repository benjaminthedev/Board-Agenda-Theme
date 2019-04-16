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

function board_agenda_archive() { ?>

	<div class="wrap top-bottom-margin">
		<?php if ( have_posts() ) : 

      $category_ids = ba_get_category_ids_for_acf();
			$current_cat  = get_category( get_query_var( 'cat' ), false );
			
			$sponsor_text = get_field( 'sponsor_text', $category_ids[ $current_cat->slug ] );
			$sponsor_link = get_field( 'sponsor_link', $category_ids[ $current_cat->slug ] );
			$sponsor_logo = get_field( 'sponsor_logo', $category_ids[ $current_cat->slug ] );
      
      /*
			var_dump( $sponsor_logo );
			die();
			*/

		?>
			<h2 class="front-title">
				<?php single_cat_title(); ?>
       
       <?php
    		 if( $sponsor_text !== NULL && $sponsor_text !== '' 
          && $sponsor_logo !== NULL && $sponsor_logo !== '' ):
        ?>
             	<a href="<?php echo $sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $sponsor_text; ?>
             		<img src="<?php echo $sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
			</h2>

			<ol class="<?php echo is_paged() ? 'category-latest-continued' : 'latest-two-stories' ?>">
			<?php
			// Start the Loop.
			$counter = 0;
			while ( have_posts() ) : the_post();

				$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

				$topic = '';
				//The resource topic
				$is_resource = 'resources' == get_post_type();
				if( $is_resource ) {
					$terms = wp_get_post_terms( get_the_ID(), 'resources-categories' );

					if( ! empty( $terms ) ) {
						$topic = $terms[0]->name . ', ';
					}
				}
				//Show the first 2 bigger
				?>
				<?php if( ++$counter <= 2 && ! is_paged() ) : ?>

					<li class="latest-stories clearfix <?php echo ($counter == 2 ) ? 'omega' : 'alpha'; ?>">
						<?php $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 5600,1000 ), false, '' ); ?>
						<a href="<?php the_permalink() ?>">
							<div class="latest-stories-thumb" style="background: url(<?php echo $src[0]; ?> )!important; background-position: center center !important; background-size: cover !important; background-repeat:no-repeat !important">
								<?php if( $is_resource ) echo '<span class="fa fa-cloud-download"></span>'; ?>
							</div>
						</a>

            <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?><?php echo $topic ?></span>
	          <span><?php echo get_the_date('j F, Y'); ?></span></p>
						<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
						<span class="latest-stories-excerpt top-two"><?php wpden_excerpt('news_feed'); ?></span>
					</li>
				<?php else: ?>
					<?php if( ! is_paged() && $counter == 3 ) : ?>
						</ol>
						<ol class="category-latest-continued">
					<?php endif; ?>
					<div class="latest-news-wrap clearfix">
						<div class="img">
			        <?php the_post_thumbnail( 'latest-news-thumb' ); ?>
							<?php if( $is_resource ) echo '<span class="fa fa-cloud-download"></span>'; ?>
						</div>

					<?php if( ! is_category( 'insights' ) ) : ?>
		        	<p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?><?php echo $topic ?></span>
		          <span><?php echo get_the_date('j F, Y'); ?></span></p>
					<?php endif; ?>
					<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
						<span class="front-latest-excerpt"> <?php wpden_excerpt('news_feed'); ?></span>
					</div>
				<?php endif; ?>
				<?php

			// End the loop.
			endwhile;

			genesis_posts_nav();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>
		</ol>

</div>

<?php }

genesis();
