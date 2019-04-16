jQuery(document).ready(function($) {
    // Show the login dialog box on click
    //Login popup
    $( '.log-me-in' ).click( function() {
			// User already logged in
			if( $( 'form#loginform' ).length === 0 ) return;

			$('.ba-popup').removeClass('visible');

      $( 'form#loginform input' ).removeAttr( 'disabled' ).removeClass( 'disabled' );
      $( 'form#loginform input[type="submit"]' ).fadeIn();

      $( '#login-popup #forgot' ).val( '' );
      $( '#login-popup .forgot' ).removeClass( 'disabled' );

      $( '#login-popup .close-popup' ).removeClass( 'visible' );
      $( '#login-popup .login-content' ).addClass( 'visible' );
      $( '#login-popup .forgot-content' ).removeClass( 'visible' );

      $( '#shade, #login-popup' ).addClass('visible');
      return false;
    });

    //Forgot password
    $( '#login-popup .forgot' ).click( function() {
      $( '#login-popup .forgot-content, #login-popup .login-content' ).toggleClass( 'visible' );

      //Set the forgot field
      $( '#login-popup #forgot' ).val( 1 );
    });

    // Perform AJAX login on form submit
    $('#login-popup form').on('submit', function(e) {
      //Validate the email
      var $email = ( $( '#login-popup #forgot' ).val() == 1 ) ? $( '#forgot-email' ) : $( '#user_login' );
      if( ! validateEmail( $email.val() ) ) {
        $( '#login-popup .login-message' ).html( 'Please insert a valid email' );
        $email.addClass( 'error' );

        return false;
      }
      //Password can't be blank
      if( $( '#login-popup #forgot' ).val() != 1
          && $( '#login-popup #user_pass' ).val().length < 8 ) {

          $( '#login-popup .login-message' ).html( 'Password must be at leat 8 characters' );
          $( '#login-popup #user_pass' ).addClass( 'error' );
          return false;
      }

      $( 'form#loginform input' ).attr( 'disabled', 'disabled' ).addClass( 'disabled' );
      $( 'form#loginform input[type="submit"]' ).fadeOut();
      $( '#login-popup .forgot' ).addClass( 'disabled' );
      $( '#login-popup input' ).removeClass( 'error' );

      $.ajax({
          type: 'POST',
          dataType: 'json',
          url: ajax_login_object.ajaxurl,
          data: {
              'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
              'email': $('form#loginform #user_login').val(),
              'forgot-email': $('form#loginform #forgot-email').val(),
              'password': $('form#loginform #user_pass').val(),
              'security': $('form#loginform #security').val() ,
              'forgot': $('form#loginform #forgot').val() },
          success: function(data){
              $( 'form#loginform .login-body.visible .login-message' ).text( data.message );

              if (data.loggedin == true){
                // Just refresh the page
                document.location.reload();
              } else {
                if( ! data.close ) {
                  $( 'form#loginform input' ).removeAttr( 'disabled' ).removeClass( 'disabled' );
                  $( 'form#loginform input[type="submit"]' ).fadeIn();
                  $( '#login-popup .forgot' ).removeClass( 'disabled' );
                } else {
                  $( 'form#loginform .close-popup' ).addClass( 'visible' );
                }
              }
          }
      });
      e.preventDefault();
    });

    // Profile PAGE, check the form fields before submit it
    $('form#board-agenda-profile').on('submit', function(e) {
      var $required = $('form#board-agenda-profile input[required]'),
          $pwd1 = $( '#leaky-paywall-password1' ),
          $pwd2 = $( '#leaky-paywall-password1' ),
          submit = true;

      $required.each( function() {
          if( $( this ).val().trim() == '' ) {
            submit = false;
            $( this ).addClass( 'error' );
          }
      });

      //Validate the email
      submit = submit && validateEmail( $('#leaky-paywall-email').val() );

      //Password match and longer than 8 characters
      if( $pwd1.val() != '' ) {
        if( $pwd1.val().length < 8 || $pwd1.val() != $pwd2.val() ) {
          $pwd1.addClass( 'error' );
          $pwd2.addClass( 'error' );

          submit = false;
        }
      }

      if( submit ) {
        return true;
      } else {
        e.preventDefault();

        return false;
      }
    });

    function validateEmail(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
    }

    $( document ).ready( function() {
      if( $( '#login-popup' ).hasClass( 'show-me' ) ) {
        $( '.log-me-in' ).trigger( 'click' );
      }
    });
});
