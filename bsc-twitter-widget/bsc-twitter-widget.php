<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://blueskycoding.com
 * @since             1.0.0
 * @package           Bsc_Twitter_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Twitter Widget
 * Plugin URI:        https://blueskycoding.com
 * Description:       "Brief" test for Saucal
 * Version:           1.0.0
 * Author:            Kyle Rusak
 * Author URI:        https://blueskycoding.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bsc-twitter-widget
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Bsc_Twitter_Widget {

	//To Do:
	//Format header for output
	//Option screen for API credentials in admin
	//Start API
		
	public function __construct() {
	
		require plugin_dir_path( __FILE__ ) . '/includes/class-bsc-twitter-widget-registrations.php';

		add_action( 'init', 										array($this, 'bsc_twitter_endpoint')); //Endpoint
		add_filter( 'woocommerce_account_menu_items', 				array($this, 'bsc_twitter_link_my_account')); //Tab link
		add_action( 'woocommerce_account_bsc-twitter_endpoint', 	array($this, 'bsc_twitter_content')); //UI content
		add_action( 'admin_post_save_bsc_twitter', 					array($this, 'bsc_save_twitter_settings'));
		add_action( 'widgets_init', 								array($this, 'bsc_register_widget'));
		add_action('init', 											array($this, 'bsc_twitter_start_session'),1);
	
		register_activation_hook( __FILE__, array($this, 'bsc_flush') );
		register_deactivation_hook( __FILE__, array($this, 'bsc_flush') );		
		
	}

	public function bsc_twitter_start_session() {
	
		if(!session_id()) {
			session_start();
		}
		
	}

	public function bsc_twitter_end_session() {
	
		$_SESSION['bsc_twitter_args'] = null;
		
	}
		
	public function bsc_register_widget() {
	
		register_widget( 'Bsc_Twitter_Widget_Widget' );
		
	}
	
	public function bsc_twitter_output(){

		include_once( plugin_dir_path( __FILE__ ) . '/templates/bsc_twitter_output_template.php' );
		
	}

	private function bsc_save_twitter_settings(){
	
		if( isset( $_POST['woocommerce-bsc-twitter-nonce'] ) && wp_verify_nonce( $_POST['woocommerce-bsc-twitter-nonce'], 'woocommerce-bsc-twitter-nonce') ) {

			$user_id 	= get_current_user_id();
			
			
			//!!!Come back and fix the keys and fields
			$key_1 		= sanitize_key( $_POST[''][''] );
			$meta_1 	= sanitize_text_field( $_POST[''][''] );
			update_user_meta( $key_1, $meta_1, true );
			$key_2 		= sanitize_key( $_POST[''][''] );
			$meta_2 	= sanitize_text_field( $_POST[''][''] );
			update_user_meta( $key_2, $meta_2, true );
			$key_3 		= sanitize_key( $_POST[''][''] );
			$meta_3 	= sanitize_text_field( $_POST[''][''] );
			update_user_meta( $key_3, $meta_3, true );	

			//could have made a pretty foreach loop if we started getting a large set of settings

			wp_redirect( ); //!!!Would have liked to set a message and add to the woo notice hook in the my account home but ran out of time
			exit;
			
		}else{
		
			die;//!!!Can fill with this with a user friendly message when I'm not on a deadline
		
		}	

	}
	
	public function bsc_twitter_endpoint(){
	
		add_rewrite_endpoint( 'bsc-twitter', EP_ROOT | EP_PAGES );
	
	}
	
	private function bsc_flush(){
	
		flush_rewrite_rules();
	
	}
	
	public function bsc_twitter_link_my_account( $items ){
	
		$items['bsc-twitter'] = 'Your Twitter Feed';
		return $items;
	
	}
	
	private function bsc_twitter_widget_account_settings(){
	
		include_once( plugin_dir_path( __FILE__ ) . '/templates/bsc_twitter_template.php' );
	
	}
	
	public function bsc_twitter_content(){
	
		echo '<h1>Your Live Widget Settings</h3><p>Here you can setup your own sidebar display to track your favorite twitter accounts while you shop. We also suggest following @saucal too.</i></p>';
		echo $this->bsc_twitter_widget_account_settings();	
		echo '<h3>Here is what your feed will look like:</h3>';
		echo $this->bsc_twitter_output();	
	
	}
	
	public function bsc_get_user_args( $user_id ){
	
		if( get_user_meta( $user_id, 'bsc_twitter', true ) ){
		
			$args = array();
			$args['accounts'] 	= get_user_meta( $user_id, 'bsc_twitter', true );
			$args['count'] 		= str_getcsv( get_user_meta( $user_id, 'bsc_twitter_count', true ) );
			$args['days']  		= get_user_meta( $user_id, 'bsc_twitter_days', true );
			//$args['destroy'] for caching we don't need to constantly query twitter, but we can set an interval like 10 minutes
			return maybe_serialize( $args );

		}else{
		
			return false;
		
		}
			
	}
	
	public function bsc_set_session(){
	
		if( is_user_logged_in() ){
		
			$user_id = get_current_user_id();
			$_SESSION['bsc_twitter_arg'] = bsc_get_user_args( $user_id );
		
		}
	
	}
	
	public function bsc_get_post_args(){
	
		if( ! is_user_logged_in() ){
		
			if( $_SESSION['bsc_twitter_args'] ){ //
			
				$args = maybe_unserialize( $_SESSION['bsc_twitter_args']);
				return $args;
			
			}else{
			
				$user_id = get_current_user_id();
				return $this->bsc_get_user_args( $user_id );
			
			}
		
		}else{
		
			return false;
		
		}
	
	}
	
	public function bsc_get_post_response(){

		$args = bsc_get_post_args();
		
		if( $args ){

			$json = wp_remote_post('https://httpbin.org/post', $args );
			
			if( is_wp_error( $json ) ) {
			
				return $json->get_error_message();
				
			}		
			
			$array = json_decode( $json['header'], true);
				 
			return $array;
		
		}else{
		
			return 'Please visit your twitter account settings to update your feed';
		
		}
		
		//!!!Still need to incorporate WP_Object_Cache so we're not running this on every page, plus determine an interval to requery twitter
		
		
    }
	
}

$bsc_twitter = new Bsc_Twitter_Widget();
