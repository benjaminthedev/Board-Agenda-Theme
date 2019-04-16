<?php
/*
Template Name: Display Authors
*/

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {


		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_front_content' );
}



function board_agenda_front_content(){ ?>

<?php
/**
 * Can't use the get_user wordpress function, as I need to
 * paginate the results and exclude administrators and
 * subscribers from the list.
 * Can't ignore them after the query is executed, otherwise
 * we'll have different number of rows for each page...
 */
global $wpdb;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$ipp  = 12;
$page = ( $paged - 1 ) * $ipp;


$query = "
    SELECT * FROM ( $wpdb->users t1 INNER JOIN $wpdb->usermeta t2 ON t1.ID = t2.user_id )
    INNER JOIN $wpdb->usermeta t3 ON t1.ID = t3.user_id
    WHERE
    t1.ID NOT IN (
      SELECT t4.user_id FROM $wpdb->usermeta t4 WHERE
      t4.meta_key = 'hide_in_page' AND t4.meta_value = 1
    )
    AND t2.meta_key = 'last_name'
    AND t3.meta_key = '{$wpdb->prefix}capabilities'
    AND t3.meta_value NOT LIKE '%subscriber%'
		ORDER BY t2.meta_value ASC LIMIT $page, $ipp
    ";

$users = $wpdb->get_results( $query );

//Total users count, I need to do the pagination
$query = "SELECT COUNT(*) " . "FROM ( $wpdb->users t1 INNER JOIN $wpdb->usermeta t2 ON t1.ID = t2.user_id )
INNER JOIN $wpdb->usermeta t3 ON t1.ID = t3.user_id
WHERE
t1.ID NOT IN (
  SELECT t4.user_id FROM $wpdb->usermeta t4 WHERE
  t4.meta_key = 'hide_in_page' AND t4.meta_value = 1
)
AND t2.meta_key = 'last_name'
AND t3.meta_key = '{$wpdb->prefix}capabilities'
AND t3.meta_value NOT LIKE '%subscriber%'
	ORDER BY t2.meta_value;";
$total = $wpdb->get_var( $query );

?>


<!-- /***************** Author List ****************/ -->

<section class="content" role="main">

    <div class="wrap top-bottom-margin">

        <h2 class="entry-title authors-title front-title"><?php echo get_the_title(); ?></h2>


        <?php
		foreach($users as $user) : ?>
			<div class="author clearfix">

                <div class="avatar-wrap">
				<div class="authorAvatar">
					<?php $image1 = get_field('author_image', 'user_' . $user->ID); ?>
					<a href="<?php echo get_author_posts_url( $user->ID ); ?>"><img src="<?php echo $image1; ?>"></a>
				</div>
                    </div>

				<div class="authorInfo">
					<h2 class="authorName"><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo $user->display_name; ?></a></h2>

						<ul class="socialIcons">
							<?php

								$linkedin = get_user_meta($user->ID, 'linkedin_profile', true);
								if($linkedin != '')
								{
									printf('<li><a href="%s">%s</a></li>', $linkedin, '<i class="fa fa-linkedin"></i>');
								}

								$twitter = get_user_meta($user->ID, 'twitter_profile', true);
								if($twitter != '')
								{
									printf('<li><a href="%s">%s</a></li>', $twitter, '<i class="fa fa-twitter"></i>');
								}

								$google = get_user_meta($user->ID, 'google_profile', true);
								if($google != '')
								{
									printf('<li><a href="%s">%s</a></li>', $google, '<i class="fa fa-google-plus"></i>');
								}

								$facebook = get_user_meta($user->ID, 'facebook_profile', true);
								if($facebook != '')
								{
									printf('<li><a href="%s">%s</a></li>', $facebook, '<i class="fa fa-facebook"></i>');
								}

								$website = $user->user_url;
								if($user->user_url != '')
								{
									printf('<li><a href="%s">%s</a></li>', $user->user_url, '<i class="fa fa-globe"></i>');
								}

								$authoremail = get_user_meta($user->ID, 'author_email', true);
								//var_dump($authoremail);
								if($authoremail != '')
								{
									//echo '<li><a href="'.$authoremail.'"></a></li>';
									printf('<li><a href="mailto:'.$authoremail.'"><i class="fa fa-envelope"></i></a></li>');
								}

							?>
						</ul>

					<p class="authorDescription"><?php echo get_user_meta($user->ID, 'description', true); ?></p>
					<p class="authorLinks"><a href="<?php echo get_author_posts_url( $user->ID ); ?>">More information &nbsp;</a><i class="fa fa-arrow-circle-right"></i></p>




				</div> <!-- END authorInfo -->
			</div>

		<?php endforeach;	?>

			<div class="archive-pagination pagination">
				<ul>
					<?php if ( $paged > 1 ) : ?>
					<li class="pagination-next"><a href="<?php the_permalink() ?>page/<?php echo $paged - 1 ?>">&laquo; Previous Page </a></li>
					<?php endif; ?>
					<?php
					 $pages = ceil( $total / $ipp );

					 for( $i = 1; $i <= $pages; $i++ ) :
						 $class = ( $i == $paged ) ? 'active' : '';
					?>
					<li class="<?php echo $class ?>"><a href="<?php the_permalink() ?>page/<?php echo $i ?>"><?php echo $i ?></a></li>
					<?php endfor; ?>
					<!-- <li><a href="http://localhost/boardagenda/news/news-analysis/page/2/">2</a></li> -->

					<?php if ( $paged < $pages ) : ?>
					<li class="pagination-next"><a href="<?php the_permalink() ?>page/<?php echo $paged + 1 ?>">Next Page »</a></li>
					<?php endif; ?>
				</ul>
			</div>
    </div>
</section>


<?php }


genesis();
