<?php
/**
 * This function is based on the original paywall do_leaky_paywall_profile
 * function.
 * I just copied it as I need to make some changes like#;
 *  - hide the username
 *  - force the password to be at least 8 characters
 *  - Ability to change Name and surname, but not email
 */
function board_agenda_leaky_paywall_profile( $atts ) {
  if( ! function_exists( 'get_leaky_paywall_settings' ) ) return;

  $nm = Nine3_Simple_Membership::getInstance();
  $to_subscribe = isset( $_GET[ 'subscribe' ] ) && $nm->get_user_level() <= 0;
  $class = $to_subscribe ? 'hidden' : '';

  $settings = get_leaky_paywall_settings();
  $mode = 'off' === $settings['test_mode'] ? 'live' : 'test';

  $defaults = array(
  );

  // Merge defaults with passed atts
  // Extract (make each array element its own PHP var
  $args = shortcode_atts( $defaults, $atts );
  extract( $args );

  $results = '';

  if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $user = get_userdata( $user_id );

		/**
		 * After registration user is automatically logged in, and the password is automatically
		 * generated.
		 *
		 * Se the first time we need to allow the user to reset his password, and so showing only
		 * the password fields
		 */
    $is_first_time    = get_user_meta( $user_id, '_need_to_reset_pwd', true );
 	  $first_time_class = empty( $is_first_time ) ? '': ' hidden ';
 	  $show_message     = get_user_meta( $user_id, '_show_thank_you', false );

		// Expiration link
		$site = '';
		$expires = get_user_meta( $user->ID, '_issuem_leaky_paywall_' . $mode . '_expires' . $site, true );
		if ( empty( $expires ) || '0000-00-00 00:00:00' === $expires ) {
			$expires = '';
		} else {
			$date_format = get_option( 'date_format' );
			$expires = 'Subscription expiration: ' . mysql2date( $date_format, $expires );
		}

		$plan = get_user_meta( $user->ID, '_issuem_leaky_paywall_' . $mode . '_plan' . $site, true );
		if ( !empty( $plan ) && 'Canceled' !== $plan && 'Never' !== $expires ) {
			$expires = sprintf( __( 'Recurs on %s', 'issuem-leaky-paywall' ), $expires );
		}

    $paid = leaky_paywall_has_user_paid( $user->user_email, $site );

    if ( 'subscription' === $paid ) {
      $subscriber_id = get_user_meta( $user->ID, '_issuem_leaky_paywall_' . $mode . '_subscriber_id' . $site, true );
      $payment_gateway = get_user_meta( $user->ID, '_issuem_leaky_paywall_' . $mode . '_payment_gateway' . $site, true );
      $cancel = sprintf( __( '<a href="%s">cancel</a>', 'issuem-leaky-paywall' ), '?cancel&payment_gateway=' . $payment_gateway . '&subscriber_id=' . $subscriber_id );
    } else {
      $cancel = '&nbsp;';
    }

		if ( isset( $_GET['pwd'] ) )	$first_time_class = ' hidden ';
		if ( isset( $_GET['premium'] ) ) $expires = 'Subscription expiration: October 13, 2017';

    $user = wp_get_current_user();

    $results .= sprintf( __( '<p class="%s">Welcome %s,</p>', 'issuem-leaky-paywall' ),
      $class, $user->display_name, wp_logout_url( get_page_link( $settings['page_for_login'] ) ) );

		if( $is_first_time ) {
			$results .= '<p>Thank you for subscribing to Board Agenda! Please take a moment to set a password that you can use to log on to the site next time you visit.</p>';
		} else {
			$results .= '<p>Thank you for subscribing to Board Agenda! You can continue to use your existing password to log into the site.</p>';
		}

    if( $to_subscribe ) {
      $results .= '<div class="download-error">You are being redirected to process your payments</div>';
    }

    $results .= apply_filters( 'leaky_paywall_profile_your_subscription_end', '' );

		// Reset password box
		$results .= '<form id="board-agenda-profile" action="" method="post" class="' . $class . '">';
		if( $is_first_time ) {
			$results .= '<div class="first-password">';
			$results .= '<p>';
	    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-password3">' . __( 'Password (minimum 8 characters)', 'issuem-leaky-paywall' ) . '</label>';
	    $results .= '<input type="password" class="issuem-leaky-paywall-field-input" id="leaky-paywall-password3" name="password3" value="" >';
	    $results .= '</p>';

	    $results .= '<p>';
	    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-gift-subscription-password4">' . __( 'Password (again)', 'issuem-leaky-paywall' ) . '</label>';
	    $results .= '<input type="password" class="issuem-leaky-paywall-field-input" id="leaky-paywall-gift-subscription-password4" name="password4" value="" >';
	    $results .= '</p>';
			$results .= '<p><input type="submit" id="submit" class="button button-primary" value="' . __( 'SET PASSWORD', 'issuem-leaky-paywall' ) . '"  ></p>';
	    $results .= '</div>';
		} else if ( $show_message ) {
      $results .= '<p><strong class="message">Thank you, your password has been saved</strong></p>';
    }

		//Your Profile
    $results .= '<div class="account-deatils">';
    $results .= '<h2 class="' . $class . '">' . __( 'Your Account details', 'issuem-leaky-paywall' ) . '</h2>';
		$results .= '<p>You can return to this page at any time to update your account details by clicking the \'My Account\' link in the top bar of the site.</p>';

    $results .= apply_filters( 'leaky_paywall_profile_your_profile_start', '' );

    $results .= '<p>';
    $results .= '<label class="lp-field-label" for="leaky-paywall-firstname">' . __( 'First name', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="text" class="issuem-leaky-paywall-field-input" id="leaky-paywall-firstname" name="firstname" value="' . $user->user_firstname . '" required>';
    $results .= '</p>';

    $results .= '<p>';
    $results .= '<label class="lp-field-label" for="leaky-paywall-lastname">' . __( 'Last name', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="text" class="issuem-leaky-paywall-field-input" id="leaky-paywall-lastname" name="lastname" value="' . $user->user_lastname . '" required>';
    $results .= '</p>';

    $results .= '<p>';
    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-email">' . __( 'Email', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="email" class="issuem-leaky-paywall-field-input" id="leaky-paywall-email" name="email" value="' . $user->user_email . '" required>';
    $results .= '</p>';

    //Get the gravity form details
		$data = array();
		$keys = array(
			'phone',
			'company',
			'country',
			'postcode',
			'job_title',
			'thirdy_part',
		);

		foreach( $keys as $key )  {
			$data[ $key ] = get_user_meta( $user->ID, $key, true );
		}

		// Get my company profile, the name could be different from the one registered?
		$args = array(
			'post_type' => 'partners',
			'author' => get_current_user_id(),
			'post_status' => array( 'publish', 'draft' ),
		);
		$company = get_posts( $args );

		if( $company ) {
			$data['company'] = $company[0]->post_title;
		}

		$results .= '<p>';
    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-company">' . __( 'Phone', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="text" class="issuem-leaky-paywall-field-input" id="leaky-paywall-company" name="phone" value="' . $data['phone'] . '">';
    $results .= '</p>';

		$results .= '<p>';

		// Edit profile link
		$edit = '';
		if( ba_is_client_user() || ba_is_resource_author() || ba_is_resource_partner() ) {
			$edit = ' (<a href="' . ba_get_my_profile_edit_link() . '">edit profile</a>)';
		}
    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-company">' . __( 'Company', 'issuem-le	aky-paywall' ) . $edit . '</label>';
    $results .= '<input type="text" class="issuem-leaky-paywall-field-input" id="leaky-paywall-company" name="company" value="' . $data['company'] . '" readonly>';
    $results .= '</p>';

		$results .= '<p>';
    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-role">' . __( 'Job title/role...', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="text" class="issuem-leaky-paywall-field-input" id="leaky-paywall-role" name="job_title" value="' . $data['job_title'] . '">';
    $results .= '</p>';
    $results .= '</p>';

    $results .= '<p>';
    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-password1">' . __( 'Password (minimum 8 characters)', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="password" class="issuem-leaky-paywall-field-input" id="leaky-paywall-password1" name="password1" value="" >';
    $results .= '</p>';

    $results .= '<p>';
    $results .= '<label class="issuem-leaky-paywall-field-label" for="leaky-paywall-gift-subscription-password2">' . __( 'Password (again)', 'issuem-leaky-paywall' ) . '</label>';
    $results .= '<input type="password" class="issuem-leaky-paywall-field-input" id="leaky-paywall-gift-subscription-password2" name="password2" value="" >';
    $results .= '</p>';

    $results .= wp_nonce_field( 'leaky-paywall-profile', 'leaky-paywall-profile-nonce', true, false );

    $results .= '<p class="submit"><input type="submit" id="submit" class="button button-primary" value="' . __( 'Update Profile Information', 'issuem-leaky-paywall' ) . '"  ></p>';

    $results .= '<p class="delete ' . $first_time_class . '"><input type="submit" name="delete" id="delete" class="button button-primary button-delete"><span>' . $expires . '</span><label for="delete" class="label-delete">' . __( 'Delete Account', 'issuem-leaky-paywall' ) . '</label></p>';
    $results .= '</form><div class="clear"></div>';
    $results .= apply_filters( 'leaky_paywall_profile_your_profile_end', '' );

    $results .= '<div class="issuem-leaky-paywall-subscriber-info">';
    $results .= '</div></div>';
    $results .= '</form>';

    if( $nm->get_user_level() <= 0 || isset( $_GET['subscriber']) ) {
      $class = $to_subscribe ? 'trigger-subscribe hidden' : '';
      $results .= '<div id="upgrade" class="' . $class . '"><p></p>';
			$results .= '<p>You are currently registered as a free member. Upgrade and become a subscriber for additional benefits.';
      $results .= '<h3>SUBSCRIBE</h3>';

      ob_start();
      do_shortcode( '[ba_register_list id="1"]' );
      $results .= ob_get_contents();
      ob_end_clean();

			if( isset( $_GET['subscribe'] ) ) {
				$results .= '<a href="#" class="button button-upgrade" onclick="document.querySelector(\'#option-' . intval($_GET['subscribe']) . ' form\').submit()">Udgrade</a>';
			} else {
        // $results .= '<a href="' . esc_url( home_url( '/register-subscribe/#subscribe' ) ) . '" class="button button-upgrade" target="_blank">Upgrade</a>';
        $results .= '<a href="' . esc_url( home_url( '/digital-subscription/?level_id=7' ) ) . '" class="button button-upgrade" target="_blank">Upgrade</a>';
        
        
			}

      $results .= '</div>';

      $results .= '<div class="hidden">';
      $results .= do_shortcode('[leaky_paywall_subscription]');
      $results .= '</div>';

    }
  } else {
    echo 'Please <a href="#" class="log-me-in">login</a> or <a href="' . home_url() . '/register-subscribe/">register</a> to access to this page.';
  }

  return $results;

}

function board_agenda_delete_confirm() {
  if( ! is_user_logged_in() ) return;
  if( isset( $_POST['confirm-delete'] ) ) {
    board_agenda_delete_done();

    return;
  }
?>
  <div class="profile-delete">
    <div class="download-error error">
      <form id="delete-account" action="" method="post">
        <input type="hidden" name="delete-confirm" value="1">
        <input type="hidden" name="delete" value="1">
        <?php echo wp_nonce_field( 'leaky-paywall-profile', 'leaky-paywall-profile-nonce', true, false ); ?>

      <p><?php the_field( 'delete_account_text', 'options'); ?></p>
      <?php
        $nm = Nine3_Simple_Membership::getInstance();

        if( $nm->is_active() ) :
      ?>
      <p>
        <input type="checkbox" name="confirm-delete" id="confirm-delete" value="1">
        <label for="confirm-delete">Confirm Deletion</label>
      </p>
      <p>
        <a href="#" class="button button-primary delete-account">DELETE ACCOUNT</a>
      </p>
      <?php endif; ?>
      </form>
    </div>
  </div>
<?php 
}

function board_agenda_delete_done() { ?>
  <div class="download-error">
    ACCOUNT DELETED
  </div>
<?php
  require_once(ABSPATH.'wp-admin/includes/user.php' );
  $current_user = wp_get_current_user();
  wp_delete_user( $current_user->ID );
}

add_shortcode( 'board_agenda_profile', 'board_agenda_leaky_paywall_profile' );

/**
 * Using wp_reset_password will logout the user, but we don't want this behaviour so we need to
 * automatically log-in after the password reset.
 * Due to genesis messyness we have to run this code before any header is sent otherwise we
 * cookies will be ignored.
 */
if ( ! empty( $_POST['leaky-paywall-profile-nonce'] ) ) {
  if( isset( $_POST['delete'] ) ) {
    board_agenda_delete_confirm();
    return;
  }
  if ( wp_verify_nonce( $_POST['leaky-paywall-profile-nonce'], 'leaky-paywall-profile' ) ) {

    try {
      $user_id = get_current_user_id();
      $user = get_userdata( $user_id );

      $args = array(
        'ID'           => $user_id,
        'user_login'   => $user->user_login,
        'user_login'   => $user->user_login,
        'display_name' => $user->display_name,
        'user_email'   => $user->user_email,
      );

      if ( !empty( $_POST['firstname'] ) ) {
        $args['first_name'] = $_POST['firstname'];
      }

      if ( !empty( $_POST['lastname'] ) ) {
        $args['last_name'] = $_POST['lastname'];
      }

      if ( !empty( $_POST['email'] ) ) {
        if ( is_email( $_POST['email'] ) ) {
          $args['user_email'] = $_POST['email'];
        } else {
          throw new Exception( __( 'Invalid email address.', 'issuem-leaky-paywall' ) );
        }
      }

      if ( !empty( $_POST['password1'] ) && !empty( $_POST['password2'] ) ) {
        if ( $_POST['password1'] === $_POST['password2'] && strlen( $_POST['password1'] ) >= 8 ) {
          wp_set_password( $_POST['password1'], $user_id );

      		// Send the notification to the user and sign in him/her again
      		board_agenda_send_mail( 'Reset Password', $user->name, $user->user_email, $user->user_login, $_POST['password1'], true );

      		// No longer needed to reset the password
      		delete_user_meta( $user->ID, '_need_to_reset_pwd' );

        } else {
          throw new Exception( __( 'Passwords do not match.', 'issuem-leaky-paywall' ) );
        }
      }

      // First time reset password.
      if ( !empty( $_POST['password3'] ) && !empty( $_POST['password4'] ) ) {
        if ( $_POST['password3'] === $_POST['password4'] && strlen( $_POST['password3'] ) >= 8 ) {
          // No longer needed to reset the password
          delete_user_meta( $user_id, '_need_to_reset_pwd' );
          update_user_meta( $user_id, '_show_thank_you', 1 );

          // Need to log-in the user again.
          wp_set_password( $_POST['password3'], $user_id );

          wp_clear_auth_cookie();
          wp_set_current_user( $user_id );
          wp_set_auth_cookie( $user_id );
        } else {
          throw new Exception( __( 'Passwords do not match.', 'issuem-leaky-paywall' ) );
        }
      }

      // wp_update_user( $args );

      $values = array(
        'company'   => $_POST['company'],
        'job_title' => $_POST['job_title'],
        'phone'     => $_POST['phone'],
      );
      foreach( $values as $key => $value ) {
        update_user_meta( $user_id, $key, $value );
      }

      if ( is_wp_error( $user_id ) ) {
        throw new Exception( $user_id->get_error_message() );
      } else {
        $user = get_userdata( $user_id ); //Refresh the user object
        $results .= '<p class="save">' . __( 'Profile Changes Saved.', 'issuem-leaky-paywall' ) . '</p>';
      }

    }
    catch ( Exception $e ) {
      header( 'Location: /profile?error=1' );

      die();
    }
  }

  header( 'Location: /profile' );
  die();
}
