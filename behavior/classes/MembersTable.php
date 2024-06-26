<?php
/**
 * Created by PhpStorm.
 * User: oleksii.khodakivskyi
 * Date: 25.12.18
 * Time: 23:28
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class MembersTable extends WP_List_Table {
    private $member;

    public function __construct($member) {
        $this->member = $member;
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'key' => 'Key',
            'ID' => 'ID',
            'user_email' => 'Email',
            'phone'     => 'Phone',
            'user_nicename' => 'Full Name',
            'membership_type' => 'Membership Type',
            'account_number' => 'Account Number',
            'payment_date' => 'Date of Payment',
            'transaction_id' => 'Transaction ID',
            'active' => 'Active'
        );
        return $columns;
    }

    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->screen = get_current_screen();
        $this->items = $this->getItemsData();
        //var_dump($this->getItemsData());
        /** Process bulk action */
        $this->process_bulk_action();
    }

    function display() {
        $page = $_REQUEST['page'];
        echo <<<HTML
            <form id="events-filter" method="get">
             <input type="hidden" name="page" value="{$page}" />
HTML;

        parent::display();

        echo <<<HTML
            </form>
HTML;
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'key':
            case 'ID':
            case 'user_email':
            case 'phone':
            case 'user_nicename':
            case 'membership_type':
            case 'account_number':
            case 'payment_date':
            case 'active':
            case 'transaction_id':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'key' => array('key',true),
            'ID' => array('ID',true),
            'user_email' => array('user_email',true),
            'phone' => array('phone',true),
            'user_nicename' => array('user_nicename',true),
            'membership_type' => array('membership_type',true),
            'account_number' => array('account_number',false),
            'payment_date' => array('payment_date',false),
            'transaction_id' => array('transaction_id',false)
        );
        return $sortable_columns;
    }

    function usort_reorder() {
        $orderby = !empty($_GET['orderby']) ? $_GET['orderby'] : 'ID';
        $order = !empty($_GET['order']) ? $_GET['order'] : 'asc';
        return 'orderby=' . $orderby . '&order=' . $order;
    }

    private function getItemsData() {
        $items = array();
        $filter  = $this->usort_reorder();
        //$members = $this->member->getMembers($filter);

        if ( !empty($_GET['order']) && !empty($_GET['orderby']) ) {
            $members = $this->member->getMembers($filter);
        } else {
            $members = get_users('orderby=meta_value&meta_key=membership_payment_date&order=asc');
        }
        
        //var_dump($members);

        //var_dump( $this->member->getMembershipData(356) );


        foreach ($members as $key => $member) {
            $membership_data = $this->member->getMembershipData($member->ID);
            $payment_date = !empty($membership_data['payment_date']) ? get_date_from_gmt( date( 'Y-m-d H:i:s', $membership_data['payment_date'] ), 'F j, Y H:i:s' ) : '';
            
            //$active_member = get_field('active_member', 'user_'.$member->ID );
            $active_member = get_user_meta( $member->ID, 'active_member', true ); 
            if( $active_member == 'true' ) {
                $active_member_status = 'checked'; 
            } else {
                $active_member_status = '';
            }
            // $all_meta_for_user = get_user_meta( 92 );
            // if ( !isset($membership_data['first-name']) ) {
            //     $membership_data['first-name'] = '';
            // }
            // if ( !isset($membership_data['last-name']) ) {
            //     $membership_data['last-name'] = '';
            // }            

            $items[] = array(
                'key' => $key+1,
                'ID' => $member->ID,
                'user_nicename' => $membership_data['first-name'] . ' ' . $membership_data['last-name'],
                'user_email' => $member->user_email,
                'phone' => $membership_data['cell-phone'],
                'membership_type' => $membership_data['membership_type'],
                'account_number' => $membership_data['account_number'],
                'payment_date' => $payment_date,
                'transaction_id' => $membership_data['transaction_index'],
                'active' => '<input class="check_active" type="checkbox" data-id="'.$member->ID.'" '.$active_member_status.' data-status="'.$active_member.'" />'
            );
            // var_dump($member->ID);
            // if($member->ID == 356) {
            //     var_dump($items);
            // }
        }

        if ('payment_date' === $_GET['orderby'] || 'membership_type' === $_GET['orderby']) {
            usort($items, function ($a1, $a2){
                if ($a1[$_GET['orderby']] == $a2[$_GET['orderby']]) return 0;

                if ('asc' === $_GET['order']) {
                    return ($a1[$_GET['orderby']] < $a2[$_GET['orderby']]) ? -1 : 1;
                }

                return ($a1[$_GET['orderby']] > $a2[$_GET['orderby']]) ? -1 : 1;
            });
        }

        //error_log(print_r($items, true));
        //var_dump($items);

        return $items;
    }

    /**
     * Render the bulk edit checkbox
     * * @param array $item
     * * @return string
     */
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']);
    }
    /**
     * Render the bulk edit checkbox
     * * @param array $item
     * * @return string
     */
    function column_first_column_name($item)
    {
        return sprintf('<a href="%s" class="btn btn-primary"/>Link Title</a>', $item['first_column_name']);
    }

    /**
     * Returns an associative array containing the bulk action
     * * @return array */
    public function get_bulk_actions()
    {
        $actions = ['bulk-delete' => 'Delete'];
        return $actions;
    }
    public function process_bulk_action()
    {
        // If the delete bulk action is triggered
        if ((isset($_GET['action']) && $_GET['action'] == 'bulk-delete') ||
            (isset($_GET['action2']) && $_GET['action2'] == 'bulk-delete')) {
                $delete_ids = esc_sql($_GET['bulk-delete']);
        // loop over the array of record IDs and delete them
        foreach($delete_ids as $id) {
            $this->delete_records($id);
        }
        $redirect = admin_url('admin.php?page=members');
        wp_redirect($redirect);
        exit;
        }
    }
    /**
     * Delete a record record.
     * * @param int $id customer ID
     */
    public function delete_records($id)
        {
            $this->member->deleteMember($id);
        }
}