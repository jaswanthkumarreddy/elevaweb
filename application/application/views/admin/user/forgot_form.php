<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php echo form_open('', array('role'=>'form')); ?>

    <div class="row">
	<div class="col-md-12">
		<div class="page-header">
        </div>
		</div>
		<div class="col-md-8">
            <h2>Lorem Ipsum</h2>
			<h3 class="martop20">Consulting</h3>
			<p>is a field we focuses on advising businesses on how best to use information technology to meet their business objectives.</p>
			<h3>Analysis</h3>
			<p>is the first step towards identifying your needs by breaking them into smaller parts for better understanding.</p>
			<h3>Strategy</h3>
			<p>is important to understand the high level plan to achieve one or more goals under conditions of uncertainty </p>
			<h3>Services</h3>
			<p>Service Oriented Architecture Software as a Service Man power provider Services as a Library.</p>
        </div>
		<div class="col-md-4">
		<div class="login-panel panel panel-default">
				<div class="panel-heading">Forgot Password</div>
				<div class="panel-body">
        <div class="form-group <?php echo form_error('email') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input email'), 'email', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'type'=>'email')); ?>
        </div>
		<?php // buttons ?>
    <div class="pull-right">
        <a class="btn btn-default" href="<?php echo $cancel_url; ?>"><?php echo lang('core button cancel'); ?></a>
        <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?php echo lang('users button reset_password'); ?></button>
    </div>
		</div>
		</div>
		</div>
    </div>

    

<?php echo form_close(); ?>
