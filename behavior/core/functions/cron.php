<?php


add_action( 'admin_head', 'my_activation' );
function my_activation() {


	if ( ! wp_next_scheduled( 'my_send_member_message' ) ) {
		wp_schedule_single_event( strtotime('2020-12-24'), 'my_send_member_message' );
	}
	if ( ! wp_next_scheduled( 'my_send_member_message_1' ) ) {
		wp_schedule_single_event( strtotime('2021-12-24'), 'my_send_member_message_1' );
	}
	if ( ! wp_next_scheduled( 'my_send_member_message_2' ) ) {
		wp_schedule_single_event( strtotime('2022-12-24'), 'my_send_member_message_2' );
	}
	if ( ! wp_next_scheduled( 'my_send_member_message_3' ) ) {
		wp_schedule_single_event( strtotime('2023-12-24'), 'my_send_member_message_3' );
	}
	if ( ! wp_next_scheduled( 'my_send_member_message_4' ) ) {
		wp_schedule_single_event( strtotime('2024-12-24'), 'my_send_member_message_4' );
	}
	if ( ! wp_next_scheduled( 'my_send_member_message_5' ) ) {
		wp_schedule_single_event( strtotime('2025-12-24'), 'my_send_member_message_5' );
	}

	if ( ! wp_next_scheduled( 'my_deactivated_members' ) ) {
		wp_schedule_single_event( strtotime('2020-12-31'), 'my_deactivated_members' );
	}	
	if ( ! wp_next_scheduled( 'my_deactivated_members_1' ) ) {
		wp_schedule_single_event( strtotime('2021-12-31'), 'my_deactivated_members_1' );
	}
	if ( ! wp_next_scheduled( 'my_deactivated_members_2' ) ) {
		wp_schedule_single_event( strtotime('2022-12-31'), 'my_deactivated_members_2' );
	}
	if ( ! wp_next_scheduled( 'my_deactivated_members_3' ) ) {
		wp_schedule_single_event( strtotime('2023-12-31'), 'my_deactivated_members_3' );
	}
	if ( ! wp_next_scheduled( 'my_deactivated_members_4' ) ) {
		wp_schedule_single_event( strtotime('2024-12-31'), 'my_deactivated_members_4' );
	}
	if ( ! wp_next_scheduled( 'my_deactivated_members_5' ) ) {
		wp_schedule_single_event( strtotime('2025-12-31'), 'my_deactivated_members_5' );
	}
}


add_action( 'my_send_member_message', 'do_send_member_message' );
add_action( 'my_send_member_message_1', 'do_send_member_message' );
add_action( 'my_send_member_message_2', 'do_send_member_message' );
add_action( 'my_send_member_message_3', 'do_send_member_message' );
add_action( 'my_send_member_message_4', 'do_send_member_message' );
add_action( 'my_send_member_message_5', 'do_send_member_message' );
function do_send_member_message(){

	$members = get_users('meta_key=membership_data');
	$message = '';
	$admin_emails = array('dgamoni@gmail.com');
	$members_emails = array();
	// $admin_emails = array(
 //        'musmachim.aba@gmail.com',
 //        'dgamoni@gmail.com'
 //    );

		$cron_emails = get_field('cron_emails', 'option');
		$single_emails = get_field('single_emails', 'option');
		$text_email = get_field('text_email', 'option');
		$email_subject = get_field('email_subject', 'option');
		//email_subject_of_deactivation
		//text_email_of_deactivation

		// $message .= $cron_emails . "\r\n";
		// $message .= $single_emails . "\r\n";
		// $message .= $text_email . "\r\n";

		$message .= $text_email . "\r\n";



		foreach ($members as $key => $member) {
			
			array_push($members_emails, $member->data->user_email);
			//$message .= '<strong>member_id:</strong> ' . $member->data->ID . "\r\n";
			//$message .= '<strong>member_email:</strong> ' . $member->data->user_email. "\r\n";

		}

		if ( $cron_emails == 'to single user') {
			$admin_emails = $single_emails;
		} else {
			$admin_emails = $members_emails;
			//$admin_emails = 'monothemes@gmail.com';
		}

	    

	    $headers = array(
            'From: מנתה | ארגון מנתחי ההתנהגות המוסמכים: ABA <no-reply@behavior-analyst.co.il>', 
            'content-type: text/html',
        );

	    //send email to members
	    if ( $email_subject) {
			$subject = $email_subject;
	    } else {
	    	$subject = 'you member account will be deactivated';
	    }
	    


	    //wp_mail( $members_emails, $subject, $message, $headers );
        wp_mail( $admin_emails, $subject, $message, $headers );



}

add_action( 'my_deactivated_members', 'do_my_deactivated_members' );
add_action( 'my_deactivated_members_1', 'do_my_deactivated_members' );
add_action( 'my_deactivated_members_2', 'do_my_deactivated_members' );
add_action( 'my_deactivated_members_3', 'do_my_deactivated_members' );
add_action( 'my_deactivated_members_4', 'do_my_deactivated_members' );
add_action( 'my_deactivated_members_5', 'do_my_deactivated_members' );
function do_my_deactivated_members(){

	$members = get_users('meta_key=membership_data');
	$message_to_admin = '';
	$message = '';
	//$admin_emails = array('dgamoni@gmail.com');
	$admin_emails = array(
        'musmachim.aba@gmail.com',
        'dgamoni@gmail.com'
    );

    		
    		$email_subject_of_deactivation = get_field('email_subject_of_deactivation', 'option');
    		$text_email_of_deactivation = get_field('text_email_of_deactivation', 'option');
    		$debug_mode = get_field('debug_mode', 'option');

		//email_subject_of_deactivation
		//text_email_of_deactivation

		// $message_to_admin .= '<p>Hi Admin</p>'. "\r\n";
		// $message_to_admin .= '<p>today All Members deactivated</p>'. "\r\n";
    		$message_to_admin .= $text_email_of_deactivation;

		foreach ($members as $key => $member) {
			
			
			//$message_to_admin .= '<strong>member_id:</strong> ' . $member->data->ID . "\r\n";
			//$message_to_admin .= '<strong>member_email:</strong> ' . $member->data->user_email. "\r\n";

			if ( $debug_mode ) {
				//update_user_meta($member->data->ID, 'active_member', 'true');
			} else {
				update_user_meta($member->data->ID, 'active_member', 'false');
			}
			

			

		}

	    

	    $headers = array(
            'From: מנתה | ארגון מנתחי ההתנהגות המוסמכים: ABA <no-reply@behavior-analyst.co.il>', 
            'content-type: text/html',
        );


        // send email to admins
        if ( $email_subject_of_deactivation ) {
			$subject_ = $email_subject_of_deactivation;
        } else {
        	$subject_ = 'members account deactivated';
        }
	    
	    wp_mail( $admin_emails, $subject_, $message_to_admin, $headers );

}