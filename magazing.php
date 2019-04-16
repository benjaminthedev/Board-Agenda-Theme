 <?php
// Template Name: Past Magazines Page

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
	




<?php if( have_rows('magazine_repeator') ): ?>

	<ul class="magazines">

	<?php while( have_rows('magazine_repeator') ): the_row(); 

		// vars
		$magazine_image = get_sub_field('magazine_image');
		$title = get_sub_field('title');
		$date = get_sub_field('date');
		$issue_number = get_sub_field('issue_number');
		$magazine_link = get_sub_field('magazine_link');

		?>

		<li class="magazine <?php echo (++$j % 2 == 0) ? 'evenpost' : 'oddpost'; ?>">


			<h3><?php echo $title; ?></h3>
			<em><?php echo $date; ?> -  Issue No: <?php echo $issue_number; ?></em><br />

			<br />


			<div class="magazineFeat_Img">
				<img src="<?php echo $magazine_image; ?>" />
			</div>

			<br />

			
			
			<?php if( $magazine_link ): ?>
				<a href="<?php echo $magazine_link; ?>" class="btn_magazine button" target="_blank">Read Magazine</a>
			<?php endif; ?>


			
			
			

		</li>

	<!-- 	<div class="clearfix"></div> -->

	<?php endwhile; ?>

	</ul>

<?php endif; ?>







<style>
	li.magazine{
		margin-bottom: 50px;
	}

	li.magazine.oddpost {
	    float: left;
	    width: 50%;
	}

	li.magazine.evenpost {
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