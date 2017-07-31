<?php
if(isset($_SESSION['elevaweb_login']) && $_SESSION['elevaweb_login'] == 1) {
	unset($_SESSION['elevaweb_login']);
	wp_safe_redirect(admin_url().'admin.php?page=elevaweb');
}