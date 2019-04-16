 <?php
// Template Name: Register - Subscribe
add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );
add_action( 'genesis_before_footer', 'board_agenda_paypal_popup' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_news_sub_category' );
}

add_action('get_header','ba_change_genesis_sidebar');
function ba_change_genesis_sidebar() {
  remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); //remove the default genesis sidebar
  add_action( 'genesis_sidebar', 'ba_register_subscription_sidebar' ); //add an action hook to call the function for my custom sidebar
}

//Function to output my custom sidebar
function ba_register_subscription_sidebar() {
	dynamic_sidebar( 'register-subscribe-sidebar' );
}

function board_agenda_news_sub_category() {

  $magazine_cover_image = get_field( 'magazine_cover_image' , 'options' );

  ?>
	<div class="wrap top-bottom-margin">
    <div class="register__banner">
      <h2 class="register__banner__text"><?php the_content(); ?></h2>
    </div>

    <!--  Free registration -->
    <div class="register__columns equalize-parent">
      <div class="register__column">
        <div class="register__column__image equalize-me" data-compare=".equalize-me"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/register-free.png" alt="Register"></div>
        <button class="register__column__button">
          <span class="register__column__button__text">
            <span class="register__column__button__text__title">REGISTER</span>
            <span class="register__column__button__text__subtitle">sign up for FREE</span>
          </span>
          <span class="register__column__button__icon"><i class="fa fa-chevron-circle-right"></i></span>
        </button>

        <div class="register-form">
          <h3>Free registration entitles you to:</h3>

          <?php echo do_shortcode( '[ba_register_list id="0"]' ); ?>
          <?php
            $nm = Nine3_Simple_Membership::getInstance();

            if( is_user_logged_in() && $nm->get_user_level() <= 0 ) {
              echo '<div class="download-error">You are already registered. Upgrade and become a subscriber for additional benefits</div>';
            } else {
              echo do_shortcode( '[gravityform id="' . get_field( 'free_form_id' ) . '" title="false" description="false" ajax="true"]' );
            }
          ?>

        </div>
      </div>

      <!--  Subscribe registration -->
      <div class="register__column register__column--right">
        <div class="register__column__image equalize-me" data-compare=".equalize-me">
          <div class="register_magazine_wrapper">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/register-premium2.png" alt="Register" class="register_magazine_wrapper__image">
            <img src="<?php echo $magazine_cover_image['url']; ?>" alt="magazine_cover_image" class="register_magazine_image">
          </div>
        </div>
        <button class="register__column__button">
          <span class="register__column__button__text">
            <span class="register__column__button__text__title">SUBSCRIBE</span>
            <span class="register__column__button__text__subtitle">12 months unlimited access from £120</span>
            <span class="register__column__button__text__subtitle">(€188 EU/$205 ROW)</span>
          </span>
          <span class="register__column__button__icon"><i class="fa fa-chevron-circle-right"></i></span>
        </button>

        <div class="register-form">
          <h3>Unlimited access to all content:</h3>

          <?php echo do_shortcode( '[ba_register_list id="1"]' ); ?>

          <?php
            if( is_user_logged_in() && $nm->get_user_level() > 0 ) {
              echo '<div class="download-error">You are already registered.</div>';
            } else {
              echo do_shortcode( '[gravityform tabindex=1 id="' . get_field( 'premium_form_id') . '" title="false" description="false" ajax="true"]' );
						}
            ?>
                <div class="paypal">
                  <!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/uk/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/uk/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png" border="0" alt="PayPal Acceptance Mark"></a></td></tr></table><!-- PayPal Logo -->
                  <p>If you do not have a PayPal account you can process your payment as a PayPal ‘Guest’.</p>
                </div>
        </div>
      </div>

      <div class="clearfix"></div>
    </div>

	</div>

<?php }

function board_agenda_paypal_popup() { ?>
  <div id="paypal-popup" class="big-popup">Redirecting to PayPal</div>
<?php }

genesis();
