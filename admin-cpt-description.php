<?php
$cpt = str_replace( 'baedit-cpt-', '', $_GET['page'] );

if( isset( $_POST['cpt'] ) ) {
	update_option( 'ba_cpt_description_' . $cpt, $_POST[ $cpt ] );
	update_option( 'ba_cpt_sidebar_' . $cpt, intval( $_POST['sidebar'] ) );
}
?>

<div class="wrap">
	<h1><?php echo ucfirst( $cpt ) ?></h1>

	<form action="" method="post">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" style="position: relative;">

					<input type="hidden" name="cpt" value="<?php echo $cpt ?>">
					<?php wp_editor( get_option( 'ba_cpt_description_' . $cpt ), $cpt ); ?>

					<?php submit_button(); ?>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<div class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle ui-sortable-handle"><span>Filter</span></h3>
						<div class="inside">
								Assign sidebar:

								<?php $sidebar = get_option( 'ba_cpt_sidebar_' . $cpt, '1' ); ?>
								<ul>
									<li>
										<label>
											<input type="radio" name="sidebar" value="" <?php checked( $sidebar, '' ); ?>>
											None
										</label>
									</li>
									<li>
										<label>
											<input type="radio" name="sidebar" value="1" <?php checked( $sidebar, 1 ); ?>>
											Search 1
										</label>
									</li>
									<li>
										<label>
											<input type="radio" name="sidebar" value="2" <?php checked( $sidebar, 2 ); ?>>
											Search 2
										</label>
									</li>
								</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
