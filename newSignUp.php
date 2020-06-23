<?php
//* Template Name: Sign-up New

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



 </section>


genesis();
