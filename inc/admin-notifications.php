<?php
/**
 * Add notification for the Admins
 *
 */
function ba_add_admin_notification($action, $message, $user) {
	$user_id = is_object( $user ) ? $user->ID : $user;
	$company = get_user_meta( $user_id, 'company', true );
	// The text after more tag, needed for make "Search Notification" working.
	$message .= '<!--more-->' . $company;

	// Create post object
	$notification = array(
	  'post_title'    => wp_strip_all_tags( $action ),
	  'post_content'  => $message,
	  'post_status'   => 'publish',
	  'post_author'   => $user_id,
	  'post_type' => 'notifications'
	);

	// Insert the post into the database
	$post_id = wp_insert_post( $notification );
}

/**
 * Show all the notifications for the admins
 *
 */
add_filter('admin_notices', 'ba_get_admin_notifications');

function ba_get_admin_notifications() {
	// Only for admins
	if( ! current_user_can('manage_options') ) return;

	$count = ba_get_notifications_count();
	if( $count['total'] === 0 || @$_GET['post_type'] === 'notifications' ) return;

	echo '<div class="notice update-nag" style="display: block"><p>';

	$url = admin_url('/edit.php') . '?post_type=notifications';
	if( @$count['draft'] > 0 ) {
		printf( 'There are new pending activations. Click <a href="%s" class="button">Here</a>', $url );
	} else {
		printf( 'There are new approved partners. Click <a href="%s" class="button">Here</a>', $url );
	}
	echo '</p></div>';
}

/**
 * Customise the columns for the notifications table
 *
 */
/*
 * Add extra filter to the top bar:
 *
 * Pending
 * Approved
 * Banned
 */
/**
 * Return an array containing the count of each post status
 */
function ba_get_notifications_count() {
	global $wpdb;

	// Get the count for each status
	$query = "SELECT Count(*) as total, post_status FROM $wpdb->posts t1 INNER JOIN $wpdb->postmeta t2 ON t1.ID = t2.post_id WHERE t2.meta_key = 'has_notification' GROUP BY post_status";
	$results = $wpdb->get_results( $query );
	$count = array( 'total' => 0 );

	foreach( $results as $result ) {
		$count[ $result->post_status ] = $result->total;

		$count['total'] += $result->total;
	}

	return $count;
}

function ba_partners_filters( $views ) {
	$count = ba_get_notifications_count();

	$pending_url = add_query_arg('filter', 'pending');
	$approved_url = add_query_arg('filter', 'approved');
	$banned_url = add_query_arg('filter', 'banned');
	$views = array(
		'all' => $views['all'],
		'pending' => sprintf( '<a href="%s">Pending (%d)</a>', esc_url($pending_url), $count['draft'] ),
		'approved' => sprintf( '<a href="%s">Approved (%d)</a>', esc_url($approved_url), $count['publish'] ),
		'banned' => sprintf( '<a href="%s">Banned (%d)</a>', esc_url($banned_url), $count['trash'] ),
	);

	return $views;
}

function ba_manage_custom_column() {
	if( isset($_GET['post_type']) && 'notifications' === $_GET['post_type'] ) {
		$screen = get_current_screen();

		// Add 'Banned', 'Pending', 'Approved' filters to the list
		add_filter("views_edit-notifications", 'ba_partners_filters', 99, 1);
	}

	add_action( "manage_notifications_posts_custom_column", 'ba_notification_custom_columns', 99, 2);
	add_filter( "manage_notifications_posts_columns" , 'ba_notification_custom_label', 99, 1 );
}

function ba_notification_custom_columns( $column, $post_id ) {
  global $post;

  $user_id = $post->post_author;
  $partner_id = get_user_meta( $user_id, '_partner', true );
  $status = get_post_status( $partner_id );

	// All notifications url
	$url = admin_url('/edit.php') . '?post_type=notifications';

	switch($column) {
    case 'type':
      $userdata = get_user_by( 'id', $user_id );
      $role = $userdata->roles[0];
      switch ($role) {
        case 'client_user':
          echo 'Free basic profile';
          break;

        case 'resource_partner':
          echo 'Enhanced profile';
          break;

        default:
          echo 'Registered';
          break;
      }

      break;
		case 'company':
			if( $partner_id ) {
				// Get the partner profile stored in the user meta
				printf( '<a href="%s">', esc_url( get_edit_post_link( $partner_id ) ) );
				echo get_the_title( $partner_id );
				echo '</a>';
			} else {
				echo get_user_meta( $user_id, 'company', true );
			}
			break;
		case 'message':
      echo $post->post_content;
			break;
    case 'status':
      switch($status) {
        case 'publish':
          echo '<span class="ba-status ba-approved">Approved</span>';
          break;
        case 'draft':
          echo '<span class="ba-status ba-pending">Pending</span>';
          break;
        case 'trash':
          echo '<span class="ba-status ba-banned">Banned</span>';
          break;
      }
      break;
    case 'action':
      switch($status) {
        case 'publish':
					$partner = get_user_meta( $user_id, '_partner', true );
					if( $partner ) :
						$url = add_query_arg( array( 'ban' => $partner_id, 'notification-id' => $post_id ), $url );
	          printf( '<a href="%s" class="button ba-delete">Ban</a>', esc_url( $url ) );
					endif;
          break;
        default:
					$url = add_query_arg( array( 'approve' => $partner_id, 'notification-id' => $post_id ), $url );
          printf( '<a href="%s" class="button button-primary">Approve</a>', esc_url( $url ) );
          break;
      }
			break;
		case 'upgrade':
		 	$userdata = get_userdata( $user_id );
			if( $userdata->roles[0] == 'resource_partner' ) {
				echo '<span class="dashicons dashicons-yes ba-approved"></span>';
			} else {
				// $class = 'draft' === $status ? 'button-disabled ba-disabled' : '';
				$url = add_query_arg( array( 'upgrade' => $partner_id, 'notification-id' => $post_id, 'user-id' => $user_id ), $url );
				printf( '<a href="%s" class="button ba-upgrade">Upgrade</a>', esc_url( $url ) );
			}
			break;
	}
}

/**
 * Add my own custom column to the list of the available ones.
 *
 * @param {array} array of the available columns
 */
 function ba_notification_custom_label( $columns ) {
   $my_column = array(
			 'type' => 'Account type',
			 'company' => 'Company profile',
       'message' => 'Message',
			 'status' => 'Status',
			 'action' => 'Action',
			 'upgrade' => 'Resource Partner',
   );

   $index = array_search( 'date', array_keys( $columns ) );
   if( $index === FALSE ) $index = count( $columns ) - 1;

	 // Remove unwanted columns
	 unset( $columns['visibility'] );
	 unset( $columns['coauthors'] );
	 unset( $columns['author'] );
	 unset( $columns['title'] );

   return array_merge( array_slice( $columns, 0, $index + 1 ),
                       $my_column,
                       array_slice( $columns, $index + 1 ) );
 }

/**
 * Need to remove quick actions for notifications, as we don't need them
 *
 */
function ba_notifications_remove_row_actions($actions, $post) {
  if( $_GET['post_type'] !== 'notifications' ) return $actions;

	// Notification is used also for new accounts, so don't need to show
	// the View profile action for them.
	$user_id = $post->post_author;
  $partner = get_user_meta( $user_id, '_partner', true );
	if( ! $partner ) return array();

  $status = get_post_status($partner);

	$edit_link = esc_url( get_edit_post_link( $partner ) );
	$title = get_the_title( $partner );
	$actions = array(
		'view' => sprintf( '<a href="%s">View Profile page</a>', $edit_link)
	);
  return $actions;
}

add_filter( 'post_row_actions', 'ba_notifications_remove_row_actions', 99, 2 );

// Approve / Ban the partner, if needed
function ba_approve_ban_partner() {
	if( ! current_user_can( 'manage_options' ) ) return;

	if( isset($_GET['ban'] ) || isset($_GET['approve']) || isset($_GET['upgrade']) ) {
		$is_ban = isset($_GET['ban']);
		$confirm_ban = isset($_GET['do-it']);
		$approve_id = isset($_GET['approve']) ? intval( $_GET['approve'] ) : intval( $_GET['upgrade'] );
		$partner_id = $is_ban ? intval( $_GET['ban'] ) : $approve_id;
		$user_id = get_post_field( 'post_author', $partner_id );
		if( isset( $_GET['user-id'] ) ) $user_id = intval( $_GET['user-id'] );

		$notification_id = intval($_GET['notification-id']);

		// Updrade to Resource Partner?
		$upgrade = isset($_GET['upgrade']);

		// Get all the resources for the specified partner
		$resources = new WP_Query( array(
			'post_type' => 'resources',
			'meta_key' => 'partner',
			'meta_value' => $partner_id,
		) );

		if( $is_ban ) {

			if( $confirm_ban ) {
				while( $resources->have_posts() ) {
					$resources->the_post();

					wp_trash_post( get_the_ID() );
				}

				// Trash the original post
				wp_trash_post( $partner_id );

				// Disable the user, by adding the _banned parameter
				// Now he/she will no longer be able to login into the website
				wp_delete_user( $user_id );
			}
		} else {
			while( $resources->have_posts() ) {
				$resources->the_post();

				wp_publish_post( get_the_ID() );
			}

			// Trash the original post
			wp_publish_post( $partner_id );

			// Inform the user
			// Get the mail from the user_id
			$user_data = get_userdata( $user_id );

			// Inform the user about the approval
			if( isset($_GET['approve']) ) {
				board_agenda_send_mail( 'FREE basic profile approved', null, $user_data->user_email, null, null, false );
			} else {
				board_agenda_send_mail( 'Enhanced profile upgraded', null, $user_data->user_email, null, null, false );
			}
		}
		wp_reset_postdata();

		// Need to upgrade/downgrade the user
		if( $is_ban ) {
			if( $confirm_ban ) {
				$user_role = 'subscriber';

				update_post_meta($notification_id, '_status', 'banned');
			}
		} else {
			$user_role = ( $upgrade ) ? 'resource_partner' : 'client_user';

			update_post_meta($notification_id, '_status', 'approved');
		}

		// Set the user role according to the current action
		$userdata = array(
			'ID' => $user_id,
			'role' => $user_role
		);
		wp_update_user( $userdata );

		// If "Upgrading" the user to Resource Partner, set the "Featured" field
		// to "TOP"
		update_field('featured', $upgrade ? '1' : '0', $partner_id);
	}
}

add_action( 'admin_init', 'ba_approve_ban_partner', 10 );
add_action( 'admin_init', 'ba_manage_custom_column', 10 );

// Filter notifications page results?
if( is_admin() && isset( $_GET['post_type'] ) && 'notifications' == $_GET['post_type'] && isset($_GET['filter']) ) {
	add_action( 'pre_get_posts', 'ba_filter_notifications_list' );
}

/**
 * Filter the notification list by "filter"
 *
 */
function ba_filter_notifications_list( $query ) {
	$query->set('meta_key', '_status');
	$query->set('meta_value', esc_sql( $_GET['filter'] ) );
}

/**
 * Show a notification to client user, about his own "limits"
 */
add_action( 'admin_notices', 'ba_show_notices_for_client_user' );

function ba_show_notices_for_client_user() {
	if( ! ba_is_client_user() ) return;

	$GLOBALS['_skip'] = true;

	$admin_notification = get_field( 'keys_admin_notification', 1588 );
?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" media="screen">
	<div class="notice notice-error">
		<?php echo $admin_notification; ?>
		<ul class="ba-form-list">
			<?php while( have_rows( 'enhanced_keys', 1588 ) ) : the_row(); ?>
			<li><?php the_sub_field( 'item' ); ?></li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php
	$GLOBALS['_skip'] = false;
}
