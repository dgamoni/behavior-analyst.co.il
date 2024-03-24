<?php
/**
 * Created by PhpStorm.
 * User: oleksii.khodakivskyi
 * Date: 25.12.18
 * Time: 23:10
 */

if( ! class_exists( 'MembersTable' ) ) {
    require_once 'MembersTable.php';
}

if( ! class_exists( 'MembersDiscounts' ) ) {
    require_once 'MembersDiscounts.php';
}

if( ! class_exists( 'MembersFix' ) ) {
    require_once 'MembersFix.php';
}

if( ! class_exists( 'Member' ) ) {
    require_once 'Member.php';
}

class MemberAdmin {
    private $members_table;

    private $discounts;

    private $fix_members;

    private $member;

    public function __construct() {
        $this->initHooks();

        $this->member = new Member();
        $this->members_table = new MembersTable($this->member);
        $this->discounts = new MembersDiscounts();
        $this->fix_members = new MembersFix($this->member);
    }

    private function initHooks() {
        add_action('admin_menu', [$this, 'members_list']);
    }

    public function members_list() {
        add_menu_page( 'Members', 'Members', 'manage_options', 'members', [$this, 'renderMembers'], 'dashicons-tickets', 6  );
        add_submenu_page( 'members', 'Coupons', 'Payment Discounts', 'manage_options', 'coupons', [$this, 'renderDiscounts']);
        add_submenu_page( 'members', 'Fix', 'Fix members', 'manage_options', 'fix_members', [$this, 'fixMembers']);
    }

    public function renderMembers() {
        $this->members_table->prepare_items();
        $this->members_table->display();
    }

    public function renderDiscounts() {
        $this->discounts->display();
    }

    public function fixMembers() {
        $this->fix_members->display();
    }
}