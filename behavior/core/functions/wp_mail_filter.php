<?php

//add_filter('wp_mail', 'ws_add_site_header', 99);
function ws_add_site_header($args) {
    $args['headers'] .= !empty($args['headers']) ? "\r\n" : '';
    $args['headers'] .= 'X-WU-Site: ' . parse_url(get_site_url(), PHP_URL_HOST);
    $args['headers'] .= 'MIME-Version: 1.0' . "\r\n";
	$args['headers'] .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    return $args;
} 


function so174837_registration_email_alert( $user_id ) {
    $user    = get_userdata( $user_id );
    $email   = $user->user_email;
    $message = $email . ' has registered to your website.';
    wp_mail( 'youremail@example.com', 'New User registration', $message );
}
//add_action('user_register', 'so174837_registration_email_alert');