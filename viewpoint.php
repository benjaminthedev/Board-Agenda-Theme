<?php
//* Template Name: Viewpoint
//* This page features posts from one single category
add_action( 'genesis_meta', 'board_agenda_front_genesis_meta' );

function board_agenda_front_genesis_meta() {

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Remove the post content (requires HTML5 theme support)
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		//add_action( 'genesis_entry_content', 'sanders_front_content' );

		add_action( 'genesis_loop', 'boardagenda_network_top', 1);

		add_action('genesis_before_footer','board_agenda_front_latest_insight',1);
			/** Replace the standard loop with our custom loop */

		add_action( 'genesis_loop', 'boardagenda_appointsments_education', 2 );
}

function boardagenda_network_top(){ ?>
	<div class="wrap top-bottom-margin">
		<article <?php post_class( 'entry' ); ?>>
			<h2 class="front-title">VIEWPOINT</h2>

			<ol class="latest-two-stories viewpoint-columns poll">
				<li class="latest-stories clearfix alpha">
					<?php
						global $wpdb;

						//Check if there is any 'open poll'
						$no_poll = true;
						$polls = $wpdb->get_results("SELECT * FROM $wpdb->pollsq WHERE pollq_active > 0 ORDER BY pollq_id DESC");

						//Is there any available poll?
						if( $polls ) {
							foreach( $polls as $poll ) {
								$poll_ID = $poll->pollq_id;
								$check_voted = check_voted( $poll_ID );

								if(intval($check_voted) > 0 || (is_array($check_voted) && sizeof($check_voted) > 0) ) {
									continue;
								} else {
									$content = do_shortcode( '[poll id="' . $poll_ID . '"]' );

									echo board_agenda_poll_result( $poll_ID, $content );

									$no_poll = false;

									break;
								}

							} //endforeach

							if( $no_poll ) {
								//get the latest poll
								// $poll_ID = $wpdb->get_var( "SELECT MAX(pollq_id) FROM $wpdb->pollsq" );
								// $content = do_shortcode( '[poll id="' . $poll_ID . '" type="result"]' );
								// echo board_agenda_poll_result( $poll_ID, $content );

								//Already voted
								echo '<div id="polls-' . $poll_ID . '" class="wp-polls"><form id="polls_form_' . $poll_ID . '" class="wp-polls-form" action="" method="post">';
								// echo '<div id="poll-' . $poll_ID . '" class="wp-poll"><form>';
								$template_question = stripslashes(get_option('poll_template_voteheader'));
								$template_footer = stripslashes(get_option('poll_template_votefooter'));

								$poll_question_text = stripslashes($poll->pollq_question);
								$poll_question_id = intval($poll->pollq_id);
								$poll_question_totalvotes = intval($poll->pollq_totalvotes);
								$poll_question_totalvoters = intval($poll->pollq_totalvoters);
								$poll_question_active = intval($poll->pollq_active);
								$poll_start_date = mysql2date('d.m.Y', gmdate('Y-m-d H:i:s', $poll->pollq_timestamp));

								$template_question = str_replace("%POLL_QUESTION%", $poll_question_text, $template_question);
								$template_question = str_replace("%POLL_DATE%", $poll_start_date, $template_question);
								$template_question = str_replace( '%PARTNER_THUMBNAIL%', '<img src="" />', $template_question );
								$template_question = str_replace( '%PARTNER_AUTHOR%', '<a href="#">Partner Name</a>', $template_question );

								echo board_agenda_poll_result( $poll_ID, $template_question );

								//Show my answer
								$answer = $wpdb->get_var( "SELECT polla_answers from $wpdb->pollsa WHERE polla_aid = " . intval( $check_voted[0] ) );
								echo '<li><span class="already">Your vote:</span> ' . $answer . '</li>';

								//Remove the VOTE BUTTON
								$template_footer = preg_replace("/<a.*>(.*)<\/a>/", "", $template_footer);
								echo $template_footer;

								echo '</form></div>';
							}
						} else {
							//NO poll available
						}
					?>
				</li>

				<li class="clearfix omega related-omega">
					<div class="related-articles">
						<h4 class="widget-title">RELATED ARTICLES</h4>

						<ol>
							<?php
							if( have_rows( 'related_articles', 758 ) ) :
								while( have_rows( 'related_articles', 758 ) ) : the_row();
									$post = get_sub_field( 'article', 758 );
								?>
								<li class="front-board-moves-wrap clearfix">
									<a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_the_post_thumbnail( $post->ID ); ?></a>
									<p class="front-latest-date"><span class="topic-selector"><?php echo get_the_date( 'd.m.Y', $post->ID ); ?></p>
									<h3 class="front-latest-title" data-compare=".front-latest-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title ?></a></h3>
									<div class="front-latest-excerpt "><p><?php echo $post->post_excerpt ?></p></div>
								</li>
							<?php endwhile;
							endif;
							?>
						</ul>
					</div>
				</li>
			</ol>

			<h2 class="front-title">POLL ARCHIVE</h2>
			<div class="poll-archive equalize-parent">
				<?php
					//Get the latest 2 closed polls
					$page = isset( $_GET['show'] ) ? intval( $_GET['show'] ) : 1;
					$ipp = 6;
					$from = ( $page - 1 ) * $ipp;
					$to = $from + $ipp;
					$polls = $wpdb->get_results("SELECT * FROM $wpdb->pollsq WHERE pollq_active = 0 ORDER BY pollq_timestamp DESC LIMIT $from, $to");
					// $polls = array_merge( $polls, $polls );
					if( $polls ) :
						$class =  '';
						foreach( $polls as $poll ) {
							$class = ( $class == 'first' ) ? 'last' : 'first';

							echo '<div class="column equalize-me one-half ' . $class . '" data-compare=".column"><div>';
							echo '<h5>POLL RESULT</h5>';

							$content = '<span class="date">%POLL_DATE%</span>';
							$content .= do_shortcode( '[poll id="' . $poll->pollq_id . '" type="result"]' );

							echo board_agenda_poll_result( $poll->pollq_id, $content );

							echo '</div></div>';
						}

						$total = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->pollsq WHERE pollq_active = 0");
						$pages = ceil( $total / $ipp );

				if( $pages > 1 ) :
				?>
				<div class="archive-pagination pagination">
					<ul>
						<?php for( $i = 1; $i <= $pages; $i++ ) : ?>
						<li class="<?php echo ( $i == $page ) ? 'active' : '' ?>"><a href="<?php echo add_query_arg( array( 'show' => $i ) ); ?>"><?php echo $i ?></a></li>
						<?php endfor; ?>

						<?php if( ++$page <= $pages ) : ?>
						<li class="pagination-next"><a href="http://localhost/boardagenda/insight/page/2/">Next Page »</a></li>
						<?php endif; ?>
					</ul>
				</div>
				<?php endif; ?>
				<?php endif; ?>
			</div>
		</article>
	</div>
<?php
}

genesis();
