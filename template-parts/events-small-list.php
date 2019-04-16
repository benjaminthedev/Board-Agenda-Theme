<article class="event">
  <header>
    <?php the_post_thumbnail(); ?>

    <div class="event-meta">
      <h4>
        <a href="<?php echo get_permalink( get_the_ID() ); ?>">
          <?php the_title(); ?>
        </a>
      </h4>

      <ul>
        <li class="date"><span class="fa fa-calendar"></span><?php the_field( 'start_date' ) ?> - <?php the_field( 'end_date' ) ?></span></li>
      </ul>
    </div>

    <div class="clearfix"></div>
  </header>

  <div class="event-content">
    <?php the_excerpt(); ?>

    <div class="view-website">
      <a href="<?php echo get_field( 'external_link' ); ?>">Visit website <span class="fa fa-arrow-circle-right"></span></a>
    </div>
  </div>
</article>
