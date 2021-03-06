 <?php
//* Template Name: Insight Page

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_news_sub_category' );

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);

}

function board_agenda_news_sub_category(){ ?>
	<div class="wrap top-bottom-margin">
		<h2 class="front-title"><?php echo get_the_title(); ?></h2>
		<ol class="latest-two-stories">

			<?php $my_query = new WP_Query(array( 'cat' => 7, 'posts_per_page' => 2 ) ); ?>

			<?php $counter = 1 ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<li class="latest-stories clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
	             <?php
$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 5600,1000 ), false, '' );
?>
 <a href="<?php the_permalink() ?>">
<div class="latest-stories-thumb" style="background: url(<?php echo $src[0]; ?> )!important; background-position: center center !important; background-size: cover !important; background-repeat:no-repeat !important">
     </div></a>
       <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector'); ?></span></p>
				<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
				<span class="latest-stories-excerpt top-two"><?php wpden_excerpt('news_feed'); ?></span>
			</li>
			<?php $counter++ ;
			endwhile; ?>
			<?php  wp_reset_postdata(); ?>
		</ol>
		</div>

 <!-- /******************** NEWS CONT. (OFFSET 2) ************************* -->

 <div class="category-latest-continued">

			<?php $my_query = new WP_Query(array( 'cat' => 7, 'posts_per_page' => 6, 'offset' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<div class="latest-news-wrap clearfix">
	            <?php the_post_thumbnail( 'latest-news-thumb' ); ?>

              <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector'); ?></span></p>
				<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
				<span class="latest-stories-excerpt"> <?php wpden_excerpt('latest_news'); ?></span>
			</div>
			<?php endwhile; ?>
			<?php  wp_reset_postdata(); ?>
    <a class="button read-more" href="index.php?p=234" title="">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
		</div>





<?php }


genesis();
