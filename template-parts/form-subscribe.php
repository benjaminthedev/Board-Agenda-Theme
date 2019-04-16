<div class="register__column register__column--right">
  <div class="register__column__image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/register-premium.png" alt="Register"></div>
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
