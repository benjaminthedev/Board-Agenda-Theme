
jQuery(document).ready(function ($) {





    $(".single-product button.single_add_to_cart_button").after('<a href="#" class="add-request-quote-button button" rel="noopener noreferrer">Get a quote</>');

    $(".add-request-quote-button").after('<a href="javascript:void(0);"" class="callback" rel="noopener noreferrer">Get a Call Back</>');

    $('a.callback').click(function (e) {
        console.log('Button Working Fine You Loser.');
    });

    /* $(".single-product button.single_add_to_cart_button").after('<a href="http://canpromos.ca/request-a-quote/" target="_blank" class="add-request-quote-button button" rel="noopener noreferrer">Get a quote</>'); */

    $(".add-to-wishlist-7235").before('<a href="http://canpromos.ca/request-a-quote/" target="_blank" class="add-request-quote-button button" style="margin-bottom:30px;display: block;" rel="noopener noreferrer">Get a quote</a>');

    $(".woocommerce-cart .checkout-button").before('<a href="http://canpromos.ca/shop/" class="fusion-button button-default fusion-button-default-size button fusion-update-cart" style="margin-top:10px;margin-bottom:0px;display: block;" rel="noopener noreferrer">Continue Shopping</a>');

    $(".home .add_to_cart_button").hide();

    $(".tax-product_cat .fusion-page-title-captions").after("<h2 class='heading-new'>Custom Designed Wholesale Promotional Products For Your Business!</h2>");

    console.log('Loaded Well.');

    $('a.callback').click(function (e) {
        console.log('Button Working.');
    });


    /*
      
        1, get the id of the product
        2, get any variations 
        3, then add to basket
    
    
    
    */

    $('input#gform_submit_button_3').click(function (e) {

        function addToCart() {
            const idNew = $('[name="add-to-cart"]').val();

            console.log('this is it' + idNew);


            $.get('/wp/?post_type=product&add-to-cart=' + idNew, function () {
                // call back
            });
        };



        // console.log('Bath Time');


        //console.log($('[name="add-to-cart"]').val());



        // function addToCart() {
        //    $.get('/wp/?post_type=product&add-to-cart=' + $('[name="add-to-cart"]').val();, function() {
        //       // call back
        //       console.log('tryimg');
        //    };


    });


    (function ($) {

        $(document).on('click', 'input#gform_submit_button_3', function (e) {
            e.preventDefault();

            var $thisbutton = $(this),
                $form = $thisbutton.closest('form.cart'),
                id = $thisbutton.val(),
                product_qty = $form.find('input[name=quantity]').val() || 1,
                product_id = $form.find('input[name=product_id]').val() || id,
                variation_id = $form.find('input[name=variation_id]').val() || 0;

            var data = {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };

            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

            $.ajax({
                type: 'post',
                url: wc_add_to_cart_params.ajax_url,
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading');
                },
                success: function (response) {

                    if (response.error & response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    }
                },
            });

            return false;
        });
    })(jQuery);







});
