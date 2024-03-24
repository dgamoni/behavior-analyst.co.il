<?php
/**
 * Created by PhpStorm.
 * User: oleksii.khodakivskyi
 * Date: 25.12.18
 * Time: 14:43
 */

require_once 'MembersDiscounts.php';

class Member {
    private $meta_key_number_name = 'account_number';
    private static $meta_key_data_name = 'membership_data';
    private $account_number;
    private $account_number_length = 4;
    private static $files_folder = 'members';
    private $free_membership_type = 'D';
    private $membership_types = array(
        'A' => 'חברים',
        'B' => 'סטודנטים',
        'C' => 'עמיתים',
        'D' => 'ידידים'
    );
    private $notification_type;
    private $additional_emails = array(
        'musmachim.aba@gmail.com'
    );

    public function __construct($params = array()) {

    }

    private function initHooks() {
        add_filter( 'bnfw_trigger_welcome-email_notification', [$this, 'filterByFormType'], 1, 3 );
        add_filter( 'bnfw_registration_email_message', [$this, 'customizeUsersEmail'], 1, 2 );
        add_filter( 'wp_new_user_notification_email_admin', [$this, 'customizeAdminEmail'], 10, 3 );
        add_filter( 'wp_mail_from', [$this, 'wpb_sender_email'] );
        add_filter( 'wp_mail_from_name', [$this, 'wpb_sender_name'] );
    }

    public function wpb_sender_email( $original_email_address ) {
        return 'no-reply@behavior-analyst.co.il';
    }

    public function eswc_var_dropdown_args( $filter_args, $email ) {
        return $email;
    }

    public function wpb_sender_name( $original_email_from ) {
        return 'מנתה | ארגון מנתחי ההתנהגות המוסמכים: ABA';
    }

    public function registerNewPayMember($params) {
        $user_id = false;

        if (!$this->checkTransaction($params)) {
            return $user_id;
        }

        if (!empty($params['email'])) {
            $username = !empty($params['username']) ? $params['username']: $params['email'];
            $membership_type = $this->getMembershipType($params['payfor']);
            $this->notification_type = $this->membership_types[$membership_type];
            $this->account_number = $this->generateAccountNumber($membership_type, $this->account_number_length);
            //error_log('ACCOUNT NUMBER');
            //error_log(print_r($this->account_number, true));
            $this->initHooks();

            $user_id = register_new_user($username, $params['email']);

            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, $this->meta_key_number_name, $this->account_number);

                $membership_data = array(
                    'account_number' => $this->account_number,
                    'membership_type' => $this->membership_types[$membership_type],
                    'payment_date' => time(),
                    'transaction_index' => $params['index']
                );

                $draft_data = self::getDraftMemberData($params['email']);

                //error_log(print_r($draft_data, true));

                if (!empty($draft_data)) {
                    $membership_data = array_merge($membership_data, $draft_data);
                    self::removeDraftMemberData($params['email']);
                }

                //error_log(print_r('DATA', true));
                //error_log(print_r(json_encode($membership_data), true));

                update_user_meta($user_id, self::$meta_key_data_name, json_encode($membership_data, JSON_UNESCAPED_UNICODE));
                update_user_meta($user_id, 'membership_payment_date', time());

                $this->setUserData($user_id, $membership_data);

                $this->sendMembershipData($membership_data, $params['email'] );

                $this->subscribeMember($params['email'], $params['firstname'], $params['lastname'], $params['phone'], $params['payfor']);
            
            } else {

                $user = get_user_by('email', $params['email'] );
                $user_id = $user->ID;
                 update_user_meta($user_id, $this->meta_key_number_name, $this->account_number);

                $membership_data = array(
                    'account_number' => $this->account_number,
                    'membership_type' => $this->membership_types[$membership_type],
                    'payment_date' => time(),
                    'transaction_index' => $params['index']
                );

                $draft_data = self::getDraftMemberData($params['email']);

                //error_log(print_r($draft_data, true));

                if (!empty($draft_data)) {
                    $membership_data = array_merge($membership_data, $draft_data);
                    self::removeDraftMemberData($params['email']);
                }

                //error_log(print_r('DATA', true));
                //error_log(print_r(json_encode($membership_data), true));

                update_user_meta($user_id, self::$meta_key_data_name, json_encode($membership_data, JSON_UNESCAPED_UNICODE));
                update_user_meta($user_id, 'membership_payment_date', time());

                update_user_meta($user_id, 'active_member', 'true');
                $this->sendMembershipData($membership_data, $params['email'], true );
                //$this->subscribeMember($params['email'], $params['firstname'], $params['lastname'], $params['phone'], $params['payfor']);
                              

            }


        }

        //error_log('USER-DATA');
        //error_log(print_r(get_user_by( 'id', $user_id ), true));

        //error_log('USER-META');
        //error_log(print_r(get_user_meta($user_id), true));

        return $user_id;
    }

    private function sendMembershipData($membership_data, $email, $update = false ) {
        $html = '';

        foreach ($membership_data as $key => $value) {
            // $value = utf8_encode($value);
            $value = $value;
            $html .= <<<HTML
                <p>
                    <span><b>{$key}</b></span> - <span>{$value}</span>
                </p>
                <br />
HTML;

        }

        $headers = array(
            'From: '.$email.'', 
            //'CC: ccc@example.com', 
            //'BCC: eee@example.pro', 
        );

        add_filter( 'wp_mail_from', function( $filter_args ) use ( $email ) {
                return $this->eswc_var_dropdown_args( $filter_args, $email );
            }
        );




        add_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

        if ( $update ) {
            wp_mail( $this->additional_emails, 'Update member', $html);
        } else {
            wp_mail( $this->additional_emails, 'New member', $html);
        }
        

        remove_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

        remove_filter( 'wp_mail_from', function( $filter_args ) use ( $email ) {
                return $this->eswc_var_dropdown_args( $filter_args, $email );
            }
        );

    }

    public function set_html_content_type() {
        return 'text/html';
    }

    public function registerNewFreeMember($params) {
        $this->initHooks();

        $user_id = false;

        if (!empty($params['user-email'])) {
            $username = !empty($params['username']) ? $params['username']: $params['user-email'];
            $this->notification_type = $this->membership_types[$this->free_membership_type];
            $this->account_number = $this->generateAccountNumber($this->free_membership_type, $this->account_number_length);
            //error_log('ACCOUNT NUMBER');
            //error_log(print_r($this->account_number, true));
            $user_id = register_new_user($username, $params['user-email']);

            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, $this->meta_key_number_name, $this->account_number);

                $membership_data = array(
                    'account_number' => $this->account_number,
                    'membership_type' => $this->membership_types[$this->free_membership_type],
                );

                $membership_data = array_merge($membership_data, $params);

                //error_log(print_r($membership_data, true));

                update_user_meta($user_id, self::$meta_key_data_name, json_encode($membership_data, JSON_UNESCAPED_UNICODE));

                $this->setUserData($user_id, $params);

                $this->sendMembershipData($membership_data);
            }
        }

        return $user_id;
    }

    private function generateAccountNumber($type, $length) {
        $char = "0123456789";
        $char = str_shuffle($char);
        for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
            $rand .= $char{mt_rand(0, $l)};
        }
        $number = $type . $rand;

        if ($this->checkAccountNumber($number)) {
            return $number;
        } else {
            $this->generateAccountNumber($type, $length);
        }
    }

    private function checkAccountNumber($number) {
        $valid = false;

        $members = get_users('meta_key=' . $this->meta_key_number_name . '&meta_value=' . $number);

        if (empty($members)) {
            $valid = true;
        }

        return $valid;
    }

    private function getMembershipType($name) {
        foreach ($this->membership_types as $key => $value) {
            if ($name == $value) {
                $type = $key;
            }
        }

        return $type;
    }

    private function setUserData($user_id, $params) {
        $data = array(
            'ID' => $user_id,
            'first_name' => !empty($params['first-name']) ? $params['first-name'] : '',
            'last_name' => !empty($params['last-name']) ? $params['last-name'] : ''
        );

        wp_update_user($data);

        $phone_number = !empty($params['phone']) ? $params['phone'] : '';
        update_user_meta($user_id, 'phone_number', $phone_number);
        update_user_meta($user_id, 'active_member', 'true');
    }

    public function filterByFormType($triggering, $settings, $user) {
        if (strpos($settings['subject'], "[{$this->notification_type}]") === false) {
            $triggering = false;
        } else {
            $triggering = true;
        }

        return $triggering;
    }

    public function customizeUsersEmail($wp_new_user_notification_email, $setting) {
        //error_log(print_r($this->account_number, true));
        $wp_new_user_notification_email = str_replace('[account_number]', $this->account_number, $wp_new_user_notification_email);

        return $wp_new_user_notification_email;
    }

    public function customizeAdminEmail($wp_new_user_notification_email, $user, $blogname) {
        //$wp_new_user_notification_email['headers'] = "From: myBlog <".$user->email.">";
        //$wp_new_user_notification_email['to'] = 'monothemes@gmail.com';
        $wp_new_user_notification_email['subject'] = sprintf( '[%s] New member %s registered.', $blogname, $user->user_login );
        $wp_new_user_notification_email['message'] = <<<HTML
            Account Number - {$this->account_number}
HTML;

        return $wp_new_user_notification_email;
    }

    private function checkTransaction($params) {
        $valid = true;

        if (!empty($params['index'])) {
            $members = get_users('meta_key=' . self::$meta_key_data_name);

            //error_log(print_r($members, true));

            if (!empty($members)) {
                foreach ($members as $member) {
                    $membership_data = $this->getMembershipData($member->ID);
                    //error_log(print_r($membership_data, true));

                    if ($params['index'] === $membership_data['transaction_index']) {
                        $valid = false;
                        break;
                    }
                }
            }
        }

        //error_log(print_r($valid, true));

        return $valid;
    }

    public function getMembers($filter) {
        $filter = !empty($filter) ? $filter . '&meta_key=' . $this->meta_key_number_name : 'meta_key=' . $this->meta_key_number_name;
        //$filter = !empty($filter) ? $filter . '&meta_key=payment_date' : 'meta_key=payment_date';
        //var_dump($filter);

        return $members = get_users($filter);
    }

    public static function getMembershipData($user_id) {
        $json = str_replace('\\\'', '\\\\\'', get_user_meta($user_id, self::$meta_key_data_name, true));
        $membership_data = json_decode($json, true);

        return $membership_data;
    }

    public static function saveDraftMemberData($data) {
        if (!empty($data['files'])) {
            foreach ($data['files'] as $key => $file_data) {
                $file_data['form_type'] = $data['form_type'];

                if (is_array($file_data['name'])) {
                    foreach ($file_data['name'] as $index => $name) {
                        $temp = array(
                            'name' => $name,
                            'form_type' => $file_data['form_type'],
                            'tmp_name' => $file_data['tmp_name'][$index]
                        );
                        $data['file_path'][] = self::saveFile($key, $data['user-email'], $temp);
                    }
                } else {
                    $data['file_path'] = self::saveFile($key, $data['user-email'], $file_data);
                }
            }
        }

        unset($data['files']);
        //error_log(print_r('SAVE_DRAFT', true));
        //error_log(print_r($data, true));

        if (empty($data['first-name'])) {
            $data['first-name'] = !empty($data['firstname']) ? $data['firstname'] : '';
        }

        if (empty($data['last-name'])) {
            $data['last-name'] = !empty($data['lastname']) ? $data['lastname'] : '';
        }

        error_log(print_r('SAVE_DRAFT', true));
        error_log(print_r($data, true));
        error_log(print_r(json_encode($data), true));
        error_log(print_r(json_last_error(), true));

        update_option('draft-member-' . $data['email'], json_encode($data));
    }

    public static function removeDraftMemberData($email) {
        /*$data = self::getDraftMemberData($email);

        if ($data['file_path']) {
            unlink($data['file_path']);
        }*/

        delete_option('draft-member-' . $email);
    }

    public static function getDraftMemberData($email) {
        $json = get_option('draft-member-' . $email);
        $json_decoded = json_decode($json, true);

        if (4 === json_last_error()) {
            $json = str_replace('\\\'', '\\\\\'', $json);
            $json_decoded = json_decode($json, true);
        }

        if (json_last_error() > 0) {
            return false;
        }

        return $json_decoded;
    }

    private static function saveFile($file_type, $email, $file_data) {
        $info = pathinfo($file_data['name']);
        $ext = $info['extension']; // get the extension of the file
        $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_data['name']);
        $newname = $withoutExt . '.' . $ext;

        $target = self::checkFolder($file_data['form_type'], $file_type, $email) . '/' . $newname;
        move_uploaded_file($file_data['tmp_name'], $target);

        return $target;
    }

    private static function checkFolder($form_type, $file_type, $email) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/' . self::$files_folder;

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        $path = $path . '/' . $form_type;

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        $path = $path . '/' . $file_type;

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        $path = $path . '/' . $email;

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        return $path;
    }

    public static function getMembershipPrice($coupon_name, $price) {
        $new_price = false;

        $coupons = MembersDiscounts::getCoupons();
        //error_log(print_r($coupons, true));

        if (!empty($coupons)) {
            foreach ($coupons as $key => $coupon) {
                if ($coupon_name === $coupon['name']) {
                    $new_price = $coupon['value'];
                }
            }
        }

        //error_log(print_r($new_price, true));

        return $new_price;
    }

    public static function getMembershipPrice_plus($coupon_name, $price) {
        $new_price = false;

        $coupons = MembersDiscounts::getCoupons();
        //error_log(print_r($coupons, true));

        if (!empty($coupons)) {
            foreach ($coupons as $key => $coupon) {
                if ($coupon_name === $coupon['name'] && $coupon['coupon_status'] == 'on') {
                    $new_price = $coupon['value'];

                    $opt = json_decode(get_option( 'membership_coupons' ));
                    $stat = false;
                    foreach ($opt as $key => $value) {
                        if ($value->name == $coupon_name && $value->coupon_multiple == 'off' ) {
                            $opt[$key]->coupon_status = 'off';
                            $stat = true;
                        }
                    }
                    if ($stat) {
                        update_option('membership_coupons', json_encode($opt) );
                    }
                    

                }
            }
        }

        //error_log(print_r($new_price, true)); 

        // var_dump(json_encode($opt) );
        // die();

        return $new_price;
    }    


    public function deleteMember($user_id) {
        delete_user_meta($user_id, $this->meta_key_number_name);
        delete_user_meta($user_id, self::$meta_key_data_name);
        wp_delete_user($user_id);
    }

    public function subscribeMember($user_mail, $user_fname, $user_lname, $user_phone, $user_payfor) {
        switch ($membership_type) {
            case 'A':
                $tag = array('חברים');
                break;
            case 'B':
                $tag = array('סטודנטים');
                break;
            case 'C':
                $tag = array('עמיתים');
                break;
            case 'D':
                $tag = array('ידידים');
                break;
            default:
                $tag = array('');
                break;
        }
		
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
			'tags'  => array($user_payfor)
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
		
	/*
        $site_id = '24533';
        $api_pass = 'KyVc7XEHTkSfW4ncDt5CJBdWKJ4erKEZ';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://gconvertrest.sendmsg.co.il/api/sendMsg/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
          \"siteID\": " . $site_id . ",
          \"password\": \"" . $api_pass . "\"
        }");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));

        $apitoken_response = json_decode(curl_exec($ch));
        $api_token = $apitoken_response->Token;

        curl_setopt($ch, CURLOPT_URL, "https://gconvertrest.sendmsg.co.il/api/sendMsg/AddUsersToLists");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
              \"users\": [
                {
                  \"Cellphone\": \"" . $user_phone . "\",
                  \"EmailAddress\": \"" . $user_mail . "\",
                }
              ],
              \"mailingLists\": [
                {
                  \"ExistingListID\": " . $list_id . "
                }
              ]
        }");

//        curl_setopt($ch, CURLOPT_URL, "https://gconvertrest.sendmsg.co.il/api/sendMsg/GetMailingListNames");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($ch, CURLOPT_HEADER, FALSE);
//
//        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=utf-8",
            "Authorization: \"" . $api_token . "\""
        ));


        $response = curl_exec($ch);
        print_r(curl_error($ch), true);
        curl_close($ch);
        error_log(print_r($response, true));
		*/
    }
}