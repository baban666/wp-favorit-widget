<?php
/**
 * Plugin Name: WFM Cats
 */



add_action('widgets_init', 'wfm_cats');



function wfm_cats(){
    register_widget('WFM_Cats');
}


class WFM_Cats extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
	        'classname' => 'WFM_Cats',
            'name' => 'Favorit category',
            'description' => 'My Widget is awesome',
        );
        parent::__construct( 'WFM_Cats','Favorits category', $widget_ops );
    }

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// var_dump($instance);
		$count = isset($instance['count']) ? $instance['count'] : 5;
		$cats = get_categories();
		// print_r($cats )
		?>
		<p>
			<label for="<?php echo $this->get_field_id('count') ?>">Count posts</label>
			<input id="<?php  echo $this->get_field_id('count') ?>"
			       name="<?php  echo $this->get_field_name('count') ?>"
			       type="text"
			       value="<?php echo $count; ?>"
			       class="widefat">
		</p>

		<?php echo '<p>'; ?>
			<?php foreach ($cats as $cat): ?>
					<input
						id="<?php echo $this->get_field_id('cat') . $cat->cat_ID;?>"
						name="<?php  echo $this->get_field_name('cat'); ?>[]"
						type="checkbox"
						value="<?php echo $cat->cat_ID; ?>"
						<?php if(is_array($instance['cat']) && in_array($cat->cat_ID, $instance['cat']))  echo 'checked'; ?>
					>
					<label for="<?php echo $this->get_field_id('cat') . $cat->cat_ID?>"><?php echo $cat->name ?></label><br>
			<?php endforeach; ?>
       <?php echo '<p>'; ?>


<?php
	}

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        // outputs the content of the widget
	    if(!empty($instance)){
	    	foreach ($instance['cat'] as $cat_id){
			    $cat = get_category($cat_id);
			    global $post;
			   $posts = get_posts(array(
			   	'category' => $cat_id,
				 'numberposts'  => $instance['count']
			   ));

			   echo '<div class="widget">';
			   echo "<h6>{$cat->name}<h2>";
			   echo '<ul>';
			   foreach ($posts as $post){
			   	setup_postdata($post);
			   	echo '<li><a href="'. get_permalink() .'">'. get_the_title() . '</a></li>';
			   }

			   echo '</ul>';
			   echo '</div>';
			   // var_dump($posts);
			    wp_reset_postdata();

		    }
	    }
	    //print_r($args);
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
	    $new_instance['count'] = ((int) $new_instance['count']) ? abs($new_instance['count']) : 5;
	    foreach ($new_instance['cat'] as $k => $v){
		    $new_instance['cat'][$k] = (int)$v;
	    }
	    return $new_instance;
    }
}