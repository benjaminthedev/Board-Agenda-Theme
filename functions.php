<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );
include_once( dirname( __FILE__ ) . '/lib/pw-profile.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis Sample Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.1.8' );

add_filter('wpseo_enable_xml_sitemap_transient_caching', '__return_false');


//Removes stupid dashboard health crap from WP
add_action('wp_dashboard_setup', 'remove_site_health_dashboard_widget');
function remove_site_health_dashboard_widget(){
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
}


// leaky_paywall here
// add fields to registration form

add_action( 'leaky_paywall_after_password_registration_field', 'zeen101_custom_registration_fields' );
function zeen101_custom_registration_fields() {
  ?>
  	<div class="form-row company">
		<label>Company*</label>
  		<input type="text" value="" placeholder="Company Name" name="company" >
	</div>

 	<div class="form-row role">
    <label>Role*</label>
  		<input type="text" value="" name="role" placeholder="Role" >
	</div>

  <div class="form-row phone">
    <label>Phone Number*</label>
      <input type="tel" value="" name="phonenumber" placeholder="Phone Number" >
  </div>

<div class="billing">
  <div class="form-row">
    <label>Billing Address*</label>
      <input type="textbox" value="" name="address01" placeholder="Billing Address" >
  </div>


  <div class="form-row billingAddress">
    <label>Billing Address </label>
      <input type="textbox" value="" name="address02" placeholder="Billing Address - line 2" >
  </div>

    <div class="form-row postCode">
    <label>Post Code</label>
      <input type="textbox" value="" name="postcodeaddress" placeholder="Post Code">
  </div>

</div>
	
	<div class="checkBoxSection checkBoxAddress">
		<label>
			<input type="checkbox" name="colorCheckbox" value="red"> Post to a different address?
		</label>
	</div>

	<div class="red box">

		<h3>Postal Address Information</h3>

	  	<div class="form-row">
    		<label>Postal Address</label>
    	  	<input type="textbox" value="" name="postal01" placeholder="Postal Address">
  		</div>

	  	<div class="form-row">
    		<label>Postal Address</label>
    	  	<input type="textbox" value="" name="postal02" placeholder="Billing Address - line 2">
  		</div>

		<div class="form-row">
			<label>Post Code</label>
			<input type="textbox" value="" name="postcodepostal" placeholder="Post Code">
		</div>		  
			
	</div>

	<style>

	.form-row {
		width: 98%;
	}
	.checkBoxSection{
		margin-top:20px;
	}
    .box{   
        display: none;
        margin-top: 20px;
    }

	.show-Me{
		display: none;
	}
	</style>

	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
		<script>
		$(document).ready(function(){
			$('input[type="checkbox"]').click(function(){
				var inputValue = $(this).attr("value");
				$("." + inputValue).toggle();
				
				console.log('CheckBox Checked');

				$('billing').toggleClass('show-Me');
			});
		});
		</script>

  <?php

}

// save fields if user is created
add_action( 'leaky_paywall_form_processing', 'zeen101_custom_registration_fields_save', 10, 6 );
function zeen101_custom_registration_fields_save( $post_data, $user_id, $price, $mode, $site, $level_id ) {

  if ( $post_data['company'] ) {
    update_user_meta( $user_id, '_company', sanitize_text_field( $post_data['company'] ) );
  }

  if ( $post_data['role'] ) {
    update_user_meta( $user_id, '_role', sanitize_text_field( $post_data['role'] ) );
  }

  if ( $post_data['phonenumber'] ) {
    update_user_meta( $user_id, '_phonenumber', sanitize_text_field( $post_data['phonenumber'] ) );
  }

  if ( $post_data['address01'] ) {
    update_user_meta( $user_id, '_address01', sanitize_text_field( $post_data['address01'] ) );
  }

  if ( $post_data['address02'] ) {
    update_user_meta( $user_id, '_address02', sanitize_text_field( $post_data['address02'] ) );
  }

  if ( $post_data['postcodeaddress'] ) {
    update_user_meta( $user_id, '_postcodeaddress', sanitize_text_field( $post_data['postcodeaddress'] ) );
  }

  if ( $post_data['postal01'] ) {
    update_user_meta( $user_id, '_postal01', sanitize_text_field( $post_data['postal01'] ) );
  }
  
  if ( $post_data['postal02'] ) {
    update_user_meta( $user_id, '_postal02', sanitize_text_field( $post_data['postal02'] ) );
  }
  
  if ( $post_data['postcodepostal'] ) {
    update_user_meta( $user_id, '_postcodepostal', sanitize_text_field( $post_data['postcodepostal'] ) );
  }  
}

// display the field in the user's WP admin profile
add_action( 'show_user_profile', 'zeen101_admin_custom_fields', 99 );
add_action( 'edit_user_profile', 'zeen101_admin_custom_fields', 99 );

function zeen101_admin_custom_fields( $user ) {

   $company = get_user_meta( $user->ID, '_company', true );
   $role = get_user_meta( $user->ID, '_role', true );
   $phonenumber = get_user_meta( $user->ID, '_phonenumber', true );
   
   $address01 = get_user_meta( $user->ID, '_address01', true );
   $address02 = get_user_meta( $user->ID, '_address02', true );
   $postcodeaddress = get_user_meta( $user->ID, '_postcodeaddress', true );


   $postal01 = get_user_meta( $user->ID, '_postal01', true );
   $postal02 = get_user_meta( $user->ID, '_postal02', true );
   $postcodepostal = get_user_meta( $user->ID, '_postcodepostal', true );
	?>
	<div class="leaky-paywall-account-fields">
  <table class="form-table">

	 <tr>
		<th><label>Company</label></th>
		<td>
			<?php echo $company; ?>
		</td>
	</tr>

	<tr>
		<th><label>Role</label></th>
		<td>
			<?php echo $role; ?>
		</td>
	</tr>

  <tr>
		<th><label>Phone Number</label></th>
		<td>
			<?php echo $phonenumber; ?>
		</td>
	</tr>

  <tr>
    <th><label>Address</label></th>
    <td>
      <?php echo $address01; ?>
    <br />
      <?php echo $address02; ?>
    <br />
      <?php echo $address02; ?>
    </td>
  </tr>

    <tr>
    <th><label>Billing Address</label></th>
    <td>
      <?php echo $postal01; ?>
    <br />
      <?php echo $postal02; ?>
    <br />
      <?php echo $postcodepostal; ?>
    </td>
  </tr>

  </table>
</div>
<?php
}

// add custom field data to Leaky Paywall Reporting Tool export
add_filter( 'leaky_paywall_reporting_tool_meta', 'zeen101_reporting_tool_custom_fields_headers' );

function zeen101_reporting_tool_custom_fields_headers( $meta ) {
  $meta[] = 'company';
  $meta[] = 'role';
  $meta[] = 'phonenumber';
  $meta[] = 'address01';
  $meta[] = 'address02';
  $meta[] = 'postcodeaddress';
  $meta[] = 'postal01';
  $meta[] = 'postal02';
  $meta[] = 'postcodepostal';

  //$meta[] = 'newsletter_signup';
	return $meta;
}

add_filter( 'leaky_paywall_reporting_tool_user_meta', 'zeen101_reporting_tool_custom_fields_values', 10, 2 );

function zeen101_reporting_tool_custom_fields_values( $user_meta, $user_id ) {
  $company = get_user_meta( $user_id, '_company', true );
  $role = get_user_meta( $user_id, '_role', true );
  $phonenumber = get_user_meta( $user_id, '_phonenumber', true );
  $address01 = get_user_meta( $user_id, '_address01', true );
  $address02 = get_user_meta( $user_id, '_address02', true );
  $postcodeaddress = get_user_meta( $user_id, '_postcodeaddress', true );


  	$postal01 = get_user_meta( $user_id, '_postal01', true );
	$postal02 = get_user_meta( $user_id, '_postal02', true );   	
	$postcodeaddress = get_user_meta( $user_id, '_postcodepostal', true );
	//$newsletter = get_user_meta( $user_id, '_newsletter_subscribe', true );


  $user_meta[$user_id]['company'] = $company;
  $user_meta[$user_id]['role'] = $role;
  $user_meta[$user_id]['phonenumber'] = $phonenumber;
  $user_meta[$user_id]['address01'] = $address01;
  $user_meta[$user_id]['address02'] = $address02;
  $user_meta[$user_id]['postcodeaddress'] = $postcodeaddress;


  $user_meta[$user_id]['postal01'] = $postal01;
  $user_meta[$user_id]['postal02'] = $postal02;
  $user_meta[$user_id]['postcodepostal'] = $postcodepostal;
	//$user_meta[$user_id]['newsletter_signup'] = $newsletter;
	return $user_meta;
}


// end leaky_paywall here




//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'boardagenda_enqueue_scripts' );
function boardagenda_enqueue_scripts() {
	// In production environment load the minified version of the files

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );

	//Ajax login
	wp_register_script('boardagenda-script', get_bloginfo( 'stylesheet_directory' ) . '/js/script.js', array('jquery'), '1.2', true );
	wp_localize_script( 'boardagenda-script', 'ajax_login_object', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirecturl' => home_url(),
			'loadingmessage' => __('Sending user info, please wait...'),
			'showafter' => get_field( 'show_popup_after_seconds', 'option' ),
	));

	wp_enqueue_script('boardagenda-script');

	//* Enque Fontawesome Icons Style
  wp_enqueue_style( 'fontawesome-standard', get_stylesheet_directory_uri() . "/css/font-awesome.min.css");
  wp_enqueue_style( 'bx-slider-css', get_stylesheet_directory_uri() . "/css/jquery.bxslider.min.css");


	//Not logged in and need to register script   
	//User LogIn JS
	if (is_page(9813)) {
		wp_enqueue_script( 'userlogin', get_stylesheet_directory_uri() . '/js/registerScript.js', array(), '', true );
	}	

	// Execute the action only if the user isn't logged in
	board_agenda_ajax_login_init();
}


//adding thumbnail support
function add_featured_image_support(){
	add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'add_featured_image_support' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'site-inner',
	'footer-widgets',
	'footer'
) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );


//****************************************************************************************************
// HEADER BEFORE TOP DIV
//*****************************************************************************************************

add_action( 'genesis_before_header', 'board_agenda_header_wrap' );


function board_agenda_header_wrap() { ?>

	<div class="header-before-wrap">
		<div class="wrap">
			<div class="floatleft toplinks">
				<p class="today-date"><?php echo date("j F, Y")?></p>
				<a href="<?php echo esc_url( home_url() ); ?>/subscriptions/" class="toplink-sub">Subscribe</a>
				<a href="<?php echo esc_url( home_url() ); ?>/advertise" class="toplink-add">Advertise</a>
				<a href="<?php echo esc_url( home_url() ); ?>/about" class="toplink-abo">About Us</a>
			</div>
			<div class="floatright">


				<ul>
				<?php if( ba_is_client_user() ) : $profile = ba_get_my_profile(); ?>

					<li><a href="https://boardagenda.com/my-account/"><i class="fa fa-plus-circle icon-color"></i>Edit company profile</a></li>

					<!-- <li><a href="<?php //echo esc_url( ba_get_my_profile_edit_link() ) ?>"><i class="fa fa-plus-circle icon-color"></i>Edit company profile</a></li> -->
				<?php endif; ?>
					<li>
												<?php
						// register - profile link
						$base_url = home_url();

						if ( ! is_user_logged_in() ) {
							$base_url 	.= '/register/?level_id=0';
							$text 		= 'Register';
						} else {

							if ( ba_is_resource_partner() || ba_is_client_user() ) {
								$base_url = '';
							} else {
								$base_url 	= '/my-account/';
								$text 		= 'My Account';
							}
						}
						if ( ! empty( $base_url ) ) : ?>
							<a href="<?php echo esc_url( $base_url ); ?>">
								<i class="fa fa-plus-circle icon-color"></i>
								<?php echo $text; ?>
							</a>
						<?php endif; ?>
					</li>
					<li>
						<a href="<?php echo is_user_logged_in() ? wp_logout_url( get_the_permalink() ) : '#' ?>" class="<?php echo is_user_logged_in() ? '' : 'log-me-in'?>">
							<i class="fa fa-lock icon-color"></i>
							<?php echo is_user_logged_in() ? 'Log out' : 'Log In'; ?>
						</a>
					</li>
                    <li>
						<div class="newTopSearch">
							<?php //echo do_shortcode('[ivory-search id="23733" title="Top Bar Menu"]'); ?>
						</div>						
                    </li>
            </ul>
			</div>
		</div>
	</div>

<?php }

//*****************************************************************************************************

							// HEADER

//*****************************************************************************************************

//* Remove the site title
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
//* Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Add logo to the header
add_action('genesis_header', 'genesis_user_header');

function genesis_user_header() { ?>

      <h1 class="mir logo"><a href="<?php bloginfo('url'); ?>/"></a></h1>

        <ul class="board-site-title">
            <li>Governance</li>
            <li>Strategy</li>
            <li>Risk</li>
            <li>Ethics</li>
        </ul>


<?php }
add_action( 'genesis_after_header', 'genesis_do_nav' );

function styzer_header_right_content () {

	//TODO : Change the image for three images. One of them is coming from a option ACF
	$magazine_image = get_field( 'magazine_cover_image' , 'option');

	?>
    <div class="header-right">
			<a href="<?php echo home_url('/subscriptions/'); ?>">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/backgound.png" class="tablets">
				<img src="<?php echo $magazine_image['url']; ?>" class="magazine_image">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/forground.png" class="subs_circle">
				<!--<img src="<?php //echo get_stylesheet_directory_uri(); ?>/images/subscribe-today.png">-->
			</a>
		</div>
<?php }

function styzer_header_right_content_old () { ?>
    <div class="header-right">
        <?php
        // query
        $the_query = new WP_Query('post_type=magazine_issues&posts_per_page=1');

        ?>
        <?php if( $the_query->have_posts() ): ?>
        <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
    	    <div class="magazine-issues-left">
        	    <span class="clearfix magazine-title"><?php the_field('title'); ?></span>
        	    <div>
								<!-- <span class="magazine-meta magazine-date"><?php the_field("date"); ?></span>  -->
								<span class="magazine-meta"><?php the_field("issue_number"); ?></span></div>

							<?php //if( ! is_user_logged_in() ) : ?>
        	    <p class="magazine-link"><a href="<?php the_field("magazine_page_link"); ?>"><?php the_field("magazine_link_title"); ?></a></p>
							<?php //endif; ?>
    	    </div>
    	    <div class="magazine-issues-right">
                <div style="background:url(<?php the_field("magazine_image"); ?>) top center no-repeat;  width:69px; height:98px;"></div>
                <div style="background:url(http://boardagenda.wpengine.com/wp-content/themes/board-agenda/images/magazine-ipad.png) bottom center no-repeat;  width:46px; height:66px; position:relative; margin:-59px 0 0 43px;"><img style="width:40px; max-height:54px; margin:6px 3px 0 0;" src="<?php the_field("magazine_image"); ?>"/></div>
    	    </div>
            <?php
							endwhile;

						wp_reset_query();  // Restore global post data stomped by the_post().
					endif;
				?>
    </div>
    <?php }
    add_action('genesis_header_right', 'styzer_header_right_content', 5 );




//******************************************************************************************************

							//CUSTOM POST TYPE HEADER RIGHT MAGAZINE

//******************************************************************************************************



add_action( 'init', 'board_custom_post_type_header_magazine' );

function board_custom_post_type_header_magazine() {
   $labels = array(
    'name' => __( 'Magazine Issues' ),
    'singular_name' => __( 'Magazine Issues' )
    );

    $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'magazineissues'),
    );

  register_post_type( 'magazine_issues', $args);

	//Taxonomies to be added
	$tax = array(
		array(
			'id' => 'resources-categories',
			'name' => 'resource',
			'label' => 'Resource type',
			'slug' => 'resources'
		),
		array(
			'id' => 'partner-categories',
			'name' => 'partners',
			'label' => 'Partner category',
			'slug' => 'partners',
		),
		array(
			'id' => 'event-categories',
			'name' => 'events',
			'label' => 'Event category',
			'slug' => 'events'
		),
		array(
			'id' => 'education-categories',
			'name' => 'educations',
			'label' => 'Education category',
			'slug' => 'educations'
		),
	);

	foreach( $tax as $t ) {
		//Resources
		register_taxonomy(
				$t['id'],
				array( $t['name'] ),
				array(
					'label' => $t['label'],
					'hierarchical' => true,
					'description' => true
				)
			);
	 }

	 //Custom post types.

	 $cpt = array(
		 array(
			 'id' => 'resources',
			 'name' => 'Resources',
			 'singular' => 'Resource',
			 'capability_type' => 'post',
			 'taxonomies' => array( 'resources_categories', 'post_tag' ),
			 'supports' => array( 'title', 'editor', 'author', 'revisions' ),
			 'slug' => 'resource',
			 'capability_type' => array('client_user_cap', 'client_user_caps'),
			 'map_meta_cap' => true,
			 'has_archive' => false,
		 ),
		 array(
			 'id' => 'partners',
			 'name' => 'Partners',
			 'singular' => 'Partner',
			 'edit' => 'Profile',
			 // 'capability_type' => 'post',
			 'taxonomies' => array( 'partner_categories', 'az-cateogories' ),
			 'slug' => 'partner',
			 'capability_type' => array('resource_partner_cap', 'resource_partner_caps'),
			 'map_meta_cap' => true,
			 'icon' => 'dashicons-welcome-widgets-menus',
		 ),
		 array(
			 'id' => 'events',
			 'name' => 'Events',
			 'singular' => 'Event',
			 'capability_type' => 'post',
			 'taxonomies' => array( 'event-categories', 'category' ),
			 'slug' => 'event',
       'has_archive' => 'events'
		 ),
		 array(
			 'id' => 'education',
			 'name' => 'Education',
			 'singular' => 'Education',
			 'capability_type' => 'post',
			 'taxonomies' => array( 'education-categories', 'category' ),
			 'slug' => 'education',
		 ),
		 array(
			 'id' => 'mail',
			 'name' => 'Mail Templates',
			 'singular' => 'Mail Template',
			 'capability_type' => 'post',
			//  'taxonomies' => array( 'education-categories' ),
			 'slug' => 'mail-templates',
			 'public' => false,
			 'exclude_from_search' => true,
		 ),
		 array(
			 'id' => 'notifications',
			 'name' => 'Notifications',
			 'capability_type' => 'post',
			 'slug' => 'notifications',
			 'public' => false,
			 'exclude_from_search' => true,
		 ),
		 array(
			 'id' => 'offers',
			 'name' => 'Promotional offer',
			 'capability_type' => 'post',
			 'slug' => 'offer',
			 'public' => false,
			 'exclude_from_search' => true,
		 ),
	 );

	 $GLOBALS['_ba_cpt'] = $cpt;
	 foreach( $cpt as $c ) {
		 $edit = isset($c['edit']) ? 'Edit your ' . $c['edit'] : 'Edit ' . @$c['singular'];
		 $add_new = isset($c['edit']) ? 'Create your ' . $c['edit'] : 'Create new ' . @$c['singular'];
			$args = array(
				'labels' => array( 'name' => $c['name'], 'singular_name' => @$c['singular'], 'edit_item' => $edit, 'add_new' => $add_new, 'add_new_item' => $add_new, 'new_item' => $add_new),
				'public' => isset( $c['public'] ) ? $c['public'] : true,
				'exclude_from_search' => isset( $c['exclude_from_search'] ) ? $c['exclude_from_search'] : false,
				'has_archive' => isset( $c['has_archive'] ) ? $c['has_archive'] : false,
				'capability_type' => $c['capability_type'],
				'supports' => array( 'title', 'editor', 'excerpt', 'author', 'revisions' ),
				'rewrite' => array( 'slug' => $c['slug'] ),
				'show_ui' => true,
				'menu_icon' => @$c['icon'],
			);

			if( isset( $c['taxonomies'] ) ) {
				$args['taxonomies'] = $c['taxonomies'];
			}

			if ( $c['id'] != 'resources' ) {
				$args['supports'][] = 'thumbnail';
			}

			register_post_type( $c['id'], $args);
	  }

		//Top FAQs searches
		if( function_exists('acf_add_options_page') ) {
			$page = acf_add_options_page(array(
				'page_title' 	=> 'Board Agenda',
				'menu_title'	=> 'Board Agenda',
				'menu_slug' 	=> 'boardagenda',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			));

      acf_add_options_sub_page(array(
    		'page_title' 	=> 'Subscriptions',
    		'menu_title' 	=> 'Subsciptions',
    		'parent_slug' 	=> $page['menu_slug'],
    	));

      acf_add_options_sub_page(array(
    		'page_title' 	=> 'Popups',
    		'menu_title' 	=> 'Popups',
    		'parent_slug' 	=> $page['menu_slug'],
    	));
		}
}
//Add a "settings" page for each custom post type
function board_agenda_add_cpt_description() {
	$cpt = $GLOBALS['_ba_cpt'];
	foreach( $cpt as $c ) {
		if( @$c['public'] === false ) continue;

		add_submenu_page('edit.php?post_type=' . $c['id'], 'Description', 'Description', 'manage_options', 'baedit-cpt-' . $c['id'], 'board_agenda_edit_cpt_description' );
	}
}
add_action( 'admin_menu', 'board_agenda_add_cpt_description' );

/** Allow to manage the custom post type description */
function board_agenda_edit_cpt_description() {
	require_once( 'admin-cpt-description.php' );
}

//*****************************************************************************************************

							// THUMBNAILS

//*****************************************************************************************************


add_theme_support( 'post-thumbnails' );


if ( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 60, 60, true ); // default Post Thumbnail dimensions (cropped)

  // additional image sizes
  // delete the next line if you do not need additional image sizes
  add_image_size( 'category-thumb', 145, 83, true );
  add_image_size( 'latest-news-thumb', 80, 80, true );
  add_image_size( 'mega-menu-thumb', 290, 156, true );
  add_image_size( 'mega-menu-resource-thumb', 290, 156, array('center', 'top') );
  add_image_size( 'mega-menu-resource-centered', 290, 156, array('center', 'center') );
  add_image_size( 'insights-front-thumb', 300, 180, true );
  add_image_size( 'latest-two-stories', 340, 190, true );
  add_image_size( 'latest-news-top', 200, 120, true );
  add_image_size( 'partner-logo', 300, 300, true );
  add_image_size( 'single-resource', 300, 600, true );
  add_image_size( 'featured-resource', 75, 150, true );
  add_image_size( 'offer-image', 250, 160, true );
}





//*****************************************************************************************************

							// WIDGETS

//*****************************************************************************************************

add_filter('widget_text', 'do_shortcode');

//*******************************************************************************************************

                                        //EXCERPT

//*******************************************************************************************************



function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Create the Custom Excerpts callback
function wpden_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom Length
function news_analysis($length) {
    return 18;
}

function latest_news($length) {
    return 34;
}

function opinion_len($length) {
    return 15;
}

function news_feed($length) {
    return 30;
}

function default_len($length) {
    return 16;
}

//*******************************************************************************************************

                                        //PAGINATION

//*******************************************************************************************************

//* Customize the previous page link
add_filter ( 'genesis_prev_link_text' , 'sp_previous_page_link' );
function sp_previous_page_link ( $text ) {
    return '&#x000AB; Previous Page';
}

//* Customize the next page link
add_filter ( 'genesis_next_link_text' , 'sp_next_page_link' );
function sp_next_page_link ( $text ) {
    return 'Next Page &#x000BB;';
}

//*******************************************************************************************************

                                        //SINGLE POST RELATED POST

//*******************************************************************************************************

add_image_size( 'related', 100, 100, true );

//for XHTML themes
add_action( 'genesis_after_post_content', 'child_related_posts' );
//for HTML5 themes
add_action( 'genesis_after_entry_content', 'child_related_posts',2 );
/**
* Outputs related posts with thumbnail
*
* @author Syed Bavajan
* @url http://mygenesisthemes.com/related-posts-genesis
* @global object $post
*/
function child_related_posts() {

    if ( is_single ( ) ) {

        global $post;

        $count = 0;
        $postIDs = array( $post->ID );
        $related = '';
        $tags = wp_get_post_tags( $post->ID );
        $cats = wp_get_post_categories( $post->ID );

        if ( $tags ) {

            foreach ( $tags as $tag ) {

                $tagID[] = $tag->term_id;

            }

            $args = array(
                'tag__in' => $tagID,
                'post__not_in' => $postIDs,
                'showposts' => 3,
                'ignore_sticky_posts' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => array(
                            'post-format-link',
                            'post-format-status',
                            'post-format-aside',
                            'post-format-quote'
                        ),
                        'operator' => 'NOT IN'
                    )
                )
            );

            $tag_query = new WP_Query( $args );

            if ( $tag_query->have_posts() ) {

                while ( $tag_query->have_posts() ) {

                    $tag_query->the_post();

                    $img = genesis_get_image() ? genesis_get_image( array( 'size' => 'related' ) ) : '<img src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/related.png" alt="' . get_the_title() . '" />';

                    $related .= '<li><a href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to' . get_the_title() . '">' . $img . get_the_title() . '</a></li>';

                    $postIDs[] = $post->ID;

                    $count++;
                }
            }
        }

        if ( $count <= 4 ) {

             $catIDs = array( );

                 foreach ( $cats as $cat ) {

                 if ( 3 == $cat )
                 continue;
                 $catIDs[] = $cat;

             }
             ?>
             <div class="related-posts">

             <h3 class="related-title">Related Stories</h3>

             <?php

             $showposts = 3 - $count;

             $args = array(
                 'category__in' => $catIDs,
                'post__not_in' => $postIDs,
                'showposts' => 3,
                'ignore_sticky_posts' => 1,
                'orderby' => 'rand',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => array(
                            'post-format-link',
                            'post-format-status',
                            'post-format-aside',
                            'post-format-quote'
                        ),
                        'operator' => 'NOT IN'
                    )
                )
            );

            $cat_query = new WP_Query( $args );

            if ( $cat_query->have_posts() ) {

                while ( $cat_query->have_posts() ) {

                    $cat_query->the_post();
                    $category = get_the_category();
                    $firstCategory = $category[0]->cat_name;


                  //  $related .= '<li><a href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to' . get_the_title() . '">' . $img . get_the_title() . the_excerpt() . '</a></li>';
                    ?>
                        <ul class="related-list latest-news-wrap clearfix">
                            <li>

                            <?php  $img = genesis_get_image() ? genesis_get_image( array( 'size' => 'related' ) ) :
                                    '<img src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/related.png" class="attachment-latest-news-thumb wp-post-image" alt="' . get_the_title() . '" />';
                                    //var_dump($img);
                                    echo $img;
                            ?>
                            <p class="related-category"><a href="<?php echo get_site_url(); ?>/<?php echo $firstCategory; ?>/"><?php echo $firstCategory; ?></a></p>
                            <h4 class="related-post-title"> <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="front-latest-excerpt"><?php if( $field = get_field('single_post_subheading') ): ?>
            <?php echo $field; ?>
            <?php endif; ?></span>

                            </li>
                        </ul>
                    <?php
                }
            }
            ?>  </div> <?php
        }

        // if ( $related ) {

        //  printf( '<div class="related-posts"><h3 class="related-title">Related Stories</h3><ul class="related-list">%s</ul></div>', $related );

        // }

        wp_reset_query();

    }
}

/*===================================================================================
* Single post page shows category title
*==================================================================================== */

add_filter('genesis_before_loop','add_category_to_single');
function add_category_to_single($classes, $class = '') {
    if (is_single() && ! is_singular('resources') ) {
         $categories = get_the_category();
         foreach($categories as $category) {
            $cat_name = $category->name;
						if( strtolower( $cat_name ) == 'uncategorized' ) continue;

                echo '<button class="post-category"><a href="'.get_category_link($category->term_id).'" style="color:#fff;font-weight:800;font-size:13px;">'.$cat_name . '</a></button>'
                    ;

            }
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

      //  Single post page tags
    add_action( 'genesis_after_entry_content', 'boardA_single_news_custom_tag',1 );

    //  Single post excerpt
    add_action( 'genesis_before_entry_content', 'add_single_post_excerpt');

    //* Customize the post info function
    add_filter( 'genesis_post_info', 'sp_post_info_filter' );


    }
}

/*===================================================================================
* Force sidebar -> content layout for the archive pages
*==================================================================================== */
//Show a custom description in archive page
function ba_page_title() {
	global $wp_query;

	//When the archive is empty, the function return me 'post'... Why?
	// $type = get_post_type();

	$type = $wp_query->query['post_type'];
?>
	<div class="archive-wrap">
		<h1 class="front-title"><?php post_type_archive_title( '' ) ?></h1>
		<div class="clearfix archive-intro">
			<?php echo wpautop( get_option( 'ba_cpt_description_' . $type ) ); ?>
		</div>
		<div class="social">
			<ul>
				<li><a href="https://twitter.com/BoardAgenda" class="fa fa-twitter"></a></li>
				<li><a href="https://www.linkedin.com/groups/8307526" class="fa fa-linkedin"></a></li>
				<li><a href="#" class="fa fa-youtube"></a></li>
				<li><a href="#" class="fa fa-google-plus"></a></li>
				<li><a href="#" class="fa fa-envelope"></a></li>
			</ul>
		</div>
	</div>
<?php }

add_action( 'get_header', 'board_agenda_set_layout' );
function board_agenda_set_layout() {
	if ( ! is_archive() || isset( $GLOBALS['is_archive'] ) ) {
		return;
	}

	//description above the page content
	add_action( 'genesis_before_content_sidebar_wrap', 'ba_page_title' );

	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
	add_action( 'genesis_sidebar', 'board_agenda_search_sidebar' );


	// Change the layout
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content' );
}

//Show the custom sidebar
function board_agenda_search_sidebar() {
	global $wp_query;
	//standard archive template
	if( isset( $GLOBALS['is_archive'] ) ) return;

	$type = isset( $_GET['type'] ) ? ( sanitize_title( $_GET['type'] ) . 's' ) : $wp_query->query['post_type'];

	//Get the sidebar
	$sidebar = get_option( 'ba_cpt_sidebar_' . $type );

	if( $sidebar ) {
		dynamic_sidebar( 'search-' . $sidebar );
	}
}



/**
 * FEATURED PARTNER RESOURCES shortcode
 */
function board_agenda_partner_resources() {
	global $featured, $size, $limit;

	$size = 'featured-resource';
	$limit = 50;
	ob_start();
	while( have_rows( 'featured_resources', 'option' ) ) : the_row(); $featured = get_sub_field( 'resource' );
		get_template_part( 'template-parts/featured', 'presources' );
	endwhile;

	$content = ob_get_contents();
	ob_end_clean();

	return '<div class="featured-resources featured-content featuredpost">' . $content . '</div>';
}

function board_agenda_featured_partner() {
	global $featured, $size, $limit;

	$featured = get_field( 'featured_partner', 'option' );
	$size = 'full';
	$limit = 250;

	ob_start();
 	get_template_part( 'template-parts/featured', 'presources' );

	$content = ob_get_contents();
	ob_end_clean();

	return '<div class="featured-content featuredpost">' . $content . '</div>';
}

add_shortcode( 'partner_resources', 'board_agenda_partner_resources' );
add_shortcode( 'featured_partner', 'board_agenda_featured_partner' );

/*===================================================================================
* RESOURCES "Restrictions" utility
*==================================================================================== */
function acf_load_restriction_field_choices( $field ) {
	global $nine3_Membership;

	// reset choices
  $field['choices'] = array();
  $field['choices']['-1'] = 'None';

	$levels = $nine3_Membership->get_subscriptions();
	foreach( $levels as $id => $level ) {
			$field['choices'][ $id ] = $level['label'];
	}

	$field['default_value'] = 0;

  // return the field
  return $field;
}

if( is_admin() )
	add_filter('acf/load_field/name=restriction', 'acf_load_restriction_field_choices');


//Registration benefits-list
function ba_register_list( $attr ) {
	$key = $attr['id'] == 0 ? 'free' : 'subscribe';
?>
	<ul class="register-list form-list">
		<?php while( have_rows( $key, 768 ) ) : the_row(); ?>
		<li><?php the_sub_field( 'item', 768 ); ?></li>
		<?php endwhile; ?>
	</ul>
<?php
}

add_shortcode( 'ba_register_list', 'ba_register_list' );

/*===================================================================================
* Single post excerpt
*==================================================================================== */

function add_single_post_excerpt(){
	if( is_singular( 'job_listing' ) ) return;
?>

   <div class="single-post-subheading clearfix">
      <?php the_excerpt(); ?>
     </div>

<div class="single-top-image clearfix">
     <?php

$image = get_field('top_image');

if( !empty($image) ):

	// vars
	$url = $image['url'];
	$title = $image['title'];
	$alt = $image['alt'];
	$caption = $image['caption'];

	// thumbnail
	$size = 'thumbnail';
	$thumb = $image['sizes'][ $size ];
	$width = $image['sizes'][ $size . '-width' ];
	$height = $image['sizes'][ $size . '-height' ];

	if( $caption ): ?>

		<div class="wp-caption">

	<?php endif; ?>



		<img src="<?php echo $url; ?>" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />



	<?php if( $caption ): ?>

			<p class="wp-caption-text"><?php echo $caption; ?></p>

		</div>

	<?php endif; ?>

<?php endif; ?>



     </div>




<?php }

/*===================================================================================
* Single post page shows tags after post content
*==================================================================================== */

function boardA_single_news_custom_tag() { ?>

    <p class="tags"><?php echo the_tags('<i class="fa fa-tags" style="margin:0 4px 0 0;"></i> '); ?></p>

<?php }


/*===================================================================================
 * shortcodes
 * =================================================================================*/


function pullleft( $atts, $content = null ) {
    return '<div class="one-fourth " style="font-family:avenir-black;font-size:18px; margin-right:30px;border-right: 2px solid #B7C9D1;
    padding: 0 18px 0 0; margin:0 20px 20px 0;float:left; line-height:140%;">'.$content.'</div>';
}

add_shortcode('pull-left', 'pullleft');

function pullright( $atts, $content = null ) {
    return '<div class="one-fourth " style="font-family:avenir-black;font-size:16px; margin-left:30px;border-left: 2px solid #B7C9D1;
    padding: 0 0 0 18px; margin:0 0 20px 20px;float:right;line-height:140%;">'.$content.'</div>';
}

add_shortcode('pull-right', 'pullright');

//*******************************************************************************************************

                                        //AUTHORS

//*******************************************************************************************************

//* Author avatar
add_action( 'genesis_entry_header', 'remove_job_title', 0 );
function remove_job_title() {
	if( is_singular( 'job_listing' ) ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	}
}

add_action( 'genesis_entry_header', 'cd_author_gravatar' );
function cd_author_gravatar() {

if ( is_singular( 'post' ) ) {

if ( function_exists( 'coauthors_posts_links' ) ) {
    $coauthors = get_coauthors();
 foreach( $coauthors as $coauthor ):
    $userdata = get_userdata( $coauthor->ID );
    $author_id = get_the_author_meta($userdata->ID);
    $author_image = get_field('author_image', 'user_'. $userdata->ID );
    if($author_image !=""){
        ?>
         <img class="singleauthor-img clearfix" src="<?php echo $author_image; ?>" >
        <?php }

endforeach; ?>

	<p class="post-author sponsor-h2">
<span class="lowercase">by</span>
 <?php

 /**
  * Sponsor data
  */
 $post_id = get_queried_object_id();
 $sponsor_text = get_field( 'sponsor_text', $post_id );
 $sponsor_link = get_field( 'sponsor_link', $post_id );
 $sponsor_logo = get_field( 'sponsor_logo', $post_id );



 $partner = get_field( 'partner' );
 if( $partner ) {
	 echo '<a href="' . get_the_permalink( $partner->ID ) . '" title="' . $partner->post_title . '" class="url fn" rel="author">' . $partner->post_title . '</a>';
 } else {
	 coauthors_posts_links();
 }
 ?>
<?php
} else {
    the_author_posts_link();
}
  ?>

<?php if( ! has_category( 'insights' ) ) :  ?>
	<span class="lowercase"> on</span>
	<?php echo get_the_date(); ?>
<?php endif; //has_author ?>

 <?php if( get_field( 'sponsored' ) ) : ?>
	 <img src="<?php $image = get_field('the_featured_post_image', $partner->ID); echo $image['url']; ?>" alt="<?php echo $partner->post_title ?>" class="sponsored-logo">
	 <small class="sponsored">SPONSORED</small>
 <?php endif; ?>

 <?php
    		 if( $sponsor_text !== NULL && $sponsor_text !== ''
          && $sponsor_logo !== NULL && $sponsor_logo !== '' ):
        ?>
             	<a href="<?php echo $sponsor_link; ?>" title="Open content in a new tab"
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $sponsor_text; ?>
             		<img src="<?php echo $sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php
         endif;
    		?>
 </p>

<?php
// $entry_author = get_avatar( get_the_author_meta( 'author_image' ), 64 );
// $author_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
// printf( '<div class="author-avatar"><a href="%s">%s</a></div>', $author_link, $entry_author );
}
}

// create description excerpt


	function author_excerpt (){
		$word_limit = 20; // Limit the number of words
		$more_txt = 'read more about:'; // The read more text
		$txt_end = '...'; // Display text end
		$authorName = get_the_author();
		$authorUrl = get_author_posts_url( get_the_author_meta('ID'));
		$authorDescriptionShort = wp_trim_words(strip_tags(get_the_author_meta('description')), $word_limit, $txt_end.'<br /> '.$more_txt.' <a href="'.$authorUrl.'">'.$authorName.'</a>');
		return $authorDescriptionShort;
	}


/*===================================================================================
 * Add Author Links
 * =================================================================================*/
function add_to_author_profile( $contactmethods ) {

	$contactmethods['linkedin_profile'] = 'Linkedin Profile URL';
	$contactmethods['twitter_profile'] = 'Twitter Profile URL';
  $contactmethods['author_email'] = 'Display Email';

	return $contactmethods;
}
add_filter( 'user_contactmethods', 'add_to_author_profile', 10, 1);

/** Modify the author box title */
add_filter('genesis_author_box_title', 'graceful_author_box_title');
function graceful_author_box_title($title) {
    $title = sprintf( '<strong>%s</strong>', get_the_author() );
    return $title;
}

/** Change Avatar size */
add_filter( 'genesis_comment_list_args', 'child_comment_list_args' );

function child_comment_list_args( $args ) {
	return array( 'type' => 'comment', 'avatar_size' => 42, 'callback' => 'genesis_comment_callback' );
}


//******************************************************************************************************

                                        //FOOTER


//******************************************************************************************************

//* Customize the entire footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
    ?>
    <p class="web-credit copyright">&#169; <?php echo date("Y"); echo " "; bloginfo('name'); ?></p>
    <?php
}

function board_agenda_network_twitter_widget() {
    register_sidebar( array(
        'name' => 'Network: Twitter feed',
        'id' => 'network-twitter',
        'description' => 'Twitter feed.',
        // 'before_widget' => '<li id="%1$s" class="widget %2$s">',
				// 'after_widget'  => '</li>',
				// 'before_title'  => '<h2 class="widgettitle">',
				// 'after_title'   => '</h2>',
    ) );

    genesis_register_sidebar( array(
        'name' => 'Search 1st',
        'id' => 'search-1',
        'description' => '1st search sidebar',
    ) );

    genesis_register_sidebar( array(
        'name' => 'Search 2nd',
        'id' => 'search-2',
        'description' => '2nd search sidebar',
    ) );

		genesis_register_sidebar( array(
			'id' => 'register-subscribe-sidebar',
			'name' => 'Registration & Subscription sidebar',
			'description' => 'Sidebar for register-subscribe page template.',
			'class' => 'register-subscribe-sidebar'
		) );
}
add_action( 'widgets_init', 'board_agenda_network_twitter_widget' );

//*******************************************************************************************************
// POLL
//*******************************************************************************************************
/**
 * Replace the poll header/footer custom variables with their values
 */
function board_agenda_poll_result( $poll_ID, $content ) {
	global $wpdb;

	$date = $wpdb->get_var("SELECT pollq_timestamp FROM $wpdb->pollsq WHERE pollq_id = " . intval( $poll_ID ) );

	$poll_start_date = mysql2date('d.m.Y', gmdate('Y-m-d H:i:s', $date));
	$content = str_replace("%POLL_DATE%", $poll_start_date, $content);

	list( $partner_name, $partner_thumbnail ) = board_agenda_poll_get_partner( $poll_ID );
	$content = str_replace( '%PARTNER_AUTHOR%', $partner_name, $content );
	$content = str_replace( '%PARTNER_THUMBNAIL%', $partner_thumbnail, $content );

	if( empty( $partner_name ) ) {
		$content = str_replace( '="sponsor"', '="sponsor hidden"', $content );
	}

	return $content;
}

/**
 * This function return the partner info associated at the poll.
 * Is used by board_agenda_poll_result and by the network.php
 */
function board_agenda_poll_get_partner( $id ) {
	$partner_name = '';
	$partner_thumbnail = '';
	$partner = get_option( 'ba_poll_partner_' . $id, -1 );

	if( $partner > -1 ) {
		$partner = get_post( $partner );

		$partner_name = '<a href="' . get_permalink( $partner->ID ) . '">' . $partner->post_title . '</a>';
		$partner_thumbnail = get_the_post_thumbnail( $partner->ID );
	}

	return array( $partner_name, $partner_thumbnail );
}
/**
 * Add the Partner field in the Add/Edit poll page.
 * The plugin doesn't provide any filter/action that I can
 * use to inject the code...
 * So I'm gonna to add it to into the 'notice area', and after
 * move into the form using a script.
 */
function board_agenda_poll_partner() {
	$page = @$_GET['page'];
	if( 'wp-polls/polls-manager.php' !== $page || ! isset( $_GET['id'] ) ) return;
	?>
	<script>
		jQuery( document ).ready( function( $ ) {
			$( '.wrap h2' ).after( $( '.poll-partner' ) );
			$( '.poll-partner' ).show();
		});
	</script>
	<div class="poll-partner" style="display: none">
		<h3>Partner</h3>
		<table class="form-table">
				<thead>
					<tr>
						<th width="20%" scope="row" valign="top">	<label for="poll-partner">Partner:</label></th>
						<td width="80%">
<select name="poll-partner" id="poll-partner" >
	<option value="-1">Not sponsored</option>
	<?php

	//Get the list partners list
	$args = array(
		'post_type' => 'partners',
		'posts_per_page' => -1
  );

	$id = intval( $_GET['id'] );

	$checked = get_option( 'ba_poll_partner_' . $id, -1 );
	$posts = get_posts( $args );
	foreach( $posts as $p ) {
		$selected = selected( $checked, $p->ID, false );
		echo '<option value="' . $p->ID . '" ' . $selected . '>' . $p->post_title . '</option>';
	}
	echo '</select></td></tr></table></div>';
}

add_action( 'admin_footer', 'board_agenda_poll_partner' );

/**
 * Store the partner name for the poll
 *
 */
function board_agenda_poll_partner_save() {
	$id = intval( $_GET['id'] );

	update_option( 'ba_poll_partner_' . $id, intval( $_POST['poll-partner'] ) );
}

add_action( 'wp_polls_update_poll', 'board_agenda_poll_partner_save' );

//*******************************************************************************************************
		  //Filter search result
//*******************************************************************************************************
/**
 * When "filtering" by letter, need to edit the where clausule, as can't
 * do that directly in the pre_get_posts hook.
 *
 */
if(isset($_GET['letter']) && !empty($_GET['letter'])) {
	add_filter( 'posts_where', 'ba_filter_by_letter', 10, 2 );
}

function ba_filter_by_letter( $where, &$wp_query ) {
	global $wpdb;

	if(stripos($where, 'featured') === false) return $where;

	$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $_GET['letter'] ) ) . '%\'';

	return $where;
}

//*******************************************************************************************************
//Register the user
//*******************************************************************************************************

function board_angeda_category_column() {
	$all = array( 'resources', 'partners' );

	foreach ($all as $type ) {
		add_filter( "manage_{$type}_posts_columns", 'board_agenda_partner_column_title', 0 );
		add_action( "manage_{$type}_posts_custom_column", 'board_agenda_partner_column_content', 10, 2 );
	}
}

function board_agenda_partner_column_title( $columns ) {
	$columns['category'] = 'Category';

	if( $_GET['post_type'] == 'resources' ) {
		$columns['partner'] = 'Partner';
	}

	return $columns;
}

function board_agenda_partner_column_content( $column_name, $id ) {
	if( $column_name == 'category' ) {
		$type = ( $_GET['post_type'] == 'resources' ) ? 'resources-categories' : 'partner-categories';
		$terms = wp_get_post_terms( $id, $type );

		if( ! is_wp_error( $terms ) ) echo $terms[0]->name;
	} else if( $column_name == 'partner' ) {
		$partner = get_field( 'partner', $id );

		echo $partner->post_title;
	}

}

/**
 * Replace the #home menu item with a Fontawesome home icon
 *
 */
function board_agenda_home_menu_item( $items ) {
	$new = array();

	foreach( $items as $item ) {
		if( $item->url == '#home' ) {
			$item->url = home_url();
			$item->title = '<span class="fa fa-home"></span>';
		}

		$new[] = $item;
	}

	return $new;
}
add_filter( 'wp_nav_menu_objects', 'board_agenda_home_menu_item', 0, 2 );

/*
 * Replace the poll sponspor with the one linked from the
 * page.
 *
 */
function board_agenda_poll_sponsor( $content, $pq ) {
	$p_id = is_object( $pq ) ? $pq->pollq_id : $pq;

	return board_agenda_poll_result( $p_id, $content );
}

add_filter('poll_template_voteheader_markup', 'board_agenda_poll_sponsor', 10, 2 );
add_filter('poll_widget_content', 'board_agenda_poll_sponsor', 10, 2 );

/**
 * This function modifies the main WordPress query to include an array of
 * post types instead of the default 'post' post type.
 *
 * @param object $query  The original query.
 * @return object $query The amended query.
 */
function ba_search_include_cpt( $query ) {
  if ( $query->is_category() ) {
		$query->set( 'post_type', array( 'post', 'resources', 'partners', 'events' ) );
		// $query->set( 'post_type', array( 'resources' ) );
  }


  return $query;
}

add_filter( 'pre_get_posts', 'ba_search_include_cpt' );

/**
 * Show the Upgra Image widget only is user has a free account
 */
function show_upgrade_widget() {
	global $nine3_Membership;

	return is_user_logged_in() && $nine3_Membership->get_user_level() == 0;
}


/***
*
* All the functions placed here have been added after the site extension by 93digital on August 016
*
*/

/**
*
* This function returns the resource posts displayed on the resource centre page
*
* @param int (required) $paged
* @param String $search Search parameter
* @param String $category Category
* @return Object WP_Object
*
*/
function get_resource_posts($paged, $featured = false, $get = null){
	//variales
	$args = array(
		'post_type' => 'resources',
		'orderby' => array( 'meta_value' => 'DESC', 'post_modified' => 'DESC' ),
		'paged' => $paged,
		'resource_search' => 1,
		'meta_key' => 'featured',
		'meta_value' => 0,
		'meta_compare' => '>=',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'featured',
				'value' => $featured ? 1 : 0, // Need to hide the featured ones, otherwise gonna show them twice
			),
		)
	);

	if( $featured )  {
		$args['posts_per_page'] = -1;
	}

	//checking if we are displaying posts for the search form
	if($get !== null){

		// if(isset($get['search']) && $get['search'] !== 'null' && $get['search'] !== null && $get['search'] !== '')
			// $args['s'] = $get['search'];

		if(isset($get['category']) && $get['category'] !== 'null' && $get['search'] !== null && $get['search'] !== ''){

			//taxonomy
			$tax_details = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'resource_category',
					'field' => 'slug',
					'terms' => $get['category'],
					'include_children' => true,
					'operator' => 'IN'
				)
			);

			//adding taxonomy query array to the args array
			$args['tax_query'] = $tax_details;

		}

		unset($args['paged']);

	}

	return new WP_Query($args);
}

/**
*
* This method returns the post featured image
*
* @param int (required) $post_id
* @param String $size
* @return String $immage_url
*/
function get_post_featured_image($post_id, $size = 'medium'){

	//variables
    $image_url = '';

    //getting attachment image url
    if(has_post_thumbnail( $post_id )){
      $image_id = get_post_thumbnail_id( $post_id );
      $image = wp_get_attachment_image_src($image_id, $size, true );
      $image_url = $image[0];
    }

    return $image_url;
}

function php_display_all_errors(){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

function page_custom_pagination($numpages = '', $pagerange = '', $paged = ''){

	if(empty($pagerange))
		$pagerange = 2;

	global $paged;

	if(empty($paged))
		$paged = 1;

	if($numpages == ''){
		global $wp_query;
		$numpages = $wp_query->max_num_pages;

		if(!$numpages)
			$numpages = 1;

	}//end if numpages

	//building pagination args array
	// Remove the data appended to the url
	$base = get_pagenum_link();
	$args = array();

	if(preg_match("/\?(.*)$/", $base, $data)) {
		$args = parse_str($data[1]);
		// print_r($args);
	}
	$base = preg_replace('/\?.*$/', '', $base);

	$pagination_args = array(
		'base'            => $base . '%_%/',
	  'format'          => 'page/%#%',
	  'total'           => $numpages,
	  'current'         => $paged,
	  'show_all'        => False,
	  'end_size'        => 1,
	  'mid_size'        => $pagerange,
	  'prev_next'       => True,
	  'prev_text'       => __('&laquo; First'),
	  'next_text'       => __('Last &raquo;'),
	  'type'            => 'plain',
	  'add_args'        => $args,
	  'add_fragment'    => '',
	);

	//getting paginate links
	$paginate_links = paginate_links($pagination_args);

	if($paginate_links){
		echo "<nav class='resources_custom_pagination'>";
		echo $paginate_links;
		echo "</nav>";
	}

}

/**
*
* This function returns all the partner data based on the current resource post id
*
* @param int (required) $post_id
* @return Object $partner_data
*/
function get_partner_data($post_id){

	//variables
	$partner_data = new stdClass();

	//getting partner custom filed
	$partner = get_field('partner', $post_id);

	//getting partner id
	$partner_id = $partner->ID;

	//getting permalink
	$partner_data->permalink = get_permalink($partner_id);

	//getting title
	$partner_data->title = get_the_title($partner_id );

	//getting post featured image
	$partner_data->featured_image_url = get_field('the_featured_post_image', $partner_id);

	//getting thumb for the header
	$partner_data->thum_image_url = $partner_data->featured_image_url['sizes']['medium'];

	//getting conten
	$partner_data->content = (empty($partner->post_excerpt)) ? $partner->post_content : $partner->post_excerpt;

	//adding id to returned data
	$partner_data->partner_id = $partner_id;

	return $partner_data;

}

 /**
  *
  * This method returns the blog page id
  *
  * @return Int $blog_id
  */
   function get_external_page_id($pagename){

    //variables
    $page = get_page_by_title($pagename);

    return $page->ID;
  }

  /**
  *
  * This function returns an object with all the data neccesary to process a form with WP
  *
  * @param String (required) $nonce Nonce's name
  * @param String $method Form method. Post by default
  * @return Object $form_data
  */
  function get_process_form_data($nonce, $method = null){

  	//variables
    $form_data = new stdClass();

    //getting admin url
    $form_data->admin_url = admin_url('admin-post.php');

    //getting the method to send the data
    $form_data->method = (is_null($method)) ? 'post' : $method;

    //getting nonce
    $form_data->nonce = wp_create_nonce($nonce);

    return $form_data;

  }

/**
*
* This function returns the page permalink based on the page name
*
* @param String (required) $page_name
* @return String $page_permalink
*/
function get_pagelink_by_name($page_name){

	//variables
  $page_permalink = '';

  $page_id = get_external_page_id($page_name);

  $page_permalink = get_the_permalink($page_id);

  return $page_permalink;

}


/**
  *
  * This method returns the post content out of the loop properly filter
  *
  * @param int (required) $post_id
  * @param boolean $get_excerpt
  * @return String $content
  */
  function get_post_filtered_content( $post_id, $get_excerpt = null ){

    //variables
    $content = '';

    //getting post data
    $the_post = get_post($post_id);

    //getting raw contentn
    $content = $the_post->post_content;

    //applying filters
    /*
    $content = apply_filters( 'the_content', $content );
    $content = str_replace(']]>', ']]>', $content);

    // if filter fails then return unfiltered content
    if( $content == 'Content not available' )
    	return  $unfiltered_content;
    */

    if( $get_excerpt == true )
    	$content = wp_trim_excerpt( $content );

    return $content;

  }

/**
*
* This function returns all the alpha data
*
* @return Array $alpha data
*/
function get_alpha_data() {
	$alpha_data = array('all', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'w', 'x', 'y', 'z');

	return $alpha_data;
}


/**
*
* This function creates a dashboard metabox to download a csv file with all the download records
*
*/
function ba_create_download_records_csv_metabox(){
	if ( current_user_can( 'manage_options' ) ) {
		add_meta_box('download_records_csv', 'Downloads', 'ba_csv_record_metabox', 'dashboard', 'normal', 'high');
	}
}

add_action('wp_dashboard_setup', 'ba_create_download_records_csv_metabox');

/**
*
* This function creates the download records csv metabox content
*/
function ba_csv_record_metabox(){

	//variables
	$limit = 120;
	$temp_url = ba_get_theme_url();
	$csv_url = $temp_url . '/inc/download_records.csv';

	//Displaying content
	?>

		<div>
			<p>Download a CSV file which contains the last <b><?php echo $limit; ?> downloads</b> made in this site</p>
			<a href="<?php echo $csv_url; ?>" class="button button-primary" target="_blank">Download CSV</a>
		</div>
	<?php

}

/**
*
* This function gets the theme url. It is necessary to overwrite the genensis framework url
*
* @return String $theme_url
*/
function ba_get_theme_url($original = null){

	//variables
	$url = (is_null($original)) ? get_template_directory_uri() : get_template_directory();

	//removing genesis url
	$theme_url = str_replace('genesis', 'board-agenda', $url);

	return $theme_url;
}
/**
*
* This function creates a downloable csv file with a download files record
*
*/
function ba_create_records_csv_file(){

	//variables
	$limit = 120;
	$filename = 'download_records.csv';

	//getting proper url
	$url = ba_get_theme_url(true);
	$file_url = $url . '/inc/' . $filename;

	//getting data from database
	global $wpdb;
	$tablename = $wpdb->prefix . 'download_stats';

	$sql = "SELECT * FROM " . $tablename . " LIMIT " . $limit;

	$data = $wpdb->get_results($sql);

	//creating csv file
	$pt = fopen($file_url, 'w');

	//column names
	fputcsv($pt, array('id', 'time', 'file_id', 'file_title', 'file_url', 'user_id', 'user_email', 'post_id', 'post_title', 'post_author'));

	//writting results onto the csv file
	foreach($data as $row){
		fputcsv($pt, get_object_vars($row));
	}//end foreach

	fclose($pt);

}

add_action('load-index.php', 'ba_create_records_csv_file', 1, 0);

/**
*
* This function limits the company areas array
*
* @param Array (required) $array Array to be limited
* @param int (required) $limit Array limit elements
* @return Array $limited
*/
function ba_limit_results($array, $limit){

	//variables
	$limited  = array();

	for($i = 0; $i < $limit; $i++){
		$limited[] = $array[$i];
	}

	return $limited;

}

/**
*
* This function gets all the featured posts by ajax.
*
* @return String $content
*/
function ba_get_ajax_featured_partners(){

	//variables
	$content = '';
	$validation = true;
	$args = array();

	//valdate data
	if(!isset($_POST['offset']) || !isset($_POST['type']) || !isset($_POST['nonce']))
		$validation = false;

	if($validation){

		//getting data
		$type = $_POST['type'];
		$offset = intval($_POST['offset']);

		//getting posts
		if($type == 'featured'){

			$args = array(
        'post_type' => 'partners',
        'meta_key' => 'featured',
        'meta_value' => 2,
        'offset' => $offset
      );

		}
		else{

			$args = array(
        'post_type' => 'partners',
        'offset' => $offset
      );

		}

		$posts = new WP_Query($args);

		//getting posts data
		if($posts->have_posts()){



			while($posts->have_posts()){

				$posts->the_post();

				//getting data
				$expertise = get_field('company_expertise');
				$join_data = join(' ', $expertise);
				$permalink = get_permalink();
				$title = get_the_title();
				$image = get_the_post_thumbnail();
				$excerpt = get_the_excerpt();

				//generating HTML content
				$content .= "<div class='resource-partner'>";
				$content .= "<div class='title'><a href='" . $permalink . "'>" . $title . "</a>";
				$content .= " <span class='industry'>" . $join_data . "</span></div>";
				$content .= "<div class='content'>";
				$content .= "<div class='logo'>" . $image . "</div>";
				$content .= "<div class='excerpt'><p>" . $excerpt . "... <a href='" . $permalink . "'>View profile &raquo;</a><p></div>";
				$content .= "</div></div>";

			}//end while

		}//end if

		wp_reset_postdata();

		//sending HTML content back to the JS ajax function
		echo $content;
		exit();

	}
	else{

		//Something went wrong
		echo $content;
		exit();

	}

}

add_action('wp_ajax_load_featured_partners', 'ba_get_ajax_featured_partners');
add_action('wp_ajax_nopriv_load_featured_partners', 'ba_get_ajax_featured_partners');

/**
*
* This funciton populates the company select on the sign up form
*
* @param Object $form
* @return Object $form
*/


add_filter( 'gform_pre_render_8', 'populate_categories_select' );
add_filter( 'gform_pre_validation_8', 'populate_categories_select' );
add_filter( 'gform_pre_submission_filter_8', 'populate_categories_select' );
add_filter( 'gform_admin_pre_render_8', 'populate_categories_select' );

add_filter( 'gform_field_value_your_parameter', 'my_custom_population_function' );


function populate_categories_select($form){

	//getting partners categories
	$terms = get_terms('partner-categories');
	$choices = array();

	//looping throught the form fields
	foreach($form['fields'] as &$field){

		if($field->type != 'select' || strpos($field->cssClass, 'select_categories') === false)
			continue;

		//looping terms
		foreach($terms as $term){
			$choices[] = array('text' => $term->name, 'value' => $term->term_id);
		}//end terms foreach

		//updating field choices
		$field->choices = $choices;

	}//end form fields foreach

	return $form;

}

/**
*
* This funciton shows the admin bar for client users and resource partners
*
* @return void
*/
function show_admin_bar_for_client_partner_users(){

	if(ba_is_client_user() || ba_is_resource_partner() || ba_is_resource_author() || current_user_can('manage_options' ))
		return true;
}

add_filter('show_admin_bar', 'show_admin_bar_for_client_partner_users' );

/**
*
* This function register a new taxonomy to resources custom post
*
* @return void
*/
function register_resource_category(){

	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Categories', 'textdomain' ),
		'all_items'         => __( 'All Categories', 'textdomain' ),
		'parent_item'       => __( 'Parent Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Category', 'textdomain' ),
		'update_item'       => __( 'Update Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Category', 'textdomain' ),
		'new_item_name'     => __( 'New Category Name', 'textdomain' ),
		'menu_name'         => __( 'Category', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'resource_category' ),
	);

	register_taxonomy('resource_category', 'resources', $args);

}

add_action('init', 'register_resource_category');

/**
*
* This filter set the resources post type on the resource centre search proccess
*
*/
function custom_search_filter( $query ) {

	//resource centre custom search
	if($query->get('resource_search') == 1 || $query->get('partner_search') == 1) {
		$is_partner = $query->get('partner_search');
		if( $is_partner ) {
			$query->set('post_type', 'partners');
		} else {
			$query->set('post_type', 'resources');
		}

		if( isset( $_GET[ 'categories' ] ) && ! empty( $_GET[ 'categories' ] ) ) {
			$tax_query = array(
				'taxonomy' => ( $is_partner ) ? 'partner-categories' : 'resource_category',
				'field' => 'slug',
				'terms' => $_GET[ 'categories' ],
				'operator' => 'IN',
			);

			$query->set( 'tax_query', array( $tax_query ) );
		}
	}
}

add_filter('pre_get_posts', 'custom_search_filter', 99);

function ba_filter_resource_partners( $where, &$wp_query ) {
	global $wpdb;

	if( ! isset($GLOBALS['_where']) ) return $where;

	$where .= sprintf(" AND ( %s.post_title LIKE '%%%s%%' OR %s.post_content LIKE '%%%s%%' )",
										$wpdb->posts,
										esc_sql( $wpdb->esc_like( $_GET['search'] ) ),
										$wpdb->posts,
										esc_sql( $wpdb->esc_like( $_GET['search'] ) )
									);
	return $where;
}
add_filter( 'posts_where', 'ba_filter_resource_partners', 10, 2 );

function ba_ga_code() { ?>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-90789144-1', 'auto');
  ga('send', 'pageview');
</script>
<?php }
add_action( 'wp_head', 'ba_ga_code' );


/**
 * Returns an array ready to be used to
 * get term ACF field.
 *
 * This is done automatically so if the user changes the category
 * name or id it still works
 *
 * @param String $term Default value is the in-built WP post category
 * @return Array $category_ids
 */
function ba_get_category_ids_for_acf( $term = 'category' ) {

	$category_ids = array();

  if( $term == 'category' )
	  $categories = get_categories();
	else {
		$args = array(
			'hide_empty' => false
		);

		$categories = get_terms( $term, $args );
	}

  foreach( $categories as $category ) {
  	$category_ids[ $category->slug ] = $term . '_' . $category->term_id;
  }

  return $category_ids;

}

/**
  *
  * This method crops text to the specified lenght
  *
  * @param String (required) $the_string Text to be cropped
  * @param int $max_lenght Number of characteres displayed on the text. 50 by default.
  * @param String $append Text to be appended at the end of the cropped string. Suspension points by default.
  * @return String $cropped Text cropped
  */
  function crop_text( $the_string, $max_lenght = 50, $append = null ) {

    $cropped = $the_string;

    if( !is_int( $max_lenght ) )
      $max_lenght = 50;

    if(is_null($append) || !is_string($append))
      $append = '...';

    if(strlen($the_string) > $max_lenght){
      $tem_string = substr($the_string, 0, $max_lenght);
      $cropped    = $tem_string . $append;
    }

    return strip_tags( $cropped );

  }

/**
 * Move yoast metabox to the bottom
 *
 * @return String
 */
function ba_move_yoast_metabox_to_bottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'ba_move_yoast_metabox_to_bottom' );

/**
 * This function sets a description text on the
 * resource type category.
 *
 * This solution has been partially developed from here : https://wordpress.stackexchange.com/questions/230032/add-description-to-custom-taxonomy-admin-metabox
 *
 * @return void
 */
function ba_add_resource_type_description_metabox() {
	$post_types = array( 'resources' );
	$screen 	= get_current_screen();

	if ( $screen->parent_base !== 'edit' ) {
		return;
	}

	if ( in_array( $screen->post_type, $post_types ) ) {
		?>
			<script type="text/javascript">
				var tax_slug = 'resources-categories';
				var tax_desc = 'This field is mandatory. Please select a resource type in order to ensure your resource will be displayed on the resource centre.';
				jQuery( '#' + tax_slug + 'div div.inside' ).prepend( '<p>' + tax_desc + '</p>' );
			</script>
		<?php
	}
}

add_action( 'admin_footer', 'ba_add_resource_type_description_metabox' );


/**
 * User registration
 * Ajax login
 */
require_once 'inc/ba-registration.php';

// Extra widgets
require_once 'inc/widgets.php';

// Admin notifications
require_once 'inc/admin-notifications.php';

// Promotional offers
require_once 'inc/promotional-offers.php';

require_once 'inc/user-roles.php';
require_once 'inc/admin-partner.php';
require_once 'inc/gravity-form.php';
require_once 'inc/extras.php';

/**
 * Hide menu items in dashboard for client users
 *
 * @return void
 */
function ba_hide_menu_items_for_client_users() {
	if ( ba_is_client_user() ) : ?>
	<style>

		#wp-admin-bar-comments {
			display: none !important;
		}

		#wp-admin-bar-new-content {
			display: none !important;
		}

		#wp-admin-bar-wpseo-menu {
			display: none !important;
		}

		#menu-posts {
			display: none !important;
		}

		#toplevel_page_wp-pro-advertising {
			display: none !important;
		}

		#menu-comments {
			display: none !important;
		}

		#menu-posts-magazine_issues {
			display: none !important;
		}

		#menu-posts-events {
			display: none !important;
		}

		#menu-posts-education {
			display: none !important;
		}

		#menu-posts-mail {
			display: none !important;
		}

		#menu-posts-notifications {
			display: none !important;
		}

		#menu-posts-offers {
			display: none !important;
		}

		#menu-tools {
			display: none !important;
		}

		#toplevel_page_boardagenda {
			display: none !important;
		}
	</style>
	<?php
	endif;
}

add_action( 'admin_head', 'ba_hide_menu_items_for_client_users' );

/**
 * Add featued post meta and position meta to partner CPT
 *
 * Both meta values are used in partners archive page ( suppliers.php ).
 * I do not know how the position works or how it is generated.
 * We might need to remove it but for now both will be included
 * by default when a post is created
 */
function ba_add_partner_meta( $post_id ) {

	// skip revision.
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( get_post_type( $post_id ) === 'partners' ) {

		$position = get_post_meta( $post_id, 'position', true );
		$featured = get_post_meta( $post_id, 'featured', true );

		// update values if required ( usually required for new partener profiles ).
		if ( ! $position || is_null( $position ) || empty( $postition ) ) {
			update_post_meta( $post_id, 'position', 1 );
		}

		if ( ! $featured || is_null( $featured ) || empty( $featured ) ) {
			update_post_meta( $post_id, 'featured', 0 );
		}
	}
}

add_action( 'save_post', 'ba_add_partner_meta' );

/**
 * Same as the funciton above.
 * Resources are not displayed if the featured meta
 * key is not set. This is a bug coming from legacy
 * funcionality, so every resource will be set as no
 * featured by defauld
 *
 * @param int ( required ) $post_id
 * @return void
 */
function ba_set_resource_default_meta_value( $post_id ) {
	// skip revision.
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( get_post_type( $post_id ) === 'resources' ) {

		$featured = get_post_meta( $post_id, 'featured', true );

		if ( ! $featured || is_null( $featured ) || empty( $featured ) ) {
			update_post_meta( $post_id, 'featured', 0 );
		}
	}
}

add_action( 'save_post', 'ba_set_resource_default_meta_value' );

/**
 * Restore slug for partners.
 * Sometimes partners slug is incomplete so
 * view profile does not work.
 * Partner is restored first time is
 * displayed.
 *
 * @param Object ( required ) $partner Partner post
 * @return String $profile_link
 */
function ba_restore_partner_slug( $partner ) {
	$profile_link = '';
	$parsed_title = str_replace( ' ', '-', $partner->post_title );
	$args = array(
		'ID' => $partner->ID,
		'post_name' => strtolower( $parsed_title ),
	);
	$partner_id = wp_update_post( $args );

	if ( $partner_id > 0 ) {
		$profile_link = get_permalink( $partner_id );
	} else {
		$profile_link = get_permalink( $partner->ID ) . strtolower( $parsed_title );
	}
	return $profile_link;
}


//Thought it would be fitting to add images to the RSS here - happy birthday ;) not that anyone gives a crap. <= Oh wow I actually put that...... -- LOLOLOL I really did put this! oh jesus I am going to be 37 soon and still feel so damn low. Fuck my life.

function featuredtoRSS($content) {
global $post;
	if ( has_post_thumbnail( $post->ID ) ){
		$content = '<div>' . get_the_post_thumbnail( $post->ID, 'medium', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
	}
	return $content;
}

add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');


//blah
// function remove_core_updates(){
// global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
// }
// add_filter('pre_site_transient_update_core','remove_core_updates');
// add_filter('pre_site_transient_update_plugins','remove_core_updates');
// add_filter('pre_site_transient_update_themes','remove_core_updates');
