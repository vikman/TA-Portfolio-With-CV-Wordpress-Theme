<?php
/**
 * @package TA Portfolio
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title page-header">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="row">
			<div class="col-sm-12 col-md-7 col-lg-7 well well-sm">
				<?php
				if ( get_post_gallery() ) {
					echo get_post_gallery();
				} elseif ( has_post_thumbnail() ) {
					the_post_thumbnail();
				}
				?>
			</div>

			<div class="col-sm-12 col-md-5 col-lg-5">
				<p>Client: 
					<strong><a href="<?php echo get_post_meta( $post->ID, '_cmb_clienturl', true); ?>" target="_blank"><?php echo get_post_meta( $post->ID, '_cmb_clientname', true); ?></a></strong>
				</p>
				<p>Date:
					<strong><?php echo get_the_date(); ?></strong>
				</p>
				<p>Categories:
					<?php $terms = wp_get_post_terms( $post->ID, 'portfolio_tags', array( "fields" => "names" ) ); ?>
					<strong><?php echo implode( ' / ',$terms ); ?></strong>
				</p>
				<?php
				$content = strip_shortcode_gallery( get_the_content() );
				$content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) );
				echo $content;
				$Contributors = get_post_meta($post->ID, 'repeatable_fields', true);
				?>
				
				<?php 
				if (is_array($Contributors)) { ?>
					<p><b>Contributors: </b></p>
					<ul>
				<?php foreach ( $Contributors as $contributor ) {
						$link = "#";
						if(function_exists("rb_register_post_type_resume")) {
							$name = esc_attr( $contributor['name']);
							$name = strtolower($name);
							$name = str_replace(" ", "-", $name);
							$query = array(
									'post_type' => 'rb_resume',
									'name' => $name 
								); 
							$the_query = new WP_Query( $query );
							// The Loop
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									$link = get_permalink();
									break;
								}
							}
							/* Restore original Post Data */
							wp_reset_postdata();
						}
				?>
						<li>
							<a href="<?php echo $link; ?>"><?php echo esc_attr( $contributor['name'] ); ?></a> - <?php echo esc_attr( $contributor['job'] ); ?>
						</li>
				<?php } ?>
				</ul>
			<?php }?>
				
			</div>
		</div><!-- .row -->

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ta-portfolio' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'ta-portfolio' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->