<?php 


add_action( 'wp_ajax_update_member', 'update_member_func' );
//add_action( 'wp_ajax_nopriv_update_member', 'update_member_func' );
function update_member_func() {
		
	$mem_id = $_POST['mem_id'];
	$ckb = $_POST['ckb'];
	$out = '';
	//$up = update_field('active_member', $ckb, 'user_'.$mem_id ); 
	//$getup = get_field('active_member', 'user_'.$mem_id );
	$up = update_user_meta($mem_id, 'active_member', $ckb);
	$getup = get_user_meta( $mem_id, 'active_member', true ); 

	$res['mem_id'] = $mem_id;
	$res['ckb'] = $ckb;
	$res['up'] = $up;
	$res['getup'] = $getup;


		//wp_send_json_success($res);
		echo json_encode( $res );
		exit;

}

// add_action( 'wp_ajax_addcupon_coupon_multiple_status', 'addcupon_coupon_multiple_status_func' );
// add_action( 'wp_ajax_nopriv_addcupon_coupon_multiple_status', 'addcupon_coupon_multiple_status_func' );
function addcupon_coupon_multiple_status_func() {
	$name = $_POST['name'];
	$status = $_POST['status'];
	$res = add_option( 'statuscupon_'.$name, $status );	
		echo json_encode( $res );
		exit;	

}

