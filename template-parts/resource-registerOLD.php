<?php $is_free = get_field( 'restriction' ) == 0; ?>
<div class="register-form">
  <h3><?php echo $is_free ? 'REGISTER FREE TO DOWNLOAD' : 'SUBSCRIBE TO DOWNLOAD' ?></h3>

  <?php
    if( $is_free ) {
      echo do_shortcode( '[gravityform id="12" title="false" description="false" ajax="true"]' );
    } else {
  ?>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  <?php
      echo '<a href="' . home_url() . '/register-subscribe">REGISTER</a>';
      // echo do_shortcode( '[gravityform id="5" title="false" description="false" ajax="true"]' );
    }
  ?>

</div>
