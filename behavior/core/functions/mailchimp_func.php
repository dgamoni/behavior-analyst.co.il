<?php

function mailchimp_get_all_subcriber() {
	$api_key = 'd527d330f19d272d6e27934c6c4f0091-us19';
	$list_id = 'bf5accb719';
	$dc = substr($api_key,strpos($api_key,'-')+1); // us5, us8 etc
	$args = array(
	    'headers' => array(
	        'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )
	    )
	);
	 
	// connect
	$response = wp_remote_get( 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.$list_id, $args );
	 
	// decode the response
	$body = json_decode( $response['body'] );
	 
	if ( $response['response']['code'] == 200 ) :
	 
	    // subscribers count
	    $member_count = $body->stats->member_count;
	    $emails = array();
	 
	    for( $offset = 0; $offset < $member_count; $offset += 50 ) :
	 
	        $response = wp_remote_get( 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members?offset=' . $offset . '&count=50', $args );
	        // decode the result
	        $body = json_decode( $response['body'] );
	 
	        if ( $response['response']['code'] == 200 ) {
	            foreach ( $body->members as $key=> $member ) {
	                $emails[] = $member->email_address;
	                // $emails[$key]['email'] = $member->email_address;
	                // $emails[$key]['tags'] = $member->tags;
	                $tag_arra[$member->email_address] = $member->tags;
	                $fname[$member->email_address] = $member->merge_fields->FNAME;
	                $last[$member->email_address] = $member->merge_fields->LNAME;
	                $phone[$member->email_address] = $member->merge_fields->PHONE;

	                //echo "<pre style='display:none;'>", var_dump($member), "</pre>";
	            }
	        }
	 
	    endfor;
	 
	endif;
	 
	// print all emails
	// print_r( $emails );
	//echo "<pre style='display:none;'>", var_dump($emails), "</pre>";
	// return $emails;

	echo "<pre style='display:none;'>";

	$members = get_users('meta_key=account_number');
    foreach ($members as $key => $line) {
            $json = str_replace('\\\'', '\\\\\'', get_user_meta($line->data->ID, 'membership_data', true));
            $membership_data = json_decode($json, true);
            //echo "<pre style='display:none'>", var_dump($membership_data), "</pre>";
            $user_mail = strtolower($membership_data["user-email"]);
            $user_fname = $membership_data["first-name"]; 
            $user_lname = $membership_data["last-name"]; 
            $user_phone = $membership_data["phone-number"]; 
            $tag = $membership_data["membership_type"];
            $account_number = get_user_meta($line->data->ID, 'account_number', true);

            //if ( !in_array($user_mail, $emails) && $user_mail == 'tyg@fhg.com') {
            if ( in_array( $user_mail, $emails) ) {
                  // var_dump( $user_mail );
                  // var_dump( $account_number[0] );
                  // var_dump( $tag_arra[$user_mail] );

    //         	var_dump('---site----');
    //         	var_dump($user_fname);
    //         	var_dump($user_phone);
				
				// var_dump('----mailchimp----');
    //         	var_dump( $fname[$user_mail] );
    //         	var_dump( $phone[$user_mail] );

            	 //&& $user_mail == '123ilanit@gmail.com'

            	if ( $phone[$user_mail] == '' && $user_phone != '' ) {
	            	
	    //         	var_dump('---site----');
	    //         	var_dump( $user_mail );
	    //         	var_dump($user_phone);
					// var_dump('----mailchimp----');
	    //         	var_dump( $phone[$user_mail] );

					// var_dump('--------');
     //        		var_dump( $user_mail );
     //        		var_dump( $user_phone );
     //        		var_dump('updated');

	            	//$result_ = mailchimp_update_subcriber_data($user_mail, $user_phone, 'phone');
	            	//var_dump($result_);

            	}


                  if ( $account_number[0] == 'C'  ) {

	                  //var_dump( $user_mail );
	                  // var_dump( $account_number[0] );
	                  // var_dump($tag);
	                  // var_dump( $tag_arra[$user_mail] );

	                  //if ( $user_mail == 'a7la_njma@hotmail.com') {
		                  // $result_ = mailchimp_update_subcriber_tags($user_mail, 'עמיתים 2020');
		                  // var_dump($result_);
	                  //}


                  } else if ( $account_number[0] == 'A'  ) {

	                  //var_dump( $user_mail );
	                  // var_dump( $account_number[0] );
	                  // var_dump($tag);
	                  // var_dump( $tag_arra[$user_mail] );
	                 //  	$result_ = mailchimp_update_subcriber_tags($user_mail, 'חברים 2020');
		                // var_dump($result_);
                  }


                 //A // חברים //חברים 2020
                 //C // עמיתים // 2020 עמיתים

                //  $result_ = mailchimp_update_subcriber($user_mail, $user_fname, $user_lname, $user_phone, $tag);
                // var_dump($result_);
            }
      
    }
    echo "</pre>";


}


function mailchimp_update_subcriber_data($user_mail, $data, $field = 'fname') {
		
		$listId = 'bf5accb719';
		$apiKey = 'd527d330f19d272d6e27934c6c4f0091-us19';
		$memberId = md5(strtolower($user_mail));
		$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);

		if ($field == 'fname') {
			$json = json_encode([
				//'email_address' => $user_mail,
			    'merge_fields' => array(
			        'FNAME' => $data
			    )
			]);
		} else if ($field == 'last') {
			$json = json_encode([
				//'email_address' => $user_mail,
			    'merge_fields' => array(
			        'LNAME' => $data
			    )
			]);
		} else if ($field == 'phone') {
			$json = json_encode([
				//'email_address' => $user_mail,
			    'merge_fields' => array(
			        'PHONE' => $data
			    )
			]);
		}
		


		$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		//print_r($result);
		return $result;	

}

function mailchimp_update_subcriber_tags($user_mail, $tag) {
		
		$listId = 'bf5accb719';
		$apiKey = 'd527d330f19d272d6e27934c6c4f0091-us19';
		$memberId = md5(strtolower($user_mail));
		$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);

		// $data = array(
		//     'email_address' => $user_mail,
		//     'tags' => array(
		//     	array(
		// 	        'name' => $tag,
		// 	        'status' => 'active'
		// 	        )
		//     	)
		//     );

		// $json_data = json_encode($data);

		$json = json_encode([
			'email_address' => $user_mail,
			'tags'  => array(
							array(
					            'name' => $tag,
					            'status' => 'active'
					        )
						)
		]);


		//$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/segments/' . $segment_id .'/members';
		  $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId . '/tags';


		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		//print_r($result);
		return $result;	

}


// dikla201278@gmail.com - added
//hadarle@gmail.com - added
// irit.shay@012.net.il - added

// mansour.mai2@gmail.cim //looks fake or invalid, please enter a real email address

// mggamil@gmail.com // was permanently deleted and cannot be re-imported. The contact must re-subscribe to get back on the list.

// nisreen.zoubi.86@gmail.com - added
// renanamargi@gmail.com - added
// sfd@fhf.com - added
// tyg@fhg.com - was permanently deleted and cannot be re-imported. The contact must re-subscribe to get back on the list.

function mailchimp_update_subcriber($user_mail, $user_fname, $user_lname, $user_phone, $tag) {
        // switch ($membership_type) {
        //     case 'A':
        //         $tag = array('חברים');
        //         break;
        //     case 'B':
        //         $tag = array('סטודנטים');
        //         break;
        //     case 'C':
        //         $tag = array('עמיתים');
        //         break;
        //     case 'D':
        //         $tag = array('ידידים');
        //         break;
        //     default:
        //         $tag = array('');
        //         break;
        // }
		
		$listId = 'bf5accb719';
		
		$data = [
			'email'     => $user_mail,
			'status'    => 'subscribed',
			'firstname' => $user_fname,
			'lastname'  => $user_lname,
			'phone'  => $user_phone
		];
		
		$apiKey = 'd527d330f19d272d6e27934c6c4f0091-us19';
		$memberId = md5(strtolower($data['email']));
		$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
		$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members';
		$json = json_encode([
			'email_address' => $data['email'],
			'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
			'merge_fields'  => [
				'FNAME'     => $data['firstname'],
				'LNAME'     => $data['lastname'],
				'PHONE'     => $data['phone']
			],
			'tags'  => array($tag)
		]);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		//print_r($result);
		return $result;

}