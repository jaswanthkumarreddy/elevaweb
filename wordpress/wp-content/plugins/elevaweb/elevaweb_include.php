<?php

function elevaweb_auth()
{
	//echo '<pre>'; print_r($_POST); die();
	$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
	$ch = curl_init();

	if($_POST['math']=='login'){$remote_url = 'https://www.addanyproject.com/weleva/application/htdocs/api/login';}
	if($_POST['math']=='reg'){$remote_url = 'https://www.addanyproject.com/weleva/application/htdocs/api/registration';}
	if($_POST['math']=='forgot'){$remote_url = 'https://www.addanyproject.com/weleva/application/htdocs/api/forgot';}
	if($_POST['math']=='change'){$remote_url = 'https://www.addanyproject.com/weleva/application/htdocs/api/changepassword';}
	$request = $_POST;
	unset($request['submit']);
	unset($request['math']);
	$request['is_active'] = 1;

	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	curl_setopt($ch, CURLOPT_URL, $remote_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	$results = curl_exec($ch);
	$results = json_decode($results);
	curl_close($ch);
	//debug_me($results,true);
	//debug_me($_POST,true);

	if($results->success==1 && $_POST['math']=='login' && $results->is_active == 1 )
	{
		if($results->data->changepassword == 1)
		{
			$_SESSION['elevaweb_login'] = 1;
			$_SESSION['elevaweb_userdata'] = $results->data;
			$_SESSION['customerId']= $results->data->customer_id;

			//debug_me($results,true);

			$url = admin_url().'admin.php?page=elevaweb';
			echo'<script> window.location="'.$url.'"; </script> ';
		}else{
			$url = admin_url().'admin.php?page=elevaweb';
			$_SESSION['elevaweb_login'] = 1;
			$_SESSION['elevaweb_userdata'] = $results->data;
			$_SESSION['customerId']= $results->data->customer_id;
		    echo'<script> window.location="'.$url.'"; </script> ';
		}
	}else if($_POST['math']=='login'){
		if($results->success == 1) {
			$_SESSION['elevaweb_login'] = 1;
			$_SESSION['elevaweb_userdata'] = $results->data;
			$_SESSION['customerId']= $results->data->customer_id;

			$url = admin_url().'admin.php?page=elevaweb';
			echo'<script> window.location="'.$url.'"; </script> ';
		}else{
			$url = admin_url().'admin.php?page=elevaweb&message='.urlencode($results->message);
			echo'<script> window.location="'.$url.'"; </script> ';
		}

	}
	if($results->success==1 && $_POST['math'] == 'reg')
	{
		$url = admin_url().'admin.php?page=elevaweb';
		$_SESSION['elevaweb_login'] = 1;
		$_SESSION['elevaweb_userdata'] = $results->data;
		echo'<script> window.location="'.$url.'"; </script> ';
	}else if($_POST['math']=='reg'){
		$url = admin_url().'admin.php?page=elevaweb-register&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script>';
	}
	if($_POST['math']=='forgot'){
		$url = admin_url().'admin.php?page=elevaweb-forgotpassword&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script> ';
	}
	if($results->success==1 && $_POST['math']=='change')
	{
		$url = admin_url().'admin.php?page=eleva-changepassword&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script> ';
	}elseif($_POST['math']=='change'){
		$url = admin_url().'admin.php?page=eleva-changepassword&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script> ';
	}
}

/* function elevaweb_logout() {
	if(isset($_SESSION['elevaweb_login']) && $_SESSION['elevaweb_login'] == 1) {
		unset($_SESSION['elevaweb_login']);
		wp_safe_redirect(admin_url().'admin.php?page=elevaweb');
	}
} */
function elevaweb_register()
{
global $pluginDir;
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>

<div class="create-account">
<div class="text-center"><?php _e('Start by registering an account to start using Elevaweb and automate your production of content. Just enter your full name, email, and password. "','elevaweb'); ?></div>
  <div class="form-box">

    <div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
    <form action="<?php echo admin_url().'admin.php?page=elevaweb';?>" method="post" name="Login_Form" class="form-sign">
      <?php if($_GET['message']){?>
      <div class="massage"><?php printf( __( '%s', 'elevaweb' ),$_GET['message']);?></div>
      <?php } ?>
      <div class="elevaweb-row">
        <input type="text" class="elevaweb-input" name="full_name" placeholder="<?php echo  __('Full Name','elevaweb') ?>" required="" />
      </div>
      <div class="elevaweb-row">
        <input type="email" class="elevaweb-input" name="email_id" placeholder="<?php echo  __('Email','elevaweb') ?>" required="" />
      </div>
      <div class="elevaweb-row">
        <input type="password" class="elevaweb-input" name="password" placeholder="<?php echo  __('Password','elevaweb') ?>" required=""/>
      </div>
      <div class="elevaweb-row">
        <div class="elevaweb-group">
          <button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php echo  __('Create Account','elevaweb') ?></button>
          <input type="hidden" name="math" value="reg"/>
		  <input type="hidden" name="website" value="<?php echo site_url();?>">
        </div>
      </div>
    </form>
  </div>
  <div class="form-box-footer"> <?php echo  __('Do you have an account?','elevaweb') ?> <a  style="width: 110px;" href="<?php echo admin_url().'admin.php?page=elevaweb';?>" class="elevaweb-link"><?php echo  __('click here','elevaweb') ?></a></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.birthdate').datepicker({
			dateFormat : 'yy-mm-dd'
		});
	});
</script>
<?php
}
function elevawebTool(){
	global $pluginDir;
	echo sideMenu();
	include($pluginDir."/templates/tools.php");
}

function elevawebProfile() {
	global $pluginDir;
	echo sideMenu();
	include($pluginDir."/templates/profile.php");
}

function elevawebPostconfig() {
	global $pluginDir;
	$post_cat = get_terms( 'category', array(
    						'hide_empty' => false,
							) );
	foreach($post_cat as $term)
	{
		$all_terms[$term->term_id]=$term->name;
		}
	unset($all_terms[1]);
	getApiFeed();
	echo sideMenu();
	include($pluginDir."/templates/myposts.php");
}

function elevawebLog() {
	global $pluginDir;
	echo sideMenu();
	include($pluginDir."/templates/log.php");
}

function elevawebNewPost() {
	global $pluginDir;
	$post_cat = get_terms( 'category', array(
    						'hide_empty' => false,
							) );
	foreach($post_cat as $term)
	{
		$all_terms[$term->term_id]=$term->name;
		}
	unset($all_terms[1]);
	getApiFeed();
	echo sideMenu();
	include($pluginDir."/templates/new-post.php");
}

function elevaweb_welcome()

{
	if(count($_POST)) {
		elevaweb_auth();
	}

	?>
<?php echo sideMenu(); ?>
<div class="eleva-wrap">
  <div class="col-md-12"><?php echo elevawebHeader("Dashboard"); ?>
    <div class="elevaweb-content">
      <div class="col-md-5">
        <div class="eleva-round-box">
		<div class="eleva-last-post-content">
		<p><?php _e('Welcome to Elevaweb, now you can auto post in your website.','elevaweb'); ?></p>
		<p><?php _e('To create your first Blog Post automation click on the pencil (right) and select which content do you want in your website.','elevaweb'); ?></p>
<p><?php _e('There we will explain you how to do that. If you prefer, can watch this
step by step video (link > <a href="https://goo.gl/CMSr5w)" target="_blank">https://goo.gl/CMSr5w)</a>.','elevaweb'); ?></p>
<?php _e('Thanks"','elevaweb'); ?>

		</div>
          <div class="eleva-last-post"><?php _e('Last Post','elevaweb'); ?></div>
          <div class="eleva-last-post-content">
			  <?php
				global $wpdb;
				$sql = "SELECT post_id,post_category FROM {$wpdb->prefix}elevaweb_scheduled_post_log ORDER BY ID DESC LIMIT 0,5";
				$results = $wpdb->get_results($sql);
				if($wpdb->num_rows > 0):
			  ?>
            <ul>
			  <?php foreach($results as $result):
					$postData = get_post($result->post_id);
					if(!$postData){
						continue;
					}
			  ?>
              <li>
                <div class="e-row">
                  <div class="e-post-title"><a href="<?php echo get_permalink($result->post_id); ?>"><?php echo $postData->post_title; ?></a></div>
                </div>
                <div class="e-row">
					<?php
						$pubDate = get_the_date('d-m-Y',$result->post_id);
						//$pubDate = date('d-m-Y',strtotime($pubDate));
					?>
                  <div class="e-post-date"><?php echo $pubDate; ?></div>
                  <?php
						$cat = '';
					  $id = $result->post_category;
						if(!empty($id)) {
							$cat = get_cat_name($id);
						}
						if(!empty($cat)):
                  ?>
                  <div class="e-post-cat"><?php echo $cat; ?></div>
                  <?php endif; ?>
                </div>
                <div class="e-row">
					<?php
						$content = $postData->post_content;
						$content = strip_tags($content);
						$content = substr($content,0,150);
					?>
                  <div class="e-post-content"><?php echo $content."..."; ?></div>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="eleva-round-box">
          <div class="e-row">
            <a href="<?php echo admin_url(); ?>admin.php?page=eleva-new-post"><div class="eleva-active"><img src="<?php echo plugins_url('/images/eleva-pencil.png', __FILE__);?>" title="Create a New auto Post" /></div></a>
            <?php /*<a href="<?php echo admin_url('admin.php?page=eleva-network'); ?>"><div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div></a>*/ ?>
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
          </div>
          <div class="e-row">
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
          </div>
          <div class="e-row">
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
          </div>
          <div class="e-row">
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
            <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __FILE__);?>"/></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
}
function elevawebForgotPassword(){
global $pluginDir;
wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>
<div class="forgot">
  <div class="form-box">
    <div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
    <form id="forgot_password" method="post" name="Login_Form" class="form-sign">
      <?php //if($_GET['message']){?>
      <div class="massage"></div>
      <?php //} ?>
      <div class="elevaweb-row"> <span class="elevaweb-label"><?php _e('Recover Password','elevaweb'); ?></span> </div>
      <div class="elevaweb-row">
        <input type="email" class="elevaweb-input" id="email_id" name="email_id" placeholder="<?php echo  __('Enter Email','elevaweb') ?>" required="" />
      </div>
      <div class="elevaweb-row">
        <div class="elevaweb-group">
          <button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php echo  __('Recover','elevaweb') ?></button>
          <input type="hidden" name="math" value="forgot"/>
        </div>
      </div>
    </form>
  </div>
  <div class="form-box-footer"> <?php echo  __('You will receive an email with new password','elevaweb') ?> <br />
    <?php echo  __('Remember your password? click here to ','elevaweb') ?> <a href="<?php echo admin_url().'admin.php?page=elevaweb';?>" class="elevaweb-link"><?php echo __('Login','elevaweb') ?></a> </div>
</div>
<?php
}
function elevawebChangePassword(){
global $pluginDir;
wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>
<div class="change">
  <div class="form-box">
    <div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
    <form action="<?php echo admin_url().'admin.php?page=elevaweb';?>" method="post" name="Login_Form" class="form-sign">
      <?php if($_GET['message']){?>
      <div class="massage"><?php printf( __( '%s', 'elevaweb' ),$_GET['message']);?></div>
      <?php } ?>
      <div class="elevaweb-row"> <span class="elevaweb-label"><?php _e('Change Password','elevaweb'); ?></span> </div>
      <div class="elevaweb-row">
        <input type="text" class="elevaweb-input" name="password" placeholder="<?php echo  __('Old Password','elevaweb') ?>" required="" />
      </div>
      <div class="elevaweb-row">
        <input type="text" class="elevaweb-input" name="new_password" placeholder="<?php echo  __('New Password','elevaweb') ?>" required="" />
      </div>
      <div class="elevaweb-row">
        <div class="elevaweb-group">
          <button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php echo  __('Change Password','elevaweb') ?></button>
          <input type="hidden" name="math" value="change"/>
          <input type="hidden" name="customerId" value="<?php echo $_SESSION['customerId']; ?>"/>
        </div>
      </div>
    </form>
  </div>
</div>
<?php
}
function sideMenu()
{
	global $pluginDir;
	include $pluginDir."templates/loading.php";
	wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
	wp_enqueue_script( 'elevaweb-autosave', plugins_url('js/sisyphus.js', __FILE__));

	?>
<!--<div class="elevaweb-side-menu">
  <div id="adminmenu">
    <ul class="side-nav" >
      <li>
        <div class="eleva-menu-image dashicons-before eleva-dashbord" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 0%;"></div>
        <div class="eleva-menu-name"><a href="<?php echo admin_url().'admin.php?page=welcome';?>" aria-haspopup="true">Dashboard</a></div>
      </li>
      <li>
        <div class="eleva-menu-image dashicons-before eleva-new-post" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 17%;"></div>
        <div class="eleva-menu-name"><a href="<?php echo admin_url().'admin.php?page=eleva-new-post';?>" aria-haspopup="true">New Post</a></div>
      </li>
      <li>
        <div class="eleva-menu-image dashicons-before eleva-post-config" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 35%;"></div>
        <div class="eleva-menu-name"><a href="<?php echo admin_url().'admin.php?page=eleva-post-config';?>" aria-haspopup="true">My Post  Configuration</a></div>
      </li>
      <li>
        <div class="eleva-menu-image dashicons-before eleva-tools" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 50%;"></div>
        <div class="wp-menu-name"><a href="<?php echo admin_url().'admin.php?page=eleva-tools';?>" aria-haspopup="true">Tools</a></div>
      </li>
      <li>
        <div class="wp-menu-image dashicons-before eleva-profile" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 67%;"></div>
        <div class="eleva-menu-name"><a href="<?php echo admin_url().'admin.php?page=eleva-profile';?>" aria-haspopup="true">Profile</a></div>
      </li>
      <li>
        <div class="wp-menu-image dashicons-before eleva-log" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 83%;"></div>
        <div class="eleva-menu-name"><a href="<?php echo admin_url().'admin.php?page=eleva-log';?>" aria-haspopup="true">Log</a></div>
      </li>
      <li>
        <div class="eleva-menu-image dashicons-before eleva-logout" style="background:url(<?php echo plugins_url('/images/elevaweb-icon.png', __FILE__); ?>) no-repeat center 99%;"></div>
        <div class="eleva-menu-name"><a href="<?php echo admin_url().'admin.php?page=eleva-logout';?>" aria-haspopup="true">Logout</a></div>
      </li>
    </ul>
  </div>
</div>-->
<?php
}
function elevawebHeader($heading=''){
?>
<div class="elevaweb-header">
  <div class="col-md-7"><span class="eleva-heading"><?php echo $heading; ?></span></div>
</div>
<?php
}
function getElevaWebFeed($feed_url) {
	$request='';
	$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
	$ch = curl_init();

	//curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	curl_setopt($ch, CURLOPT_URL, $feed_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

	$results = curl_exec($ch);

	curl_close($ch);

	var_dump($results);

    /*$x = new SimpleXmlElement($results);

    $feedData = array();

    foreach($x->channel->item as $entry) {
        $feedData[] = $entry;
    }

    return $feedData;*/
}

function getApiFeed() {

	$request='';
	$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	curl_setopt($ch, CURLOPT_URL, 'https://www.addanyproject.com/weleva/application/htdocs/api/getFeedList');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

	$results = curl_exec($ch);
	$results = json_decode($results);

	//debug_me($results,true);
	if($results->data){
		foreach($results->data as $feed){
		//debug_me($feed,true);
			$feeds[] = $feed->feed_name;
			$feed_web[$feed->feed_name] = $feed->website_url;
			foreach($feed->feeds_path_category as $feed_extra){
				$feed_path[$feed->feed_name][]=$feed_extra->feed_path;
				$feed_url[$feed->feed_name][]= $feed->website_url.$feed_extra->feed_path;
				$feed_cat[$feed->feed_name][]=$feed_extra->feed_category;
			}

			$feed_rule[$feed->feed_name]['rule1_type']=$feed->rule1_type;
			$feed_rule[$feed->feed_name]['rule1_type_value']=$feed->rule1_type_value;
			$feed_rule[$feed->feed_name]['rule1_is_single']=$feed->rule1_is_single;
			$feed_rule[$feed->feed_name]['rule1_is_inner']=$feed->rule1_is_inner;
			$feed_rule[$feed->feed_name]['rule2_type']=$feed->rule2_type;
			$feed_rule[$feed->feed_name]['rule2_type_value']=$feed->rule2_type_value;
			$feed_rule[$feed->feed_name]['rule2_is_single']=$feed->rule2_is_single;
			$feed_rule[$feed->feed_name]['rule2_is_inner']=$feed->rule2_is_inner;
			$feed_rule[$feed->feed_name]['feed_template']=htmlentities($feed->feed_template);
			$feed_rule[$feed->feed_name]['is_strip_parts']=$feed->is_strip_parts;
			$feed_rule[$feed->feed_name]['strip1_type']=$feed->strip1_type;
			$feed_rule[$feed->feed_name]['strip1_value']=$feed->strip1_value;
			$feed_rule[$feed->feed_name]['strip2_type']=$feed->strip2_type;
			$feed_rule[$feed->feed_name]['strip2_value']=$feed->strip2_value;
		}
		$_SESSION['feeds']=$feeds;
		$_SESSION['feed_rule']=$feed_rule;
		$_SESSION['feed_web']=$feed_web;
		$_SESSION['feed_path']=$feed_path;
		$_SESSION['feed_url'] = $feed_url;
		$_SESSION['feed_cat']=$feed_cat;
		curl_close($ch);
	}
}

function getFeedcatajax()
{
	$data.='<option value="">'.__('--Select Category--','elevaweb').'</option>';
	foreach($_SESSION['feed_cat'][$_POST['feed']] as $key => $cats)
	{
		$data.='<option value="'.$cats.'|'.$key.'">'.$cats.'</option>';
	}

		echo $data;
		die();

	}
add_action( 'wp_ajax_getFeedcatajax', 'getFeedcatajax' );

add_action( 'wp_ajax_nopriv_getFeedcatajax', 'getFeedcatajax' );

add_action('wp_ajax_elevaweb_save_feed','elevawebSaveFeed');

function elevawebSaveFeed() {

	if ( !wp_verify_nonce( $_POST['_wpnonce'], 'elevaweb_save_feed' ) ) {
		$out = array("Error"=>1,"Msg"=>__("Something Went Wrong","elevaweb"));
		echo json_encode( $out );
		exit;
	}
	global $wpdb;
        $feedId = 0;
        if(isset($_REQUEST['feed_id'])) {
            $feedId = $_REQUEST['feed_id'];
        }
	$src_feed = $_REQUEST['src_feed'];
	$feed_cat = $_REQUEST['src_cat'];
	$source_feed_url = $_SESSION['feed_url'];
	$feed_cat_id = 0;
	if(!empty($feed_cat)) {
		$feed_cat = explode('|',$feed_cat);
		$feed_cat_id = $feed_cat[1];
		$feed_cat = $feed_cat[0];
	}
	$post_category = '';
	$post_tags = '';
	if(isset($_REQUEST['blog_cat'])) {
		$post_category = $_REQUEST['blog_cat'];
	}
	if(isset($_REQUEST['eleva_tags'])) {
		$post_tags = $_REQUEST['eleva_tags'];
		$post_tags = explode(',',$post_tags);
	}
	$feed_url = $_SESSION['feed_url'][$src_feed][$feed_cat_id];
	$feed_rule = $_SESSION['feed_rule'][$src_feed];

	// Rule 1
	$getContentType = $_SESSION['feed_rule'][$src_feed]['rule1_type'];
	$contentClass = $_SESSION['feed_rule'][$src_feed]['rule1_type_value'];
	$contentIsSingle = $_SESSION['feed_rule'][$src_feed]['rule1_is_single'];
	$contentIsInner = $_SESSION['feed_rule'][$src_feed]['rule1_is_inner'];

	// Rule 2
	$getContentType2 = $_SESSION['feed_rule'][$src_feed]['rule2_type'];
	$contentClass2 = $_SESSION['feed_rule'][$src_feed]['rule2_type_value'];
	$contentIsSingle2 = $_SESSION['feed_rule'][$src_feed]['rule2_is_single'];
	$contentIsInner2 = $_SESSION['feed_rule'][$src_feed]['rule2_is_inner'];

	//$feed_url = str_replace('https','http',$feed_url);
	$feedData = getElevaFeed($feed_url);
	if(!$feedData){
		$out = array("Error"=>1,"Msg"=>__("Your Domain has been forbidden.","elevaweb"));
		echo json_encode( $out );
		exit;
	}
	if(is_array($feedData)) {
		$scheduledTime = '';
		if(isset($_REQUEST['schedule_hour']) && isset($_REQUEST['schedule_minutes'])) {
			if($_REQUEST['schedule_hour'] != "-1" && $_REQUEST['schedule_minutes'] != "-1") {
				$scheduledTime = $_REQUEST['schedule_hour'].":".$_REQUEST['schedule_minutes'];
			}
		}
		$scheduledDate = $_REQUEST['days'];
		if(!empty($scheduledDate)) {
			$scheduledDateNew = array();
			if(!empty($scheduledTime)) {
				foreach($scheduledDate as $date) {
					$scheduledDateNew[] = $date." ".$scheduledTime;
				}
			}
			if(count($scheduledDateNew) > 0) {
				$scheduledDate = $scheduledDateNew;
			}
			$scheduledDate = serialize($scheduledDate);
		}
		else {
			$date = date('Y-m-d');
			$scheduledDate = array($date);
			$scheduledDateNew = array();
			if(!empty($scheduledTime)) {
				foreach($scheduledDate as $date) {
					$scheduledDateNew[] = $date." ".$scheduledTime;
				}
			}
			if(count($scheduledDateNew) > 0) {
				$scheduledDate = $scheduledDateNew;
			}
			$scheduledDate = serialize($scheduledDate);
		}

		$positivekeyword = '';
		$negativekeyword = '';
		if(isset($_REQUEST['eleva_pos_word'])) {
			$positivekeyword = $_REQUEST['eleva_pos_word'];
			if(!empty($positivekeyword)) {
				if(strpos($positivekeyword,',') !== false) {
					$positivekeyword = explode(',',$positivekeyword);
				}
				else {
					$positivekeyword = array($positivekeyword);
				}
			}
		}
		if(isset($_REQUEST['eleva_neg_word'])) {
			$negativekeyword = $_REQUEST['eleva_neg_word'];
			if(!empty($negativekeyword)) {
				if(strpos($negativekeyword,',') !== false) {
					$negativekeyword = explode(',',$negativekeyword);
				}
				else {
					$negativekeyword = array($negativekeyword);
				}
			}
		}
		$postTitle = '';
		$postContent = '';
		$skipFeed = false;
		$skipFeedImg = false;
		$elevaTitle = '';
		$postScheduleCounter = 0;
		$postNotScheduleCounter = 0;
		if(isset($_REQUEST['eleva_title'])) {
			$elevaTitle = $_REQUEST['eleva_title'];
			/* if(strpos($elevaTitle,'[title_post]') !== false) {
				$elevaTitle = explode('[title_post]',$elevaTitle);
				//$elevaTitle = array_filter($elevaTitle);
				if(empty($elevaTitle[0]) && empty($elevaTitle[1])) {
					$elevaTitle = '';
				}
			} */
		}

		$skipWithoutImages = '';
		$removePostImages = '';
		if(isset($_REQUEST['image_condition']) && $_REQUEST['image_condition'] == "skip_without_images") {
			$skipWithoutImages = true;
		}
		if(isset($_REQUEST['image_condition']) && $_REQUEST['image_condition'] == "remove_image") {
			$removePostImages = true;
		}

		$addImages = '';
		$imagesUrls = '';
		if(isset($_REQUEST['image_condition']) && $_REQUEST['image_condition'] == "add_image") {
				$addImages = $_REQUEST['add_images'];
				if(isset($_REQUEST['eleva_image_url'])) {
					$imagesUrls = $_REQUEST['eleva_image_url'];
					if(!empty($imagesUrls)) {
						$imagesUrls = explode(PHP_EOL,$imagesUrls);
						if(!is_array($imagesUrls)) {
							$imagesUrls = explode(',',$imagesUrls);
						}
						else {
							$imagesUrls = $imagesUrls;
						}
					}
				}
		}

		$postTags = implode(',',$post_tags);

		$metaFields = array('positive_word' => $positivekeyword, 'negative_keyword' => $negativekeyword, 'skip_without_image' => $skipWithoutImages, 'remove_images' => $removePostImages, 'change_title' => $_REQUEST['eleva_title'], 'tags' => $postTags);

		if(count($imagesUrls) && !empty($imagesUrls) && $imagesUrls && !is_null($imagesUrls) > 0) {
				$metaFields['add_images'] = 1;
				$metaFields['images_url'] = serialize($imagesUrls);
		}

		$metaFields = serialize($metaFields);

		if($feedData) {
			foreach($feedData as $feed) {
				//debug_me($feed,true);
				if((isset($_POST['save_type']) && $_POST['save_type'] == "save_schedule" ) || $feedId){

					$post_id = 0;

					$prefix = $wpdb->prefix;

					$feed_rule_ser = serialize($feed_rule);

					if(!$feedId){

						$sql = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post(feed,feed_url,feed_rule,feed_meta,feed_category,post_category,scheduled_date,status,running) VALUES('{$src_feed}','{$feed_url}','{$feed_rule_ser}','{$metaFields}','{$feed_cat}','{$post_category}','{$scheduledDate}',1,1);";

						$insert = $wpdb->query($sql);

						if($wpdb->rows_affected == 1) {
							$postScheduleCounter++;
							$_SESSION['elevaweb_message'] = "Your post has been scheduled. You can check it in myposts";
							/* if ( wp_get_referer() )
							{
									wp_safe_redirect( wp_get_referer() );
							}
							die; */
							$out = array("Error"=>0,"Msg"=>__("Your post has been scheduled. You can check it in myposts","elevaweb"));
							echo json_encode( $out );
							exit;
						}else{
							continue;
							$postNotScheduleCounter++;
							$_SESSION['elevaweb_message'] = "Error while processing your request. Please try again.";
							$out = array("Error"=>1,"Msg"=>__("Error while processing your request. Please try again.","elevaweb"));
							echo json_encode( $out );
							exit;
						}
					}else{
						$sql = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET feed = '{$src_feed}',feed_url = '{$feed_url}',feed_rule = '{$feed_rule_ser}',feed_meta = '{$metaFields}',feed_category = '{$feed_cat}',post_category = '{$post_category}',scheduled_date = '{$scheduledDate}' WHERE ID = {$feedId}";

						$update = $wpdb->query($sql);

						if($wpdb->rows_affected == 1){
							$_SESSION['elevaweb_message'] = "Your post has been scheduled. You can check it in myposts";
							/* if ( wp_get_referer() )
							{
									wp_safe_redirect( wp_get_referer() );
							}
							die; */
							$out = array("Error"=>0,"Msg"=>__("Your post has been scheduled. You can check it in myposts","elevaweb"));
							echo json_encode( $out );
							exit;
						}else{
							$_SESSION['elevaweb_message'] = "Error while processing your request. Please try again.";
							/* if ( wp_get_referer() )
							{
									wp_safe_redirect( wp_get_referer() );
							}
							die; */
							$out = array("Error"=>1,"Msg"=>__("Error while processing your request. Please try again.","elevaweb"));
							echo json_encode( $out );
							exit;
						}
					}
				}else if((isset($_POST['save_type']) && $_POST['save_type'] == "save_schedule_published" )) {

					$feed_rule_ser = serialize($feed_rule);

					$linkContent = '';
					$postContent = '';
					$linkData = '';
					$postFound = 0;
					$linkData = wp_remote_request($feed->link);
					if ( is_wp_error( $linkData ) ) {
						$error_message = $linkData->get_error_message();
						//wp_mail( "vaidfaiyaz@gmail.com", "Elevaweb Cron Error", $error_message );
						wp_die($error_message." at line number 170");
					}

					$linkContent = wp_remote_retrieve_body($linkData);

					$linkContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $linkContent);
					//$linkContent = preg_replace('#<a (.*?)>(.*?)</a>#is', '', $linkContent);

					//echo htmlentities($linkContent);
					$skipFeed = false;
					$skipFeedImg = false;
					$dom = new DOMDocument();
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput       = true;
					@$dom->loadHTML(mb_convert_encoding($linkContent, 'HTML-ENTITIES', 'UTF-8'));

					$xpath = new DOMXPath($dom);
					$postTitle = $feed->title;
					$pTitle = $feed->title;
					if($getContentType == "0") {
						$tags = $dom->getElementById($contentClass);
						if($tags) {
							foreach ($tags as $tag) {
								$postContent .= get_inner_html($tag);
								if($contentIsSingle) {
									break;
								}
							}
						}
					}
					else if($getContentType == "1") {
						$classname = $contentClass;
						$tags = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
						foreach ($tags as $tag) {
							$postContent .= get_inner_html($tag);
							if($contentIsSingle) {
								break;
							}
						}
					}
					else if($getContentType == "2") {
						$tags = $xpath->query($contentClass);
						foreach ($tags as $tag) {
							$postContent .= get_inner_html($tag);
							if($contentIsSingle) {
								break;
							}
						}
					}

					if(!empty($contentClass2) && !empty($getContentType2)) {
						if($getContentType2 == "0") {
							$tags = $dom->getElementById($contentClass2);
							if($tags) {
								foreach ($tags as $tag) {
									$postContent .= get_inner_html($tag);
									if($contentIsSingle) {
										break;
									}
								}
							}
						}
						else if($getContentType2 == "1") {
							$classname = $contentClass2;
							$tags = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
							foreach ($tags as $tag) {
								$postContent .= get_inner_html($tag);
								if($contentIsSingle) {
									break;
								}
							}
						}
						else if($getContentType2 == "2") {
							$tags = $xpath->query($contentClass2);
							foreach ($tags as $tag) {
								$postContent .= get_inner_html($tag);
								if($contentIsSingle) {
									break;
								}
							}
						}
					}

					if(!empty($elevaTitle)) {
						$postTitle = $elevaTitle.$postTitle;
						/* if(isset($elevaTitle[1])) {
							$postTitle = $postTitle.$elevaTitle[1];
						} */
					}
					$skipFeed = false;
					if(!empty($positivekeyword)) {
						if(positivekeyword($postTitle,$positivekeyword) || positivekeyword($postContent,$positivekeyword)) {
							$skipFeed = false;
						}else{
							$skipFeed = true;
						}
					}

					if(!empty($negativekeyword)) {
						if(negativekeyword($postTitle,$negativekeyword) || negativekeyword($postContent,$negativekeyword)) {
							$skipFeed = true;
						}else {
							$skipFeed = false;
						}
					}

					if(!empty($skipWithoutImages) && $skipWithoutImages) {
						$res = elevawebFindImages($postContent);
						if($res == 0 || $res == false) {
							$skipFeedImg = true;
						}
					}
					else {
						$skipFeedImg = false;
					}
					if($removePostImages && !empty($removePostImages)) {
						$postContentWithoutImages = removeImages($postContent);
						if($postContentWithoutImages) {
							$postContent = $postContentWithoutImages;
						}
					}

					//var_dump($postContent);

					$postContentWithLocalImg = uploadImagesToWp($postContent);

					if($postContentWithLocalImg && !is_null($postContentWithLocalImg)) {
						$postContent = $postContentWithLocalImg;
					}

					if(count($imagesUrls)) {
						$postContent = replaceUploadImagesToWp($postContent,$imagesUrls);
					}

					if($feed_rule['is_strip_parts'] == "1") {
						$feed_strip1_type = $feed_rule['strip1_type'];
						$feed_strip1_value = $feed_rule['strip1_value'];
						$feed_strip2_type = $feed_rule['strip2_type'];
						$feed_strip2_value = $feed_rule['strip2_value'];
						$strippedContent1 = stripContent($postContent,$feed_strip1_type,$feed_strip1_value);
						$strippedContent2 = stripContent($postContent,$feed_strip2_type,$feed_strip2_value);
						$newContent = '';
						if($strippedContent1 !== false) {
							$newContent = $strippedContent1;
						}
						if($strippedContent2 !== false) {
							$newContent .= $strippedContent2;
						}

						if(!empty($newContent)) {
							$postContent = $newContent;
						}
					}

					$postContent = strip_tags($postContent,'<b><p><i><img><ul><li><h1><h2><h3><h4><h5><h6>');
					if(strlen($postContent) > 2000) {
						$postContent = elevaweb_truncate($postContent,2000,array('html'=>true));
					}

					//$postContent .= preg_replace('#<a (.*?)>(.*?)</a>#is', '', $postContent);
					//$postContent .= elevaweb_add_image_to_post( $imagesUrls );


					//wp_mail( "vaidfaiyaz@gmail.com", "Elevaweb Cron Content", $postContent );

					//&& $skipFeedImg == false && $skipFeed == false

					if(!empty($postTitle) && !empty($postContent) && $skipFeedImg === false && $skipFeed === false && strlen($postContent) > 2000) {



						if(is_object($postTitle)) {
							$key = 0;
							$postTitle = (array) $postTitle;
							$postTitle = $postTitle[0];
						}

						$new_title = utf8_decode( $pTitle );
						$post_id = 0;


						$post = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_name = '".sanitize_title( $new_title )."' AND post_type = 'post'" );

						if(!$post){


							if(!empty($imagesUrls)) {
								if(is_array($imagesUrls)){
									//Elevaweb_Set_Featured_Image(urldecode( $imagesUrls[0] ), $post_id);
									//$i = elevaweb_add_image_to_post( array( urldecode(utf8_decode( $imagesUrls[0] ) ) ) );
									//$postContent = $i.$postContent;
								}
							}else{
								if($removePostImages != true){
									$ele_feed = $_REQUEST['src_feed'];
									if(strtolower( $ele_feed ) == "endeavor" || strripos( $ele_feed, "Marketing Digital") != ""){
										if(strripos( $ele_feed, "Marketing Digital") != ""){
											//$postContent = stripContent($postContent,"1","mkdf-post-info");
											if(!empty($feed->link)){
												$fea_image = elevaweb_get_og_image_from_url( $feed->link[0] );
												if( $fea_image ){
													$ij = elevaweb_add_image_to_post( array( utf8_decode( $fea_image ) ) );
													$postContent = $ij.$postContent;
												}
											}
										}else{
											if(!empty($feed->link)){
												$fea_image = elevaweb_get_og_image_from_url( $feed->link );

												if( $fea_image ){
													$ij = elevaweb_add_image_to_post( array( utf8_decode( $fea_image ) ) );
													$postContent = $ij.$postContent;
												}
											}
										}
									}
								}
							}
							$postContent .= "<p><a href='".$feed->link."' rel='nofollow'>".__('Click here to view full post','elevaweb')."</a></p>";

							$args = array(
								'post_author' => 1,
								'post_title' => $postTitle,
								'post_content' => $postContent,
								'post_name' => sanitize_title( $new_title ),
								'post_status' => 'publish',
								'post_type' => 'post'
							);

							$post_id = wp_insert_post($args);

							if($post_id){

								update_post_meta($post_id,'custom_canonical_url',"$feed->link");

								//Matching Endeavor feed for get og:image and set as featured image in post
								/* if(!empty($imagesUrls)) {
									if(is_array($imagesUrls)){
										Elevaweb_Set_Featured_Image(urldecode( $imagesUrls[0] ), $post_id);
									}
								}else{
									if($removePostImages != true){
										$ele_feed = $_REQUEST['src_feed'];
										if(strtolower( $ele_feed ) == "endeavor"){
											if(!empty($feed->link)){
												$fea_image = elevaweb_get_og_image_from_url( $feed->link );
												if( $fea_image ){
													Elevaweb_Set_Featured_Image(urldecode(utf8_decode( $fea_image )), $post_id);
												}
											}
										}
									}
								} */
								//Facebook Share Code Starts
								$results_fb = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook' AND status = 1");
								if($results_fb){
									foreach($results_fb as $result_fb){
										if(isset($_POST['fb_'.$result_fb->ID]) && $_POST['fb_'.$result_fb->ID] == $result_fb->ID){
											update_post_meta($post_id,'elevaweb_facebook_'.$result_fb->ID,1);
											if($result_fb->access_token != ""){
												elevaweb_share_facebook($result_fb->application_id,$result_fb->application_secret,$result_fb->access_token,$post_id,$result_fb->type,$result_fb->social_id);
											}
										}else{
											update_post_meta($post_id,'elevaweb_facebook_'.$result_fb->ID,0);
										}
									}
								}

								$results_insta = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Instagram' AND status = 1");
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
							}
							//Facebook Share Code Ends

							if(!empty($post_category)) {
								wp_set_post_categories($post_id,array($post_category));
							}
							if(!empty($post_tags)) {
								if(is_array($post_tags)) {
									wp_set_post_tags($post_id,$post_tags);
								}
								else {
									wp_set_post_tags($post_id,array($post_tags));
								}
							}

							$published_date = get_the_date('Y-m-d H:i:s',$post_id);

							// LOG the post
							$SQL = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post_log(feed,feed_url,post_title,post_id,original_post_url,feed_category,post_category,published_date) VALUES('{$src_feed}','{$feed_url}','{$postTitle}','{$post_id}','{$feed->link}','{$feed_cat}','{$post_category}','{$published_date}')";
							$wpdb->query($SQL);

							$sql = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post(feed,feed_url,feed_rule,feed_meta,feed_category,post_category,scheduled_date,status,running) VALUES('{$src_feed}','{$feed_url}','{$feed_rule_ser}','{$metaFields}','{$feed_cat}','{$post_category}','{$scheduledDate}',1,1);";

							$insert = $wpdb->query($sql);

							if($insert) {
								$_SESSION['elevaweb_message'] = "Post is saved successfully. <a href='".get_permalink($post_id)."'>Click here to view the post.</a>";
								/* if ( wp_get_referer() )
								{
									wp_safe_redirect( wp_get_referer() );
								}
								die; */
								$out = array("Error"=>0,"Msg"=>__("Post is saved successfully. <a href='".get_permalink($post_id)."'>Click here to view the post.</a>","elevaweb"));
								echo json_encode( $out );
								exit;
							}
							else {
								continue;
							}
							$postFound++;
							die;
						}
						else {
							continue;
						}
					}
				}
			}
			if($postFound == 0) {
				/* $_SESSION['elevaweb_message'] = "Sorry! No post found.";
				$_SESSION['elevaweb_error'] = true;
				if ( wp_get_referer() )
				{
					wp_safe_redirect( wp_get_referer() );
				}
				die; */
				$out = array("Error"=>1,"Msg"=>__("Sorry! No post found.","elevaweb"));
				echo json_encode( $out );
				exit;
			}
		}
	}
	else {
		$_SESSION['elevaweb_message'] = __("Sorry! No post found.","elevaweb");
		$_SESSION['elevaweb_error'] = true;
		if ( wp_get_referer() )
		{
			wp_safe_redirect( wp_get_referer() );
		}
		die;
	}
}

function pauseMyPost( $id = 0 ) {
	global $wpdb;
	if($id) {
		$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
		$result = $wpdb->get_results($SQL);
		if($wpdb->num_rows > 0) {
			$SQL = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET running=0 WHERE ID={$id}";
			$update = $wpdb->query($SQL);
			if($update) {
				$_SESSION['elevaweb_message'] = "1 feed has been paused.";
			}
			else {
				$_SESSION['elevaweb_message'] = "Failed to update the feed.";
			}
		}
		else {
			$_SESSION['elevaweb_message'] = "Failed to update the feed.";
		}
		wp_safe_redirect(admin_url().'admin.php?page=eleva-post-config');
		exit;
	}
}

function resumeMyPost( $id = 0 ) {
	global $wpdb;
	if($id) {
		$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
		$result = $wpdb->get_results($SQL);
		if($wpdb->num_rows > 0) {
			$SQL = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET running=1 WHERE ID={$id}";
			$update = $wpdb->query($SQL);
			if($update) {
				$_SESSION['elevaweb_message'] = "1 feed has been resumed.";
			}
			else {
				$_SESSION['elevaweb_message'] = "Failed to update the feed.";
			}
		}
		else {
			$_SESSION['elevaweb_message'] = "Failed to update the feed.";
		}
		wp_safe_redirect(admin_url().'admin.php?page=eleva-post-config');
		exit;
	}
}

function deleteMyPost( $id = 0 ) {
	global $wpdb;
	if($id) {
		$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
		$result = $wpdb->get_results($SQL);
		if($wpdb->num_rows > 0) {
			$SQL = "DELETE FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
			$update = $wpdb->query($SQL);
			if($update) {
				$_SESSION['elevaweb_message'] = "1 feed has been deleted.";
			}
			else {
				$_SESSION['elevaweb_message'] = "Failed to delete the feed.";
			}
		}
		else {
			$_SESSION['elevaweb_message'] = "Failed to delete the feed.";
		}
		wp_safe_redirect(admin_url().'admin.php?page=eleva-post-config');
		exit;
	}
}

function getSavedFeedData( $id = 0 ) {
	global $wpdb;
	if($id) {
		$sql = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID = {$id}";
		$results = $wpdb->get_row($sql);
		if($wpdb->num_rows) {
			return $results;
		}
	}
}

function getCurrentDaySchedule( $dateArray = '', $day = '' ) {
	if(!empty($dateArray) && !empty($day)) {
		foreach($dateArray as $date) {
			$currentDay = strtolower(date('l',strtotime($date)));
			if($currentDay == $day) {
				return date('Y-m-d',strtotime($date));
			}
			else {
				continue;
			}
		}
	}
	return false;
}

function getScheduledTime($scheduledDate = '') {
	if($scheduledDate) {
		foreach($scheduledDate as $date) {
			$time = explode(' ',$date);
			if(isset($time[1])) {
				return $time[1];
			}
		}
	}
	return false;
}

add_action('admin_post_edit_profile','editProfile');

function editProfile() {
	$name = $_REQUEST['full_name'];
	$email = $_REQUEST['email'];
	$phone = $_REQUEST['phone'];
	if(isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture'])){
		$picture = base64_encode( file_get_contents($_FILES['profile_picture']['tmp_name']) );
	}else{
		$picture = '';
	}

	$website = site_url();
	$is_active = 1;
	$payment_status = 1;
	$id = $_REQUEST['id'];
	$args = array (
		'body' => array('full_name' => $name, 'email_id' => $email, 'mobile_no' => $phone, 'website' => $website, 'is_active' => $is_active, 'payment_status' => $payment_status, 'id' => $id, 'profile_picture' => $picture)
	);
	$response = wp_remote_post('https://www.addanyproject.com/weleva/application/htdocs/api/editRegistration',$args);
	if ( is_wp_error( $response ) ) {
	   $error_message = $response->get_error_message();
	   echo "Something went wrong: $error_message";
	} else {
	   $body = wp_remote_retrieve_body($response);
	   if($body) {
		   $resp = json_decode($body);
		   $_SESSION['elevaweb_message'] = $resp->message;
		   if(isset($resp->success)) {
			   if($resp->success == "1") {
					/* $data = new stdClass;
					$data->full_name = $name;
					$data->customer_id = $id;
					$data->email_id = $email;
					$data->mobile_no = $phone; */

					$_SESSION['elevaweb_userdata'] = $resp->data;
			   }
		   }
			if ( wp_get_referer() ){
				wp_safe_redirect( wp_get_referer() );
			}
			die;
	   }
	}
}

?>
