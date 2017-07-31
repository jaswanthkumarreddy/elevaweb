var ajaxURL = $("#ajaxBaseURL").val();
$(document).ready(function() {
 
	$(".levelClick").change(function() {
	  
	
	 $.ajax({
				url : ajaxURL + "/menu_list",
				type: 'post',
				cache: false, 
				data: {'level_id': $(this).val()},
				beforeSend : function() {
				   $(".loader").show();
				},
				success : function(data) {	
				   $("#menu-list").html(data);
				   $("#menu-list").show();
				},
				complete : function() {
				   $(".loader").hide();
				}
			});
	
	
	
	
	});
	
	
	
});
