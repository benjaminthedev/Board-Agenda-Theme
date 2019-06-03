/* Add your JavaScript code here.

If you are using the jQuery library, then don't forget to wrap your code inside jQuery.ready() as follows:

jQuery(document).ready(function( $ ){
    // Your code in here
});

--

If you want to link a JavaScript file that resides on another server (similar to
<script src="https://example.com/your-js-file.js"></script>), then please use
the "Add HTML Code" page, as this is a HTML code that links a JavaScript file.

End of comment */ 


jQuery(document).ready(function($){
	


    $(".single-product button.single_add_to_cart_button").after('<a href="#" class="add-request-quote-button button" rel="noopener noreferrer">Get a quote</>');
  
  	$(".add-request-quote-button").after('<a href="javascript:void(0);"" class="callback" rel="noopener noreferrer">Get a Call Back</>');
  
  	$('a.callback').click(function(e){
          console.log('Button Working Fine You Loser.');
         });
  
 /* $(".single-product button.single_add_to_cart_button").after('<a href="http://canpromos.ca/request-a-quote/" target="_blank" class="add-request-quote-button button" rel="noopener noreferrer">Get a quote</>'); */
  
 $(".add-to-wishlist-7235").before('<a href="http://canpromos.ca/request-a-quote/" target="_blank" class="add-request-quote-button button" style="margin-bottom:30px;display: block;" rel="noopener noreferrer">Get a quote</a>');
  
  $(".woocommerce-cart .checkout-button").before('<a href="http://canpromos.ca/shop/" class="fusion-button button-default fusion-button-default-size button fusion-update-cart" style="margin-top:10px;margin-bottom:0px;display: block;" rel="noopener noreferrer">Continue Shopping</a>');
  
  $( ".home .add_to_cart_button" ).hide();
  
  $(".tax-product_cat .fusion-page-title-captions").after("<h2 class='heading-new'>Custom Designed Wholesale Promotional Products For Your Business!</h2>");
  
  console.log('Loading you Twat');
  
    	$('a.callback').click(function(e){
          console.log('Button Working Fine You .');
         });
  

  
    $('input#gform_submit_button_3').click(function(e) {
          e.preventDefault();
          addToCart();
          return false;
       });    

       function addToCart(p_id) {
          $.get('/wp/?post_type=product&add-to-cart=' + p_id, function() {
             // call back
          });
    }
});

  
  
   
   });