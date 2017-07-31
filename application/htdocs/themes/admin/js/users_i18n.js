 $('.date').datepicker({
    format: 'dd/mm/yyyy',
    startDate: '-30d',
    endDate: '+0d',
    autoclose: true
 });

var ajaxURL = $("#ajaxBaseURL").val();
$(document).ready(function() {
	
  $("input[name=username]").blur(function(){ 

	if($(this).val() != "") {
  
	  $.ajax({
            url : ajaxURL + "/check_username",
            type: 'post',
            cache: false,
			dataType: "json",
            data: {'username': $(this).val(),'current' : $("input[name=id]").val()},
            beforeSend : function() {
               $(".loader").show();
            },
            success : function(data) {	
				
                 if(data == "1") {  
					 $("input[name=username]").val('');			 
                }   
            },
            complete : function() {
               $(".loader").hide();
            }
        }); 
	}
		
  });

 $("input[name=email_id]").blur(function(){
	 
	 if($(this).val() != "") {
		 
	  $.ajax({ 
            url : ajaxURL +"/check_email",
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

 
 