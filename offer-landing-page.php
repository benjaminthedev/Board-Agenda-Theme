<?php
/**
 * Template name: Promo landing page
 */
//* Remove the default Genesis loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

//* Remove the post content (requires HTML5 theme support)
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

add_action( 'genesis_loop', 'ba_landing_page_content' );

function ba_landing_page_content() {
  $offer = ba_get_promotional_offer();
  $is_valid = is_object( $offer ) ? $offer->is_valid : false;

  $currencies = array(
    'uk'  => '£',
    'eu'  => '€',
    'row' => '$',
  );
?>
  <article class="entry">
    <div class="offer__header">
      <div class="offer__header__logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/popup-logo.png" alt=""></div>
      <div class="offer__header__expire">OFFER VALID UNTIL <?php echo get_field( 'end_date', $offer ); ?></div>
    </div>
    <header class="entry-header offer__titles">
      <h1 class="entry-title offer__title"><?php echo get_the_title( $offer ); ?></h1>
      <h2 class="offer__subtitle"><?php echo get_field( 'subtitle', $offer ); ?></h2>
    </header>
    <div class="content offer__content">
      <div class="offer__image">
        <?php echo get_the_post_thumbnail( $offer, 'offer-image' );  ?>
      </div>
      <div class="offer__text">
        <?php if ( $is_valid ): ?>
          <div class="offer__text__content">
            <?php echo wpautop( $offer->post_content ); ?>
          </div>

          <div class="offer__form-container">
            <div class="register-form offer__form">
              <?php echo do_shortcode( '[gravityform tabindex=1 id="5" title="false" description="false" ajax="true"]' ); ?>

              <div class="paypal">
                <!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.com/uk/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/uk/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png" border="0" alt="PayPal Acceptance Mark"></a></td></tr></table><!-- PayPal Logo -->
                <p>If you do not have a PayPal account you can process your payment as a PayPal ‘Guest’.</p>
              </div>

            </div>
          </div>
        <?php else: ?>
          Invalid promo code
        <?php endif; ?>
      </div>
    </div>
  </article>
<?php }

genesis();
