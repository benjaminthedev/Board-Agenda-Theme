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

function board_agenda_news_sub_category(){
  // The back url, contains "javascript: history.back()" if the referrer is the /resource-center page
  // so user will see the latest search filter applied
  $pagelink = home_url('/corporate-advisory-services/');
  if( isset($_SERVER['HTTP_REFERER']) ) {
    if( stripos($_SERVER['HTTP_REFERER'], '/corporate-advisory-services') > 0 ) {
      $pagelink = 'javascript:history.back()';
    }
  }

  $expertise = get_field('company_expertise');

  //thumbnail
  $post_image_url = get_field('the_featured_post_image', $post_id);

  //post title for related posts searching
  $post_title = get_the_title( $post_id );

	$is_resource_partner = ba_is_resource_partner( get_the_ID() );

	define('AREA_LIMIT', $is_resource_partner ? 5 : 1);
  ?>

  <section class="wrap top-bottom-margin">
    <a href="<?php echo $pagelink; ?>" class="res_single_top_link">&laquo; back to search results</a>
    <article class="res_single_post_wrapper">
      <header class="sup_header sup_main_element border-bottom">
        <div class="sup_header_left">
          <h3 class="sup_title"><?php the_title(); ?></h3>
          <p class="sup_subtitle"><?php echo join(', ', $expertise); ?></p>
        </div>
        <div class="sup_header_right">
				<?php if( $is_resource_partner ) : ?>
          <img src="<?php echo $post_image_url['sizes']['medium']; ?>" class="sup_featured_image">
				<?php endif; ?>
        </div>
      </header>
      <div class="sup_main sup_main_element">
        <div class="sup_main_column">
          <div class="sup_element">
            <ul class="sup_header_list">
            <?php if($type = get_field('type')): ?>
              <li>
                <strong>Type: </strong> <?php echo $type; ?>
              </li>
            <?php endif; ?>
            <?php if($is_resource_partner && $at = get_field('annual_turnover')): ?>
              <li>
                <strong>Annual Tunover: </strong> <?php echo $at; ?>
              </li>
            <?php endif; ?>
            <?php if($is_resource_partner && $established = get_field('established')): ?>
              <li>
                <strong>Established: </strong> <?php echo $established; ?>
              </li>
            <?php endif; ?>
            <?php if($is_resource_partner && $size = get_field('number_of_staff')): ?>
              <li>
                <strong>Size: </strong> <?php echo $size; ?> employees
              </li>
            <?php endif; ?>
            <?php if($is_resource_partner && $budget = get_field('budget_from')): $budget_to = get_field('budget_to'); ?>
              <li>
                <strong>Budget range: </strong> <?php echo $budget; ?> <?php echo $budget_to ? " - {$budget_to}" : ""; ?>
              </li>
            <?php endif; ?>
            <?php if($is_resource_partner && $website = get_field('website')): ?>
              <li>
                <strong>Web: </strong> <a href="<?php echo $website ?>"><?php echo untrailingslashit( preg_replace( "/http.?:\/\//", '', $website ) ); ?></a>
              </li>
            <?php endif; ?>
            </ul>
          </div>
          <div class="sup_element partner-description">
            <?php echo ba_get_partner_description(); ?>
          </div>
        <?php if($is_resource_partner && have_rows('clients')): ?>
          <div class="sup_element">
            <h5 class="sup_small_title">Clients:</h5>
          </div>
          <div class="sup_element">
            <ul class="sup_no_wid_list">
            <?php while(have_rows('clients')): the_row(); ?>
                <li><?php the_sub_field('name'); ?></li>
            <?php endwhile; ?>
            </ul>
          </div>
        <?php endif; ?>
        </div>
        <div class="sup_sidebar">
        <?php if($is_resource_partner && $expertise):

          // limit displayed results to five
          $expertise = ba_limit_results($expertise, AREA_LIMIT);

        ?>
          <div class="sup_sidebar_element">
            <h3 class="sup_sidebar_title">Industry</h3>
            <ul class="sup_sidebar_list">
              <li><?php echo join('</li><li>', $expertise); ?></li>
            </ul>
          </div>
        <?php endif; ?>
        <?php if($is_resource_partner && $area_expertise = get_field('area_of_expertise')):

          //limit displayed results to five
          $area_expertise = ba_limit_results($area_expertise, AREA_LIMIT);

          ?>
          <div class="sup_sidebar_element">
            <h3 class="sup_sidebar_title">Area of expertise</h3>
            <ul class="sup_sidebar_list">
              <li><?php echo join('</li><li>', $area_expertise); ?></li>
            </ul>
          </div>
        <?php endif; ?>
        <?php if($is_resource_partner && $sector_expertise = get_field('industry_sector_expertise')):

          //limit displayed results to five
          $sector_expertise = ba_limit_results($sector_expertise, AREA_LIMIT);

        ?>
          <div class="sup_sidebar_element">
            <h3 class="sup_sidebar_title">Sector Expertise</h3>
            <ul class="sup_sidebar_list">
              <li><?php echo join('</li><li>', $sector_expertise); ?></li>
            </ul>
          </div>
        <?php endif; ?>
        </div>
      </div>
        <?php $first = true; ?>
        <?php if(have_rows('addresses')): ?>
				<footer class="sup_footer sup_main_element border-top">
					<div class="sup_element">
	          <h5 class="sup_small_title">Contact:</h5>
	        </div>
	        <div class="sup_element">
          <?php while(have_rows('addresses')) : the_row(); ?>
            <ul class="sup_wid_list">
            <?php if($address = get_sub_field('address')): ?>
              <li><?php echo $address; ?></li>
            <?php endif; ?>
            </ul>
            <ul class="sup_wid_list sup_wid_list-contacts">
            <?php if($first && $website = get_field('website')):  ?>
              <li><i class="fa fa-globe sup_wid_list_icon"></i><a href="<?php echo $website ?>" class="sup_wid_list_link"><?php echo untrailingslashit( preg_replace( "/http.?:\/\//", '', $website ) ); ?></a></li>
            <?php endif; ?>
            <?php if($first && $email = get_field('email')): ?>
              <li><i class="fa fa-envelope sup_wid_list_icon"></i><a href="malito:<?php echo $email ?>" class="sup_wid_list_link"><?php echo $email ?></a></li>
            <?php endif; ?>
            <?php if($phone = get_sub_field('phone')): ?>
              <li><i class="fa fa-phone sup_wid_list_icon"></i><a href="tel:<?php echo $phone ?>" class="sup_wid_list_link"><?php echo $phone ?></a></li>
            <?php endif; ?>
          </ul>
        <?php
				// Show only the first address, for Client User
				if( $first && ! $is_resource_partner )
					break;

				$first = false;
				?>
			<?php endwhile; ?>
					</ul>
				</div>
			</footer>
			<?php endif ?>
    </article>
  </section>

  <?php

    //getting current post id
    $post_id = get_the_id();

    $args = array(
      'post_type' => 'resources',
      'orderby' => 'date',
      'posts_per_page' => 3,
      'meta_key' => 'partner',
      'meta_value' => $post_id,
     );


    $posts = new WP_Query( $args );
    //if there is no partner id then null is returned
    if( $posts->have_posts() ) : ?>
  <h2 class="front-title resource-list-title">PUBLISHED BY <?php the_title(); ?></h2>
  <div class="resources-list">

  <?php
    $class = 'first';
    $i = 1;
    while( $posts->have_posts() ) {
      $posts->the_post();

      get_template_part( 'template-parts/resource', 'centre-list' );
    }

    wp_reset_postdata();
  ?>
  </div>

  <?php endif;  ?>

    <!-- COMMENT block -->
    <div class="wrap top-bottom-margin">
      <h2 class="front-title">Articles</h2>

      <div class="clearfix">
        <ol class="front-analysis floatleft">

        <?php

        $my_query = new WP_Query('showposts=4&cat=5&s=' . $post_title);

        if($my_query->have_posts()){
             $counter = 1;
             while ($my_query->have_posts()) : $my_query->the_post(); ?>

            <li class="news-analysis-wrap clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
              <?php the_post_thumbnail( 'category-thumb' ); ?>
              <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
              <span><?php echo get_the_date('j F, Y'); ?></span></p>
              <h3 class="front-latest-title"><a href="<?php the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></h3>
              <span class="front-latest-excerpt top-two"><?php wpden_excerpt('news_analysis'); ?>
    </span>
            </li>
            <?php $counter++ ;
            endwhile;
          }
          else
            echo "<p>No Articles posts related with this company are available.</p>";
            ?>
      </ol>
      </div>
      <?php if($my_query->have_posts()) : ?>
        <a class="button read-more" href="<?php the_author_posts_link(); ?>" title="View ALL">View ALL<i class="fa fa-chevron-circle-right"></i></a>
      <?php endif; ?>
    </div>

    <?php

      //Displaying interviews HTML block

    ?>

    <div class="wrap top-bottom-margin">
      <h2 class="front-title">Interviews</h2>

      <div class="clearfix">
        <ol class="front-analysis floatleft">

        <?php $my_query = new WP_Query('showposts=4&cat=534&s=' . $post_title);

          if($my_query->have_posts()) {

              $counter = 1;
               while ($my_query->have_posts()) : $my_query->the_post(); ?>

              <li class="news-analysis-wrap clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
                <?php the_post_thumbnail( 'category-thumb' ); ?>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ) echo get_field('topic_selector') . ','; ?></span>
                <span><?php echo get_the_date('j F, Y'); ?></span></p>
                <h3 class="front-latest-title"><a href="<?php the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></h3>
                <span class="front-latest-excerpt top-two"><?php wpden_excerpt('news_analysis'); ?>
      </span>
              </li>
              <?php $counter++ ;
              endwhile;
            }
            else
              echo "<p>No Interview posts related with this company are available.</p>";
          ?>
      </ol>
      </div>
      <?php if($my_query->have_posts()) : ?>
        <a class="button read-more" href="<?php the_author_posts_link(); ?>" title="View ALL">View ALL<i class="fa fa-chevron-circle-right"></i></a>
      <?php endif; ?>
    </div>

<?php }


genesis();

?>
