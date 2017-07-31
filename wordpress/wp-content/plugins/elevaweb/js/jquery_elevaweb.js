function validateURL(url) {

	return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);

}

function pop_elevaweb(url){

	//window.location.replace( url );

	var location = encodeURI(window.location.protocol + '//' + window.location.hostname);

    window.open(url + '&location=' + location, name, "width=650,height=900,scrollbars=yes,toolbar=no,status=no,resizable=no,menubar=no,location=no,directories=no,top=20,left=20");

}

(function( $ ) {

	$("body").on("click","#fb_profile",function(){

		

		//$("#fb_add_form").append('<input type="hidden" name="action" value="add_new_fb_app" />');

		/* $("#app_type").val("profile");

		$("#fb_app_name").val('');

		$("#fb_app_id").val('');

		$("#fb_app_secret").val('');

		$("#action").val("add_new_fb_app");

		$("#new_app_add").show();

		$("#insta_app_add").hide(); */

	});

	$("body").on("click","#fb_page",function(){

		//$("#fb_add_form").append('<input type="hidden" name="action" value="add_new_fb_app" />');

		$("#app_type").val("page");

		$("#fb_app_name").val('');

		$("#fb_app_id").val('');

		$("#fb_app_secret").val('');

		$("#action").val("add_new_fb_app");

		$("#insta_app_add").hide();

		$("#new_app_add").show();

	});

	$("#fb_add_form").submit(function(){

		var app_name = $("#fb_app_name").val();

		var app_id = $("#fb_app_id").val();

		var app_secret = $("#app_secret").val();

		

		if(app_name == ""){

			$("#fb_error").text("Enter App Name");

			return false;

		}

		if(app_id == ""){

			$("#fb_error").text("Enter App Id");

			return false;

		}

		if(app_secret == ""){

			$("#fb_error").text("Enter App Secret");

			return false;

		}

		var data = $(this).serialize();

		$.post(elevaweb.ajax_url, data, function(response) {

			var obj = $.parseJSON( response );

			if(obj.Error == 0){

				window.location.replace( obj.redirect_uri );

			}else if(obj.Error == 1){

				$("#fb_error").text( obj.Msg );

			}

		});

		return false;

	});

	$("body").on("click",".edit_fb_account",function(){

		var acc_id = $(this).parent().data("id");

		if(acc_id != ""){

			var data = {

				'action': 'get_fb_detail',

				'acc_id': acc_id

			};

			$.post(elevaweb.ajax_url, data, function(response) {

				var obj = $.parseJSON( response );

				if(obj.Error == 0){

					//console.log(obj.Data);

					$("#fb_app_name").val(obj.Data.application_name);

					$("#fb_app_id").val(obj.Data.application_id);

					$("#fb_app_secret").val(obj.Data.application_secret);

					$("#action").val("update_fb_app");

					$("#insta_app_add").hide();

					$("#new_app_add").show();

				}

			});

			

		}

	});

	$("body").on("click",".delete_fb_account,.delete_insta_account",function(){

		var acc_id = $(this).parent().data("id");

		if(acc_id != ""){

			var data = {

				'action': 'delete_fb_detail',

				'acc_id': acc_id

			};

			$.post(elevaweb.ajax_url, data, function(response) {

				var obj = $.parseJSON( response );

				if(obj.Error == 0){

					location.reload();

				}

			});

			

		}

	});

	$("#select_page_form").submit(function(){

		var data = {

				'action': 'add_fb_page',

				'_wpnonce': $("#select_page_form").find("#wpnonce").val(),

				'page_name': $( "#fb_page_select option:selected" ).data("name"),

				'page_token': $( "#fb_page_select option:selected" ).data("token"),

				'page_id': $( "#fb_page_select option:selected" ).val()

		};

		$.post(elevaweb.ajax_url, data, function(response) {

			var obj = $.parseJSON( response );

			if(obj.Error == 0){

				window.location.replace( obj.URL );

			}

		});

		return false;

	});

	$("body").on("click","#twitter_profile",function(){

		$("#new_twitter_add").show();

	});

	$("#twitter_add_form").submit(function(){

		var twitter_user_name = $("#twitter_user_name").val();

		var twitter_api_key = $("#twitter_api_key").val();

		var twitter_api_secret = $("#twitter_api_secret").val();

		var twitter_access_token = $("#twitter_access_token").val();

		var twitter_access_token_secret = $("#twitter_access_token_secret").val();

		

		if(twitter_user_name == ""){

			$("#twitter_error").text("Enter Username");

			return false;

		}

		if(twitter_api_key == ""){

			$("#twitter_error").text("Enter API Key");

			return false;

		}

		if(twitter_api_secret == ""){

			$("#twitter_error").text("Enter API Secret");

			return false;

		}

		if(twitter_access_token == ""){

			$("#twitter_error").text("Enter Access Token");

			return false;

		}

		if(twitter_access_token_secret == ""){

			$("#twitter_error").text("Enter Access Token Secret");

			return false;

		}

		var data = $(this).serialize();

		$.post(elevaweb.ajax_url, data, function(response) {

			var obj = $.parseJSON( response );

			if(obj.Error == 0){

				location.reload();

			}else if(obj.Error == 1){

				$("#twitter_error").text( obj.Msg );

			}

		});

		return false;

	});

	$("body").on("click","#insta_profile",function(){

		$("#insta_action").val("add_new_insta_app");

		$("#insta_username").val('');

		$("#insta_password").val('');

		$("#new_app_add").hide();

		$("#insta_app_add").show();

	});

	$("#insta_add_form").submit(function(){

		var ins_username = $("#insta_username").val();

		var ins_password = $("#insta_password").val();

		

		if(ins_username == ""){

			$("#insta_error").text("Enter Username");

			return false;

		}

		if(ins_password == ""){

			$("#insta_error").text("Enter Password");

			return false;

		}

		var data = $(this).serialize();

		$.post(elevaweb.ajax_url, data, function(response) {

			var obj = $.parseJSON( response );

			if(obj.Error == 0){

				location.reload();

			}else if(obj.Error == 1){

				$("#insta_error").text( obj.Msg );

			}

		});

		return false;

	});

	$("body").on("click",".edit_insta_account",function(){

		var acc_id = $(this).parent().data("id");

		if(acc_id != ""){

			var data = {

				'action': 'get_fb_detail',

				'acc_id': acc_id,

				'social': 'instagram'

			};

			$.post(elevaweb.ajax_url, data, function(response) {

				var obj = $.parseJSON( response );

				if(obj.Error == 0){

					//console.log(obj.Data);

					$("#insta_action").val("update_insta_app");

					$("#insta_username").val(obj.Data.user_name);

					$("#insta_password").val(obj.Data.access_token);

					$("#new_app_add").hide();

					$("#insta_app_add").show();

				}

			});

			

		}

	});

	$("body").on("click",".btn_dlt",function(){

		var id = $(this).data('id');
		var action = $(this).data('action');
		var r = confirm("Are You Sure Want to delete ?");
		if (r == true) {
			window.location.replace( elevaweb.admin_url+'?page=eleva-post-config&action=delete_post&id='+id );
		}
	});

	$("body").on("change","#src_feed",function(){
		var data = {
			"action": "getFeedcatajax",
			"feed": $(this).val()
		};
		$.ajax({
			type:'POST',
			url: elevaweb.ajax_url,
			data: data,
			beforeSend: function(){
				$('#loading').show();
			},
			complete: function(){
				$('#loading').hide();
			},
			success: function(response){
				if(response){					
					$("#src_cat").html(response);
				}
			}
		});
	});
	$("body").on("click","#save_schedule",function(){
		$("#save_type").val("save_schedule");
		$("#eleva-new-post").submit();
	});

	$("body").on("click","#save_schedule_published",function(){
		$("#save_type").val("save_schedule_published");
		$("#eleva-new-post").submit();
	});

	$("#eleva-new-post").submit(function(){
		var validatedURL = true;
		if($('#src_feed option:selected').val() == ""){
			$('#src_feed').focus();
			$('#src_feed').siblings('.validation-error').addClass('active');
			return false;
		}else{
			$('#src_feed').siblings('.validation-error').removeClass('active');
		}

		if($('#src_cat option:selected').val() == "") {
			$('#src_cat').focus();
			$('#src_cat').siblings('.validation-error').addClass('active');
			return false;
		}else{
			$('#src_cat').siblings('.validation-error').removeClass('active');
		}

		if($(this).find('#add_image').is(':checked')) {

			var urls = $('textarea[name="eleva_image_url"]').val();

			if(urls == ""){

				$("#new_post_error").html('<div class="error fade"><p>Enter url for image</p></div>');

				return false;

			}

			if(urls != "" && typeof urls != "undefined") {

				var matches = urls.match(/\n/g);

				var new_lines = matches ? matches.length : 0;

				if(new_lines) {

					urls = urls.split('\n');

				}

				else if(urls.indexOf(',') > -1) {

					urls = urls.split(',');

				}

				else {

					urls = [urls];

				}

				if(urls.length > 0){

					urls.forEach(function(value,index) {

						validatedURL = validateURL(value);

						if(!validatedURL) {

							return false;

						}

					});

				}	

				if(!validatedURL){

					$("#new_post_error").html('<div class="error fade"><p>Invalid url given for image</p></div>');

					return false;

				}	

			}

		}

		if($('input[name="days[]"]:checked').length == 0) {

			$('input[name="days[]"]').parents().closest('.eleva-profile-input').find('.validation-error').addClass('active');

			return false;

		}else{

			$('input[name="days[]"]').parents().closest('.eleva-profile-input').find('.validation-error').removeClass('active');

		}

		if($('select[name="schedule_hour"] option:selected').val() == "-1" || $('select[name="schedule_minutes"] option:selected').val() == "-1") {

			$('select[name="schedule_hour"]').parents().closest('.eleva-radio-button-time').find('.validation-error').addClass('active');

			return false;

		}else{

			$('select[name="schedule_hour"]').parents().closest('.eleva-radio-button-time').find('.validation-error').removeClass('active');

		}

		

		var data = $(this).serialize();

		$.ajax({
			type:'POST',
			url: elevaweb.ajax_url,
			data: data,
			beforeSend: function(){
				$('#loading').show();
			},
			complete: function(){
				$('#loading').hide();
			},
			success: function(response){
			   var obj = $.parseJSON( response );
				if(obj.Error == 0){
					$("#new_post_error").html('<div class="error fade"><p>'+ obj.Msg +'</p></div>');
					location.reload();
				}else if(obj.Error == 1){
					$("#new_post_error").html('<div class="error fade"><p>'+ obj.Msg +'</p></div>');
				}
			}
		});

		return false;

	});

	$("#forgot_password").submit(function(){

		var email_id = $("#email_id").val();

		if( email_id == "" ){

			$(".massage").html("Enter Email");

			return false;

		}

		var data = {

			"action": "reset_password_user",

			"email_id": email_id

		};

		$.post(elevaweb.ajax_url, data, function(response) {

			var obj = JSON.parse( response );

			if(obj.success == 1){

				$("#email_id").val('');

				$(".massage").html( obj.message );

			}else{

				$("#email_id").val('');

				$(".massage").html( obj.message );

			}

		});

		return false;

	});

})( jQuery );