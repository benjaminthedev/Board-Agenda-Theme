
<div class="wrap top-bottom-margin">
    	<h2 class="front-title sponsor-h2">
    		Latest Videos

    		<?php

    		 if( $interviews_sponsor_text !== NULL && $interviews_sponsor_text !== '' 
          && $interviews_sponsor_logo !== NULL && $interviews_sponsor_logo !== '' ):
           ?>
             	<a href="<?php echo $interviews_sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $interviews_sponsor_text; ?>
             		<img src="<?php echo $interviews_sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
    	</h2>

    	<div class="clearfix">
	    	<ol class="front-analysis floatleft">

				<?php $my_query = new WP_Query('showposts=4&cat=6814'); ?>

				<?php $counter = 1 ?>
				<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

				<li class="news-analysis-wrap clearfix <?php if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
                    <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'category-thumb' ); ?></a>

                    <p class="front-latest-date"><span class="topic-selector"><?php the_field('topic_selector'); ?></span></p>
                    <h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></h3>

					<div class="front-latest-excerpt "><?php wpden_excerpt('news_analysis'); ?></div>
				</li>
				<?php $counter++ ;
				endwhile; ?>
			</ol>
    	</div>
        <a href="<?php echo home_url('/videos/'); ?>" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
    </div>