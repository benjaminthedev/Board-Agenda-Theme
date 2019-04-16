(function($){
  'use strict';
  var resizeTimeout;

  /*
   * Scroll (with animation) to the requested element
   *
   * Exapmle:
   *  $('#element').goTo();
   */
  $.fn.goTo = function() {
      $('html, body').animate({
          scrollTop: ($(this).offset().top - 20) + 'px' //20 is the gap we want with the top
      }, 'slow');
      return this; // for chaining...
  }

  var equalizeItems = function() {
    //Remove the 'forced' height
    $( '.equalize-me' ).css( { height: '' } );
    $( '.equalize-parent' ).each( function() {
      var maxHeight = {};

      $( this ).find( '.equalize-me' ).each( function() {
        var c = $( this ).data('compare');
        maxHeight[c] = Math.max( $( this ).height(), maxHeight[c] || 0 ); 
      });

      for( var key in maxHeight ) {
        $( this ).find( key ).css( { height: maxHeight[key] } );
      }
    });
  };

  //changing the selected alpha option on the search form
  $('.sup_alpha_label').click(function(e){

    //getting value
    var id = $(this).attr('for');
    var $input_element = $('#' + id);

    //disable all the options
    $('.sup_alpha_label').removeClass('alp_selected');

    //adding class to the selected options
    $(this).addClass('alp_selected');

  });

  //checking word limitation in the sign up form textarea
  $('.sng_textarea').keydown(function(e){

    //variables
    var text = $(this).val();

    var array_text = text.split(' ');

    //disable textarea if more than 200 words have already been written
    if(array_text.length >= 201){
      $(this).attr('disabled', 'disabled');
      $(this).addClass('sng_disabled');
    }
    else{
      $(this).removeAttr('disabled');
      $(this).removeClass('sng_disabled');
    }

  });

  //Login
  $( '#views-left .close, #login-popup .close, form#loginform .close-popup, .big-popup .close' ).click( function() {
    $( '#shade, #views-left, #login-popup, .big-popup' ).removeClass('visible');
    // $( '#shade, #views-left, #login-popup, .big-popup' ).fadeOut();
  });

  $('#register-popup .login a').click(function() {
      $('#register-popup').removeClass('visible');
  });

  //Submit the form on select change
  $( 'form.searchform select' ).change(function(){
    $( 'form.searchform' ).submit();
  });

  /*
   * In the archive page toggle the selected categories, when All
   * is pressed
   */
  $( '#resource-searchform input[type="checkbox"]' ).change( function() {
    var $this = $( this ),
    checked = $this.is( ':checked' );
    if( $this.attr( 'name' ) == 'all' ) {
      $( '#resource-searchform input[type="checkbox"]' ).attr( 'checked', checked );
    } else {
      //Uncheck the ALL, if I'm unchecking one category
      $( '#resource-searchform input[name="all"]' ).attr( 'checked', false );
    }
  });

  //Delete account
  $( '.delete-account' ).click( function() {
      var $check = $( '#delete-account input[type="checkbox"]' );

      if( $check.is(':checked') ) {
        $( '#delete-account' ).submit();
      }
  });

  // $( 'a[href^="#"]' ).click( function( e ) {
  //   var anchor = $( this ).attr( 'href' );
  //   if( anchor !== '#' ) {
  //     $( 'body, html' ).animate( { scrollTop: $( anchor ).position().top } );
  //   }
  //   e.preventDefault();
  // });

  //Go to the payment page
  if( $( '.trigger-subscribe' ).length > 0 ) {
    $( '.button-upgrade' ).trigger( 'click' );
  }

  $( document ).ready( function() {
    equalizeItems();

    if ($('#register-popup').hasClass('show')) {
      $('#shade,#register-popup').addClass('visible');
    }
  });

  $( window ).resize(function() {
    clearTimeout(resizeTimeout);
    console.log(resizeTimeout);

    resizeTimeout = setTimeout(function() {
      equalizeItems();
    }, 20);
  }).load( function() {
    //Is there a popup?
    if( $( '#views-left' ).length > 0 ) {
      $( '#shade, #views-left' ).addClass('visible');
    }

    equalizeItems();
  });

  $(document).ready(function() {
    if ($('.bxslider').length) {
      $('.bxslider').bxSlider({auto: true});
    }

    //ajax request to load more featured posts
    $('#all_featured, #all').click(function(e){

      console.log('Here we are');

      //disable link
      e.preventDefault();

      //changing link text
      $(this).html('Loading...');

      //getting data
      var ajax_url = $(this).data('ajax');
      var offset = $(this).data('offset');
      var type = $(this).data('type');
      var nonce = $(this).data('nonce');
      var pointer = $(this);

      //getting featured partners

      $.ajax({
        type: 'post',
        url : ajax_url,
        data : {
          action : 'load_featured_partners',
          offset : offset,
          type : type,
          nonce : nonce
        },
        success : function(e){

          //check if response contents the HTML code
          if(e !== ''){

            //hiding button
            pointer.fadeOut(400, 'swing', function(event){

              //appending content
              $('#featured_container').append(e);

            });
          }
          else
            pointer.html('No more posts to display');
        },
        error : function(e){
          pointer.html('No more posts to display');
        }
      });

    });

  });

  $('.resource-category').click(function(e) {
    e.stopPropagation();
    e.preventDefault();

    var $next = $(this).next();
    // var isOpen = $next.hasClass('visible');

    $('.resource-category-list').not($next).removeClass('visible');
    $next.toggleClass('visible');
  });

  $('*').click(function(e) {
    if ($('.resource-category-list.visible').length === 0) return;

    if ($(this).closest('.resource_form_element').length === 0) {
      $('.resource-category-list').removeClass('visible');
    } else {
      e.stopPropagation();
    }
  });

  $('.register__column__button').click(function() {
    $(this).parent().find('form').goTo();
  });

  /**
   * Events form onSubmit event.
   *
   * Don't want to show all the parameters in the url, if no filter have to be applied.
   */
  $('.events-filter-form').submit(function(e) {
    var $lists = $('.resource-category-list');

    $lists.each(function() {
      var $inputs = $(this).find('input');
      var $checked = $(this).find('input:checked');

      if ( $inputs.length === $checked.length ) {
        $inputs.prop('checked', '');
      }
    });

    return true;
  });

  /**
   * Scroll all the elements having a tag attribute inside the href one,
   * to the specified element using a nicely animation.
   *
   * Example:
   *
   *  <a href="#register_form">
   *  will scroll to the "register_form" element
   */
  $('a[href^="#"]').click(function(e) {
    var attr = $(this).attr('href');

    // Don't have to animate the 'href="#"' elements
    if( attr === '#' ) return true;

    $(attr).goTo();
    e.preventDefault();
    return false;
  });

  function showSubscriptionPrice() {
    var type = $('#gform_fields_5 input[type="radio"]:checked').val(); // Web & Print - Web Only option
    var currency = $('#input_5_19 option:selected').val();
    var year = $('#input_5_11 option:selected').val();

    $('.subscription-price-label').addClass('hidden');
    var $priceField = $('.price-' + type + '-' + year + '-' + currency);
    $priceField.removeClass('hidden strikethrough');

    // If does exist a offer field for the current selection, add strikertough
    // style to the $priceField, and show the offer as well.
    var $offerField = $('.offer-' + type + '-' + year + '-' + currency);
    if ( $offerField.length ) {
      $priceField.addClass( 'strikethrough' );
      $offerField.removeClass( 'hidden' );
    }
  }

  /**
   * Trigger the price change
   */
  $('body').on('change', '#gform_fields_5 input[type="radio"], #gform_fields_5 select', function() {
    // Show the price according to the selected options
    showSubscriptionPrice();
  });

  // Show the popup and shade on document load, if any
  $(document).ready(function() {
    if( $('.show-popup').length ) {
      $('.show-popup, #shade').addClass('visible');
    }
  });

	$(window).load(function() {
		// Scroll to the subscribe form
		if( window.location.hash === '#subscribe' ) {
			$('.register__column--right .register__column__button').trigger('click');
		}

    // Is there any promo-code? If so, select accordingly all the option to show it
    var promoCode = $('input[name="input_28"]').val();
    if ( promoCode ) {
      var $offerLabel = $('.subscription-offer').first();

      $('input[type="radio"][value="' + $offerLabel.data('type') + '"]').prop('checked', true);
      $('#input_5_11').val($offerLabel.data('id'));
      $('#input_5_19').val($offerLabel.data('currency'));
    }
    showSubscriptionPrice();
	});
})(jQuery);
