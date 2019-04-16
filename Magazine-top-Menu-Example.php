<?php if( have_rows('magazine_repeator', 7890) ): 

$i = 0;

?>

    <ul>

    <?php while( have_rows('magazine_repeator', 7890) ): the_row();

    $i++;

    if( $i > 3 )
			{
				break;
			}

     ?>

        <li class="mangazine-top-menu"> 

            <a href="<?php echo get_permalink( $p->ID ); ?>">
        	   <?php the_sub_field('title'); ?>
            </a>   

        	<?php the_sub_field('date'); ?>

               <div class="magazineFeat_Img_thub">
                  <a href="<?php echo get_permalink( $p->ID ); ?>" >  
                    <img src="<?php the_sub_field('magazine_image'); ?>" />
                  </a>  
		      </div>

		 </li>     

    <?php endwhile; ?>

    </ul>

<?php endif; ?>





====


Using this one below:


<?php if( have_rows('magazine_in_the_menu', 7890) ):?>
    <ul>
    <?php while( have_rows('magazine_in_the_menu', 7890) ): the_row();?>
        <li class="mangazine-top-menu"> 

             <a href="<?php the_sub_field('magazine_link'); ?>" class="btn_magazine button" target="_blank">Read Magazine</a><br />

             Bear : <?php the_sub_field('magazine_link'); ?>


        	 <a href="<?php echo get_permalink( $p->ID ); ?>">
               <?php the_sub_field('title'); ?>
            </a>   
            <?php the_sub_field('date'); ?>
               <div class="magazineFeat_Img_thub">
                  <a href="<?php echo get_permalink( $p->ID ); ?>" >  
                    <img src="<?php the_sub_field('magazine_image'); ?>" />
                  </a>  



              </div>
		 </li>     
    <?php endwhile; ?>
    </ul>
<?php endif; ?>



====


NICE


<?php if( have_rows('magazine_in_the_menu', 7890) ):?>
    <ul>
    <?php while( have_rows('magazine_in_the_menu', 7890) ): the_row();?>
        <li class="mangazine-top-menu"> 
             <strong><a href="/boardAgenda/magazine/">
               <?php the_sub_field('title'); ?>
            </a></strong>
            <?php the_sub_field('date'); ?>
               <div class="magazineFeat_Img_thub">
                  <a href="<?php the_sub_field('magazine_link'); ?>" >  
                    <img src="<?php the_sub_field('magazine_image'); ?>" />
                  </a>  
              </div>
         </li>     
    <?php endwhile; ?>
    </ul>
<?php endif; ?>




