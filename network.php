<?php
//* Template Name: Network
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
			<header class="clearfix">


					<?php the_content(); ?>

			</header>

			<h2 class="front-title">CONNECT ONLINE</h2>

			<ol class="latest-two-stories connect-columns equalize-parent">
				<li class="latest-stories clearfix equalize-me alpha">
					<div>
						<div class="header">
							<span class="fa fa-twitter"></span>TWITTER
						</div>

						<ul class="ul-content">
							<?php dynamic_sidebar( 'network-twitter' ); ?>
						</ul>
					</div>
				</li>

				<li class="latest-stories clearfix equalize-me omega">
					<div>
						<div class="header">
							<span class="fa fa-linkedin"></span>LINKEDIN
						</div>
						<p class="ul-content">
							We invite you to join the Board Agenda Discussion Group, the key destination for serving board members, non-executive directors and professional advisors seeking best practice in corporate governance.
						</p>
						<p class="ul-content">
							Our mission is to explore core issues in governance, strategic planning, risk and ethics. We invite you to join our group and join the debate.
						</p>
						<p class="ul-content">
							<a class="button" href="https://www.linkedin.com/groups/8307526/profile">JOIN</a>
						</p>
					</div>
				</li>
			</ol>

			<h2 class="front-title">RESEARCH PANEL</h2>

			<ol class="latest-two-stories equalize-parent">
				<li class="latest-stories clearfix equalize-me alpha">
					<div>
						<?php the_field( 'research_panel_left' ); ?>
					</div>
				</li>
				<li class="latest-stories clearfix equalize-me omega">
					<div>
						<?php the_field( 'research_panel_right' ); ?>
					</div>

					<?php
					$nm = Nine3_Simple_Membership::getInstance();

					if( $nm->get_user_level() < 0 ) :
					?>
						<div>
							<p>
								<a href="<?php echo esc_url( home_url( '/register-subscribe' ) ) ?>" class="button">REGISTER</a>
							</p>
						</div>
					<?php endif; ?>
				</li>
			</ol>

			<h2 class="front-title">EVENTS</h2>
			<div class="events-list">
			<?php
				$args = array(
					'post_type' => 'events',
					'orderby' => 'date',
					'posts_per_page' => 4,
					'meta_query' => array(
						array(
							'key'     => 'start_date',
							'value'   => date( 'Ymd', mktime() ),
							'compare' => '>=',
						),
					),
				);

				$posts = new WP_Query( $args );
				$class = '';
				while( $posts->have_posts() ) {
					$posts->the_post();

					$class = ( $class == 'first' ) ? 'last' : 'first';
					include( locate_template( 'template-parts/events-small-list.php' ) );
				}

				wp_reset_postdata();
			?>
			</div>
			<!-- <a class="browse-all button clearfix" href="/event">VIEW ALL&nbsp;&nbsp;&nbsp;<span class="fa fa-chevron-circle-right"></span></a> -->
		</article>
	</div>
<?php
}

genesis();
