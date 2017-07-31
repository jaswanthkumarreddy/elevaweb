<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $page_title; ?> - <?php echo $this->settings->site_name; ?></title>
 
<link href="<?php echo base_url('themes/admin/css/bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('themes/admin/css/datepicker3.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('themes/admin/css/styles.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('themes/admin/css/font-awesome.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('themes/admin/css/font-awesome.min.css'); ?>" rel="stylesheet">


<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	 
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#"><span>Elevaweb</span></a>
				<!--<ul class="user-menu">
				
					<li class="dropdown pull-right">
						<a href="admin/user/register">Sign Up Now</a>
					</li>
					
				</ul>-->
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
	<!--<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">  
		<?php // Nav bar left ?>
	<ul class="arrow martop20">
		<li>Demo 1</li>
		<li>Demo 2</li>
		<li>Demo 3</li>
		<li>Demo 4</li>
		<li>Demo 5</li>
	</ul> 
		
		
		<div class="attribution">&copy; 2016-<?php echo date('Y'); ?> Technologies, Inc.</div>
	</div><!--/.sidebar-->
	
	<div class="col-sm-12 col-sm-offset-3 col-lg-10 col-lg-offset-1 main">
	 <?php // Main body ?>
    <div class="" role="main">

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
	<div class="login-footer">© 2016-2017  YouMeWebs</div>
	
	<!--Icons-->
	<script src="<?php echo base_url('themes/admin//js/lumino.glyphs.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/jquery-1.11.1.min.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/chart.min.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/chart-data.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/easypiechart.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/easypiechart-data.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin//js/bootstrap-datepicker.js'); ?>"></script>
	<script>
		$('#calendar').datepicker({
		});

		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	
		$(document).ready(function() {
			 setTimeout(function(){ $(".alert-dismissable").hide('slow'); }, 5000);
		});
	
	
	</script>	
</body>

</html>
