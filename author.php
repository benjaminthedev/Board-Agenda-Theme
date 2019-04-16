<?php
//* Template Name: Author
// add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
$GLOBALS['is_archive'] = 1;
add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {


		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_after_header', 'board_agenda_front' );
		add_action( 'genesis_loop', 'board_agenda_front_content' );

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);


}

function board_agenda_front(){ ?>

<?php }

function board_agenda_front_content() {
	/**
	 * hack: I dunno why get_the_author_meta('ID') return
	 * always the same id, so the other the_author_[] functions.
	 * So I'm gonna retrieve the correct ID using the
	 * get_queried_object.
	 */
	$q = get_queried_object();
	$author_id = $q->ID;

?>


<!-- /****************** SINGLE AUTHOR******************** -->
<section class="content" role="main">

    <div class="wrap top-bottom-margin">

<!-- /******************AUTHOR TITLE******************** -->

    <h2 class="entry-title author-title front-title"><?php the_author_meta( 'display_name', $author_id ); ?></h2>


<!-- /****************** AUTHOR SOCIAL LINK ******************** -->

	<ul class="socialIcons icons">
		<?php

			$linkedin_profile = get_the_author_meta( 'linkedin_profile', $author_id );
			if ( $linkedin_profile && $linkedin_profile != '' ) {
					echo '<li class="linkedin"><a href="' . esc_url($linkedin_profile) . '"><i class="fa fa-linkedin"></i></a></li>';
			}

			$twitter_profile = get_the_author_meta( 'twitter_profile', $author_id );
			if ( $twitter_profile && $twitter_profile != '' ) {
					echo '<li class="twitter"><a href="' . esc_url($twitter_profile) . '"><i class="fa fa-twitter"></i></a></li>';
			}

			$google_profile = get_the_author_meta( 'google_profile', $author_id );
			if ( $google_profile && $google_profile != '' ) {
					echo '<li class="google"><a href="' . esc_url($google_profile) . '" rel="author"><i class="fa fa-google-plus"></i></a></li>';
			}

			$facebook_profile = get_the_author_meta( 'facebook_profile', $author_id );
			if ( $facebook_profile && $facebook_profile != '' ) {
					echo '<li class="facebook"><a href="' . esc_url($facebook_profile) . '"><i class="fa fa-facebook"></i></a></li>';
			}

			$website = get_the_author_url();
			if ($website && $website !='' ) { ?>
				<li><a href="<?php the_author_url(); ?>"><i class="fa fa-globe"></i></a></li>
			<?php }

			$author_email = get_the_author_meta( 'author_email', $author_id );
			if ( $author_email && $author_email != '' ) {
				echo '<li><a href="mailto:' . $author_email . '"><i class="fa fa-envelope"></i></a></li>';
			}



		?>
	</ul>


    <div class="avatar-wrap single-author">
	<div class="authorAvatar">
	<?php
		$author_image = get_field('author_image', 'user_'. $author_id );
	?>
	<img src="<?php echo $author_image; ?>" >
        </div>
       </div>

    <div class="authorInfo single-author-info">

		<p class="authorDescription single-author-desc"><?php the_author_meta('description', $author_id); ?></p>

	</div>
<!--END .author-bio-->



    <!-- /******************LATEST ARTICLES******************** -->

<h1 class="secondary-title author-articles">Latest Articles by <?php
if(get_query_var('author_name')) :
    $curauth = get_user_by('slug', get_query_var('author_name'));
else :
    $curauth = get_userdata(get_query_var('author'));
endif;
?>
<?php echo $curauth->nickname; ?></h1>

	<?php
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$args =array(
		'author'=> $author_id,
		'order' => 'ASC',
		'paged' => $paged,
		'posts_per_page' => 5,
	);
	$wp_query = new WP_Query($args);

	if($wp_query->have_posts()){

		while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

				<ol class="latest-articles">
					<li class="latest-news-wrap clearfix">
			            <?php the_post_thumbnail( 'latest-news-thumb' ); ?>
			            <p class="front-latest-date"><?php echo get_the_date('j F, Y'); ?></p>
						<span class="front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></span>
						<span class="front-latest-excerpt"><?php wpden_excerpt('latest_news'); ?></span>
					</li>

	</ol>

					<?php
		endwhile;
		//endif; ?>
		<div class="float:left; clear:both;"><?php posts_nav_link(); ?></div>
		<?php
		//Reset Query
		wp_reset_query();

	}//end  if
	else
		echo "<p>There are not articles published by this author.</p>";

	?>


</div>
    </section>

<?php }


genesis();
