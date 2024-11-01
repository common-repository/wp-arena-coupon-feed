<?php
/**
 * Plugin Name:  WP Arena Coupon Feed
 * Plugin URI: 	 https://wparena.com/deals/
 * Description:  A very simple plugin to grab latest coupons, deals, discounts and savings and more from WPArena.com right inside your WordPress Dashboard. The number of deals displayed, and the category of deals displayed, can both be configured.
 *               It is based on the 'WPArena Dashboard Feed' plugin by WPArena, but has been adapted to show feeds from WPArena.com/deals/ instead.
 * Version: 	 1.0
 * Author: 	 Jazib
 * Author URI: 	 https://wparena.com/
 * License: 	 GPL2
 * License 	 URI: http://www.gnu.org/licenses/gpl-2.0.html
**/


// Creates the custom dashboard feed RSS box
function wparena_dashboard_deals_feed_output() {
	
	$widget_options = wparena_deals_dashboard_options();
	
	// Variable for RSS feed
	$wparena_feed = 'https://wparena.com/deals/feed';			
	
	echo '<div class="rss-widget" id="wparena-rss-widget">';
		wp_widget_rss_output(array(
			'url' => $wparena_feed,
			'title' => 'Latest Deals from WP Arena',
			'items' => $widget_options['posts_number'],
			'show_summary' => 0,
			'show_author' => 0,
			'show_date' => 0
		));
	echo "</div>";
}


// Function used in the action hook
function wparena_add_deals_dashboard_widgets() {	
	wp_add_dashboard_widget('wparena_dashboard_deals_feed', 'Latest Deals from WPArena.com', 'wparena_dashboard_deals_feed_output', 'wparena_dashboard_deals_setup' );
}


function wparena_deals_dashboard_options() {	
	$defaults = array( 'posts_number' => 5 );
	if ( ( !$options = get_option( 'wparena_dashboard_deals_feed' ) ) || !is_array($options) )
		$options = array();
	return array_merge( $defaults, $options );
}


function wparena_dashboard_deals_setup() {
 
	$options = wparena_deals_dashboard_options();
 
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'wparena_dashboard_deals_feed' == $_POST['widget_id'] ) {
		foreach ( array( 'posts_number', 'posts_feed' ) as $key )
				$options[$key] = $_POST[$key];
		update_option( 'wparena_dashboard_deals_feed', $options );
	}
 
?>
 
		<p>
			<label for="posts_number"><?php _e('How many items?', 'wparena_dashboard_deals_feed' ); ?>
				<select id="posts_number" name="posts_number">
					<?php for ( $i = 5; $i <= 10; $i = $i + 1 )
						echo "<option value='$i'" . ( $options['posts_number'] == $i ? " selected='selected'" : '' ) . ">$i</option>";
						?>
					</select>
				</label>
 		</p>

 
<?php
 }


// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'wparena_add_deals_dashboard_widgets' );

?>