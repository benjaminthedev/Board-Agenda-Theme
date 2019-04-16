<?php

$expertise = get_field('company_expertise');

//thumbnail
$post_image_url = get_field('the_featured_post_image', $post_id);

?>
<div class="resource-partner">
  <div class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <span class="industry"><?php echo join(' ', $expertise); ?></span></div>
  <div class="content">
    <div class="logo"><img src="<?php echo $post_image_url['sizes']['medium']; ?>" class="supplier_logo"></div>
    <div class="excerpt"><p><?php echo ba_get_partner_description(); ?>... <a href="<?php the_permalink(); ?>">View profile &raquo;</a></p></div>
  </div>
</div>
