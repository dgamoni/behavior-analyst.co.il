

jQuery(document).ready(function($) {

	$('.check_active').click(function(event) {
		var data_id = $(this).attr('data-id');
		console.log(data_id);
		var ckb = $(this).is(':checked');

		        $.ajax({
	                type    : "POST",
	                url     : MyAjax.ajaxurl,
	                dataType: "json",
	                data    : "action=update_member&mem_id=" + data_id + "&ckb=" + ckb,
	                success : function (a) {
	                    console.log(a);
	                }

				});
	});
		
});  