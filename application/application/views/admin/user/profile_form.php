<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php echo form_open('', array('role'=>'form')); ?>
	
	 <div class="row">
    	<div class="col-md-12">
		<div class="page-header">
        </div>
		</div>
		<div class="col-md-6">
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
		<div class="col-md-6">
				
    <div class="row">
    	<div class="login-panel panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body"> 
		<div class="row">
        <div class="form-group col-md-6<?php echo form_error('username') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input username'), 'username', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php echo form_input(array('name'=>'username', 'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control')); ?>
        </div>
		
		<div class="form-group col-md-6<?php echo form_error('email') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input email'), 'email', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'type'=>'email')); ?>
        </div>
		</div>
		<div class="row">
		<?php // first name ?>
        <div class="form-group col-md-6<?php echo form_error('first_name') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input first_name'), 'first_name', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php echo form_input(array('name'=>'first_name', 'value'=>set_value('first_name', (isset($user['first_name']) ? $user['first_name'] : '')), 'class'=>'form-control')); ?>
        </div>

        <?php // last name ?>
        <div class="form-group col-md-6<?php echo form_error('last_name') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input last_name'), 'last_name', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php echo form_input(array('name'=>'last_name', 'value'=>set_value('last_name', (isset($user['last_name']) ? $user['last_name'] : '')), 'class'=>'form-control')); ?>
        </div>
		</div>
	<div class="row">
    	 
         <div class="form-group col-md-6<?php echo form_error('mobile_no') ? ' has-error' : ''; ?>">
			<?php echo form_label(lang('users input mobile_no'), 'mobile_no', array('class'=>'control-label')); ?>
				<span class="required">*</span>
				<?php echo form_input(array('name'=>'mobile_no', 'value'=>set_value('mobile_no', (isset($user['mobile_no']) ? $user['mobile_no'] : '')), 'class'=>'form-control')); ?>
		</div>
		
		<div class="form-group col-md-6<?php echo form_error('client_id') ? ' has-error' : ''; ?>">
			<?php echo form_label(lang('users input client_id'), 'client_id', array('class'=>'control-label')); ?>

				<span class="required">*</span>
				<?php echo form_dropdown('client_id', $clientList , set_value('client_id', (isset($user['client_id']) ? $user['client_id'] : '')), 'class="form-control"'); ?>
		</div>
		
    </div>
	<div class="row">
        <?php // password ?>
        <div class="form-group col-md-6<?php echo form_error('password') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input password'), 'password', array('class'=>'control-label')); ?>
            <?php if ($password_required) : ?><span class="required">*</span><?php endif; ?>
            <?php echo form_password(array('name'=>'password', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
        </div>

        <?php // password repeat ?>
        <div class="form-group col-md-6<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('users input password_repeat'), 'password_repeat', array('class'=>'control-label')); ?>
            <?php if ($password_required) : ?><span class="required">*</span><?php endif; ?>
            <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
        </div>
        <?php if ( ! $password_required) : ?>
            <span class="help-block"><br /><?php echo lang('users help passwords'); ?></span>
        <?php endif; ?>
    </div>
	    <?php // buttons ?>
    <div class="pull-right">
        <a class="btn btn-default" href="<?php echo $cancel_url; ?>"><?php echo lang('core button cancel'); ?></a>
        <?php if ($this->session->userdata('logged_in')) : ?>
            <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> <?php echo lang('core button save'); ?></button>
        <?php else : ?>
            <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?php echo lang('users button register'); ?></button>
        <?php endif; ?>
    </div>
    
	
		
		
		</div>
    </div>
	
		</div>
    </div>
	
        
    </div>
	
	
 



<?php echo form_close(); ?>
