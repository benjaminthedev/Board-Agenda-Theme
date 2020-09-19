<?php
/**
 * The template for displaying search results pages.
 *
 */
get_header(); ?>

<div class="wrap">
	<section id="primary" class="wrap">
		<main id="main" class="site-main" role="main">
<div class="facetwp-template">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="front-title test">
				<?php printf( __( 'Search Results for: %s', 'board-agenda' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

			<ol class="category-latest-continued">

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post(); ?>

				<?php
				/*
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				
				/**
				 * Exclude sitemap to be displayed in search 
				 */
				$post_title = get_the_title();
				if( trim( ucfirst( $post_title  ) ) == 'Sitemap' )
					continue;


				$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

				//The resource topic
				$topic = '';
				$is_resource = 'resources' == get_post_type();
				if( $is_resource ) {
					$terms = wp_get_post_terms( get_the_ID(), 'resources-categories' );

					if( ! empty( $terms ) ) {
						$topic = $terms[0]->name . ', ';
					}
				}
				?>

				<li class="latest-news-wrap clearfix">
					<div class="img">
					<a href="<?php the_permalink() ?>" class="new-link">
	          			<?php the_post_thumbnail( 'latest-news-thumb' ); ?>
			  		</a>
						<?php if( $is_resource ) echo '<span class="fa fa-cloud-download"></span>'; ?>
					</div>
					<p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?><?php echo $topic; ?></span>
          <span><?php echo get_the_date('j F, Y'); ?></span></p>
					<h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
						<span class="front-latest-excerpt front-latest-excerpt--search">
						<?php wpden_excerpt('latest_news'); ?>
						<a href="<?php the_permalink() ?>" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
						</span>

				</li>

				
				<?php



			// End the loop.
			endwhile;

			echo '</ol>';?>


			<div class="nav-previous alignleft"><?php previous_posts_link( 'Older posts' ); ?></div>
			<div class="nav-next alignright"><?php next_posts_link( 'Newer posts' ); ?></div>


			<?php echo do_shortcode('[facetwp facet="categories"]'); ?>
		<?php 
		// If no content, include the "No posts found" template.
		else :
			echo '<h2>No result found!</h2>';
		endif;
		?>
		</div>


			<h1>facetwp</h1>

<?php dynamic_sidebar('search-side'); ?> 


		</main><!-- .site-main -->
	
<!-- Notes

1, add a new sidebar
2, add the shortcode for the filters
nope it don't work.

 -->



	</section><!-- .content-area -->
</div>

<style>

.latest-news-wrap .img img{
	width:160px;
	border: 2px solid #000;
}

.category-latest-continued h3{
	font-size: 19px;
}

.front-latest-excerpt {
    font-size: 16px;
    float: left;
    max-width: 710px;
    margin-top: 10px;
}

.latest-news-wrap{
	margin-bottom: 50px;
}

</style>


<?php get_footer(); ?>
