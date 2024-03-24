<?php

function reactivate_member_message_custom($member_email){


	$text_email = get_field('text_email_of_reactivate', 'option');
	$message .= $text_email . "\r\n";

    $headers = array(
        'From: מנתה | ארגון מנתחי ההתנהגות המוסמכים: ABA <no-reply@behavior-analyst.co.il>', 
        'content-type: text/html',
    );

	$subject = 'תודה על הרשמתך';
	$admin_email = 'no-reply@behavior-analyst.co.il';

    add_filter( 'wp_mail_from', function( $filter_args ) use ( $admin_email ) {
            return eswc_var_dropdown_args_( $filter_args, $admin_email );
        }
    );

    wp_mail( $member_email, $subject, $message, $headers );

    remove_filter( 'wp_mail_from', function( $filter_args ) use ( $admin_email ) {
            return eswc_var_dropdown_args_( $filter_args, $admin_email );
        }
    );

} 


function eswc_var_dropdown_args_( $filter_args, $email ) {
        return $email;
    }