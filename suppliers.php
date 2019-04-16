 <?php
//* Template Name: Suppliers

//getting alpha datat
$alpha_data = get_alpha_data();

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_resource_centre' );

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);

}

function board_agenda_resource_centre() {

  //getting alpha datat
  $alpha_data = get_alpha_data();

  $letter = isset($_GET['letter']) ? $_GET['letter'] : '';
  $letter = strtoupper($letter);

  //featured nonce
  $featured_nonce = wp_create_nonce('featured');

  //all nonce
  $all_nonce = wp_create_nonce('all');

?>
  <article <?php post_class( 'entry' ); ?>>

        <h2 class="front-title resource-list-title"><?php the_title(); ?></h2>

        <div class="resource_wrapper">
          <form action="" method="get" id="resource-searchform">
            <div class="resource_form_wrapper">
              <div class="sup_alpha_wrapper">
                <ul class="sup_alpha_ul">
                  <li>
                    <a href="<?php echo remove_query_arg('letter' ); ?>" class="sup_alpha_label <?php if($letter === '') echo 'alp_selected'; ?>">ALL</a>
                  </li>
                  <?php
                    // foreach($alpha_data as $key => $value){
                    for($i = 65; $i < 91; $i++) {
                      $value = chr($i);
                      ?>
                        <li>
                          <a href="<?php echo add_query_arg('letter', $value ); ?>" class="sup_alpha_label <?php if($letter === $value) echo 'alp_selected'; ?>"><?php echo strtoupper($value); ?></a>
                        </li>
                      <?php

                    }//end foreach
                  ?>
                </ul>
              </div>
              <div class="resource_form_element">
                <input type="text" name="search" id="search" placeholder="Search..." value="<?php echo @$_GET['search']; ?>" class="resource_form_input resource_margin">
                <label for="searchsubmit" class="button fa fa-search resource_icon_label resource_margin"></label>
              </div>
              <div class="resource_form_element resource_form_category">
                <?php
                $categories = get_terms('partner-categories', array('hide_empty' => false)); $selected = @$_GET['categories']; $category_name = 'All categories'; ?>
                <label for="category" class="resource-category empty">
                  Categories
                  <?php
                    $selected = ( isset( $_GET['categories'] ) && ! empty( $_GET['categories'] ) ) ? $_GET['categories'] : array();
                  ?>
                </label>
                  <div class="resource-category-list">
                  <?php foreach($categories as $cat ):
                    if($cat->term_id == $category) $category_name = $cat->name;
                    ?>
                    <div class="resource-category-item">
                      <input type="checkbox" name="categories[]" id="category-<?php echo $cat->term_id ?>" value="<?php echo $cat->slug; ?>" <?php echo ( in_array( $cat->slug, $selected ) ) ? 'checked="checked"' : ''; ?>>
                      <label for="category-<?php echo $cat->term_id ?>" class="tick"></label>
                      <label for="category-<?php echo $cat->term_id ?>" class="label"><?php echo $cat->name; ?></label>
                    </div>
                  <?php endforeach; ?>
                </div>
                <label for="searchsubmit" class="button fa fa-angle-down resource_icon_label resource_margin"></label>
              </div>
              <div class="resource_form_element resource_form_filter">
                <input type="submit" value="FILTER" class="resources_form_input resource_submit">
              </div>
            </div>
          </form>
        </div>
			<?php if( get_query_var('paged', 1) <= 1 ) : ?>
        <div class="resources-list" id="featured_container">
          <h3 class="resources_container_title">Featured Partners</h3>
            <?php
              $args = array(
                'post_type' => 'partners',
                'posts_per_page' => -1,
                'meta_key' => 'position',
								'meta_value' => 0,
                'meta_compare' => '>=',
                'partner_search' => 1,
								'orderby' => array( 'meta_value' => 'DESC', 'post_modified' => 'DESC' ),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key' => 'featured',
										'value' => 1,
									)
								)
              );
              $GLOBALS['_where'] = 1;

              // include categories if required.
              if ( isset( $_GET['categories'] ) && ! empty( $_GET['categories'] ) ) {
                $tax_query = array(
                  'relation' => 'AND',
                );

                foreach ( $categories as $slug ) {
                  $tax_query[] = array(
                    'taxonomy'        => 'partner-categories',
                    'field'           => 'slug',
                    'terms'           => $slug,
                    'include_chidren' => true,
                    'operator'        => 'IN'
                  );
                }

                $args['tax_query'] = $tax_query;
              }
              $posts = new WP_Query($args);

              unset($GLOBALS['_where']);

              while( $posts->have_posts() ) {
                $posts->the_post();

                get_template_part( 'template-parts/resource', 'partner' );
              }

              wp_reset_postdata();
            ?>
          <!-- <a class="sup_end_list_link" id="all_featured" data-ajax="<?php echo admin_url('admin-ajax.php'); ?>" data-offset="<?php echo $posts->post_count; ?>" data-type="featured" data-nonce="<?php echo $featured_nonce; ?>">All Featured Partners <i class="fa fa-chevron-circle-right end_list_icon"></i></a> -->
        </div>
			<?php endif; ?>
        <div class="resources-list">
          <h3 class="resources_container_title">All Corporate &amp; Advisory Services</h3>
            <?php
              $args = array(
                'post_type' => 'partners',
                'posts_per_page' => 10,
                'partner_search' => 1,
                'paged' => get_query_var('paged', 1),
								'meta_key' => 'position',
								'meta_value' => 0,
                'meta_compare' => '>=',
								'orderby' => array( 'meta_value' => 'DESC', 'post_modified' => 'DESC' ),
								'meta_query' => array(
									'relation' => 'AND',
									array(
										'key' => 'featured',
										'value' => 0,
									)
								)
              );
              $GLOBALS['_where'] = 1;

               // include categories if required.
               if ( isset( $_GET['categories'] ) && ! empty( $_GET['categories'] ) ) {
                $tax_query = array(
                  'relation' => 'AND',
                );

                foreach ( $categories as $slug ) {
                  $tax_query[] = array(
                    'taxonomy'        => 'partner-categories',
                    'field'           => 'slug',
                    'terms'           => $slug,
                    'include_chidren' => true,
                    'operator'        => 'IN'
                  );
                }

                $args['tax_query'] = $tax_query;
              }

              $posts = new WP_Query($args);
              unset($GLOBALS['_where']);

              while( $posts->have_posts() ) {

                $posts->the_post();

                get_template_part( 'template-parts/resource', 'partner' );

              }

              wp_reset_postdata();
            ?>
          <!-- <a class="sup_end_list_link" id="all" data-ajax="<?php echo admin_url('admin-ajax.php'); ?>" data-offset="<?php echo $posts->post_count; ?>" data-type="all" data-nonce="<?php echo $all_nonce; ?>">SEE ALL<i class="fa fa-chevron-circle-right end_list_icon"></i></a> -->
    </div>
    <?php

      // displaying custom pagination
      page_custom_pagination($posts->max_num_pages, '', $paged);
      wp_reset_postdata();
    ?>
  </article>


<?php
}

genesis();
