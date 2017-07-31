<?php
include('wp-config.php');

$feed_url = 'https://criacaode.site/category/blog/marketing/feed/';

$feedData = getElevaFeed($feed_url);
foreach( $feedData as $feed){
	$linkData = wp_remote_request($feed->link);
	$linkContent = wp_remote_retrieve_body($linkData);
	print_r( $linkContent );
}