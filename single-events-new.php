<?php

//* Template Name: Single Events News New Ben

?>




<?php get_header(); ?>



<div class="site-inner">
    <div class="wrap">	
        <div class="archive-wrap">

            <h1 class="front-title">Events</h1>

            		<div class="clearfix archive-intro">
		            	<p>Please find a selection of our partner events that are concerned with emerging issues around corporate strategy and leadership.</p>
		            </div>

<!-- repeator start -->

<section class="events">


<?php if( have_rows('new_events') ): ?>
    <?php while( have_rows('new_events') ): the_row(); 

    // variables
    $title = get_sub_field('event_title');
    $content = get_sub_field('event_information');
    $link = get_sub_field('event_url');
    $startdate = get_sub_field('start_date');
    $enddate = get_sub_field('end_date');
    $event_map = get_sub_field('event_map');
    ?>

   
    <div class="new-event">
    
        <?php if( $link ): ?>
            <a href="<?php echo $link; ?>" target="_blank">
        <?php endif; ?>

        <h5><?php echo $title; ?> </h5>
        
        <?php if( $link ): ?>
            </a>
        <?php endif; ?>

        <div class="dates-time">    
            <span class="fa fa-calendar"></span> <?php echo $startdate; ?> to <?php echo $enddate; ?> 
        </div>
       
        <p><?php echo $content; ?></p>

        <p><strong><i class="fa fa-map-marker"></i> Location:</strong> <?php echo $event_map; ?></p>

        <div class="view-website">
            <?php if( $link ): ?>
                <a href="<?php echo $link; ?>" target="_blank">
            <?php endif; ?>
                    Visit Website <span class="fa fa-arrow-circle-right"></span>
            <?php if( $link ): ?>
                </a>
            <?php endif; ?>
        </div><!-- end view-website -->
        </div><!-- end new-event -->



      





        <?php endwhile; ?>
        <?php endif; ?>

</section><!-- end section events -->

<!-- repeator end -->




        </div>
    </div>
</div>


<style>
    .new-event {
        background: #dfedf0;
        padding:5px 15px 32px 15px;
        margin-bottom: 34px;
    }

    .new-event h5{
        margin-bottom: 0px;
    }

    .new-event .dates-time{
        margin-top:10px;
        margin-bottom:20px; 
    }
</style>



<?php get_footer(); ?>

