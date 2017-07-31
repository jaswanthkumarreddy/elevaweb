<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $page_title; ?> - <?php echo $this->settings->site_name; ?></title>   
<?php // CSS files ?>
    <?php if (isset($css_files) && is_array($css_files)) : ?>
        <?php foreach ($css_files as $css) : ?>
            <?php if ( ! is_null($css)) : ?>
                <link rel="stylesheet" href="<?php echo $css; ?>?v=<?php echo $this->settings->site_version; ?>"><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
<input id="ajaxBaseURL" name="ajaxBaseURL" value="<?php echo base_url('ajax'); ?>" />
	<nav class="navbar navbar-ptk navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><span>Elevaweb</span></a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $this->session->userdata['logged_in']['username']; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
						 
							<li><a href="<?php echo base_url('/admin/settings'); ?>"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg> Settings</a></li>
							<li><a href="<?php echo base_url('/logout'); ?>"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">  
		<?php // Nav bar left ?>
		<ul class="nav menu">
		<li class="<?php echo (uri_string() == 'admin' OR uri_string() == 'admin/dashboard') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin'); ?>"><?php echo lang('admin button dashboard'); ?></a></li>
		 
 
		<!-- <li class="<?php echo (uri_string() == 'admin/users') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/users'); ?>"><?php echo lang('admin menu user'); ?></a></li> -->

		<li class="<?php echo (uri_string() == 'admin/customers') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/customers'); ?>"><?php echo lang('admin menu customer'); ?></a></li> 
        
<!--         <li class="<?php echo (uri_string() == 'admin/menus/add') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/menus/add'); ?>"><?php echo lang('admin menu mennu'); ?></a></li> 
 -->		
		<li class="<?php echo (uri_string() == 'admin/menus/services') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/services'); ?>">Services</a></li> 	
	</ul>
		
		
		<div class="attribution">&copy; 2016-<?php echo date('Y'); ?> YouMeWebs.</div>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

	 <?php // Main body ?>
    <div class="theme-showcase" role="main">

        <?php // Page title ?>
        <div class="page-header">
            <h3 class="marbtm0"><?php echo $page_header; ?></h3>
        </div>

        <?php // System messages ?>
        <?php if ($this->session->flashdata('message')) : ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <?php elseif ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php elseif (validation_errors()) : ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo validation_errors(); ?>
            </div>
        <?php elseif ($this->error) : ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->error; ?>
            </div>
        <?php endif; ?>

        <?php // Main content ?>
        <?php echo $content; ?>

    </div>
	
		
		 
		 
		</div><!--/.row-->
								
		 
	</div>	<!--/.main-->
	
	
	<!--Icons-->
	 
	 <?php // Javascript files ?>
    <?php if (isset($js_files) && is_array($js_files)) : ?>
        <?php foreach ($js_files as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript" src="<?php echo $js; ?>?v=<?php echo $this->settings->site_version; ?>"></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($js_files_i18n) && is_array($js_files_i18n)) : ?>
        <?php foreach ($js_files_i18n as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<div class="loader">
	<img src='<?php echo base_url("themes/admin/images/loader.gif");?>'>
</div>
</body>

</html>
