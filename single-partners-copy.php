<?php
//* Template Name: Insight Page

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
	<div class="wrap top-bottom-margin single-partner">
    <header class="partner-header"><h2 class="front-title"><?php the_title(); ?></h2></header>
    <div class="partner-content">
      <div class="thumbnail">
        <?php the_post_thumbnail('partner-logo'); ?>
      </div>

        <?php the_content(); ?>
    </div>

    <footer class="partner-footer">
      <ul>
        <?php if( get_field( 'website' ) ) : ?>
        <li><span class="fa fa-globe"></span><a href="<?php the_field( 'website' ) ?>"><?php the_field( 'website' ) ?></a></li>
        <?php endif; ?>

        <?php if( get_field( 'email' ) ) : ?>
        <li><span class="fa fa-envelope"></span><a href="mailto:<?php the_field( 'email' ) ?>"><?php the_field( 'email' ) ?></a></li>
        <?php endif; ?>

        <?php if( get_field( 'phone' ) ) : ?>
        <li><span class="fa fa-phone"></span><a href="tel:<?php the_field( 'phone' ) ?>"><?php the_field( 'phone' ) ?></a></li>
        <?php endif; ?>
      </ul>
    </footer>
	</div>

  <?php
    $args = array( 'posts_per_page' => 3,
     'offset' => 0,
     'post_type' => 'post',
     'meta_query' => array(
       array(
         'key'     => 'partner',
         'value'   => get_the_ID(),
         'compare' => '=',
       ),
     ),
    );

    $my_query = new WP_Query( $args );

    if( $my_query->have_posts() ) :  ?>
  <h2 class="widget-title resource-list-title">ARTICLES FROM <?php the_title(); ?></h2>
  <ol class="category-latest-continued">


     <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

     <li class="latest-news-wrap clearfix">
       <?php the_post_thumbnail( 'latest-news-thumb' ); ?>
       <p class="front-latest-date"><?php echo get_the_date('j F, Y'); ?></p>
       <h3><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
       <span class="latest-stories-excerpt"><?php wpden_excerpt('latest_news'); ?></span>
     </li>
     <?php endwhile; ?>
     <?php  wp_reset_postdata(); ?>

  </ol>
  <a class="browse-all button" href="/resources">VIEW ALL&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>
  <?php endif; ?>

  <h2 class="widget-title resource-list-title">RELATED RESOURCES</h2>

  <?php
    $args = array(
      'post_type' => 'resources',
      'orderby' => 'date',
      'posts_per_page' => 3,
      'meta_key' => 'partner',
      'meta_value' => get_the_ID()
    );

    $posts = new WP_Query( $args );

    $class = 'first';
    $i = 1;

    if( $posts->have_posts() ) : ?>
  <div class="resources-list">
    <?php while( $posts->have_posts() ) {
        $posts->the_post();

        get_template_part( 'template-parts/resource', 'list' );
      }

      wp_reset_postdata(); ?>
    <a class="browse-all button" href="/resources">VIEW ALL&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>
  </div>
  <?php endif; ?>

  <?php
    $args = array(
      'post_type' => 'events',
      'orderby' => 'date',
      'posts_per_page' => 3,
      'meta_query' => array(
        array(
          'key'     => 'partner',
          'value'   => get_the_ID(),
          'compare' => '=',
        ),
      ),
    );

    $posts = new WP_Query( $args );

    if( $posts->have_posts() ) :  ?>
  <h2 class="widget-title resource-list-title">COURSES FROM <?php the_title(); ?></h2>
  <div class="events-list">
  <?php while( $posts->have_posts() ) {
      $posts->the_post();

      get_template_part( 'template-parts/events', 'list' );
    }

    wp_reset_postdata();
  ?>
    <a class="browse-all button" href="/events">VIEW ALL&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a>
  </div>
  <?php endif; ?>

<?php }


genesis();
