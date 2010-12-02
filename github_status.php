<?php
/**
 * @package github Status
 * @author Roberto Torella
 * @version 1.0.0
 */
/*
Plugin Name: github status
Plugin URI: http://www.ganglio.eu/home
Description: This widget shows the latest activity for a specific github user
Author: Roberto Torella
Version: 1.0.0
Author URI: http://www.ganglio.eu/home
*/

require_once("gitHub.php");

function github_status() {

	$options = get_option('widget_github_status');
	
}

function github_status_widget($args) {
	extract($args);
	$options = get_option('widget_github_status');
	$title = empty($options['title']) ? __('github Activity') : apply_filters('widget_title', $options['title']);

	echo $before_widget;
	echo $before_title . $title . $after_title;
	github_status();
	echo $after_widget;
}

/**
 * Register all of the default WordPress widgets on startup.
 *
 * Calls 'widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 2.2.0
 */
function github_status_widget_register() {
	if ( !is_blog_installed() )
		return;
	$widget_ops = array('classname' => 'github_status_widget', 'description' => __( "github Activity Widget") );
	wp_register_sidebar_widget('github_status', __('Last Scrobbled'), 'github_status_widget', $widget_ops);
	wp_register_widget_control('github_status', __('Last Scrobbled', 'github_status_widget'), 'github_status_widget_control' );
	if ( is_active_widget('github_status_widget') ) {
		add_action('wp_head', 'github_status_css');
	}
}

add_action('init', 'github_status_widget_register', 1);

// We need some CSS to position the paragraph
function github_status_css() {
	echo "
	<style type='text/css'>
	.github_status {
		position: relative;
		font-family: monospace, monospace;
		color: #999999;
		text-align: center;
	}
	
	.github_status .artist,
	.github_status .title,
	.github_status .album {
		text-align: left;
	}
	
	.github_status .title {
		color: #555555;
		font-size: 1.05em;
	}
	
	.github_status .date {
		text-align: right;
		font-size: 8px;
	}
	</style>
	";
}

/**
 * Manage WordPress Tag Cloud widget options.
 *
 * Displays management form for changing the tag cloud widget title.
 *
 * @since 2.3.0
 */
function github_status_widget_control() {
	$options = $newoptions = get_option('widget_github_status');
	
	if ( isset($_POST['github_status-submit']) ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST['github_status-title']));
		$newoptions['number'] = strip_tags(stripslashes($_POST['github_status-number']));
		$newoptions['username'] = strip_tags(stripslashes($_POST['github_status-username']));
	}
	
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_github_status', $options);
	}
	
	$title = attribute_escape( $options['title'] );
	$number = attribute_escape( $options['number'] );
	$username = attribute_escape( $options['username'] );
	
?>
	<p><label for="github_status-title">
	Title: <input type="text" class="widefat" id="github_status-title" name="github_status-title" value="<?php echo $title ?>" /></label>
	</p>
	<p><label for="github_status-number">
	Number of commits: <input type="text" class="widefat" style="width: 25px; text-align: center;" id="github_status-number" name="github_status-number" value="<?php echo $number ?>" /></label>
	</p>
	<p><label for="github_status-username">
	Username: <input type="text" class="widefat" id="github_status-username" name="github_status-username" value="<?php echo $username ?>" /></label>
	</p>
	<input type="hidden" name="github_status-submit" id="github_status-submit" value="1" />
<?php //941368eccf0d5e3decd74891dc63f561
}

?>
