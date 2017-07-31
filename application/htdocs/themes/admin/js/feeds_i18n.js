var ajaxURL = $("#ajaxBaseURL").val();
$(document).ready(function() {
  /* Add category in session....  */
 $("#add_category").click(function(){
	 if($("input[name=feed_path]").val() != "" || $("input[name=feed_category]").val() != "" ) { 
	  $.ajax({ 
            url : ajaxURL +"/feed_add_category",
            type: 'post',
            cache: false,
			dataType: "json",
            data: {'feed_path': $("input[name=feed_path]").val() ,'feed_category' : $("input[name=feed_category]").val()},
            beforeSend : function() {
               $(".loader").show();
            },
            success : function(data) {					
                 getFeedList();  
				 $("input[name=feed_path]").val('');
				 $("input[name=feed_category]").val('');
				 $("input[name=feed_path]").focus();
            },
            complete : function() {
               $(".loader").hide();
            }
        }); 
	 }else {
		alert("Enter Feed path or Feed category..."); 
	  }
	 
  });   
  
  /* End Add category in session....  */ 
  
});

function getFeedList(){
	 
 $.ajax({ 
		url : ajaxURL +"/feed_path_category_list_in_session",
		type: 'post',
		cache: false,
		beforeSend : function() {
		   $(".loader").show();
		},
		success : function(data) {					
	  		$(".feed_path_category_list").html(data);
		},
		complete : function() {
		   $(".loader").hide();
		}
	});  
}

function deleteFeed(id){
	if (confirm("Are you sure you want to delete this record?")) {	
	   $.ajax({ 
			url : ajaxURL +"/delete_feed_in_session",
			type: 'post',
			cache: false,		 
			data:  { dId: id },
			beforeSend : function() {
			   $(".loader").show();
			},
			success : function(data) {					
				getFeedList();
			},
			complete : function() {
			   $(".loader").hide();
			}
		}); 
	}
}
 

 