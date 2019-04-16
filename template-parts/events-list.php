<article class="event">
  <header>
    <?php the_post_thumbnail(); ?>

    <div class="event-meta">
      <?php echo get_field( 'featured' ) ? '<small class="featured">FEATURED</small>' : ''; ?>
      <h4>
        <a href="<?php echo get_field( 'website' ) ?>">
          <?php the_title(); ?>
        </a>
      </h4>

      <ul>
        <li class="date"><span class="fa fa-calendar"></span><?php the_field( 'start_date' ) ?> - <?php the_field( 'end_date' ) ?></span></li>
        <!-- <li><span class="fa fa-certificate"></span>Course type entry</li> -->
        <?php if( is_post_type_archive( 'education' ) ) :
          $partner = get_field( 'partner' );
          ?>
          <li><span class="fa fa-institution"></span><?php echo is_object( $partner ) ? $partner->post_title : ""; ?></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="clearfix"></div>
  </header>

  <div class="event-content">
    <?php the_excerpt(); ?>

    <div class="view-website">
      <a href="<?php echo get_field( 'website' ); ?>">Visit website <span class="fa fa-arrow-circle-right"></span></a>
    </div>
  </div>
</article>
