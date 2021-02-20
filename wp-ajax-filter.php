<?php
/**
 * Plugin Name: WP Ajax Filter
 * Plugin URI: 
 * Description: Мой кастомный аякс фильтр
 * Version: 
 * Author: Me
 * Author URI: 
 * Text Domain: 
 * Domain Path: 
 * Requires at least: 5.4
 * Requires PHP: 7.0
 *
 */


class WPNamelessFilter {
    
    public function __construct()
    {   
        
        add_shortcode('query_shortcode', array($this, 'filter_shortcode'));
        add_action( 'wp_enqueue_scripts', array($this, 'filter_scripts'));
        add_action( 'wp_ajax_filtered', array( $this, 'ajax_filter_function' ) );
        add_action( 'wp_ajax_nopriv_filtered', array( $this, 'ajax_filter_function' ) );
    }

    

    public function filter_shortcode( $atts ){
        
        
        $atts = shortcode_atts( [
            'post_type' => 'post',
            'amount'    => 6,
            'paged'     => '',
        ], $atts );
    
    $this->filter_buttons();
    echo '<div class="row" id="filter">';
    $this->filter_body( $atts );
    echo '</div>';
        
        
    }

    
    public function filter_body( $params ){
        
        $tax_query = array(
            'relation' => 'OR'
        );
        if( isset($_POST['terms']) ){
            $terms = $_POST['terms'];
            if ( $terms !== '' ){
            $categories = explode(",",  $terms);
        
                $tax_query[] =  array(
                                'taxonomy' => 'category',
                                'field' => 'id',
                                'terms' => $categories,
                                'include_children' => false
                );
            }
        }
        

        $args = array(
            'post_type'      => $params['post_type'],
            'posts_per_page' => $params['amount'],
            'paged'          => $params['paged'],
            'tax_query'      => $tax_query,
        );  

        $query = new WP_Query( $args );
        $post_count = $query->found_posts;        
        
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post(); ?>
                <div class="col-sm-4 mb-5">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <p class="card-text">Краткое описание</p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Go somewhere</a>
                    </div>
                    </div>
                </div>
            <?php }
        } else {
            // Постов не найдено
        }
        wp_reset_postdata();

        $this->pagination( $post_count, $params['amount'] );

    }

    public function filter_buttons(){
        
        $terms  = get_terms( 'category' );
        $form = '<div class="col-12 d-flex flex-wrap mb-5">';
        foreach ($terms as $term){
            $form .= '<a href="#" data-term="'.$term->term_id.'" class="btn btn-primary me-2 btn-filter">';
            $form .= $term->slug;
            $form .= '</a>';          
        }
        $form .= '</div>'; 

        echo $form;
    }

    public function pagination( $count, $amount  ){  
        $page_count = ceil( $count / $amount );

        $pagination = '';

        ob_start(); ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination"> 
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <?php
                    for ($i = 1; $i <= $page_count; $i++) {
                        echo '<li class="page-item"><a class="page-link" href="#">'.$i.'</a></li>';
                    } 
                ?>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>     
            </ul>
        </nav>
        <?php
        
        $pagination = ob_get_contents();
        ob_end_clean();

        echo $pagination;
        
    }

    public function filter_scripts(){
        wp_enqueue_script('fetchjs', '/wp-content/plugins/wp-ajax-filter/assets/main.js ', '', null, true);
		wp_localize_script( 'fetchjs', 'plugin', array(
				'nonce'    => wp_create_nonce( 'plugin' ),
				'ajax_url' => admin_url( 'admin-ajax.php' )
		));
    }


    public function ajax_filter_function( $params ){
        
        if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'plugin' ) )
        die('Permission denied');
        
        $params = [
            'post_type' => 'post',
            'amount'    => 3,
            'paged'     => $_POST['paged'],
        ];

        $this->filter_body( $params );     
        wp_die();
        
    }
}
 
$wpFilter = new WPNamelessFilter();
