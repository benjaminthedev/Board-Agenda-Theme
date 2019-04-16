<?php
//* Template Name: Appointments
//* This page features posts from one single category
add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );

		add_action( 'genesis_loop', 'boardagenda_appointsments_top', 1);

		// add_action('genesis_before_footer','board_agenda_front_latest_insight',1);
			/** Replace the standard loop with our custom loop */

		add_action( 'genesis_loop', 'boardagenda_appointsments_resources', 2 );
		add_action( 'genesis_loop', 'boardagenda_appointsments_education', 3 );

}

function boardagenda_appointsments_top(){ ?>
	<div class="wrap top-bottom-margin">
		<article <?php post_class( 'entry' ); ?>>
	      <?php the_content(); ?>

<!-- 		<h2 class="front-title">Selection</h2>
		<ol class="front-analysis">
			<?php
	      $args = array( 'posts_per_page' => 4,
	       'offset' => 0,
	       'post_type' => 'post',
				 'cat' => 399
	      );

	      $my_query = new WP_Query( $args );

				$i = 0;
	   		while ($my_query->have_posts()) : $my_query->the_post(); ?>
				<li class="news-analysis-wrap clearfix <?php echo $i++ % 2 == 0 ? 'alpha' : 'omega' ?>">
	        <?php the_post_thumbnail( 'latest-news-thumb' ); ?>
	        <p class="front-latest-date"><?php echo get_the_date('j F, Y'); ?></p>
	        <h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
	        <span class="latest-stories-excerpt"><?php wpden_excerpt('latest_news'); ?></span>
				</li>

				<?php endwhile; ?>
			</ol>
			<a href="#" class="button browse-all">READ MORE&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a> -->

<!-- SELECTION -->

    <div class="wrap top-bottom-margin">
    	<h2 class="front-title">Selection</h2>

    	<div class="clearfix">
	    	<ol class="front-analysis floatleft">

				<?php $my_query = new WP_Query('showposts=4&cat=399'); ?>

				<?php $counter = 1 ?>
				<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

				<li class="news-analysis-wrap clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
          <?php the_post_thumbnail( 'category-thumb' ); ?>
          <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
          <span><?php echo get_the_date('j F, Y'); ?></span></p>
					<h3 class="front-latest-title"><a href="<?php the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></h3>
					<span class="front-latest-excerpt"><?php wpden_excerpt('news_analysis'); ?>
</span>
				</li>
				<?php $counter++ ;
				endwhile; ?>
			</ol>
    	</div>
        <a href="/category/selection/" class="button read-more" title="">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
    </div>

<!-- SELECTION ENDS -->


<!-- BOARD MOVES -->

    <div class="wrap top-bottom-margin">
    	<h2 class="front-title">Board Moves</h2>

    	<div class="clearfix">
	    	<ol class="front-analysis floatleft">

				<?php $my_query = new WP_Query('showposts=4&cat=6'); ?>

				<?php $counter = 1 ?>
				<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

				<li class="news-analysis-wrap clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
          <?php the_post_thumbnail( 'category-thumb' ); ?>
          <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
          <span><?php echo get_the_date('j F, Y'); ?></span></p>
					<h3 class="front-latest-title"><a href="<?php the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></h3>
					<span class="front-latest-excerpt"><?php wpden_excerpt('news_analysis'); ?>
</span>
				</li>
				<?php $counter++ ;
				endwhile; ?>
			</ol>
    	</div>
        <a href="index.php?p=191" class="button read-more" title="">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
    </div>

<!-- BOARD MOVES ENDS -->

			<!-- <h2 class="front-title">Search &amp; Selection Company Profiles</h2>
			<ol class="latest-two-stories headhunters-list">
				<?php
		      $args = array( 'posts_per_page' => 2,
		       'offset' => 0,
		       'post_type' => 'partners',
					 'cat' => array( 446 )
		      );

		      $my_query = new WP_Query( $args );

					$i = 0;
					while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<li class="latest-stories clearfix <?php echo $i++ % 2 == 0 ? 'alpha' : 'omega' ?>">

						<?php $src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), array( 600,400 ), false, '' ); ?>
						<a href="<?php the_permalink() ?>">
							 <div class="latest-stories-thumb" style="background: url(<?php echo $src[0]; ?> )!important; background-position: center center !important; background-size: cover !important; background-repeat:no-repeat !important"></div>
						</a>
		        <h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
		        <span class="latest-stories-excerpt"><?php wpden_excerpt('latest_news'); ?></span>
					</li>

					<?php endwhile; ?>
				</ol>
				<a href="<?php echo esc_url( home_url( '/partner' ) ) ?>?category=450" class="button clearfix clear">SEARCH &amp; SELECTION COMPANY PROFILES&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>
      <?php  wp_reset_postdata(); ?>

			<li class="latest-stories clearfix omega">

			</li> -->
		</ol>

	</article>
</div>
<?php }

function boardagenda_appointsments_education() { ?>
	<div class="wrap top-bottom-margin career-courses-block">
		<h2 class="front-title">Events &amp; Executive Education</h2>

		<div class="events-list">
		<?php
			$args = array(
				'post_type' => 'events',
				'orderby' => 'date',
				'posts_per_page' => 5,
				// 'meta_query' => array(
				// 	array(
				// 		'key'     => 'partner',
				// 		'value'   => get_the_ID(),
				// 		'compare' => '=',
				// 	),
				// ),
			);

			$posts = new WP_Query( $args );

			while( $posts->have_posts() ) {
				$posts->the_post();

				get_template_part( 'template-parts/events', 'list' );
			}

			wp_reset_postdata();
		?>
			<?php /* <a class="browse-all button" href="/event">VIEW ALL&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a> */ ?>
		</div>
	</div>
<?php
}


function boardagenda_appointsments_resources() {
?>
	<h2 class="widget-title resource-list-title">RELATED RESOURCES</h2>

	<div class="resources-list">
	<?php
		$args = array(
			'post_type' => 'resources',
			'orderby' => 'date',
			'post__in' => get_field('related_resources'),
		);

		$posts = new WP_Query( $args );

		$class = 'first';
		$i = 1;
		while( $posts->have_posts() ) {
			$posts->the_post();

			get_template_part( 'template-parts/resource', 'list' );
		}

		wp_reset_postdata();
	?>

	</div>
<a class="browse-all button" href="/resource/?category=514">Browse Selection Resources&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>
        <a class="browse-all button" href="<?php bloginfo('url'); ?>/corporate-advisory-services/">Browse Search &amp; Selection Agencies&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>

<?php
}

genesis();
