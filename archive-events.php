<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 *
 *
 * Note : At the moment both topics and partners filters are hidden using
 *        comments. If they are set to be definitely hidden then they can
 *        be deleted.
 */
add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_archive' );
}

function board_agenda_archive() {
	global $wpdb;

  remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

  // topics
  // $topics = get_terms('category', array('hide-empty' => true));
	$sql = "
		SELECT term_id as cat_id FROM $wpdb->posts
		LEFT JOIN $wpdb->term_relationships ON
		($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		LEFT JOIN $wpdb->term_taxonomy ON
		($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
		WHERE $wpdb->posts.post_status = 'publish'
		AND $wpdb->posts.post_type = 'events'
		AND $wpdb->term_taxonomy.taxonomy = 'category'
		AND $wpdb->term_taxonomy.count > 0
		GROUP BY term_id
	";
	$results = $wpdb->get_results( $sql );
	$topics = array();
	foreach( $results as $r ) {
		$topics[] = $r->cat_id;
	}

  // partners
  $args = array('post_type' => 'partners', 'numberposts' => 500, 'orderby' => 'title', 'order' => 'ASC');
  $partners = get_posts($args);

  wp_reset_postdata();

  // months
  $selected_month = @$_GET['month'];
  $months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  );

?>

<div class="wrap archive-events">
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="resource_wrapper">
				<form action="" method="get" id="resource-searchform" class="events-filter-form">
					<div class="resource_form_wrapper">
						<div class="resource_form_element">
							<input type="text" name="search" id="search" placeholder="Enter a keyword..." value="<?php echo @$_GET['search']; ?>" class="resource_form_input resource_margin">
							<!--<label for="searchsubmit" class="button fa fa-search resource_icon_label resource_margin"></label>-->
						</div>
						<!--
						<div class="resource_form_element">
							<label for="category" class="resource-category empty">Topics</label>
							<div class="resource-category-list">
								<div class="resource-category-item">

									<?php
									/*
                    $selected = @$_GET['categories'];
                    $selected_partners = isset( $_GET['partner'] ) ? esc_attr( $_GET['partner'] ) : '';
                    $selected_month = @$_GET['month'];
                    $months = array(
                      'January',
                      'February',
                      'March',
                      'April',
                      'May',
                      'June',
                      'July',
                      'August',
                      'September',
                      'October',
                      'November',
                      'December',
                    );
										foreach($topics as $cat_id) :
											$topic = get_category( $cat_id );
											$category = "category-" . $topic->term_id;
											*/
											?>
												<input type="checkbox" name="categories[]" id="<?php //echo $category; ?>" value="<?php// echo $topic->slug; ?>" <?php //echo ( empty($selected) || in_array( $topic->slug, $selected ) ) ? 'checked="checked"' : ''; ?>>
												<label for="<?php //echo $category; ?>" class="tick"></label>
												<label for="<?php //echo $category; ?>" class="label"><?php //echo $topic->name; ?></label>
											<?php
										//endforeach;
									?>

								</div>
							</div>
							<label for="searchsubmit" class="button fa fa-angle-down resource_icon_label resource_margin"></label>
						</div>
					   -->
            <div class="resource_form_element">
							<label for="category" class="resource-category empty"><?php echo ! empty( $_GET['month'] ) ? esc_attr( $_GET['month'] ) : 'Month' ?></label>
							<div class="resource-category-list">
								<div class="resource-category-item">
                  <input type="radio" name="month" id="month-all" value="" <?php echo ( empty($selected) || in_array( $month_name, $selected ) ) ? 'checked="checked"' : ''; ?>>
                  <label for="month-all" class="tick tick--radio"></label>
                  <label for="month-all" class="label">All</label>

                  <?php foreach ( $months as $id => $month_name ) : $month = $id + 1; ?>
                    <input type="radio" name="month" id="month-<?php echo $month; ?>" value="<?php echo $month_name; ?>" <?php checked( $month_name, $selected_month ) ?>>
                    <label for="month-<?php echo $month; ?>" class="tick tick--radio"></label>
                    <label for="month-<?php echo $month; ?>" class="label"><?php echo $month_name; ?></label>
                  <?php endforeach; ?>
                </div>
              </div>
              <label for="searchsubmit" class="button fa fa-angle-down resource_icon_label resource_margin"></label>
            </div>
            
            <!--
            <div class="resource_form_element">
							<label for="category" class="resource-category empty"><?php //echo empty( $_GET['partner'] ) ? 'Partner' : esc_attr( $_GET['partner'] ) ?></label>
							<div class="resource-category-list">
								<div class="resource-category-item">
                  <input type="radio" name="partner" id="partner-all" value="<?php //echo $partner->post_title; ?>" <?php //checked( $selected_partners, '' ) ?>>
                  <label for="partner-all" class="tick tick--radio"></label>
                  <label for="partner-all" class="label">All</label>

                  <?php //foreach ( $partners as $partner ) : ?>
                    <input type="radio" name="partner" id="partner<?php //echo $partner->ID; ?>" value="<?php //echo $partner->post_name; ?>" <?php //checked( $selected_partners, $partner->post_name ) ?>>
                    <label for="partner<?php //echo $partner->ID; ?>" class="tick tick--radio"></label>
                    <label for="partner<?php //echo $partner->ID; ?>" class="label"><?php //echo $partner->post_title; ?></label>
                  <?php //endforeach; ?>
                </div>
              </div>
              <label for="searchsubmit" class="button fa fa-angle-down resource_icon_label resource_margin"></label>
						</div>
					   -->
						<div class="resource_form_element resource_form_filter resource_form_element__filter">
							<input type="submit" value="FILTER" class="resources_form_input resource_submit">
						</div>
					</div>
        </form>
        </div>
			</div>
			<?php if ( have_posts() ) : ?>

			<div class="events-list">
			<?php
				while ( have_posts() ) { the_post();

					get_template_part( 'template-parts/events', 'list' );

				}

				genesis_posts_nav();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
		</div>

		</main><!-- .site-main -->
	</section><!-- .content-area -->
</div>

<?php }

genesis();
