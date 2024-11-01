<?php
	/*
	Plugin Name: Skip Dashboard
	Plugin URI: http://www.hemthapa.com
	Description: This lightweight plugin allows user to skip default wordpress dashboard after login and jump straight to user defined page.
	Version: 1.0
	Author: Hem Thapa
	Author URI: http://www.hemthapa.com
	License: GPL2
	*/
   
   	// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        die;
    }
	
	//add admin menu for plugin setting
	add_action('admin_menu', 'setSkipDashboardAdminMenu'); 
	
	// check if user set the redirect url on setting page
	if(get_option('skip_dashboard_redirect_url') != false && !empty(esc_attr(get_option('skip_dashboard_redirect_url')))){
		
		add_filter( 'login_redirect', 'setSkipDashboardLoginRedirect', 10, 3 );   //redirect user to user set page
	
	}
   
	/*
	* 	Add admin menu for user URL setting page
	*/
	function setSkipDashboardAdminMenu()
	{
		add_options_page("Skip Dashboard", "Skip Dashboard", "administrator", 'skip-dashboard', 'skipDashboardOptionsetting');
		add_action( 'admin_init', 'register_skip_dashboard_setting_options' );
	}
   
   		
	
	/*
	*	Register wordpress option for user setting
	*/
	function register_skip_dashboard_setting_options() {
		
		register_setting( 'skip-dashboard-settings-op-group', 'skip_dashboard_redirect_url' );
		register_setting( 'skip-dashboard-settings-op-group', 'skip_dashboard_hide_notification' );
		
	}

   

	/*
	*	Load plugin setting page
	*/
	function skipDashboardOptionsetting(){
	?>
		<div class="wrap">
		<h1>Skip Dashboard</h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'skip-dashboard-settings-op-group' ); ?>
			<?php do_settings_sections( 'skip-dashboard-settings-op-group' ); ?>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Redirect URL</th>
				<td>
					<input type="text" name="skip_dashboard_redirect_url" value="<?php echo esc_attr( get_option('skip_dashboard_redirect_url') ); ?>" placeholder="/wp-admin/post-new.php" size="50" />
					<br><br>Only user with <strong>Administrator, Editor and Author</strong> privilege will be redirected to above URL.<br>
					<br><u>URL format</u><br>
					<pre>/wp-admin/post-new.php</br>OR,<br>http://example.com/wp-admin/post-new.php</br></pre>
				</td>
				</tr>
			</table>
			
			<?php submit_button(); ?>
			<br><hr>
			Please contact <a href="http://hemthapa.com" target="_blank">developer</a> to report any bug or issues with this plugin.
		</form>
		</div>
	<?php }
	
	

	/*
	*	Redirect user
	*/
	function setSkipDashboardLoginRedirect( $redirect_to, $request, $user ) {
		
		//check for user roles
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check if user is admin
			if ( in_array( 'administrator', $user->roles) || in_array( 'editor', $user->roles) || in_array( 'author', $user->roles) ) {

					wp_redirect(esc_attr( get_option('skip_dashboard_redirect_url')));
			}
		} else {
			return $redirect_to;
		}
		
	}
