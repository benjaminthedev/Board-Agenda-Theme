 <?php
//* Template Name: Sign-up

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_news_sub_category' );

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);
}

function board_agenda_news_sub_category(){
    
  ?>

  <section class="wrap top-bottom-margin">
    <div class="signup__banner">
      <h2 class="front-title signup__banner__title"><?php the_title(); ?></h2>
      <p class="signup__banner__text"><?php the_content(); ?></p>
    </div>

    <!--  Free registration -->
    <div class="register__columns">
      <div class="register__column signup__column">
        <div class="signup__column__title">FREE basic profile</div>

        <div class="register-form signup-form">
          <ul class="register-list form-list">
            <?php while( have_rows( 'basic_keys' ) ) : the_row(); ?>
            <li><?php the_sub_field( 'item' ); ?></li>
            <?php endwhile; ?>
          </ul>
          <?php
            // Can't register more that 1 profile
            /*
						$args = array(
							'post_type' => 'partners',
							'author' => intval( get_current_user_id() ),
            );
            */
            

						if( is_user_logged_in() ) {
							/*
							 * If I already registered a profile, and I'm still a subscriber,
							 * it means that my profile has not been approved yet.
							 */
							if( ba_is_subscriber() ) : ?>
								<p class='ba-profile-exists'>Your profile is awaiting approval.</p>
              <?php else: 
                $profile = ba_get_my_profile();
                ?>
								<p class='ba-profile-exists'>Click <a href="<?php echo esc_url( ba_get_my_profile_edit_link() ); ?>">here</a> to edit your company profile.</p>
							<?php endif;
						} else {
							echo do_shortcode( '[gravityform id="8" title="false" description="false" ajax="true"]' );
						}
					?>
        </div>
      </div>

      <!--  Subscribe registration -->
      <div class="register__column signup__column register__column--right">
        <div class="signup__column__title">Enhanced Partner Profile</div>
        <div class="signup-form">
          <ul class="register-list form-list">
            <?php while( have_rows( 'enhanced_keys' ) ) : the_row(); ?>
            <li><?php the_sub_field( 'item' ); ?></li>
            <?php endwhile; ?>
          </ul>
          <p><?php the_field('purchase_text'); ?></p>
        </div>
      </div>

      <div class="clearfix"></div>
    </div>

  </section>

<?php }
// Inject the popup in the footer
/*
if( ! is_user_logged_in() ) {
  add_action('ba_footer', 'ba_signup_popup');
}
*/

function ba_signup_popup() { ?>
  <div id="register-popup" class="ba-popup show-popup">
    <div class="header">
      <div class="widget-title">BOARD AGENDA</div>
      <!-- <div class="close fa fa-times-circle-o"></div> -->
    </div>

    <div class="body">
      <div class="strong"><?php the_field('popup_title'); ?></div>
      <p><?php the_field('popup_paragraph'); ?></p>

      <a href="<?php echo esc_url( home_url('/sign-up/') ); ?>" class="button" hidden="hidden">REGISTER</a>
      <div class="account-login">
        Already have an account? <a href="#" class="login log-me-in">Log in</a> now
      </div>
    </div>
  </div>
  </div>
<?php }

genesis();
