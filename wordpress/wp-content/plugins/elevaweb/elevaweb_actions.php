<?php
if( !defined('ABSPATH') ){ exit();}
//if(!isset($_SESSION['elevaweb_login'])){ return; }
add_action( 'wp_ajax_add_new_fb_app', 'elevaweb_add_new_fb_app' );
function elevaweb_add_new_fb_app() {
	if( wp_verify_nonce( $_POST['_wpnonce'], 'add_fb_profile' ) ) {
		global $wpdb;
		if(isset($_POST['fb_app_name']) && isset($_POST['fb_app_id']) && isset($_POST['fb_app_secret'])){
			$redirect_uri = urlencode( admin_url('admin.php?page=eleva-network&auth=1') );
			$fb_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook'" );
			if($fb_count <= 5 ){
				$duplicate_row = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE application_id = '".$_POST['fb_app_id']."' AND application_secret = '".$_POST['fb_app_secret']."' AND type = '".$_POST['app_type']."'" );
				if($duplicate_row > 0){
					$out = array("Error"=>1,"Msg"=>__("Duplicate Entry Found","elevaweb"));
				}else{
					$data = array(
								'application_name' => $_POST['fb_app_name'],
								'application_id' => $_POST['fb_app_id'],
								'application_secret' => $_POST['fb_app_secret'],
								'social_name' => 'Facebook',
								'type' => $_POST['app_type'],
								'user_id' => get_current_user_id()
							);
					$wpdb->insert($wpdb->prefix.'elevaweb_networks_authentication',$data);
					$last_id = $wpdb->insert_id;
					setcookie("last_id", $last_id, 0, "/");
					$session_state = md5(uniqid(rand(), TRUE));
					$dialog_url = "https://www.facebook.com/v2.6/dialog/oauth?client_id=".$_POST['fb_app_id']."&redirect_uri=".$redirect_uri."&state="
				. $session_state . "&scope=email,public_profile,publish_pages,user_posts,publish_actions,manage_pages,user_photos";
					
					$out = array("Error"=>0,"Msg"=>__("Success","elevaweb"),"redirect_uri"=>$dialog_url);
				}
			}else{
				$out = array("Error"=>1,"Msg"=>__("Maximum Account Limit Reached","elevaweb"));
			}
		}else{
			$out = array("Error"=>1,"Msg"=>__("Some Parameters Are Missing","elevaweb"));
		}
	}
	echo json_encode( $out );
	exit;
}
/* function my_project_updated_send_email( $post_id ) {

	// If this is just a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) ) return;

	$post_type = get_post_type( $post_id );
	if ( "post" != $post_type ) return;
	
	global $wpdb;
	$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook'");
	
	if($results){
		require 'networks/Facebook/autoload.php';
		foreach($results as $result){
			$fb = new \Facebook\Facebook([
			  'app_id' => $result->application_id,
			  'app_secret' => $result->application_secret,
			  'default_graph_version' => 'v2.6',
			  //'default_access_token' => '{access-token}', // optional
			]);
			$page_access_token = $result->access_token;
			$linkData = [
			  'source' => get_permalink( $post_id ),
			  'message' => get_the_title( $post_id ),
			];
			$response = $fb->post('/me/feed', $linkData, $page_access_token);
		}
	}
}
add_action( 'save_post', 'my_project_updated_send_email' ); */
function add_og_tags_in_post() {
	global $post;
	$post_id = $post->ID;
    ?>
        <meta property="og:title" content="<?php echo get_the_title( $post_id ); ?>" />
		<meta property="og:description" content="<?php echo wp_trim_words(strip_tags( $post->post_content ), 160); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="<?php echo get_permalink( $post_id ); ?>" />
		<meta property="og:site_name" content="<?php echo site_url(); ?>" />
		<meta property="og:image" content="<?php echo get_the_post_thumbnail_url( $post_id ); ?>" />
		<?php
		$canonical = get_post_meta($post_id,'custom_canonical_url',true);
		if( get_post_type( $post_id ) == "post" && !empty( $canonical )){
			?>
			<link rel="canonical" href="<?php echo $canonical; ?>" />
			<?php
		}else{
		?>
		<link rel="canonical" href="<?php echo get_permalink( $post_id ); ?>" />
    <?php
		}
}
add_action('wp_head', 'add_og_tags_in_post');
add_action( 'wp_ajax_get_fb_detail', 'get_fb_detail' );
function get_fb_detail() {
	if(isset($_POST['acc_id'])){
		global $wpdb;
		$results = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE ID = ".$_POST['acc_id']."");
		if($results){
			setcookie("last_id", $_POST['acc_id'], 0, "/");
			
			if(isset($_POST['social']) && $_POST['social'] == "instagram"){
				$d = array("user_name"=>$results->user_name,"access_token"=>$results->access_token);
			}else{
				$d = array("application_name"=>$results->application_name,"application_id"=>$results->application_id,"application_secret"=>$results->application_secret);
			}
			$out = json_encode( array("Error"=>0,"Data"=>$d), JSON_FORCE_OBJECT);
		}else{
			$out = json_encode( array("Error"=>1,"Data"=>__('No Results Found','elevaweb')), JSON_FORCE_OBJECT);
		}
	}else{
		$out = json_encode( array("Error"=>1,"Data"=>__('Id Not Define','elevaweb')), JSON_FORCE_OBJECT);
	}
	echo $out;
	exit;
}
add_action( 'wp_ajax_update_fb_app', 'update_fb_app' );
function update_fb_app(){
	$out = '';
	if( wp_verify_nonce( $_POST['_wpnonce'], 'add_fb_profile' ) ) {
		global $wpdb;
		if(isset($_POST['fb_app_name']) && isset($_POST['fb_app_id']) && isset($_POST['fb_app_secret'])){
			$redirect_uri = urlencode( admin_url('admin.php?page=eleva-network&auth=1') );
			$data = array(
						'application_name' => $_POST['fb_app_name'],
						'application_id' => $_POST['fb_app_id'],
						'application_secret' => $_POST['fb_app_secret'],
						'social_name' => 'Facebook',
						'user_id' => get_current_user_id()
					);
			$where = array("ID"=>$_COOKIE['last_id']);
			$wpdb->update($wpdb->prefix.'elevaweb_networks_authentication',$data,$where);
			$session_state = md5(uniqid(rand(), TRUE));
			$dialog_url = "https://www.facebook.com/v2.6/dialog/oauth?client_id=".$_POST['fb_app_id']."&redirect_uri=".$redirect_uri."&state="
		. $session_state . "&scope=email,public_profile,publish_pages,user_posts,publish_actions,manage_pages,user_photos";
			
		$out = array("Error"=>0,"Msg"=>__("Success","elevaweb"),"redirect_uri"=>$dialog_url);
		}
	}
	echo json_encode( $out );
	exit;
}
//Add Meta Box to post
function add_social_meta_box_to_post() {
    //add_meta_box( 'elevaweb-social', __( 'Elevaweb', 'elevaweb' ), 'social_meta_box_callback', 'post', 'side' );
}
add_action( 'add_meta_boxes', 'add_social_meta_box_to_post' );
function social_meta_box_callback( $post ){
	global $wpdb;
	$results = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook' AND status = 1");
	
	$results_twi = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Twitter'");
	
	$results_insta = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Instagram' AND status = 1");
	?>
	<ul>
		<?php
		/*if($results){
			foreach($results as $result){
				$fb = get_post_meta($post->ID,'elevaweb_facebook_'.$result->ID,true);
		?>
		<li>
			<label>
				<input type="checkbox" name="fb_<?php echo $result->ID; ?>" name="fb_<?php echo $result->ID; ?>" value="<?php echo $result->ID; ?>" <?php checked($fb,1); ?> /> <?php echo $result->social_name.' : '.$result->user_name; ?>
			</label>
		</li>
		<?php
			}
		}*/
		
		/*if($results_twi){
			foreach($results_twi as $result_twi){
				$twitter = get_post_meta($post->ID,'elevaweb_twitter_'.$result_twi->ID,true);
		?>
		<li>
			<label>
				<input type="checkbox" name="twitter_<?php echo $result_twi->ID; ?>" name="twitter_<?php echo $result_twi->ID; ?>" value="<?php echo $result_twi->ID; ?>" <?php checked($twitter,1); ?> /> <?php echo $result_twi->social_name.' : '.$result_twi->user_name; ?>
			</label>
		</li>
		<?php
			}
		} */
		/*if($results_insta){
			foreach($results_insta as $result_insta){
				$insta = get_post_meta($post->ID,'elevaweb_instagram_'.$result_insta->ID,true);
		?>
		<li>
			<label>
				<input type="checkbox" name="instagram_<?php echo $result_insta->ID; ?>" name="instagram_<?php echo $result_insta->ID; ?>" value="<?php echo $result_insta->ID; ?>" <?php checked($insta,1); ?> /> <?php echo $result_insta->social_name.' : '.$result_insta->user_name; ?>
			</label>
		</li>
		<?php
			}
		}*/
		?>
	</ul>
	<?php
}
//Save Meta Box
function save_elevaweb_meta_box( $post_id ) {
	
	if ( wp_is_post_revision( $post_id ) ) return;

	$post_type = get_post_type( $post_id );
	if ( "post" != $post_type ) return;
	
	global $wpdb;
	$results_fb = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook' AND status = 1");
	
	$results_insta = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Instagram' AND status = 1");
	
	$results_twi = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Twitter'");
	
	if($results_fb){
		foreach($results_fb as $result){
			if(isset($_POST['fb_'.$result->ID]) && $_POST['fb_'.$result->ID] == $result->ID){
				update_post_meta($post_id,'elevaweb_facebook_'.$result->ID,1);
				if($result->access_token != ""){
					elevaweb_share_facebook('709790099209475','099a27fca241ff7c96330a3f35e3713d',$result->access_token,$post_id,$result->type,$result->social_id);
				}
			}else{
				update_post_meta($post_id,'elevaweb_facebook_'.$result->ID,0);
			}
		}
	}
	if($results_insta){
		foreach($results_insta as $result_insta){
			if(isset($_POST['instagram_'.$result_insta->ID]) && $_POST['instagram_'.$result_insta->ID] == $result_insta->ID){
				update_post_meta($post_id,'elevaweb_instagram_'.$result_insta->ID,1);
				if($result_insta->access_token != ""){
					elevaweb_share_instagram($result_insta->user_name,$result_insta->access_token,$post_id);
				}
			}else{
				update_post_meta($post_id,'elevaweb_instagram_'.$result_insta->ID,0);
			}
		}
	}
    
	/* if($results_twi){
		foreach($results_twi as $result_twi){
			if(isset($_POST['twitter_'.$result_twi->ID]) && $_POST['twitter_'.$result_twi->ID] == $result_twi->ID){
				update_post_meta($post_id,'elevaweb_twitter_'.$result_twi->ID,1);
				if($result_twi->access_token != "" && $result_twi->user_name){
					elevaweb_share_twitter($result_twi->application_id,$result_twi->application_secret,$result_twi->access_token,$result_twi->social_id,$post_id);
				}
			}else{
				update_post_meta($post_id,'elevaweb_twitter_'.$result_twi->ID,0);
			}
		}
	} */
}
add_action( 'save_post', 'save_elevaweb_meta_box' );
if( !function_exists('elevaweb_share_facebook') ){
	function elevaweb_share_facebook($app_id,$app_secret,$token,$post_id,$type,$social_id){
		require 'networks/Facebook/autoload.php';
		$fb = new \Facebook\Facebook([
		  'app_id' => $app_id,
		  'app_secret' => $app_secret,
		  'default_graph_version' => 'v2.6'
		]);
		
		$linkData = [
		  'source' => get_permalink( $post_id ),
		  'message' => get_the_title( $post_id ),
		];
		if($type == "profile"){
			$response = $fb->post('/me/feed', $linkData, $token);
		}else if($type == "page"){
			$response = $fb->post(''.$social_id.'/feed', $linkData, $token);
		}
	}
}
add_action( 'wp_ajax_delete_fb_detail', 'delete_fb_detail' );
function delete_fb_detail(){
	if(isset($_POST['acc_id'])){
		global $wpdb;
		$wpdb->delete( $wpdb->prefix."elevaweb_networks_authentication", array( 'ID' => $_POST['acc_id'] ) );
		$out = array("Error"=>0,"Msg"=>__('Deleted Successfully','elevaweb'));
	}else{
		$out = array("Error"=>1,"Msg"=>__('Something Went Wrong','elevaweb'));
	}
	echo json_encode( $out );
	exit;
}
add_action( 'wp_ajax_add_fb_page', 'add_fb_page' );
function add_fb_page(){
	if( wp_verify_nonce( $_POST['_wpnonce'], 'add_fb_page' ) ) {
		global $wpdb;
		$res_page = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_id = '".$_POST['page_id']."'");
		if($res_page){
			$update_data = array(
					'user_name' => $_POST['page_name'],
					'social_id' => $_POST['page_id'],
					'access_token' => $_POST['page_token']
				);
			$where = array( "ID" => $res_page->ID );
			$wpdb->update($wpdb->prefix.'elevaweb_networks_authentication',$update_data,$where);
		}else{
			$update_data = array(
					'social_name' => 'Facebook',
					'user_id' => get_current_user_id(),
					'user_name' => $_POST['page_name'],
					'social_id' => $_POST['page_id'],
					'access_token' => $_POST['page_token'],
					'type' => 'page',
					'status' => 1
				);
			$wpdb->insert($wpdb->prefix.'elevaweb_networks_authentication',$update_data);
		}
		
	}
	$out = array("Error"=>0,"URL"=>admin_url('admin.php?page=eleva-network'));
	echo json_encode( $out );
	exit;
}
add_action( 'wp_ajax_add_new_twitter', 'add_new_twitter_profile' );
function add_new_twitter_profile(){
	if( wp_verify_nonce( $_POST['_wpnonce'], 'add_twitter_profile' ) ) {
		global $wpdb;
		$data = array(
					'application_name' => $_POST['twitter_user_name'],
					'application_id' => $_POST['twitter_api_key'],
					'application_secret' => $_POST['twitter_api_secret'],
					'social_name' => 'Twitter',
					'type' => 'profile',
					'user_name' => $_POST['twitter_user_name'],
					'access_token' => $_POST['twitter_access_token'],
					'social_id' => $_POST['twitter_access_token_secret'],
					'status' => 1,
					'user_id' => get_current_user_id()
				);
		$wpdb->insert($wpdb->prefix.'elevaweb_networks_authentication',$data);
		$out = array("Error"=>0);
	}else{
		$out = array("Error"=>1,"Msg"=>"Something Went Wrong.");
	}
	echo json_encode( $out );
	exit;
}
if( !function_exists('elevaweb_share_twitter') ){
	function elevaweb_share_twitter($consumer_key,$consumer_secret,$user_token,$user_secret,$post_id){
		require 'networks/Twitter/twitteroauth.php';
		$p = get_post( $post_id );
		$twobj = new TWAPTwitterOAuth(array( 'consumer_key' => $consumer_key, 'consumer_secret' => $consumer_secret, 'user_token' => $user_token, 'user_secret' => $user_secret,'curl_ssl_verifypeer' => false));
		
		$name = $postpp->post_title;
				
		$name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
		$name = strip_tags( $name );
		
		$content = $postpp->post_content;
		if($content != ""){
			$content = html_entity_decode($content, ENT_QUOTES, get_bloginfo('charset'));
			$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
			$content = preg_replace('/\[.+?\]/', '', $content);
			$content = wp_trim_words(strip_tags( $content ), 50);
		}
		$string = $name.'<br />'.$content;
		
		if(has_post_thumbnail( $post_id )){
			$img = array();
			$img = wp_remote_get( get_the_post_thumbnail_url( $post_id ) );
			if(is_array($img))
			{
				if(isset($img['body'])&& trim($img['body']) != ''){
					if(($img['headers']['content-length']) && trim($img['headers']['content-length']) != '')
					{
						$img_size = $img['headers']['content-length']/(1024*1024);
						if($img_size > 3){
							$image_found = 0;
							$img_status = "Image skipped(greater than 3MB)";
						}
					}	
					$img = $img['body'];
					$resultfrtw = $twobj->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $string), true, true);
				}
				else{
					$resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array('status' =>$string));
				}	
			}else{
				$resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array('status' =>$string));
			}
			
		}else{
			$resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array('status' =>$string));
		}
	}
}
if( !function_exists('elevaweb_share_instagram') ){
	function elevaweb_share_instagram($username,$password,$post_id,$debug = false){
		
		//if(has_post_thumbnail($post_id)){
			include $pluginDir.'networks/Instagram/autoload.php';
			require $pluginDir.'networks/Instagram/mgp25/instagram-php/src/Instagram.php';
			$caption = get_the_title( $post_id );
			//$photo = get_the_post_thumbnail_url( $post_id );
			
			$feature_image_id = get_post_thumbnail_id( $post_id );
			$feature_image_meta = wp_get_attachment_image_src($feature_image_id, '32');
            $photo = get_attached_file( $feature_image_id ); // Full path
			
			//$image = 'https://scontent-sit4-1.cdninstagram.com/t51.2885-19/15403325_230527950706575_2090802496443252736_a.jpg';
			
			$action_delete = FALSE;
			list($originalWidth, $originalHeight) = getimagesize($photo);
			$ratio = $originalWidth / $originalHeight;
			if($ratio < 0.8) {
				$cropH = $originalHeight;
				$cropW = $originalHeight * 0.8 + 2;
				$X = ($cropW - $originalWidth) / 2;
			
				$origimg = imagecreatefromjpeg($photo);
				$cropimg = imagecreatetruecolor($cropW,$cropH);
				$white = imagecolorallocate($cropimg, 255, 255, 255);
				imagefill($cropimg, 0, 0, $white);
			
				// Crop
				imagecopyresized($cropimg, $origimg, $X, 0, 0, 0, $originalWidth, $originalHeight, $originalWidth, $originalHeight);
				imagejpeg($cropimg, $pluginDir . 'temp.jpg');
				$photo = $pluginDir . 'temp.jpg';
				$action_delete = TRUE;
			}
			$photo = wp_get_image_editor( $photo );
			if ( ! is_wp_error( $image ) ) {
				$photo->resize( 500, 500 );
				$photo->save( plugin_dir_path( __FILE__ ) . 'temp.jpg' );
				$photo = $pluginDir . 'temp.jpg';
				$action_delete = TRUE;
			}
			
			$i = new \InstagramAPI\Instagram($username, $password, $debug);
			try {
				$i->login();
			} catch (Exception $e) {
				echo '<pre>';
				print_r( $e );
				echo '<pre>';
				exit;
			}
			try {
				$i->uploadPhoto($photo, $caption);
			} catch (Exception $e) {
				echo '<pre>';
				print_r( $e );
				echo '<pre>';
				exit;
			}
			if($action_delete == TRUE) {
				unlink($photo);
			} 
		//}
	}
}
add_action( 'wp_ajax_add_new_insta_app', 'add_new_insta_profile' );
function add_new_insta_profile(){
	if( wp_verify_nonce( $_POST['_wpnonce'], 'insta_add_profile' ) ) {
		global $wpdb;
		$insta_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Instagram'" );
		if($insta_count <= 5 ){
			$duplicate_row = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE user_name = '".$_POST['insta_username']."' AND access_token = '".$_POST['insta_password']."' AND type = 'profile'" );
			
			if($duplicate_row > 0){
				$out = array("Error"=>1,"Msg"=>__("Duplicate Entry Found","elevaweb"));
			}else{
				$data = array(
						'application_name' => $_POST['insta_username'],
						'application_id' => '',
						'application_secret' => '',
						'social_name' => 'Instagram',
						'type' => 'profile',
						'user_name' => $_POST['insta_username'],
						'access_token' => $_POST['insta_password'],
						'social_id' => '',
						'status' => 1,
						'user_id' => get_current_user_id()
					);
				$wpdb->insert($wpdb->prefix.'elevaweb_networks_authentication',$data);
				$out = array("Error"=>0);
			}
		}else{
			$out = array("Error"=>1,"Msg"=>__("Maximum Amount Reached","elevaweb"));
		}
	}else{
		$out = array("Error"=>1,"Msg"=>"Something Went Wrong.");
	}
	echo json_encode( $out );
	exit;
}
add_action( 'wp_ajax_update_insta_app', 'update_insta_profile' );
function update_insta_profile(){
	if( wp_verify_nonce( $_POST['_wpnonce'], 'insta_add_profile' ) ) {
		global $wpdb;
		$insta_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Instagram'" );
		if($insta_count <= 5 ){
			$duplicate_row = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE application_id = '".$_POST['fb_app_id']."' AND application_secret = '".$_POST['fb_app_secret']."' AND type = '".$_POST['app_type']."'" );
				if($duplicate_row > 0){
					$out = array("Error"=>1,"Msg"=>__("Duplicate Entry Found","elevaweb"));
				}else{
					$data = array(
							'application_name' => $_POST['insta_username'],
							'user_name' => $_POST['insta_username'],
							'access_token' => $_POST['insta_password']
						);
					$where = array("ID"=>$_COOKIE['last_id']);
					$wpdb->update($wpdb->prefix.'elevaweb_networks_authentication',$data,$where);
					$out = array("Error"=>0);
				}
		}else{
			$out = array("Error"=>1,"Msg"=>__("Maximum Amount Reached","elevaweb"));
		}
	}else{
		$out = array("Error"=>1,"Msg"=>"Something Went Wrong.");
	}
	echo json_encode( $out );
	exit;
}
function add_opengraph_nameser( $output ) {
 return $output . '
xmlns:og="http://opengraphprotocol.org/schema/"
xmlns:fb="http://www.facebook.com/2008/fbml"';
 }
add_filter('language_attributes', 'add_opengraph_nameser');
add_action( 'wp_ajax_recieve_access_token_from_other_site', 'recieve_access_token_from_other_site' );
add_action( 'wp_ajax_nopriv_recieve_access_token_from_other_site', 'recieve_access_token_from_other_site' );
function recieve_access_token_from_other_site(){
	if(isset($_POST['Accesskey']) && $_POST['Accesskey'] == 12345){
		if(!empty( $_POST['Access_Token'] ) || !empty($_POST['page_token'])){
			global $wpdb;
			$fb_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook'" );
			if($fb_count <= 5 ){
				if(isset($_POST['type']) && $_POST['type'] == "profile"){
					$res_fb = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_id = '".$_POST['fb_id']."'");
					
					if($res_fb){
						$update_data = array(
								'user_name' => $_POST['fb_name'],
								'social_id' => $_POST['fb_id'],
								'access_token' => $_POST['Access_Token']
							);
						$where = array( "ID" => $res_fb->ID );
						$wpdb->update($wpdb->prefix.'elevaweb_networks_authentication',$update_data,$where);
					}else{
						$update_data = array(
								'social_name' => 'Facebook',
								'user_id' => get_current_user_id(),
								'user_name' => $_POST['fb_name'],
								'social_id' => $_POST['fb_id'],
								'access_token' => $_POST['Access_Token'],
								'type' => 'profile',
								'status' => 1
							);
						$wpdb->insert($wpdb->prefix.'elevaweb_networks_authentication',$update_data);
					}
					$out = array("error"=>0,"msg"=>"Successfully Saved.");
				}
				if(isset($_POST['type']) && $_POST['type'] == "page"){
					$res_page = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_id = '".$_POST['page_id']."'");
					if($res_page){
						$update_data = array(
								'user_name' => $_POST['page_name'],
								'social_id' => $_POST['page_id'],
								'access_token' => $_POST['page_token']
							);
						$where = array( "ID" => $res_page->ID );
						$wpdb->update($wpdb->prefix.'elevaweb_networks_authentication',$update_data,$where);
					}else{
						$update_data = array(
								'social_name' => 'Facebook',
								'user_id' => get_current_user_id(),
								'user_name' => $_POST['page_name'],
								'social_id' => $_POST['page_id'],
								'access_token' => $_POST['page_token'],
								'type' => 'page',
								'status' => 1
							);
						$wpdb->insert($wpdb->prefix.'elevaweb_networks_authentication',$update_data);
					}
					$out = array("error"=>0,"msg"=>"Successfully Saved.");
				}
			}else{
				$out = array("error"=>1,"msg"=>"Maximum Limit Reached.");
			}
		}else{
			$out = array("error"=>1,"msg"=>"Something Went Wrong");
		}
	}else{
		$out = array("error"=>1,"msg"=>"Something Went Wrong");
	}
	echo json_encode( $out );
	exit;
}
add_action('init', function () {
    remove_action('wp_head', 'rel_canonical');
}, 15);
add_action( 'wp_ajax_reset_password_user', 'reset_password_user' );
function reset_password_user(){
	if(isset($_POST['email_id']) && $_POST['email_id'] != ""){
		
		$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
		$ch = curl_init();
		
		$remote_url = 'https://www.addanyproject.com/weleva/application/htdocs/api/forgot';
		$request = $_POST;
		$request['is_active'] = 1;
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_URL, $remote_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		$results = curl_exec($ch);
		$results = json_decode($results);
		curl_close($ch);
		
		if( $results->success == 1 ){
			echo json_encode(array("success"=>1,"message"=>__("Your password has been reset,Please check your email for your new temporary password.!","elevaweb")));
		}else{
			echo json_encode(array("success"=>1,"message"=>__("Email id is not registered.","elevaweb")));
		}
		exit;
	}
}