<?php

//* Template Name: Single Magazine



add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );
add_action( 'genesis_before_footer', 'board_agenda_paypal_popup' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );
		add_action( 'genesis_loop', 'board_agenda_magazines_issues' );

}


function board_agenda_magazines_issues(){ ?>



	<div class="wrap top-bottom-margin">
		<h2 class="front-title"><?php echo get_the_title(); ?></h2>



	</div>


<?php //the_field('magazine'); ?>







<br />


<!-- 

Odd even the first two 

then jquery the rest to make smaller etc etc


 -->

<?php 

$posts = get_field('blha');

if( $posts ): ?>
	<ul class="singleMagazine">
	<?php foreach( $posts as $p ): // variable must NOT be called $post (IMPORTANT) ?>
	    <li class="latest-stories-excerpt <?php echo (++$j % 2 == 0) ? 'evenpost' : 'oddpost'; ?>">

	    	    <div class="latest-stories-thumb">
					<?php echo get_the_post_thumbnail( $p->ID, 'latest-two-stories' ); ?>
				</div>

				<div class="newClassWrapper">
	    	
				 <p class="front-latest-date"><span class="topic-selector"><?php if( get_post_field('topic_selector', $p->ID) ) echo get_post_field('topic_selector', $p->ID) . ','; ?></span>

				<span><?php echo get_the_date('jS F Y', $p->ID);?></span></p>			



				<h3 class="latest-stories-post"><a href="<?php echo get_permalink( $p->ID ); ?>" class="anImgNew">
	    			<?php echo get_the_title( $p->ID ); ?>
	    		</a></h3>

			    	<p><?php echo get_the_excerpt( $p->ID ); ?></p>

			    </div><!-- end newClassWrapper -->	

	    


	    </li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>





<br />




<style>

	li.latest-stories-excerpt.oddpost.newClass, 
	li.latest-stories-excerpt.evenpost.newClass {
	    clear: both;
	    float: none;
    	width: 100%;
	}

	li.latest-stories-excerpt.oddpost.newClass, 
	li.latest-stories-excerpt.evenpost.newClass {
  
	}


	li.latest-stories-excerpt.oddpost.newClass .newClassWrapper,
	li.latest-stories-excerpt.evenpost.newClass .newClassWrapper{
    margin-left: 190px;
	}


	li.latest-stories-excerpt.oddpost.newClass img.attachment-latest-two-stories.size-latest-two-stories.wp-post-image,
	li.latest-stories-excerpt.evenpost.newClass	img.attachment-latest-two-stories.size-latest-two-stories.wp-post-image{
		   /*width: 40%;*/

		       width: 90%;
    		float: left;
	}




	li.latest-stories-excerpt.oddpost.newClass .latest-stories-thumb, 
	li.latest-stories-excerpt.evenpost.newClass .latest-stories-thumb  {
    float: left;
    position: absolute;
    /*width: 30%;*/
    width: 190px;

	}


	h3.latest-stories-post {
    font-size: 18px;
    font-family: 'Avenir-Black';
    line-height: 22px;

	}

	h3.latest-stories-post a{
		color:#000;
	}

	h3.latest-stories-post a:hover {
    	color: #738086;
	}

	

	 






	li.latest-stories-excerpt{
		margin-bottom: 50px;
	}

	li.latest-stories-excerpt.oddpost {
	    float: left;
	    width: 50%;
	}

	li.latest-stories-excerpt.evenpost {
	    float: right;
	    width: 50%;
	}

	.magazineFeat_Img {
	    width: 64%;
	    margin: 0;
	}		

	li.magazine h3{
		margin-bottom: 0px;
	}

	li.magazine em {
    	font-size: 14px;
    	margin-top: 0px;
	}




</style>








<?php }

genesis();