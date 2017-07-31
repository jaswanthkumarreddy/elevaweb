<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open('', array('role'=>'form')); ?>
<?php // hidden id ?>
<?php if (isset($user_id)) : ?>
<?php echo form_hidden('id', $user_id); ?>
<?php endif; ?>

<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('first_name') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input first_name'), 'first_name', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'first_name', 'value'=>set_value('first_name', (isset($user['first_name']) ? $user['first_name'] : '')), 'class'=>'form-control')); ?> </div>
  <div class="form-group col-sm-6<?php echo form_error('last_name') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input last_name'), 'last_name', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'last_name', 'value'=>set_value('last_name', (isset($user['last_name']) ? $user['last_name'] : '')), 'class'=>'form-control')); ?> </div>
</div>
<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('username') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input username'), 'username', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'username', 'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control')); ?> </div>
  <div class="form-group col-sm-6<?php echo form_error('email_id') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input email'), 'email_id', array('class'=>'control-label')); ?> <?php echo form_input(array('name'=>'email_id', 'value'=>set_value('email_id', (isset($user['email_id']) ? $user['email_id'] : '')), 'class'=>'form-control')); ?> </div>
</div>
<div class="row">
  <?php // password ?>
  <div class="form-group col-sm-6<?php echo form_error('password') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input password'), 'password', array('class'=>'control-label')); ?> <span class="">(Minimum 8 Charachers)</span>
    <?php if ($password_required) : ?>
    <span class="required">*</span>
    <?php endif; ?>
    <?php echo form_password(array('name'=>'password', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?> </div>
  <?php // password repeat ?>
  <div class="form-group col-sm-6<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input password_repeat'), 'password_repeat', array('class'=>'control-label')); ?>
    <?php if ($password_required) : ?>
    <span class="required">*</span>
    <?php endif; ?>
    <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?> </div>
  <?php if ( ! $password_required) : ?>
  <span class="help-block" style="padding-left: 15px;"><br />
  <?php echo lang('users help passwords'); ?></span>
  <?php endif; ?>
</div>
<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('phone_no_work') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input phone no work'), 'phone_no_work', array('class'=>'control-label')); ?> <?php echo form_input(array('name'=>'phone_no_work', 'value'=>set_value('phone_no_work', (isset($user['phone_no_work']) ? $user['phone_no_work'] : '')), 'class'=>'form-control')); ?> </div>
  <div class="form-group col-sm-6<?php echo form_error('phone_no') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input phone no'), 'phone_no', array('class'=>'control-label')); ?> <?php echo form_input(array('name'=>'phone_no', 'value'=>set_value('phone_no', (isset($user['phone_no']) ? $user['phone_no'] : '')), 'class'=>'form-control')); ?> </div>
</div>
<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('mobile_no') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input mobile_no'), 'mobile_no', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'mobile_no', 'value'=>set_value('mobile_no', (isset($user['mobile_no']) ? $user['mobile_no'] : '')), 'class'=>'form-control')); ?> </div>
  <div class="form-group col-sm-6<?php echo form_error('registration_date') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input registration_date'), 'registration_date', array('class'=>'control-label')); ?>
    <div class='input-group date'> <?php echo form_input(array('name'=>'registration_date', 'value'=>set_value('registration_date', (isset($user['registration_date']) ? date_format(date_create($user['registration_date']),'d/m/Y') : '')), 'class'=>'form-control')); ?> <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> </div>
  </div>
</div>
<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('access_level_id') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input access_level'), 'access_level_id', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_dropdown('access_level_id', $accessLevel , set_value('access_level_id', (isset($user['access_level_id']) ? $user['access_level_id'] : '')), 'class="form-control"'); ?> </div>
  <div class="form-group col-sm-6"> <?php echo form_label(lang('users input note'), 'note', array('class'=>'control-label')); ?> <?php echo form_textarea(array('name'=>'note', 'value'=>set_value('note', (isset($user['note']) ? $user['note'] : '')), 'class'=>'form-control','rows'=>2)); ?> </div>
</div>
<?php 
							// Add is_admin
							form_hidden("is_admin","1");
						?>
<div class="row radio-block">
  <?php // is_active ?>
  <div class="form-group col-sm-6<?php echo form_error('is_active') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('users input is_active'), '', array('class'=>'control-label')); ?> <span class="required">*</span>
    <div class="radio">
      <label> <?php echo form_radio(array('name'=>'is_active', 'id'=>'radio-is_active-1', 'value'=>'1', 'checked'=>(( ! isset($user['is_active']) OR (isset($user['is_active']) && (int)$user['is_active'] == 1) OR $user['user_id'] == 1) ? 'checked' : FALSE))); ?> <?php echo lang('admin input active'); ?> </label>
    </div>
    <?php if ( ! $user['user_id'] OR $user['user_id'] > 1) : ?>
    <div class="radio">
      <label> <?php echo form_radio(array('name'=>'is_active', 'id'=>'radio-is_active-2', 'value'=>'0', 'checked'=>((isset($user['is_active']) && (int)$user['is_active'] == 0) ? 'checked' : FALSE))); ?> <?php echo lang('admin input inactive'); ?> </label>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php // buttons ?>
<div class="row">
  <div class="col-md-12"> <a class="btn btn-default" href="<?php echo $cancel_url; ?>" data-toggle="tooltip" data-original-title="Cancel"> <?php echo lang('core button cancel'); ?> </a>
    <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> <?php echo lang('core button save'); ?> </button>
  </div>
</div>
<?php echo form_close(); ?>