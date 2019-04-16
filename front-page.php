<?php

//* Template Name: Home Page


//add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {
		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_after_header', 'board_agenda_front' );
		add_action( 'genesis_loop', 'board_agenda_front_content' );

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);
}

function board_agenda_front(){ 
  
  
	?>
	<div class="wrap front-description">
		<div class="front-description-container">
    	<?php the_content(); ?>
		</div>
	</div>
<?php }

function board_agenda_front_content(){ 

  /**
   * Category sponsor
   */
  $category_ids = ba_get_category_ids_for_acf();
  
  //Regulation
  $regulation_sponsor_text = get_field( 'sponsor_text', $category_ids['regulation'] );
  $regulation_sponsor_link = get_field( 'sponsor_link', $category_ids['regulation'] );
  $regulation_sponsor_logo = get_field( 'sponsor_logo', $category_ids['regulation'] );

  //New analisys
  $news_sponsor_text = get_field( 'sponsor_text', $category_ids['news-analysis'] );
  $news_sponsor_link = get_field( 'sponsor_link', $category_ids['news-analysis'] );
  $news_sponsor_logo = get_field( 'sponsor_logo', $category_ids['news-analysis'] );

  //interviews
  $interviews_sponsor_logo = get_field( 'sponsor_logo', $category_ids['interviews'] );
  $interviews_sponsor_text = get_field( 'sponsor_text', $category_ids['interviews'] );
  $interviews_sponsor_link = get_field( 'sponsor_link', $category_ids['interviews'] );

  //comment
  $comment_sponsor_logo = get_field( 'sponsor_logo', $category_ids['comment'] );
  $comment_sponsor_text = get_field( 'sponsor_text', $category_ids['comment'] );
  $comment_sponsor_link = get_field( 'sponsor_link', $category_ids['comment'] );

  //board-moves
  $board_moves_sponsor_logo = get_field( 'sponsor_logo', $category_ids['board-moves'] );
  $board_moves_sponsor_text = get_field( 'sponsor_text', $category_ids['board-moves'] );
  $board_moves_sponsor_link = get_field( 'sponsor_link', $category_ids['board-moves'] );

  //insights
  $insights_sponsor_logo = get_field( 'sponsor_logo', $category_ids['insights'] );
  $insights_sponsor_text = get_field( 'sponsor_text', $category_ids['insights'] );
  $insights_sponsor_link = get_field( 'sponsor_link', $category_ids['insights'] ); 


	?>

	<div class="wrap top-bottom-margin first mobile-view-hide">
		<h2 class="front-title">Top Stories</h2>

        <script>
	        jQuery(document).ready(function ($) {
	            var options = {
	                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
	                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
	                $AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
	                $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1
	                $Loop: 1,                                       //[Optional] Enable loop(circular) of carousel or not, 0: stop, 1: loop, 2 rewind, default value is 1

	                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
	                $SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
	                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
	                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
	                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
	                $SlideSpacing: 5, 					                //[Optional] Space between each slide in pixels, default value is 0
	                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
	                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
	                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
	                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
	                $DragOrientation: 0,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

	                $ThumbnailNavigatorOptions: {
	                    $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
	                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always

	                    $Loop: 2,                                       //[Optional] Enable loop(circular) of carousel or not, 0: stop, 1: loop, 2 rewind, default value is 1
	                    $AutoCenter: 3,                                 //[Optional] Auto center thumbnail items in the thumbnail navigator container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 3
	                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange thumbnails, default value is 1
	                    $SpacingX: 4,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
	                    $SpacingY: 4,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
	                    $DisplayPieces: 4,                              //[Optional] Number of pieces to display, default value is 1
	                    $ParkingPosition: 0,                            //[Optional] The offset position to park thumbnail
	                    $Orientation: 2,                                //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
	                    $DisableDrag: true                             //[Optional] Disable drag or not, default value is false
	                }
	            };

	            var jssor_slider1 = new $JssorSlider$("slider1_container", options);

	            //responsive code begin
	            //you can remove responsive code if you don't want the slider scales while window resizes
	            function ScaleSlider() {
	                var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
	                if (parentWidth) {
	                    var sliderWidth = parentWidth;

	                    //keep the slider width no more than 810
	                    sliderWidth = Math.min(sliderWidth, 800);

	                    jssor_slider1.$ScaleWidth(sliderWidth);
	                }
	                else
	                    window.setTimeout(ScaleSlider, 30);
	            }
	            ScaleSlider();

	            $(window).bind("load", ScaleSlider);
	            $(window).bind("resize", ScaleSlider);
	            $(window).bind("orientationchange", ScaleSlider);
	            //responsive code end
	        });
	    </script>

	    <!-- Home Page Slider Begin -->
	    <!-- To move inline styles to css file/block, please specify a class name for each element. -->
	    <div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 800px; height: 330px; background: #fff; overflow: hidden; ">

	        <!-- Loading Screen -->
	        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
	            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
	                background-color: #000000; top: 0px; left: 0px;width: 100%;height:100%;">
	            </div>
	            <div style="position: absolute; display: block; background: url(<?php echo get_stylesheet_directory_uri(); ?>/img/loading.gif) no-repeat center center;
	                top: 0px; left: 0px;width: 100%;height:100%;">
	            </div>
	        </div>
			 <?php
				$post_objects = get_field('top_stories_home');
//var_dump($post_objects);


			if( $post_objects ):
	     	?>   <!-- Slides Container -->

	        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 7px ; width: 550px; height: 317px; overflow: hidden; ">
				<?php
					foreach( $post_objects as $post_object):
						setup_postdata($post_object);
					$do_not_duplicate = get_the_ID();
					$category = get_the_category($post_object->ID);
					$topic_selector = get_field( 'topic_selector', $post_object->ID );
				?>
		        <div>
			        <!-- Get Featured Image from post -->
			        <?php
						$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_object->ID) );
					?>
					<a href="<?php echo get_permalink($post_object->ID); ?>" class="new-link"><div u="image" style="background:url(<?php echo $feat_image; ?>) center center no-repeat; width:550px; height:317px; background-size:cover;"></div></a>
			        <div u="thumb" onclick="location.href='<?php echo get_permalink($post_object->ID); ?>';">
			        <p class="slider-category-t front-slider-category"><?php echo $topic_selector; ?></p>
			        <h3 class="slider-title front-slider-title"><a href="<?php echo get_permalink($post_object->ID); ?>" class="slider-top-link"><?php echo get_the_title($post_object->ID); ?></a></h3>
              <!-- <div class="slider-subheading"><?php the_field('single_post_subheading', $post_object->ID); ?></div> -->
			        </div>
		        </div>
		        <?php
				endforeach;
				 ?>
				 <?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>
		        </div>
			<?php endif; ?>
	        <!--#region ThumbnailNavigator Skin Begin -->

	          <div u="thumbnavigator" class="jssort11" style="left: 530px; top:0px;">
	            <!-- Thumbnail Item Skin Begin -->
	            <div u="slides" style="cursor: default;">
	                <div u="prototype" class="p" style="top: 0; left: 0;">
	                    <div u="thumbnailtemplate" class="tp"></div>
	                </div>
	            </div>
	            <!-- Thumbnail Item Skin End -->
	        </div>
	    </div>
    </div>

 <!-- Top Stories Mobile View Only -->
 <div class="wrap top-bottom-margin first mobile-view-show">
		<h2 class="front-title">Top Stories</h2>

<?php
/*
		*  Loop through post objects (assuming this is a multi-select field) ( setup postdata )
		*  Using this method, you can use all the normal WP functions as the $post object is temporarily initialized within the loop
		*  Top three stores loads from the front page multi select option
		*/
?>
	<?php $post_objects = get_field('top_stories_home');
		
		if( $post_objects ): ?>
		    <ul>
		    <?php foreach( $post_objects as $post_object): ?>
		    	 <?php setup_postdata($post_object); ?>
					<li class="latest-news-wrap clearfix">
 			             <?php echo get_the_post_thumbnail($post_object->ID ); ?>
                         <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?>, <?php endif; ?></span> <?php echo get_the_date('j F, Y', $post_object); ?> </p>
						<h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php echo get_permalink($post_object->ID); ?>" class="new-link"><?php echo get_the_title($post_object->ID); ?></a></h3>
		</span>
		        </li>
		    <?php endforeach; ?>
		    </ul>
		<?php endif; ?>
</div>


    <!-- /****************** LATEST NEWS ******************** -->

<div class="wrap top-bottom-margin">
	<h2 class="front-title">Latest News</h2>
	<div class="news-left floatleft">
		<ol>
	<?php $my_query = new WP_Query("showposts=8&cat=4"); $i = 0; ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post();
		if( ++$i == 4 ) {
			echo '</ol></div><div class="news-right floatright"><ol>';
		}
	?>

		<li class="latest-news-wrap clearfix">
		<?php if( $i <= 3) : ?>
	    <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'latest-news-top' ); ?></a>
		<?php endif; ?>
	    <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?>, <?php endif; ?></span> <?php echo get_the_date('j F, Y'); ?> </p>
			<h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h3>
		<?php if( $i <= 3) : ?>
			<div class="front-latest-excerpt "><?php wpden_excerpt('latest_news'); ?></div>
		<?php endif; ?>
		</li>
	<?php endwhile; ?>
</ol>
<div class="button-clear clearfix"><a href="<?php echo esc_url( home_url() ); ?>/news" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a></div>
</div>


</div>

<!-- /******************** NEWS ANALYSIS ************************* 

    <div class="wrap top-bottom-margin">
    	<h2 class="front-title sponsor-h2">
    		News Analysis

    		<?php
    		 if( $news_sponsor_text !== NULL && $news_sponsor_text !== '' 
          && $news_sponsor_logo !== NULL && $news_sponsor_logo !== '' ):
        ?>
             	<a href="<?php echo $news_sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $news_sponsor_text; ?>
             		<img src="<?php echo $news_sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
    	</h2>

    	<div class="clearfix">
	    	<ol class="front-analysis floatleft">

				<?php //$my_query = new WP_Query('showposts=4&cat=5'); ?>

				<?php //$counter = 1 ?>
				<?php //while ($my_query->have_posts()) : $my_query->the_post(); ?> 

				<li class="news-analysis-wrap clearfix <?php //if ($counter % 2 == 0 ) { echo 'omega'; } elseif ($counter % 2 == 1 ) { echo 'alpha'; } ?>">
                    <a href="<?php //the_permalink() ?>"><?php //the_post_thumbnail( 'category-thumb' ); ?></a>

                    <p class="front-latest-date"><span class="topic-selector"><?php //the_field('topic_selector'); ?></span></p>
                    <h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php //the_permalink() ?>" class="ana-link"  ><?php the_title(); ?></a></h3>

					<div class="front-latest-excerpt "><?php //wpden_excerpt('news_analysis'); ?></div>
				</li>
				<?php //$counter++ ;
				//endwhile; ?>
			</ol>
    	</div>
        <a href="index.php?p=189" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
    </div>
NEW ANALYSIS NOT IN USE AT THE MOMENT..... -->

<!-- /******************** INTERVIEWS ************************* -->

    <div class="wrap top-bottom-margin">
    	<h2 class="front-title sponsor-h2">
    		Interviews

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

				<?php $my_query = new WP_Query('showposts=4&cat=534'); ?>

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
        <a href="<?php echo home_url('/interviews/'); ?>" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
    </div>


<!-- /******************* COMMENT Latest author post with bx slider *********************** -->

    <div class="wrap top-bottom-margin comment-wrapper">
    	<h2 class="front-title sponsor-h2">
    		Comment

    		<?php

    		 if( $comment_sponsor_text !== NULL && $comment_sponsor_text !== '' 
          && $comment_sponsor_logo !== NULL && $comment_sponsor_logo !== '' ):
           ?>
             	<a href="<?php echo $comment_sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $comment_sponsor_text; ?>
             		<img src="<?php echo $comment_sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
    	</h2>

			<ul class="bxslider">

				<?php
				    $authors=get_users();
				    $i=0;
				    //get all users list
				    foreach($authors as $author) {
				        $authorList[$i]['id']=$author->data->ID;
				        $authorList[$i]['name']=$author->data->display_name;
				        $i++;
				    }

				        foreach($authorList as $athor_id => $author) {
				            $args = array(
				                    'showposts' => 5,
				                    'author' => $author['id'],
				                    // 'caller_get_posts'=>1,
				                    'ignore_sticky_posts'=> 1,
				                    'cat' => 16,
				                   );
										$posts = get_posts($args);
										if( empty($posts) ) continue;

				            // $query = new WP_Query($args);

				            // if($query->have_posts() ) {
				            //     while ($query->have_posts()) {
				            //         $query->the_post();
										global $post;
										foreach($posts as $p) {
											$post = $p;
				        ?>
								<div class="front-opinion">
							<li>
						        <div class="front-avatar">
								<?php
									 $author_id = get_the_author_meta('ID');

									 $author_image = get_field('author_image', 'user_'. $author['id'] );
									//echo get_avatar($author['id'], 66);
								?>
								 <img src="<?php echo $author_image; ?>" >
						         <!-- <p> <?php //echo get_avatar( get_the_author_meta( 'ID' ) , 32 ); ?></p> -->
						         <div class="author-name"><?php echo $author['name']; ?></div>
						         </div>
						         <div class="front-opinion-news">
							         <h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php echo get_permalink(); ?>"> <?php echo get_the_title(); ?> </a></h3>
							         <div class="comment-excerpt"><?php wpden_excerpt('opinion_len'); ?></div>
						        </div>
				         </li>
							 </div>
				        <?php
				                }
				            // }

										wp_reset_postdata();
				        }
				        ?>
			</ul>
    </div>

<!-- /******************** BOARD MOVES and REGULATION Latest Post Section ******************** -->

    <div class="wrap top-bottom-margin">

        <div class="front-board-moves">
        <h2 class="front-title sponsor-h2">
        	Board Moves

        	<?php

    		 if( $board_moves_sponsor_text !== NULL && $board_moves_sponsor_text !== '' 
          && $board_moves_sponsor_logo !== NULL && $board_moves_sponsor_logo !== '' ):
           ?>
             	<a href="<?php echo $board_moves_sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $board_moves_sponsor_text; ?>
             		<img src="<?php echo $board_moves_sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
        </h2>
    	<ol class="">
    		<?php


    			//getting board moves page permalink
    		$board_link = get_pagelink_by_name('Board Moves');

    		?>

    		<?php $my_query = new WP_Query('showposts=3&cat=6'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<li class="front-board-moves-wrap clearfix">
                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'latest-news-thumb' ); ?></a>
	            <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?>, <?php endif; ?></span> <?php echo get_the_date('j F, Y'); ?></p>
				<h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" ><?php the_title(); ?></a></h3>
				<div class="front-latest-excerpt "><?php wpden_excerpt('default_len'); ?></div>
			</li>
			<?php endwhile; ?>
    	</ol>
							<div class="button-clear clearfix"><a href="<?php echo $board_link; ?>" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a></div>
            </div>


        <div class="front-regulation">
    	<h2 class="front-title sponsor-h2">
    		Regulation

    		<?php

    		 if( $regulation_sponsor_text !== NULL && $regulation_sponsor_text !== '' 
          && $regulation_sponsor_logo !== NULL && $regulation_sponsor_logo !== '' ):
           ?>
             	<a href="<?php echo $regulation_sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $regulation_sponsor_text; ?>
             		<img src="<?php echo $regulation_sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
    	</h2>
        <ol class="">


    		<?php $my_query = new WP_Query('showposts=3&cat=15'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<li class="front-board-moves-wrap clearfix">
        <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'latest-news-thumb' ); ?></a>
        <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?>, <?php endif; ?></span> <?php echo get_the_date('j F, Y'); ?></p>
				<h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
				<div class="front-latest-excerpt "><?php wpden_excerpt('latest_news'); ?></div>
			</li>
			<?php endwhile; 

			$regulation_url = get_home_url() . '/category/regulation/';
		
			?>

    	</ol>
    				<div class="button-clear clearfix"><a href="<?php echo $regulation_url; ?>" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a></div>
            </div>

           <!--<div class="button-clear clearfix"><a href="index.php?p=191" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a></div>-->
    </div>


		<div class="wrap top-bottom-margin partner-box">
			<h2>Partner Contributions</h2>

			<?php while( have_rows( 'items', 'options' ) ) : the_row(); ?>
				<?php $post = get_sub_field( 'post' ); $partner = get_field( 'partner', $post->ID ); ?>
				<div class="item">
					<div class="thumbnail floatleft">
						<?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
					</div>

					<div class="title floatleft">
						<p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?> <?php endif; ?></span><a href="<?php echo get_permalink( $partner->ID ); ?>" class="brought">Brought to you by <?php echo $partner->post_title; ?></a></p>
						<h3><a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title ?></a></h3>
					</div>

					<div class="partner">
						<?php echo get_the_post_thumbnail( $partner->ID, 'small' ); ?>
					</div>

					<div class="entry">
						<?php echo $post->post_excerpt; ?>
					</div>

				</div>
			<?php endwhile; ?>

		</div>

		<div class="wrap top-bottom-margin">
			<h2 class="front-title">LATEST REPORTS &amp; BRIEFINGS</h2>

			<div class="resources-home">
			<?php
			$args = array(
				'post_type' => 'resources',
				'orderby' => 'date',
				'posts_per_page' => 4
			);

			$posts = new WP_Query( $args );

			$class = 'first';
			$i = 1;
			while( $posts->have_posts() ) {
				$posts->the_post();

				get_template_part( 'template-parts/resource', 'home' );
			}

			wp_reset_postdata();
			?>

			</div>
		</div>
<?php }

function board_agenda_front_latest_insight(){ ?>
<div class="site-inner">

	<div class="wrap top-bottom-margin insights-homepage equalize-parent">

    <h2 class="front-title sponsor-h2">
    	Insight

    	<?php

    		 if( $insights_sponsor_text !== NULL && $insights_sponsor_text !== '' 
          && $insights_sponsor_logo !== NULL && $insights_sponsor_logo !== '' ):
           ?>
             	<a href="<?php echo $insights_sponsor_link; ?>" title="Open content in a new tab" 
             		 target="_blank" class="front-title-sub-link">
             		<?php echo $insights_sponsor_text; ?>
             		<img src="<?php echo $_sponsor_logo['sizes']['related']; ?>" alt="" class="title-sponsor-image">
             	</a>
           <?php   
         endif;
    		?>
    </h2>

    	<!-- /************************ Insight & Governance ********************-->

    	<div class="one-sixth first">
    	<h3>Governance</h3>

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 8 ), 'posts_per_page' => 1 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me clearfix">
                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span></p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
				<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
					<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
			</div>
			<?php endwhile; ?>

			<!-- /***************************** Insight & Governance offset 1 ****************************** -->

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 8 ), 'offset' => 1, 'posts_per_page' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me offset-insight clearfix">
                 <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                 <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span></p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
			<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
            <?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
</div>
			<?php endwhile; ?>


		</div>

		<!-- /********************************** Insight & Strategy ******************************************-->

		<div class="one-sixth second">
		<h3>Strategy</h3>

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 10 ), 'posts_per_page' => 1 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me clearfix">
                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span> </p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
				<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
					<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
			</div>
			<?php endwhile; ?>

			<!-- /****************************** Insight & Strategy offset 1 ************************** -->

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 10 ), 'offset' => 1, 'posts_per_page' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me offset-insight clearfix">
                 <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span> </p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
			<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
					<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
</div>
			<?php endwhile; ?>

		</div>

		<!-- /************************************ Insight & Risk ********************************-->

		<div class="one-sixth third">
            <h3>Risk</h3>

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 9 ), 'posts_per_page' => 1 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me clearfix">
                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span></p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
				<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
					<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
			</div>
			<?php endwhile; ?>

			<!-- /********************************* Insight & Risk offset 1 **************************** -->

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 9 ), 'offset' => 1, 'posts_per_page' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me offset-insight clearfix">
                 <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span> </p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
			<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
						<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
</div>
			<?php endwhile; ?>

		</div>

		<!-- /************************************** Insight & Ethics ************************************-->

		<div class="one-sixth fourth">
		<h3>Ethics</h3>

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 11 ), 'posts_per_page' => 1 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me clearfix">
                 <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>

                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span> </p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
				<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
						<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
			</div>
			<?php endwhile; ?>

			<!-- /************************************* Insight & Ethics offset 1 *************************-->

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 7, 11 ), 'offset' => 1, 'posts_per_page' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me offset-insight clearfix">
                 <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span></p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
			<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
						<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
</div>
			<?php endwhile; ?>

		</div>

			<!-- /************************************** Board Expertise *****************************-->

		<div class="one-sixth">
		<h3>Board Expertise</h3>

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 394 ), 'posts_per_page' => 1 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me clearfix">
	            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?>
                    <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span> </p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
				<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
					<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
			</div>
			<?php endwhile; ?>

			<!--/**************************************** Board Expertise offset 1 ******************-->

    		<?php $my_query = new WP_Query(array( 'category__and' => array( 394 ), 'offset' => 1, 'posts_per_page' => 2 ) ); ?>

			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<div data-compare=".front-insight-wrap" class="front-insight-wrap equalize-me offset-insight clearfix">
                 <a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'insights-front-thumb' ); ?> </a>
                <p class="front-latest-date"><span class="topic-selector"><?php if( get_field('topic_selector') ): ?><?php the_field('topic_selector'); ?></p> <p class="front-latest-date"><?php endif; ?></span> </p>
				<h4 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php the_permalink() ?>" class="new-link"><?php the_title(); ?></a></h4>
			<div class="front-latest-excerpt "><?php if( $field = get_field('single_post_subheading') ): ?>
				<?php echo substr( wp_strip_all_tags( $field ), 0, 170 ); ?>[...]
            <?php endif; ?></div>
</div>
			<?php endwhile; ?>
		</div>
		<div class="one-half first clearfix insights-read-more-wrap">
			<a href="index.php?p=46" class="button read-more">Read More &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
		</div>
	</div>
        </div> <!-- end site-inner -->


<?php }






genesis();
