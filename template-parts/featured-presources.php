<?php global $featured, $size, $limit; ?>
<article class="post-<?php echo $featured->ID; ?> resources type-resources status-publish has-post-thumbnail category-governance resources-categories-report entry" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
  <a href="<?php echo get_permalink( $featured->ID ); ?>" title="<?php echo $featured->post_title ?>" class="alignnone"><?php echo get_the_post_thumbnail( $featured->ID, $size ); ?></a>
  <header class="entry-header"><h2 class="entry-title"><a href="<?php echo get_permalink( $featured->ID ); ?>" title="<?php echo $featured->post_title; ?>"><?php echo $featured->post_title; ?></a></h2></header>
  <div class="entry-content"><?php echo substr( strip_tags( $featured->post_content ), 0, $limit ); ?>...</div>
</article>
