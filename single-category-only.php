<?php

//* Template Name: Single Category Only
//* This page features posts from one single category
add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );

		add_action( 'genesis_loop', 'board_agenda_news_sub_category', 1);

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);
			/** Replace the standard loop with our custom loop */

add_action( 'genesis_loop', 'wnd_do_custom_loop', 2 );

}

function board_agenda_news_sub_category(){ ?>
	<div class="wrap top-bottom-margin">
		<h2 class="front-title"><?php echo get_the_title(); ?></h2>

		<?php if( ! is_paged() ) : ?>
		<ol class="latest-two-stories">

			<?php $cat_select = get_field('select_category'); ?>
			<?php $my_query = new WP_Query(array( 'category__and' => array( $cat_select ), 'posts_per_page' => 2 ) ); ?>

			<?php $counter = 1 ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<li class="latest-stories clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
	             <?php $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 5600,1000 ), false, '' ); ?>

                <a href="<?php the_permalink() ?>">
<div class="latest-stories-thumb" style="background: url(<?php echo $src[0]; ?> )!important; background-position: center center !important; background-size: cover !important; background-repeat:no-repeat !important">
                    </div> </a>
	         
							<p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
		          <span><?php echo get_the_date('j F, Y'); ?></span></p>
				<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
				<span class="latest-stories-excerpt top-two"><?php wpden_excerpt('news_feed'); ?></span>
			</li>
			<?php $counter++ ;
			endwhile;

			?>

			<?php  wp_reset_postdata(); ?>
		</ol>

		<?php endif; ?>
</div>
<?php }
/******************** CATEGORY CONT. (OFFSET 2) *************************/

function wnd_do_custom_loop() { ?>
	<div class="wrap top-bottom-margin">
 <ol class="category-latest-continued">
<?php
	$cat_select = get_field('select_category');
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    global $paged; // current paginated page

    $args = array(
    	'post_type' =>   'post',
        'cat' => $cat_select,
        'posts_per_page' => 10,
        'paged'   => $paged,
    );

		if( ! is_paged() ) {
			$args['offset'] = 2;
		}


		/**
		 * The first page is showing 10 posts, 2 bigs + 8 "normal" list.
		 * If I write:
		 * 		'posts_per_page' => is_paged() ? 8 : 10
		 *
		 * I have wrong page numbers for the first page, so I'm using always 10
		 * and skip the latest 2 from the 1st page.
		 */
		$count = 0;

 	query_posts( $args );
    if (have_posts()) :
	    while (have_posts()) : the_post();
			//if( ++$count > 8 ) continue;
			?>

				<li class="latest-news-wrap clearfix">
                    <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'latest-news-thumb' ); ?></a>
		            <!-- <p class="front-latest-date"><?php echo get_the_date('j F, Y'); ?></p> -->
								<p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
			          <span><?php echo get_the_date('j F, Y'); ?></span></p>
					<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
					<span class="front-latest-excerpt"><?php wpden_excerpt('latest_news'); ?></span>
				</li>
				<?php

		endwhile;
				genesis_posts_nav();

				wp_reset_postdata();
	endif;
	?>
	</ol>
</div>
	<?php
}



genesis();
