<?php
add_action('wp_ajax_woo_filter', 'woo_shop_filter');
add_action('wp_ajax_nopriv_woo_filter', 'woo_shop_filter');
function woo_shop_filter(){


	global $wp_query;
	$params = array(
		'posts_per_page' => 16,
		'post_type' => 'product',
		'paged' => get_query_var('paged') ?: 1
	);

	if( isset( $_POST['cat-filter'] ) )
			$params['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $_POST['cat-filter'],
					'operator' => 'IN'
				)
			);

	$query = new WP_Query( $params ); ?>
	<ul class="products columns-4">
		<?php if( $query->have_posts() ) :
			while( $query->have_posts() ): $query->the_post();
			global $product;
			?>
				<li class="product">
					<a href="<?php the_permalink(); ?>">
						<div class="product__image">
							<?php
							if( has_post_thumbnail() ) {
								the_post_thumbnail();
							}
							else {
								echo '<img src="'.get_bloginfo("template_url").'/images/no-image.png" />';
							}
							?>
						</div>
						<div class="product__info">
							<h4><?php echo  $query->post->post_title; ?></h4>
							<?php if( $product->get_price_html() ){ ?>
								<p><?php echo $product->get_price_html(); ?></p>
							<?php } ?>
						</div>

					</a>
				</li>
			<?php endwhile; ?>
			<div class="">
				<?php   ?>
			</div>
			<?php
			the_posts_pagination();
			wp_reset_postdata();
		else :
			echo 'No posts found';
		endif; ?>
		</ul>
		<?php

		die();
}