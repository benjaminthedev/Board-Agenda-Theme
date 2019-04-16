<?php
/**
 * Template used to display form to add resources
 * Template Name: Add Resource
 * 
 * @package Boardagenda
 */

//getting alpha datat
$alpha_data = get_alpha_data();

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_add_resource_template' );

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);

}

function board_agenda_add_resource_template() {

    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( get_home_url() . '/sign-up/' );
        exit;
    }

    // only admins, subscribers and client users can access here.
    if ( ! ba_is_subscriber() && ! ba_is_client_user()  && ! current_user_can( 'manage_options' ) ) {
        wp_safe_redirect( get_home_url() . '/sign-up/' );
        exit;
    }
    
    
    $page_id        = get_queried_object_id();
    $page_title     = get_the_title( $page_id );
    $content_post   = get_post($my_postid);
    $content        = $content_post->post_content;
    $content        = apply_filters('the_content', $content);
    $page_content   = str_replace(']]>', ']]&gt;', $content);
    $current_user   = wp_get_current_user();
    $files_data     = ba_get_files_left( $current_user->ID );
    $text           = sprintf( _n( '<b>%s</b> resource', '<b>%s</b> resources', $files_data->files_left ), $files_data->files_left );
    
?>
        <section class="wrap top-bottom-margin">
            <div class="signup__banner">
            <h2 class="front-title signup__banner__title"><?php echo $page_title; ?></h2>
            <p class="signup__banner__text"><?php echo $page_content; ?></p>
            <?php if ( $files_data->files_left <= $files_data->post_limit ) : ?>
                <p>You can publish <?php echo $text; ?> of a limit of <b><?php echo $files_data->post_limit; ?></b> resources.</p>
                <?php echo do_shortcode( '[gravityform id="11" title="false" description="false" ]' ); ?>
            <?php else : ?>
                <p>You have already published <b><?php echo $files_data->post_limit; ?></b> resources of a limit of <b><?php echo $files_data->post_limit; ?></b> resources</p>
            <?php endif; ?>
            </div>
        </section>
    <?php
}

genesis();