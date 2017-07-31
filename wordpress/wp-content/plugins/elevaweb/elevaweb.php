<?php
session_start();
header('Access-Control-Allow-Origin: *');
/**
 * Plugin Name: Elevaweb
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 *Text Domain: elevaweb
 * Description: Plugin for Elevaweb Auto Post.
 * Version: The Plugin's Version Number, e.g.: 1.0
 * Author: Elevaweb
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: A "Slug" license name e.g. GPL2
 */
 /*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// create custom plugin settings menu

global $pluginDir,$pluginUrl;


$pluginDir = plugin_dir_path(__FILE__);
$pluginUrl = plugin_dir_url(__FILE__);
define('FB_AUTH','https://www.addanyproject.com/weleva/wordpress/FB_API/fb_auth.php');
define('FB_DATA','https://www.addanyproject.com/weleva/wordpress/FB_API/fb_data.php');

function my_cron_schedules($schedules){
    if(!isset($schedules["5min"])){
        $schedules["1min"] = array(
            'interval' => 1*60,
            'display' => __('Once every 5 minutes','elevaweb'));
    }
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes','elevaweb'));
    }
    return $schedules;
}
add_filter('cron_schedules','my_cron_schedules');

function elevaweb_truncate($text, $length = 100, $options = array()) {
    $default = array(
        'ending' => '...', 'exact' => true, 'html' => false
    );
    $options = array_merge($default, $options);
    extract($options);

    if ($html) {
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, ' ');
        if (isset($spacepos)) {
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
    $truncate .= $ending;

    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }

    return $truncate;
}
function elevaweb_get_og_image_from_url( $url ){
	if(empty( $url )){
		return false;
	}
	require_once($pluginDir.'classes/metaData.class.php');
	$metaData = MetaData::fetch( $url );
	try{
		if(is_array( $metaData->tags()['og:image'] )){
			return $metaData->tags()['og:image'][0];
		}else{
			return $metaData->tags()['og:image'];
		}
	}catch(Exception $e) {
		return false;
	}

}
function Elevaweb_Set_Featured_Image( $image, $post_id  ){

    require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');


	// magic sideload image returns an HTML image, not an ID
	$media = media_sideload_image($image, $post_id);

	// therefore we must find it so we can set it as featured ID
	if(!empty($media) && !is_wp_error($media)){
		$args = array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_status' => 'any',
			'post_parent' => $post_id
		);

		// reference new image to set as featured
		$attachments = get_posts($args);

		if(isset($attachments) && is_array($attachments)){
			foreach($attachments as $attachment){
				// grab source of full size images (so no 300x150 nonsense in path)
				$image = wp_get_attachment_image_src($attachment->ID, 'full');
				// determine if in the $media image we created, the string of the URL exists
				if(strpos($media, $image[0]) !== false){
					// if so, we found our image. set it as thumbnail
					set_post_thumbnail($post_id, $attachment->ID);
					// only want one image
					break;
				}
			}
		}
	}
}
function remove_footer_admin () {
	$time = current_time('timestamp');
	$time = date('H:i:s',$time);
	echo 'Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | '.$time.'</p>';
}

add_filter('admin_footer_text', 'remove_footer_admin');

add_action('elevaweb_cron_event', 'do_this_every_minute');

function do_this_every_minute() {
	global $wpdb;
	global $pluginDir;
	$myfile = fopen($pluginDir."logs/cronlog.log", "a+");
	$today = date('Y-m-d');
	$dayName = date('l');
	$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE scheduled_date LIKE '%{$today}%' AND running=1 ORDER BY ID DESC";
	$results = $wpdb->get_results($SQL);
	$data = print_r($results,true);
	$matchedTime = true;
	$noPostFound = false;
	$current_time = current_time('timestamp');
	$logdata = date('d-m-Y H:i',$current_time);
	$logdata .= "\n==================================\n";
	$logdata .= "Elevaweb Cron Started";
	$logdata .= "\n==================================\n";
	fwrite($myfile,$logdata);
	//debug_me($results,true);
	if($results && count($results)) {
		foreach($results as $feed) {
			$postCount = 0;
			$feed_rule = $feed->feed_rule;
			$feed_rule = unserialize($feed_rule);

			$metas = $feed->feed_meta;
			$feed_meta = unserialize($metas);
			$positivekeyword = '';
			$negativekeyword = '';
			if(isset($feed_meta['positive_word'])) {
				$positivekeyword = $feed_meta['positive_word'];
			}
			if(isset($feed_meta['negative_keyword'])) {
				$negativekeyword = $feed_meta['negative_keyword'];
			}
			$getContentType = $feed_rule['rule1_type'];
			$contentClass = $feed_rule['rule1_type_value'];
			$time = current_time('timestamp');
			$time = date('H:i',$time);
			$hasTime = false;
			$scheduledDates = unserialize($feed->scheduled_date);
			$postTime = '';
			$skipFeed = false;
			$skipFeedImg = false;
			$elevaTitle = '';
			if(isset($feed_meta['change_title'])) {
				$elevaTitle = $feed_meta['change_title'];
				/* if(strpos($elevaTitle,'[title_post]') !== false) {
					$elevaTitle = explode('[title_post]',$elevaTitle);
					if(empty($elevaTitle[0]) && empty($elevaTitle[1])) {
						$elevaTitle = '';
					}
				} */
			}

			$skipWithoutImages = '';
			$removePostImages = '';
			if(isset($feed_meta['skip_without_image'])) {
				if($feed_meta['skip_without_image'] == "1") {
					$skipWithoutImages = true;
				}
			}
			if(isset($feed_meta['remove_images'])) {
				if($feed_meta['remove_images'] == "1") {
					$removePostImages = true;
				}
			}
			$post_tags = '';
			$postTags = '';
			if(isset($feed_meta['tags'])) {
				$post_tags = $feed_meta['tags'];
				if(!empty($post_tags)) {
					$postTags = explode(',',$post_tags);
				}
			}

			$imagesUrls = '';
			if(isset($feed_meta['images_url'])) {
				$imagesUrls = unserialize($feed_meta['images_url']);
			}

			foreach($scheduledDates as $d) {
				if(strpos($d,$today) !== false) {
					$stime = date('H:i',strtotime($d));
					if ($stime != "00:00" && preg_match('/^\d{2}:\d{2}$/', $stime)) {
						$hasTime = true;
						$postTime = $stime;
					} else {
						$hasTime = false;
						$postTime = '';
					}
				}
			}

			if(!empty($postTime)) {
				fwrite($myfile,"\n Current post has scheduled time.");
				if($postTime == $time) {
					fwrite($myfile,"\n Current time matched with scheduled time.");
					$matchedTime = true;
				}
				else {
					$matchedTime = false;
				}
			}
			else {
				$matchedTime = true;
			}

			if($matchedTime) {
				$post_category = $feed->post_category;
				$feed_rule = $feed->feed_rule;

				$feed_url = $feed->feed_url;

				$feedData = getElevaFeed($feed_url);

				if($feedData) {
					foreach($feedData as $explorefeed) {

						//var_dump($explorefeed->link);

						$linkContent = '';
						$postContent = '';
						$linkData = '';

						$linkData = wp_remote_request($explorefeed->link);

						if ( is_wp_error( $linkData ) ) {
							$error_message = $linkData->get_error_message();
							wp_die($error_message." at line number 170");
						}

						$linkContent = wp_remote_retrieve_body($linkData);

						$linkContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $linkContent);
						//$linkContent = preg_replace('#<a (.*?)>(.*?)</a>#is', '', $linkContent);

						//echo htmlentities($linkContent);
						$skipFeed = false;
						$skipFeedImg = false;
						$dom = new DOMDocument();
						@$dom->loadHTML(mb_convert_encoding($linkContent, 'HTML-ENTITIES', 'UTF-8'));
						$xpath = new DOMXPath($dom);
						$postTitle = $explorefeed->title;
						$pTitle = $explorefeed->title;
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

						if(!empty($elevaTitle)) {
							//if(isset($elevaTitle[0])) {
							$postTitle = $elevaTitle.$postTitle;
							//}
							/* if(isset($elevaTitle[1])) {
								$postTitle = $postTitle.$elevaTitle[1];
							} */
						}
						if(!empty($positivekeyword)) {
							if(strpos($positivekeyword,',') !== false) {
								$positivekeyword = explode(',',$positivekeyword);
							}
							else {
								$positivekeyword = array($positivekeyword);
							}
							if(positivekeyword($postTitle,$positivekeyword) || positivekeyword($postContent,$positivekeyword)) {
								$skipFeed = false;
							}
							else {
								$skipFeed = true;
							}
						}
						else {
							$skipFeed = false;
						}
						if(!empty($negativekeyword)) {
							if(strpos($negativekeyword,',') !== false) {
								$negativekeyword = explode(',',$negativekeyword);
							}
							else {
								$negativekeyword = array($negativekeyword);
							}
							if(negativekeyword($postTitle,$negativekeyword) || negativekeyword($postContent,$negativekeyword)){
								$skipFeed = false;
							}else {
								$skipFeed = true;
							}
						}
						else {
							$skipFeed = false;
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
						//$postContent .= elevaweb_add_image_to_post( $imagesUrls );

						if($removePostImages != true){
							if (strpos($feed_url, 'Endeavor') !== false || strpos($feed_url, 'CBN Curitiba') !== false) {
								if(!empty($explorefeed->link)){
									$fea_image = elevaweb_get_og_image_from_url( $explorefeed->link );
									if( $fea_image ){
										//Elevaweb_Set_Featured_Image(urldecode( $fea_image ), $post_id);
										$i = elevaweb_add_image_to_post( array( urldecode( $fea_image ) ) );
										$postContent = $i.$postContent;
									}
								}
							}
						}


						$postContent .= "<p><a href='".$explorefeed->link."' rel='nofollow'>".__('Click here to view full post','elevaweb')."</a></p>";

						if(!empty($postTitle) && !empty($postContent) && $skipFeedImg === false && $skipFeed === false && strlen($postContent) > 2000) {

							if(is_object($postTitle)) {
								$key = 0;
								$postTitle = (array) $postTitle;
								$postTitle = $postTitle[0];
							}
							$new_title = utf8_decode( $pTitle );
							$post_id = 0;
							$args = array(
								'post_author' => 1,
								'post_title' => $postTitle,
								'post_content' => $postContent,
								'post_name' => sanitize_title( $new_title ),
								'post_status' => 'publish',
								'post_type' => 'post'
							);

						$post = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_name = '".sanitize_title( $new_title )."' AND post_type = 'post'" );

						if(!$post) {
								$post_id = wp_insert_post($args);

								//Facebook Share Code Starts
								if($post_id){

									update_post_meta($post_id,'custom_canonical_url',"$explorefeed->link");

									$results_fb = $wpdb->get_results("SELECT user_name,social_name,ID FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook' AND status = 1");
									if($results_fb){
										foreach($results_fb as $result_fb){
											if(isset($_POST['fb_'.$result_fb->ID]) && $_POST['fb_'.$result_fb->ID] == $result_fb->ID){
												update_post_meta($post_id,'elevaweb_facebook_'.$result_fb->ID,1);
												if($result_fb->access_token != ""){
													elevaweb_share_facebook(app_id,app_secret,$result_fb->access_token,$post_id,$result_fb->type,$result_fb->social_id);
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
								$newScheduledDates = array();
								foreach($scheduledDates as $d) {
									if(strpos($d,$today) !== false) {
										$d = explode(" ",$d);
										if(isset($d[0])) {
											$day = strtolower($dayName);
											$nextDay = strtotime('next '.$day);
											$date = date('Y-m-d',$nextDay);
											$d[0] = $date;
										}
										$d = implode(" ",$d);
									}
									$newScheduledDates[] = $d;
								}
								if(count($newScheduledDates)) {
									/*$newScheduledDates = serialize($newScheduledDates);
									$SQL = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET scheduled_date = '{$newScheduledDates}' WHERE ID = {$feed->ID}";
									$wpdb->query($SQL);*/
								}

								$published_date = get_the_date('Y-m-d H:i:s',$post_id);

								// LOG the post
								$SQL = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post_log(feed,feed_url,post_title,post_id,original_post_url,feed_category,post_category,published_date) VALUES('{$feed->feed}','{$feed->feed_url}','{$postTitle}','{$post_id}','{$explorefeed->link}','{$feed->feed_category}','{$feed->post_category}','{$published_date}')";
								$wpdb->query($SQL);

								$postCount++;

								break;
							}
							else {
								continue;
							}
						}
					}
					if($postCount == 0 && !empty($postTime)) {
						$newScheduledDates = array();
						foreach($scheduledDates as $d) {
							if(strpos($d,$today) !== false) {
								$d = explode(" ",$d);
								if(isset($d[0])) {
									$day = strtolower($dayName);
									$nextDay = strtotime('next '.$day);
									$date = date('Y-m-d',$nextDay);
									$d[0] = $date;
								}
								$d = implode(" ",$d);
							}
							$newScheduledDates[] = $d;
						}
						if(count($newScheduledDates)) {
							$newScheduledDates = serialize($newScheduledDates);
							$SQL = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET scheduled_date = '{$newScheduledDates}' WHERE ID = {$feed->ID}";
							$wpdb->query($SQL);
						}
					}
				}
			}
		}
		//die;
	}
	fwrite($myfile,"\n\n");
	//fclose($f);
	fclose($myfile);
}

add_action('init','init_my_cron');

function init_my_cron() {
	if (! wp_next_scheduled ( 'elevaweb_cron_event' )) {
		wp_schedule_event(time(), '1min', 'elevaweb_cron_event');
    }
    /*if(strpos($_SERVER['REQUEST_URI'],'?page=eleva') !== false) {
		do_this_every_minute();
	}*/
}

function elevaweb_install() {
	global $wpdb;

	if (! wp_next_scheduled ( 'elevaweb_cron_event' )) {
		wp_schedule_event(time(), '1min', 'elevaweb_cron_event');
    }

	$prefix = $wpdb->prefix;
	$sql = "CREATE TABLE IF NOT EXISTS {$prefix}elevaweb_scheduled_post (
		`ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`feed` varchar(100) NOT NULL,
		`feed_url` varchar(255) NOT NULL,
		`feed_rule` text,
		`feed_meta` text,
		`feed_category` varchar(100) NOT NULL,
		`post_category` varchar(100) NOT NULL,
		`scheduled_date` text NOT NULL,
		`status` int(1) NOT NULL DEFAULT '1',
		`running` int(1) NOT NULL DEFAULT '1'
	)";
	$wpdb->query($sql);
	$sql = "CREATE TABLE IF NOT EXISTS {$prefix}elevaweb_scheduled_post_log (
		`ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`feed` varchar(100) NOT NULL,
		`feed_url` varchar(255) NOT NULL,
		`post_title` varchar(255) NOT NULL,
		`post_id` varchar(255) NOT NULL,
		`original_post_url` varchar(255) NOT NULL,
		`feed_category` varchar(100) NOT NULL,
		`post_category` varchar(100) NOT NULL,
		`published_date` timestamp NULL DEFAULT NULL
	)";
	$wpdb->query($sql);

	$sql = "CREATE TABLE IF NOT EXISTS {$prefix}elevaweb_networks_authentication (
		`ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`application_name` varchar(100) NOT NULL,
		`application_id` varchar(255) NOT NULL,
		`application_secret` varchar(255) NOT NULL,
		`social_name` varchar(255) NOT NULL,
		`user_id` int(11) NOT NULL,
		`user_name` varchar(255) NOT NULL,
		`social_id` varchar(255) NOT NULL,
		`access_token` longtext NOT NULL,
		`type` varchar(20) NOT NULL,
		`status` int(11) NOT NULL
	)";
	$wpdb->query($sql);
}

register_activation_hook( __FILE__, 'elevaweb_install' );

add_action( 'plugins_loaded', 'elevaweb_load_textdomain' );
function elevaweb_load_textdomain() {
	$fb_data = file_get_contents( FB_DATA );
	if(!empty( $fb_data )){
		$fb_data = json_decode( $fb_data );
		define("app_id",$fb_data->application_id);
		define("app_secret",$fb_data->application_secret);
	}
	if(load_plugin_textdomain( 'elevaweb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' )){

	  // echo "Loaded";
	}else{
	  // echo "Not Loaded";
	}
}
function wp_admin_plugin_load_js() {
        wp_enqueue_script( 'elevaweb-network-js', plugins_url( 'js/jquery_elevaweb.js', __FILE__ ), array(), '1.0.0', true );
		wp_localize_script( 'elevaweb-network-js', 'elevaweb',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'admin_url' => admin_url('admin.php') ) );
}
add_action( 'admin_enqueue_scripts', 'wp_admin_plugin_load_js' );
add_action('admin_menu', 'elevaweb_plugin_create_menu');

function elevaweb_plugin_create_menu() {
	if(isset($_SESSION['elevaweb_login'])) {
		add_menu_page('Elevaweb', __('Elevaweb','elevaweb'), 'administrator', 'elevaweb', 'elevaweb_welcome' , plugins_url('images/elewaweb.png', __FILE__) );
		add_submenu_page( 'elevaweb', 'Dashboard', __('Dashboard','elevaweb'), 'manage_options', 'elevaweb', 'elevaweb_welcome',plugins_url('images/elewaweb.png', __FILE__));
		add_submenu_page( 'elevaweb', 'New Post', __('New Auto Post','elevaweb'), 'manage_options', 'eleva-new-post', 'elevawebNewPost');
		add_submenu_page( 'elevaweb', 'My Post Configuration', __('My Post Configuration','elevaweb'), 'manage_options', 'eleva-post-config', 'elevawebPostconfig');

		//Add Facebook Menu to admin
		add_submenu_page( 'elevaweb_hidden', 'Networks', __('Networks','elevaweb'), 'manage_options', 'eleva-network', 'elevaweb_network');

		add_submenu_page( 'elevaweb', 'Tools', __('Tools','elevaweb'), 'manage_options', 'eleva-tools', 'elevawebTool');
		add_submenu_page( 'elevaweb', 'Profile', __('Profile','elevaweb'), 'manage_options', 'eleva-profile', 'elevawebProfile');
		add_submenu_page( 'elevaweb', 'Log', __('Log','elevaweb'), 'manage_options', 'eleva-log', 'elevawebLog');
		add_submenu_page( 'elevaweb', 'Change Password', __('Change Password','elevaweb'), 'manage_options', 'eleva-changepassword', 'elevawebChangePassword');
		add_submenu_page( 'elevaweb', 'Logout', __('Logout','elevaweb'), 'manage_options', 'eleva-logout', 'elevaweb_logout');
	}
	else {
		//create new top-level menu
		add_menu_page('Elevaweb', __('Elevaweb','elevaweb'), 'administrator', 'elevaweb', 'elevaweb_auth' , plugins_url('images/elewaweb.png', __FILE__) );
		add_submenu_page( 'elevaweb', 'Login', __('Login','elevaweb'), 'manage_options', 'elevaweb', 'elevaweb_plugin_login_page');
		add_submenu_page( 'elevaweb', 'Register', __('Register','elevaweb'), 'manage_options', 'elevaweb-register', 'elevaweb_register');
		add_submenu_page( 'elevaweb', 'Forgot Password', __('Forgot Password','elevaweb'), 'manage_options', 'elevaweb-forgotpassword', 'elevawebForgotPassword');
	}
}
/***Network Code Start***/
function elevaweb_network(){
	include( 'elevaweb_network.php' );
}
function elevaweb_logout(){
	include( 'elevaweb_logout.php' );
}
/***Network Code Ends***/
function elevaweb_plugin_login_page() {
	global $pluginDir;
	 wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
	 include $pluginDir."templates/loading.php";
	 /* if(isset($_POST['math'])){
		 elevaweb_auth();
	 } */
	?>

<div class="form-box login-box">
  <div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
  <form action="<?php echo admin_url().'admin.php?page=elevaweb';?>" method="post" name="Login_Form" class="form-sign">
    <?php if($_GET['message']){?>
    <div class="massage"><?php printf( __( '%s', 'elevaweb' ),$_GET['message']);?></div>
    <?php } ?>
    <div class="elevaweb-row">
      <input type="text" class="elevaweb-input" name="email_id" placeholder="<?php echo  __('E-mail','elevaweb') ?>" required="" />
      </div>
      <div class="elevaweb-row">
      <input type="password" class="elevaweb-input" name="password" placeholder="<?php echo  __('Password','elevaweb') ?>" required=""/>
    </div>
    <div class="elevaweb-row">
      <div class="elevaweb-group">
      <button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php echo  __('Login','elevaweb') ?></button>
      <input type="hidden" name="math" value="login"/>
      <input type="hidden" name="site_address" value="<?php echo site_url(); ?>"/>
      <!--<input type="hidden" name="website" value="<?php echo site_url();?>">-->
      <a href="<?php echo admin_url().'admin.php?page=elevaweb-register';?>" class="btn-elevaweb-register"><?php echo  __('Create Account','elevaweb') ?></a>
      </div>
      </div>
  </form>
</div>
<div class="form-box-footer"> <?php echo  __('Forgot your password? Click here to ','elevaweb') ?><a href="<?php echo admin_url().'admin.php?page=elevaweb-forgotpassword';?>" class="elevaweb-link"><?php echo __('Recover','elevaweb') ?></a> <br />
  <?php echo  __('You wil receive an e-mail with new password','elevaweb') ?> </div>
<?php }

function elevawebFindImages( $string = '' ) {
	if(!empty($string)) {
		$hrefs = array();
		$dom = new DOMDocument();
		@$dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$tags = $dom->getElementsByTagName('img');
		return $tags->length;
	}
	return false;
}

function elevawebRemoveAnchor( $string = '' ) {
	if(!empty($string)) {
		$hrefs = array();
		$dom = new DOMDocument();
		@$dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$tags = $dom->getElementsByTagName('a');
		$removeElement = array();
		foreach ($tags as $tag) {
			$removeElement[] = $tag;
		}
		if(count($removeElement) && $removeElement) {
			foreach($removeElement as $node) {
				$html = get_inner_html($node);

				$divnode = $dom->createElement("span");
				$node->removeAttribute('href');
				$divnode->nodeValue = $node->nodeValue;
				$divnode->setAttribute('class','a-replacement');

				appendHTML($divnode,$html);

				$node->parentNode->appendChild($divnode);
				$node->parentNode->removeChild($node);
			}
		}
		$string = $dom->saveHTML();
	}
	return $string;
}

function appendHTML(DOMNode $parent, $source) {
    $tmpDoc = new DOMDocument();
    @$tmpDoc->loadHTML(mb_convert_encoding($source, 'HTML-ENTITIES', 'UTF-8'));
    foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
        $node = $parent->ownerDocument->importNode($node);
        $parent->appendChild($node);
    }
}

function uploadImagesToWp( $string = '',$post_id = 0 ) {
	$wp_upload_dir = wp_upload_dir();
	$uploadPath = $wp_upload_dir['path'];
	if(!empty($string)) {
		$hrefs = array();
		$dom = new DOMDocument();
		@$dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$tags = $dom->getElementsByTagName('img');
		$removeElement = array();
		foreach ($tags as $tag) {
			$hrefs[] =  $tag->getAttribute('src');

			$leadImage = $tag->getAttribute('src');

			if(!empty($leadImage)) {
				list($width, $height) = @getimagesize($leadImage);
				if($width > 500) {
					//$leadImage = "http://i1.wp.com/cbncuritiba.com/wp-content/uploads/2017/06/00200669.jpg";

					// Required file for function "media_sideload_image()"
					require_once(ABSPATH . 'wp-admin/includes/media.php');
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');


						$image = media_sideload_image($leadImage,$post_id);

						// then find the last image added to the post attachments
						$attachments = get_posts(array(
							'numberposts' => '1',
							'post_parent' => $post_id,
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'order' => 'DESC'
						));

						$wpImageUrl = wp_get_attachment_url($attachments[0]->ID);
						$tag->setAttribute('src',$wpImageUrl);
						$tag->setAttribute('srcset',$wpImageUrl);
				}
				else {
					$removeElement[] = $tag;
				}
			}
			else {
				$removeElement[] = $tag;
			}
		}
		if($removeElement && count($removeElement)) {
			foreach($removeElement as $node){
				$node->parentNode->removeChild($node);
			}
		}
		$string = $dom->saveHTML();
	}
	return $string;
}
function elevaweb_add_image_to_post( $imageSource = '',$post_id = 0 ){
	$wp_upload_dir = wp_upload_dir();
	$uploadPath = $wp_upload_dir['path'];
	$string = '';
	if(is_array($imageSource)) {
		foreach ($imageSource as $src_image) {
			
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			if(!empty($src_image)) {
				$image = media_sideload_image($src_image,$post_id);

				// then find the last image added to the post attachments
				$attachments = get_posts(array(
					'numberposts' => '1',
					'post_parent' => $post_id,
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'order' => 'DESC'
				));

				$wpImageUrl = wp_get_attachment_url($attachments[0]->ID);
				$string .= '<img src="'.$wpImageUrl.'" />';
			}
		}
	}
	return $string;
}
function replaceUploadImagesToWp( $string = '',$imageSource = '',$post_id = 0 ) {

	$wp_upload_dir = wp_upload_dir();
	$uploadPath = $wp_upload_dir['path'];
	if(!empty($string)) {
		$hrefs = array();
		$dom = new DOMDocument();
		@$dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$tags = $dom->getElementsByTagName('img');
		$i = 0;
		foreach ($tags as $tag) {
			if(isset($imageSource[$i])) {
				$leadImage = $imageSource[$i];

				//$leadImage = "http://i1.wp.com/cbncuritiba.com/wp-content/uploads/2017/06/00200669.jpg";

				// Required file for function "media_sideload_image()"
				require_once(ABSPATH . 'wp-admin/includes/media.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');

				if(!empty($leadImage)) {
					$image = media_sideload_image($leadImage,$post_id);

					// then find the last image added to the post attachments
					$attachments = get_posts(array(
						'numberposts' => '1',
						'post_parent' => $post_id,
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'order' => 'DESC'
					));

					$wpImageUrl = wp_get_attachment_url($attachments[0]->ID);

					$tag->setAttribute('src',$wpImageUrl);
					$tag->setAttribute('srcset',$wpImageUrl);
					$i++;
				}
				//$string = $dom->saveHTML();
			}
		}
		$string = $dom->saveHTML();
		//debug_me($string,true);
	}
	return $string;
}

function removeImages( $string = '' ) {
	if(!empty($string)) {
		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput       = true;
		@$dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$tags = $dom->getElementsByTagName('img');
		foreach ($tags as $tag) {
			$tag->parentNode->removeChild($tag);
		}
		$string = $dom->saveHTML();
		return $string;
	}
	return false;
}

function debug_me($content,$die = false) {
	echo "<pre>";
	print_r($content);
	echo "</pre>";
	if($die) {
		die;
	}
}

function get_inner_html( $node ) {
    $innerHTML= '';
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveHTML( $child );
    }
    return $innerHTML;
}

add_action('init','init_elevaweb_actions',15);

function init_elevaweb_actions() {
	ob_start();
	if(!session_id()) {
		session_start();
	}
}

function elevaweb_message($message = '') {
	$message = $_SESSION['elevaweb_message'];
	if(!empty($message)) {
		echo '<div class="updated fade"><p>'.$message.'</p></div>';
		unset($_SESSION['elevaweb_message']);
	}
}

function getElevaFeed($feed_url) {

	if(strpos($feed_url,'http') === false) {
		$feed_url = "http://".$feed_url;
	}

    $content = wp_remote_request($feed_url);

    if ( is_wp_error( $content ) ) {
		$error_message = $content->get_error_message();
		wp_die($error_message." at line number 584");
		//wp_die('Oops! Something went wrong. Please try again. => 583');
	}

    $body = wp_remote_retrieve_body($content);
    //$content = htmlentities($body);

    $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $body);


    $body = stripInvalidXml($body);

    $x = '';

	try {
		$x = new SimpleXmlElement($body);
		$feeData = array();
		foreach($x->channel->item as $entry) {
			$feedData[] = $entry;
		}
		return $feedData;
	}catch (Exception $e){
		return false;
	}
}

//Find Positive keyword
function positivekeyword($string ,$keyword){
	//$s = mb_convert_encoding($string, 'utf-8', 'HTML-ENTITIES');
	$count = 0;
    foreach ($keyword as $url) {
		if (strpos($string, $url) !== FALSE){
			$count++;
		}
    }
	if($count == count( $keyword )){
		return true;
	}else{
		return false;
	}
}

//Find Positive keyword
function negativekeyword($string ,$keyword){
	//$s = mb_convert_encoding($string, 'utf-8', 'HTML-ENTITIES');
	$count = 0;
    foreach ($keyword as $url) {
		if (strpos($string, $url) !== FALSE){
			$count++;
		}
    }
	if($count == count( $keyword )){
		return true;
	}else{
		return false;
	}
}

function stripContent($string,$type,$element) {
	if(!empty($string) && !empty($type) && !empty($element)) {
		$dom = new DOMDocument();
		@$dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$tags = '';
		$content = '';
		if($type == "0") {
			$tags = $dom->getElementById($element);
		}
		else if($type == "1") {
			$classname = $element;
			$tags = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
		}
		if($tags) {
			foreach($tags as $tag) {
				$content .= get_inner_html($tag);
			}
			if(!empty($content)) {
				return $content;
			}
		}
	}
	return false;
}

add_filter( 'http_request_timeout', 'wp9838c_timeout_extend' );

function wp9838c_timeout_extend( $time ) {
    return 1800;
}

/**
 * Removes invalid XML
 *
 * @access public
 * @param string $value
 * @return string
 */
function stripInvalidXml($value)
{
    $ret = "";
    $current;
    if (empty($value))
    {
        return $ret;
    }

    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($value{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
    return $ret;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );

function add_action_links ( $links ) {
	if(isset($_SESSION['elevaweb_login'])) {
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=elevaweb' ) . '">'.__("Settings","elevaweb").'</a>',
		);
	}else{
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=elevaweb-register' ) . '">'.__("Settings","elevaweb").'</a>',
		);
	}
	return array_merge( $links, $mylinks );
}
add_action('admin_head', 'admin_elevaweb_css');
function admin_elevaweb_css() {
  echo '<style>
    .toplevel_page_elevaweb img{ padding: 3px 0 0 !important; }
  </style>';
}
include("myposts.php");
include("log.php");
include("elevaweb_include.php");
include("elevaweb_actions.php");
?>
