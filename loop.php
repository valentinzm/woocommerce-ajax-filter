<form id="filter"  method="POST" class="filter__form">
    <?php
    $args = array(
        'taxonomy'   => "category",
        'hide_empty' => false,
        'exclude' => 1,

    );
    $categories = get_terms($args);
    foreach( $categories as $cat){
        echo '<a href="#" data-cat="'.$cat->term_id.'" class="filter__item">' . $cat->name . '</a>';
    }
    ?>
    <input type="text" name="filter-search" placeholder="Поиск по новостям.." class="filter__search">

    </form>

    <div id="response">
       <?php
       $args = array(
       	'posts_per_page' => 5,
        'paged' => 1,
       );

       $query = new WP_Query( $args );
       $count = $query->post_count;
       // Цикл
       if ( $query->have_posts() ) {
       	while ( $query->have_posts() ) {
       		$query->the_post();
          get_template_part('/template-parts/blog/single-blog');
       	}
       } else {
       	// Постов не найдено
       }
       // Возвращаем оригинальные данные поста. Сбрасываем $post.
       wp_reset_postdata();
       if( $count > 5){
         echo '<a href="#" class="blog__loadmore">Смотреть еще новости</a>';
       }
       ?>
       </div>