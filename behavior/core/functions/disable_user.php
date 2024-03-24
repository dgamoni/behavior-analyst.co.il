<?php

add_action( 'wp_login', 'custom_disable_user_login', 10, 2 );
function custom_disable_user_login( $user_login, $user = null ) {

	if ( !$user ) {
		$user = get_user_by('login', $user_login);
	}
	if ( !$user ) {
		// not logged in - definitely not disabled
		return;
	}
	//var_dump($user->roles);
	// Get user meta
	$disabled = get_user_meta( $user->ID, 'active_member', true );
	
	// Is the use logging in disabled?
	if ( user_can( $user->ID, 'subscriber' ) ) {
		if ( $disabled != 'true' ) {
			// Clear cookies, a.k.a log user out
			wp_clear_auth_cookie();

			// Build login URL and then redirect
			$login_url = site_url( 'wp-login.php', 'login' );
			$login_url = add_query_arg( 'disabled', '1', $login_url );
			wp_redirect( $login_url );
			exit;
		}
	}
}

add_filter( 'login_message', 'custom_user_login_message' );
function custom_user_login_message( $message ) {

	// Show the error message if it seems to be a disabled user
	if ( isset( $_GET['disabled'] ) && $_GET['disabled'] == 1 ) 
		$message =  '<div id="login_error">' . apply_filters( 'ja_disable_users_notice', __( 'החשבון אינו בתוקף', 'ja_disable_users' ) ) . '</div>';

	return $message;
}