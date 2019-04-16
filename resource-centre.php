 <?php
//* Template Name: Resource Centre
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
  $resouce_link = get_pagelink_by_name('Resource Centre');  

?>
  <article <?php post_class( 'entry' ); ?>>

      <?php the_content(); ?>

        <h2 class="front-title resource-list-title">REPORTS, WHITEPAPERS & DIRECTOR BRIEFINGS</h2>

        <div class="resource_wrapper">
          <form action="" method="get" id="resource-searchform">
            <div class="resource_form_wrapper">
              <div class="resource_form_element">
                <input type="text" name="search" id="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" class="resource_form_input resource_margin">
                <label for="searchsubmit" class="button fa fa-search resource_icon_label resource_margin"></label>
              </div>
              <div class="resource_form_element resource_form_category">
                <?php
                $categories = get_terms('resource_category', array('hide_empty' => false)); $selected = @$_GET['categories']; ?>
                <label for="category" class="resource-category empty">
                  Categories
                </label>
                <div class="resource-category-list">
                <?php foreach($categories as $cat ):
                  if($cat->term_id == $category) $category_name = $cat->name;
                  ?>
                  <div class="resource-category-item">
                    <input type="checkbox" name="categories[]" id="category-<?php echo $cat->term_id ?>" value="<?php echo $cat->slug; ?>" <?php echo ( empty($selected) || in_array( $cat->slug, $selected ) ) ? 'checked="checked"' : ''; ?>>
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
          <?php
          $GLOBALS['_where'] = 1;
          $paged = get_query_var( 'paged', 1 );

          $featured = get_resource_posts( $paged, true );
          $posts = get_resource_posts( $paged );
          unset( $GLOBALS['_where'] );

          if((isset($_GET['search']) && $_GET['search'] != '') || (isset($_GET['category']) && $_GET['category'] !== '')):
            $search = (isset($_GET['search']) && $_GET['search'] !== '') ? $_GET['search'] : null;
            $category = (isset($_GET['category']) && $_GET['category'] !== '') ? $_GET['category'] : null;

            $total_posts = $featured->post_count + $posts->post_count;

          ?>
            <div class="resource_form_wrapper">
              <p class="resources_filter_text"><?php printf( _n( '%d result', '%d results', $total_posts ), $total_posts ); ?> found for '<?php echo esc_attr( $_GET['search'] ); ?>'</p>
            </div>
          <?php endif; ?>
          </form>
        </div>
				<?php if( $paged <= 1 ) : ?>
        <div class="resources-list">
          <h3 class="resources_container_title">Featured Resources</h3>
            <?php

              if($featured->have_posts()) :
                while( $featured->have_posts() ) {
                  $featured->the_post();

                  get_template_part( 'template-parts/resource', 'centre-list' );
                }

                wp_reset_postdata();
              endif;
            ?>
	    </div>
		<?php endif; ?>
    <div class="resources-list">
      <h3 class="resources_container_title all_resources">All Resources</h3>
      <?php

        if($posts->have_posts()) :
          while( $posts->have_posts() ) {
            $posts->the_post();

            get_template_part( 'template-parts/resource', 'centre-list' );
          }

          wp_reset_postdata();
        endif;
      ?>
    </div>
    <?php

      //displaying custom pagination -- disabled with the custom search form
      if(!isset($_GET['search']) && !isset($_GET['category']))
        page_custom_pagination($posts->max_num_pages, '', $paged);

      wp_reset_postdata();
    ?>

    <!--
       <a class="browse-all button" href="<?php echo home_url(); ?>/resource">BROWSE ALL RESOURCES&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>
            <a class="browse-all button" href="<?php echo home_url(); ?>/partner">BROWSE RESOURCE PARTNERS&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>

    -->
  </article>


<?php
}

genesis();
