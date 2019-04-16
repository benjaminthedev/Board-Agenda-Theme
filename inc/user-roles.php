<?php
/**
 * Client Users & Partners roles management
 *
 * The following code take care of creating and manage the limitations
 *
 */

/**
 * Check if the current user is a subscrber
 */
function ba_is_subscriber() {
	$current_user = wp_get_current_user();
	$role = @$current_user->roles[0];

	return $role == 'subscriber';
}

/**
 * Check if the current user is a 'client_user'.
 */
function ba_is_client_user() {
	$current_user = wp_get_current_user();
	$role = @$current_user->roles[0];

	return $role == 'client_user';
}

/**
 * Check if the Partner post or the current user is a resource partner.
 *
 * This function is used to limit the data showed between CLient Users and
 * Resource Partners.
 * Is also used to check if the logged user role is 'resource_partner'.
 *
 * @param $post_id integer the Partner post id.
 */
function ba_is_resource_partner( $partner_id = null ) {
	if( $partner_id === null ) {
		$current_user = wp_get_current_user();
		$role = @$current_user->roles[0];

		return $role == 'resource_partner';
	} else {
		return get_field('featured', $partner_id) == 1;
	}
}

function ba_is_resource_author(){

	$current_user = wp_get_current_user();
	$role = @$current_user->roles[0];

	return $role == 'resource_author';

}

/**
* This function adds the new users role related with the resource centre
*/
function add_extra_users_role(){

	//variables
	$capabilities = array(
		'read'=> true,
		'edit_posts' => false,
		'delete_posts' => false,
		'create_posts' => false,
		'publish_posts' => false,
		'edit_themes' => false,
		'install_plugins' => false,
		'update_plugin' => false,
		'update_core' => false
		);

	//register client user role
	$result = add_role('client_user', __('Client User'), $capabilities);

	//register resource partner role
	$result = add_role('resource_partner', __('Resource Partner'), $capabilities);

	//register resource author role
	$result = add_role('resource_author', __('Resource Author'), $capabilities);

}


//add_action('init', 'add_extra_users_role');

function ba_remove_role(){
	remove_role('client_user');
	remove_role('resource_partner');
}

// add_action('admin_init', 'ba_remove_role');
/**
*
* This function assigns the new capabilities to the new roles
*
* @return void
*/
function ba_add_user_caps() {

	//variables
	//array('client_user_cap', 'client_user_caps' 'resource_partner_cap', 'resource_partner_caps')

	//adding the new capabilities to administrators
	$role = get_role('administrator');

	//adding caps
	$role->add_cap( 'read' );
	$role->add_cap( 'read_client_user_cap');
	$role->add_cap( 'read_client_user_caps');
	$role->add_cap( 'read_resource_partner_cap');
	$role->add_cap( 'read_resource_partner_caps');

	$role->add_cap( 'edit_client_user_cap');
	$role->add_cap( 'edit_client_user_caps');
	$role->add_cap( 'edit_resource_partner_cap');
	$role->add_cap( 'edit_resource_partner_caps');

	$role->add_cap( 'edit_others_client_user_cap');
	$role->add_cap( 'edit_others_client_user_caps');
	$role->add_cap( 'edit_others_resource_partner_cap');
	$role->add_cap( 'edit_others_resource_partner_caps');

	$role->add_cap( 'publish_client_user_caps');
	$role->add_cap( 'publish_resource_partner_caps');

	$role->add_cap( 'delete_client_user_cap');
	$role->add_cap( 'delete_client_user_caps');
	$role->add_cap( 'delete_others_client_user_caps');
	$role->add_cap( 'delete_published_client_user_caps');
	$role->add_cap( 'delete_private_client_user_caps');

	$role->add_cap( 'delete_others_client_user_cap');
	$role->add_cap( 'delete_published_client_user_cap');
	$role->add_cap( 'delete_private_client_user_cap');

	$role->add_cap( 'delete_resource_partner_cap');
	$role->add_cap( 'delete_resource_partner_caps');
	$role->add_cap( 'delete_others_resource_partner_caps');
	$role->add_cap( 'delete_published_resource_partner_caps');
	$role->add_cap( 'delete_private_resource_partner_caps');

	$role->add_cap( 'delete_others_resource_partner_caps');
	$role->add_cap( 'delete_published_resource_partner_caps');
	$role->add_cap( 'delete_private_resource_partner_caps');

	$caps = array(
		'read_resource_partners_cap',
		'read_resource_partners_caps',
		'publish_resource_partner_cap',
		'publish_resource_partner_caps',
		'edit_resource_partner_cap',
		'edit_resource_partner_caps',
		'create_posts',
	);

	foreach($caps as $cap) {
		$role->add_cap($cap);
	}

	//setting capabilities to client user
	$role = get_role('client_user');

	$role->remove_cap('edit_theme_options');
	$role->remove_cap('switch_themes');
	$role->remove_cap('edit_themes');
	$role->remove_cap('install_themes');

	//setting capabilities to resource author
	$role = get_role('resource_author');

	$role->remove_cap('edit_theme_options');
	$role->remove_cap('switch_themes');
	$role->remove_cap('edit_themes');
	$role->remove_cap('install_themes');


	//setting capabilities to resource partners
	$role = get_role('resource_partner');

	$role->remove_cap('edit_theme_options');
	$role->remove_cap('switch_themes');
	$role->remove_cap('edit_themes');
	$role->remove_cap('install_themes');
}


//add_action('admin_init', 'ba_add_user_caps', 999);


/**
 * Check if the client has reached the limit of the allowed posts that can
 * create. If so, just die and display the error message.
 *
 */
add_action('admin_head', 'ba_check_if_client_can_add_new');
function ba_check_if_client_can_add_new() {
	global $pagenow;

	if(!ba_is_client_user() && !ba_is_resource_partner()) return;

	// checking if the post has been published by a client user
	if(in_array($pagenow, array('post.php', 'post-new.php'))) {
		// client_user can't edit the time
		echo '<style>.misc-pub-curtime{display:none!important;}</style>';
	}

	// Max elements per post type
	$max_posts = array(
		'resources' => 2,
		'partners' => 1
	);

	$post_type = $_GET['post_type'];
	//getting client user puiblished posts count
	$args = array(
		'post_type' => $post_type,
		'post_status' => array('publish','draft'),
		'posts_per_page' => -1,
		'author'=> get_current_user_id(),
	);

	$posts = new WP_Query($args);

	//checking posts number
	$max = $max_posts[$post_type];
	if($posts->post_count >= $max) {
		if('post-new.php' == $pagenow) {
			wp_safe_redirect(admin_url() . 'edit.php?post_type=' . $post_type . '&denied=1&max=' . $max);
		} else {
			//Remove the "Add new"
			echo '<script>jQuery(document).ready(function($){$("a.page-title-action,a[href^=\'post-new.php\']").remove()});</script>';
		}
	}
}

if(isset($_GET['denied'])) {
	add_action('admin_notices', 'bp_cant_add_more_error');
}
function bp_cant_add_more_error() { ?>
	<div class="error notice">
		<p>You're not allowed to create other <?php echo $_GET['post_type']; ?></p>
	</div>
<?php }

/**
 * User can only see / edit to their content, so gonna filter the resources list,
 * but only in the back-end
 */
if(is_admin() && ! current_user_can('manage_options')) {
	add_action('pre_get_posts', 'ba_filter_resources');
}
function ba_filter_resources($query) {
	global $pagenow;

	// the _skip parameter is set by the 'upgrade your profile...' notice
	if( isset($GLOBALS['_skip']) ) return;

	if($pagenow == 'edit.php' && 'resources' === $_GET['post_type']) {
		$query->set('author', get_current_user_id());
	}
}

/**
 * Client Users can access only to some categories...
 *
 * To allow a user to access to a category, just use 'client_user' inside the
 * category description.
 */
add_filter( 'get_terms', 'ba_filter_terms', 0, 3 );

function ba_filter_terms($terms, $type, $taxonomy) {
	if(!ba_is_client_user() || $type[0] != 'category') return $terms;

	$allowed = array();
	foreach($terms as $term) {
		if($term->description == 'client_user') $allowed[] = $term;
	}

	return $allowed;
}

/**
 * Remove Leaky Paywall metabox for Client/Resource user
 *
 */
function ba_remove_paywall_meta_box() {
	global $wp_meta_boxes;

	if ( ! current_user_can( 'manage_options' ) ) {
		remove_meta_box( 'leaky_paywall_content_visibility', null, 'side' );
		remove_meta_box( 'et_monarch_settings', null, 'advanced' );
		remove_meta_box( 'et_monarch_sharing_stats', null, 'advanced' );
		remove_meta_box( 'postexcerpt', null, 'normal' );
		//remove_meta_box( 'wpseo_meta', null, 'normal' );

		?>
			<style type="text/css">
					#wpseo_meta{
						display: none;
					}
			</style>
		<?php

		// TODO: Use a hook, if possible
		echo '<style>.yoast-seo-score.content-score,.yoast-seo-score.keyword-score {display: none !important;}</style>';
	}
}
add_action( 'add_meta_boxes', 'ba_remove_paywall_meta_box', 999 );

/**
 * Check if the profile company page exists, otherwise need to force the
 * user to create it, before be able to add any resources.
 *
 */
add_action('admin_head', 'ba_check_if_company_profile_exists');
function ba_check_if_company_profile_exists() {
	if( ! ba_is_resource_partner() ) return;

	if( isset( $_GET['post_type'] ) && 'resources' == $_GET['post_type'] ) {
		// If no Company Profile exists, I can't add a new resource
		$profile = ba_get_my_profile();
		if( empty( $profile ) ) wp_safe_redirect( admin_url('post-new.php?post_type=partners') );
	}
}
