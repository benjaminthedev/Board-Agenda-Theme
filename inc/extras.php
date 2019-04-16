<?php
define( 'IS_PROFILE_PAGE', false );

add_action( 'admin_enqueue_scripts', 'ba_admin_styles', 99 );
function ba_admin_styles() {
	wp_enqueue_style('ba-admin', get_stylesheet_directory_uri() . '/css/admin.css');
	wp_enqueue_script('ba-select', get_stylesheet_directory_uri() . '/js/select2.min.js', array( 'jquery' ));
	wp_enqueue_script('ba-admin', get_stylesheet_directory_uri() . '/js/admin.js', array( 'jquery' ));
}

/**
 * The partner description is stored in the 'company_description' ACF field,
 * but for the old and existing content is stored in the post_excerpt.
 * So, this function check which field return.
 * Also text description have to be limited to 25 words for Client Users, and 300
 * for Resource partners.
 * Also need to ignore tags during the counting.
 * Resource partner has the "Featured" field set to "Top".
 *
 * @since 07/09/2016
 *
 * @param $view_more add the 'View more' text
 * @param integer $resource_limit is the limit to use for resource partner in the /corporate-advisory-services/ page
 */
function ba_get_partner_description( $view_more = false, $resource_limit = 300 ) {
	// Can't allow all the HTML tags, but only: ul, strong
	$allowed = array(
		'ul' => array(),
		'li' => array(),
		'strong' => array(),
		'i' => array(),
		'b' => array(),
		'a' => array(),
	);
  $description = wp_kses( get_the_content(), $allowed );

	$is_resource_partner = get_field('featured') == 1;
	$max_length = $is_resource_partner ? $resource_limit : 25;
	$words = explode(' ', $description);

	$slice = array_slice( $words, 0, $max_length );
	$content = join( ' ', $slice );

	if( $view_more ) {
		$content .= '... <a href="' . get_the_permalink() . '">View profile &raquo;</a>';
	}
	return wpautop( ba_close_tags( $content ) );
}

/**
 * Close all the opened tags in the $content.
 *
 * @param  string $html The HTML to check
 * @return string 		  The formatted HTML with the closing tags
 */
function ba_close_tags($html) {
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i=0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</'.$openedtags[$i].'>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
}

// ===== remove edit profile link from admin bar and side menu and kill profile page if not an admin
if( !current_user_can('activate_plugins') ) {
	function ba_remove_profile_page() {
	    global $wp_admin_bar;
	    $wp_admin_bar->remove_menu('edit-profile', 'user-actions');
	}
	add_action( 'wp_before_admin_bar_render', 'ba_remove_profile_page' );

	/**
	 * When the non admin users try to access to their profile page
	 * we redirect them to the 'front-end' version.
	 */
	function ba_redirect_profile_page() {
    if(IS_PROFILE_PAGE === true) {
      wp_safe_redirect( home_url( '/profile' ) );
    }

    remove_menu_page( 'profile.php' );
    remove_submenu_page( 'users.php', 'profile.php' );
	}

	// add_action( 'admin_init', 'ba_redirect_profile_page' );
}

/**
 * Return the current user profile post
 */
function ba_get_my_profile() {
	$args = array(
		'post_type' => 'partners',
		'post_status' => 'publish',
		'author' => get_current_user_id(),
	);

	return get_posts( $args );
}

/**
 * Need to give the possibility to Resource Partner to create their own
 * company profile, as they could not go through the Free basic process first.
 */
function ba_get_my_profile_edit_link() {
	$post = ba_get_my_profile();

	if( $post ) {
		return get_edit_post_link( $post[0]->ID );
	} else {
		return admin_url( 'post-new.php?post_type=partners' );
	}
}

/**
 * Clean up the admin back-end menu for non 93digital users.
 *
 * The only purpose is just show the info that the client needs, nothing more
 */
 // Create the function to use in the action hook
function example_remove_dashboard_widget() {
  remove_meta_box('dashboard_quick_press','dashboard','side'); //Quick Press widget
  remove_meta_box('dashboard_recent_drafts','dashboard','side'); //Recent Drafts
  remove_meta_box('dashboard_primary','dashboard','side'); //WordPress.com Blog
  remove_meta_box('dashboard_secondary','dashboard','side'); //Other WordPress News
  remove_meta_box('dashboard_incoming_links','dashboard','normal'); //Incoming Links
  remove_meta_box('dashboard_plugins','dashboard','normal'); //Plugins
  remove_meta_box('dashboard_right_now','dashboard', 'normal'); //Right Now
  remove_meta_box('rg_forms_dashboard','dashboard','normal'); //Gravity Forms
  remove_meta_box('dashboard_recent_comments','dashboard','normal'); //Recent Comments
  remove_meta_box('icl_dashboard_widget','dashboard','normal'); //Multi Language Plugin
  remove_meta_box('dashboard_activity','dashboard', 'normal'); //Activity
  remove_meta_box('wpe_dify_news_feed','dashboard', 'normal'); //Activity
  remove_meta_box('wpseo-dashboard-overview','dashboard', 'normal'); //Activity
  remove_action('welcome_panel','wp_welcome_panel');
}

// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'example_remove_dashboard_widget', 99 );


/**
 * Get the specified ad
 */
function ba_widget_get_ad() {
  $id = intval( $_POST['id'] );
  if ( $id > 0 ) {
    echo do_shortcode( '[pro_ad_display_adzone id="' . $id . '"]' );
  }
  die();
}
add_action('wp_ajax_get_ad', 'ba_widget_get_ad');
add_action('wp_ajax_nopriv_get_ad', 'ba_widget_get_ad');

//this function filters the events main query in events archive page by the filter form
function ba_filter_events( $query ) {
  global $wpdb;
	// if ( ! is_archive('events') || ! $query->is_main_query() ) return;

	//filter by month
	if ( isset( $_GET['month'] ) && $_GET['month'] != '' ) {
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

    $month_id = array_search( $_GET['month'], $months );

    if ( $month_id >= 0 ) {
      $meta = array(
        'relation' => 'AND',
        array(
          'key' => 'start_month',
          'value' => $month_id + 1,
        ),
        array(
          'key' => 'end_month',
          'value' => $month_id + 1,
        ),
      );

      $query->set('meta_query', $meta);
    }
  }

	//filter by text search parameter
	if(isset($_GET['search']) && $_GET['search'] !== '')
		$query->set('s', $_GET['search']);

	//filter by topic
	if(isset($_GET['categories']) && !empty($_GET['categories'])){

		//build terms array

		$tax_query = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $_GET['categories'],
				'include_children' => true,
				'operator' => 'IN'
			)
		);

		$query->set('tax_query', $tax_query);

	}// end if isset categories

	if( isset( $_GET['partner'] ) ) {
    // Get the partner id by post_title
    $name = esc_sql( $_GET['partner'] );
    $partner_id = $wpdb->get_var( "SELECT id FROM $wpdb->posts WHERE post_name = '$name' AND post_type = 'partners'" );

    if ( $partner_id > 0 ) {
  		$query->set('meta_key', 'partner');
  		$query->set('meta_value', $_GET['partner']);
    }
	}
}

// add_filter('pre_get_posts', 'ba_filter_events');

/**
 * On Save event need to store the event month in a separate field,
 * as we need to be able to filter the using the pre_get_posts
 */
function ba_save_event_month( $post_id ) {
  $type = $_POST['post_type'];
  if ( 'events' != $type ) return;

  $start_date = explode( '/', get_field( 'start_date', $post_id ) );
  $end_date = explode( '/', get_field( 'end_date', $post_id ) );

  update_post_meta( $post_id, 'start_month', intval( $start_date[1] ) );
  update_post_meta( $post_id, 'end_month', intval( $end_date[1] ) );
}

add_action( 'save_post', 'ba_save_event_month', 999, 1 );


add_filter( 'query', function( $query ) {
  // echo "$query\n";
  return $query;
});

/**
 * Return how many resources can a subscriber publish
 * 
 * @param int ( required ) $user_id
 * @return Object $files_data 
 */
function ba_get_files_left( $user_id ) {
  $files_data = new stdClass();
  global $nine3_Membership;
  $files_data->post_limit = ( $nine3_Membership->get_user_level() == 0 ) ? 2 : 10;
  $args = array(
    'post_type'      => 'resources',
    'posts_per_page' => $files_data->post_limit,
    'post_status'    => 'any',
    'author'         => $user_id,
  );
  $posts = new WP_Query( $args );
  if( ! $posts->have_posts() ) {
    $files_data->files_left = $files_data->post_limit;
    return $files_data;
  }
  else {
    while ( $posts->have_posts() ) {
      $posts->the_post();
      $files_data->post_count = $posts->post_count;
      $files_data->files_left = $files_data->post_limit - $posts->post_count;
      break;
    }
    return $files_data;
  }
}

