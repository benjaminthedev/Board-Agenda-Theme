<?php
/**
 * Validation successfully, add the user using the wp function
 *
 */
function board_agenda_register_user( $confirmation, $form, $entry, $ajax ) {
	global $wpdb, $wp_hasher;
	$mailTemplate = 'Free';
	$email = $_POST['input_3'];
	$password = '';
	$is_register = $form['id'] === 12; // Register free form

	if( ! is_user_logged_in() ) {
		$username = sanitize_title( $email );
		$password = wp_generate_password( 10, true, true );

		$user_id = wp_create_user( $username,
										$password,
										$email );

		// No longer needed to reset the password
		update_user_meta( $user_id, '_need_to_reset_pwd', true );

	} else {
		$current_user = wp_get_current_user();

		$username = $current_user->user_login;
		$user_id = $current_user->ID;
	}

	$data = array(
		'ID' => $user_id,
		'display_name' => $_POST['input_1'] . ' ' . $_POST['input_2'],
		'first_name' => $_POST['input_1'],
		'last_name' => $_POST['input_2'],
	);

	wp_update_user( $data );

	// Add new notification for the registered user.
	ba_add_admin_notification( 'New account', 'New account registration', $user_id );

	/**
	 * Extra gravity form fields
	 */
	$values = array(
		'phone_prefix' => $is_register ? $_POST['input_18'] : $_POST['input_27'],
		'phone' => $is_register ? $_POST['input_11'] : $_POST['input_15'],
		'company' => $_POST['input_5'],
		'country' => $_POST['input_14'],
		'postcode' => $_POST['input_13'],
		'job_title' => $is_register ? $_POST['input_14'] : $_POST['input_22'],
		'thirdy_part' => $_POST['input_7_1'],
	);

	foreach( $values as $key => $value ) {
		update_user_meta( $user_id, $key, $value );
	}
	update_user_meta( $user_id, '_gravity_fields', $_POST );

	/**
	 * For the registration page I used gravity form + some bespoke
	 * php code, as leaky_paywall is too complex to customise.
	 * So, I got some code from the original plugin to register the user.
	 * But, We use paywall for the payment and recurring part...
	 *
	 * The level id is specified as chouces value, inside the gravity Form
	 * http://boardagenda.com/wp-admin/admin.php?page=gf_edit_forms&id=5
	 */
	$level_id = $_POST['input_10'];
	$level    = get_leaky_paywall_subscription_level( $level_id );
	$userdata = array(
		'user_login'		=> $username,
		'user_email'		=> $email,
		'first_name'		=> $data['first_name'],
		'last_name'			=> $data['last_name'],
		// 'user_pass'			=> $key,
		'user_registered'	=> date_i18n( 'Y-m-d H:i:s' ),
	);

	leaky_paywall_email_subscription_status( $user_id, 'new', $userdata );

	$is_free = $level['price'] == 0;
	$args = array(
		'level_id' 			=> $level_id,
		'subscriber_id' 	=> '',
		'subscriber_email' 	=> $email,
		'price' 			=> $level['price'],
		'description' 		=> $level['label'],
		'payment_status' 	=> 'active',
		'interval' 			=> $level['interval'],
		'interval_count' 	=> $level['interval_count'],
		'site' 				=> @$level['site'],
	);

	if( $is_free ) {
		$args['payment_gateway'] 	= 'free_registration';
		update_user_meta( $user_id, '_is_free', 1 );
	} else {
		$args['payment_gateway'] 	= 'paypal_standard';
		$args['payment_status'] 	= 'deactivated';

		//Set the plan as exipred
		$args['expires'] 	= date_i18n( 'Y-m-d H:i:s' );

		$mailTemplate = 'PayPal';
	}

	// Send the email to the user
	board_agenda_send_mail( $mailTemplate, $_POST['input_1'], $_POST['input_3'], $username, null, true );

	//Mimic PayPal's Plan...
	if ( !empty( $level['recurring'] ) && 'on' == $level['recurring'] )
		$args['plan'] = $level['interval_count'] . ' ' . strtoupper( substr( $level['interval'], 0, 1 ) );

	$args['subscriber_email'] = $email;
	leaky_paywall_update_subscriber( NULL, $email, 'free-' . time(), $args );

	return $confirmation;
}

/**
 * When the paypal payment is made, leaky_paywall doesn't
 * send any mail to the user.
 * So why this code.
 */
function board_agenda_subscriber_mail( $user_id, $email, $meta, $customer_id = null, $meta_args = null ) {
	if( $meta['level_id'] > 0 && $meta['payment_status'] == 'active' ) {
		$user = get_user_by( 'id', $user_id );

		$mailTemplate = ( get_user_meta( $user_id, '_is_free', true ) == 1 ) ? 'Subscribe' : 'Subscribe';

		board_agenda_send_mail( $mailTemplate, $user->display_name, $email, $user->user_nicename );

		update_user_meta( $user_id, '_is_free', 0 );
	}
}

add_action( 'leaky_paywall_update_subscriber', 'board_agenda_subscriber_mail', 10, 3 );

/**
 * PayPal payment cancel, set the user as _is_free
 *
 */
function board_angeda_payment_cancelled() {
	if( isset( $_GET['issuem-leaky-paywall-paypal-standard-cancel-return'] ) ) {
		update_user_meta( $user_id, '_is_free', 1 );
	}
}

add_action( 'init', 'board_angeda_payment_cancelled' );

/**
 * PayPal IPN send back the payeer email, that could be different
 * from the user one.
 * So I need to send a custom value, to keep track of the original
 * user ID.
 *
 * BUG: TRY TO SEND / GET the customer_id from PayPal
 */
function ba_field_user_id( $result ) {
	$result .= '<input type="hidde" name="customer_id" value="999" >';

	return $result;
}

add_filter( 'leaky_paywall_subscription_options_after_subscription_options', 'ba_field_user_id' );

/**
 * Send the email to the registered/subscribed user
 *
 * @param $type the meta value
 * @param $uname
 */
function board_agenda_send_mail( $type, $uname, $email, $username = null, $key = null, $login = true ) {
	global $wpdb;

	//The post is private, can't access if not logged in...
	$query = "SELECT * FROM $wpdb->posts INNER JOIN $wpdb->postmeta on ID = post_id WHERE post_type='mail' AND meta_value = '{$type}'";
	$post = $wpdb->get_row($query);
	if( $post ) {
		$body = $post->post_content;

		//Custom strings to be replaced
    $user_data = get_user_by('email', $email);

    if ( $key === null ) {
      $key = get_password_reset_key( $user_data );
    }
    $reset_link = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_data->user_login), 'login');

		$replace = array(
			'username' => $uname,
			'email' => $email,
			'resources' => admin_url('/edit.php') . '?post_type=resources',
			'profile' => '<a href="' . $reset_link . '">' . $reset_link . '</a>',
		);

		foreach( $replace as $k => $value ) {
			$body = str_replace( '%' . $k, $value, $body );
		}

		add_filter( 'wp_mail_content_type', 'ba_set_html_content_type' );
		add_filter( 'wp_mail_from', 'ba_set_mail_from' );
		add_filter( 'wp_mail_from_name', 'ba_set_mail_from_name' );

		$status = wp_mail($email, get_field( 'subject', $post->ID ), wpautop( $body ) );
	}

	if($login) {
		//Sign In the user
		$info = array();
		$user = get_user_by( 'email', $email );

		wp_clear_auth_cookie();
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID, true );
	}

	remove_filter( 'wp_mail_content_type', 'ba_set_html_content_type' );
}

function ba_set_mail_from( $from ) {
	return 'info@boardagenda.com';
}

function ba_set_mail_from_name( $from ) {
	return 'Board Agenda';
}

// /leaky_paywall_update_subscriber_meta
function ba_set_html_content_type() {
	return 'text/html';
}

//Generate random username
function board_agenda_next_username( $latest = null ) {
	global $wpdb;

	if( null == $latest ) {
		$latest = $wpdb->get_var( "SELECT MAX(user_login) FROM $wpdb->users WHERE LEFT(user_login, 3) = 'ba_'" );
		@list( $null, $latest ) = explode( "_", $latest );
	}

	$latest = intval( $latest ) + 1;

	//Check if it already exists, just to be sure
	$username = 'ba_' . $latest;

	if( username_exists( $username ) ) {
		return board_agenda_next_username( $latest );
	}

  return $username;
}

//Check if the mail address already exists
add_filter( 'gform_validation_12', 'board_agenda_check_email' );
add_filter( 'gform_validation_5', 'board_agenda_check_email' );

add_filter( 'gform_confirmation_12', 'board_agenda_register_user', 10, 4 );
add_filter( 'gform_confirmation_5', 'board_agenda_register_user', 10, 4 );

/*
 * Set the level dynamically, so will be easier to add more subscriptions
 * without change any code.
 *
 * The paywall level field have to start with:
 *	pw_subscription_
 *
 * followed by the subsribtion name ( in lowercase ), that have to match the paywall one.
 *
 * Example: pw_subscription_free
 */
function board_agenda_dynamic_subscription_level( $value, $field, $name ) {
	global $nine3_Membership;

	if( strpos( $name, 'pw_subscription_' ) === FALSE ) return $value;
	$name = strtolower( str_replace('pw_subscription_', '', $name) );

	$levels = $nine3_Membership->get_levels();
	foreach( $levels as $key => $level ) {
		$label = strtolower( strtolower( $level['label'] ) );

		if( $label == $name ) {
				return $key;
		}
	}

	return $value;
}

add_filter( 'gform_field_value', 'board_agenda_dynamic_subscription_level', 10, 3 );

//Retrieve the info from the logged user
function board_agenda_dynamic_user_name( $value ) {
	global $current_user;
	get_currentuserinfo();

	return $current_user->first_name;
}

function board_agenda_dynamic_user_lastname( $value ) {
	global $current_user;
	get_currentuserinfo();

	return $current_user->last_name;
}

function board_agenda_dynamic_user_email( $value ) {
	global $current_user;
	get_currentuserinfo();

	return $current_user->user_email;
}

add_filter( 'gform_field_value_user_name', 'board_agenda_dynamic_user_name' );
add_filter( 'gform_field_value_user_lastname', 'board_agenda_dynamic_user_lastname' );
add_filter( 'gform_field_value_user_email', 'board_agenda_dynamic_user_email' );


/**
 * Show "views left" popup when less than 2, but only for
 * non registered users
 */
function board_angeda_view_left( $content ) {
	if( is_user_logged_in() ) return $content;

	if ( ! empty( $_COOKIE['issuem_lp'] ) ) {
		$available_content = maybe_unserialize( stripslashes( $_COOKIE['issuem_lp'] ) );
	}

	//If the key doesn't exists, means that no limits are applied to this post type
	$post_type = get_post_type();
	if( isset( $available_content[ $post_type ] ) ) {
		//How many can I read?
		$restrictions = leaky_paywall_subscriber_restrictions();

		$allowed = $restrictions[0]['allowed_value'];
		$left = $allowed - count( $available_content[ $post_type ] );
		// if( $left <= 3 && $left > 0 ) {
		if( $left === 0 ) {
?>
			<div id="views-left">
				<div class="header">
					<div class="widget-title">BOARD AGENDA</div>
					<div class="close fa fa-times-circle-o"></div>
				</div>

				<div class="body">
					<div class="strong"><?php echo $left ?> unregistered article views left.</div>
					<p>Register free for 30 article view per week and unlimited resource downloads.</p>

					<a href="<?php echo esc_url( home_url() ) ?>/register-subscribe" class="button">REGISTER</a>
				</div>
			</div>
<?php
		}
	}

	remove_filter( 'the_content', 'board_angeda_view_left', 99 );
	return $content;
}

add_filter( 'genesis_footer', 'board_angeda_view_left', 99 );

// User can't access to the resource
add_filter( 'wp_footer', 'board_angeda_register_poup', 0 );

function board_angeda_register_poup() { ?>
	<div id="register-popup" class="big-popup <?php echo isset($_GET['denied']) ? 'show' : '' ?>">
		<div class="header">
			<div class="widget-title">BOARD AGENDA</div>
			<div class="close fa fa-times-circle-o"></div>
		</div>

		<div class="body">
			<div class="strong title">Sorry, this report is only available for registered users.</div>
			<p>Register free to download designated resources, or subscribe for unlimited access and exclusive content.</p>

			<a href="<?php echo esc_url( home_url() ) ?>/sign-up" class="button">REGISTER</a>
			<a href="<?php echo esc_url( home_url() ) ?>/sign-up" class="button">SUBSCRIBE</a>

			<p class="login">Already have an account? <a href="#" class="log-me-in">Log in</a> now</p>
		</div>
	</div>
<?php }

/**
 * LOGIN
 */
//Customize the login logo
function board_agenda_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png);
						width: 100%;
				    height: 100%;
				    background-size: 80% auto;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'board_agenda_login_logo' );

function board_agenda_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'board_agenda_login_logo_url' );

function board_agenda_ajax_login_init () {
    wp_enqueue_script('ajax-login-script');
}

// Enable the user with no privileges to run ajax_login() in AJAX
add_action( 'wp_ajax_nopriv_ajaxlogin', 'board_agenda_ajax_login' );

function board_agenda_ajax_login() {
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
		if( $_POST['forgot'] == 1 ) {
      // Fake is set as we don't have to reset the current password, just send
      // him the reset password link.
			$success = ba_retrieve_password_by_login( $_POST['forgot-email'], false, 'fake' );

			echo json_encode(
			array(
				'loggedin'=>false,
				'message'=> 'We have sent you an email with a link to reset your password.',
				'close' => true,
			 	'reset' => true,
				)
			);
		} else {
	    $info = array();
			$user = get_user_by( 'email', $_POST['email'] );
	    $info['user_login'] = $user->user_login;
	    $info['user_password'] = $_POST['password'];
	    $info['remember'] = true;

	    $user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
	        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password..') ));
	    } else {
	        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
	    }
		}

    die();
}

/**
 * Redirect the wp-login.php to the custom login form
 *
 * User created through register page can't access via /wp-admin
 * As they don't know their login username
 */
function board_agenda_login_redirect() {
	global $pagenow;

	if( 'wp-login.php' == $pagenow
		&& ! isset( $_GET['reauth'] )
		&& ! isset( $_GET['action'] )
		&& ! isset( $_GET['wpe-login'] ) ) {

		$url = add_query_arg( array( 'login' => 1 ), home_url() );
		wp_redirect( esc_url( $url ) );
		exit();
	}

	// Deny the /wp-admin/ for the subscriber
	if( is_user_logged_in()
			&& stripos( $_SERVER['REQUEST_URI'], '/wp-admin/' )
			&& 'index.php' == $pagenow
			&& ! current_user_can('edit_posts')
			&& ! current_user_can('read_client_user_cap') ) {
			wp_redirect( esc_url( home_url() ) );
			exit();
	}

	remove_action( 'init', 'board_agenda_login_redirect' );
}

add_action( 'init', 'board_agenda_login_redirect' );

/**
 * Hide the admin bar for the subscribers
 */
if ( ! current_user_can('edit_posts') ) {
 add_filter('show_admin_bar', '__return_false');
}


/**
 * For security, hide the standard login error message
 *
 */
function board_agenda_login_errors( $error ) {
	if( stripos( 'invalid_username', $error ) )
		return "Invalid username or password";
	else
		return $error;
}
add_filter('login_errors', 'board_agenda_login_errors' );

/**
 * Force the minimum password length
 */
function ba_password_min_length_check( $errors, $user) {
    if( isset( $_POST['pass1'] ) && strlen($_POST['pass1']) < 8 )
	    $errors->add( 'password_too_short', 'ERROR: Password needs to be minimum 8 characters.' );
}

add_action( 'validate_password_reset', 'ba_password_min_length_check', 10, 2 );

/**
 * Add the "Category" column to the Resources and Partners list
 */
add_action( 'admin_init', 'board_angeda_category_column', 10 );

/**
 * Handles sending password retrieval email to user.
 *
 * @uses $wpdb WordPress Database object
 * @param string $user_login User Login or Email
 * @return bool true on success false on error
 */
function ba_retrieve_password_by_login($user_login, $login = true, $userpass = null) {
    global $wpdb, $current_site;

    $user_data = get_user_by( 'email', trim( $user_login ) );
    if ( empty( $user_data ) )
       return false;

    if ( !$user_data ) return false;

    // redefining user_login ensures we return the right case in the email
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

		$key = $userpass;
		if( is_null( $userpass ) ) {
			$key = wp_generate_password(20, false);
			wp_set_password( $key, $user_data->ID );
		}

		board_agenda_send_mail( 'Reset Password', $user_data->name, $user_email, $user_data->user_login, $key , $login);

    return true;
}

/**
 * Workaround for Paypal IPN
 *
 * For some reason Paypal or Paywall append -1 at the end of the user email,
 * so the $_REQUEST looks like:
 *
 * [mc_gross] => 0.01
 * [custom] => senesealessandro+25@gmail.com-1
 * [charset] => windows-1252
 *
 * Trying to ask support to the Paywall plugin, but in the meanwhile this code
 * is needed to "fix" the anomaly.
 * The workaround just strip the -## at the end of the 'custom' email parameter.
 *
 * PS: The 'custom' contains the email of the user that need to be activated,
 * as it can be different from the user that pay through paypal.
 */
function ba_paypal_ipn_request() {
	if( ! isset( $_GET['listener'] ) || strtoupper( $_GET['listener'] ) != 'IPN' ) {
		return;
	}

	if( isset($_REQUEST['custom'] ) ) {
		list($name, $website) = explode('@', $_REQUEST['custom']);
		$website = preg_replace("/-\d*$/", "", $website);

		$_REQUEST['custom'] = "{$name}@{$website}";
	}
}

add_action('after_setup_theme', 'ba_paypal_ipn_request', 0);

/**
 * Need to have 2 different tabindex for the forms in the "register-subscribe"
 * page, to allow the user to jump to the next field using the tab key.
 * By default the 2 forms have the same id, and this cause the user to go
 * through to the fields of both forms, when hitting the tab key, so doesn't
 * go to the next field of the same field, but go to the next field of the other
 * for first.
 */
function ba_gravity_form_tabindex( $tab_index, $form ) {
	$index = isset( $_GLOBALS['_tabindex'] ) ? intval($GLOBALS['_tabindex']) + 100 : 0;
	$GLOBALS['_tabindex'] = $index;

	return $index;
}

add_filter( 'gform_tabindex', 'ba_gravity_form_tabindex', 10, 2 );


/**
 * Check if the email already exists, but only if the user
 * is not logged in
 */
function board_agenda_check_email( $validation_result ) {
	$form = $validation_result['form'];

	if( is_user_logged_in() ) return $validation_result;

	$exists = email_exists( rgpost( 'input_3' ) );
	if( $exists ) {
	  // set the form validation to false
	  $validation_result['is_valid'] = false;

		//finding Field with ID of 1 and marking it as failed validation
		foreach( $form['fields'] as &$field ) {

				if ( $field->id == '3' && ! is_user_logged_in() ) {
						$field->failed_validation = true;
						$field->validation_message = 'Email address already exists!';
						break;
				}
		}
	}

  //Assign modified $form object back to the validation result
  $validation_result['form'] = $form;
  return $validation_result;
}

// Need to show "Upgrade", instead of the default submit text
add_filter( 'gform_submit_button_5', 'ba_change_submit_text', 10, 2 );

function ba_change_submit_text($button, $form) {
	if( ! is_user_logged_in() ) return $button;

	return "<button class='button' id='gform_submit_button_{$form['id']}'>Upgrade</button>";
}
