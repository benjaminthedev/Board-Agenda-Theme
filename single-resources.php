 <?php
//* Template Name: Single Resource

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_news_sub_category' );

		// add_action('genesis_before_footer','board_agenda_front_latest_insight',1);

}

function board_agenda_news_sub_category() {
  global $nine3_Membership;

  // The back url, contains "javascript: history.back()" if the referrer is the /resource-center page
  // so user will see the latest search filter applied
  $back_link = home_url('/resource-centre');
  if( isset($_SERVER['HTTP_REFERER']) ) {
    if( stripos($_SERVER['HTTP_REFERER'], '/resource-centre') > 0 ) {
      $back_link = 'javascript:history.back()';
    }
  }

   //getting current post id
  $post_id = get_the_id();

  $partner_data = get_partner_data($post_id);

  //featured post image
  $featured_image_url = get_field('the_featured_post_image', $post_id);

  //getting post title
  $post_title = get_the_title();

  //getting post content
  $post_content = get_post_filtered_content($post_id);

  $partner = get_field('partner');

  // hack to get permalink and fix it - ba_restore_partner_slug fixes permalink issue for current resource partner.
  $post_slug    = get_post_field( 'post_name', $partner->ID );
  $partner_link = ( empty( $post_slug ) ) ? ba_restore_partner_slug( $partner ) : get_permalink( $partner->ID );

  ?>

	<section class="wrap top-bottom-margin">
    <a href="<?php echo $back_link ?>" class="">&laquo; back to search results</a>
    <article class="res_single_post_wrapper">
      <header class="res_single_header">
        <div class="res_single_header_left">
          <nav>
            <?php $terms = wp_get_post_terms( get_the_ID(), 'resources-categories' ); ?>
            <ul class="res_single_tags">
              <?php foreach($terms as $term): ?>
                <li>
                  <a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </nav>
          <h3 class="res_single_post_title"><?php the_title(); ?></h3>
        </div>
        <?php if ( isset( $partner_data->thum_image_url) && ! empty( $partner_data->thum_image_url ) ) : ?>
          <div class="res_single_header_right">
            <img src="<?php echo $partner_data->thum_image_url; ?>" class="res_single_thumb_image">
          </div>
        <?php endif; ?>
      </header>
      <hr/>
      <section class="res_single_main">
        <img src="<?php echo $featured_image_url['sizes']['medium']; ?>" class="res_single_featured_image">
        <?php echo $post_content; ?>
      <?php
    if( $nine3_Membership->user_can_access_to_resource() ) :

      //checking which field is being used
      $resource_type = get_field('type_of_donwload');

      if(is_null($resource_type) || $resource_type == 'upload' ) {
	      // Hide the download link
	      echo '<p><a class="button resource-download" href="' . home_url() . '/download/?resource=' . get_the_id() . '">DOWNLOAD</a></p>';
	    }
    else{
      $external_resource_link = get_field('resource_external_link');
      echo '<p><a class="button resource-download" target="_blank" href="' . $external_resource_link . '">VIEW RESOURCE</a></p>';
    }
      echo '</section>';
    else:
      ?>
      </section>
      <section class="res_single_main res_extra_padding">
        <?php echo get_template_part('template-parts/resource', 'register'); ?>
      </section>
    <?php endif; ?>
      <hr/>
      <footer class="res_single_main res_single_main_footer">
        <header class="res_single_partner_header">
          <p class="res_single_partner_title"><a href="<?php echo get_permalink($partner->ID); ?>" class="res_single_partner_name"><?php echo $partner->post_title ?></a></p>
        </header>
        <div class="res_single_partner_main">
        <?php if ( isset( $partner_data->featured_image_url ) && ! empty( $partner_data->featured_image_url ) ) : ?>
          <img src="<?php echo $partner_data->featured_image_url['url']; ?>" class="res_single_partner_featured_image">
        <?php endif; ?>
          <p class="res_single_partner_content">
            <?php
            if ( isset( $partner->post_excerpt ) && ! empty( $partner->post_excerpt ) ) : 
              echo esc_html( $partner->post_excerpt ) . ' ...';
            endif; 

            if ( isset( $partner_link ) && ! empty( $partner_link ) ) : ?>
              <a href="<?php echo esc_url( $partner_link ); ?>" class="res_single_patner_permalink">View profile &raquo;</a></p>
            <?php endif; ?>
        </div>
      </footer>
    </article>
	</section>

  <?php

    //getting current post id
    $post_id = get_the_id();

    $partner_data = get_partner_data($post_id);
    $args = array(
      'post_type' => 'resources',
      'orderby' => 'date',
      'posts_per_page' => 3,
      'meta_key' => 'partner',
      'meta_value' => $partner_data->partner_id,
     );

    $posts = new WP_Query( $args );
    if( $posts->have_posts() ) : ?>
  <h2 class="front-title resource-list-title">RELATED RESOURCES</h2>
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

  <?php endif; ?>

<?php }


genesis();

?>
