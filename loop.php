<div class="row">
  <div class="col-xl-3">
    <h4>product categories</h4>
    <form id="filter" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
    <?php
    $args = array(
        'taxonomy'   => "product_cat",
        'hide_empty' => 0,
        'exclude' => 15,
        'hierarchical' => false,
        'parent'=> 0,
    );
    $product_categories = get_terms($args);
    foreach( $product_categories as $cat){
        echo '<label class="filter-label"><input name="cat-filter[]" data-name="'.$cat->name.' " type="checkbox" value=" ' .$cat->term_id . ' ">' . $cat->name . '</label>';
    }//$product_categories
    ?>
    <input type="hidden" name="action" value="woo_filter">
    </form>

  </div>
  <div class="col-xl-9">
    <div class="filter-params"></div>


    <div id="response">

      <ul class="products columns-4">
      	<?php

          global $wp_query;
      		$args = array(
      			'post_type' => 'product',
      			'posts_per_page' => 16,
            //'paged' => $paged,
            'paged' => get_query_var('paged') ?: 1 // страница пагинации
      			);
      		$loop = new WP_Query( $args );
      		if ( $loop->have_posts() ) {
      			while ( $loop->have_posts() ) : $loop->the_post();
      				wc_get_template_part( 'content', 'product' );
      			endwhile;
              the_posts_pagination();
      		} else {
      			echo __( 'No products found' );
      		}
      		wp_reset_postdata();
      	?>
      </ul>

  <?php
	// woocommerce_product_loop_start();
	// if ( wc_get_loop_prop( 'total' ) ) {
	// 	while ( have_posts() ) {
	// 		the_post();
  //
	// 		/**
	// 		 * Hook: woocommerce_shop_loop.
	// 		 */
	// 		do_action( 'woocommerce_shop_loop' );
  //
	// 		wc_get_template_part( 'content', 'product' );
	// 	}
	// }
  //
	// woocommerce_product_loop_end();
  ?>

  <?php
	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	//do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

?>
    </div>
  </div><!--#col9-->
</div><!--#row-->