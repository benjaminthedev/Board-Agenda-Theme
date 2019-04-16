<div class="resource <?php echo @$class ?>">
  <a href="<?php echo get_permalink( get_the_ID() ); ?>" class="thumbnail">
    <?php the_post_thumbnail( 'featured-resource' ); ?>
  </a>
  <div class="resource-right">
    <div class="resource-meta front-latest-date topic-selector">
      <?php $terms = wp_get_post_terms( get_the_ID(), 'resources-categories' ); $term = is_wp_error( $terms ) ? '' : $terms[0]; ?>
      <a class="resource-link" href="<?php echo is_object( $term ) ? get_term_link( $term ) : ''; ?>"><?php echo is_object( $term ) ? $term->name : '' ?></a>
    </div>
    <div class="resource-header">
      <h3 class="front-latest-title"><a href="<?php the_permalink(); ?>" class="new-link"><?php the_title(); ?></a></h3>
    </div>

    <div class="resource-entry">
      <p><?php echo substr( get_the_excerpt(), 0, 190 ); ?>...[]</p>
    </div>
  </div>
</div>
