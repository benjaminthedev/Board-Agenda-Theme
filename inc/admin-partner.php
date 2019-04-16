<?php
/**
 * Admin partner management
 *
 */
function ba_my_company_menu() {
  if(!ba_is_client_user() && !ba_is_resource_partner()) return;
  // Hide the "Partners" menu
  remove_menu_page('edit.php?post_type=partners');

  add_menu_page(
      __( 'My Company', 'theme-slug' ),
      __( 'My Company', 'theme-slug' ),
      'read',
      'my-profile',
      'ba_redirect_to_my_page',
      'dashicons-welcome-widgets-menus',
      10
  );
}
add_action( 'admin_menu', 'ba_my_company_menu' );

/**
*
* This function adds the authors admin page
*
*/
function ba_add_authors_menu_page(){

  //checking user role
  if(ba_is_resource_partner() || current_user_can('manage_options' )){

    add_menu_page(
      __( 'Authors', 'theme-slug' ),
      __( 'Authors', 'theme-slug' ),
      'read',
      'resource-authors',
      'ba_authors_page_content',
      'dashicons-book-alt',
      10
    );

  }
}

// add_action( 'admin_menu', 'ba_add_authors_menu_page' );

function ba_redirect_to_my_page() {
  /**
  * The partner information are stored inside the "Parter cpt".
  * So in the function I'm going to get the Page associated to my profile, and
  * if not exists I'm going to create an empty one.
  */
  $args = array(
		'post_type' => 'partners',
		'post_status' => array('publish','draft'),
		'posts_per_page' => -1,
		'author'=> get_current_user_id(),
	);
  $page = get_posts( $args );

  if($page) {
    $link = admin_url() . 'post.php?post=' . $page[0]->ID . '&action=edit';
  } else {
    $link = admin_url() . 'post-new.php?post_type=partners';
  }

  wp_redirect( $link );
  die();
}

/**
 * For Client/Resource user need to set the "PARTNER" field pragmatically,
 * as it his hidden.
 */
add_action('save_post', 'ba_set_partner_field', 999);

function ba_set_partner_field($post_id) {
  $post_type = @$_POST['post_type'];

  /*
   * We weren't able to make the Media upload working for client/resource and author users,
   * so created a new image field using ACF.
   * As we need to show some items in the mega menu we need to use the 'thumbnail',
   * so gonna save it pragmatically on a post save...
   */
  if( 'resources' == $post_type ) {
    if( $thumbnail = get_field('the_featured_post_image', $post_id)) {
      $thumbnail_id = $thumbnail['ID'];
      set_post_thumbnail( $post_id, $thumbnail_id );
    }
  }

	/**
	 * For Resource Partners the Company profile could be created after
	 * they have been upgraded, so no basic profile was available at the time.
	 * In this case need to set the featured field to true.
	 */
	if( ba_is_resource_partner() && 'partners' === $post_type ) {
		update_field('featured', 1, $post_id);
	}

  if((!ba_is_client_user() && !ba_is_resource_partner() && !ba_is_resource_author()) || $post_type != 'resources') return;

  //getting auhtor id
  $author_id = (ba_is_resource_author()) ? get_user_meta( get_current_user_id(), 'partner_related', true ) : get_current_user_id();

  // Get my PARTNER page
  $args = array(
		'post_type' => 'partners',
		'post_status' => array('publish','draft'),
		'posts_per_page' => -1,
		'author'=> $author_id,
	);

  $page = get_posts( $args );

  update_field('partner', $page[0], $post_id);
}

/**
*
* This function enables the featured image for resources custom post if the user is either a resource partner or admin
*
*/
function enable_resources_post_featured_image(){

  //enable featured image -- remove dashboard and media library stuff
  if(ba_is_resource_partner())
    ba_remove_admin_media_items();

  //disable featured image
  if(ba_is_client_user())
    ba_remove_featured_image();

}

add_action('admin_init', 'enable_resources_post_featured_image', 11);

/**
*
* This function removes the admin media items. This allow resource partners to upload featured images but not to accees the media library
*/
function ba_remove_admin_media_items(){

  //removing media library
  remove_menu_page('upload.php');

  //removing dashboard
  remove_menu_page('index.php');

  //removing pdf plugin menu item
  //remove_menu_page('admin.php?page=themencode-pdf-viewer-upload-file');

  ?>

    <style type="text/css">

      #toplevel_page_themencode-pdf-viewer-options{
        display: none;
      }

    </style>

  <?php

}

/**
*
* This function hides the featured image box for client users
*
*/
function ba_remove_featured_image(){

   //removing dashboard
  remove_menu_page('index.php');

}

/**
*
* This function creates the authors page for resource partners users. In this page they can authorize authors to contribute with resource posts.
*
*/
function ba_authors_page_content(){
  //variables
  $partner_id = get_current_user_id();
  $form_data = get_process_form_data('create_author');
  $nonce = wp_create_nonce('create_author');

  //getting all the authors related with this resource partner
  $authors = ba_get_related_authors($partner_id);

  //getting notification
  $notification = (isset($_GET['status'])) ? ba_get_notification(intval($_GET['status'])) : null;

  ?>
  <header class="authors_header">
    <h1>Authors</h1>
    <p class="authors_p">This section allows you to manage which authors you want to give permission to create / edit your resources.</p>
  </header>
  <?php

    //Displaying notification
    if(!is_null($notification)){
  ?>
    <div class="authors_notification <?php echo $notification->class; ?>">
      <p><i class="authors_icon dashicons <?php echo $notification->icon; ?>"></i><?php echo $notification->message; ?></p>
    </div>
  <?php
    }//end if notification
  ?>
  <section class="authors_section">
    <form action="<?php echo $form_data->admin_url; ?>" method="<?php echo $form_data->method; ?>">
      <input type="hidden" value="<?php echo $nonce; ?>" name="form_nonce">
      <h2>Add a new author</h2>
      <p class="authors_p">Create a new author user filling up the form below. An email will be sent to this user to set a password and start using WP to create / edit your resources.</p>
      <div class="form_element_wrapper">
        <div class="form_element">
          <label for="author_name" class="author_label">Username:</label>
        </div>
        <div class="form_element">
          <input type="text" name="author_name" class="author_text_input" required="required" maxlenght="60">
        </div>
      </div>
      <div class="form_element_wrapper">
        <div class="form_element">
          <label for="author_email" class="author_label">Email:</label>
        </div>
        <div class="form_element">
          <input type="email" name="author_email" class="author_text_input" required="required">
        </div>
      </div>
      <div class="form_element_wrapper">
        <div class="form_element">
          <input type="submit" value="Add Author" class="author_submit_input button button-primary">
        </div>
      </div>
    </form>
  </section>
  <section class="authors_section">
    <h2>Authors assigned to you</h2>
    <p class="authors_p">The authors listed below have been authorized to create / edit your resources</p>
    <?php
      if(empty($authors)){
    ?>
        <p class="no_authors">There are no authors registered on your account.</p>
    <?php
      }
      else{
    ?>
        <table class="authors_list">
          <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Date registered</th>
          </tr>

          <?php

            //Displaying related authors
            foreach($authors as $author){

              ?>

                <tr>
                  <td><?php echo $author->id; ?></td><td><?php echo $author->name; ?></td><td><?php echo $author->email; ?></td><td><?php echo $author->date_registered; ?></td>
                </tr>

              <?php

            }//end foreach

          ?>

        </table>
      <?php
    }//end authors table else
  ?>
  </section>
  <?php
}

/**
*
* This function creates a new author user
*
*
*/
function ba_create_author_user() {

  //checking if we come from the proper form
  if(isset($_POST['author_name']) && isset($_POST['author_email']) && isset($_POST['form_nonce'])){
    //variables
    $partner_id = get_current_user_id();

    //validating form
    if($_POST['author_name'] === '' || is_null($_POST['author_name']) || $_POST['author_email'] === '' || is_null($_POST['author_email']))
      ba_redirect_to_author_page(0);

    //validating email format
    if(!is_email($_POST['author_email']))
      ba_redirect_to_author_page(1);

    //checking that the email does not exists
    if(email_exists($_POST['author_email'] !== false ))
      ba_redirect_to_author_page(2);

    //sanitizing and getting values
    $user_name = sanitize_text_field($_POST['author_name'] );
    $user_email = sanitize_email($_POST['author_email'] );
    $password = wp_generate_password();

    //adding user to the wp site
    $args = array(
      'user_login' => $user_name,
      'user_email' => $user_email,
      'user_pass' => $password,
      'role' => 'resource_author'
    );

    $author_id = wp_insert_user($args);

    //checking if the user is properly created
    if(!is_wp_error($author_id)){

      //adding this author to this partner profile
      update_user_meta( $author_id, 'partner_related', $partner_id );

      //sending change password email
      ba_retrieve_password_by_login($user_email, false, $password);


      if(!is_wp_error($author_id ))
        ba_redirect_to_author_page(4);      //Email sent
      else
        ba_redirect_to_author_page(6);      //Error during update

    }
    else
      ba_redirect_to_author_page(3);

  }//end checking form

}

add_action('admin_init', 'ba_create_author_user');

/**
*
* This function redirects the user to the authors page during the authors page form process
*
* @param int (requried) $status notification
* @return void
*/
function ba_redirect_to_author_page($status){

  //variables
  $redirect_url = get_admin_url();

  //adding authors page arg
  $redirect_url = add_query_arg('page', 'resource-authors', $redirect_url);

  //adding status variable
  $redirect_url = add_query_arg('status', $status, $redirect_url);

  //executing safe redirect
  wp_safe_redirect($redirect_url );
  exit();

}

/**
*
* This function process and returns a notification message to the author page based on a status code
*
* @param int (required) $status Status code
* @return Object $notification
*/
function ba_get_notification($status){
  // variables
  $notification = new stdClass();
  $notification->class = 'ba_error';
  $notification->icon = 'dashicons-no-alt';

  switch($status){
    case 0:
      $notification->message = 'There have been an error processing the form.';
      break;
    case 1:
      $notification->message = 'The user email address is invalid';
      break;
    case 2:
      $notification->message = 'This user email already exists in our database';
      break;
    case 3:
      $notification->message = 'There is an error creating the new user. Please try it again';
      break;
    case 4:
      $notification->message = 'A new author user has been susccessfully created. An email has been sent to this user.';
      $notification->class = 'ba_success';
      $notification->icon = 'dashicons-yes';
      break;
    default:
      $notification->message = 'There have been an error processing the form.';
      break;
  }

  return $notification;

}


/**
 *
 * This function gets all the authors related with a resource partner
 *
 * @param int (required) $partner_id resource partner id
 * @return Object $authors_related
 */
function ba_get_related_authors( $partner_id ) {

  //variables
  $authors_related = array();
  $i = 0;

  //getting all the authors
  $args = array(
    'role' => 'resource_author'
  );

  $authors = get_users( $args );

  //looping authors to get those who are related with this partner id
  foreach($authors as $author){

    //getting author related meta
    $is_related = get_user_meta( $author->ID, 'partner_related', true );

    if($is_related !== ''){

      //this author is related so we added it to the authors array
      $authors_related[$i] = new stdClass();

      $authors_related[$i]->id = $author->ID;
      $authors_related[$i]->name = $author->data->display_name;
      $authors_related[$i]->email = $author->data->user_email;
      $authors_related[$i]->date_registered = date('d-m-Y H:i:s', strtotime($author->data->user_registered));

      $i++;

    }//end adding author if

  }//end foreach

  return $authors_related;

}

/**
 * Meta boxes to Approve / Ban a partner profile
 *
 */
/**
 * Add the meta box on the specified pages
 */
add_action( 'add_meta_boxes', 'ba_add_partner_profile_metaboxe' );
function ba_add_partner_profile_metaboxe() {
	if( ! current_user_can( 'manage_options' ) ) return;

    $screens = array('partners');
    foreach ( $screens as $screen ) {
        add_meta_box(
            'ba-partner',
            __( 'Partner Profile Action', 'theme-slug' ),
            'ba_partner_profile_metabox',
            $screen,
            'side',
            'high'
        );
    }
}

/*
 * Utility function to retrieve the field value
 *
 * Usage: ba_partner_get_meta( 'ba-partner_[field_id]', '[default value]' );
 */
function ba_partner_get_meta( $field_id, $default ) {
    global $post;

    $value = get_post_meta( $post->ID, $field_id, true );

    if( empty( $value ) ) {
        return $default;
    } else {
        return is_array( $value ) ? stripslashes_deep( $value ) : stripslashes( wp_kses_decode_entities( $value ) );
    }
}

/**
 * Rendering the Meta Box
 *
 * This function print out the meta box content.*
 *
 * NOTE: To render the metabox in a custom page use the following code:
 *
 *  <?php do_meta_boxes( 'partners', 'advanced', null ); ?>
 */
function ba_partner_profile_metabox( $post ) {
		wp_nonce_field( '_ba-partner_nonce', 'ba-partner_nonce' );

		$status = get_post_status( $post );
		$is_approved = 'publish' === $status;
?>
    <p>
        <label for="status">Status:</label>
        <span class="ba-status <?php echo $is_approved ? 'ba-approved' : 'ba-pending' ?>"><?php echo $is_approved ? 'Approved' : 'Pending' ?></span>
    </p>
    <p>
			<?php if ( ! $is_approved ): ?><a href="<?php echo esc_url( add_query_arg( 'approve', $post->ID ) ); ?>" class="button button-primary">Approve</a><?php endif; ?>
			<a href="<?php echo esc_url( add_query_arg( 'ban', $post->ID ) ); ?>" class="button ba-delete">BAN</a>
    </p>

<?php
}
