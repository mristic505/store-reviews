<?php
/**
 * Plugin Name: DVOJKA
 * Plugin URI: http://mristic.com
 * Description: Allows users to love your posts
 * Version: 1.0.0
 * Author: Mateja Ristic
 * Author URI: http://mristic.com
 * License: GPL2
 */

function add_ajaxurl_cdata_to_front(){ ?>
    <script type="text/javascript"> //<![CDATA[
        ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
    //]]> </script>
<?php }
add_action( 'wp_head', 'add_ajaxurl_cdata_to_front', 1);

// ADD JS
function reviews_and_rating_wp_enqueue_scripts() {
		wp_enqueue_style( 'rateyo', 'https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.css' );
		wp_enqueue_script( 'rateyo', 'https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'reviews_and_rating', plugins_url( '/reviews_and_rating.js', __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_style( 'reviews_and_rating', plugins_url( '/reviews_and_rating.css', __FILE__ ), '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'reviews_and_rating_wp_enqueue_scripts' );

///////////////////////////// SHOW REVIEWS STATS ////////////////////////////
function show_reviews_stats(){

	// SET REVIEWS STATS VARIABLES
	$number_of_reviews = 0;
	$rating_5_count = 0;$rating_4_count = 0;$rating_3_count = 0;$rating_2_count = 0;$rating_1_count = 0;

	// START LOOP
	$args = array(	'post_type' 	=> 'review', 	
					'post_status'   => array( 'draft', 'publish' )
				 );
    $custom_query = new WP_Query($args); 
    if ( $custom_query->have_posts() ) :
	while($custom_query->have_posts()) : $custom_query->the_post(); 

	$number_of_reviews++;
	
	// GET META VALUES
	$product_rating_value = get_post_meta( get_the_ID(), 'product_rating', true );

	if ($product_rating_value == 1) $rating_1_count++;  
	if ($product_rating_value == 2) $rating_2_count++; 
	if ($product_rating_value == 3) $rating_3_count++; 
	if ($product_rating_value == 4) $rating_4_count++; 
	if ($product_rating_value == 5) $rating_5_count++;
		 
	endwhile;

	echo '<span id="number_of_reviews" data-nor="'.$number_of_reviews.'"></div>';
	$overall_rating = round(($rating_1_count*1+$rating_2_count*2+$rating_3_count*3+$rating_4_count*4+$rating_5_count*5)/$number_of_reviews);
	?>

	<div id="bv-rev-stats">
	    <div class="rev_stats_table clearfix row-eq-height">
	        <div class="col-sm-6 rating_snapshot">
	            <h4>Rating Snapshot</h4>
	            <div class="levo">
	                <ul id="rating_distribution">
	                    <li data-count="<?php echo $rating_5_count; ?>" data-rating="5" data-total-results="<?php echo $number_of_reviews; ?>"><span class="rating_value">5</span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
	                        <div class="progress rating_bar">
	                            <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div><?php echo $rating_5_count; ?>
	                    </li>
	                    <li data-count="<?php echo $rating_4_count; ?>" data-rating="4" data-total-results="<?php echo $number_of_reviews; ?>"><span class="rating_value">4</span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
	                        <div class="progress rating_bar">
	                            <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div><?php echo $rating_4_count; ?>
	                    </li>
	                    <li data-count="<?php echo $rating_3_count; ?>" data-rating="3" data-total-results="<?php echo $number_of_reviews; ?>"><span class="rating_value">3</span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
	                        <div class="progress rating_bar">
	                            <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div><?php echo $rating_3_count; ?>
	                    </li>
	                    <li data-count="<?php echo $rating_2_count; ?>" data-rating="2" data-total-results="<?php echo $number_of_reviews; ?>"><span class="rating_value">2</span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
	                        <div class="progress rating_bar">
	                            <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div><?php echo $rating_2_count; ?>
	                    </li>
	                    <li data-count="<?php echo $rating_1_count; ?>" data-rating="1" data-total-results="<?php echo $number_of_reviews; ?>"><span class="rating_value">1</span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
	                        <div class="progress rating_bar">
	                            <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div><?php echo $rating_1_count; ?>
	                    </li>
	                </ul>
	            </div>
	        </div>
	        <div class="col-sm-6 average_rating">
	            <h4>Average Customer Ratings</h4>
	            <div class="desno"><span class="overall_rating users_rating_<?php echo $overall_rating;?>" data-rating="4">Overall <span class="stars"><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span></span> <span class="aor2"><?php echo $overall_rating; ?></span></span>
	            </div>
	        </div>
	    </div>
	</div>

	<?php

	// IF NO REVIEWS FOUND
	else:
	echo '<div>No Reviews</div>';	
	endif;
	wp_reset_postdata(); // END LOOP
	die();
}
add_action( 'wp_ajax_show_reviews_stats', 'show_reviews_stats' );
add_action( 'wp_ajax_nopriv_show_reviews_stats', 'show_reviews_stats' );

///////////////////////////// SHOW REVIEWS ////////////////////////////
function show_reviews() { ?>
	

	<?php // START LOOP

	// Loop variables
	$orderby = $_POST['orderby'];
	$order = $_POST['order'] ;
	$posts_per_page = $_POST['posts_per_page'];

	$args = array(	'post_type' 	=> 'review', 	
					'post_status'   => array( 'draft', 'publish' ),
					'meta_key'		=> 'product_rating',
					'orderby' 		=> $orderby,
            		'order' 		=> $order,
            		'posts_per_page'=> $posts_per_page
				 );
    $custom_query = new WP_Query($args); 
    if ( $custom_query->have_posts() ) :
	while($custom_query->have_posts()) : $custom_query->the_post(); 

	$number_of_reviews++;
	
	// GET META VALUES
	$product_rating_value = get_post_meta( get_the_ID(), 'product_rating', true );

	?>
	<div><?php the_title(); ?> rating: <?php echo $product_rating_value; ?></div>
	<div id="bv-rev-display">
	    <div>
	        <ul id="reviews_list">
	            <li><span class="users_rating users_rating_<?php echo $product_rating_value; ?>" data-rating="<?php echo $product_rating_value; ?>"><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span><span class="glyphicon glyphicon-star" aria-hidden="true"></span></span><span class="users_nickname" data-rating="mristic99"><?php the_author(); ?></span>
	                <div class="review_title"><?php the_title(); ?></div>
	                <div class="review_text"><?php the_content(); ?></div>
	            </li>
	        </ul>
	    </div>
	</div>
	<?php endwhile;
	// IF NO REVIEWS FOUND
	else:
	echo '<div>No Reviews Posted For This Product</div>';	
	endif;
	wp_reset_postdata(); // END LOOP
	die();
}
add_action( 'wp_ajax_show_reviews', 'show_reviews' );
add_action( 'wp_ajax_nopriv_show_reviews', 'show_reviews' );