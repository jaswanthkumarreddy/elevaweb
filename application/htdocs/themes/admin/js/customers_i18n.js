var ajaxURL = $("#ajaxBaseURL").val();
$(document).ready(function() {
	 
 $("input[name=email_id]").blur(function(){
	 
	 if($(this).val() != "") {
		 
	  $.ajax({ 
            url : ajaxURL +"/check_customeremail",
            type: 'post',
            cache: false,
			dataType: "json",
            data: {'email_id': $(this).val(),'current' : $("input[name=id]").val()},
            beforeSend : function() {
               $(".loader").show();
            },
            success : function(data) {					
                 if(data == "1") {  
					 $("input[name=email_id]").val('');			 
                }   
            },
            complete : function() {
               $(".loader").hide();
            }
        });
	 }
	 
  });  
  
  
});

 
 