<?php
//* Template Name: Category Page
//* This page features posts categorised as 'category selected + news' at the top and the 'category selected + insights' at the bottom.
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
		<h1 class="front-title"><?php echo get_the_title(); ?></h1>
		<ol class="latest-two-stories">
			<?php $pgurl =  strtolower(get_the_title( $ID )); ?>
			<?php $cat_select = get_field('select_category'); ?>
			<?php $my_query = new WP_Query(array( 'category__and' => array( 4, $cat_select ), 'posts_per_page' => 2 ) ); ?>

			<?php $counter = 1 ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<li class="latest-stories clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">

<?php
$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 600, 400 ), false, '' );
?>
<a href="<?php the_permalink() ?>">
<div class="latest-stories-thumb" style="background: url(<?php echo $src[0]; ?> )!important; background-position: center center !important; background-size: cover !important; background-repeat:no-repeat !important">
    </div></a>
	            <p class="latest-stories-date"><?php echo get_the_date('j F, Y'); ?></p>
				<h2><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h2>
				<div class="latest-stories-excerpt"><?php wpden_excerpt('latest_news'); ?></div>
			</li>
			<?php $counter++ ;
			endwhile; ?>
			<?php  wp_reset_postdata(); ?>
		</ol>
</div>

 <!-- /******************** CATEGORY/NEWS CONT. (OFFSET 2) ************************* -->

	<div class="wrap top-bottom-margin">
 <ol class="category-latest-continued">

			<?php $cat_select = get_field('select_category'); ?>
			<?php $my_query = new WP_Query(array( 'category__and' => array( 4, $cat_select ), 'posts_per_page' => 6, 'offset' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<li class="latest-news-wrap clearfix">
        <?php the_post_thumbnail( 'latest-news-thumb' ); ?>
        <!-- <p class="front-latest-date"><?php echo get_the_date('j F, Y'); ?></p> -->
				<p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
				<span><?php echo get_the_date('j F, Y'); ?></span></p>
				<h4><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
				<span class="front-latest-excerpt"><?php wpden_excerpt('latest_news'); ?></span>
			</li>
			<?php endwhile; ?>
			<?php  wp_reset_postdata(); ?>

		</ol>
        <a href="/news/<?php echo $pgurl; ?>-news/" class="button read-more" style="">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
</div>

 <!-- /******************** CATEGORY INSIGHTS ************************* -->

		<div class="wrap top-bottom-margin">
    	<h3 class="front-title">Insights</h3>
    	<div class="clearfix">

	    	<ol class="front-analysis floatleft">

				<?php $cat_select = get_field('select_category'); ?>
				<?php $my_query = new WP_Query(array( 'category__and' => array( 7, $cat_select ), 'posts_per_page' => 4 ) ); ?>

				<?php $counter = 1 ?>
				<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
				<?php $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
				<li class="news-analysis-wrap clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
		            <?php the_post_thumbnail( 'category-thumb' ); ?>
					<div class="front-latest-title"><a href="<?php the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></div>
					<div class="front-latest-excerpt"><?php wpden_excerpt('news_analysis'); ?></div>
				</li>
				<?php $counter++ ;
				endwhile ?>
				<?php  wp_reset_postdata(); ?>
			</ol>
<a class="button read-more" href="/insights/<?php echo $pgurl; ?>-insights" style="text-transform: lowercase;">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
    	</div>
    </div>


<?php }


genesis();
