<?php
require_once( 'Facebook/autoload.php' );

use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;
//FacebookSession::setDefaultApplication('709790099209475', '099a27fca241ff7c96330a3f35e3713d');
if(!isset($_REQUEST['code'])){
	if(isset($_REQUEST['choose']) && $_REQUEST['choose'] == "profile"){
		$r = $_REQUEST['redirect'];
		$fb_token = "https://www.facebook.com/v2.6/dialog/oauth?client_id=709790099209475&redirect_uri=https://www.addanyproject.com/weleva/wordpress/FB_API/fb_auth.php&state=profile,".$r."&scope=email,public_profile,publish_pages,user_posts,publish_actions,manage_pages,user_photos";
		header('Location: '.$fb_token);
	}else if(isset($_REQUEST['choose']) && $_REQUEST['choose'] == "page"){
		$r = $_REQUEST['redirect'];
		$fb_token = "https://www.facebook.com/v2.6/dialog/oauth?client_id=709790099209475&redirect_uri=https://www.addanyproject.com/weleva/wordpress/FB_API/fb_auth.php&state=page,".$r."&scope=email,public_profile,publish_pages,user_posts,publish_actions,manage_pages,user_photos";
		header('Location: '.$fb_token);
	}
}
if(isset($_REQUEST['code'])){
	
	$redirect_uri = 'https://www.addanyproject.com/weleva/wordpress/FB_API/fb_auth.php';
	$code = $_REQUEST['code'];
	if(!empty($code)){
		if($code){
			$token_url = "https://graph.facebook.com/v2.6/oauth/access_token?"
			. "client_id=709790099209475&redirect_uri=" . $redirect_uri
			. "&client_secret=099a27fca241ff7c96330a3f35e3713d&code=" . $code;
			
			$response = file_get_contents( $token_url );
			
			if(!empty($response)){
				$params = json_decode($response);
				if(isset($params->access_token))
				$access_token = $params->access_token;
			}
			if($access_token != ""){
				$type_array = urldecode( $_REQUEST['state'] );
				$type = explode(",",$type_array);
				$ajax_url = $type[1];
				if($type[0] == "profile"){
					$url = 'https://graph.facebook.com/v2.6/me?access_token='.$access_token;
					
					$contentget = file_get_contents( $url );
					if(!empty( $contentget )){
						$fb_data = json_decode( $contentget );
						$user_id = $fb_data->id;
						$user_name = $fb_data->name;
						
						$session = new FacebookSession( $access_token );
						$session = FacebookSession::newAppSession();
						$request = new FacebookRequest(
							$session,
							'POST',
							'/709790099209475/roles',
							array (
							'user' => $user_id,
							'role' => 'administrators',
							)
						);
						$response = $request->execute();
						$graphObject = $response->getGraphObject();
					}
				}else if($type[0] == "page"){
					$url = "https://graph.facebook.com/v2.6/me/accounts?access_token=".$access_token."";
					$content = file_get_contents( $url );
					if(!empty( $content )){
						$params = json_decode( $content );
						if(isset($params->data)){
							$fb_pages = $params->data;
							echo '<form method="post" id="select_fb_page">';
							echo '<select class="elevaweb-select semi-square" name="fb_page" id="fb_page_select">';
							if(is_array( $fb_pages )){
								foreach($fb_pages as $d){
									echo '<option data-name="'.$d->name.'" data-token="'.$d->access_token.'" value="'.$d->id.'">'.$d->name.'</option>';
								}
							}
							echo '</select><input type="submit" name="btn_submit" id="page_submit" value="Select Page" />';
							echo '</form>';
						}
					}
				}
			}
		}
	}
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function(){
	var type = '<?php echo (!empty($type[0])) ? $type[0] : ''; ?>';
	
	if(type != ""){
		var ajax_url = '<?php echo (!empty( $ajax_url )) ? $ajax_url : ''; ?>/wp-admin/admin-ajax.php?action=recieve_access_token_from_other_site';
		var access_token = '<?php echo $access_token; ?>';
		
		if(type == "profile"){
			var fb_id = '<?php echo $user_id; ?>';
			var fb_name = '<?php echo $user_name; ?>';
			var post_data = {
				'Accesskey' : '12345',
				'Access_Token' : access_token,
				'fb_id' : fb_id,
				'fb_name' : fb_name,
				'type' : type
			}
			if(ajax_url != ""){
				$.post(ajax_url, post_data, function( response ){
					var obj = $.parseJSON( response );
					if(obj.error == 1){
						alert(obj.msg);
						window.close();
					}else if(obj.error == 0){
						alert(obj.msg);
						window.close();
					}
				});
			}
		}
		if(type == "page"){
			$("#select_fb_page").submit(function(){
				var data = {
					'Accesskey' : '12345',
					'page_name': $( "#fb_page_select option:selected" ).data("name"),
					'page_token': $( "#fb_page_select option:selected" ).data("token"),
					'page_id': $( "#fb_page_select option:selected" ).val(),
					'type' : type
				};
				if(ajax_url != ""){
					$.post(ajax_url, data, function( response ){
						var obj = $.parseJSON( response );
						if(obj.error == 1){
							alert(obj.msg);
							window.close();
						}else if(obj.error == 0){
							alert(obj.msg);
							window.close();
						}
					});
				}
				return false;
			});
		}
	}
	
});
</script>