<?php
global $nine3_Membership;

// Check the resource restriction, if any, and show the associated icon
$can_access = $nine3_Membership->user_can_access_to_resource();
$icon = $can_access ? '' : 'fa fa-lock';

$post_id = get_the_id();

//thumbnail
$post_image_url = get_field('the_featured_post_image', $post_id);

$partner = get_field('partner', $post_id);

//getting post excerpt
//$excerpt = get_the_excerpt(); 
$post_content = get_post_filtered_content( $post_id );
$excerpt      = crop_text( $post_content, 300 );

?>

	<div class="resource_element">
		<img src="<?php echo $post_image_url['url']; ?>" class="resource_img">
		<div class="resource_data_wrapper">
			<div class="resource_upper">
				<div class="resource_upper_left">
					<p class="resource_details"><span class="resource_type">
            <?php
            $terms = wp_get_post_terms( get_the_ID(), 'resources-categories' );
            foreach($terms as $term):

            	?>
            <a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>
          <?php endforeach; ?>
            </span> Brought to you by <a href="<?php echo get_the_permalink( $partner->ID ); ?>"><?php echo $partner->post_title; ?></a>
          </p>
					<h4 class="resource_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
				</div>
				<div class="resource_upper_right">
				<?php if( get_field('pdf_pdf') && $can_access ) :
					$link = add_query_arg( 'resource', get_the_ID(), home_url('/download/') );
				?>
					<a href="<?php echo esc_url( $link );  ?>" class="resource_download_link" download="download"><i class="<?php echo $icon; ?> resource_first_icon"></i> Download <i class="fa fa-arrow-circle-down resource_second_icon"></i></a>
				<?php endif; ?>
				</div>
			</div>
			<div class="resource_main">
				<p><?php echo $excerpt; ?></p>
			</div>
		</div>
	</div>
