<?php
function register_acf_options_pages() {

    // Check function exists.
    if( !function_exists('acf_add_options_page') )
        return;

    // register options page.
    $option_page = acf_add_options_page(array(
        'page_title'    => __('Theme Settings'),
        'menu_title'    => __('Theme Settings'),
        'menu_slug'     => 'theme-custom-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

// Hook into acf initialization.
add_action('acf/init', 'register_acf_options_pages'); 


add_filter('acf/load_field/name=members_to_deactivate', 'pata_acf_load_field_members_to_deactivate');
function pata_acf_load_field_members_to_deactivate( $field ) {


	 $members = get_users('meta_key=membership_data');
     //var_dump($members );

    foreach ($members as $key => $line) {
    	    $active_member = get_user_meta( $line->data->ID, 'active_member', true ); 
            if( $active_member == 'true' ) {
    			$field['choices'][$line->data->ID] = $line->data->user_email;
    		}
       
    }

    return $field;
}


function clear_advert_main_transient() {
	$screen = get_current_screen();
    //var_dump($screen);

	if (strpos($screen->id, "theme-custom-settings") == true) {

        if( empty($_POST['acf']) ) {
            return;
        }

		//var_dump($_POST["acf"]);

        //deactivate members
		$deact_user = $_POST["acf"]["field_5de2720145561"];
		if($deact_user):
			foreach ($deact_user as $key => $user_id) {
				//var_dump( $user_id);
				update_user_meta($user_id, 'active_member', 'false');
			}
		endif;


        //add_new_member
        $add_new_members = $_POST["acf"]["field_5def1001f2aad"];


        if ( $add_new_members ) :

            foreach ($add_new_members as $key => $add_new_member) :

                // var_dump( $add_new_member );

                $pass = wp_generate_password();
                $email = $add_new_member["field_5def114d21343"];
                $first_name = $add_new_member["field_5def126fa9d1b"];
                $last_name = $add_new_member["field_5def1282a9d1c"];
                $membership_level = $add_new_member["field_5def12da8c022"];
                $membership_date = strtotime( $add_new_member["field_5def1362765a6"] );

                // var_dump('$email= '.$email);
                // var_dump('$first_name= '.$first_name);
                // var_dump('$last_name= '.$last_name);
                // var_dump('$membership_level= '.$membership_level);
                //var_dump('$membership_date= '.$membership_date);
                // var_dump('pass= '.$pass);

                $userdata = array(
                    'user_pass'       => $pass, // обязательно
                    'user_login'      => $email, // обязательно
                    'user_email'      => $email,
                    'first_name'      => $first_name,
                    'last_name'       => $last_name,
                    'role'            => 'subscriber', // (строка) роль пользователя
                );

                $new_mem_id = wp_insert_user( $userdata );
                //var_dump('$new_mem_id = '.$new_mem_id);

                $account_number = $membership_level.'100'.$new_mem_id;

                if ($membership_level == 'A') {
                    $membership_type = "חברים";
                } else if ($membership_level == 'B') {
                    $membership_type = "סטודנטים";
                } if ($membership_level == 'C') {
                    $membership_type = "עמיתים"; 
                }
                
                
                $membership_data = array(
                    "account_number"    => $account_number,
                    "membership_type"   => $membership_type,
                    "payment_date"      => $membership_date,
                    "transaction_index" => $account_number,
                    "first-name"        => $first_name,  
                    "last-name"         => $last_name,  
                );
                //var_dump(json_encode($membership_data, JSON_UNESCAPED_UNICODE));

                update_user_meta($new_mem_id, 'membership_data', json_encode($membership_data, JSON_UNESCAPED_UNICODE));
                update_user_meta($new_mem_id, 'account_number', $account_number);
                update_user_meta($new_mem_id, 'active_member', 'true');
                update_user_meta($new_mem_id, 'membership_payment_date', $membership_date);

            endforeach;
                
        endif;

		//die();

        //clear
		$_POST["acf"]["field_5de2720145561"] = '';
        $_POST["acf"]["field_5def1001f2aad"] = '';

	}
}
add_action('acf/save_post', 'clear_advert_main_transient', 5);


/**
 * Convert values of ACF core date time pickers from Y-m-d H:i:s to timestamp
 * @param  string $value   unmodified value
 * @param  int    $post_id post ID
 * @param  object $field   field object
 * @return string          modified value
 */
function acf_save_as_timestamp( $value, $post_id, $field  ) {
    if( $value ) {
        $value = strtotime( $value );
    }

    return $value;    
}

//add_filter( 'acf/update_value/type=membership_date', 'acf_save_as_timestamp', 10, 3 );

/**
 * Convert values of ACF core date time pickers from timestamp to Y-m-d H:i:s
 * @param  string $value   unmodified value
 * @param  int    $post_id post ID
 * @param  object $field   field object
 * @return string          modified value
 */
function acf_load_as_timestamp( $value, $post_id, $field  ) {
    if( $value ) {
        $value = date( 'Y-m-d H:i:s', $value );
    }

    return $value;    
}

//add_filter( 'acf/load_value/type=membership_date', 'acf_load_as_timestamp', 10, 3 );