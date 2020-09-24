<?php

//* Template Name: Custom Search

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );
add_action( 'genesis_before_footer', 'board_agenda_paypal_popup' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_custom_search' );

}


function board_agenda_custom_search(){ ?>







<style>
/* Will Add Local Styles in here.  */
</style>








<?php }

genesis();