<?php
/**
 * http://www.jordancrown.com/multi-column-gravity-forms/
 *
 * "Split" the form in columns, as there is no default support for that.
 *
 * To split in column just add a "Section Break" with the 'gform_column' class.
 * Also add 'two-column' class to the form ( from Form Settings page ) .
 */
function gform_column_splits($content, $field, $value, $lead_id, $form_id) {
	if(IS_ADMIN) return $content; // only modify HTML on the front end

	$form = RGFormsModel::get_form_meta($form_id, true);
	$form_class = array_key_exists('cssClass', $form) ? $form['cssClass'] : '';
	$form_classes = preg_split('/[\n\r\t ]+/', $form_class, -1, PREG_SPLIT_NO_EMPTY);
	$fields_class = array_key_exists('cssClass', $field) ? $field['cssClass'] : '';
	$field_classes = preg_split('/[\n\r\t ]+/', $fields_class, -1, PREG_SPLIT_NO_EMPTY);

	// multi-column form functionality
	if($field['type'] == 'section') {

		// check for the presence of multi-column form classes
		$form_class_matches = array_intersect($form_classes, array('two-column', 'three-column'));

		// check for the presence of section break column classes
		$field_class_matches = array_intersect($field_classes, array('gform_column'));

		// if field is a column break in a multi-column form, perform the list split
		if(!empty($form_class_matches) && !empty($field_class_matches)) { // make sure to target only multi-column forms

			// retrieve the form's field list classes for consistency
			$ul_classes = GFCommon::get_ul_classes($form).' '.$field['cssClass'];

			// close current field's li and ul and begin a new list with the same form field list classes
			return '</li></ul><ul class="'.$ul_classes.'"><li class="gfield gsection empty">';
		}
	}

  if( $field->cssClass == 'captcha' ) {
    $field->placeholder = $GLOBALS['spam_question'];

    $content = str_replace( 'stop-spam', $GLOBALS['spam_question'], $content );
  }

	return $content;
}
add_filter('gform_field_content', 'gform_column_splits', 10, 5);

// https://www.gravityhelp.com/gravity-forms-v1-9-placeholders/
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

/**
 * Custom validation for "Phone" number fields.
 * We can't use the default GF validation, but still want to force
 * the user to insert a valid phone number.
 *
 * Valid number format:
 *    00....
 *    +...
 */
function ba_validate_phone_number( $result, $value, $form, $field ) {
	if ( $result['is_valid'] && ! preg_match('/^[0-9\s]*$/', $value) ) {
			$result['is_valid'] = false;
			$result['message'] = 'Please insert a valid phone number';
	}

	return $result;
}

add_filter( 'gform_field_validation_12_11', 'ba_validate_phone_number', 10, 4 );
add_filter( 'gform_field_validation_5_15', 'ba_validate_phone_number', 10, 4 );
add_filter( 'gform_field_validation_8_4', 'ba_validate_phone_number', 10, 4 );

add_filter( 'gform_pre_render_12', 'populate_posts' );
add_filter( 'gform_pre_render_5', 'populate_posts' );
add_filter( 'gform_pre_render_8', 'populate_posts' );
function populate_posts( $form ) {

    foreach ( $form['fields'] as &$field ) {
        if ( $field->type != 'select' || strpos( $field->cssClass, 'phone-prefix' ) === false ) {
            continue;
        }

        // Get the list for the json file, downloaded from: http://data.okfn.org/data/core/country-codes/r/country-codes.json
        /**
         * Tried to import in gravity form, but, during the bulk import,
         * can't separate the name from the values.
         */
        $content = file_get_contents( dirname(__FILE__) . '/country-codes.json' );
        $json = json_decode( $content );

        $choices = array();

        foreach ( $json as $item ) {
          $text = sprintf("%s (+%s)", $item->name, $item->Dial);
            $choices[] = array( 'text' => $text, 'value' => $item->Dial );
        }

        // update 'Select a Post' to whatever you'd like the instructive option to be
        $field->placeholder = 'Select a prefix';
        $field->choices = $choices;
    }

    return $form;
}

/**
 * Sign up page 'submission hook'
 *
 * User can upgrade to Client / Partner profile, only after he has
 * been registered as Free or Premium user.
 * Also, when Free user upgrade to Free Partner, his role change have to be
 * approved by admins, in order to avoid SPAMMERS. While, Premium user are
 * automatically upgrade to Client User, when and if requested.
 *
 * Also need to use the inserted data to create his own Company profile page
 */
function ba_upgrade_user_role( $entry, $form ) {
	global $nine3_Membership;

	$nine3 = $nine3_Membership::getInstance();

	// Only premium user are eligible to straightly create a free partner account
	$post_status = $nine3->has_premium_subscription() ? 'publish' : 'draft';

	// create user
	$user_name = rgar( $entry, 24 );
	$user_login = gf_next_username();
	$user_args = array(
		'user_pass' 	=> wp_generate_password( 10, true ),
		'user_login' 	=> $user_login,
		'user_email' 	=> rgar( $entry, 29 ),
		'user_nicename' => $user_name,
		'display_name'	=> $user_name,
		'first_name' 	=> $user_name,
		'last_name' 	=> rgar( $entry, 25 ),
	);
	$user_id = wp_insert_user( $user_args );
	error_log( print_r( $user_id , true ) );
	error_log( print_r( $user_args, true ) );

	if ( is_wp_error( $user_id ) ) {
		return false;
	}
	

	// Create post object
	$partner = array(
	  'post_title'    => wp_strip_all_tags( rgar($entry, 2) ),
	  'post_content'  => substr(rgar($entry, 19), 0, 200),
	  'post_status'   => $post_status,
	  'post_author'   => $user_id,
	  'post_type' 	=> 'partners'
	);

	// Insert the post into the database
	$post_id = wp_insert_post( $partner );

	// Update the meta fields
	$website = rgar($entry, 28);
	if (substr($website, 0, 4) != 'http') $website = 'http://' . $website;
	update_post_meta($post_id, 'website', $website);
	update_post_meta($post_id, 'email', rgar($entry, 5));
	update_post_meta($post_id, 'country', rgar($entry, 12));
	update_post_meta($post_id, 'company_description', substr(rgar($entry, 19), 0, 200));
	update_post_meta($post_id, 'company_expertise', rgar($entry, 15));
	update_post_meta($post_id, 'area_of_expertise', rgar($entry, 16));
	update_post_meta($post_id, 'industry_sector_expertise', rgar($entry, 17));

	// Needed to perform the counting in the "Notifications" list page
	update_post_meta($post_id, 'has_notification', 1);

	// Addresses are stored as repeater, for Resource Partner
	$address = array(
		'address' => rgar($entry, 22),
		// 'address_line_2' => rgar($entry, 10),
		'phone' => rgar($entry, 4),
		'postcode' => rgar($entry, 13),
	);

	/*
	 * Can't use the "addresses" key, acf doesn't like it... Bah
	 * To know the key just inspect the HTML and find the data-key for the
	 * field needed.
	 * Also Client Partner and Resource Partner have 2 different field_key, so
	 * need to update both...
	 * PS: Tried get_field_object, but doesn't work...
	 */
	update_field('field_5810a9af93d2e', array( $address ), $post_id);
	update_field('field_57c856ed9736e', array( $address ), $post_id);

	// Assign the company to the new user
	update_user_meta( $user_id, '_partner', $post_id );

	// Notify the admin on the new "upgration request"
	board_agenda_send_mail( 'FREE basic profile request', '', get_option('admin_email'), null, null, false );

	// Notify the user that is request has been made
	$current_user = get_user_by( 'ID', $user_id );
	$mail = $current_user->user_email;
	board_agenda_send_mail( 'FREE basic profile submission', '', $mail, null, null, false );

	$message_key = $nine3->has_free_subscription() ? 'free_message' : 'premium_message';
	$message = get_field($message_key, 'options');
	ba_add_admin_notification('Free basic profile', $message, $current_user );

	// Can I upgrade the user to "Client Partner"?
	if( $nine3->has_premium_subscription() ) {
		$userdata = array(
			'ID' => $user_id,
			'role' => 'client_user'
		);

		wp_update_user( $userdata );

		// Inform about the upgrade has been approved
		// board_agenda_send_mail( 'FREE basic profile', '', '', null, null, false );
	}
}

add_action( 'gform_after_submission_8', 'ba_upgrade_user_role', 10, 2 );

//Generate random username
function gf_next_username( $latest = null ){
	global $wpdb;

	if( null == $latest ) {
		$latest = $wpdb->get_var( "SELECT MAX(user_login) FROM $wpdb->users WHERE LEFT(user_login, 3) = 'ba_'" );
		@list( $null, $latest ) = explode( "_", $latest );
	}

	$latest = intval( $latest ) + 1;

	//Check if it already exists, just to be sure
  $username = 'ba_' . $latest;
  // while ( username_exists( $username ) ){
	if( username_exists( $username ) ) {
    return board_agenda_next_username( $latest );
	}

  return $username;
}

/**
 * Check if the email already exists, but only if the user
 * is not logged in
 */
function gf_check_email( $validation_result ) {
	$form = $validation_result['form'];

	if( is_user_logged_in() ) return $validation_result;

	$exists = email_exists( rgpost( 'input_27' ) );
	if( $exists ) {
	  // set the form validation to false
	  $validation_result['is_valid'] = false;

		//finding Field with ID of 1 and marking it as failed validation
		foreach( $form['fields'] as &$field ) {

				if ( $field->id == '27' && ! is_user_logged_in() ) {
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

add_filter( 'gform_validation_8', 'gf_check_email' );
