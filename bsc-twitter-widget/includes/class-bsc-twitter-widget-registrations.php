<?php
 
class Bsc_Twitter_Widget_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(false, $name = 'BSC Twitter Widget'); //Omitted Description: shows boring header info :)
    }
	
    function widget($args, $instance) {	
        extract( $args );
        $title 			= apply_filters('widget_title', $instance['title']);
        $message 		= $instance['message'];
		$Bsc_Twitter 	= new Bsc_Twitter_Widget;
		
        echo $before_widget;
			if ( $title ){
				echo $before_title . $title . $after_title;
				echo $bsc_Twitter->bsc_twitter_content();			
			}
		echo $after_widget; 

    }
 
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form($instance) {	
 
        $title 		= esc_attr($instance['title']);
       // $message	= esc_attr($instance['message']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
		
    }

} 