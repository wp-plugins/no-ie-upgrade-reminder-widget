<?php
/*
Plugin Name: NO-IE
Plugin URI: http://www.Stephanis.info/
Description: If the user is currently using an outdated version of IE, this widget will display a reminder for them to upgrade.
Version: 1.0.1
Author: E. George Stephanis
Author URI: http://www.Stephanis.info/
*/

register_activation_hook	(	__FILE__,			array('noie', 'activate')		);
register_deactivation_hook	(	__FILE__,			array('noie', 'deactivate')	);
add_action					(	"widgets_init",		array('noie', 'register')		);

class noie {

	function activate()
	{
		update_option( 'noie_title', 'Update Your Browser!' );
		update_option( 'noie_random', 'YES' );
		update_option( 'noie_version', 6 );
		update_option( 'noie_browsers', 'firefox|chrome|opera|safari|ie' );
	}
	
	function deactivate()
	{
		delete_option( 'noie_title' );
		delete_option( 'noie_random' );
		delete_option( 'noie_version' );
		delete_option( 'noie_browsers' );
	}
	
	function register()
	{
		wp_register_sidebar_widget('no-ie', 'NO IE', array('noie', 'widget'));
		wp_register_widget_control('no-ie', 'NO IE', array('noie', 'control'));
	}
	
	function control()
	{
		if (isset($_POST['noie_title'])){		update_option( 'noie_title', esc_attr( $_POST['noie_title'] ) );						}
		if (isset($_POST['noie_version'])){		update_option( 'noie_version', esc_attr( $_POST['noie_version'] ) );					}
		if (isset($_POST['noie_browsers'])){	update_option( 'noie_browsers', esc_attr( implode( '|', $_POST['noie_browsers'] ) ) );	}
		if (isset($_POST['noie_random'])){		update_option( 'noie_random', esc_attr( $_POST['noie_random'] ) );						}
		?>
		<p><label><strong>Widget Title:</strong><br />
		<input class="widefat" type="text" name="noie_title" value="<?php echo get_option( 'noie_title' ); ?>" /></label></p>
		<p><strong>Version:</strong><br />
		<label><input type="radio" name="noie_version" value="6" <?php echo get_option('noie_version')=='6'?'checked="checked" ':''; ?>/> &le; IE 6</label><br />
		<label><input type="radio" name="noie_version" value="7" <?php echo get_option('noie_version')=='7'?'checked="checked" ':''; ?>/> &le; IE 7</label></p>
		<p><strong>Offer Links To:</strong><br />
		<label><input type="checkbox" name="noie_browsers[]" value="firefox" <?php echo strpos(get_option('noie_browsers'),'firefox')!==FALSE?'checked="checked" ':''; ?>/> <img src="<?php echo WP_PLUGIN_URL; ?>/no-ie/img/firefox.png" height="16" width="16" alt="Mozilla Firefox" /> Firefox</label><br />
		<label><input type="checkbox" name="noie_browsers[]" value="chrome" <?php echo strpos(get_option('noie_browsers'),'chrome')!==FALSE?'checked="checked" ':''; ?>/> <img src="<?php echo WP_PLUGIN_URL; ?>/no-ie/img/chrome.png" height="16" width="16" alt="Google Chrome" /> Chrome</label><br />
		<label><input type="checkbox" name="noie_browsers[]" value="safari" <?php echo strpos(get_option('noie_browsers'),'safari')!==FALSE?'checked="checked" ':''; ?>/> <img src="<?php echo WP_PLUGIN_URL; ?>/no-ie/img/safari.png" height="16" width="16" alt="Apple Safari" /> Safari</label><br />
		<label><input type="checkbox" name="noie_browsers[]" value="opera" <?php echo strpos(get_option('noie_browsers'),'opera')!==FALSE?'checked="checked" ':''; ?>/> <img src="<?php echo WP_PLUGIN_URL; ?>/no-ie/img/opera.png" height="16" width="16" alt="Opera" /> Opera</label><br />
		<label><input type="checkbox" name="noie_browsers[]" value="ie" <?php echo strpos(get_option('noie_browsers'),'ie')!==FALSE?'checked="checked" ':''; ?>/> <img src="<?php echo WP_PLUGIN_URL; ?>/no-ie/img/ie.png" height="16" width="16" alt="Internet Explorer" /> Internet Explorer</label></p>
		<p><strong>Randomize Browser Links:</strong><br />
		<label><input type="radio" name="noie_random" value="YES" <?php echo get_option('noie_random')=='YES'?'checked="checked" ':''; ?>/> Yes</label><br />
		<label><input type="radio" name="noie_random" value="NO" <?php echo get_option('noie_random')=='NO'?'checked="checked" ':''; ?>/> No</label></p>
		<?php 
	}
	
	function widget( $args )
	{
		echo '<!--[if lte IE '.get_option( 'noie_version', 6 ).' ]>';
			print $args['before_widget']
				. $args['before_title'] . get_option( 'noie_title', 'Upgrade Your Browser!' ) . $args['after_title']
				. self::browser_alternatives()
				. $args['after_widget'];
		echo '<![endif]-->';
	}
	
	function browser_alternatives( $returnThis = '' )
	{
		$browser_links = 
			Array(
				'firefox'	=>	'http://www.mozilla.com/en-US/firefox/firefox.html',
				'chrome'	=>	'http://www.google.com/chrome/',
				'safari'	=>	'http://www.apple.com/safari/',
				'opera'		=>	'http://www.opera.com/',
				'ie'		=>	'http://www.microsoft.com/windows/internet-explorer/default.aspx'
			);
		$browser_titles = 
			Array(
				'firefox'	=>	'Mozilla Firefox',
				'chrome'	=>	'Google Chrome',
				'safari'	=>	'Apple Safari',
				'opera'		=>	'Opera',
				'ie'		=>	'Internet Explorer'
			);
		$browsers = explode( '|', get_option( 'noie_browsers', 'firefox|chrome|opera|safari|ie' ) );
		if( get_option( 'noie_random' ) == 'YES' )
		{
			shuffle( $browsers );
		}
		
		$returnThis .= '<ul class="browser-alternatives">';
		foreach( $browsers as $browser )
		{
			$returnThis .= "\n\t".'<li id="browser-alternative-'.$browser.'"><a href="'.$browser_links[$browser].'" rel="nofollow"><img src="'.WP_PLUGIN_URL.'/no-ie/img/'.$browser.'.png" height="16" width="16" alt="'.$browser_titles[$browser].'" /> Get '.$browser_titles[$browser].'</a></li>';
		}
		$returnThis.= '</ul>';
		
		return $returnThis;
	}
}

