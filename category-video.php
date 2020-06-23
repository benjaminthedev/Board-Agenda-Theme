<?php

//* Template Name: Videos



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

function board_agenda_news_sub_category(){ ?>


    <br />
    <br />

<?php $query_args = array(
	
    'cat' => '6814',   
            
);

// The Query
 $the_query = new WP_Query( $query_args );?>


<?php if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();}?>
	
        <!-- <h1><a href="<?php //the_permalink() ?>" class="new-link"><?php //the_title(); ?></a></h1> -->

        <h2><?php the_title() ?></h2>
        <?php the_content();?>

        <hr />


    <?php /* Restore original Post Data */
	wp_reset_postdata();
} else {
	// no posts found
} ?>    


<?php } ?>


<?php genesis();
