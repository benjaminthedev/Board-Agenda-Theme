<?php
  global $nine3_Membership;

  //Check the resource restriction, if any, and show the associated icon
  $icon = $nine3_Membership->user_can_access_to_resource() ? 'fa-cloud-download' : 'fa-lock';

  //thumbnail
  $post_image_url = get_field('the_featured_post_image', $post_id);

?>
    <div class="one-third resource <?php echo @$class ?>">
      <a href="<?php echo get_permalink( get_the_ID() ); ?>" class="resource-cover">
        <div class="icon"><span class="fa <?php echo $icon ?>"></span></div>
        <img src="<?php echo $post_image_url['sizes']['medium']; ?>">
      </a>
      <div class="resource-meta">
        <?php $partner = get_field( 'partner' ); $terms = wp_get_post_terms( get_the_ID(), 'resources-categories' ); $term = is_wp_error( $terms ) ? '' : $terms[0]; ?>
        <?php if( get_field( 'featured' ) ) echo '<small class="featured">FEATURED</small>'; ?>
        <?php if( $partner ) : ?>
          <a href="<?php echo get_permalink( $partner->ID ); ?>">
          <?php echo $partner->post_title; ?></a><span> | </span>
        <?php endif; ?>
        <?php if( is_object( $term ) ) : ?>
          <a class="resource-link" href="<?php get_term_link( $term ) ?>">
            <?php echo is_object( $term ) ? $term->name : '' ?>
          </a><span> | </span>
        <?php endif; ?>
        <?php the_category( '<span> | </span>' ); ?>
      </div>
      <div class="resource-header">
        <h3><a href="<?php the_permalink(); ?>" class="new-link"><?php the_title(); ?></a></h3>
      </div>

      <div class="resource-entry">
        <p><?php echo substr( get_the_excerpt(), 0, 190 ); ?>...[]</p>
      </div>
    </div>
