<?php
/**
 * Created by PhpStorm.
 * User: oleksii
 * Date: 04.02.19
 * Time: 12:59
 */

class MembersFix {
    private $member;

    public function __construct($member) {
        $this->member = $member;

        $this->initAjaxActions();
    }

    public function initAjaxActions() {
        //add_action('wp_ajax_save-draft-data', [$this, 'save_draft_data']);
        //add_action('wp_ajax_nopriv_save-draft-data', [$this, 'save_draft_data']);

        //add_action('wp_ajax_save-data', [$this, 'restoreMemberData']);
        //add_action('wp_ajax_nopriv_save-data', [$this, 'restoreMemberData']);
    }

    public function restoreMemberData() {
        $user = get_user_by('email', 'sarayp510@gmail.com	');
        $membership_data = $this->member->getMembershipData($user->ID);

        $draft_data = $this->member->getDraftMemberData('sarayp510@gmail.com	');

        //error_log(print_r($draft_data, true));

        if (!empty($draft_data)) {
            $membership_data = array_merge($membership_data, $draft_data);
            $this->member->removeDraftMemberData($membership_data['email']);
        }

        //error_log(print_r('DATA', true));
        //error_log(print_r(json_encode($membership_data), true));

        update_user_meta($user->ID, 'membership_data', json_encode($membership_data, JSON_UNESCAPED_UNICODE));

        die();
    }

    public function save_draft_data() {
        $data = array(
            'form_type' => 'friends',
            'last-name' => "עמית",
            'first-name' => "שרי",
            'birth-date' => "1989-01-06",
            'address' => "אורן 27 חריש",
            'cell-phone' => "0526886118",
            'phone-number' => '',
            'user-email' => "sarayp510@gmail.com",
            'id-number' => 303105571,
            'accreditation' => "אונ\' תל אביב",
            'certification-type' => "BCaBA",
            'certification-number' => "0-18-9191",
            'ba-in-the-field' => "מדעי ההתנהגות",
            'ba-academic-institution' => "אוניברסיטת אריאל",
            'masters-degree-in-the-field' => '',
            'masters-academic-institution' => '',
            'additional-degree' => '',
            'additional-academic-institution' => '',
            'speciality' => "התפתחות תקינה",
            'coupon' => '',
            'email' => "sarayp510@gmail.com"
        );

        $this->member->saveDraftMemberData($data);

        die();
    }

    public function display () {
        echo <<<HTML
            <div class="wrap">
                <h2>Fix Members (don't use, only for developers)</h2>
                <form id="member-fix-form" method="post" action="/wp-admin/admin.php?page=fix_members">
                    <input type="email" name="member_email" value="" placeholder="Enter member's email here...">
                    <input type="submit" value="Get data">
                </form>
            <button class="save-draft">Save draft</button>
            <script>
                (function($) {
                  $('.save-draft').on('click', function(e) {
                    e.preventDefault();
                    
                    /*var data = {
                        //action: 'save-draft-data'
                        action: 'save-data'
                    };
                    
                    $.post(window.ajaxurl, data, function(response) {
                      console.log(response);
                    });*/
                  });
                })(jQuery);
            </script>
HTML;

        if (!empty($_POST['member_email'])) {
            $user_data = $this->renderUserData($_POST['member_email']);
            $member_data = $this->renderMemberData($_POST['member_email']);
            $draft_data = $this->renderDraftData($_POST['member_email']);
        }

        echo <<<HTML
                
                <hr>
                
                {$user_data}
                
                {$member_data}
               
                {$draft_data}
            </div>
HTML;
    }

    private function renderUserData($email) {
        $user = get_user_by('email', $email);

        $html = <<<HTML
            <h2>User Current Data</h2>
            <div class="data">
                <div class="row">
                    <span class="key">User ID</span>
                    <span class="value">{$user->ID}</span>
                </div>
                <div class="row">
                    <span class="key">User Email</span>
                    <span class="value">{$user->user_email}</span>
                </div>
                <div class="row">
                    <span class="key">User First Name</span>
                    <span class="value">{$user->user_firstname}</span>
                </div>
                <div class="row">
                    <span class="key">User Last Name</span>
                    <span class="value">{$user->user_lastname}</span>
                </div>
HTML;

        $user_meta = get_user_meta($user->ID);

        foreach ($user_meta as $key => $data) {
            $html .= <<<HTML
            <div class="row">
                <span class="key">{$key}</span>
                <span class="value">{$data[0]}</span>
            </div>
HTML;

        }

        $html .= <<<HTML
            </div>
HTML;

        return $html;
    }

    private function renderMemberData($email) {
        $user = get_user_by('email', $email);
        //error_log(print_r($user->ID, true));
        $member_data =  $this->member->getMembershipData($user->ID);
        //error_log(print_r($member_data, true));

        $member_data['payment_date'] = get_date_from_gmt( date( 'Y-m-d H:i:s', $member_data['payment_date'] ), 'F j, Y H:i:s');

        if (!empty($member_data['file_path'])) {
            if (is_array($member_data['file_path'])) {
                foreach ($member_data['file_path'] as $key => $path) {
                    $member_data['file_path'][$key] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
                }
            } else {
                $member_data['file_path'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $member_data['file_path']);
            }
        }

        $html = <<<HTML
            <h2>Member Current Data</h2>
            <div class="data">
HTML;

        foreach ($member_data as $key => $data) {
            $html .= <<<HTML
            <div class="row">
                <span class="key">{$key}</span>
                <span class="value">{$data}</span>
            </div>
HTML;

        }

        $html .= <<<HTML
            </div>
HTML;

        return $html;
    }

    private function renderDraftData($email) {
        $member_data =  $this->member->getDraftMemberData($email);

        $html = <<<HTML
            <h2>Member Draft Data</h2>
            <div class="data">
HTML;

        foreach ($member_data as $key => $data) {
            $html .= <<<HTML
            <div class="row">
                <span class="key">{$key}</span>
                <span class="value">{$data}</span>
            </div>
HTML;

        }

        $html .= <<<HTML
            </div>
HTML;

        return $html;
    }
}