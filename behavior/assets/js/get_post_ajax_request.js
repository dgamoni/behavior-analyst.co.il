jQuery(document).ready(function($){
    
ajaxShowPost = {  
    init : function() {        
       
        ajaxShowPost.get_data();
        ajaxShowPost.paginationPageEvent();
        //ajaxShowPost.selectTriger();
       
           
                
    },
    /*selectTriger : function(){
        
    $('.wrap-select').click(function(){
       var select = $(this).find('.select');
       select.click();
        console.log(select);

    });  
    },*/
    paginationPageEvent : function(){
      $('body').on('click','.pagievent', function(e) {
            var pagenumber =  $(this).attr('id');
            //var formid = $('#curform').val();
            ajaxShowPost.pagination_ajax(pagenumber);
            return false;
        });  
    },
  
   get_data : function () {
        $( '#awpqsf_id_btn' ).click(function( ) {

            var resoult = $('#resoult');	
            //var res = {loader:$('<div />',{'class':'mloading'}),container : $(''+ajxdiv+'')};
            var getdata = $("#filter-form").serialize();
            var pagenum = '1';

            jQuery.ajax({
                cache: false,
                timeout: 8000,
                url: window.wpAjaxURL,
                type: "POST",	 
                data: ({action : 'awpqsf_ajax',getdata:getdata, pagenum:pagenum }),
                beforeSend:function() {
                    $('#resoult,#ajax-response').empty();
                    $('.mloading').fadeIn('fast');
                },
                success: function(html) {
                    console.log(html);
                $('.mloading').hide();
                $('#resoult').html(html);
                
                }
            });
        });
    },
    pagination_ajax : function (pagenum) {           
        var resoult = $('#resoult');	
        //var res = {loader:$('<div />',{'class':'mloading'}),container : $(''+ajxdiv+'')};
        var getdata = $("#filter-form").serialize();
        //console.log(getdata);
        jQuery.ajax({
            cache: false,
            timeout: 8000,
            url: window.wpAjaxURL,
            type: "POST",	 
            data: ({action : 'awpqsf_ajax',getdata:getdata,pagenum:pagenum  }),
            beforeSend:function() {
                $('#resoult,#ajax-response').empty();
                $('.mloading').fadeIn('fast');                   
            },
            success: function(html) {
            $('.mloading').hide();
            $('#resoult').html(html);
           
            }
        });
    }
    
};    


jQuery(document).ready(function($){
    ajaxShowPost.init();
    
    $('#filter-form').on('keypress', function(event){
    	if(event.keyCode == 13) {
	   event.preventDefault();
           $('#awpqsf_id_btn').trigger('click');
        }
    });

});


});
 