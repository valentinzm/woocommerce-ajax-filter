add_action('wp_ajax_blog_filter', 'blog_filter');
add_action('wp_ajax_nopriv_blog_filter', 'blog_filter');
function blog_filter(){

	$page = $_POST['page'];
	$categories = $_POST['categories'];
	$s = $_POST['search'];


	$params = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 5,
		's' => $s,
		'cat' => array( $_POST['categories'] ),
	);
	//
	$query = new WP_Query( $params );
	$count = $query->found_posts;
	if( $query->have_posts() ) :
			while( $query->have_posts() ): $query->the_post();
				get_template_part('/template-parts/blog/single-blog');
			endwhile;
			if($count > 5){
					echo '<a href="#" class="blog__loadmore">Смотреть еще новости</a>';
			}
			wp_reset_postdata();
		else :
			echo '<h4 class="blog__title">Постов не найдено</h4>';
		endif;
		die();

}

add_action('wp_ajax_blog_loadmore', 'blog_loadmore');
add_action('wp_ajax_nopriv_blog_loadmore', 'blog_loadmore');
function blog_loadmore(){

	$page = $_POST['page'];
	$categories = $_POST['categories'];
	$s = $_POST['search'];


	$params = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 5,
		'paged' => $page,
		's' => $s,
		'cat' => array( $_POST['categories'] ),
	);
	//
	$query = new WP_Query( $params );
	$count = $query->found_posts;
	if( $query->have_posts() ) :
			while( $query->have_posts() ): $query->the_post();
				get_template_part('/template-parts/blog/single-blog');
			endwhile;
			wp_reset_postdata();
		else :
			echo '<h4 class="blog__title">Постов не найдено</h4>';
		endif;
		if($count > 5){
				echo '<a href="#" class="blog__loadmore">Смотреть еще новости</a>';
		}
		die();

}