jQuery(document).ready(function($) {
  $(".single-product button.single_add_to_cart_button").after('<a href="#" class="add-request-quote-button button" rel="noopener noreferrer">Get a quote</>');
  $(".add-request-quote-button").after('<a href="javascript:void(0);"" class="callback" rel="noopener noreferrer">Get a Call Back</>');
  $(".add-to-wishlist-7235").before('<a href="http://canpromos.ca/request-a-quote/" target="_blank" class="add-request-quote-button button" style="margin-bottom:30px;display: block;" rel="noopener noreferrer">Get a quote</a>');
  $(".woocommerce-cart .checkout-button").before('<a href="http://canpromos.ca/shop/" class="fusion-button button-default fusion-button-default-size button fusion-update-cart" style="margin-top:10px;margin-bottom:0px;display: block;" rel="noopener noreferrer">Continue Shopping</a>');
  $(".home .add_to_cart_button").hide();
  $(".tax-product_cat .fusion-page-title-captions").after("<h2 class='heading-new'>Custom Designed Wholesale Promotional Products For Your Business!</h2>");
  console.log('Loaded Well.');
  /*
    
      1, get the id of the product
      2, get any variations 
      3, then add to basket
    
  */
  // $('input#gform_submit_button_3').click(function(e) {
  //   function addToCart() {
  //     const idNew = $('[name="add-to-cart"]').val();
  //     console.log(idNew);
  //   };
  // });

    $('input#gform_submit_button_3').click(function(e) {
        $('button.single_add_to_cart_button').trigger( "click" );
        console.log('send btn pushed');
    });

    $('button.single_add_to_cart_button').click(function(e){
      console.log('buy btn pushed.');
    });
});